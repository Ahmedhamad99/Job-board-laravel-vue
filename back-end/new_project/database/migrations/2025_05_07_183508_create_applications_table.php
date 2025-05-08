<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationsTable extends Migration
{
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('job_id')->constrained()->onDelete('cascade'); 
            $table->foreignId('candidate_id')->constrained('users')->onDelete('cascade');
            $table->string('resume')->nullable(); 
            $table->text('contact_info')->nullable(); 
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending'); 
            $table->timestamps(); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('applications');
    }
}