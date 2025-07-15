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
        $user = Auth::user(); // Get the currently authenticated user
        // if (!$user) {
        //     // Handle unauthenticated user - perhaps redirect to login
        //     return redirect()->route('login')->withErrors('Silakan masuk untuk melanjutkan.');
        // }

        $userEmail = $user->email; // Get the user's email

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

        // Fetch ALL MstPeserta records for the logged-in user to display in the table
        $mstPesertaList = MstPeserta::where('email', $userEmail)->get(); // Use get() for a collection

        // Auto-fill logic for the form:
        // If a user has any existing kontingen, pre-fill the form with the FIRST one found.
        // You might want to pick a specific one if a user can have many (e.g., based on a primary ID).
        $mstPesertaToAutofill = $mstPesertaList->first(); // Get the first record for autofill


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
            'mstPesertaList'
        ));
    }

    /**
     * Store a newly created item in storage.
     * This method is called when the 'Save Item' button is pressed and the form is submitted.
     */
    public function saveKontingen(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return back()->withErrors(['message' => 'User not authenticated.']);
        }

        $userEmail = $user->email; // Get the user's email

        // Log all incoming request data for debugging purposes
        Log::info('Incoming Request Data for saveKontingen:', $request->all());

        // Get jnsKompetisi from the hidden input field
        $jnsKompetisi = $request->input('jnsKompetisi');
        // Validate jnsKompetisi to ensure it's valid
        if (!in_array($jnsKompetisi, ['K', 'C', 'P'])) {
            return back()->withInput()->withErrors(['error' => 'Invalid jenis kompetisi provided.']);
        }

        // Define base validation rules
        $rules = [
            'nama_kontingen' => ['required', 'string', 'max:255'],
            'jnsKompetisi' => ['required', 'string', 'in:C,K,P'],
            'provinsi_input' => ['required', 'string', 'max:255'], // Renamed from provinsi_input
            'negara_input' => ['required', 'string', 'max:255'],   // Renamed from negara_input
            'contact_person_input' => ['nullable', 'string', 'max:255'], // Renamed from contact_person_input
            'telepon_input' => ['nullable', 'string', 'max:255'],     // Renamed from telepon_input
            'jumlah_official_input' => ['required', 'integer', 'min:1'], // Renamed from jumlah_official_input
        ];

        // Add conditional rules based on jnsKompetisi
        if ($jnsKompetisi === 'K' || $jnsKompetisi === 'C') {
            $rules['jenis_kota_kab'] = ['required', 'string', 'max:255'];
            $rules['nama_kota_kab'] = ['required', 'string', 'max:255'];
        }

        // Define custom validation messages
        $messages = [
            'nama_kontingen.required' => 'Kolom Nama Kontingen belum terisi',
            'provinsi_input.required' => 'Kolom Provinsi belum terisi',
            'negara_input.required' => 'Kolom Negara belum terisi',
            'jumlah_official_input.required' => 'Kolom Jumlah Official belum terisi',
            'jumlah_official_input.integer' => 'Kolom Jumlah Official harus angka',
            'jenis_kota_kab.required' => 'Kolom Jenis Kota/Kab belum terisi',
            'nama_kota_kab.required' => 'Kolom Nama Kota/Kab belum terisi',
            // Add other specific messages if needed
        ];

        // Perform validation
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            // Check for specific error to return custom message, otherwise return all
            if ($validator->errors()->has('nama_kontingen')) {
                return back()->withInput()->withErrors(['nama_kontingen_error' => $validator->errors()->first('nama_kontingen')]);
            }
            return back()->withInput()->withErrors($validator->errors());
        }

        // After successful validation, retrieve validated data
        $validatedData = $validator->validated();

        // Initialize NAMACLUB based on existing logic
        $namaClubToSave = $validatedData['nama_kontingen'];

        // --- CONCATENATION LOGIC HERE ---
        // Only if jnsKompetisi is "K", concatenate for NAMACLUB
        if ($jnsKompetisi === 'K') {
            $jenisKotaKab = $validatedData['jenis_kota_kab'];
            $namaKotaKab = $validatedData['nama_kota_kab'];
            // Concatenate and convert to uppercase, trim whitespace
            $namaClubToSave = strtoupper(trim($jenisKotaKab . ' ' . $namaKotaKab));
            Log::info('Concatenated NAMACLUB for jnsKompetisi K:', ['namaClubToSave' => $namaClubToSave]);
        }
        // --- END CONCATENATION LOGIC ---

        // Prepare data for saving
        $dataToSave = [
            // These map directly to your database column names (from MstPeserta $fillable)
            'NAMAPROPDOM'   => $request->input('provinsi_input'), // Use 'provinsi' as per HTML name
            'NAMANEGDOM'    => $request->input('negara_input'),   // Use 'negara' as per HTML name
            'CONTACTPERSON' => $request->input('contact_person_input'), // Use 'contact_person'
            'TELPON'        => $request->input('telepon_input'),     // Use 'telepon'
            'OFFICIAL'      => $request->input('jumlah_official_input'), // Use 'jumlah_official'
            'KETERANGAN'    => null, // Set to null or a relevant value if you have it
            'email'         => $user->email,
        ];

        // Conditional data based on jnsKompetisi
        if ($jnsKompetisi === 'K') {
            $dataToSave['ASAL'] = $request->input('nama_kontingen'); // For K, ASAL is nama_kontingen
            $dataToSave['NAMACLUB'] = $namaClubToSave; // For K, NAMACLUB is nama_kontingen
            $dataToSave['JENISDOM'] = $request->input('jenis_kota_kab');
            $dataToSave['NAMAKOTADOM'] = $request->input('nama_kota_kab');
        } elseif ($jnsKompetisi === 'C') {
            $dataToSave['ASAL'] = $request->input('nama_kota_kab'); // For C, ASAL is nama_kota_kab
            $dataToSave['NAMACLUB'] = $request->input('nama_kontingen'); // For C, NAMACLUB is nama_kontingen
            $dataToSave['JENISDOM'] = $request->input('jenis_kota_kab');
            $dataToSave['NAMAKOTADOM'] = $request->input('nama_kota_kab');
        } elseif ($jnsKompetisi === 'P') {
            $dataToSave['ASAL'] = $request->input('nama_kontingen'); // For P, ASAL is nama_kontingen
            $dataToSave['NAMACLUB'] = $request->input('nama_kontingen'); // For P, NAMACLUB is nama_kontingen
            // For 'P', 'jenis_kota_kab' and 'nama_kota_kab' are not required/submitted,
            // so set them to default like '-' or null, based on your database schema
            $dataToSave['JENISDOM'] = $request->input('jenis_kota_kab') ?: '-'; // Use '-' if empty
            $dataToSave['NAMAKOTADOM'] = $request->input('nama_kota_kab') ?: '-'; // Use '-' if empty
        }

        // Log the data being saved for debugging purposes (optional)
        Log::info('Attempting to save MstPeserta data:', $dataToSave);

        // Save to database
        try {
            // Define the attributes to find the record.
            // In this case, we want to find a record by the user's email.
            // If a user can have multiple MstPeserta entries, you'd add more unique criteria here,
            // e.g., ['email' => $userEmail, 'NAMACLUB' => $dataToSave['NAMACLUB']]
            $findAttributes = ['email' => $userEmail];

            // Use updateOrCreate to either update an existing record or create a new one.
            // The first array ($findAttributes) is for finding, the second ($dataToSave) is for updating/creating.
            MstPeserta::updateOrCreate($findAttributes, $dataToSave);

            Log::info('MstPeserta data saved/updated successfully.', ['user_email' => $userEmail]);

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
