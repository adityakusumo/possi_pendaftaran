<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kompetisi; // Import your Kompetisi model
use Illuminate\View\View; // For the index method return type
use Illuminate\Support\Facades\Log;

class CompetitionSettingController extends Controller
{
    /**
     * Display the competition settings view and pass current setting.
     */
    public function index(): View
    {
        // Try to find the current active competition type setting
        // We'll assume the single 'active' competition setting is stored in the record with ID 1.
        // If it doesn't exist yet, $currentKompetisiSetting will be null.
        $currentKompetisiSetting = Kompetisi::find(1);

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
            \Log::error('Error updating competition type: ' . $e->getMessage());

            return response()->json(['message' => 'Failed to update competition type. Please try again.', 'error' => $e->getMessage()], 500);
        }
    }
}
