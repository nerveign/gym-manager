<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Membership;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        // Get some users for testing
        $users = User::where('role', 'customer')->get();
        
        $transactions = [];
        
        foreach($users as $user) {
            // Create membership first
            $membership = Membership::create([
                'user_id' => $user->id,
                'status' => 'inactive',
                'start_time' => null,
                'end_time' => null,
                'total_amount' => 200000,
                'payment_status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // Create transaction
            $status = ['pending', 'completed', 'failed'][rand(0, 2)];
            $transactions[] = [
                'membership_id' => $membership->id,
                'amount' => 200000,
                'payment_method' => ['bank_transfer', 'credit_card', 'gopay', 'ovo'][rand(0, 3)],
                'status' => $status,
                'payment_gateway_id' => 'PAY-' . strtoupper(uniqid()),
                'paid_at' => $status === 'completed' ? now()->subDays(rand(0, 5)) : null,
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now()->subDays(rand(0, 5)),
            ];
            
            // If transaction is completed, activate membership
            if ($status === 'completed') {
                $membership->update([
                    'status' => 'active',
                    'start_time' => now()->subDays(rand(0, 5)),
                    'end_time' => now()->addDays(30),
                    'payment_status' => 'paid'
                ]);
            }
        }
        
        DB::table('transactions')->insert($transactions);
    }
}
