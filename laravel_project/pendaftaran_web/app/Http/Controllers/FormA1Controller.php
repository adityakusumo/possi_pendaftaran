<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Kompetisi;
use App\Models\PilihanPesertaKotaKab;
use App\Models\MstClub;
use App\Models\SpecialUser; // Keep this for SpecialUser table check
use App\Models\MstPeserta;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // For debugging
use Illuminate\Support\Facades\Validator; // For custom validation
use Carbon\Carbon; // Keep this for expiry date check

class FormA1Controller extends Controller
{
    public function kontingen(): View
    {
        $currentKompetisiSetting = Kompetisi::find(1);
        $jnsKompetisi = $currentKompetisiSetting ? mb_strtoupper(trim($currentKompetisiSetting->JNSKOMPETISI), 'UTF-8') : null;

        $jenisKompetisiOptions = [
            'K' => 'Antar Kota/Kab',
            'C' => 'Antar Club',
            'P' => 'Antar Provinsi',
        ];

        $namaClubsMstClub = [];
        $mstClubDetails = [];
        $namaClubsPilihanPeserta = [];
        $namaPropinsiPilihanPeserta = [];
        $pilihanPesertaKotaKabDetails = [];

        $user = Auth::user();
        $userClubNameFromUsersTable = null; // Still needed for auto-fill logic if appliedMode is 2
        $userEmail = null; // Still needed for auto-fill logic if appliedMode is 2
        $userRoleString = 'user'; // Default role string
        $appliedMode = 2; // Default to disabled mode for regular users

        $autoSelectedClubValue = null;
        $autoFillDetails = null;

        $formatSelect2Data = function ($name) {
            if ($name === null) return null;
            return ['id' => mb_strtoupper($name, 'UTF-8'), 'text' => mb_strtoupper($name, 'UTF-8')];
        };
        $formatDetailValue = function ($value) {
            return ($value !== null) ? mb_strtoupper($value, 'UTF-8') : null;
        };


        if ($user) {
            $userEmail = $user->email; // Get email for SpecialUser check

            // --- NEW: Determine appliedMode and userRoleString based on Spatie roles AND SpecialUser table ---
            $isUserAdminViaSpatie = $user->hasRole('admin');
            $isUserOperatorViaSpatie = $user->hasRole('operator');
            $isUserSpecialViaTable = false;

            if ($userEmail) { // Only check SpecialUser table if user has an email
                $specialUser = SpecialUser::where('email', $userEmail)
                    ->where('expired_at', '>', Carbon::now()) // Check if not expired
                    ->first();
                if ($specialUser) {
                    $isUserSpecialViaTable = true;
                }
            }

            if ($isUserAdminViaSpatie) {
                $appliedMode = 1;
                $userRoleString = 'admin';
            } elseif ($isUserOperatorViaSpatie) {
                $appliedMode = 1;
                $userRoleString = 'operator';
            } elseif ($isUserSpecialViaTable) {
                $appliedMode = 1;
                $userRoleString = 'special'; // Indicate it's a special user from the table
            } else {
                $appliedMode = 2; // Regular user (no admin, operator, or active special status)
                $userRoleString = 'user';
            }
            // --- END NEW Logic ---

            // This is still used in the Mode 2 auto-fill logic, so we retrieve it here
            $userClubNameFromUsersTable = $user->NAMACLUB;
        }

        // --- Logic for auto-selection and auto-fill for Mode 2 (Regular User) ---
        // This block only runs if $appliedMode is 2 (i.e., user is NOT admin/operator/special status)
        if ($appliedMode === 2 && $user && $userClubNameFromUsersTable) {
            $normalizedUserClubFromUsersTable = mb_strtoupper($userClubNameFromUsersTable, 'UTF-8');
            $userMstClubDetails = MstClub::whereRaw('UPPER(NAMACLUB) = ?', [$normalizedUserClubFromUsersTable])->first();

            if ($jnsKompetisi === 'C') {
                $autoSelectedClubValue = $formatDetailValue($userClubNameFromUsersTable);
                if ($userMstClubDetails) {
                    $autoFillDetails = [
                        'JENIS' => $formatDetailValue($userMstClubDetails->JENIS),
                        'NAMAKOTA' => $formatDetailValue($userMstClubDetails->NAMAKOTA),
                        'NAMAPROP' => $formatDetailValue($userMstClubDetails->NAMAPROP),
                    ];
                }
            } elseif ($jnsKompetisi === 'K') {
                if ($userMstClubDetails && $userMstClubDetails->NAMAKOTA) {
                    $autoSelectedClubValue = $formatDetailValue($userMstClubDetails->NAMAKOTA);
                    $relatedPilihanPeserta = PilihanPesertaKotaKab::whereRaw('UPPER(NAMAKOTA) = ?', [$autoSelectedClubValue])->first();
                    if ($relatedPilihanPeserta) {
                        $autoFillDetails = [
                            'JENIS' => $formatDetailValue($relatedPilihanPeserta->JENIS),
                            'NAMAKOTA' => $formatDetailValue($relatedPilihanPeserta->NAMAKOTA),
                            'NAMAPROP' => $formatDetailValue($relatedPilihanPeserta->NAMAPROPINSI),
                        ];
                    }
                }
            } elseif ($jnsKompetisi === 'P') {
                if ($userMstClubDetails && $userMstClubDetails->NAMAPROP) {
                    $autoSelectedClubValue = $formatDetailValue($userMstClubDetails->NAMAPROP);
                    $autoFillDetails = null;
                }
            }
        } else {
            // If mode is 1 (Admin/Operator/Special via table), ensure these are null
            $autoSelectedClubValue = null;
            $autoFillDetails = null;
        }

        // --- Fetch all options for Select2 --- (This part remains largely unchanged)
        $mstClubData = MstClub::select('NAMACLUB', 'JENIS', 'NAMAKOTA', 'NAMAPROP')
            ->whereNotNull('NAMACLUB')
            ->orderBy('NAMACLUB', 'asc')
            ->get();

        $namaClubsMstClub = $mstClubData->map(function ($item) use ($formatSelect2Data) {
            return $formatSelect2Data($item->NAMACLUB);
        })->unique('id')->values()->toArray();

        $mstClubDetails = $mstClubData->mapWithKeys(function ($item) use ($formatDetailValue) {
            return [
                mb_strtoupper($item['NAMACLUB'], 'UTF-8') => [
                    'NAMACLUB' => $formatDetailValue($item['NAMACLUB']),
                    'JENIS' => $formatDetailValue($item['JENIS']),
                    'NAMAKOTA' => $formatDetailValue($item['NAMAKOTA']),
                    'NAMAPROP' => $formatDetailValue($item['NAMAPROP']),
                ]
            ];
        })->toArray();

        $pilihanPesertaKotaKabRawData = PilihanPesertaKotaKab::select('NAMACLUB', 'JENIS', 'NAMAKOTA', 'NAMAPROPINSI')
            ->get();

        $namaClubsPilihanPeserta = PilihanPesertaKotaKab::select('NAMACLUB')
            ->distinct()
            ->whereNotNull('NAMACLUB')
            ->orderBy('NAMACLUB', 'asc')
            ->pluck('NAMACLUB')
            ->map($formatSelect2Data)
            ->toArray();

        $namaPropinsiPilihanPeserta = PilihanPesertaKotaKab::select('NAMAPROPINSI')
            ->distinct()
            ->whereNotNull('NAMAPROPINSI')
            ->orderBy('NAMAPROPINSI', 'asc')
            ->pluck('NAMAPROPINSI')
            ->map($formatSelect2Data)
            ->toArray();

        $pilihanPesertaKotaKabDetails = $pilihanPesertaKotaKabRawData->mapWithKeys(function ($item) use ($formatDetailValue) {
            $key = $item['NAMACLUB'] ? mb_strtoupper($item['NAMACLUB'], 'UTF-8') : mb_strtoupper($item['NAMAKOTA'], 'UTF-8');
            return [
                $key => [
                    'NAMACLUB' => $formatDetailValue($item['NAMACLUB']),
                    'JENIS' => $formatDetailValue($item['JENIS']),
                    'NAMAKOTA' => $formatDetailValue($item['NAMAKOTA']),
                    'NAMAPROPINSI' => $formatDetailValue($item['NAMAPROPINSI']),
                ]
            ];
        })->toArray();

        // NEW: Fetch existing MstPeserta data for display in the table
        $mstPesertaData = MstPeserta::where('email', $user->email)->get();

        return view('form_a1_kontingen', compact(
            'currentKompetisiSetting',
            'jenisKompetisiOptions',
            'jnsKompetisi',
            'namaClubsMstClub',
            'mstClubDetails',
            'namaClubsPilihanPeserta',
            'namaPropinsiPilihanPeserta',
            'pilihanPesertaKotaKabDetails',
            'appliedMode',
            'autoSelectedClubValue',
            'autoFillDetails',
            'userRoleString',
            'mstPesertaData'
        ));
    }

    public function addSpecialUser(Request $request)
    {
        // This method remains as is, managing temporary special access via the SpecialUser table.
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:special_users,email',
        ]);

        SpecialUser::create([
            'name' => $request->name,
            'email' => $request->email,
            'expired_at' => Carbon::now()->addDays(30),
        ]);

        return back()->with('success', 'Special user added successfully with a 30-day expiry.');
    }
}
