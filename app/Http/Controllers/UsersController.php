<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as reqFas;

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
            'emp_id' => 'required|min:1|unique:users,emp_id',
            'name' => 'required|min:2',
            'email' => 'required|min:3|max:75|email',
            'phone' => 'required|digits:10|numeric',
            'joined_at' => 'required'
        ]);

        User::create($attributes);

        return back()->with('success', "User Created");
    }

    public function edit(User $user)
    {
        return view('users.index', [
            'users' => User::latest()->get(),
            'editUser' => $user,
            'id' => $user->id
        ]);
    }

    public function update(User $user)
    {
        $attributes = request()->validate([
            'emp_id' => 'required|min:1|unique:users,emp_id',
            'name' => 'required|min:2',
            'email' => 'required|min:3|max:75|email',
            'phone' => 'required|digits:10|numeric',
            'joined_at' => 'required'
        ]);

        $user->update($attributes);
        
        return redirect(RouteServiceProvider::EMPLOYEES)->with('success', 'Update Successful');
    }
}
