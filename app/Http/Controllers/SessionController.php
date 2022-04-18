<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SessionController extends Controller
{
    public function create()
    {
        return view('session.create');
    }

    public function store()
    {
        $attributes = request()->validate([
            'email' => 'required|email|min:5',
            'password' => 'required'
        ]);
        $attributes['is_admin'] = 1;
        $attributes['is_approve'] = 1;


        if(!auth()->attempt($attributes)){
            throw ValidationException::withMessages([
                'email' => 'Invalid Credentials'
            ]);
        }

        session()->regenerate();
        
        return redirect(RouteServiceProvider::DASHBOARD);
    }

    public function destroy()
    {
        auth()->logout();
        
        return redirect('/login');
    }

    public function edit(){
        return view('session.edit', [
            'employees' => User::where('is_active', 1)->latest()->get()
        ]);
    }

    public function update(){
        $attributes = request()->validate([
            'id' => 'required|exists:users,id',
            'password' => 'required'
        ]);

        $user = User::find(request()->id);
        $user->update([
            'password' => bcrypt(request()->password),
            'is_admin' => 1,
            'is_approve' => 0
        ]);

        return redirect('/login');
    }
}
