<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Admin\UserManagementController;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', function () {
    // If the user is already logged in, redirect them to the dashboard
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    // Otherwise, show the login page
    return view('auth.login'); // Breeze's login view is typically in 'resources/views/auth/login.blade.php'
});

// Route::get('/settings', function () {
//     return view('settings'); // This will look for resources/views/settings.blade.php
// })->middleware(['auth'])->name('settings');

// Route::get('/settings', function () {
//     // Fetch all users
//     $users = User::all();

//     // Fetch all roles (assuming you're using Spatie's laravel-permission)
//     $roles = Role::all();

//     // Pass users and roles to the view
//     return view('settings', compact('users', 'roles'));
// })->middleware(['auth', 'role:admin'])->name('settings');

Route::middleware(['auth', 'role:admin'])->group(function () {
    // OLD ROUTE (REMOVE OR COMMENT OUT THIS BLOCK):
    /*
    Route::get('/settings', function () {
        // Fetch all users
        $users = User::all();
        // Fetch all roles (assuming you're using Spatie's laravel-permission)
        $roles = Role::all();
        // Pass users and roles to the view
        return view('settings', compact('users', 'roles'));
    })->name('settings');
    */

    // NEW ROUTE (USE THIS ONE):
    Route::get('/settings', [UserManagementController::class, 'index'])->name('settings');

    // Make sure your other settings-related routes are also defined here,
    // pointing to your UserManagementController methods
    Route::patch('/settings/{user}/update', [UserManagementController::class, 'updateUser'])->name('settings.update');
    Route::delete('/settings/{user}/delete-user', [UserManagementController::class, 'destroyUser'])->name('settings.destroy-user');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    // Route to update a user's role
    Route::patch('/settings/users/{user}/update-role', [UserManagementController::class, 'updateRole'])
        ->name('settings.update-role');

    // Route to delete a user
    Route::delete('/settings/users/{user}/delete', [UserManagementController::class, 'destroyUser'])
        ->name('settings.destroy-user');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
