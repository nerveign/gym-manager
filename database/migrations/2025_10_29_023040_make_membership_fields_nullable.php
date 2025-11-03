<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('memberships', function (Blueprint $table) {
            $table->datetime('start_time')->nullable()->change();
            $table->datetime('end_time')->nullable()->change();
        });
    }
    
    public function down(): void
    {
        Schema::table('memberships', function (Blueprint $table) {
            $table->datetime('start_time')->nullable(false)->change();
            $table->datetime('end_time')->nullable(false)->change();
        });
    }
};
