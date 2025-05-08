<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobsTable extends Migration
{
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('employer_id')->constrained('users')->onDelete('cascade');
            $table->string('title'); 
            $table->text('description'); 
            $table->text('skills')->nullable(); 
            $table->decimal('salary_min', 10, 2)->nullable();
            $table->decimal('salary_max', 10, 2)->nullable(); 
            $table->string('location'); 
            $table->foreignId('category_id')->constrained()->onDelete('restrict');
            $table->enum('status', ['accepted', 'pending', 'rejected'])->default('pending');
            $table->timestamps(); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('jobs');
    }
}