<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\JobController;


Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');


Route::get('/jobs/{jobId}/apply', [JobApplicationController::class, 'showApplyForm'])->name('jobs.apply');


Route::post('/jobs/{jobId}/apply', [JobApplicationController::class, 'applyForJob'])->name('jobs.apply.store');
