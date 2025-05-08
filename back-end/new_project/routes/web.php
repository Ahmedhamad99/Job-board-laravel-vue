<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashbaordController;
Route::get('/', function () {
    return view('welcome');
});


Route::get('/admin',[AdminController::class,"index"])->name('admin.index');
