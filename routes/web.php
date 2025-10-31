<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\Login;
use App\Livewire\Admin\UserManagement;

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

    // Admin routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', UserManagement::class)->name('users');
    });

    // Logout
    Route::post('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});
