<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kompetisi; // Import your Kompetisi model
use Illuminate\View\View; // For the index method return type
use Illuminate\Support\Facades\Log;

class CompetitionSettingController extends Controller
{
    // // Add the constructor for middleware
    // public function __construct()
    // {
    //     $this->middleware(['auth', 'role:admin']); // Ensure only admins can access
    // }

    /**
     * Display the competition settings view and pass current setting.
     */
    public function index(): View
    {
        // Try to find the current active competition type setting
        // We'll assume the single 'active' competition setting is stored in the record with ID 1.
        // If it doesn't exist yet, $currentKompetisiSetting will be null.
        $currentKompetisiSetting = Kompetisi::find(1);

        // If it doesn't exist yet, create a default one
        if (!$currentKompetisiSetting) {
            $currentKompetisiSetting = Kompetisi::create([
                'JNSKOMPETISI' => 'K', // Default value for Competition Type (e.g., 'K' for Kota/Kab)
                'WAJIBNIAS' => 0, // Default value for Wajib Nias (0 for Tidak Wajib)
                // Add any other default columns if needed
            ]);
        }

        return view('competition_settings', compact('currentKompetisiSetting'));
    }

    /**
     * Handle the AJAX request to update the competition type.
     */
    public function updateCompetitionType(Request $request)
    {
        $request->validate([
            'type_code' => 'required|string|in:K,C,P',
            'type_description' => 'required|string',
        ]);

        try {
            // Find the record with ID 1 and update it, or create it if it doesn't exist.
            // This ensures there's only one record for the 'current' competition setting.
            $kompetisiSetting = Kompetisi::updateOrCreate(
                ['id' => 1], // Always target the first (and only) settings record
                [
                    'JNSKOMPETISI' => $request->type_code,
                    'KETKOMPETISI' => $request->type_description,
                ]
            );

            return response()->json(['message' => 'Competition type updated successfully!', 'kompetisi' => $kompetisiSetting], 200);

        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error updating competition type: ' . $e->getMessage());

            return response()->json(['message' => 'Failed to update competition type. Please try again.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the WAJIBNIAS setting via AJAX.
     */
    public function updateWajibNias(Request $request)
    {
        $request->validate([
            'wajib_nias' => 'required|in:0,1',
        ]);

        try {
            // First, get the current settings record. This is crucial for updateOrCreate
            // to retain other column values if you're not explicitly setting them.
            $existingKompetisiSetting = Kompetisi::find(1);

            // Determine the values for JNSKOMPETISI and KETKOMPETISI
            // If existingKompetisiSetting is null, use defaults. Otherwise, use existing values.
            $jnsKompetisiToSet = $existingKompetisiSetting ? $existingKompetisiSetting->JNSKOMPETISI : 'K';
            $ketKompetisiToSet = $existingKompetisiSetting ? $existingKompetisiSetting->KETKOMPETISI : 'ANTAR KOTA';


            $kompetisiSetting = Kompetisi::updateOrCreate(
                ['id' => 1], // Always target the single settings record
                [
                    'WAJIBNIAS' => $request->wajib_nias,
                    'JNSKOMPETISI' => $jnsKompetisiToSet,
                    'KETKOMPETISI' => $ketKompetisiToSet,
                ]
            );

            $message = $request->wajib_nias == '1' ? 'Wajib Nias set to Wajib!' : 'Wajib Nias set to Tidak Wajib!';
            return response()->json(['message' => $message], 200);

        } catch (\Exception $e) {
            Log::error('Error updating Wajib Nias: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to update Wajib Nias. Please try again.', 'error' => $e->getMessage()], 500);
        }
    }

}
