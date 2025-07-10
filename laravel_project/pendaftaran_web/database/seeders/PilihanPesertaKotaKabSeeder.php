<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PilihanPesertaKotaKab;
use Illuminate\Support\Facades\File;

class PilihanPesertaKotaKabSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PilihanPesertaKotaKab::truncate(); // Hapus semua data yang ada sebelumnya

        $processedData = [];

        // --- DEFINISIKAN JALUR KE FILE JSON ANDA ---
        // Asumsi pendaftaran_web/list_wilayah ada di root proyek Anda
        $baseDataPath = base_path('list_wilayah/');

        // 1. Baca provinces.json untuk membuat peta kode_provinsi ke nama_provinsi
        $provinsiJsonPath = $baseDataPath . 'provinces.json';
        if (!File::exists($provinsiJsonPath)) {
            $this->command->error("ERROR: File provinces.json TIDAK DITEMUKAN di: " . $provinsiJsonPath);
            $this->command->info("Pastikan Anda telah menempatkannya di 'pendaftaran_web/list_wilayah/' di root proyek Anda.");
            return;
        }
        $provinsiContent = File::get($provinsiJsonPath);
        $provinsiData = json_decode($provinsiContent, true);
        $provinsiMap = [];

        if (isset($provinsiData['data']) && is_array($provinsiData['data'])) {
            foreach ($provinsiData['data'] as $provinsi) {
                $provinsiMap[$provinsi['code']] = strtoupper($provinsi['name']);
            }
        } else {
            $this->command->error("ERROR: Format provinces.json tidak valid. Kunci 'data' tidak ditemukan atau bukan array.");
            return;
        }


        // 2. Baca regencies.json (yang sekarang berisi KABUPATEN dan KOTA)
        $regenciesJsonPath = $baseDataPath . 'regencies.json';
        if (!File::exists($regenciesJsonPath)) {
            $this->command->error("ERROR: File regencies.json TIDAK DITEMUKAN di: " . $regenciesJsonPath);
            $this->command->info("Pastikan Anda telah menempatkannya di 'pendaftaran_web/list_wilayah/' di root proyek Anda.");
            return;
        }
        $regenciesContent = File::get($regenciesJsonPath);
        $regenciesData = json_decode($regenciesContent, true);

        foreach ($regenciesData as $item) { // Menggunakan $item karena bisa KABUPATEN atau KOTA
            $namaProvinsi = $provinsiMap[$item['province_id']] ?? 'UNKNOWN PROVINSI';
            $rawName = strtoupper($item['name']);

            $jenis = '';
            $namaKota = '';
            $namaClub = '';

            // Periksa apakah nama diawali dengan "KABUPATEN "
            if (str_starts_with($rawName, 'KABUPATEN ')) {
                $jenis = 'KAB';
                $namaKota = str_replace('KABUPATEN ', '', $rawName);
                $namaClub = 'KAB. ' . $namaKota;
            }
            // Periksa apakah nama diawali dengan "KOTA "
            elseif (str_starts_with($rawName, 'KOTA ')) {
                $jenis = 'KOTA';
                $namaKota = str_replace('KOTA ', '', $rawName);
                $namaClub = 'KOTA ' . $namaKota;
            }
            // Fallback jika ada nama yang tidak sesuai format (jarang terjadi pada data wilayah resmi)
            else {
                $jenis = 'UNKNOWN'; // Atau Anda bisa memutuskan untuk tidak memasukkan entri ini
                $namaKota = $rawName;
                $namaClub = $rawName;
                $this->command->warn("Peringatan: Format nama wilayah tidak terduga: " . $rawName);
            }

            $processedData[] = [
                'NAMACLUB' => $namaClub,
                'JENIS' => $jenis,
                'NAMAKOTA' => $namaKota,
                'NAMAPROPINSI' => $namaProvinsi,
                'NAMANEGARA' => 'INDONESIA',
            ];
        }

        // Masukkan data yang sudah diproses ke database per batch untuk efisiensi
        foreach (array_chunk($processedData, 1000) as $chunk) {
            PilihanPesertaKotaKab::insert($chunk);
        }

        $this->command->info('Seeding PilihanPesertaKotaKab completed with ' . count($processedData) . ' entries.');
    }
}
