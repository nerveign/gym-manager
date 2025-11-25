<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class MembershipCheckController extends Controller
{
    public function forceCheck()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        // Force refresh relationship dan clear cache
        $user->refresh();
        $user->load(['membership' => function($query) {
            $query->orderBy('updated_at', 'desc');
        }]);
        
        // Clear any related cache
        Cache::forget('user_' . $user->id . '_membership');
        
        // Get current membership status langsung dari database
        $membership = \App\Models\Membership::where('user_id', $user->id)
                                           ->orderBy('updated_at', 'desc')
                                           ->first();
        
        if ($membership && $membership->status === 'active') {
            // Jika membership active, redirect ke success page dengan transaction info
            $transaction = \App\Models\Transaction::where('membership_id', $membership->id)
                                                 ->where('status', 'success')
                                                 ->first();
            
            if ($transaction) {
                return redirect()->route('customer.payment.success', ['transaction_id' => $transaction->id])
                               ->with('success', 'Payment successful! Your membership is now active.');
            } else {
                return redirect()->route('customer.dashboard')
                               ->with('success', 'Membership is active! Welcome to the gym!');
            }
        }
        
        return redirect()->route('customer.payment.show')
                       ->with('info', 'Membership activation required.');
    }
}
