# NIAS Registration — Laravel Web App
### POSSI Jawa Timur | Sistem Pendaftaran NIAS (Nomor Induk Anggota Selam)

---

## Cara Install (5 menit)

### 1. Buat proyek Laravel baru
```bash
composer create-project laravel/laravel nias-app
cd nias-app
```

### 2. Salin file dari zip ini ke dalam proyek

| File dari zip                                          | Tujuan di proyek Laravel         |
|--------------------------------------------------------|----------------------------------|
| `app/Models/Nias.php`                                  | `app/Models/Nias.php`            |
| `app/Http/Controllers/NiasController.php`              | `app/Http/Controllers/`          |
| `routes/web.php`                                       | `routes/web.php` (timpa)         |
| `routes/console.php`                                   | `routes/console.php` (timpa)     |
| `database/migrations/2024_01_01_000001_create_nias_table.php` | `database/migrations/`    |
| `resources/views/layouts/app.blade.php`                | `resources/views/layouts/`       |
| `resources/views/nias/*.blade.php` (5 file)            | `resources/views/nias/` (baru)   |

### 3. Setup environment
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dbnias
DB_USERNAME=root
DB_PASSWORD=password_anda
```

### 4. Buat database di MariaDB
```sql
CREATE DATABASE dbnias CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 5. Jalankan migrasi
```bash
php artisan migrate
```

### 6. Jalankan server
```bash
php artisan serve
```

Buka: **http://localhost:8000**

---

## Routes

```
GET    /               → redirect ke /nias
GET    /nias           → Daftar semua anggota (+ search)
GET    /nias/create    → Formulir pendaftaran baru
POST   /nias           → Simpan pendaftaran (auto: TGLDAFTAR, EXPIRED +2th)
GET    /nias/{id}      → Detail anggota
GET    /nias/{id}/edit → Form edit
PUT    /nias/{id}      → Simpan perubahan
DELETE /nias/{id}      → Hapus
```

---

## Struktur Tabel NIAS (identik dengan DBNIAS.mdb)

| Kolom        | Tipe         | Keterangan                             |
|--------------|--------------|----------------------------------------|
| ID           | BIGINT PK AI | Primary key                            |
| NONIAS       | VARCHAR(20)  | Nomor NIAS (unique, opsional)          |
| NAMA         | VARCHAR(100) | Nama lengkap (UPPERCASE)               |
| GENDER       | CHAR(1)      | L / P                                  |
| TGLLAHIR     | DATE         | Tanggal lahir                          |
| TEMPATLAHIR  | VARCHAR(100) | Tempat lahir                           |
| NIK          | VARCHAR(20)  | NIK KTP                                |
| EMAIL        | VARCHAR(100) | Email                                  |
| NAMACLUB     | VARCHAR(100) | Nama klub (dari combobox)              |
| KDCLUB       | VARCHAR(5)   | Kode klub (auto dari lookup)           |
| KDJENIS      | CHAR(1)      | 0=Kota 1=Kab (kota klub)              |
| JENIS        | VARCHAR(10)  | KOTA / KAB                             |
| KDKOTA       | VARCHAR(10)  | Kode kota klub                         |
| NAMAKOTA     | VARCHAR(100) | Nama kota klub                         |
| KDJENISDOM   | CHAR(1)      | 0=Kota 1=Kab (domisili)               |
| JENISDOM     | VARCHAR(10)  | KOTA / KAB                             |
| KDPROPDOM    | VARCHAR(5)   | 05 = Jawa Timur (hardcoded)            |
| NAMAPROPDOM  | VARCHAR(50)  | JAWA TIMUR (hardcoded)                 |
| KDKOTADOM    | VARCHAR(10)  | Kode kota domisili                     |
| NAMAKOTADOM  | VARCHAR(100) | Nama kota domisili                     |
| STATUS       | TINYINT      | 1=Aktif 0=Non-aktif                    |
| TGLDAFTAR    | DATE         | **Auto = hari ini**                    |
| EXPIRED      | DATE         | **Auto = TGLDAFTAR + 2 tahun**         |
| LASTMUTASI   | VARCHAR(10)  | YYYYMM terakhir update                 |
| MUTASI       | VARCHAR(5)   | P=Pindah, dll                          |

---

## Kebutuhan Sistem
- PHP >= 8.1
- Laravel 10.x
- MariaDB / MySQL 5.7+
- Composer
