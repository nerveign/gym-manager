<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Update enum payment_method untuk menambahkan midtrans dan manual
            $table->enum('payment_method', [
                'cash', 'credit_card', 'debit_card', 'gopay', 'ovo', 'dana', 
                'bank_transfer', 'midtrans', 'manual'
            ])->change();
            
            // Update enum status untuk menambahkan completed
            $table->enum('status', ['pending', 'success', 'failed', 'completed'])->default('pending')->change();
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Kembalikan ke enum semula
            $table->enum('payment_method', [
                'cash', 'credit_card', 'debit_card', 'gopay', 'ovo', 'dana', 'bank_transfer'
            ])->change();
            
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending')->change();
        });
    }
};
