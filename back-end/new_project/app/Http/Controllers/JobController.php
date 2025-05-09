<?php

namespace App\Http\Controllers;

use App\Http\Requests\JobRequest;
use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index(){
        $jobs = Job::all();
        return response()->json(compact('jobs'));
    }

    public function store(JobRequest $request){
        Job::create($request->all());
        return response()->json(['status'=>true,'message'=>'Job created successfully']);
    }

    public function edit($id){
        $job = Job::findOrFail($id);
        return response()->json(compact('job')); 
    }
    
    
}