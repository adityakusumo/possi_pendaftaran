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

    // NEW: Method to handle saving kontingen data
    public function saveKontingen(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return back()->withErrors(['message' => 'User not authenticated.']);
        }

        $jnsKompetisi = $request->input('jnsKompetisi'); // Get jnsKompetisi from hidden input

        // Custom validation rules based on jnsKompetisi
        $rules = [
            'nama_kontingen' => ['required', 'string', 'max:255'],
            'provinsi' => ['required', 'string', 'max:255'],
            'negara' => ['required', 'string', 'max:255'],
            'jumlah_official' => ['required', 'integer', 'min:0'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'telepon' => ['nullable', 'string', 'max:255'],
            // 'jumlah_pelatih' is not in MstPeserta, so it's not validated for saving here.
            // If it's a display field only, it doesn't need to be sent.
        ];

        if ($jnsKompetisi === 'K' || $jnsKompetisi === 'C') {
            $rules['jenis_kota_kab'] = ['required', 'string', 'max:255'];
            $rules['nama_kota_kab'] = ['required', 'string', 'max:255'];
        } else if ($jnsKompetisi === 'P') {
            // For 'P', jenis_kota_kab and nama_kota_kab can be ignored/null
            // The request might still send them, but they won't be required.
        }

        // Validate the request
        $validator = Validator::make($request->all(), $rules, [
            'nama_kontingen.required' => 'Kolom Nama Kontingen belum terisi',
            'provinsi.required' => 'Kolom Provinsi belum terisi',
            'negara.required' => 'Kolom Negara belum terisi',
            'jumlah_official.required' => 'Kolom Jumlah Official belum terisi',
            'jumlah_official.integer' => 'Kolom Jumlah Official harus angka',
            'jenis_kota_kab.required' => 'Kolom Jenis Kota/Kab belum terisi',
            'nama_kota_kab.required' => 'Kolom Nama Kota/Kab belum terisi',
        ]);

        if ($validator->fails()) {
            // Use the specific message if 'nama_kontingen' is missing, otherwise return all errors
            if ($validator->errors()->has('nama_kontingen')) {
                return back()->withInput()->withErrors(['nama_kontingen_error' => 'Kolom Nama Kontingen belum terisi']);
            }
            return back()->withInput()->withErrors($validator->errors());
        }

        // Prepare data for MstPeserta based on jnsKompetisi
        $dataToSave = [
            'NAMAPROPDOM' => $request->input('provinsi'),
            'NAMANEGDOM' => $request->input('negara'),
            'CONTACTPERSON' => $request->input('contact_person'),
            'TELPON' => $request->input('telepon'),
            'OFFICIAL' => $request->input('jumlah_official'),
            'KETERANGAN' => null, // Always null as per your spec
            'email' => $user->email,
        ];

        if ($jnsKompetisi === 'K') {
            $dataToSave['ASAL'] = $request->input('nama_kontingen');
            $dataToSave['NAMACLUB'] = $request->input('nama_kontingen');
            $dataToSave['JENISDOM'] = $request->input('jenis_kota_kab');
            $dataToSave['NAMAKOTADOM'] = $request->input('nama_kota_kab');
        } elseif ($jnsKompetisi === 'C') {
            $dataToSave['ASAL'] = $request->input('nama_kota_kab'); // 'nama kota/kab' for ASAL
            $dataToSave['NAMACLUB'] = $request->input('nama_kontingen');
            $dataToSave['JENISDOM'] = $request->input('jenis_kota_kab');
            $dataToSave['NAMAKOTADOM'] = $request->input('nama_kota_kab');
        } elseif ($jnsKompetisi === 'P') {
            $dataToSave['ASAL'] = $request->input('nama_kontingen');
            $dataToSave['NAMACLUB'] = $request->input('nama_kontingen');
            $dataToSave['JENISDOM'] = '-'; // Specific value for 'P'
            $dataToSave['NAMAKOTADOM'] = '-'; // Specific value for 'P'
        }

        try {
            MstPeserta::create($dataToSave);
            return redirect()->route('form_a1.kontingen')->with('success', 'Data kontingen berhasil disimpan!');
        } catch (\Exception $e) {
            Log::error('Error saving MstPeserta data: ' . $e->getMessage(), ['user_email' => $user->email, 'data' => $dataToSave]);
            return back()->withInput()->withErrors(['save_error' => 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.']);
        }
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
