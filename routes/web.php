<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\Login;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/', Login::class)->name('login');
    Route::get('/login', Login::class);
});

// Authenticated routes
Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Logout
    Route::post('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});
