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
        Schema::table('jobs', function (Blueprint $table) {
            // Changing the salary columns from decimal to integer
            $table->integer('salary_min')->nullable()->change();
            $table->integer('salary_max')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            // Reverting back to decimal if needed
            $table->decimal('salary_min', 10, 2)->nullable()->change();
            $table->decimal('salary_max', 10, 2)->nullable()->change();
        });
    }
};