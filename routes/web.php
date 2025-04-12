<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return view('auth.register');
})->name('auth.register');


Route::get('logout',[AuthController::class,'logout'])->name('logout');


Route::middleware(['guest'])->group(function () {
    Route::post('login', [AuthController::class, 'loginStore'])->name('login.store');
    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::resource('users', AuthController::class)->names('users');   
    
});

Route::middleware(['auth'])->group(function () {
    Route::resource('tasks', TaskController::class)->names('tasks');
    Route::get('/dashboard', [AuthController::class, 'index'])->name('dashboard');
  
});