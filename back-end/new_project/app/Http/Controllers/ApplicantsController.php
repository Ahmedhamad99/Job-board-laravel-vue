<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\User;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ApplicantsController extends Controller
{
    /**
     * Get all applicants for a specific job.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $jobId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getJobApplicants(Request $request, $jobId)
    {
        try {
            // Get the authenticated user
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated',
                ], 401);
            }
            
            // First check if the job exists
            $job = Job::find($jobId);
            
            if (!$job) {
                return response()->json([
                    'success' => false,
                    'message' => 'Job not found',
                ], 404);
            }
            
            // Check if the user is the employer of this job or an admin
            $isEmployer = $job->employer_id === $user->id;
            $isAdmin = $user->role === 'admin';
            
            if (!$isEmployer && !$isAdmin) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to view applicants for this job',
                ], 403);
            }
            
            // Fetch all applications for this job with candidate information
            $applications = Application::where('job_id', $jobId)
                ->with(['candidate' => function($query) {
                    $query->select('id', 'name', 'email', 'role', 'created_at');
                }])
                ->orderBy('created_at', 'desc')
                ->get();
                
            return response()->json([
                'success' => true,
                'data' => $applications,
                'job' => $job,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch applicants',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update application status (accept or reject).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $applicationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateApplicationStatus(Request $request, $applicationId)
    {
        try {
            // Validate request
            $request->validate([
                'status' => 'required|string|in:accepted,rejected,pending',
            ]);
            
            // Get the authenticated user
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated',
                ], 401);
            }
            
            // Find the application
            $application = Application::with('job')->find($applicationId);
            
            if (!$application) {
                return response()->json([
                    'success' => false,
                    'message' => 'Application not found',
                ], 404);
            }
            
            // Check if the user is the employer of this job or an admin
            $isEmployer = $application->job->employer_id === $user->id;
            $isAdmin = $user->role === 'admin';
            
            if (!$isEmployer && !$isAdmin) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to update this application',
                ], 403);
            }
            
            // Update the application status
            $application->status = $request->status;
            $application->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Application status updated successfully',
                'data' => $application,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update application status',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
} 