<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
   public function index()
    {
        $users = User::all();
        return view('index', compact('users'));
    }



}