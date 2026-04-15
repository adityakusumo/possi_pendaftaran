<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\Nias;
use App\Models\User;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    // ── Tampilkan halaman setting ─────────────────────────────────
    public function index()
    {
        // Setting NIAS
        $niasOpenDate  = AppSetting::get('nias_open_date');
        $niasCloseDate = AppSetting::get('nias_close_date');
        $maxAccountsJson = AppSetting::get('nias_max_accounts_per_club', '{}');
        $maxAccountsMap  = json_decode($maxAccountsJson, true) ?? [];

        // Daftar semua club dari lookup + jumlah akun aktif per club
        $allClubs = array_keys(Nias::$clubLookup);
        sort($allClubs);

        $clubStats = [];
        foreach ($allClubs as $club) {
            $clubStats[$club] = [
                'count' => User::where('namaclub', $club)->where('role', 'regular')->count(),
                'max'   => $maxAccountsMap[$club] ?? 2, // default 2
            ];
        }

        // Data untuk tab Lomba — daftar user (sama seperti template lama)
        $search = request('cari');
        $users  = User::when($search, fn($q) => $q->where('nama', 'like', "%{$search}%")
                                                   ->orWhere('email', 'like', "%{$search}%"))
                      ->orderBy('nama')
                      ->paginate(20)
                      ->withQueryString();

        return view('settings', compact(
            'niasOpenDate', 'niasCloseDate',
            'clubStats', 'allClubs',
            'users'
        ));
    }

    // ── Simpan setting jadwal & batas akun NIAS ───────────────────
    public function saveNias(Request $request)
    {
        $request->validate([
            'nias_open_date'  => 'nullable|date',
            'nias_close_date' => 'nullable|date|after_or_equal:nias_open_date',
        ], [
            'nias_close_date.after_or_equal' => 'Tanggal tutup harus sama atau setelah tanggal buka.',
        ]);

        AppSetting::set('nias_open_date',  $request->nias_open_date);
        AppSetting::set('nias_close_date', $request->nias_close_date);

        // Simpan batas akun per club
        // Blade menggunakan name="max_accounts[NamaClub]" sehingga Laravel
        // otomatis parse sebagai array $request->max_accounts
        $maxAccounts = $request->input('max_accounts', []);
        $map = [];
        foreach ($maxAccounts as $club => $max) {
            if ($max !== null && $max !== '') {
                $map[$club] = (int) $max;
            }
        }
        AppSetting::set('nias_max_accounts_per_club', json_encode($map));

        return redirect()->route('settings')->with('success', 'Setting NIAS berhasil disimpan.');
    }

    // ── Reset jadwal NIAS (tutup pendaftaran) ─────────────────────
    public function resetNiasSchedule()
    {
        AppSetting::set('nias_open_date',  null);
        AppSetting::set('nias_close_date', null);

        return redirect()->route('settings')->with('success', 'Jadwal NIAS direset. Pendaftaran sekarang tertutup.');
    }


    // ── Reset Password User ───────────────────────────────────────
    public function resetUserPassword(\App\Models\User $user)
    {
        $user->update(['password' => \Illuminate\Support\Facades\Hash::make('Possi@1234')]);
        return redirect()->route('settings', ['tab' => 'lomba'])
            ->with('success', "Password {$user->nama} berhasil direset menjadi: Possi@1234");
    }

    // ── Hapus User ────────────────────────────────────────────────
    public function deleteUser(\App\Models\User $user)
    {
        if ($user->role === 'admin') {
            return redirect()->route('settings', ['tab' => 'lomba'])
                ->with('error', 'Akun admin tidak bisa dihapus.');
        }
        $user->delete();
        return redirect()->route('settings', ['tab' => 'lomba'])
            ->with('success', "Akun {$user->nama} berhasil dihapus.");
    }
}