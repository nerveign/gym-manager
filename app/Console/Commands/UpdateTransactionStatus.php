<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;

class UpdateTransactionStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transaction:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update transaction status from "success" to "completed"';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating transaction status from "success" to "completed"...');
        
        // Find all transactions with status 'success'
        $transactions = Transaction::where('status', 'success')->get();
        
        $this->info("Found {$transactions->count()} transactions with status 'success'");
        
        if ($transactions->count() === 0) {
            $this->info('No transactions to update.');
            return;
        }
        
        // Update each transaction
        $updatedCount = 0;
        foreach ($transactions as $transaction) {
            try {
                $transaction->update(['status' => 'completed']);
                $updatedCount++;
                $this->line("Updated transaction ID: {$transaction->id}");
            } catch (\Exception $e) {
                $this->error("Failed to update transaction ID: {$transaction->id} - Error: {$e->getMessage()}");
            }
        }
        
        $this->info("Successfully updated {$updatedCount} transactions.");
        $this->info('Transaction status update completed!');
    }
}
