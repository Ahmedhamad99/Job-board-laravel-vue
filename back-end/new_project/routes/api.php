<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Apis\Auth\RegisterController;
use App\Http\Controllers\Apis\Auth\EmailVerficationController;
use App\Http\Controllers\Apis\Auth\LoginController;
use App\Http\Controllers\AdminController;
Route::middleware("auth:sanctum")->get("/user",function (Request $request) {
    return $request->user();
});

Route::group(["prefix" => "admin"], function () {
    Route::group(["prefix" => "jobs"], function () {
        Route::get("/", [AdminController::class, "getAlljobs"]);
        Route::post("/create", [AdminController::class, "createJob"]);
        Route::get("/show/{id}", [AdminController::class, "showJob"]);
        Route::put("/update/{id}", [AdminController::class, "updateJob"]);
        Route::delete("/delete/{id}", [AdminController::class, "deleteJob"]);
        Route::patch("/rejected/{id}", [AdminController::class, "rejectedJob"]);
        Route::get("/accepted", [AdminController::class, "acceptedJobs"]);

        Route::patch("/accepted/{id}", [AdminController::class, "acceptedJob"]);
        Route::get("/search", [AdminController::class, "searchJob"]);
        Route::get("/pending", [AdminController::class, "pendingJobs"]);
        Route::get("/rejected", [AdminController::class, "rejectedJobs"]);

    });

    Route::group(["prefix" => "users"], function () {
        Route::get("/", [AdminController::class, "getAllusers"]);
        Route::post("/create", [AdminController::class, "createUser"]);
        Route::get("/show/{id}", [AdminController::class, "showUser"]);
        Route::put("/update/{id}", [AdminController::class, "updateUser"]);
        Route::delete("/delete/{id}", [AdminController::class, "deleteUser"]);
      Route::get("/search", [AdminController::class, "searchUser"]);
    });

    Route::group(["prefix" => "categories"], function () {
        Route::get("/", [AdminController::class, "getAllcatigories"]);
    });

    Route::group(["prefix" => "employers"], function () {
        Route::get("/", [AdminController::class, "getAllEmployers"]);
    });

    
   
    
  
});


