<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Profile;
use Illuminate\Support\Facades\Log;

class EnsureProfileExists
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        
        if ($user && !$user->profile) {
            // Create a default profile if one doesn't exist
            Profile::create([
                'user_id' => $user->id,
                'bio' => 'Welcome to my profile',
                'profile_picture' => 'profiles/default.jpg',
                'phone' => '',
                'address' => '',
            ]);
            
            Log::info('Created default profile for user: ' . $user->id);
        }
        
        return $next($request);
    }
}