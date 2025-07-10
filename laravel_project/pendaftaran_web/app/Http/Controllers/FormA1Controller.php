<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Kompetisi;
use App\Models\PilihanPesertaKotaKab;
use App\Models\MstClub;
use App\Models\SpecialUser;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
        $userClubNameFromUsersTable = null;
        $userEmail = null;
        $isUserAdminOperator = false;
        $isUserSpecial = false;

        $userRoleString = 'user'; // <--- NEW: Initialize a user role string
        $appliedMode = 2;

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
            $userClubNameFromUsersTable = $user->NAMACLUB;
            $userEmail = $user->email;

            $normalizedUserClubFromUsersTable = mb_strtoupper($userClubNameFromUsersTable, 'UTF-8');
            if (in_array($normalizedUserClubFromUsersTable, ['ADMIN', 'OPERATOR'])) {
                $isUserAdminOperator = true;
                $userRoleString = $normalizedUserClubFromUsersTable; // <--- NEW: Set role string
            }

            if (!$isUserAdminOperator && $userEmail) {
                $specialUser = SpecialUser::where('email', $userEmail)
                    ->where('expired_at', '>', Carbon::now())
                    ->first();
                if ($specialUser) {
                    $isUserSpecial = true;
                    $userRoleString = 'special'; // <--- NEW: Set role string
                }
            }
        }

        // Determine the applied mode
        if ($isUserAdminOperator || $isUserSpecial) {
            $appliedMode = 1; // Mode 1: Enabled (Admin, Operator, or Special User)
            $autoSelectedClubValue = null;
            $autoFillDetails = null;
        } else {
            $appliedMode = 2; // Mode 2: Disabled (Regular User)
            // Logic for auto-selection and auto-fill for Mode 2
            if ($user && $userClubNameFromUsersTable) {
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
                $autoSelectedClubValue = null;
                $autoFillDetails = null;
            }
        }

        // --- Fetch all options for Select2 ---
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
            'userRoleString' // <--- NEW: Pass userRoleString to the view
        ));
    }

    public function addSpecialUser(Request $request)
    {
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
