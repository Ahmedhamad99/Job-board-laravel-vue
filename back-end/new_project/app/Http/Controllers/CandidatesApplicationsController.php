<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CandidatesApplicationsController extends Controller
{
    /**
     * Get all applications for the authenticated candidate.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getApplications(Request $request)
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
            
            // Fetch the user's applications with related job data
            $applications = Application::where('candidate_id', $user->id)
                ->with(['job' => function($query) {
                    $query->select('id', 'title', 'description', 'location', 'salary_min', 'salary_max');
                }])
                ->orderBy('created_at', 'desc')
                ->get();
                
            return response()->json([
                'success' => true,
                'data' => $applications,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch applications',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete an application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteApplication(Request $request, $id)
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
            
            // Find the application
            $application = Application::where('id', $id)
                ->where('candidate_id', $user->id)
                ->first();
            
            if (!$application) {
                return response()->json([
                    'success' => false,
                    'message' => 'Application not found or not authorized to delete',
                ], 404);
            }
            
            // Delete the resume file if it exists
            if ($application->resume) {
                $filePath = str_replace('public/', '', $application->resume);
                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
            }
            
            // Delete the application
            $application->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Application cancelled successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete application',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
} 