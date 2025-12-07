<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('member_class_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Customer
            $table->foreignId('class_agenda_id')->constrained('class_agendas')->onDelete('cascade'); // Agenda yg selesai
            $table->timestamp('completed_at')->useCurrent(); // Kapan selesai
            $table->foreignId('marked_by')->constrained('users'); // Siapa Trainer yang menandai
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_class_progress');
    }
};
