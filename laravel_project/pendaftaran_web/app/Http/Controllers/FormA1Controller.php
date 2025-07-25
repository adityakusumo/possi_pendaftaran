<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // For debugging
use Illuminate\Support\Facades\Validator; // For custom validation
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\Kompetisi;
use App\Models\PilihanPesertaKotaKab;
use App\Models\MstClub;
use App\Models\SpecialUser; // Keep this for SpecialUser table check
use App\Models\MstPeserta;
use App\Models\MstKU;
use App\Models\NIAS;
use App\Models\Atlet;
use Carbon\Carbon; // Keep this for expiry date check

class FormA1Controller extends Controller
{
    public function kontingen(): View
    {
        $user = Auth::user();
        $userEmail = $user->email;

        $currentKompetisiSetting = Kompetisi::find(1);
        $jnsKompetisi = $currentKompetisiSetting ? mb_strtoupper(trim($currentKompetisiSetting->JNSKOMPETISI), 'UTF-8') : null;

        $jenisKompetisiOptions = [
            'K' => 'Antar Kota/Kab',
            'C' => 'Antar Club',
            'P' => 'Antar Provinsi',
        ];

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

        $namaKotaKabPilihanPeserta = PilihanPesertaKotaKab::select(DB::raw("CONCAT(JENIS, ' ', NAMAKOTA) AS full_name"))
            ->distinct()
            ->whereNotNull('JENIS')
            ->whereNotNull('NAMAKOTA')
            ->orderBy('full_name', 'asc')
            ->pluck('full_name')
            ->map($formatSelect2Data)
            ->toArray();

        $namaPropinsiPilihanPeserta = PilihanPesertaKotaKab::select('NAMAPROPINSI')
            ->distinct()
            ->whereNotNull('NAMAPROPINSI')
            ->orderBy('NAMAPROPINSI', 'asc')
            ->pluck('NAMAPROPINSI')
            ->map($formatSelect2Data)
            ->toArray();

        // Re-key pilihanPesertaKotaKabDetails to allow lookup by JENIS NAMAKOTA and NAMAPROPINSI
        $pilihanPesertaKotaKabDetails = [];
        foreach ($pilihanPesertaKotaKabRawData as $item) {
            $jenisKotaKey = null;
            $propinsiKey = null;

            if ($item['JENIS'] && $item['NAMAKOTA']) {
                $jenisKotaKey = mb_strtoupper($item['JENIS'] . ' ' . $item['NAMAKOTA'], 'UTF-8');
            }
            if ($item['NAMAPROPINSI']) {
                $propinsiKey = mb_strtoupper($item['NAMAPROPINSI'], 'UTF-8');
            }

            $detail = [
                'NAMACLUB' => $formatDetailValue($item['NAMACLUB']),
                'JENIS' => $formatDetailValue($item['JENIS']),
                'NAMAKOTA' => $formatDetailValue($item['NAMAKOTA']),
                'NAMAPROPINSI' => $formatDetailValue($item['NAMAPROPINSI']),
            ];

            if ($jenisKotaKey) {
                $pilihanPesertaKotaKabDetails[$jenisKotaKey] = $detail;
            }
            if ($propinsiKey) {
                // This key is less specific and might overwrite if multiple cities/clubs share a province name.
                // For 'P' type, this is the primary lookup for details if they exist beyond just the name.
                $pilihanPesertaKotaKabDetails[$propinsiKey] = $detail;
            }
        }


        // --- Determine appliedMode (based on user roles and SpecialUser table) ---
        $userRoleString = 'user'; // Default role string
        $appliedMode = 2; // Default to disabled mode for regular users

        $isUserAdminViaSpatie = $user->hasRole('admin');
        $isUserOperatorViaSpatie = $user->hasRole('operator');
        $isUserSpecialViaTable = false;

        if ($userEmail) {
            $specialUser = SpecialUser::where('email', $userEmail)
                ->where('expired_at', '>', Carbon::now())
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


        // --- Initialize auto-fill details (these will be passed to the view) ---
        $autoSelectedClubValue = null;
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
            'ASAL'          => ''  // Original name for ASAL column (will be derived)
        ];

        // --- Logic for autoSelectedClubValue and autoFillDetails in Mode 2 (ONLY from users table) ---
        if ($appliedMode === 2) {
            // Populate contact person, phone, and nation directly from Auth::user()
            $autoFillDetails['CONTACTPERSON'] = $formatDetailValue($user->name); // Assuming 'name' is contact person
            $autoFillDetails['TELPON']        = $formatDetailValue($user->phone); // Assuming 'phone' exists
            $autoFillDetails['OFFICIAL']      = 1; // Default
            $autoFillDetails['NAMANEGARA']    = $formatDetailValue($user->NAMANEGDOM); // Assuming user has NAMANEGDOM

            if ($jnsKompetisi === 'C') {
                if ($user->NAMACLUB) {
                    $normalizedUserClub = $formatDetailValue($user->NAMACLUB);
                    $autoSelectedClubValue = $normalizedUserClub;

                    // Try to find full details from MstClub based on user's NAMACLUB
                    $clubDetails = MstClub::whereRaw('UPPER(NAMACLUB) = ?', [$normalizedUserClub])->first();
                    if ($clubDetails) {
                        $autoFillDetails['JENIS']    = $formatDetailValue($clubDetails->JENIS);
                        $autoFillDetails['NAMAKOTA'] = $formatDetailValue($clubDetails->NAMAKOTA);
                        $autoFillDetails['NAMAPROP'] = $formatDetailValue($clubDetails->NAMAPROP);
                        $autoFillDetails['NAMACLUB'] = $formatDetailValue($clubDetails->NAMACLUB);
                        $autoFillDetails['ASAL']     = $formatDetailValue($clubDetails->NAMACLUB); // ASAL defaults to NAMACLUB
                    } else {
                        // If no MstClub entry, default to user's raw NAMACLUB for other details
                        $autoFillDetails['NAMACLUB'] = $normalizedUserClub;
                        $autoFillDetails['ASAL'] = $normalizedUserClub;
                        // JENIS, NAMAKOTA, NAMAPROP might remain empty if not found from MstClub
                    }
                }
            } elseif ($jnsKompetisi === 'K') {
                if ($user->NAMAKOTADOM) {
                    $normalizedUserKota = $formatDetailValue($user->NAMAKOTADOM);
                    // Find JENIS from PilihanPesertaKotaKab for concatenation
                    $pkkEntry = PilihanPesertaKotaKab::whereRaw('UPPER(NAMAKOTA) = ?', [$normalizedUserKota])->first();
                    if ($pkkEntry) {
                        $autoSelectedClubValue = $formatDetailValue($pkkEntry->JENIS . ' ' . $pkkEntry->NAMAKOTA);
                        $autoFillDetails['JENIS']        = $formatDetailValue($pkkEntry->JENIS);
                        $autoFillDetails['NAMAKOTA']     = $formatDetailValue($pkkEntry->NAMAKOTA);
                        $autoFillDetails['NAMAPROPINSI'] = $formatDetailValue($pkkEntry->NAMAPROPINSI);
                        $autoFillDetails['NAMAPROP']     = $formatDetailValue($pkkEntry->NAMAPROPINSI); // For compatibility
                        $autoFillDetails['ASAL']         = $autoSelectedClubValue; // ASAL for 'K' becomes the concatenated name
                    } else {
                        // If no matching JENIS is found, just use the city name for autoSelectedClubValue
                        $autoSelectedClubValue = $normalizedUserKota;
                        $autoFillDetails['NAMAKOTA']     = $normalizedUserKota;
                        $autoFillDetails['NAMAPROPINSI'] = $formatDetailValue($user->NAMAPROPDOM);
                        $autoFillDetails['NAMAPROP']     = $formatDetailValue($user->NAMAPROPDOM);
                        $autoFillDetails['ASAL']         = $normalizedUserKota;
                    }
                }
            } elseif ($jnsKompetisi === 'P') {
                if ($user->NAMAPROPDOM) {
                    $normalizedUserProp = $formatDetailValue($user->NAMAPROPDOM);
                    $autoSelectedClubValue = $normalizedUserProp;
                    $autoFillDetails['NAMAPROP']     = $normalizedUserProp;
                    $autoFillDetails['NAMAPROPINSI'] = $normalizedUserProp;
                    $autoFillDetails['ASAL']         = $normalizedUserProp; // ASAL for 'P' becomes the province name

                    // If you need more details for a province (e.g., from PilihanPesertaKotaKab), you'd fetch them here
                    $pkkEntry = PilihanPesertaKotaKab::whereRaw('UPPER(NAMAPROPINSI) = ?', [$normalizedUserProp])->first();
                    if ($pkkEntry) {
                        $autoFillDetails['NAMACLUB'] = $formatDetailValue($pkkEntry->NAMACLUB); // Example if relevant
                        $autoFillDetails['JENIS'] = $formatDetailValue($pkkEntry->JENIS);
                        $autoFillDetails['NAMAKOTA'] = $formatDetailValue($pkkEntry->NAMAKOTA);
                    }
                }
            }
        }
        // $mstPesertaList remains only for table display, not for autofill logic here.
        $mstPesertaList = \App\Models\MstPeserta::where('email', $userEmail)->get();


        return view('form_a1_kontingen', compact(
            'currentKompetisiSetting',
            'jenisKompetisiOptions',
            'jnsKompetisi',
            'namaClubsMstClub',
            'mstClubDetails',
            'namaKotaKabPilihanPeserta',
            'namaPropinsiPilihanPeserta',
            'pilihanPesertaKotaKabDetails',
            'appliedMode',
            'autoSelectedClubValue', // This is what Mode 2 will use for the dropdown
            'autoFillDetails', // This populates the other detail fields
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

        $kompetisi = Kompetisi::first(); // Fetch the relevant competition record
        $userJnsKompetisi = null;
        $wajibNiasStatusText = ''; // Initialize the variable to hold the status text

        if ($kompetisi) {
            if ($kompetisi->JNSKOMPETISI) {
                $userJnsKompetisi = mb_strtoupper($kompetisi->JNSKOMPETISI, 'UTF-8');
            }

            // Determine the text based on the WAJIBNIAS column
            // Assuming WAJIBNIAS is an integer column (0 or 1)
            if (isset($kompetisi->WAJIBNIAS)) {
                if ($kompetisi->WAJIBNIAS == 0) {
                    $wajibNiasStatusText = 'Bebas';
                } elseif ($kompetisi->WAJIBNIAS == 1) {
                    $wajibNiasStatusText = 'SP Jika tanpa NIAS';
                }
            } else {
                $wajibNiasStatusText = 'Status tidak tersedia'; // Fallback if WAJIBNIAS is null/not set
            }
        } else {
            return redirect()->back()->withErrors('Tidak dapat menentukan jenis kompetisi saat ini.');
        }
        // --- END NEW ---

        // dd($userJnsKompetisi);

        $query = NIAS::query(); // Start building the query for the NIAS table

        switch ($userJnsKompetisi) {
            case 'K': // JNS & NAMAKOTA in users table is equal to JENIS & NAMAKOTA in NIAS table
                // Assuming user->jenis maps to NIAS.KDJENIS
                // Assuming user->namakota maps to NIAS.NAMAKOTA
                if ($user->JENISDOM) {
                    $query->whereRaw('UPPER(JENISDOM) = ?', [mb_strtoupper($user->JENISDOM, 'UTF-8')]);
                } else {
                    // If NAMACLUB is not set for user in 'C' case, ensure no results
                    $query->whereRaw('1 = 0');
                }
                if ($user->NAMAKOTADOM) {
                    $query->whereRaw('UPPER(NAMAKOTADOM) = ?', [mb_strtoupper($user->NAMAKOTADOM, 'UTF-8')]);
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
        // dd($query->toSql(), $query->getBindings());
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

        $userID = $user->id;
        // $atletList = \App\Models\Atlet::where('updated_by', $userID)->get();
        $atletList = \App\Models\Atlet::where('updated_by', $userID)
            ->orderBy('GENDER', 'asc')    // Sort by GENDER first
            ->orderBy('NAMAATLET', 'asc') // Then by NAMAATLET
            ->get();

        return view('form_a1_namaatlet', compact('niasList', 'autoFillDetails', 'mstKuOptions', 'mstKuData', 'wajibNiasStatusText', 'atletList'));
    }

    public function saveAtlet(Request $request)
    {
        try {
            // 1. Validation
            $validatedData = $request->validate([
                // Hidden inputs from selected NIAS row
                'selected_nias_nonias' => 'required|string|max:50', // This is for NONIAS
                // 'selected_nias_exp1009' => 'nullable|string|max:50', // This is for EXP1009
                'selected_nias_expired' => 'nullable|string|max:50',

                // Form fields from "DATA ATLET" section (mapped to original HTML names)
                'nama_club' => 'required|string|max:255', // Maps to NAMACLUB
                'jenis_kota_kab' => 'nullable|string|max:4', // Will map to JENISI
                'nama_kota_kab' => 'nullable|string|max:50', // Will map to ASAL and NAMAKOTADOM
                'propinsi' => 'nullable|string|max:30', // Will map to NAMAPROPDOM
                'negara' => 'nullable|string|max:30', // Not explicitly requested for Atlet table, but can be saved
                'nama_atlet' => 'required|string|max:255', // Maps to NAMAATLET
                'birth_day' => 'required|numeric|between:1,31',
                'birth_month' => 'required|numeric|between:1,12',
                'birth_year' => 'required|numeric|digits:4',
                'ku' => 'nullable|string|max:10', // Maps to KU
                'gender' => 'required|in:PA,PI', // Maps to GENDER
                'sparing_partner' => 'required|in:0,1', // Will be hardcoded to '0'

            ]);

            //// Combine date components into a single TGLLAHIR field
            $tglLahir = null;
            if ($validatedData['birth_year'] && $validatedData['birth_month'] && $validatedData['birth_day']) {
                try {
                    $tglLahir = Carbon::create(
                        $validatedData['birth_year'],
                        $validatedData['birth_month'],
                        $validatedData['birth_day']
                    )->format('Y-m-d');
                } catch (\Exception $e) {
                    throw ValidationException::withMessages(['tgl_lahir' => 'Tanggal lahir tidak valid.']);
                }
            }

            // NEW: Convert EXPIRED string to a date format
            $expiredDate = null;
            if (!empty($validatedData['selected_nias_expired'])) {
                try {
                    // Assuming the format from NIAS is 'YYYY-MM-DDTHH:MM:SS.000000Z' (ISO 8601)
                    $expiredDate = Carbon::parse($validatedData['selected_nias_expired'])->format('Y-m-d');
                } catch (\Exception $e) {
                    Log::warning('Could not parse EXPIRED date: ' . $validatedData['selected_nias_expired'], ['error' => $e->getMessage()]);
                    // If parsing fails, you might want to throw a validation exception or just leave it null
                    // throw ValidationException::withMessages(['expired' => 'Format tanggal expired tidak valid.']);
                }
            }

            // Prepare data for saving/updating in Atlet table
            // IMPORTANT: Map your form input names to your Atlet table column names
            $atletData = [
                'NAMACLUB' => $validatedData['nama_club'],               // From form: nama_club
                'JENISDOM' => $validatedData['jenis_kota_kab'],           // From form: jenis_kota_kab
                'NAMAKOTADOM' => $validatedData['nama_kota_kab'],      // From form: nama_kota_kab
                'NAMAPROPDOM' => $validatedData['propinsi'],             // From form: propinsi
                'NAMAATLET' => $validatedData['nama_atlet'],           // From form: nama_atlet
                'GENDER' => $validatedData['gender'],                   // From form: gender
                'KU' => $validatedData['ku'],                           // From form: ku
                'NONIAS' => $validatedData['selected_nias_nonias'],     // From hidden input
                'TGLLAHIR' => $tglLahir,                                 // Calculated TGLLAHIR
                // 'EXP1009' => $validatedData['selected_nias_exp1009'],    // From hidden input (if you want to save this)

                // NEW MAPPINGS BASED ON YOUR REQUEST:
                'ASAL' => $validatedData['nama_kota_kab'],             // 'nama_kota_kab' from form -> ASAL column
                // 'SP' => '0',                                             // Hardcode '0' into SP column as requested
                'SP' => $validatedData['sparing_partner'],
                'EXPIRED' => $expiredDate,
                // 'updated_by' => Auth::id(),
                // Note: 'NEGARA' is not explicitly requested for Atlet table, but you have it in validation if needed
                // 'NEGARA' => $validatedData['negara'],
            ];

            // 2. Find or Create the Atlet record based on NONIAS
            // Assumes NONIAS is your unique identifier for updateOrCreate
            $atlet = Atlet::updateOrCreate(
                ['NONIAS' => $validatedData['selected_nias_nonias']], // Key to find by
                $atletData // Data to update/create
            );

            // // If a new record was created, set created_by
            // if ($atlet->wasRecentlyCreated) {
            //     $atlet->created_by = Auth::id();
            //     $atlet->save(); // Save again to update created_by
            //     $message = 'Data atlet baru berhasil ditambahkan!';
            // } else {
            //     // If it was updated, ensure updated_by is set (it's in $atletData)
            //     // You might want to update it specifically for 'updated_by' if it wasn't in $atletData by default
            //     // $atlet->updated_by = Auth::id(); // Already included in $atletData
            //     $message = 'Data atlet berhasil diperbarui!';
            // }
            $message = $atlet->wasRecentlyCreated ? 'Data atlet baru berhasil ditambahkan!' : 'Data atlet berhasil diperbarui!';

            // Redirect back with a success message
            return redirect()->back()->with('success', $message);
        } catch (ValidationException $e) {
            // Log validation errors for debugging
            Log::error('Validation Error saving Atlet data: ' . json_encode($e->errors()), ['request' => $request->all()]);
            // Redirect back with validation errors and old input
            return redirect()->back()->withErrors($e->errors())->withInput()->with('error', 'Terdapat kesalahan validasi. Mohon periksa input Anda.');
        } catch (\Exception $e) {
            // Handle other potential errors (database, etc.)
            Log::error('Error saving Atlet data: ' . $e->getMessage(), ['request' => $request->all()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data atlet: ' . $e->getMessage())->withInput();
        }
    }

    public function destroyAtlet(Request $request)
    {
        try {
            $request->validate([
                'nonias_to_delete' => 'required|string|max:50', // Validate the NONIAS
            ]);

            $nonias = $request->input('nonias_to_delete');
            Log::info('Attempting to delete Atlet with NONIAS:', ['nonias' => $nonias]);

            // Find the atlet by NONIAS and delete it
            $deletedCount = Atlet::where('NONIAS', $nonias)->delete();

            if ($deletedCount > 0) {
                Log::info('Successfully deleted Atlet with NONIAS:', ['nonias' => $nonias]);
                return redirect()->back()->with('success', "Atlet dengan NONIAS {$nonias} berhasil dihapus.");
            } else {
                Log::warning('Atlet with NONIAS not found or not deleted:', ['nonias' => $nonias]);
                return redirect()->back()->with('error', "Atlet dengan NONIAS {$nonias} tidak ditemukan atau tidak dapat dihapus.");
            }
        } catch (ValidationException $e) {
            Log::error('Validation Error deleting Atlet data: ' . json_encode($e->errors()), ['request' => $request->all()]);
            return redirect()->back()->withErrors($e->errors())->with('error', 'Gagal menghapus atlet: ID Atlet tidak valid.');
        } catch (\Exception $e) {
            Log::error('Error deleting Atlet data: ' . $e->getMessage(), ['request' => $request->all()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data atlet: ' . $e->getMessage());
        }
    }

    public function searchNias(Request $request)
    {
        $searchQuery = $request->query('query'); // Get the search term from the 'query' parameter
        $page = $request->query('page', 1); // Get the current page for pagination
        $perPage = 20; // Number of items per page, match your view

        $user = Auth::user();

        $kompetisi = Kompetisi::first(); // Fetch the relevant competition record

        $query = NIAS::query();

        // Apply filtering logic based on JNSKOMPETISI (similar to daftarAtlet)
        if ($kompetisi && $kompetisi->JNSKOMPETISI) {
            $userJnsKompetisi = mb_strtoupper($kompetisi->JNSKOMPETISI, 'UTF-8');
            switch ($userJnsKompetisi) {
                case 'K':
                    if ($user->JENISDOM) {
                        $query->whereRaw('UPPER(JENISDOM) = ?', [mb_strtoupper($user->JENISDOM, 'UTF-8')]);
                    } else {
                        $query->whereRaw('1 = 0');
                    }
                    if ($user->NAMAKOTADOM) {
                        $query->whereRaw('UPPER(NAMAKOTADOM) = ?', [mb_strtoupper($user->NAMAKOTADOM, 'UTF-8')]);
                    } else {
                        $query->whereRaw('1 = 0');
                    }
                    break;
                case 'C':
                    if ($user->NAMACLUB) {
                        $query->whereRaw('UPPER(NAMACLUB) = ?', [mb_strtoupper($user->NAMACLUB, 'UTF-8')]);
                    } else {
                        $query->whereRaw('1 = 0');
                    }
                    break;
                case 'P':
                    if ($user->NAMAPROP) {
                        $query->whereRaw('UPPER(NAMAPROP) = ?', [mb_strtoupper($user->NAMAPROP, 'UTF-8')]);
                    } else {
                        $query->whereRaw('1 = 0');
                    }
                    break;
                default:
                    $query->whereRaw('1 = 0');
                    break;
            }
        } else {
            // If no competition or JNSKOMPETISI, return no results for search
            $query->whereRaw('1 = 0');
        }

        // Apply search query: search by NONIAS or NAMA
        if ($searchQuery) {
            $searchQueryUpper = mb_strtoupper($searchQuery, 'UTF-8');
            $query->where(function ($q) use ($searchQueryUpper) {
                $q->whereRaw('UPPER(NONIAS) LIKE ?', ['%' . $searchQueryUpper . '%'])
                    ->orWhereRaw('UPPER(NAMA) LIKE ?', ['%' . $searchQueryUpper . '%']);
            });
        }

        $niasList = $query->orderBy('NAMA', 'asc')->paginate($perPage, ['*'], 'page', $page);

        // Return the paginated data as JSON
        return response()->json($niasList);
    }
}
