<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Profile;
use App\Models\Category;
use App\Models\Job;
use App\Models\Application;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $categories = \App\Models\Category::factory(10)->create();

        \App\Models\User::factory(10)->create()->each(function ($user) {
            $user->profile()->save(\App\Models\Profile::factory()->make());
        });

        foreach ($categories as $category) {
        Job::factory()->count(3)->create([
            'category_id' => $category->id
        ]);
    }

        \App\Models\Application::factory(20)->create();
    }
}
