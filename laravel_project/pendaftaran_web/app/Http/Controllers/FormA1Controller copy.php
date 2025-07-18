<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // For debugging
use Illuminate\Support\Facades\Validator; // For custom validation
use Illuminate\Support\Facades\DB;
use App\Models\Kompetisi;
use App\Models\PilihanPesertaKotaKab;
use App\Models\MstClub;
use App\Models\SpecialUser; // Keep this for SpecialUser table check
use App\Models\MstPeserta;
use App\Models\MstKU;
use App\Models\NIAS;
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

        // --- Data for Select2 and other dropdowns (usually fetched regardless of mode) ---
        $formatSelect2Data = function ($name) {
            if ($name === null) return null;
            return ['id' => mb_strtoupper($name, 'UTF-8'), 'text' => mb_strtoupper($name, 'UTF-8')];
        };
        $formatDetailValue = function ($value) {
            return ($value !== null) ? mb_strtoupper($value, 'UTF-8') : null;
        };

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


        // --- Determine appliedMode (based on user roles and SpecialUser table) ---
        $userRoleString = 'user'; // Default role string
        $appliedMode = 2; // Default to disabled mode for regular users

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

        if ($isUserAdminViaSpatie || $isUserOperatorViaSpatie || $isUserSpecialViaTable) {
            $appliedMode = 1; // Admin, Operator, or active Special User
            if ($isUserAdminViaSpatie) $userRoleString = 'admin';
            elseif ($isUserOperatorViaSpatie) $userRoleString = 'operator';
            else $userRoleString = 'special';
        } else {
            $appliedMode = 2; // Regular user (no admin, operator, or active special status)
            $userRoleString = 'user';
        }


        // --- Fetch MstPeserta data for table display and auto-fill ---
        $mstPesertaList = MstPeserta::where('email', $userEmail)->get(); // For table display
        $mstPesertaToAutofill = $mstPesertaList->first(); // The specific record to use for form auto-fill


        // --- Initialize auto-fill details (these will be passed to the view) ---
        $autoSelectedClubValue = null; // Value for nama_kontingen select
        $autoFillDetails = [
            'JENIS'         => '',
            'NAMAKOTA'      => '',
            'NAMAPROP'      => '',
            'NAMAPROPINSI'  => '',
            'NAMANEGARA'    => '',
            'CONTACTPERSON' => '',
            'TELPON'        => '',
            'OFFICIAL'      => 1, // Default for number input often starts at 1
            'NAMACLUB'      => '', // Used for the concatenated name in Mode 2
            'ASAL'          => ''  // Original name for ASAL column
        ];

        // --- Populate autoFillDetails from MstPeserta (PRIORITY 1: Existing User Data) ---
        if ($mstPesertaToAutofill) {
            // These fields directly come from MstPeserta
            $autoFillDetails['CONTACTPERSON'] = $formatDetailValue($mstPesertaToAutofill->CONTACTPERSON);
            $autoFillDetails['TELPON']        = $formatDetailValue($mstPesertaToAutofill->TELPON);
            $autoFillDetails['OFFICIAL']      = $mstPesertaToAutofill->OFFICIAL !== null ? $mstPesertaToAutofill->OFFICIAL : 1; // Keep as number
            $autoFillDetails['NAMAPROP']      = $formatDetailValue($mstPesertaToAutofill->NAMAPROPDOM);
            $autoFillDetails['NAMAPROPINSI']  = $formatDetailValue($mstPesertaToAutofill->NAMAPROPDOM); // For compatibility
            $autoFillDetails['NAMANEGARA']    = $formatDetailValue($mstPesertaToAutofill->NAMANEGDOM);
            $autoFillDetails['JENIS']         = $formatDetailValue($mstPesertaToAutofill->JENISDOM);
            $autoFillDetails['NAMAKOTA']      = $formatDetailValue($mstPesertaToAutofill->NAMAKOTADOM);
            $autoFillDetails['NAMACLUB']      = $formatDetailValue($mstPesertaToAutofill->NAMACLUB); // The (potentially concatenated) kontingen name
            $autoFillDetails['ASAL']          = $formatDetailValue($mstPesertaToAutofill->ASAL);   // The original kontingen name
        }


        // --- Further populate autoFillDetails based on Kompetisi Type and MstClub/PilihanPeserta (PRIORITY 2: If no MstPeserta data, or for other auto-selection) ---
        // This logic fills in if no MstPeserta data exists OR if MstPeserta doesn't have a specific field.
        // If $mstPesertaToAutofill is NOT present, then the original logic of deriving from user's club name applies.
        // Otherwise, if $mstPesertaToAutofill IS present, we just use its values.

        // This section is mainly for determining `autoSelectedClubValue` for the `nama_kontingen` dropdown
        // and potentially setting default `JENIS`, `NAMAKOTA`, `NAMAPROP` if MstPeserta didn't have them.
        // However, if MstPeserta is the source of truth, this complex logic for autoFillDetails might be redundant
        // if `autoFillDetails` is already fully populated by MstPeserta.
        // Let's simplify this. If `mstPesertaToAutofill` exists, its data overrides others.

        // Determine `autoSelectedClubValue` (for the main 'Nama Kontingen' field in Mode 2)
        // If MstPeserta exists, use its NAMACLUB or ASAL. Otherwise, try to derive from user's club.
        if ($mstPesertaToAutofill) {
            // For autoSelectedClubValue, prioritize the actual NAMACLUB from MstPeserta
            $autoSelectedClubValue = $autoFillDetails['NAMACLUB'] ?: $autoFillDetails['ASAL'];
        } elseif ($user && $user->NAMACLUB) {
            // Fallback: If no MstPeserta for user, try to derive from User->NAMACLUB
            $normalizedUserClubFromUsersTable = mb_strtoupper($user->NAMACLUB, 'UTF-8');
            $userMstClubDetails = MstClub::whereRaw('UPPER(NAMACLUB) = ?', [$normalizedUserClubFromUsersTable])->first();

            if ($jnsKompetisi === 'C') {
                $autoSelectedClubValue = $formatDetailValue($user->NAMACLUB);
                if ($userMstClubDetails) {
                    $autoFillDetails['JENIS'] = $formatDetailValue($userMstClubDetails->JENIS);
                    $autoFillDetails['NAMAKOTA'] = $formatDetailValue($userMstClubDetails->NAMAKOTA);
                    $autoFillDetails['NAMAPROP'] = $formatDetailValue($userMstClubDetails->NAMAPROP);
                }
            } elseif ($jnsKompetisi === 'K') {
                if ($userMstClubDetails && $userMstClubDetails->NAMAKOTA) {
                    $autoSelectedClubValue = $formatDetailValue($userMstClubDetails->NAMAKOTA);
                    $relatedPilihanPeserta = PilihanPesertaKotaKab::whereRaw('UPPER(NAMAKOTA) = ?', [$autoSelectedClubValue])->first();
                    if ($relatedPilihanPeserta) {
                        $autoFillDetails['JENIS'] = $formatDetailValue($relatedPilihanPeserta->JENIS);
                        $autoFillDetails['NAMAKOTA'] = $formatDetailValue($relatedPilihanPeserta->NAMAKOTA);
                        $autoFillDetails['NAMAPROP'] = $formatDetailValue($relatedPilihanPeserta->NAMAPROPINSI);
                    }
                }
            } elseif ($jnsKompetisi === 'P') {
                if ($userMstClubDetails && $userMstClubDetails->NAMAPROP) {
                    $autoSelectedClubValue = $formatDetailValue($userMstClubDetails->NAMAPROP);
                    // For P, no additional autoFillDetails from here, as NAMAPROP is the primary.
                }
            }
        }

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
            'nama_kontingen' => ['required', 'string', 'max:30'],
            'jnsKompetisi' => ['required', 'string', 'in:C,K,P'],
            'provinsi_input' => ['required', 'string', 'max:30'], // Renamed from provinsi_input
            'negara_input' => ['required', 'string', 'max:30'],   // Renamed from negara_input
            'telepon_input' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-() ]+$/'], // Updated regex
            'contact_person_input' => ['nullable', 'string', 'max:50', 'regex:/^[^0-9]*$/'],
            'jumlah_official_input' => ['required', 'integer', 'min:1', 'max:50'], // Renamed from jumlah_official_input
        ];

        // Add conditional rules based on jnsKompetisi
        if ($jnsKompetisi === 'K' || $jnsKompetisi === 'C') {
            $rules['jenis_kota_kab'] = ['required', 'string', 'max:4'];
            $rules['nama_kota_kab'] = ['required', 'string', 'max:50'];
        }

        // Define custom validation messages
        $messages = [
            'nama_kontingen.required' => 'Kolom Nama Kontingen belum terisi',
            'provinsi_input.required' => 'Kolom Provinsi belum terisi',
            'negara_input.required' => 'Kolom Negara belum terisi',
            'jumlah_official_input.required' => 'Kolom Jumlah Official belum terisi',
            'jumlah_official_input.integer' => 'Kolom Jumlah Official harus angka',
            'jumlah_official_input.min' => 'Kolom Jumlah Official minimal terisi 1',
            'jenis_kota_kab.required' => 'Kolom Jenis Kota/Kab belum terisi',
            'nama_kota_kab.required' => 'Kolom Nama Kota/Kab belum terisi',
            'telepon_input.regex' => 'Kolom Telepon hanya boleh mengandung angka, tanda tambah (+), strip (-), dan kurung buka/tutup (()).',
            'contact_person_input.regex' => 'Kolom Contact Person tidak boleh mengandung angka.',
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
            $dataToSave['ASAL'] = $request->input('nama_kota_kab'); // For K, ASAL is nama_kontingen
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

    public function destroyKontingen(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return back()->withErrors(['message' => 'User not authenticated.']);
        }
        $userEmail = $user->email;
        $mstPeserta = MstPeserta::where('email', $userEmail)->first();

        if ($mstPeserta) {
            try {
                $mstPeserta->delete();
                return redirect()->route('form_a1.kontingen')->with('success', 'Data kontingen berhasil dihapus!');
            } catch (\Exception $e) {
                return back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus data. Silakan coba lagi.']);
            }
        } else {
            return back()->withErrors(['error' => 'Data masih kosong!']); // Corrected to use 'error' key
        }
    }

    public function daftarAtlet(): View
    {
        // You would fetch data here if needed, e.g.,
        // $existingParticipants = YourModel::all();
        // return view('form_a1_namaatlet', compact('existingParticipants'));
        $user = Auth::user();

        // If user is not authenticated, redirect to login
        if (!$user) {
            return redirect()->route('login')->withErrors('Silakan masuk untuk melanjutkan.');
        }

        // --- NEW: Fetch JNSKOMPETISI from the Kompetisi table ---
        $kompetisi = Kompetisi::first(); // !!! IMPORTANT: Adjust this query !!!
        // This line currently fetches the first competition record.
        // You might need:
        // - Kompetisi::where('status', 'active')->first();
        // - Kompetisi::find($user->kompetisi_id); // If user is linked to a competition
        // - Or any other logic to determine the current competition

        $userJnsKompetisi = null;
        if ($kompetisi && $kompetisi->JNSKOMPETISI) {
            $userJnsKompetisi = mb_strtoupper($kompetisi->JNSKOMPETISI, 'UTF-8');
        } else {
            // Handle case where no competition or JNSKOMPETISI is found
            // For safety, we can default to a state that shows no data,
            // or redirect, or provide a message.
            return redirect()->back()->withErrors('Tidak dapat menentukan jenis kompetisi saat ini.');
        }
        // --- END NEW ---

        // dd($userJnsKompetisi);

        $query = NIAS::query(); // Start building the query for the NIAS table

        switch ($userJnsKompetisi) {
            case 'K': // JNS & NAMAKOTA in users table is equal to JENIS & NAMAKOTA in NIAS table
                // Assuming user->jenis maps to NIAS.KDJENIS
                // Assuming user->namakota maps to NIAS.NAMAKOTA
                if ($user->JENIS) {
                    $query->whereRaw('UPPER(JENIS) = ?', [mb_strtoupper($user->JENIS, 'UTF-8')]);
                } else {
                    // If NAMACLUB is not set for user in 'C' case, ensure no results
                    $query->whereRaw('1 = 0');
                }
                if ($user->NAMAKOTA) {
                    $query->whereRaw('UPPER(NAMAKOTA) = ?', [mb_strtoupper($user->NAMAKOTA, 'UTF-8')]);
                } else {
                    // If NAMACLUB is not set for user in 'C' case, ensure no results
                    $query->whereRaw('1 = 0');
                }
                break;

            case 'C': // NAMACLUB in users table is equal to NAMACLUB in NIAS table
                // Assuming user->namaclub maps to NIAS.NAMACLUB
                if ($user->NAMACLUB) {
                    $userClubName = mb_strtoupper($user->NAMACLUB, 'UTF-8');
                    // $userClubName = "AMARTA";

                    // --- Direct SQL Execution for Debugging ---
                    // $rawSqlResult = DB::select("SELECT * FROM NIAS WHERE UPPER(NAMACLUB) = ?", [$userClubName]);
                    // $rawSqlResult = DB::select("SELECT * FROM NIAS WHERE NAMACLUB = 'JOYOBOYO'");
                    // dd($rawSqlResult); // This will dump the result of the raw SQL query
                    // --- End Direct SQL Debugging ---

                    $query->whereRaw('UPPER(NAMACLUB) = ?', [$userClubName]);
                } else {
                    // If NAMACLUB is not set for user in 'C' case, ensure no results
                    $query->whereRaw('1 = 0');
                }

                break;

            case 'P': // NAMAPROP in users table is equal to NAMAPROP in NIAS table
                // Assuming user->namaprop maps to NIAS.NAMAPROP
                if ($user->NAMAPROP) {
                    $query->whereRaw('UPPER(NAMAPROP) = ?', [mb_strtoupper($user->NAMAPROP, 'UTF-8')]);
                } else {
                    // If NAMACLUB is not set for user in 'C' case, ensure no results
                    $query->whereRaw('1 = 0');
                }
                break;

            default:
                // If jnskompetisi doesn't match any specific case,
                // Ensures no records are returned
                $query->whereRaw('1 = 0');
                break;
        }

        // --- DEBUGGING (Optional) ---
        // You can uncomment these lines to see the generated SQL query and its bindings
        // dd($query->toSql(), $query->getBindings());
        // --- END DEBUGGING ---

        // --- Sort data by NAMA ATLET (assuming column is 'NAMA') in ascending order ---
        $query->orderBy('NAMA', 'asc');

        // --- Pagination Implementation ---
        $perPage = 20; // Define how many items you want per page
        $niasList = $query->paginate($perPage); // Execute the query and paginate the results

        // // --- Fetch KU options from MstKU table ---
        // $mstKuOptions = MstKU::pluck('KU', 'KU')->toArray(); // Fetches 'KU' column as both key and value
        // // If you need to sort them:
        // $mstKuOptions = MstKU::orderBy('KU', 'asc')->pluck('KU', 'KU')->toArray();

        // --- UPDATED: Fetch all relevant MstKU data ---
        // We get all columns and order by KU for consistent display/looping
        $mstKuData = MstKU::orderBy('KU', 'asc')->get()->toArray();
        // For the dropdown options, we still just need the KU values
        $mstKuOptions = array_column($mstKuData, 'KU', 'KU');

        // --- Fetch NIAS data for table display and auto-fill ---
        $niasToAutofill = $niasList->first();
        $formatDetailValue = function ($value) {
            return ($value !== null) ? mb_strtoupper($value, 'UTF-8') : null;
        };

        // --- Initialize auto-fill details (these will be passed to the view) ---
        $autoNamaKontingenValue = null; // Value for nama_kontingen select
        $autoFillDetails = [
            'NAMACLUB' => $user->NAMACLUB ?? '', // User's club name
            'JENIS' => $user->JENIS ?? '',       // User's JENIS (e.g., KAB/KOTA code)
            'NAMAKOTA' => $user->NAMAKOTA ?? '', // User's city/kabupaten name
            'NAMAPROP' => $user->NAMAPROP ?? '', // User's province name
            'NAMA' => '',                        // Nama Atlet should be empty initially
        ];

        // --- Populate autoFillDetails from MstPeserta (PRIORITY 1: Existing User Data) ---
        if ($niasToAutofill) {
            // These fields directly come from MstPeserta
            $autoFillDetails['NAMACLUB'] = $formatDetailValue($niasToAutofill->NAMACLUB);
            $autoFillDetails['JENIS']    = $formatDetailValue($niasToAutofill->JENIS);
            $autoFillDetails['NAMAKOTA'] = $formatDetailValue($niasToAutofill->NAMAKOTA);
            $autoFillDetails['NAMAPROP'] = $formatDetailValue($niasToAutofill->NAMAPROP);
            $autoFillDetails['NAMA']    = $formatDetailValue($niasToAutofill->NAMA);
        }

        return view('form_a1_namaatlet', compact('niasList', 'autoFillDetails', 'mstKuOptions', 'mstKuData'));
    }
}
