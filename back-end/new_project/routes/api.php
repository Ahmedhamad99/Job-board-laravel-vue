<?php
use App\Http\Controllers\Apis\ProductController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Apis\Auth\RegisterController;
use App\Http\Controllers\Apis\Auth\EmailVerficationController;
use App\Http\Controllers\Apis\Auth\LoginController;
use App\Http\Controllers\AuthRegister;
use App\Http\Controllers\Controller;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\JobControllerCandidate;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\JobControllerSearch;
use App\Http\Controllers\CandidatesApplicationsController;
use App\Http\Controllers\ApplicantsController;
use GuzzleHttp\Middleware;

// Public routes that don't require authentication
Route::get('/jobs/search', [JobControllerSearch::class, 'search']);

Route::middleware("auth:sanctum")->get("/user",function (Request $request) {
    return $request->user();
});
Route::post("/register",[AuthRegister::class,'store']);
Route::post("/login",[AuthRegister::class,'login']);

Route::middleware(['auth:sanctum', \App\Http\Middleware\EnsureProfileExists::class])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile', [ProfileController::class, 'update']);
    Route::resource('jobs', JobController::class);
    
    // User applications routes
    Route::get('/applications', [CandidatesApplicationsController::class, 'getApplications']);
    Route::delete('/applications/{id}', [CandidatesApplicationsController::class, 'deleteApplication']);
    
    // Job applicants management routes
    

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
    Route::get('/jobs/{id}', [JobController::class, 'show']);

Route::get('/jobs', [JobControllerCandidate::class, 'index']);

});

Route::post('/test/jobs/{jobId}/apply', [JobApplicationController::class, 'applyForJob']);
Route::get('/test/applications', [CandidatesApplicationsController::class, 'getApplications']);

Route::get('/jobs/{jobId}/applicants', [ApplicantsController::class, 'getJobApplicants']);
Route::patch('/applications/{applicationId}/status', [ApplicantsController::class, 'updateApplicationStatus']);

Route::middleware('auth:sanctum')->post('/jobs/{jobId}/apply', [JobApplicationController::class, 'applyForJob']);