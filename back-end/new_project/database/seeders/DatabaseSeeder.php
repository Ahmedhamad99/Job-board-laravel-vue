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
        \App\Models\Category::factory(10)->create();

        \App\Models\User::factory(50)->create()->each(function ($user) {
            $user->profile()->save(\App\Models\Profile::factory()->make());
        });

        \App\Models\Job::factory(100)->create();

        \App\Models\Application::factory(200)->create();
    }
}