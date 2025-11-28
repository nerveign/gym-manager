<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->string('equipment_name');
            $table->string('brand');
            $table->string('condition');
            $table->integer('quantity')->default(0); // <--- TAMBAHKAN INI
            $table->string('image_url')->nullable(); // Sebaiknya nullable jaga-jaga jika tidak ada gambar
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};
