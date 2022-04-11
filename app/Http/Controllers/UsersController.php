<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as reqFas;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    public function index()
    {
        return view('users.index', [
            'users' => User::where('is_active', '=', '1')->latest()->get()
        ]);
    }

    public function store()
    {
        $attributes = request()->validate([
            'emp_id' => 'required|min:1|unique:users,emp_id',
            'name' => 'required|min:2',
            'email' => 'required|min:5|max:75|email',
            'phone' => 'required|digits:10|numeric',
            'joined_at' => 'required'
        ]);
        $attributes['created_by'] = auth()->user()->id;

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
            'emp_id' => ['required', 'min:1', Rule::unique('users', 'emp_id')->ignore($user)],
            'name' => 'required|min:2',
            'email' => 'required|min:3|max:75|email',
            'phone' => 'required|digits:10|numeric',
            'joined_at' => 'required'
        ]);
        $attributes['updated_by'] = auth()->user()->id;

        $user->update($attributes);
        
        return redirect(RouteServiceProvider::EMPLOYEES)->with('success', 'Update Successful');
    }

    public function destroy(User $user)
    {
        $user->update(['is_active' => 0]);

        return redirect(RouteServiceProvider::EMPLOYEES)->with('success', 'Deleted Successful');
    }
}
