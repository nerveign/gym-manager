<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('memberships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->datetime('start_time');
            $table->datetime('end_time');
            $table->enum('status', ['active', 'inactive'])->default('inactive');
            $table->decimal('total_amount', 10, 2); 
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('memberships');
    }
};
