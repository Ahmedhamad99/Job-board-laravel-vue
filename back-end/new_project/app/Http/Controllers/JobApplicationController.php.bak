<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobApplicationController extends Controller
{
    
    public function showApplyForm($jobId)
    {
        $job = Job::findOrFail($jobId);
        return view('jobs.apply', compact('job'));
    }

    
    public function applyForJob(Request $request, $jobId)
    {
     
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

      
        $request->validate([
            'contact_info' => 'required_without:resume',
            'resume' => 'nullable|mimes:pdf,doc,docx|max:2048',
        ]);

    
        $application = Application::create([
            'job_id' => $jobId,
            'candidate_id' => $user->id,
            'contact_info' => $request->contact_info,
            'status' => 'pending',
        ]);

        
        if ($request->hasFile('resume')) {
            $filePath = $request->file('resume')->store('resumes', 'public');
            $application->update(['resume' => $filePath]);
        }

        
        return response()->json([
            'message' => "نجاح!"
        ], 201);
    }
}
