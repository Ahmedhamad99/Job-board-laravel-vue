<?php
use App\Http\Controllers\Apis\ProductController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Apis\Auth\RegisterController;
use App\Http\Controllers\Apis\Auth\EmailVerficationController;
use App\Http\Controllers\Apis\Auth\LoginController;
Route::middleware("auth:sanctum")->get("/user",function (Request $request) {
    return $request->user();
});


