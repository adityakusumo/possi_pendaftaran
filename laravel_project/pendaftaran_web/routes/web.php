<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Admin\UserManagementController; // Ensure this is correct namespace
use App\Http\Controllers\CompetitionSettingController;
use App\Http\Controllers\FormA1Controller;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('auth.login');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    // User Management Routes
    Route::get('/settings', [UserManagementController::class, 'index'])->name('settings');

    // This route handles both role and club updates for a user
    Route::patch('/settings/{user}/update', [UserManagementController::class, 'updateUser'])->name('settings.update');

    // These routes are redundant if updateUser handles everything, or need to be specific
    // If updateUser handles all updates (role and club), you can remove these:
    // Route::patch('/settings/users/{user}/update-role', [UserManagementController::class, 'updateRole'])->name('settings.update-role');
    // Route::delete('/settings/users/{user}/delete', [UserManagementController::class, 'destroyUser'])->name('settings.destroy-user'); // This is already defined below

    // Keep the delete route, ensure it points to destroyUser
    Route::delete('/settings/{user}/delete-user', [UserManagementController::class, 'destroyUser'])->name('settings.destroy-user');

    // Route to reset a user's password via POST request (AJAX)
    Route::post('/settings/users/{user}/reset-password', [UserManagementController::class, 'resetPassword'])->name('settings.reset-password');

    // Competition Settings Routes
    Route::get('/competition-settings', [CompetitionSettingController::class, 'index'])->name('competition_settings');
    Route::post('/competition-settings/update-type', [CompetitionSettingController::class, 'updateCompetitionType'])->name('competition_settings.update_type');
    Route::post('/competition-settings/update-wajib-nias', [CompetitionSettingController::class, 'updateWajibNias'])->name('competition_settings.update_wajib_nias');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/form-a1/kontingen', [FormA1Controller::class, 'kontingen'])->name('form_a1.kontingen');

    // Route for saving the kontingen data
    Route::post('/form-a1/kontingen/save', [FormA1Controller::class, 'saveKontingen'])->name('form_a1.saveKontingen');
});

require __DIR__ . '/auth.php';
