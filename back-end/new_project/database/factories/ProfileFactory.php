<?php

namespace Database\Factories;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileFactory extends Factory
{
    protected $model = Profile::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(), // مستخدم عشوائي
            'bio' => $this->faker->paragraph,
            'profile_picture' => $this->faker->imageUrl(200, 200, 'people'), // صورة وهمية
            'resume' => $this->faker->url, // رابط وهمي للسيرة الذاتية
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
