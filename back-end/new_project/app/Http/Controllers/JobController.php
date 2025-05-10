<?php

namespace App\Http\Controllers;

use App\Http\Requests\JobRequest;
use App\Models\Category;
use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index(){
        $jobs = Job::all();
        return response()->json(compact('jobs'));
    }
    public function create(){
        $categories = Category::all();
        return response()->json(compact('categories'));
    }

    public function store(JobRequest $request){
        Job::create($request->all());
        return response()->json(['status'=>true,'message'=>'Job created successfully']);
    }

    public function edit($id){
        $job = Job::findOrFail($id);
        $categories = Category::all();
        return response()->json(compact('job', 'categories')); 
    }

    public function update(JobRequest $request, $id){
        Job::where('id',$id)->update($request->all());
        return response()->json(['status'=>true,'message'=>'Job updated successfully']);
    }

    public function destroy($id){
        Job::where('id',$id)->delete();
        return response()->json(['status'=>true,'message'=>'Job deleted successfully']);
    }
    
    
}