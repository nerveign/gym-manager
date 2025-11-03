<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['membership.user']);
        
        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        // Filter by payment method
        if ($request->has('payment_method') && $request->payment_method != '') {
            $query->where('payment_method', $request->payment_method);
        }
        
        // Search by payment_gateway_id or user name
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('payment_gateway_id', 'like', "%{$search}%")
                  ->orWhereHas('membership.user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        // Order by newest first
        $transactions = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Statistics
        $stats = [
            'total' => Transaction::count(),
            'completed' => Transaction::where('status', 'completed')->count(),
            'pending' => Transaction::where('status', 'pending')->count(),
            'failed' => Transaction::where('status', 'failed')->count(),
            'total_amount' => Transaction::where('status', 'completed')->sum('amount'),
        ];
        
        return view('admin.transactions.index', compact('transactions', 'stats'));
    }
    
    public function show($id)
    {
        $transaction = Transaction::with(['membership.user'])->findOrFail($id);
        return view('admin.transactions.show', compact('transaction'));
    }
    
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,failed'
        ]);
        
        $transaction = Transaction::findOrFail($id);
        $oldStatus = $transaction->status;
        $transaction->status = $request->status;
        $transaction->save();
        
        // If status changed to completed, activate membership
        if ($request->status == 'completed' && $oldStatus != 'completed') {
            if ($transaction->membership) {
                $transaction->membership->update([
                    'status' => 'active',
                    'start_time' => now(),
                    'end_time' => now()->addDays(30), // 30 days membership
                    'payment_status' => 'paid'
                ]);
            }
        }
        
        return redirect()->back()->with('success', 'Status transaksi berhasil diupdate');
    }
    
    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();
        
        return redirect()->route('admin.transactions.index')->with('success', 'Transaksi berhasil dihapus');
    }
}
