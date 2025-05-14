<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;

class JobControllerCandidate extends Controller
{
    
    public function index()
    {
        $jobs = Job::all();
        return response()->json([
            'data' => $jobs,
            'message' => 'All jobs retrieved successfully.'
        ], 200);
    }

    
    public function show($id)
    {
        $job = Job::find($id);

        if (!$job) {
            return response()->json([
                'message' => 'Job not found.'
            ], 404);
        }

        return response()->json([
            'data' => $job,
            'message' => 'Job retrieved successfully.'
        ], 200);
    }
}