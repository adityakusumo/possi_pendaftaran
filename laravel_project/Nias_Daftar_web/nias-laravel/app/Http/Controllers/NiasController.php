<?php

namespace App\Http\Controllers;

use App\Models\Nias;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class NiasController extends Controller
{
    // -------------------------------------------------------------------------
    // INDEX
    // -------------------------------------------------------------------------
    public function index(Request $request)
    {
        $query = Nias::where('user_id', Auth::id())
                     ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('NAMA',     'like', "%{$s}%")
                  ->orWhere('NONIAS',    'like', "%{$s}%")
                  ->orWhere('NAMACLUB',  'like', "%{$s}%");
            });
        }

        $records = $query->paginate(20)->withQueryString();

        return view('nias.index', compact('records'));
    }

    // -------------------------------------------------------------------------
    // CREATE
    // -------------------------------------------------------------------------
    public function create()
    {
        $domisilis = array_keys(Nias::$domisiliLookup);
        sort($domisilis);

        // Club otomatis dari akun pelatih
        $userClub = Auth::user()->namaclub;

        return view('nias.create', compact('domisilis', 'userClub'));
    }

    // -------------------------------------------------------------------------
    // STORE
    // -------------------------------------------------------------------------
    public function store(Request $request)
    {
        $validated = $request->validate([
            'NAMA'        => 'required|string|max:100',
            'GENDER'      => 'required|in:L,P',
            'TGLLAHIR'    => 'required|date|before:today',
            'TEMPATLAHIR' => 'required|string|max:100',
            'NIK'         => 'nullable|digits:16',
            'EMAIL'       => 'nullable|email|max:100',
            'NAMAKOTADOM' => 'required|string|max:100',
            // File wajib
            'file_kk'     => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'file_foto'   => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'file_akte'   => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            // File opsional (tidak ditulis opsional di UI)
            'file_ijazah' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ], [
            'file_kk.required'   => 'File Kartu Keluarga wajib diupload.',
            'file_foto.required' => 'File Foto wajib diupload.',
            'file_akte.required' => 'File Akte Lahir wajib diupload.',
            'file_kk.mimes'      => 'File KK harus berformat PDF, JPG, atau PNG.',
            'file_foto.mimes'    => 'File Foto harus berformat PDF, JPG, atau PNG.',
            'file_akte.mimes'    => 'File Akte harus berformat PDF, JPG, atau PNG.',
            'file_ijazah.mimes'  => 'File Ijazah harus berformat PDF, JPG, atau PNG.',
            'file_kk.max'        => 'Ukuran file KK maksimal 5MB.',
            'file_foto.max'      => 'Ukuran file Foto maksimal 5MB.',
            'file_akte.max'      => 'Ukuran file Akte maksimal 5MB.',
            'file_ijazah.max'    => 'Ukuran file Ijazah maksimal 5MB.',
            'NIK.digits'         => 'NIK harus 16 digit angka.',
        ]);

        // Club dari akun pelatih (bukan dari form)
        $namaclub = Auth::user()->namaclub;
        $clubInfo = Nias::$clubLookup[$namaclub] ?? null;
        $clubCode = Nias::$clubCodeLookup[$namaclub] ?? null;

        // Domisili
        $domInfo = Nias::$domisiliLookup[$validated['NAMAKOTADOM']] ?? null;

        // Dates
        $today   = Carbon::today();
        $expired = $today->copy()->addYears(2);

        // Upload files — simpan di storage/app/private/nias/{user_id}/
        $folder   = 'nias/' . Auth::id();
        $fileKk     = $request->file('file_kk')->store($folder, 'local');
        $fileFoto   = $request->file('file_foto')->store($folder, 'local');
        $fileAkte   = $request->file('file_akte')->store($folder, 'local');
        $fileIjazah = $request->hasFile('file_ijazah')
                        ? $request->file('file_ijazah')->store($folder, 'local')
                        : null;

        DB::transaction(function () use (
            $validated, $namaclub, $clubInfo, $clubCode, $domInfo,
            $today, $expired, $fileKk, $fileFoto, $fileAkte, $fileIjazah
        ) {
            Nias::create([
                'user_id'     => Auth::id(),
                'NAMA'        => strtoupper(trim($validated['NAMA'])),
                'GENDER'      => $validated['GENDER'],
                'TGLLAHIR'    => $validated['TGLLAHIR'],
                'TEMPATLAHIR' => strtoupper(trim($validated['TEMPATLAHIR'])),
                'NIK'         => $validated['NIK']   ?? null,
                'EMAIL'       => $validated['EMAIL'] ?? null,
                'NAMACLUB'    => $namaclub,
                'KDCLUB'      => $clubCode,
                'KDJENIS'     => $clubInfo[0] ?? null,
                'JENIS'       => $clubInfo[1] ?? null,
                'KDKOTA'      => $clubInfo[2] ?? null,
                'NAMAKOTA'    => $clubInfo[3] ?? null,
                'KDJENISDOM'  => $domInfo[0] ?? null,
                'JENISDOM'    => $domInfo[1] ?? null,
                'KDPROPDOM'   => '05',
                'NAMAPROPDOM' => 'JAWA TIMUR',
                'KDKOTADOM'   => $domInfo[2] ?? null,
                'NAMAKOTADOM' => $validated['NAMAKOTADOM'],
                'STATUS'      => 1,
                'TGLDAFTAR'   => $today->toDateString(),
                'EXPIRED'     => $expired->toDateString(),
                'LASTMUTASI'  => $today->format('Ym'),
                'MUTASI'      => null,
                'file_kk'     => $fileKk,
                'file_foto'   => $fileFoto,
                'file_akte'   => $fileAkte,
                'file_ijazah' => $fileIjazah,
            ]);
        });

        return redirect()->route('nias.index')
            ->with('success', 'Pendaftaran NIAS berhasil! Masa berlaku s/d: ' . $expired->format('d/m/Y'));
    }

    // -------------------------------------------------------------------------
    // SHOW
    // -------------------------------------------------------------------------
    public function show(Nias $nias)
    {
        $this->authorizeNias($nias);
        return view('nias.show', compact('nias'));
    }

    // -------------------------------------------------------------------------
    // EDIT
    // -------------------------------------------------------------------------
    public function edit(Nias $nias)
    {
        $this->authorizeNias($nias);

        $domisilis = array_keys(Nias::$domisiliLookup);
        sort($domisilis);

        $userClub = Auth::user()->namaclub;

        return view('nias.edit', compact('nias', 'domisilis', 'userClub'));
    }

    // -------------------------------------------------------------------------
    // UPDATE
    // -------------------------------------------------------------------------
    public function update(Request $request, Nias $nias)
    {
        $this->authorizeNias($nias);

        $validated = $request->validate([
            'NAMA'        => 'required|string|max:100',
            'GENDER'      => 'required|in:L,P',
            'TGLLAHIR'    => 'required|date|before:today',
            'TEMPATLAHIR' => 'required|string|max:100',
            'NIK'         => 'nullable|digits:16',
            'EMAIL'       => 'nullable|email|max:100',
            'NAMAKOTADOM' => 'required|string|max:100',
            'file_kk'     => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'file_foto'   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'file_akte'   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'file_ijazah' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $namaclub = Auth::user()->namaclub;
        $clubInfo = Nias::$clubLookup[$namaclub] ?? null;
        $clubCode = Nias::$clubCodeLookup[$namaclub] ?? null;
        $domInfo  = Nias::$domisiliLookup[$validated['NAMAKOTADOM']] ?? null;

        $folder = 'nias/' . Auth::id();

        // Handle file updates — hanya replace kalau ada file baru
        $fileKk     = $request->hasFile('file_kk')
                        ? $request->file('file_kk')->store($folder, 'local')
                        : $nias->file_kk;
        $fileFoto   = $request->hasFile('file_foto')
                        ? $request->file('file_foto')->store($folder, 'local')
                        : $nias->file_foto;
        $fileAkte   = $request->hasFile('file_akte')
                        ? $request->file('file_akte')->store($folder, 'local')
                        : $nias->file_akte;
        $fileIjazah = $request->hasFile('file_ijazah')
                        ? $request->file('file_ijazah')->store($folder, 'local')
                        : $nias->file_ijazah;

        $nias->update([
            'NAMA'        => strtoupper(trim($validated['NAMA'])),
            'GENDER'      => $validated['GENDER'],
            'TGLLAHIR'    => $validated['TGLLAHIR'],
            'TEMPATLAHIR' => strtoupper(trim($validated['TEMPATLAHIR'])),
            'NIK'         => $validated['NIK']   ?? null,
            'EMAIL'       => $validated['EMAIL'] ?? null,
            'NAMACLUB'    => $namaclub,
            'KDCLUB'      => $clubCode,
            'KDJENIS'     => $clubInfo[0] ?? null,
            'JENIS'       => $clubInfo[1] ?? null,
            'KDKOTA'      => $clubInfo[2] ?? null,
            'NAMAKOTA'    => $clubInfo[3] ?? null,
            'KDJENISDOM'  => $domInfo[0] ?? null,
            'JENISDOM'    => $domInfo[1] ?? null,
            'KDKOTADOM'   => $domInfo[2] ?? null,
            'NAMAKOTADOM' => $validated['NAMAKOTADOM'],
            'MUTASI'      => 'P',
            'LASTMUTASI'  => now()->format('Ym'),
            'file_kk'     => $fileKk,
            'file_foto'   => $fileFoto,
            'file_akte'   => $fileAkte,
            'file_ijazah' => $fileIjazah,
        ]);

        return redirect()->route('nias.show', $nias)
            ->with('success', 'Data NIAS berhasil diperbarui.');
    }

    // -------------------------------------------------------------------------
    // DESTROY
    // -------------------------------------------------------------------------
    public function destroy(Nias $nias)
    {
        $this->authorizeNias($nias);

        // Hapus file dari storage
        foreach (['file_kk','file_foto','file_akte','file_ijazah'] as $col) {
            if ($nias->$col) Storage::disk('local')->delete($nias->$col);
        }

        $nias->delete();

        return redirect()->route('nias.index')
            ->with('success', 'Data NIAS berhasil dihapus.');
    }

    // -------------------------------------------------------------------------
    // HELPER
    // -------------------------------------------------------------------------
    private function authorizeNias(Nias $nias): void
    {
        if ($nias->user_id !== Auth::id()) {
            abort(403, 'Kamu tidak punya akses ke data ini.');
        }
    }
}
