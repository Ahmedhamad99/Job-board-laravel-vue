<?php

namespace Database\Factories;

use App\Models\Application;
use App\Models\Job;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApplicationFactory extends Factory
{
    protected $model = Application::class;

    public function definition()
    {
        return [
            'job_id' => Job::factory(), 
            'candidate_id' => User::factory()->state(['role' => 'candidate']), // مستخدم بصلاحية candidate
            'resume' => $this->faker->url,
            'contact_info' => $this->faker->email,
            'status' => $this->faker->randomElement(['pending', 'accepted', 'rejected']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}