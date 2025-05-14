<?php

namespace App\Http\Controllers;

use App\Http\Requests\JobRequest;
use App\Http\traits\ApiResponses;
use App\Models\Category;
use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;


class JobController extends Controller
{
    use ApiResponses;

    public function index(){
        // $user = auth()->user();
        $user = User::find(1);
        // dd($user);
        $jobs = Job::where('employer_id', $user->id)->get();
        return $this->data(compact('jobs'));
        // return response()->json(compact('jobs'));
    }

    public function show($id){
        $job = Job::findOrFail($id);
        $category = Category::where('id', $job->category_id)->first()->name;
        return $this->data(compact('job', 'category'));
        // return response()->json(compact('job'));
    }

    public function create(){
        $categories = Category::all();
        return $this->data(compact('categories'));
        // return response()->json(compact('categories'));
    }

    public function store(JobRequest $request){
        Job::create($request->all());
        return $this->success('Job created successfully',201);
        // return response()->json(['status'=>true,'message'=>'Job created successfully']);
    }

    public function edit($id){
        $job = Job::findOrFail($id);
        $categories = Category::all();
        return $this->data(compact('job', 'categories'));
        // return response()->json(compact('job', 'categories')); 
    }

    public function update(JobRequest $request, $id){
        Job::where('id',$id)->update($request->all());
        return $this->success('Job updated successfully',200);
        // return response()->json(['status'=>true,'message'=>'Job updated successfully']);
    }

    public function destroy($id){
        Job::where('id',$id)->delete();
        return $this->success('Job deleted successfully',200);
        // return response()->json(['status'=>true,'message'=>'Job deleted successfully']);
    }
    
    
}