<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role; // Import Spatie's Role model

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

        // Return the view with the users, roles, and the search term (to persist it in the input)
        return view('settings', [
            'users' => $users,
            'roles' => $roles,
            'cari' => $cari // Pass the search term back to the view
        ]);
    }

    /**
     * Update the specified user's role.
     */
    public function updateRole(Request $request, User $user)
    {
        // DD 1: See all incoming request data
        // dd($request->all());

        $request->validate([
            'role' => ['required', 'string', 'exists:roles,name'], // Validate role exists in your roles table
        ]);

        // DD 2: See the specific role name being requested
        // dd($request->role);

        // DD 3: See the user object before role change and their current roles
        // dd($user, $user->getRoleNames());

        // Prevent admin from changing their own role (optional but recommended)
        if (auth()->user()->id === $user->id && $request->role !== 'admin') {
            return back()->withErrors(['role' => 'You cannot change your own role from admin.']);
        }

        // DD 4: See what roles the user *currently* has right before syncRoles
        // dd($user->getRoleNames());        

        // Remove all current roles and assign the new one
        $user->syncRoles($request->role);

        // DD 5: See what roles the user *now* has immediately after syncRoles
        // dd($user->getRoleNames()); // This should show the new role if successful

        // DD 6: If you reach here, it means syncRoles executed without crashing
        // dd('Role updated successfully (before redirect)');        

        return back()->with('status', 'User role updated successfully!');
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

        $user->delete();

        return back()->with('status', 'User deleted successfully!');
    }
}