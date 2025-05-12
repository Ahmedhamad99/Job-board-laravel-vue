<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\backend\HelloContoller;
use App\Http\Controllers\backend\ProductController;
use App\Http\Controllers\backend\UserController;
use App\Http\Controllers\DashbaordController;
use App\Http\Controllers\JobController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('jobs', [JobController::class, 'search']);
