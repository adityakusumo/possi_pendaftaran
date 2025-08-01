<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View; // Import View class
use Illuminate\Support\Facades\Auth; // For Auth::user()
use Illuminate\Support\Facades\Log;
use App\Models\Kompetisi; // Assuming you have a Kompetisi model
use App\Models\MstPeserta;
use App\Models\MstKU;
use App\Models\NIAS;
use App\Models\Atlet;
use App\Models\A3;
use Carbon\Carbon;

class FormA3Controller extends Controller
{
    /**
     * Display the Form A3 - Nomor Perorangan view.
     */
    public function index(): View
    {
        $user = Auth::user();

        // If user is not authenticated, redirect to login
        if (!$user) {
            return redirect()->route('login')->withErrors('Silakan masuk untuk melanjutkan.');
        }

        // --- Data for the view (initialize as empty or default) ---
        // These will be populated as you implement the actual logic for Form A3
        $clubName = $user->NAMACLUB ?? 'Nama Club User'; // Example: Get from authenticated user
        $kotaKab = $user->NAMAKOTADOM ?? 'Kota/Kab User'; // Example: Get from authenticated user
        $propinsi = $user->NAMAPROPDOM ?? 'Propinsi User'; // Example: Get from authenticated user
        $negara = 'INDONESIA'; // Default

        $userEmail = $user->email;
        // This list will be populated by the "Daftar Entri" table on the right
        $daftarEntriList = \App\Models\A3::where('email', $userEmail)
            ->orderBy('GENDER', 'asc')    // Sort by GENDER first
            ->orderBy('NAMAATLET', 'asc') // Then by NAMAATLET
            ->get();

        // --- ADAPTED PART: ACTIVATE KONTINGEN SUMMARY SECTION ---
        // Base query for counting entries for the current user and 'Perorangan'
        $baseSummaryQuery = A3::where('email', $userEmail)
            ->where('NOMOR', 'Perorangan');

        $kontingenSummary = [
            'atletPa' => $baseSummaryQuery->clone()->where('GENDER', 'PA')->count(),
            'atletPi' => $baseSummaryQuery->clone()->where('GENDER', 'PI')->count(),
            'totalAtlet' => $baseSummaryQuery->clone()->count(),
            'totalSp' => $baseSummaryQuery->clone()->where('SP', 1)->count(), // Assuming 'SP' is stored as 1
        ];

        Log::info('Kontingen Summary Data:', $kontingenSummary);
        // --- END OF ADAPTED PART ---

        // You might fetch competition data here if needed for specific logic
        $kompetisi = Kompetisi::first(); // Or based on a specific competition ID

        // $AtletData = Atlet::orderBy('NAMAATLET', 'asc')->get()->toArray();
        // $NamaAtletList = array_column($AtletData, 'NAMAATLET', 'NAMAATLET');

        // Fetch all relevant Atlet data for the dropdown and JavaScript processing
        // Filter by the current user's updated_by, similar to Form A1, if that's the logic.
        // Or fetch all if it's a global list. Assuming it's based on the user's entries.
        $userID = $user->id;
        $allAtletData = Atlet::where('updated_by', $userID) // Or remove this filter if all athletes are selectable
            ->orderBy('NAMAATLET', 'asc')
            ->get(); // Get all records, not just names

        Log::info('Result of Atlet query (allAtletData): ' . $allAtletData->toJson()); // <--- Log the query result


        // Prepare data for the dropdown (just name and IDATLET as value)
        // And prepare a JavaScript-friendly array of full athlete details
        $NamaAtletList = [];
        $atletDetailsForJs = []; // This will hold the full details for JS
        foreach ($allAtletData as $atlet) {
            $NamaAtletList[$atlet->IDATLET] = $atlet->NAMAATLET; // Use IDATLET as value for dropdown
            $atletDetailsForJs[$atlet->IDATLET] = [ // Store full details keyed by IDATLET
                'IDATLET' => $atlet->IDATLET,
                'NONIAS' => $atlet->NONIAS,
                'NAMAATLET' => $atlet->NAMAATLET,
                'GENDER' => $atlet->GENDER,
                'KU' => $atlet->KU,
                'NAMACLUB' => $atlet->NAMACLUB,
                'JENISDOM' => $atlet->JENISDOM,
                'NAMAKOTADOM' => $atlet->NAMAKOTADOM,
                'NAMAPROPDOM' => $atlet->NAMAPROPDOM,
                'SP' => $atlet->SP,
                'TGLLAHIR' => Carbon::parse($atlet->TGLLAHIR)->toDateString(), // <-- MAKE SURE THIS LINE IS PRESENT
                'ASAL' => $atlet->ASAL,       // <-- MAKE SURE THIS LINE IS PRESENT
                // Add any other fields you might need for auto-filling later (e.g., TGLLAHIR, EXPIRED)
            ];
        }

        Log::info('Final atletDetailsForJs sent to view: ' . json_encode($atletDetailsForJs)); // <--- Log the final array

        // Fetch A3 entries for the current user
        // Select only the columns needed for matching (NAMAATLET, GENDER, TGLLAHIR, email)
        $existingA3Entries = A3::where('email', $userEmail)
            ->select('NAMAATLET', 'GENDER', 'TGLLAHIR', 'email')
            ->get()
            ->map(function ($entry) {
                // Ensure TGLLAHIR is also formatted as YYYY-MM-DD for consistent matching
                $entry->TGLLAHIR = Carbon::parse($entry->TGLLAHIR)->toDateString();
                return $entry;
            });

        Log::info('Existing A3 entries for user (for filtering): ' . json_encode($existingA3Entries));

        // Pass data to the view
        return view('form_a3_noperorangan', compact(
            'clubName',
            'kotaKab',
            'propinsi',
            'negara',
            'daftarEntriList',
            'kontingenSummary',
            'NamaAtletList', // For the Blade dropdown
            'atletDetailsForJs', // For JavaScript to read
            'existingA3Entries',
        ));
    }

    /**
     * Handles the AJAX request to save perorangan data to A3 table.
     */
    public function savePerorangan(Request $request)
    {
        try {
            // 1. Server-side Validation (CRUCIAL!)
            $request->validate([
                'gender' => ['required', 'string', 'in:PA,PI'],
                'ku' => ['required', 'string', 'max:255'],
                'nama_atlet' => ['required', 'string', 'max:255'],
                'asal' => ['nullable', 'string', 'max:255'],
                'nama_club' => ['required', 'string', 'max:255'],
                'jenis_dom' => ['nullable', 'string', 'max:255'],
                'nama_kota_dom' => ['nullable', 'string', 'max:255'],
                'nama_prop_dom' => ['nullable', 'string', 'max:255'],
                'sp' => ['nullable', 'string', 'max:255'],
                'tgl_lahir' => ['required', 'date'],
                'nomor' => ['required', 'string', 'in:Perorangan'],
                'email' => ['required', 'email', 'max:255'],
                'gendermix' => ['nullable', 'string', 'in:0'],
                // Validate time inputs as nullable integers
                'MON50MM' => ['nullable', 'integer', 'min:0', 'max:99'],
                'MON50SS' => ['nullable', 'integer', 'min:0', 'max:99'],
                'MON50HS' => ['nullable', 'integer', 'min:0', 'max:99'],
                // Add validation rules for other distances (MON100MM, MON100SS, etc.) as you enable them
            ]);

            // 2. Prepare data for insertion
            $dataToSave = [
                'GENDER' => $request->gender,
                'KU' => $request->ku,
                'NAMAATLET' => $request->nama_atlet,
                'ASAL' => $request->asal,
                'NAMACLUB' => $request->nama_club,
                'JENISDOM' => $request->jenis_dom,
                'NAMAKOTADOM' => $request->nama_kota_dom,
                'NAMAPROPDOM' => $request->nama_prop_dom,
                'SP' => $request->sp,
                // 'TGLLAHIR' => $request->tgl_lahir,
                // Ensure TGLLAHIR is in a format Laravel's updateOrCreate/database likes for comparison
                // Since it's cast to 'date' in the model, Carbon::parse() will handle the ISO string.
                'TGLLAHIR' => Carbon::parse($request->tgl_lahir)->toDateString(), // Store as 'YYYY-MM-DD'
                'NOMOR' => $request->nomor,
                'email' => $request->email,
                'GENDERMIX' => '0',
            ];

            // 3. Process SF 50m time data based on JS logic (re-apply server-side)
            // This is crucial because client-side validation can be bypassed.
            $mon50mm = $request->input('MON50MM');
            $mon50ss = $request->input('MON50SS');
            $mon50hs = $request->input('MON50HS');

            // Check if all are null/empty (from JS sending empty string if checkbox unchecked)
            if ($mon50mm === null && $mon50ss === null && $mon50hs === null) {
                $dataToSave['MON50MM'] = null; // Or 99 if you want to store 99 for unchecked
                $dataToSave['MON50SS'] = null; // Or 99
                $dataToSave['MON50HS'] = null; // Or 99
            } else {
                // If checkbox was checked and values processed by JS, they should be integers or '0'/'99'
                $dataToSave['MON50MM'] = (int)$mon50mm;
                $dataToSave['MON50SS'] = (int)$mon50ss;
                $dataToSave['MON50HS'] = (int)$mon50hs;

                // Server-side re-validation for SS < 1 for checked inputs
                if ($dataToSave['MON50SS'] < 1 && ($mon50mm !== '99' || $mon50ss !== '99' || $mon50hs !== '99')) {
                    return response()->json(['success' => false, 'message' => 'Input waktu 50m tidak boleh kurang dari 1 detik!'], 422);
                }
            }

            // You would repeat this processing for other distances (MON100MM, MON100SS, etc.)
            // Example for 100m:
            // $mon100mm = $request->input('MON100MM');
            // $mon100ss = $request->input('MON100SS');
            // $mon100hs = $request->input('MON100HS');
            // if ($mon100mm === null && $mon100ss === null && $mon100hs === null) {
            //     $dataToSave['MON100MM'] = null;
            //     $dataToSave['MON100SS'] = null;
            //     $dataToSave['MON100HS'] = null;
            // } else {
            //     $dataToSave['MON100MM'] = (int)$mon100mm;
            //     $dataToSave['MON100SS'] = (int)$mon100ss;
            //     $dataToSave['MON100HS'] = (int)$mon100hs;
            //     if ($dataToSave['MON100SS'] < 1 && ($mon100mm !== '99' || $mon100ss !== '99' || $mon100hs !== '99')) {
            //         return response()->json(['success' => false, 'message' => 'Input waktu 100m tidak boleh kurang dari 1 detik!'], 422);
            //     }
            // }

            // Define the attributes to find a matching record
            // These four columns will be used to check for existence
            $matchCriteria = [
                'NAMAATLET' => $request->nama_atlet,
                'GENDER' => $request->gender,
                'TGLLAHIR' => Carbon::parse($request->tgl_lahir)->toDateString(), // Use the same formatted date for matching
                'email' => $request->email,
            ];

            // Use updateOrCreate to either update the existing record or create a new one
            // All fields in $dataToSave will be used for updating or inserting
            $a3Record = A3::updateOrCreate($matchCriteria, $dataToSave);

            // Log the action (optional, but good for debugging)
            if ($a3Record->wasRecentlyCreated) {
                Log::info('New A3 record created:', $dataToSave);
                return response()->json(['success' => true, 'message' => 'Data baru berhasil disimpan!']);
            } else {
                Log::info('Existing A3 record updated:', $dataToSave);
                return response()->json(['success' => true, 'message' => 'Data berhasil diperbarui!']);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            Log::error('Validation Error saving A3 data: ' . $e->getMessage(), $e->errors());
            return response()->json(['success' => false, 'message' => 'Validasi gagal', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            // Handle other exceptions
            Log::error('Error saving A3 data: ' . $e->getMessage(), $request->all());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan server saat menyimpan data.'], 500);
        }
    }

    public function deletePerorangan(Request $request, $id)
    {
        // 1. Get the authenticated user's email for a security check
        $currentUserEmail = Auth::user()->email;

        try {
            // 2. Find the A3 record by its ID and the current user's email
            $a3Record = A3::where('IDA3P', $id)
                ->where('email', $currentUserEmail)
                ->first();

            // 3. Check if the record exists and belongs to the user
            if (!$a3Record) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan atau Anda tidak memiliki izin untuk menghapusnya.'
                ], 404); // 404 Not Found
            }

            // 4. Delete the record
            $a3Record->delete();

            // 5. Log and return a success response
            Log::info("A3 record with IDA3P={$id} deleted by user {$currentUserEmail}.");
            return response()->json([
                'success' => true,
                'message' => "Data {$a3Record->NAMAATLET} berhasil dihapus."
            ]);
        } catch (\Exception $e) {
            // Handle any exceptions (e.g., database error)
            Log::error('Error deleting A3 data: ' . $e->getMessage(), ['id' => $id, 'user_email' => $currentUserEmail]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server saat menghapus data.'
            ], 500); // 500 Internal Server Error
        }
    }

    // You will add other methods (e.g., savePerorangan, deletePerorangan, searchAtlet) here later

    public function indexEstafet(): View
    {
        $user = Auth::user();

        // If user is not authenticated, redirect to login
        if (!$user) {
            return redirect()->route('login')->withErrors('Silakan masuk untuk melanjutkan.');
        }

        // --- Data for the view (initialize as empty or default) ---
        // These will be populated as you implement the actual logic for Form A3
        $clubName = $user->NAMACLUB ?? 'Nama Club User'; // Example: Get from authenticated user
        $kotaKab = $user->NAMAKOTADOM ?? 'Kota/Kab User'; // Example: Get from authenticated user
        $propinsi = $user->NAMAPROPDOM ?? 'Propinsi User'; // Example: Get from authenticated user
        $negara = 'INDONESIA'; // Default

        $userEmail = $user->email;
        // This list will be populated by the "Daftar Entri" table on the right
        $daftarEntriList = \App\Models\A3::where('email', $userEmail)
            ->orderBy('GENDER', 'asc')    // Sort by GENDER first
            ->orderBy('NAMAATLET', 'asc') // Then by NAMAATLET
            ->get();

        // --- ADAPTED PART: ACTIVATE KONTINGEN SUMMARY SECTION ---
        // Base query for counting entries for the current user and 'Perorangan'
        $baseSummaryQuery = A3::where('email', $userEmail)
            ->where('NOMOR', 'Perorangan');

        $kontingenSummary = [
            'atletPa' => $baseSummaryQuery->clone()->where('GENDER', 'PA')->count(),
            'atletPi' => $baseSummaryQuery->clone()->where('GENDER', 'PI')->count(),
            'totalAtlet' => $baseSummaryQuery->clone()->count(),
            'totalSp' => $baseSummaryQuery->clone()->where('SP', 1)->count(), // Assuming 'SP' is stored as 1
        ];

        Log::info('Kontingen Summary Data:', $kontingenSummary);
        // --- END OF ADAPTED PART ---

        // You might fetch competition data here if needed for specific logic
        $kompetisi = Kompetisi::first(); // Or based on a specific competition ID

        // $AtletData = Atlet::orderBy('NAMAATLET', 'asc')->get()->toArray();
        // $NamaAtletList = array_column($AtletData, 'NAMAATLET', 'NAMAATLET');

        // Fetch all relevant Atlet data for the dropdown and JavaScript processing
        // Filter by the current user's updated_by, similar to Form A1, if that's the logic.
        // Or fetch all if it's a global list. Assuming it's based on the user's entries.
        $userID = $user->id;
        $allAtletData = Atlet::where('updated_by', $userID) // Or remove this filter if all athletes are selectable
            ->orderBy('NAMAATLET', 'asc')
            ->get(); // Get all records, not just names

        Log::info('Result of Atlet query (allAtletData): ' . $allAtletData->toJson()); // <--- Log the query result


        // Prepare data for the dropdown (just name and IDATLET as value)
        // And prepare a JavaScript-friendly array of full athlete details
        $NamaAtletList = [];
        $atletDetailsForJs = []; // This will hold the full details for JS
        foreach ($allAtletData as $atlet) {
            $NamaAtletList[$atlet->IDATLET] = $atlet->NAMAATLET; // Use IDATLET as value for dropdown
            $atletDetailsForJs[$atlet->IDATLET] = [ // Store full details keyed by IDATLET
                'IDATLET' => $atlet->IDATLET,
                'NONIAS' => $atlet->NONIAS,
                'NAMAATLET' => $atlet->NAMAATLET,
                'GENDER' => $atlet->GENDER,
                'KU' => $atlet->KU,
                'NAMACLUB' => $atlet->NAMACLUB,
                'JENISDOM' => $atlet->JENISDOM,
                'NAMAKOTADOM' => $atlet->NAMAKOTADOM,
                'NAMAPROPDOM' => $atlet->NAMAPROPDOM,
                'SP' => $atlet->SP,
                'TGLLAHIR' => Carbon::parse($atlet->TGLLAHIR)->toDateString(), // <-- MAKE SURE THIS LINE IS PRESENT
                'ASAL' => $atlet->ASAL,       // <-- MAKE SURE THIS LINE IS PRESENT
                // Add any other fields you might need for auto-filling later (e.g., TGLLAHIR, EXPIRED)
            ];
        }

        Log::info('Final atletDetailsForJs sent to view: ' . json_encode($atletDetailsForJs)); // <--- Log the final array

        // Fetch A3 entries for the current user
        // Select only the columns needed for matching (NAMAATLET, GENDER, TGLLAHIR, email)
        $existingA3Entries = A3::where('email', $userEmail)
            ->select('NAMAATLET', 'GENDER', 'TGLLAHIR', 'email')
            ->get()
            ->map(function ($entry) {
                // Ensure TGLLAHIR is also formatted as YYYY-MM-DD for consistent matching
                $entry->TGLLAHIR = Carbon::parse($entry->TGLLAHIR)->toDateString();
                return $entry;
            });

        Log::info('Existing A3 entries for user (for filtering): ' . json_encode($existingA3Entries));

        // Pass data to the view
        return view('form_a3_noperorangan', compact(
            'clubName',
            'kotaKab',
            'propinsi',
            'negara',
            'daftarEntriList',
            'kontingenSummary',
            'NamaAtletList', // For the Blade dropdown
            'atletDetailsForJs', // For JavaScript to read
            'existingA3Entries',
        ));
    }
}
