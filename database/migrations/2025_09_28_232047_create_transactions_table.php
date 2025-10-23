<?php
 use Illuminate\Database\Migrations\Migration;
 use Illuminate\Database\Schema\Blueprint;
 use Illuminate\Support\Facades\Schema;
 
 return new class extends Migration
 {
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membership_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['cash', 'credit_card', 'debit_card', 'gopay', 'ovo', 'dana', 'bank_transfer']);
            $table->string('payment_gateway_id')->nullable();
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
 };