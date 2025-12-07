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
        Schema::create('class_agendas', function (Blueprint $table) {
            $table->id();
            // Terhubung ke tabel gym_classes
            $table->foreignId('gym_class_id')->constrained('gym_classes')->onDelete('cascade');
            $table->string('title'); // Nama Agenda/Materi
            $table->integer('order')->default(1); // Urutan agenda
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_agendas');
    }
};
