<?php

namespace Database\Factories;

use App\Models\Job;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobFactory extends Factory
{
    protected $model = Job::class;

    public function definition()
    {
        return [
            'employer_id' => User::factory()->state(['role' => 'employer']), // مستخدم بصلاحية employer
            'title' => $this->faker->jobTitle,
            'description' => $this->faker->paragraphs(3, true),
            'skills' => json_encode($this->faker->words(5)), // مهارات كـ JSON
            'salary_min' => $this->faker->numberBetween(3000, 5000),
            'salary_max' => $this->faker->numberBetween(6000, 10000),
            'location' => $this->faker->city,
            'category_id' => Category::factory(), // فئة عشوائية
            'status' => $this->faker->randomElement(['accepted', 'pending', 'rejected']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}