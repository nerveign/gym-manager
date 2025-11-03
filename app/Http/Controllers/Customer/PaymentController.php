<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction as MidtransTransaction;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Require Midtrans library manually
        require_once base_path('midtrans-php-master/Midtrans.php');
        
        // Set Midtrans configuration - use config() instead of env() for consistency
        \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        \Midtrans\Config::$clientKey = config('services.midtrans.client_key');
        \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('services.midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('services.midtrans.is_3ds');
        
        // Debug logging untuk memastikan config ter-load
        \Log::info('Midtrans Config Loaded', [
            'server_key_set' => !empty(\Midtrans\Config::$serverKey),
            'client_key_set' => !empty(\Midtrans\Config::$clientKey),
            'is_production' => \Midtrans\Config::$isProduction
        ]);
    }
    /**
     * Tampilkan halaman payment untuk aktivasi membership
     */
    public function show()
    {
        $user = auth()->user();
        
        // Pastikan user adalah customer
        if (!$user->isCustomer()) {
            abort(403, 'Access denied. Customer only.');
        }
        
        // Jika sudah memiliki membership aktif DAN tidak ada session payment yang sedang berlangsung
        // maka redirect ke dashboard
        if ($user->hasActiveMembership() && !session()->has('transaction_id')) {
            return redirect()->route('customer.dashboard')
                           ->with('info', 'You already have an active membership.');
        }
        
        // Data untuk payment
        $paymentData = [
            'user' => $user,
            'amount' => 200000, // Harga membership Rp 200.000
            'duration' => 30, // 30 hari
            'description' => 'Aktivasi Membership Gym - 30 Hari'
        ];
        
        return view('customer.payment.show', $paymentData);
    }
    
    /**
     * Proses pembayaran membership
     */
    public function process(Request $request)
    {
        $user = auth()->user();
        
        // Validasi input
        $request->validate([
            'payment_method' => 'required|in:midtrans,manual',
        ]);
        
        // Pastikan user adalah customer dan belum memiliki membership aktif
        if (!$user->isCustomer() || $user->hasActiveMembership()) {
            return redirect()->route('customer.dashboard')
                           ->with('error', 'Invalid payment request.');
        }

        $paymentMethod = $request->input('payment_method');

        if ($paymentMethod === 'midtrans') {
            return $this->processMidtrans($user);
        } else {
            return $this->processManual($user);
        }
    }

    /**
     * Process payment with Midtrans
     */
    private function processMidtrans($user)
    {
        try {
            // Generate unique order ID
            $orderId = 'MEMBERSHIP-' . $user->id . '-' . time();
            $amount = 200000; // Rp 200.000
            
            \Log::info('Processing Midtrans payment for user: ' . $user->id);
            
            // Parameter untuk Midtrans
            $params = array(
                'transaction_details' => array(
                    'order_id' => $orderId,
                    'gross_amount' => $amount,
                ),
                'customer_details' => array(
                    'first_name' => explode(' ', $user->name)[0],
                    'last_name' => implode(' ', array_slice(explode(' ', $user->name), 1)) ?: '',
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'billing_address' => array(
                        'address' => $user->address,
                    ),
                ),
                'item_details' => array(
                    array(
                        'id' => 'MEMBERSHIP-30D',
                        'price' => $amount,
                        'quantity' => 1,
                        'name' => 'Membership Gym - 30 Hari'
                    )
                ),
            );

            \Log::info('Midtrans params: ' . json_encode($params));
            
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            
            \Log::info('Snap token generated successfully: ' . $snapToken);
            
            // Test apakah snapToken valid
            if (empty($snapToken)) {
                throw new \Exception('Snap token is empty');
            }

            // Pastikan user memiliki membership atau buat membership baru
            $membership = $user->membership;
            if (!$membership) {
                $membership = Membership::create([
                    'user_id' => $user->id,
                    'start_time' => null,
                    'end_time' => null,
                    'status' => 'inactive',
                    'total_amount' => $amount,
                    'payment_status' => 'pending'
                ]);
            }

            // Buat transaksi pending terlebih dahulu
            $transaction = Transaction::create([
                'membership_id' => $membership->id,
                'amount' => $amount,
                'payment_method' => 'midtrans',
                'payment_gateway_id' => $orderId,
                'status' => 'pending',
                'paid_at' => null
            ]);

            \Log::info('Transaction created with ID: ' . $transaction->id);

            // Store transaction ID in session for later use
            session(['transaction_id' => $transaction->id]);

            return view('customer.payment.midtrans', compact('snapToken', 'transaction'));

        } catch (\Exception $e) {
            \Log::error('Midtrans payment error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->back()
                           ->with('error', 'Payment failed. Please try again: ' . $e->getMessage());
        }
    }

    /**
     * Process manual payment (for testing)
     */
    private function processManual($user)
    {
        try {
            DB::beginTransaction();
            
            // Buat atau update membership - UNTUK MANUAL: langsung active
            $membership = Membership::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'start_time' => now(),
                    'end_time' => now()->addDays(30),
                    'status' => 'active',
                    'total_amount' => 200000,
                    'payment_status' => 'paid'
                ]
            );
            
            // Buat record transaksi
            $transaction = Transaction::create([
                'membership_id' => $membership->id,
                'amount' => 200000,
                'payment_method' => 'manual',
                'payment_gateway_id' => 'MANUAL-' . time() . '-' . $user->id,
                'status' => 'completed',
                'paid_at' => now()
            ]);
            
            DB::commit();
            
            // Untuk manual payment, langsung redirect ke dashboard karena sudah active
            return redirect()->route('customer.dashboard')
                           ->with('success', 'Payment successful! Your membership is now active.');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                           ->with('error', 'Payment failed. Please try again.')
                           ->withInput();
        }
    }
    
    /**
     * Halaman sukses pembayaran - STRICT VALIDATION
     */
    public function success(Request $request)
    {
        $user = auth()->user();
        $transactionId = $request->get('transaction_id');
        $orderId = $request->get('order_id');
        
        \Log::info('Payment success page accessed', [
            'user_id' => $user->id,
            'transaction_id' => $transactionId,
            'order_id' => $orderId,
            'url' => $request->fullUrl()
        ]);
        
        // STRICT VALIDATION: Only allow if transaction is actually completed
        if (!$transactionId && !$orderId) {
            \Log::warning('Success page accessed without transaction_id or order_id', [
                'user_id' => $user->id
            ]);
            
            return redirect()->route('customer.payment.show')
                           ->with('error', 'Invalid payment access. Please complete payment first.');
        }
        
        $transaction = null;
        
        // Try to find by transaction_id first, then by order_id
        if ($transactionId) {
            $transaction = Transaction::find($transactionId);
        } elseif ($orderId) {
            $transaction = Transaction::where('payment_gateway_id', $orderId)->first();
        }
        
        if (!$transaction || 
            !$transaction->membership || 
            $transaction->membership->user_id !== $user->id) {
            
            \Log::warning('Invalid transaction access attempt', [
                'user_id' => $user->id,
                'transaction_id' => $transactionId
            ]);
            
            return redirect()->route('customer.payment.show')
                           ->with('error', 'Transaction not found or unauthorized access.');
        }
        
        // STRICT CHECK: Only allow success page if transaction is completed
        if ($transaction->status !== 'completed') {
            \Log::warning('Success page accessed for non-completed transaction', [
                'user_id' => $user->id,
                'transaction_id' => $transactionId,
                'transaction_status' => $transaction->status
            ]);
            
            return redirect()->route('customer.payment.show')
                           ->with('error', 'Payment has not been completed yet. Please wait for confirmation.');
        }
        
        // Get membership
        $membership = $transaction->membership->fresh();
        
        \Log::info('Valid success page access', [
            'user_id' => $user->id,
            'transaction_id' => $transactionId,
            'membership_id' => $membership->id,
            'membership_status' => $membership->status
        ]);
        
        return view('customer.payment.success', [
            'user' => $user,
            'membership' => $membership,
            'transaction' => $transaction
        ]);
    }

    /**
     * Verify payment status with Midtrans before allowing success page
     */
    public function verifyPayment(Request $request)
    {
        $orderId = $request->input('order_id');
        
        if (!$orderId) {
            return response()->json([
                'success' => false,
                'message' => 'Order ID is required'
            ], 400);
        }
        
        try {
            // Configure Midtrans
            \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
            \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
            
            // Get transaction status from Midtrans
            $status = MidtransTransaction::status($orderId);
            
            \Log::info('Midtrans status check', [
                'order_id' => $orderId,
                'status' => $status
            ]);
            
            // Check if payment is actually successful
            if (in_array($status->transaction_status, ['capture', 'settlement'])) {
                
                // Find the transaction and activate membership
                $transaction = Transaction::where('payment_gateway_id', $orderId)->first();
                
                if ($transaction && $transaction->status !== 'completed') {
                    // Activate membership if not already activated
                    $this->activateMembership($transaction, $transaction->membership);
                    
                    \Log::info('Membership activated through verify payment', [
                        'transaction_id' => $transaction->id,
                        'order_id' => $orderId
                    ]);
                }
                
                return response()->json([
                    'success' => true,
                    'payment_verified' => true,
                    'transaction_status' => $status->transaction_status,
                    'message' => 'Payment verified and membership activated'
                ]);
            } else {
                return response()->json([
                    'success' => true,
                    'payment_verified' => false,
                    'transaction_status' => $status->transaction_status,
                    'message' => 'Payment not completed yet'
                ]);
            }
            
        } catch (\Exception $e) {
            \Log::error('Error verifying payment status: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error verifying payment status'
            ], 500);
        }
    }

    public function activateNow(Request $request)
    {
        $user = auth()->user();
        $transactionId = $request->get('transaction_id');
        
        try {
            DB::beginTransaction();
            
            if ($transactionId) {
                $transaction = Transaction::find($transactionId);
                if ($transaction && $transaction->status === 'pending') {
                    $transaction->update([
                        'status' => 'completed',
                        'paid_at' => now()
                    ]);
                }
            }
            
            // Update membership jadi active
            $membership = $user->membership;
            if ($membership) {
                $membership->update([
                    'status' => 'active',
                    'payment_status' => 'paid',
                    'start_time' => now(),
                    'end_time' => now()->addDays(30),
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('customer.payment.success', ['transaction_id' => $transactionId])
                           ->with('success', 'Membership activated successfully!');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to activate membership: ' . $e->getMessage());
        }
    }
    
    /**
     * Handle Midtrans Payment Callback
     */
    public function midtransCallback(Request $request)
    {
        try {
            // Get the notification
            $notification = new \Midtrans\Notification();
            
            $transactionStatus = $notification->transaction_status;
            $paymentType = $notification->payment_type;
            $orderId = $notification->order_id;
            $fraudStatus = $notification->fraud_status;
            
            \Log::info('Midtrans callback received', [
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
                'payment_type' => $paymentType,
                'fraud_status' => $fraudStatus
            ]);
            
            // Find transaction by payment_gateway_id (order_id)
            $transaction = Transaction::where('payment_gateway_id', $orderId)->first();
            
            if (!$transaction) {
                \Log::error('Transaction not found for order_id: ' . $orderId);
                return response()->json(['status' => 'error', 'message' => 'Transaction not found'], 404);
            }
            
            $membership = $transaction->membership;
            
            if ($transactionStatus == 'capture') {
                if ($paymentType == 'credit_card') {
                    if ($fraudStatus == 'challenge') {
                        // Handle challenge status
                        $transaction->update(['status' => 'pending']);
                    } else if ($fraudStatus == 'accept') {
                        // Payment success
                        $this->activateMembership($transaction, $membership);
                    }
                }
            } else if ($transactionStatus == 'settlement') {
                // Payment success
                $this->activateMembership($transaction, $membership);
            } else if ($transactionStatus == 'pending') {
                // Payment pending
                $transaction->update(['status' => 'pending']);
            } else if ($transactionStatus == 'deny') {
                // Payment denied
                $transaction->update(['status' => 'failed']);
            } else if ($transactionStatus == 'expire') {
                // Payment expired
                $transaction->update(['status' => 'expired']);
            } else if ($transactionStatus == 'cancel') {
                // Payment cancelled
                $transaction->update(['status' => 'cancelled']);
            }
            
            return response()->json(['status' => 'success'], 200);
            
        } catch (\Exception $e) {
            \Log::error('Midtrans callback error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Helper method to activate membership
     */
    private function activateMembership($transaction, $membership)
    {
        DB::beginTransaction();
        try {
            // Update membership to active
            $membership->update([
                'status' => 'active',
                'payment_status' => 'paid',
                'start_time' => now(),
                'end_time' => now()->addDays(30),
            ]);
            
            // Update transaction to completed
            $transaction->update([
                'status' => 'completed',
                'paid_at' => now()
            ]);
            
            DB::commit();
            \Log::info('Membership activated via callback for transaction: ' . $transaction->id);
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to activate membership via callback: ' . $e->getMessage());
            throw $e;
        }
    }
    
}
