<?php
use App\Http\Controllers\Apis\ProductController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Apis\Auth\RegisterController;
use App\Http\Controllers\Apis\Auth\EmailVerficationController;
use App\Http\Controllers\Apis\Auth\LoginController;
use App\Http\Controllers\Api\JobController;

Route::middleware("auth:sanctum")->get("/user",function (Request $request) {
    return $request->user();
});

Route::get('jobs', [JobController::class, 'search']);

Route::get('categories', [JobController::class, 'getCategories']);
