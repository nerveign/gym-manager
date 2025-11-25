<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Transaction;

class TestDooveraAPI extends Command
{
    protected $signature = 'doovera:test {va_number?}';
    
    protected $description = 'Test Doovera API connection and payment status';

    public function handle()
    {
        $vaNumber = $this->argument('va_number') ?? '8800003398844716';
        
        $this->info("Testing Doovera API connection...");
        $this->info("VA Number: {$vaNumber}");
        
        // Test API configuration
        $apiKey = config('payment.api_key');
        $baseUrl = config('payment.base_url');
        
        $this->line("API Key: " . substr($apiKey, 0, 10) . "...");
        $this->line("Base URL: {$baseUrl}");
        
        // Test API call
        try {
            $this->info("Making API request...");
            
            $response = Http::timeout(30)->withHeaders([
                'X-API-KEY' => $apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'User-Agent' => 'GymManager/1.0'
            ])->get($baseUrl . '/check-payment', [
                'va_number' => $vaNumber
            ]);

            $this->line("Response Status: " . $response->status());
            $this->line("Response Headers: " . json_encode($response->headers(), JSON_PRETTY_PRINT));
            $this->line("Response Body: " . $response->body());
            
            if ($response->successful()) {
                $data = $response->json();
                
                $this->info("✅ API call successful!");
                $this->line("Response Data: " . json_encode($data, JSON_PRETTY_PRINT));
                
                if (isset($data['data']['status'])) {
                    $status = $data['data']['status'];
                    $this->line("Payment Status in Doovera: {$status}");
                    
                    if (in_array(strtolower($status), ['paid', 'success', 'completed', 'settlement'])) {
                        $this->info("🎉 Payment is PAID in Doovera!");
                        
                        // Update local database
                        $transaction = Transaction::where('payment_gateway_id', $vaNumber)->first();
                        if ($transaction) {
                            $this->line("Local Transaction Status: " . $transaction->status);
                            
                            if ($transaction->status !== 'success') {
                                $this->info("Updating local transaction to success...");
                                $transaction->update([
                                    'status' => 'success',
                                    'paid_at' => now()
                                ]);
                                
                                // Update membership
                                $membership = $transaction->membership;
                                if ($membership) {
                                    $membership->update([
                                        'status' => 'active',
                                        'payment_status' => 'paid',
                                        'start_time' => now(),
                                        'end_time' => now()->addDays(30)
                                    ]);
                                }
                                
                                $this->info("✅ Local database updated!");
                            } else {
                                $this->info("✅ Local transaction already marked as success");
                            }
                        } else {
                            $this->warn("❌ No local transaction found for VA: {$vaNumber}");
                        }
                    } else {
                        $this->warn("⏳ Payment still pending in Doovera: {$status}");
                    }
                } else {
                    $this->warn("⚠️ No status field in response");
                }
                
            } else {
                $this->error("❌ API call failed!");
                $this->error("Status: " . $response->status());
                $this->error("Body: " . $response->body());
            }
            
        } catch (\Exception $e) {
            $this->error("❌ Exception occurred: " . $e->getMessage());
            $this->error("Trace: " . $e->getTraceAsString());
        }
        
        return 0;
    }
}
