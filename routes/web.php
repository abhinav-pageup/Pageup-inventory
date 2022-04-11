<?php

use App\Http\Controllers\ProductMasterController;
use App\Http\Controllers\PurchaseController;
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
Route::delete('/employees/{user}', [UsersController::class, 'destroy']);

Route::get('/products', [ProductMasterController::class, 'index']);
Route::post('/products', [ProductMasterController::class, 'store']);
Route::get('/products/{product}/edit', [ProductMasterController::class, 'edit']);
Route::patch('/products/{product}', [ProductMasterController::class, 'update']);

Route::get('/purchases', [PurchaseController::class, 'index']);
Route::get('/purchases/purchase/create', [PurchaseController::class, 'create']);
Route::post('/purchases', [PurchaseController::class, 'store']);