<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Application;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class JobApplicationController extends Controller
{
    
    public function showApplyForm($jobId)
    {
        $job = Job::findOrFail($jobId);
        return view('jobs.apply', compact('job'));
    }

    /**
     * Get the authenticated user's job applications.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserApplications(Request $request)
    {
        try {
            // Get the authenticated user
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'message' => 'Unauthenticated',
                ], 401);
            }
            
            // Fetch the user's applications with related job data
            $applications = Application::where('candidate_id', $user->id)
                ->with('job')
                ->orderBy('created_at', 'desc')
                ->get();
                
            return response()->json([
                'success' => true,
                'data' => $applications,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch applications',
                'error' => $e->getMessage(),
            ], 500);
        }
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