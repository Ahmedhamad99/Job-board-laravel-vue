<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update empty phone values to NULL to avoid duplicate index issues
        DB::table('profiles')->where('phone', '')->update(['phone' => null]);
        
        Schema::table('profiles', function (Blueprint $table) {
            // Make phone nullable to allow empty values
            $table->string('phone')->nullable()->change();
        });
        
        // Add uniqueness where phone is not null
        Schema::table('profiles', function (Blueprint $table) {
            $table->unique('phone', 'profiles_phone_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropUnique('profiles_phone_unique');
            $table->string('phone')->change();
        });
    }
};