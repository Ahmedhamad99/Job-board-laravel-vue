<?php

namespace App\Http\Controllers\Fixed;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Application;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Exception;

class JobApplicationController extends Controller
{
    /**
     * Apply for a job
     * Enhanced version with proper error handling and debugging
     * 
     * @param Request $request
     * @param int $jobId
     * @return \Illuminate\Http\JsonResponse
     */
    public function applyForJob(Request $request, $jobId)
    {
        Log::info('Job application initiated', [
            'job_id' => $jobId,
            'has_resume' => $request->hasFile('resume'),
            'request_data' => $request->except(['resume']),
        ]);

        try {
            // Check authentication first - important for debugging
            $user = Auth::user();
            
            // Debugging code - will use user ID 1 if in dev environment
            if (!$user && config('app.env') === 'local') {
                Log::warning('Auth failed but using test user ID 1 in development mode');
                $user = User::find(1);
                if (!$user) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Test user not found (ID: 1)',
                        'dev_note' => 'Please create a test user with ID 1 or update this code'
                    ], 500);
                }
            } elseif (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized - please log in',
                    'auth_status' => 'Not authenticated'
                ], 401);
            }

            // Validate job exists
            $job = Job::find($jobId);
            if (!$job) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Job not found'
                ], 404);
            }
            
            // Validate request
            $request->validate([
                'contact_info' => 'required_without:resume',
                'resume' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            ]);
            
            // Database transaction for atomicity
            return DB::transaction(function () use ($request, $jobId, $job, $user) {
                // Create application record
                $application = Application::create([
                    'job_id' => $jobId,
                    'candidate_id' => $user->id,
                    'contact_info' => $request->contact_info,
                    'status' => 'pending',
                ]);
                
                Log::info('Application created without resume', [
                    'application_id' => $application->id,
                    'candidate_id' => $user->id,
                    'job_id' => $jobId
                ]);
                
                // Handle resume upload if present
                if ($request->hasFile('resume')) {
                    try {
                        // Create directories if they don't exist
                        $resumeDir = storage_path('app/public/resumes');
                        if (!file_exists($resumeDir)) {
                            mkdir($resumeDir, 0755, true);
                        }
                        
                        // Store file with safe filename
                        $file = $request->file('resume');
                        $filename = 'resume_' . time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
                        $file->move($resumeDir, $filename);
                        
                        // Update application with file path
                        $filePath = 'resumes/' . $filename;
                        $application->update(['resume' => $filePath]);
                        
                        Log::info('Resume uploaded successfully', [
                            'path' => $filePath, 
                            'application_id' => $application->id
                        ]);
                    } catch (Exception $e) {
                        Log::error('Resume upload failed', [
                            'error' => $e->getMessage(),
                            'application_id' => $application->id
                        ]);
                        
                        // Continue without the resume rather than failing
                        // Since we already created the application record
                    }
                }
                
                return response()->json([
                    'success' => true,
                    'message' => 'Application submitted successfully!',
                    'data' => [
                        'application_id' => $application->id,
                        'job_title' => $job->title,
                        'resume_uploaded' => isset($filePath)
                    ]
                ], 201);
            });
            
        } catch (Exception $e) {
            Log::error('Job application error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'job_id' => $jobId
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit application. Please try again.',
                'debug_info' => [
                    'error_type' => get_class($e),
                    'error_message' => $e->getMessage(),
                    'job_id' => $jobId
                ]
            ], 500);
        }
    }
    
    /**
     * Check storage configuration
     * Useful for debugging storage issues
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkStorageConfig()
    {
        try {
            $publicDiskExists = Storage::disk('public')->exists('.');
            $publicStoragePath = storage_path('app/public');
            $publicStorageExists = file_exists($publicStoragePath);
            $publicStorageWritable = is_writable($publicStoragePath);
            
            $resumeDirPath = storage_path('app/public/resumes');
            $resumeDirExists = file_exists($resumeDirPath);
            
            $symlinkPath = public_path('storage');
            $symlinkExists = file_exists($symlinkPath);
            
            return response()->json([
                'success' => true,
                'storage_config' => [
                    'public_disk_accessible' => $publicDiskExists,
                    'public_storage_exists' => $publicStorageExists,
                    'public_storage_writable' => $publicStorageWritable,
                    'resumes_dir_exists' => $resumeDirExists,
                    'symlink_exists' => $symlinkExists,
                    'public_storage_path' => $publicStoragePath,
                    'symlink_path' => $symlinkPath,
                    'environment' => config('app.env')
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Storage check failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 