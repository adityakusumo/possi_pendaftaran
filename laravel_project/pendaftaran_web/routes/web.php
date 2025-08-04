<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Admin\UserManagementController; // Ensure this is correct namespace
use App\Http\Controllers\CompetitionSettingController;
use App\Http\Controllers\FormA1Controller;
use App\Http\Controllers\FormA3Controller;

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
    Route::post('/form-a1/kontingen/save', [FormA1Controller::class, 'saveKontingen'])->name('form_a1.saveKontingen');
    Route::delete('/kontingen', [FormA1Controller::class, 'destroyKontingen'])->name('kontingen.destroy');

    Route::get('/form-a1/daftar-atlet', [FormA1Controller::class, 'daftarAtlet'])->name('form_a1.daftarAtlet');
    Route::post('/form-a1/daftar-atlet', [FormA1Controller::class, 'saveAtlet'])->name('atlet.saveAtlet');
    Route::delete('/form-a1/daftar-atlet', [FormA1Controller::class, 'destroyAtlet'])->name('atlet.destroyAtlet'); // Route for deletion
    Route::get('/form-a1/daftar-atlet/cari', [FormA1Controller::class, 'searchNias'])->name('atlet.niasSearch');

    Route::get('/form-a3/nomor-perorangan', [FormA3Controller::class, 'index'])->name('form_a3.nomorPerorangan');
    Route::post('/form-a3/save-perorangan', [FormA3Controller::class, 'savePerorangan'])->name('form_a3.savePerorangan');
    Route::delete('/form-a3/delete-perorangan/{id}', [FormA3Controller::class, 'deletePerorangan'])->name('form-a3.delete');

    Route::get('/form-a3/nomor-estafet', [FormA3Controller::class, 'indexEstafet'])->name('form_a3.nomorEstafet');
    Route::post('/form-a3/save-estafet', [FormA3Controller::class, 'saveEstafet'])->name('form_a3.saveEstafet');
    Route::delete('/form-a3/delete-estafet/{id}', [FormA3Controller::class, 'deleteEstafet'])->name('form-a3.deleteEstafet');
});

require __DIR__ . '/auth.php';
