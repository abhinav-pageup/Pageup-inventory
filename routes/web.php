<?php

use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function(){
    return view('dashboard', [
        'users' => 5
    ]);
});


Route::get('/employees', [UsersController::class, 'index']);
Route::post('/employees', [UsersController::class, 'store']);
Route::get('/employees/{user}/edit', [UsersController::class, 'edit']);
Route::patch('/employees/{user}', [UsersController::class, 'update']);
