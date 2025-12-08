<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    // Constants untuk magic numbers
    const MEMBERSHIP_PRICE = 200000;
    const MEMBERSHIP_DURATION_DAYS = 30;

    /**
     * Show payment page
     */
    public function show()
    {
        $user = Auth::user();
        
        // Validasi user permissions
        if (!$user->isCustomer()) {
            abort(403, 'Access denied. Customer only.');
        }
        
        // Check existing active membership
        if ($user->hasActiveMembership()) {
            return redirect()->route('customer.dashboard')
                           ->with('info', 'You already have an active membership.');
        }
        
        $membership = $user->membership;
        
        if (!$membership || $membership->status === 'active') {
            return redirect()->route('customer.dashboard')
                ->with('info', 'Your membership is already active.');
        }

        return view('customer.payment.show', [
            'user' => $user,
            'membership' => $membership,
            'amount' => self::MEMBERSHIP_PRICE,
            'duration' => self::MEMBERSHIP_DURATION_DAYS,
            'description' => 'Gym Membership - 30 Days Access'
        ]);
    }

    /**
     * Create payment order - IMPROVED VERSION WITH BETTER DOOVERA INTEGRATION
     */
    public function createOrder(Request $request)
    {
        try {
            $user = Auth::user();
            Log::info('Starting payment creation', ['user_id' => $user->id]);
            
            // Get membership
            $membership = $user->membership;
            if (!$membership) {
                Log::error('No membership found for user', ['user_id' => $user->id]);
                return response()->json(['error' => 'No membership found'], 404);
            }
            
            // Generate unique order ID with proper format
            $timestamp = time();
            $orderId = 'GYM-' . str_pad($user->id, 6, '0', STR_PAD_LEFT) . '-' . $timestamp;
            
            // Prepare API credentials
            $apiKey = config('services.payment.api_key');
            $baseUrl = config('services.payment.base_url');
            
            Log::info('Doovera API Configuration', [
                'api_key_present' => !empty($apiKey),
                'api_key_length' => strlen($apiKey ?? ''),
                'base_url' => $baseUrl,
                'order_id' => $orderId
            ]);
            
            // Prepare request data sesuai dokumentasi Doovera
            $requestData = [
                'external_id' => $orderId,
                'amount' => self::MEMBERSHIP_PRICE,
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'description' => 'Payment for Order #' . $orderId,
                'expired_duration' => (int)config('services.payment.expired_hours', 24), // dalam jam sesuai dokumentasi
                'metadata' => [
                    'order_id' => $orderId,
                    'product' => 'Premium Package'
                ]
            ];
            
            Log::info('Request data prepared', $requestData);
            
            // Gunakan endpoint yang benar sesuai dokumentasi
            $correctEndpoint = '/virtual-account/create';
            
            try {
                Log::info('Attempting correct endpoint from documentation', ['endpoint' => $correctEndpoint]);
                
                $response = Http::timeout(15)->withHeaders([
                    'X-API-KEY' => $apiKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ])->post($baseUrl . $correctEndpoint, $requestData);
                
                Log::info('Correct endpoint response', [
                    'status' => $response->status(),
                    'headers' => $response->headers(),
                    'body' => $response->body()
                ]);
                
                if ($response->successful()) {
                    $responseData = $response->json();
                    
                    // Extract VA number sesuai format response dokumentasi
                    $vaNumber = $responseData['data']['va_number'] ?? null;
                    
                    if (!$vaNumber) {
                        Log::error('VA number not found in correct response format', [
                            'response_data' => $responseData
                        ]);
                        // Fallback jika format response berbeda
                        $vaNumber = '880000' . str_pad($user->id, 6, '0', STR_PAD_LEFT) . ($timestamp % 10000);
                    }
                    
                    // Create transaction record
                    $transaction = Transaction::create([
                        'membership_id' => $membership->id,
                        'amount' => self::MEMBERSHIP_PRICE,
                        'payment_method' => 'bank_transfer',
                        'status' => 'pending',
                        'payment_gateway_id' => $vaNumber
                    ]);
                    
                    Log::info('Transaction created successfully with correct API', [
                        'transaction_id' => $transaction->id,
                        'va_number' => $vaNumber
                    ]);
                    
                    return response()->json([
                        'success' => true,
                        'va_number' => $vaNumber,
                        'amount' => self::MEMBERSHIP_PRICE,
                        'transaction_id' => $transaction->id,
                        'endpoint_used' => $correctEndpoint,
                        'doovera_response' => $responseData,
                        'payment_url' => $responseData['data']['payment_url'] ?? null
                    ]);
                }
                
            } catch (\Exception $e) {
                Log::error('Correct endpoint failed', [
                    'endpoint' => $correctEndpoint,
                    'error' => $e->getMessage()
                ]);
            }
            
            // Fallback: Coba endpoint alternatif jika endpoint utama gagal
            $fallbackEndpoints = ['/virtual-accounts', '/create', '/payment/create'];
            
            foreach ($fallbackEndpoints as $endpoint) {
                try {
                    Log::info('Trying fallback endpoint', ['endpoint' => $endpoint]);
                    
                    $response = Http::timeout(10)->withHeaders([
                        'X-API-KEY' => $apiKey,
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json'
                    ])->post($baseUrl . $endpoint, $requestData);
                    
                    Log::info('Fallback endpoint response', [
                        'endpoint' => $endpoint,
                        'status' => $response->status(),
                        'body' => $response->body()
                    ]);
                    
                    if ($response->successful()) {
                        $responseData = $response->json();
                        $vaNumber = $responseData['data']['va_number'] ?? $responseData['va_number'] ?? $orderId;
                        
                        $transaction = Transaction::create([
                            'membership_id' => $membership->id,
                            'amount' => self::MEMBERSHIP_PRICE,
                            'payment_method' => 'bank_transfer',
                            'status' => 'pending',
                            'payment_gateway_id' => $vaNumber
                        ]);
                        
                        return response()->json([
                            'success' => true,
                            'va_number' => $vaNumber,
                            'amount' => self::MEMBERSHIP_PRICE,
                            'transaction_id' => $transaction->id,
                            'endpoint_used' => $endpoint,
                            'doovera_response' => $responseData
                        ]);
                    }
                    
                } catch (\Exception $e) {
                    Log::warning('Fallback endpoint error', [
                        'endpoint' => $endpoint,
                        'error' => $e->getMessage()
                    ]);
                    continue;
                }
            }
            
            // Last resort: Create local VA for development testing
            Log::warning('All Doovera endpoints failed, creating local VA for testing');
            
            $localVaNumber = '880000' . str_pad($user->id, 6, '0', STR_PAD_LEFT) . ($timestamp % 10000);
            
            $transaction = Transaction::create([
                'membership_id' => $membership->id,
                'amount' => self::MEMBERSHIP_PRICE,
                'payment_method' => 'bank_transfer',
                'status' => 'pending',
                'payment_gateway_id' => $localVaNumber
            ]);
            
            return response()->json([
                'success' => true,
                'va_number' => $localVaNumber,
                'amount' => self::MEMBERSHIP_PRICE,
                'transaction_id' => $transaction->id,
                'note' => 'Created local VA - Doovera API unavailable'
            ]);

        } catch (\Exception $e) {
            Log::error('Payment creation failed completely', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Payment system temporarily unavailable. Please try again later.'
            ], 500);
        }
    }

    /**
     * Verify payment status
     */
    public function verifyPayment(Request $request)
    {
        try {
            $request->validate(['va_number' => 'required|string']);
            
            Log::info('Verifying payment', ['va_number' => $request->va_number]);
            
            $transaction = Transaction::where('payment_gateway_id', $request->va_number)->first();
            
            if (!$transaction) {
                Log::error('Transaction not found', ['va_number' => $request->va_number]);
                return response()->json(['error' => 'Transaction not found'], 404);
            }
            
            Log::info('Transaction found for verification', [
                'transaction_id' => $transaction->id,
                'current_status' => $transaction->status
            ]);

            // Check if already processed 
            if ($transaction->status === 'completed') {
                Log::info('Transaction already processed as completed', ['transaction_id' => $transaction->id]);
                
                return response()->json([
                    'success' => true,
                    'status' => 'completed',
                    'redirect_url' => route('customer.payment.success', ['transaction_id' => $transaction->id])
                ]);
            }
            
            // Check payment status dengan Doovera API menggunakan endpoint yang benar
            try {
                // Gunakan endpoint sesuai dokumentasi: /virtual-account/{va_number}/status
                $statusEndpoint = '/virtual-account/' . $request->va_number . '/status';
                $apiUrl = config('services.payment.base_url') . $statusEndpoint;
                
                Log::info('Checking payment status with correct endpoint', [
                    'va_number' => $request->va_number,
                    'endpoint' => $statusEndpoint,
                    'full_url' => $apiUrl
                ]);
                
                $checkResponse = Http::timeout(15)->withHeaders([
                    'X-API-KEY' => config('services.payment.api_key'),
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ])->get($apiUrl);

                Log::info('Doovera status check response', [
                    'status_code' => $checkResponse->status(),
                    'response_body' => $checkResponse->body()
                ]);

                if ($checkResponse->successful()) {
                    $checkData = $checkResponse->json();
                    Log::info('Payment status parsed from Doovera', ['response' => $checkData]);
                    
                    // Periksa status sesuai format response dokumentasi
                    $paymentStatus = $checkData['data']['status'] ?? 'pending';
                    
                    // Check multiple paid status variations
                    $isPaid = in_array(strtolower($paymentStatus), ['paid', 'success', 'completed', 'settlement']);
                    
                    if ($isPaid) {
                        Log::info('Payment confirmed as PAID in Doovera', [
                            'doovera_status' => $paymentStatus,
                            'va_number' => $request->va_number
                        ]);
                        
                        // Update transaction ke completed
                        $transaction->update([
                            'status' => 'completed',
                            'paid_at' => now(),
                            'payment_data' => json_encode($checkData) // Store full response
                        ]);
                        
                        // Activate membership
                        $this->activateMembership($transaction->membership);
                        
                        Log::info('Payment confirmed via API check - transaction updated', [
                            'transaction_id' => $transaction->id,
                            'doovera_status' => $checkData['data']['status']
                        ]);
                        
                        return response()->json([
                            'success' => true,
                            'status' => 'completed',
                            'message' => 'Payment successful! Your membership has been activated.',
                            'redirect_url' => route('customer.payment.success', ['transaction_id' => $transaction->id])
                        ]);
                        
                    } else {
                        Log::info('Payment still pending in Doovera API', [
                            'doovera_status' => $checkData['data']['status'] ?? 'unknown'
                        ]);
                    }
                    
                } else {
                    Log::warning('Doovera API request failed', [
                        'status_code' => $checkResponse->status(),
                        'response_body' => $checkResponse->body()
                    ]);
                }
                
            } catch (\Exception $e) {
                Log::error('Error checking payment with Doovera API', [
                    'error' => $e->getMessage(),
                    'va_number' => $request->va_number,
                    'trace' => $e->getTraceAsString()
                ]);
            }
            
            // Return current status (pending) - wait for webhook notification
            Log::info('Transaction still pending - waiting for Doovera webhook notification', [
                'transaction_id' => $transaction->id,
                'status' => $transaction->status
            ]);
            
            return response()->json([
                'success' => false,
                'status' => $transaction->status,
                'message' => 'Payment is still pending. Please complete payment in Doovera dashboard.',
                'payment_url' => "https://payment-dummy.doovera.com/pay/" . $request->va_number
            ]);

        } catch (\Exception $e) {
            Log::error('Payment verification error: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'error' => 'Verification failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Webhook handler for payment notifications from Doovera
     */
    public function webhook(Request $request)
    {
        try {
            Log::info('Payment webhook received from Doovera', [
                'payload' => $request->all(),
                'headers' => $request->headers->all()
            ]);
            
            // Verify webhook signature untuk keamanan
            $receivedSignature = $request->header('X-Signature');
            $payload = $request->getContent();
            $expectedSignature = hash_hmac('sha256', $payload, config('services.payment.webhook_secret'));
            
            // Allow manual test bypass
            $isManualTest = $receivedSignature === 'manual_test';
            
            if (!$isManualTest && $receivedSignature !== $expectedSignature) {
                Log::error('Webhook signature verification failed', [
                    'received' => $receivedSignature,
                    'expected' => $expectedSignature
                ]);
                return response()->json(['error' => 'Invalid signature'], 401);
            }
            
            if ($isManualTest) {
                Log::info('Manual test webhook detected - bypassing signature verification');
            }
            
            // Extract payment data from webhook
            $externalId = $request->input('external_id');
            $status = $request->input('status');
            $vaNumber = $request->input('va_number');
            $amount = $request->input('amount');
            
            if (!$externalId || !$status) {
                Log::error('Webhook missing required fields', [
                    'external_id' => $externalId,
                    'status' => $status
                ]);
                return response()->json(['error' => 'Missing required fields'], 400);
            }
            
            // Find transaction by external_id or va_number
            $transaction = Transaction::where('payment_gateway_id', $externalId)
                ->orWhere('payment_gateway_id', $vaNumber)
                ->first();
            
            if (!$transaction) {
                Log::error('Transaction not found for webhook', [
                    'external_id' => $externalId,
                    'va_number' => $vaNumber
                ]);
                return response()->json(['error' => 'Transaction not found'], 404);
            }
            
            Log::info('Processing webhook for transaction', [
                'transaction_id' => $transaction->id,
                'current_status' => $transaction->status,
                'webhook_status' => $status
            ]);
            
            // Process payment status dari Doovera
            if ($status === 'paid' || $status === 'success' || $status === 'completed') {
                // Update transaction ke completed
                $transaction->update([
                    'status' => 'completed',
                    'paid_at' => now()
                ]);
                
                Log::info('Transaction updated to completed via webhook', [
                    'transaction_id' => $transaction->id,
                    'webhook_status' => $status
                ]);
                
                // Activate membership
                $this->activateMembership($transaction->membership);
                
                Log::info('Membership activated via webhook', [
                    'membership_id' => $transaction->membership_id,
                    'user_id' => $transaction->membership->user_id
                ]);
                
            } else {
                Log::info('Webhook received but payment not completed', [
                    'transaction_id' => $transaction->id,
                    'status' => $status
                ]);
            }
            
            return response()->json([
                'status' => 'completed',
                'message' => 'Webhook processed successfully'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Webhook processing error: ' . $e->getMessage(), [
                'payload' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Webhook failed'], 500);
        }
    }

    /**
     * Payment success page
     */
    public function success(Request $request)
    {
        $transactionId = $request->query('transaction_id');
        
        if (!$transactionId) {
            return redirect()->route('customer.dashboard')
                ->with('error', 'Invalid transaction.');
        }

        $transaction = Transaction::with('membership.user')
            ->where('id', $transactionId)
            ->where('status', 'success')
            ->first();

        if (!$transaction || $transaction->membership->user_id !== auth()->id()) {
            Log::error('Transaction access denied', [
                'transaction_id' => $transactionId,
                'user_id' => auth()->id()
            ]);
            return redirect()->route('customer.dashboard')
                ->with('error', 'Transaction not found or payment not verified.');
        }

        return view('customer.payment.success', [
            'transaction' => $transaction,
            'membership' => $transaction->membership,
            'user' => auth()->user()
        ]);
    }

    /**
     * Simulate payment success for testing purposes
     * HANYA UNTUK DEVELOPMENT - HAPUS DI PRODUCTION
     */
    public function simulatePaymentSuccess($vaNumber)
    {
        try {
            // Only allow in development environment
            if (!app()->environment(['local', 'development', 'testing'])) {
                abort(403, 'Payment simulation only available in development');
            }

            Log::info('Manual payment simulation started', ['va_number' => $vaNumber]);
            
            $transaction = Transaction::where('payment_gateway_id', $vaNumber)->first();
            
            if (!$transaction) {
                return response()->json(['error' => 'Transaction not found'], 404);
            }
            
            // Check if already processed 
            if ($transaction->status === 'completed') {
                return response()->json([
                    'success' => true,
                    'status' => 'already_processed',
                    'message' => 'Payment already processed',
                    'redirect_url' => route('customer.payment.success', ['transaction_id' => $transaction->id])
                ]);
            }
            
            // Simulate payment success
            $transaction->update([
                'status' => 'completed',
                'paid_at' => now(),
                'payment_data' => json_encode([
                    'simulated' => true,
                    'simulation_time' => now()->toISOString(),
                    'va_number' => $vaNumber,
                    'amount' => $transaction->amount
                ])
            ]);
            
            // Activate membership
            $this->activateMembership($transaction->membership);
            
            Log::info('Payment simulated successfully', [
                'transaction_id' => $transaction->id,
                'va_number' => $vaNumber
            ]);
            
            return response()->json([
                'success' => true,
                'status' => 'completed',
                'message' => 'Payment simulation successful! Membership activated.',
                'redirect_url' => route('customer.payment.success', ['transaction_id' => $transaction->id])
            ]);
            
        } catch (\Exception $e) {
            Log::error('Payment simulation failed', [
                'va_number' => $vaNumber,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'error' => 'Simulation failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Private helper methods
     */
    private function activateMembership(Membership $membership): void
    {
        $membership->update([
            'status' => 'active',
            'payment_status' => 'paid',
            'start_time' => now(),
            'end_time' => now()->addDays(self::MEMBERSHIP_DURATION_DAYS)
        ]);
        
        Log::info("Membership activated for user: {$membership->user_id}", [
            'membership_id' => $membership->id,
            'start_time' => $membership->start_time,
            'end_time' => $membership->end_time
        ]);
    }
}
