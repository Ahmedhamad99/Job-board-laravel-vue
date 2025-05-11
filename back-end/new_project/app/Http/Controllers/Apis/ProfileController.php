<?php

namespace App\Http\Controllers\Apis;
use App\Models\User;
use App\Http\Requests\UpdateProfileRequest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
   public function show()
{
     $user = auth()->user(); 
    //$user = User::first(); 

    if (!$user || !$user->profile) {
        return response()->json(['message' => 'Profile not found'], 404);
    }

    return response()->json([
        'user' => [
            'name' => $user->name,
            'email' => $user->email,
        ],
        'profile' => $user->profile,
    ]);
}



 public function update(UpdateProfileRequest $request)
{
    // $user = User::first(); 
        $user = auth()->user(); 


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
        'profile_picture' => $data['profile_picture'] ?? $user->profile->profile_picture ?? null,
        'resume' => $data['resume'] ?? $user->profile->resume ?? null,
    ]);

    return response()->json([
        'message' => 'Profile updated successfully',
        'user' => $user,
        'profile' => $profile,
    ]);
}


}

