<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MembershipSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua user customer
        $customers = User::where('role', 'customer')->get();
        
        if ($customers->count() == 0) {
            $this->command->info('No customers found. Skipping MembershipSeeder.');
            return;
        }

        $memberships = [];
        
        foreach ($customers as $index => $customer) {
            $startDate = Carbon::now()->subDays(rand(1, 30));
            $endDate = $startDate->copy()->addDays(30);
            
            $memberships[] = [
                'user_id'       => $customer->id,
                'start_time'    => $startDate,
                'end_time'      => $endDate,
                'total_amount'  => 200000,
                'status'        => $this->getMembershipStatus($endDate),
                'payment_status'=> $this->getRandomPaymentStatus(),
                'created_at'    => now(),
                'updated_at'    => now(),
            ];
            
            if ($index >= 9) {
                break;
            }
        }

        DB::table('memberships')->insert($memberships);
        
        $this->command->info('Successfully created ' . count($memberships) . ' memberships.');
    }

    /**
     * Tentukan status membership berdasarkan tanggal berakhir
     */
    private function getMembershipStatus($endDate)
    {
        $now = Carbon::now();
        
        if ($now->gt($endDate)) {
            return 'inactive'; // Sudah lewat tanggal berakhir
        } else {
            return 'active'; // Masih aktif
        }
    }

    /**
     * Dapatkan random payment status
     */
    private function getRandomPaymentStatus()
    {
        $statuses = ['paid', 'paid', 'paid', 'pending', 'failed']; // 60% paid, 20% pending, 20% failed
        return $statuses[array_rand($statuses)];
    }
}