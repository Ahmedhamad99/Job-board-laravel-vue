<?php
use App\Http\Controllers\Apis\ProductController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Apis\Auth\RegisterController;
use App\Http\Controllers\Apis\Auth\EmailVerficationController;
use App\Http\Controllers\Apis\Auth\LoginController;
use App\Http\Controllers\AuthRegister;
use App\Http\Controllers\Controller;
use GuzzleHttp\Middleware;

Route::middleware("auth:sanctum")->get("/user",function (Request $request) {
    return $request->user();
});
Route::post("/register",[AuthRegister::class,'store']);
Route::post("/login",[AuthRegister::class,'login']);


