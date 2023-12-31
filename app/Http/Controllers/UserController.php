<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function show(\App\Models\User $user)
    {
        return view('users.show')->with(compact('user'));
    }
}
