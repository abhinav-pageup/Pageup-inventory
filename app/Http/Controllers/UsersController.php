<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index()
    {
        return view('users.index', [
            'users' => User::latest()->get()
        ]);
    }

    public function store()
    {
        $attributes = request()->validate([
            'id' => 'required|min:1',
            'name' => 'required|min:2',
            'email' => 'required|min:3|max:75|email',
            'phone' => 'required|min:3|max:10|numeric',
            'joined' => 'required'
        ]);

        dd($attributes);
    }
}
