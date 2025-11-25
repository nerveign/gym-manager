<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use App\Models\Membership;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncPaymentStatus extends Command
{
    protected $signature = 'payment:sync {va_number?}';
    
    protected $description = 'Sync payment status with Doovera API';

    public function handle()
    {
        $vaNumber = $this->argument('va_number');
        
        if ($vaNumber) {
            // Sync specific VA number
            $this->syncSinglePayment($vaNumber);
        } else {
            // Sync all pending payments
            $this->syncAllPendingPayments();
        }
    }
    
    private function syncSinglePayment($vaNumber)
    {
        $this->info("Syncing payment for VA: {$vaNumber}");
        
        $transaction = Transaction::where('payment_gateway_id', $vaNumber)
                                ->where('status', 'pending')
                                ->first();
                                
        if (!$transaction) {
            $this->error("No pending transaction found for VA: {$vaNumber}");
            return;
        }
        
        if ($this->checkPaymentWithDoovera($vaNumber)) {
            $this->updateTransactionToSuccess($transaction);
            $this->info("✅ Payment updated to success for VA: {$vaNumber}");
        } else {
            $this->warn("❌ Payment still pending in Doovera for VA: {$vaNumber}");
        }
    }
    
    private function syncAllPendingPayments()
    {
        $this->info("Syncing all pending payments...");
        
        $pendingTransactions = Transaction::where('status', 'pending')->get();
        
        foreach ($pendingTransactions as $transaction) {
            if ($this->checkPaymentWithDoovera($transaction->payment_gateway_id)) {
                $this->updateTransactionToSuccess($transaction);
                $this->info("✅ Updated transaction {$transaction->id} - VA: {$transaction->payment_gateway_id}");
            }
        }
        
        $this->info("Sync completed!");
    }
    
    private function checkPaymentWithDoovera($vaNumber)
    {
        try {
            $response = Http::withHeaders([
                'X-API-KEY' => config('payment.api_key'),
                'Content-Type' => 'application/json'
            ])->get(config('payment.base_url') . '/check-payment', [
                'va_number' => $vaNumber
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return isset($data['data']['status']) && 
                       in_array($data['data']['status'], ['paid', 'success', 'completed']);
            }
            
        } catch (\Exception $e) {
            Log::error('Error checking payment with Doovera', [
                'va_number' => $vaNumber,
                'error' => $e->getMessage()
            ]);
        }
        
        return false;
    }
    
    private function updateTransactionToSuccess($transaction)
    {
        $transaction->update([
            'status' => 'success',
            'paid_at' => now()
        ]);
        
        // Activate membership
        $membership = $transaction->membership;
        $membership->update([
            'status' => 'active',
            'payment_status' => 'paid',
            'start_time' => now(),
            'end_time' => now()->addDays(30)
        ]);
        
        Log::info('Payment synced successfully', [
            'transaction_id' => $transaction->id,
            'va_number' => $transaction->payment_gateway_id
        ]);
    }
}
