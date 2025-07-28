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

        // This list will be populated by the "Daftar Entri" table on the right
        $daftarEntriList = []; // Initialize as empty array for now

        // Kontingen Summary data
        $kontingenSummary = [
            'atletPa' => 0,
            'atletPi' => 0,
            'totalAtlet' => 0,
            'totalSp' => 0,
        ];

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
                // Add any other fields you might need for auto-filling later (e.g., TGLLAHIR, EXPIRED)
            ];
        }

        Log::info('Final atletDetailsForJs sent to view: ' . json_encode($atletDetailsForJs)); // <--- Log the final array

        // Pass data to the view
        return view('form_a3_noperorangan', compact(
            'clubName',
            'kotaKab',
            'propinsi',
            'negara',
            'daftarEntriList',
            'kontingenSummary',
            'NamaAtletList', // For the Blade dropdown
            'atletDetailsForJs' // For JavaScript to read
        ));
    }

    // You will add other methods (e.g., savePerorangan, deletePerorangan, searchAtlet) here later
}
