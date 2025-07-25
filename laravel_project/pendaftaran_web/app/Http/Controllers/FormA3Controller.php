<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View; // Import View class
use Illuminate\Support\Facades\Auth; // For Auth::user()
use App\Models\Kompetisi; // Assuming you have a Kompetisi model

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

        // Pass data to the view
        return view('form_a3_noperorangan', compact(
            'clubName',
            'kotaKab',
            'propinsi',
            'negara',
            'daftarEntriList',
            'kontingenSummary'
        ));
    }

    // You will add other methods (e.g., savePerorangan, deletePerorangan, searchAtlet) here later
}
