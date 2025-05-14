<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /* this function is used to get all users and jobs from database and return them in json format
      and return this job has been pending and accepted and rejected
    */
   public function getAlljobs()
    {
        $jobs = Job::paginate(10);
        return response()->json(['jobs' => $jobs]);
    }

    public function getAllEmployers(){
        $employers = User::where('role', 'employer')->paginate(10);
        return response()->json(['employers' => $employers]);
    }


    /*
    this function it return all pending jobs 
    */
    //==>
    public function pendingJobs(){
        $pendingJobs = Job::where('status', 'pending')->paginate(10);
        return response()->json(['pendingJobs' => $pendingJobs]);
    }
    //==>
    public function rejectedJobs(){
        $rejectedJobs = Job::where('status', 'rejected')->paginate(10);
        return response()->json(['rejectedJobs' => $rejectedJobs]);
    }

    public function acceptedJobs(){
        $acceptedJobs = Job::where('status', 'accepted')->paginate(10);
        return response()->json(['acceptedJobs' => $acceptedJobs]);
    }    
    public function searchJob(){
        if(request()->has('query')){
            $query = request()->input('query');
            $jobs = Job::where('title', 'like', '%' . $query . '%')->paginate(10);
            return response()->json(['jobs' => $jobs]);
        }
        else{
            return response()->json(['message' => 'No query provided']);
        }
       
    }
    /*
    this function it accepted job and return message that job has been accepted 
    */
    //==>
    public function acceptedJob(Request $request){
        $job = Job::find($request->id);
        if(!$job){
            return response()->json(['message' => 'Job not found']);
        }
        else{
            $job->status = 'accepted';
            $job->save();
            return response()->json(['message' => 'Job has been accepted']);
        }
       
       
    }

    /*
    this function it rejected job and return message that job has been rejected
    */ 
    // ==>
    public function rejectedJob(Request $request){

        $job = Job::find($request->id);
        if(!$job){
            return response()->json(['message' => 'Job not found']);
        }
        else{
            $job->status = 'rejected';
            $job->save();
            // $job->delete();
            return response()->json(['message' => 'Job has been rejected']);
        }
       
    }
  
    /*
     this function it update job and return message that job has been updated
     */
     //==>
    public function updateJob(Request $request){

        $job = Job::find($request->id);
        $job->title = $request->title;
        $job->description = $request->description;
        $job->location = $request->location;
        $job->status = $request->status;
        $job->save();
        return response()->json(['message' => 'Job has been updated']);
    }
   
    /*
      this function it delete job and return message that job has been deleted
    
    */
    // ==>
    public function deleteJob(Request $request){

        $job = Job::find($request->id);
        if($job){
            $job->delete();
            return response()->json(['message' => 'Job has been deleted']);
        }
        else{
            return response()->json(['message' => 'Job not found']);
        }
        
    }
     // to show one job //==>
    public function showJob(Request $request){
        $job = Job::find($request->id);
        if($job){
            return response()->json($job);
        }
        else{
            return response()->json(['message' => 'Job not found']);
        }
        
    }
    // to create a job //==>
    public function createJob(Request $request){
        $job = new Job();
        $job->title = $request->title;
        $job->description = $request->description;
        $job->salary_max = $request->salary_max;
        $job->salary_min = $request->salary_min;
        $job->location = $request->location;
        $job->employer_id = $request->employer_id;
        $job->category_id = $request->category_id;
        
        $job->status = $request->status;
        $job->save();
        return response()->json(['message' => 'Job has been created']);
    }
    
    public function getAllusers()
    {
        $users = User::paginate(10);
        return response()->json(['users' => $users]);
    }
    public function createUser(Request $request){
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->save();
        return response()->json(['message' => 'User has been created']);
    }
    public function showUser(Request $request){
        $user = User::find($request->id);
        if($user){
            return response()->json($user);
        }
        else{
            return response()->json(['message' => 'User not found']);
        }
    }
    public function updateUser(Request $request){

        $user = User::find($request->id);
        if($user){
                    $user->name = $request->name;
                    $user->email = $request->email;
                    $user->password = $request->password;
                    $user->save();
                    return response()->json(['message' => 'User has been updated']);
        }
        else{
            return response()->json(['message' => 'User not found']);
        }
    }
    public function deleteUser(Request $request){
        $user = User::find($request->id);
        if($user){
            $user->delete();
            return response()->json(['message' => 'User has been deleted']);
        }
        else{
            return response()->json(['message' => 'User not found']);
        }
        
    }

    public function searchUser(){
        if(request()->has('query')){
            $query = request()->input('query');
            $users = User::where('name', 'like', '%'.$query.'%')->paginate(10);
            return response()->json(['users' => $users]);
        }
        else{
            return response()->json(['message' => 'No query provided']);
        }
    }
   public function getAllcatigories()
    {
        $categories = Category::paginate(5);
        return response()->json(['categories' => $categories]);
    }

    

    






}