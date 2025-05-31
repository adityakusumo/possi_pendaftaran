<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MstClub;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    // public function create(): View
    // {
    //     return view('auth.register');
    // }

    public function create(): View
    {
        // Fetch clubs and order them by name for the dropdown
        $clubs = MstClub::orderBy('NAMACLUB', 'asc')->get();

        // Pass the clubs to the registration view
        return view('auth.register', compact('clubs'));
    }    

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'club_IDCLUB' => ['required', 'string', 'exists:MstClub,IDCLUB'], // <--- New validation rule
        ]);

         // Find the selected MstClub record based on the submitted IDCLUB
        $selectedClub = MstClub::where('IDCLUB', $request->club_IDCLUB)->firstOrFail();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // <--- Add all relevant club attributes here
            'IDCLUB' => $selectedClub->IDCLUB,
            'KDPROP' => $selectedClub->KDPROP,
            'NAMAPROP' => $selectedClub->NAMAPROP,
            'KDJENIS' => $selectedClub->KDJENIS,
            'JENIS' => $selectedClub->JENIS,
            'KDKOTA' => $selectedClub->KDKOTA,
            'NAMAKOTA' => $selectedClub->NAMAKOTA,
            'KDCLUB' => $selectedClub->KDCLUB, // KDCLUB might be redundant if IDCLUB is the true unique identifier, but including as requested
            'NAMACLUB' => $selectedClub->NAMACLUB,            
        ]);

        // Ensure the 'user' role exists in your database first!
        $userRole = Role::where('name', 'user')->first();

        if ($userRole) {
            $user->assignRole($userRole);
        } else {
            // Optional: Log an error or create the role if it doesn't exist
            \Log::warning("Role 'user' not found during registration for user: " . $user->email);
            // Example to create it if missing (handle this carefully in production)
            // Role::create(['name' => 'user', 'guard_name' => 'web']);
            // $user->assignRole('user');
        }        

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
