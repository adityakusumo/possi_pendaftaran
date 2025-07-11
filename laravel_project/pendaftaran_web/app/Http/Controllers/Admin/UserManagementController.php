<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MstClub;
use App\Models\SpecialUser; // NEW: Import the SpecialUser model
use Spatie\Permission\Models\Role; // Import Spatie's Role model
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash; // Import Hash facade
use Illuminate\Validation\Rules\Password; // Import Password rules for strong validation
use Illuminate\Support\Facades\Log; // Ensure Log is imported for error logging
use Carbon\Carbon; // NEW: Import Carbon for date calculations

class UserManagementController extends Controller
{
    // Ensure only admins can access methods in this controller
    // public function __construct()
    // {
    //     $this->middleware(['auth', 'role:admin']);
    // }

    public function index(Request $request)
    {
        // dd('Index method hit!');
        // Get the search term from the request
        $cari = $request->cari;

        // Start a query on the User model
        $usersQuery = User::query();

        // If a search term is present, apply the search conditions
        if ($cari) {
            $usersQuery->where(function ($query) use ($cari) {
                $query->where('name', 'like', "%" . $cari . "%")
                    ->orWhere('email', 'like', "%" . $cari . "%");
            });
        }

        // Paginate the results, showing 10 users per page
        $users = $usersQuery->paginate(10);

        // dd($users);

        // Get all roles for the dropdowns (as you already do)
        $roles = Role::all();
        $clubs = MstClub::orderBy('NAMACLUB', 'asc')->get();

        // Return the view with the users, roles, and the search term (to persist it in the input)
        return view('settings', [
            'users' => $users,
            'roles' => $roles,
            'clubs' => $clubs,
            'cari' => $cari // Pass the search term back to the view
        ]);
    }

    /**
     * Update the user's role and club information.
     */
    public function updateUser(Request $request, User $user)
    {
        // $allowedSpatieRoles = Role::pluck('name')->toArray();
        // dd('Allowed Spatie Roles:', $allowedSpatieRoles, 'Submitted Role:', $request->input('role')); // Check what's actually being used for validation


        // try {
        //     $validatedData = $request->validate([
        //         'role' => ['required', 'string', Rule::in(Role::pluck('name')->toArray(), 'special')],
        //         'club_IDCLUB' => [
        //             'nullable',
        //             'string',
        //             Rule::exists('MstClub', 'IDCLUB')
        //         ],
        //     ]);
        // } catch (\Illuminate\Validation\ValidationException $e) {
        //     // Log the validation errors
        //     Log::error('Validation failed for user update:', $e->errors());
        //     // Dump and die to see errors directly in browser
        //     dd('Validation Errors:', $e->errors());
        //     // Return back with errors (if dd() is commented out)
        //     // return back()->withErrors($e->errors())->withInput();
        // }

        // dd('Validation passed! Request data:', $validatedData); // After successful validation


        $request->validate([
            // NEW: Allow 'special' in the role validation
            'role' => ['required', 'string', Rule::in(Role::pluck('name')->toArray(), 'special')],
            'club_IDCLUB' => [
                'nullable',
                'string', // Or 'integer' if IDCLUB is numeric
                Rule::exists('MstClub', 'IDCLUB')
            ],
        ]);

        // Self-role-change protection
        if (auth()->user()->id === $user->id && $request->role !== 'admin') {
            return back()->withErrors(['role' => 'You cannot change your own role from admin.']);
        }

        // --- NEW: Handle Role Assignment (including 'special' logic) ---
        $newRole = $request->input('role');
        $userEmail = $user->email; // Get user's email for special_users table operations

        // 1. Remove all current Spatie roles from the user
        $user->syncRoles([]);

        // 2. Assign the new Spatie role IF it's not 'special'
        if ($newRole !== 'special') {
            $user->assignRole($newRole);
            // If the user's role is changed FROM 'special' to another role, remove them from special_users table
            SpecialUser::where('email', $userEmail)->delete();
            Log::info("User {$user->id} ({$user->email}) role changed to '{$newRole}'. Removed from special_users table.");
        } else {
            // If the new role IS 'special'
            // Insert/update into special_users table with an expiry date
            SpecialUser::updateOrCreate(
                ['email' => $userEmail],
                [
                    'name' => $user->name, // Use user's current name
                    'expired_at' => Carbon::now()->addDays(30),
                ]
            );
            Log::info("User {$user->id} ({$user->email}) assigned 'special' role. Added/updated in special_users table.");
        }
        // --- END NEW Role Logic ---

        // 2. Update Club Properties (remains mostly the same, ensuring it happens AFTER role)
        $selectedClub = null;
        if ($request->filled('club_IDCLUB')) {
            $selectedClub = MstClub::where('IDCLUB', $request->club_IDCLUB)->first();
        }

        if ($selectedClub) {
            $user->update([
                'KDPROP' => $selectedClub->KDPROP,
                'NAMAPROP' => $selectedClub->NAMAPROP,
                'KDJENIS' => $selectedClub->KDJENIS,
                'JENIS' => $selectedClub->JENIS,
                'KDKOTA' => $selectedClub->KDKOTA,
                'NAMAKOTA' => $selectedClub->NAMAKOTA,
                'KDCLUB' => $selectedClub->KDCLUB,
                'IDCLUB' => $selectedClub->IDCLUB,
                'NAMACLUB' => $selectedClub->NAMACLUB,
            ]);
            Log::info("User {$user->id} club updated to {$selectedClub->NAMACLUB}.");
        } else {
            // If no club selected or club not found
            $user->update([
                'KDPROP' => null,
                'NAMAPROP' => null,
                'KDJENIS' => null,
                'JENIS' => null,
                'KDKOTA' => null,
                'NAMAKOTA' => null,
                'KDCLUB' => null,
                'IDCLUB' => null,
                'NAMACLUB' => null,
            ]);
            Log::info("User {$user->id} club details cleared.");
        }

        return back()->with('status', 'User updated successfully!');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroyUser(User $user)
    {
        // Prevent admin from deleting themselves (optional but recommended)
        if (auth()->user()->id === $user->id) {
            return back()->withErrors(['delete_user' => 'You cannot delete your own account.']);
        }

        // NEW: Before deleting the user, also remove them from special_users table if they exist
        SpecialUser::where('email', $user->email)->delete();
        Log::info("User {$user->id} ({$user->email}) deleted. Removed from special_users table (if existed).");

        $user->delete();

        return back()->with('status', 'User deleted successfully!');
    }

    /**
     * Resets the password for a given user.
     */
    public function resetPassword(Request $request, User $user)
    {
        // Validate the new password received from the frontend
        // This ensures the generated password meets your server-side requirements
        $request->validate([
            'new_password' => [
                'required',
                'string',
                Password::min(20) // Minimum 20 characters
                    ->mixedCase() // At least one uppercase and one lowercase
                    ->numbers()   // At least one number
                    ->symbols(),  // At least one special character
            ],
        ]);

        try {
            // Hash the new password before saving it to the database
            $user->password = Hash::make($request->new_password);
            $user->save();
            Log::info("Password reset for user {$user->id} ({$user->email}).");

            return response()->json(['message' => 'Password reset successfully!'], 200);
        } catch (\Exception $e) {
            // Log the error for debugging purposes
            Log::error('Error resetting password for user ' . $user->id . ': ' . $e->getMessage());
            // Return an error response to the frontend
            return response()->json(['message' => 'Failed to reset password. Please try again.', 'error' => $e->getMessage()], 500);
        }
    }
}
