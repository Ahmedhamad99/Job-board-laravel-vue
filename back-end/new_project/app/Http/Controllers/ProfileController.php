<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Profile;
use App\Http\Requests\UpdateProfileRequest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
   public function show()
   {
     $user = auth()->user();
     
     if (!$user) {
        return response()->json(['message' => 'User not authenticated'], 401);
     }

     // Create a default profile if one doesn't exist
     if (!$user->profile) {
        try {
            $profile = Profile::create([
                'user_id' => $user->id,
                'bio' => 'Welcome to my profile',
                'profile_picture' => 'profiles/default.jpg',
                'phone' => '',
                'address' => '',
            ]);
            
            Log::info('Created default profile for user: ' . $user->id);
            
            return response()->json([
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ],
                'profile' => $profile,
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating profile: ' . $e->getMessage());
            return response()->json(['message' => 'Error creating profile', 'error' => $e->getMessage()], 500);
        }
     }

     return response()->json([
        'user' => [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ],
        'profile' => $user->profile,
     ]);
   }

 public function update(UpdateProfileRequest $request)
{
    $user = auth()->user(); 
         
    if (!$user) {
        return response()->json(['message' => 'User not authenticated'], 401);
    }

    $data = $request->validated();

    if (isset($data['password'])) {
        $data['password'] = bcrypt($data['password']);
        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);
    } else {
        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);
    }

    if ($request->hasFile('profile_picture')) {
        $data['profile_picture'] = $request->file('profile_picture')->store('profiles', 'public');
    }

    if ($request->hasFile('resume')) {
        $data['resume'] = $request->file('resume')->store('resumes', 'public');
    }

    $profile = $user->profile()->updateOrCreate(['user_id' => $user->id], [
        'phone' => $data['phone'] ?? null,
        'address' => $data['address'] ?? null,
        'bio' => $data['bio'] ?? null,
        'profile_picture' => $data['profile_picture'] ?? $user->profile->profile_picture ?? 'profiles/default.jpg',
        'resume' => $data['resume'] ?? $user->profile->resume ?? null,
    ]);

    return response()->json([
        'message' => 'Profile updated successfully',
        'user' => $user,
        'profile' => $profile,
    ]);
}

}