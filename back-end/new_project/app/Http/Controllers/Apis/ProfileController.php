<?php

namespace App\Http\Controllers\Apis;
use App\Models\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
   public function show()
{
    // $user = User::first();
    $user = auth()->user();
        // return response()->json($user->profile);


    if ($user && $user->profile) {
        return response()->json($user->profile);
    }

    return response()->json(['message' => 'Profile not found'], 404);
}


    public function update(Request $request)
    {
        // $user = User::first();
        $user = auth()->user();

        $data = $request->validate([
            'bio' => 'nullable|string',
          
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string',
            'profile_picture' => 'nullable|image|max:5120',
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        if ($request->hasFile('profile_picture')) {
            $data['profile_picture'] = $request->file('profile_picture')->store('profiles', 'public');
        }

        if ($request->hasFile('resume')) {
            $data['resume'] = $request->file('resume')->store('resumes', 'public');
        }

        $user->profile()->updateOrCreate(['user_id' => $user->id], $data);

        return response()->json(['message' => 'Profile updated successfully']);
    }
}

