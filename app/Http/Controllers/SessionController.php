<?php

namespace App\Http\Controllers;

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
}
