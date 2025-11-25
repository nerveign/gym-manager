<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Transaction;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Doovera testing endpoints
Route::middleware('auth:sanctum')->group(function () {
    
    // Check payment status with Doovera
    Route::post('/doovera/check-payment', function (Request $request) {
        try {
            $vaNumber = $request->va_number;
            
            if (!$vaNumber) {
                return response()->json(['success' => false, 'error' => 'VA number required']);
            }
            
            Log::info('API: Checking payment with Doovera', ['va_number' => $vaNumber]);
            
            $response = Http::timeout(30)->withHeaders([
                'X-API-KEY' => config('payment.api_key'),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->get(config('payment.base_url') . '/check-payment', [
                'va_number' => $vaNumber
            ]);

            Log::info('API: Doovera response', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                $dooveraStatus = $data['data']['status'] ?? 'unknown';
                
                // Check local status
                $transaction = Transaction::where('payment_gateway_id', $vaNumber)->first();
                $localStatus = $transaction ? $transaction->status : 'not_found';
                
                return response()->json([
                    'success' => true,
                    'doovera_status' => $dooveraStatus,
                    'local_status' => $localStatus,
                    'doovera_response' => $data
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => 'Doovera API error: ' . $response->body()
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('API: Doovera check failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    });
    
    // Sync payment status
    Route::post('/payment/sync', function (Request $request) {
        try {
            $vaNumber = $request->va_number;
            
            if (!$vaNumber) {
                return response()->json(['success' => false, 'error' => 'VA number required']);
            }
            
            Log::info('API: Syncing payment', ['va_number' => $vaNumber]);
            
            $transaction = Transaction::where('payment_gateway_id', $vaNumber)->first();
            
            if (!$transaction) {
                return response()->json(['success' => false, 'error' => 'Transaction not found']);
            }
            
            // Force check with Doovera
            $response = Http::timeout(30)->withHeaders([
                'X-API-KEY' => config('payment.api_key'),
                'Content-Type' => 'application/json'
            ])->get(config('payment.base_url') . '/check-payment', [
                'va_number' => $vaNumber
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $dooveraStatus = strtolower($data['data']['status'] ?? '');
                
                if (in_array($dooveraStatus, ['paid', 'success', 'completed', 'settlement'])) {
                    // Update transaction
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
                    
                    Log::info('API: Payment synced successfully', [
                        'transaction_id' => $transaction->id,
                        'doovera_status' => $dooveraStatus
                    ]);
                    
                    return response()->json([
                        'success' => true,
                        'message' => 'Payment synced successfully! Membership activated.',
                        'transaction_status' => 'success',
                        'membership_status' => 'active'
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'error' => 'Payment still pending in Doovera: ' . $dooveraStatus
                    ]);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'error' => 'Failed to check Doovera API: ' . $response->body()
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('API: Payment sync failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    });
    
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
