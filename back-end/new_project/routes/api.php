<?php
use App\Http\Controllers\Apis\ProductController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Apis\Auth\RegisterController;
use App\Http\Controllers\Apis\Auth\EmailVerficationController;
use App\Http\Controllers\Apis\Auth\LoginController;
use App\Http\Controllers\JobController;

Route::middleware("auth:sanctum")->get("/user",function (Request $request) {
    return $request->user();
});
Route::group(['prefix'=>'jobs'],function(){
    Route::get('/', [JobController::class, 'index']);
    Route::post('/', [JobController::class, 'store']);
    Route::post('/create', [JobController::class, 'create']);
    Route::patch('/{id}/edit', [JobController::class, 'edit']);
    Route::delete('/{id}', [JobController::class, 'destroy']);
});