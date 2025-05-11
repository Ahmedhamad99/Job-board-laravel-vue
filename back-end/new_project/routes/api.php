<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobController;
use App\Http\Controllers\JobApplicationController;

Route::get('/jobs/{id}', [JobController::class, 'show']);

Route::get('/jobs', [JobController::class, 'index']);


Route::middleware('auth:sanctum')->post('/jobs/{jobId}/apply', [JobApplicationController::class, 'applyForJob']);
