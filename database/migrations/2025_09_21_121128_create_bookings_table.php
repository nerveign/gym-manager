<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
         Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trainer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('membership_id')->constrained('memberships')->onDelete('cascade');
            $table->integer('duration');
            $table->date('date');
            $table->time('time');
            $table->timestamps();
          });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
