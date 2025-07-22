<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MstClub;
// Removed: use App\Models\PilihanPesertaKotaKab; // No longer needed for options
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB; // <-- IMPORTANT: Add this import for DB::raw()

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        // Fetch clubs and order them by name for the dropdown
        $clubs = MstClub::orderBy('NAMACLUB', 'asc')->get();

        // --- MODIFIED: Fetch kotaKabDomOptions directly from MstClub ---
        $kotaKabDomOptions = MstClub::select(DB::raw("CONCAT(JENIS, ' ', NAMAKOTA) AS full_domisili"))
                                ->distinct() // Get only unique combinations
                                ->orderBy('full_domisili', 'asc') // Order the results alphabetically
                                ->pluck('full_domisili', 'full_domisili'); // Use the combined string as both key and value
        // -----------------------------------------------------------------

        // Pass the clubs to the registration view
        return view('auth.register', compact('clubs', 'kotaKabDomOptions'));
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
            'club_IDCLUB' => ['required', 'string', 'exists:MstClub,IDCLUB'],
            'kota_kab_dom' => ['required', 'string', 'max:255'], // This is the 'JENIS NAMAKOTA' string
        ]);

        // 1. Find the selected MstClub record based on the submitted club_IDCLUB
        $selectedClub = MstClub::where('IDCLUB', $request->club_IDCLUB)->firstOrFail();

        // 2. Find the MstClub record that matches the selected kota_kab_dom string
        // This logic remains the same as it correctly parses and looks up
        $domisiliJenis = null;
        $domisiliNamakota = null;
        $matchingMstClubForDomisili = null;

        if ($request->filled('kota_kab_dom')) {
            $selectedDomisiliString = $request->input('kota_kab_dom');
            $parts = explode(' ', $selectedDomisiliString, 2);
            $domisiliJenis = $parts[0] ?? null;
            $domisiliNamakota = $parts[1] ?? null;

            $matchingMstClubForDomisili = MstClub::where('JENIS', $domisiliJenis)
                                                  ->where('NAMAKOTA', $domisiliNamakota)
                                                  ->first();

            if (!$matchingMstClubForDomisili) {
                Log::warning("No MstClub record found for selected domisili during registration: {$selectedDomisiliString}");
            }
        }

        // Prepare domisili fields for user creation
        $domisiliFields = [
            'KDPROPDOM' => null,
            'NAMAPROPDOM' => null,
            'KDJENISDOM' => null,
            'JENISDOM' => null,
            'KDKOTADOM' => null,
            'NAMAKOTADOM' => null,
        ];

        if ($matchingMstClubForDomisili) {
            $domisiliFields = [
                'KDPROPDOM' => $matchingMstClubForDomisili->KDPROP,
                'NAMAPROPDOM' => $matchingMstClubForDomisili->NAMAPROP,
                'KDJENISDOM' => $matchingMstClubForDomisili->KDJENIS,
                'JENISDOM' => $matchingMstClubForDomisili->JENIS,
                'KDKOTADOM' => $matchingMstClubForDomisili->KDKOTA,
                'NAMAKOTADOM' => $matchingMstClubForDomisili->NAMAKOTA,
            ];
        }


        $user = User::create(array_merge([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // Club attributes from selectedClub
            'IDCLUB' => $selectedClub->IDCLUB,
            'KDPROP' => $selectedClub->KDPROP,
            'NAMAPROP' => $selectedClub->NAMAPROP,
            'KDJENIS' => $selectedClub->KDJENIS,
            'JENIS' => $selectedClub->JENIS,
            'KDKOTA' => $selectedClub->KDKOTA,
            'NAMAKOTA' => $selectedClub->NAMAKOTA,
            'KDCLUB' => $selectedClub->KDCLUB,
            'NAMACLUB' => $selectedClub->NAMACLUB,
            // Store the raw combined string in 'kota_kab_dom' if you have this column in 'users' table
            'kota_kab_dom' => $request->kota_kab_dom,
        ], $domisiliFields)); // Merge the domisiliFields array here

        // Ensure the 'user' role exists in your database first!
        $userRole = Role::where('name', 'user')->first();

        if ($userRole) {
            $user->assignRole($userRole);
        } else {
            Log::warning("Role 'user' not found during registration for user: " . $user->email);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
