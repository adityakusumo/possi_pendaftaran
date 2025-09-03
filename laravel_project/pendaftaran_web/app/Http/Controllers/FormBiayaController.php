<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View; // Import View class
use Illuminate\Support\Facades\Auth; // For Auth::user()
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Kompetisi; // Assuming you have a Kompetisi model
use App\Models\MstPeserta;
use App\Models\rKwtDaftarDeposit;
use App\Models\MstKU;
use App\Models\Atlet;
use App\Models\A3;
use Carbon\Carbon;

class FormBiayaController extends Controller
{
    /**
     * Display the Form A3 - Nomor Perorangan view.
     */
    public function indexHitungBiaya(): View
    {
        $user = Auth::user();

        // If user is not authenticated, redirect to login
        if (!$user) {
            return redirect()->route('login')->withErrors('Silakan masuk untuk melanjutkan.');
        }

        // 1. Fetch user's MstPeserta data
        $mstPesertaData = MstPeserta::where('email', $user->email)->first();

        // 2. Prepare the data for the text boxes, with default values if not found
        $namaClub = Str::upper($mstPesertaData->NAMACLUB ?? '');
        $kotaKab = Str::upper($mstPesertaData->JENISDOM ?? '');
        $namaKotaKab = Str::upper($mstPesertaData->NAMAKOTADOM ?? '');
        $propinsi = Str::upper($mstPesertaData->NAMAPROPDOM ?? '');
        $negara = Str::upper($mstPesertaData->NAMANEGDOM ?? 'INDONESIA');

        // 3. Fetch competition data for radio buttons
        $kompetisi = Kompetisi::first();
        $defaultSpType = (($kompetisi->WAJIBNIAS ?? 0) == 1) ? 'SP_TANPA_NIAS' : 'BEBAS';
        $jnsKompetisi = strtoupper($kompetisi->JNSKOMPETISI ?? '');
        $defaultCompetitionType = match ($jnsKompetisi) {
            'K' => 'ANTAR_KOTAKAB',
            'C' => 'ANTAR_CLUB',
            default => 'ANTAR_PROPINSI',
        };

        // Fetch all data from the rKwtDaftarDeposit table
        // $biayaList = rKwtDaftarDeposit::all();
        $biayaList = RKwtDaftarDeposit::where('email', $user->email)->get();

        // 4. Pass all data to the view
        return view('hitung_biaya', compact(
            'namaClub',
            'kotaKab',
            'namaKotaKab',
            'propinsi',
            'negara',
            'defaultSpType',
            'defaultCompetitionType',
            'biayaList'
        ));
    }
}
