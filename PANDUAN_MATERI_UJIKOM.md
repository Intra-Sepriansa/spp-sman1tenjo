# MODUL MASTER & KNOWLEDGE BASE LENGKAP UJI KOMPETENSI (UJIKOM)
## Skema: Pemrogram / Software Development (BNSP - LSP Telematika / Informatika)

Dokumen ini disusun sebagai panduan menyeluruh, teori fundamental, implementasi teknis kode (*hands-on code*), dokumen portofolio, hingga kisi-kisi wawancara asesor untuk kelulusan **9 Unit Kompetensi SKKNI**.

---

## DAFTAR ISI
1. [Standar & Alur Asesmen BNSP/LSP](#standar--alur-asesmen-bnsplsp)
2. [Bedah Mendalam 9 Unit Kompetensi (Teori & Contoh Kode Nyata)](#bedah-mendalam-9-unit-kompetensi)
   - [Unit 1: Menggunakan Spesifikasi Program (J.620100.009.01)](#unit-1-menggunakan-spesifikasi-program-j62010000901)
   - [Unit 2: Menulis Kode Sesuai Guidelines dan Best Practices (J.620100.016.01)](#unit-2-menulis-kode-sesuai-guidelines-dan-best-practices-j62010001601)
   - [Unit 3: Mengimplementasikan Pemrograman Terstruktur (J.620100.017.02)](#unit-3-mengimplementasikan-pemrograman-terstruktur-j62010001702)
   - [Unit 4: Mengimplementasikan Pemrograman Berorientasi Objek / OOP (J.620100.018.02)](#unit-4-mengimplementasikan-pemrograman-berorientasi-objek--oop-j62010001802)
   - [Unit 5: Menggunakan Library atau Komponen Pre-existing (J.620100.019.02)](#unit-5-menggunakan-library-atau-komponen-pre-existing-j62010001902)
   - [Unit 6: Menerapkan Akses Basis Data (J.620100.021.02)](#unit-6-menerapkan-akses-basis-data-j62010002102)
   - [Unit 7: Membuat Dokumen Kode Program (J.620100.023.02)](#unit-7-membuat-dokumen-kode-program-j62010002302)
   - [Unit 8: Melakukan Debugging (J.620100.025.02)](#unit-8-melakukan-debugging-j62010002502)
   - [Unit 9: Melaksanakan Pengujian Unit Program (J.620100.033.02)](#unit-9-melaksanakan-pengujian-unit-program-j62010003302)
3. [Arsitektur Proyek Referensi (Struktur Folder Lengkap)](#arsitektur-proyek-referensi)
4. [Template Portofolio Dokumen Ujikom](#template-portofolio-dokumen-ujikom)
   - [Template 1: Dokumen Pengujian Unit (Test Case Report)](#template-1-dokumen-pengujian-unit-test-case-report)
   - [Template 2: README.md Standar Sertifikasi](#template-2-readmemd-standar-sertifikasi)
   - [Template 3: Kamus Data Database](#template-3-kamus-data-database)
5. [Bank Tanya Jawab Wawancara Asesor (20+ Pertanyaan Populer)](#bank-tanya-jawab-wawancara-asesor)
6. [Cheatsheet Menangani Error Populer Saat Ujikom](#cheatsheet-menangani-error-populer-saat-ujikom)

---

## STANDAR & ALUR ASESMEN BNSP/LSP

Pada saat Uji Kompetensi, Asesor mengevaluasi 3 aspek utama:
1. **Knowledge (Pengetahuan)**: Kemampuan menjelaskan konsep dasar komputasi, alur logika, sintaks, arsitektur, dan keamanan melalui tes tulis / wawancara lisan.
2. **Skill (Keterampilan)**: Kemampuan membuat aplikasi web/desktop berbasis database secara nyata, menulis kode yang berjalan tanpa error, bersih, dan aman.
3. **Attitude (Sikap Kerja)**: Disiplin penamaan variabel, kerapian indentasi, struktur file, kepatuhan pada spesifikasi soal, dan dokumentasi.

Formulir Ujikom yang akan diisi:
- **FR.APL.01**: Formulir Permohonan Sertifikasi Kompetensi.
- **FR.APL.02**: Asesmen Mandiri (Anda mencentang "K" / Kompeten pada seluruh Kriteria Unjuk Kerja).
- **FR.IA.01 / 02**: Lembar Tugas Praktik Demonstrasi / Ujian Praktik.
- **FR.IA.03**: Pertanyaan Lisan / Wawancara Verifikasi Portofolio.

---

## BEDAH MENDALAM 9 UNIT KOMPETENSI

---

### UNIT 1: Menggunakan Spesifikasi Program (J.620100.009.01)

#### A. Konsep Teori
Unit ini menguji kemampuan Anda membaca, menganalisis, dan mengeksekusi dokumen perancangan sistem menjadi kode program nyata.

1. **Analisis Kebutuhan (Software Requirements)**:
   - **Kebutuhan Fungsional**: Proses apa saja yang dilakukan sistem (Contoh: Form login autentikasi, input data barang, hitung total harga dan diskon, cetak laporan bulanan).
   - **Kebutuhan Non-Fungsional**: Batasan sistem (Contoh: Sistem responsif di HP & PC, waktu respon < 2 detik, password dienkripsi dengan Bcrypt/Argon2).
2. **Model IPO (Input - Process - Output)**:
   - **Input**: Data dari pengguna (Form HTML, parameter URL, file upload).
   - **Process**: Validasi, kalkulasi aritmatika, manipulasi string, query database.
   - **Output**: Tampilan tabel, pesan flash alert, data JSON, file PDF cetak.
3. **Membaca Diagram**:
   - **Flowchart**: Simbol oval (*Start/End*), jajar genjang (*Input/Output*), persegi panjang (*Process*), belah ketupat (*Decision/Kondisi*).
   - **Entity Relationship Diagram (ERD)**: Entitas, atribut, primary key, foreign key, serta kardinalitas relasi (1:1, 1:N, N:M).

#### B. Praktik di Proyek Ujikom
- Saat membaca lembar soal ujian, buatlah daftar fitur checklist (To-Do List).
- Pastikan semua *field* input yang diminta di lembar soal memiliki tipe data dan validasi yang cocok (contoh: harga harus angka positif, email harus berformat valid).

---

### UNIT 2: Menulis Kode Sesuai Guidelines dan Best Practices (J.620100.016.01)

#### A. Konsep Teori
Unit ini memastikan kode yang Anda tulis standar industri, mudah dirawat (*maintainable*), dan mudah dibaca oleh programmer lain.

1. **Coding Standard (PSR-1 & PSR-12 untuk PHP)**:
   - Tag PHP dibuka dengan `<?php` (jangan gunakan short tag `<?`).
   - File PHP harus diakhiri dengan baris kosong (*single empty line*), tanpa tag penutup `?>` jika file tersebut hanya berisi kode PHP murni (mencegah isu whitespace header).
   - Indentasi 4 spasi (bukan tab acak).
2. **Konvensi Penamaan (Naming Conventions)**:
   - **Variabel & Properti**: `camelCase` (Contoh: `$namaLengkap`, `$totalHargaBarang`, `$isLoggedIn`).
   - **Fungsi & Method**: `camelCase` dan diawali kata kerja (Contoh: `simpanData()`, `hitungPajak()`, `getUserById()`).
   - **Nama Class**: `PascalCase` / `StudlyCaps` berupa kata benda (Contoh: `KoneksiDatabase`, `ProdukController`, `LaporanTransaksi`).
   - **Konstanta**: `UPPER_SNAKE_CASE` (Contoh: `DB_NAME`, `STATUS_AKTIF`, `PAJAK_PPN`).
3. **Prinsip Desain Bersih (Clean Code)**:
   - **DRY (Don't Repeat Yourself)**: Jangan copy-paste logika query database di setiap halaman. Buat class atau helper function terpusat.
   - **KISS (Keep It Simple, Stupid)**: Jangan membuat kode berbelit-belit jika bisa diselesaikan dengan fungsi standar yang ringkas.
   - **Single Responsibility Principle (SRP)**: Satu fungsi hanya boleh melakukan satu tugas spesifik.

#### B. Contoh Kode Sesuai Best Practice (PHP):

```php
<?php
/**
 * Class TransaksiHelper
 * Menerapkan prinsip PSR-12, penamaan camelCase, dan Clean Code
 */
class TransaksiHelper
{
    public const PERSENTASE_PPN = 0.11; // 11% PPN

    /**
     * Menghitung total akhir transaksi beserta potongan diskon dan PPN
     *
     * @param float $subtotal
     * @param float $persenDiskon (Rentang 0 - 100)
     * @return array
     */
    public function hitungRincianPembayaran(float $subtotal, float $persenDiskon = 0): array
    {
        $nilaiDiskon = $subtotal * ($persenDiskon / 100);
        $setelahDiskon = $subtotal - $nilaiDiskon;
        $nilaiPpn = $setelahDiskon * self::PERSENTASE_PPN;
        $totalAkhir = $setelahDiskon + $nilaiPpn;

        return [
            'subtotal'       => $subtotal,
            'diskon'         => $nilaiDiskon,
            'setelah_diskon' => $setelahDiskon,
            'ppn'            => $nilaiPpn,
            'total_akhir'    => $totalAkhir
        ];
    }
}
```

---

### UNIT 3: Mengimplementasikan Pemrograman Terstruktur (J.620100.017.02)

#### A. Konsep Teori
Pemrograman terstruktur berfokus pada aliran eksekusi instruksi secara sekuensial, percabangan logis, perulangan, dan pemecahan masalah ke modul/fungsi terpisah.

1. **Struktur Kontrol**:
   - **Sekuensial**: Eksekusi baris demi baris dari atas ke bawah.
   - **Kondisional (Branching)**: `if-elseif-else`, `switch-case`, `match` (PHP 8+), ternary operator.
   - **Iterasi (Looping)**: `for`, `while`, `do-while`, `foreach`.
2. **Modularitas (Function)**:
   - Parameter formal vs Argumen aktual.
   - Nilai kembalian (*return type*).
   - Scope Variabel: Local scope vs Global scope.
3. **Sanitasi & Validasi Input**:
   - Sanitasi input form pengguna untuk mencegah XSS (*Cross-Site Scripting*) dengan `htmlspecialchars()`.

#### B. Contoh Kode Pemrograman Terstruktur (Fungsi Helper Terpusat):

```php
<?php
// File: helpers/utility.php

/**
 * Format angka ke format mata uang Rupiah
 */
function formatRupiah(float $angka): string
{
    return "Rp " . number_format($angka, 0, ',', '.');
}

/**
 * Sanitasi string input dari user untuk mencegah serangan XSS
 */
function bersihkanInput(string $data): string
{
    $data = trim($data);
    $data = stripslashes($data);
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

/**
 * Logika evaluasi status kelayakan transaksi / grade diskon
 */
function tentukanTingkatDiskon(float $totalBelanja, bool $isMember): float
{
    if (!$isMember) {
        return ($totalBelanja >= 500000) ? 5.0 : 0.0;
    }

    // Member aktif
    if ($totalBelanja >= 1000000) {
        return 15.0;
    } elseif ($totalBelanja >= 500000) {
        return 10.0;
    } else {
        return 5.0;
    }
}
```

---

### UNIT 4: Mengimplementasikan Pemrograman Berorientasi Objek / OOP (J.620100.018.02)

#### A. Konsep Teori
Asesor **paling sering** menguji bagian ini pada saat wawancara!

1. **Komponen Inti**:
   - **Class**: Cetak biru / blueprint (contoh: `Produk`).
   - **Object**: Wujud nyata instance dari class (contoh: `$laptop = new Produk();`).
   - **Property**: Variabel yang dimiliki oleh class (contoh: `$nama`, `$harga`).
   - **Method**: Fungsi / tingkah laku yang dimiliki oleh class (contoh: `simpan()`, `hitungStok()`).
   - **Constructor (`__construct`)**: Method khusus yang otomatis berjalan saat objek pertama kali dibuat.
2. **4 Pilar Utama OOP**:
   - **Encapsulation (Pembungkusan Data)**: Melindungi data internal dari akses liar luar dengan visibilitas:
     - `public`: Bebas diakses dari mana pun.
     - `protected`: Hanya bisa diakses dalam class itu sendiri dan class turunannya (*child class*).
     - `private`: Hanya bisa diakses di dalam class itu sendiri.
     - Sediakan method **Getter** (untuk membaca) dan **Setter** (untuk mengubah dengan validasi).
   - **Inheritance (Pewarisan)**: Mewariskan atribut dan method dari class induk (*parent*) ke class anak (*child*) menggunakan kata kunci `extends`.
   - **Polymorphism (Banyak Bentuk)**: Kemampuan method turunan memiliki implementasi berbeda melalui *Method Overriding*.
   - **Abstraction**: Menyembunyikan detail implementasi menggunakan `interface` atau `abstract class`.

#### B. Contoh Kode Nyata OOP Lengkap:

```php
<?php
// File: classes/Model.php
// Konsep Abstraksi: Parent Class
abstract class Model
{
    protected ?PDO $db = null;

    public function __construct(PDO $koneksiDatabase)
    {
        $this->db = $koneksiDatabase;
    }

    // Abstract method: Wajib diimplementasikan oleh setiap child class
    abstract public function ambilSemua(): array;
    abstract public function ambilBerdasarkanId(int $id): ?array;
}

// Konsep Inheritance: Produk mewarisi Model
class Produk extends Model
{
    // Konsep Encapsulation: Property private
    private string $kode;
    private string $nama;
    private float $harga;
    private int $stok;

    // Getter dan Setter
    public function setKode(string $kode): void
    {
        $this->kode = strtoupper(trim($kode));
    }

    public function getKode(): string
    {
        return $this->kode;
    }

    public function setHarga(float $harga): void
    {
        if ($harga < 0) {
            throw new InvalidArgumentException("Harga tidak boleh bernilai negatif!");
        }
        $this->harga = $harga;
    }

    public function getHarga(): float
    {
        return $this->harga;
    }

    // Implementasi method abstract (Polymorphism)
    public function ambilSemua(): array
    {
        $stmt = $this->db->query("SELECT * FROM produk ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function ambilBerdasarkanId(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM produk WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ?: null;
    }

    public function simpan(string $kode, string $nama, float $harga, int $stok): bool
    {
        $sql = "INSERT INTO produk (kode_produk, nama_produk, harga, stok) 
                VALUES (:kode, :nama, :harga, :stok)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':kode'  => $kode,
            ':nama'  => $nama,
            ':harga' => $harga,
            ':stok'  => $stok
        ]);
    }
}
```

---

### UNIT 5: Menggunakan Library atau Komponen Pre-existing (J.620100.019.02)

#### A. Konsep Teori
Menilai efisiensi programmer dalam memanfaatkan pustaka kode pihak ketiga (*third-party libraries*) agar tidak perlu membuat ulang hal-hal standar dari nol (*reinventing the wheel*).

1. **Jenis Library**:
   - **Frontend UI Framework**: **Bootstrap 5** (Grid system, modal dialog, formulir rapi, navigasi responsif).
   - **Tabel Interaktif**: **DataTables (jQuery plugin)** (Pencarian instan, sorting per kolom, pagination otomatis tanpa reload query).
   - **Popup Interaktif**: **SweetAlert2** (Modal konfirmasi hapus data yang cantik menggantikan `confirm()` browser).
   - **Icon**: **FontAwesome 6** atau **Bootstrap Icons**.
   - **Laporan Cetak**: **FPDF** / **Dompdf** (PHP library untuk render cetak laporan ke format `.pdf`).
   - **Grafik / Chart**: **Chart.js** (Menampilkan statistik penjualan/stok barang).
2. **Metode Pemasangan**:
   - **Via CDN (Content Delivery Network)**: Praktis jika laptop pengujian tersambung internet.
   - **Via Local File / Composer Vendor**: Wajib disiapkan jika ruangan ujian *offline* tanpa internet. Simpan file `.css` dan `.js` di dalam folder `assets/`.

#### B. Contoh Integrasi Library di View (`views/header.php` & `views/footer.php`):

```html
<!-- Bagian <head> : Memuat CSS Library -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<!-- Bagian Konten HTML -->
<table id="tabelData" class="table table-striped table-bordered">
  <thead>
    <tr>
      <th>No</th>
      <th>Kode</th>
      <th>Nama Barang</th>
      <th>Harga</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <!-- Loop data disini -->
  </tbody>
</table>

<!-- Bagian Sebelum Tutup </body> : Script JS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // 1. Inisialisasi DataTables
    $('#tabelData').DataTable({
        language: {
            search: "Cari data:",
            lengthMenu: "Tampilkan _MENU_ data per halaman",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data"
        }
    });

    // 2. SweetAlert2 untuk Konfirmasi Hapus Data
    $(document).on('click', '.btn-hapus', function(e) {
        e.preventDefault();
        const urlTarget = $(this).attr('href');

        Swal.fire({
            title: 'Apakah Anda Yakin?',
            text: "Data yang dihapus tidak dapat dipulihkan kembali!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus Data!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = urlTarget;
            }
        });
    });
});
</script>
```

---

### UNIT 6: Menerapkan Akses Basis Data (J.620100.021.02)

#### A. Konsep Teori
Akses database merupakan nyawa dari aplikasi. Asesor akan memeriksa:
1. **Perancangan Relasi Antar Tabel**:
   - Minimal 2 atau 3 tabel yang saling berelasi dengan Foreign Key (contoh: `kategori` 1 $\to$ N `produk`, atau `transaksi` 1 $\to$ N `detail_transaksi`).
2. **Kueri SQL Esensial**:
   - **CREATE TABLE** beserta tipe data dan constraint.
   - **INSERT, SELECT, UPDATE, DELETE (CRUD)**.
   - **JOIN Table** (`INNER JOIN` atau `LEFT JOIN`) untuk menggabungkan data dari dua tabel.
3. **Keamanan Database (Kritikal!)**:
   - **SQL Injection**: Serangan manipulasi query database akibat input pengguna ditempel langsung dengan string SQL (`SELECT * FROM user WHERE username = '$user'`).
   - **Solusi Wajib**: Selalu gunakan **PDO Prepared Statements** dengan *Named Parameter* atau tanda tanya `?`. Jangan gunakan `mysql_*` (sudah usang) dan hindari raw query string concatenation.

#### B. Contoh Kode Koneksi Database Singleton PDO & Query Aman:

```php
<?php
// File: config/Database.php

class Database
{
    private static ?PDO $instance = null;

    private const DB_HOST = '127.0.0.1';
    private const DB_PORT = '3306';
    private const DB_NAME = 'db_ujikom';
    private const DB_USER = 'root';
    private const DB_PASS = '';

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $dsn = "mysql:host=" . self::DB_HOST . ";port=" . self::DB_PORT . ";dbname=" . self::DB_NAME . ";charset=utf8mb4";
            
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lempar Exception jika ada error SQL
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Kembalikan array asosiatif
                PDO::ATTR_EMULATE_PREPARES   => false,                  // Gunakan prepared statement native MySQL
            ];

            try {
                self::$instance = new PDO($dsn, self::DB_USER, self::DB_PASS, $options);
            } catch (PDOException $e) {
                // Di level produksi simpan ke log, tampilkan pesan aman ke user
                die("Koneksi ke Basis Data Gagal: " . $e->getMessage());
            }
        }

        return self::$instance;
    }
}
```

#### C. Contoh Query CRUD Berelasi & Aman:

```php
<?php
$db = Database::getConnection();

// 1. SELECT dengan JOIN (Menampilkan produk beserta nama kategorinya)
$querySelect = "
    SELECT p.id, p.kode_produk, p.nama_produk, p.harga, p.stok, k.nama_kategori
    FROM produk p
    INNER JOIN kategori k ON p.kategori_id = k.id
    WHERE p.stok > :min_stok
    ORDER BY p.id DESC
";
$stmt = $db->prepare($querySelect);
$stmt->execute([':min_stok' => 0]);
$daftarProduk = $stmt->fetchAll();

// 2. INSERT dengan Prepared Statement
$queryInsert = "INSERT INTO produk (kategori_id, kode_produk, nama_produk, harga, stok) 
                VALUES (:kategori_id, :kode, :nama, :harga, :stok)";
$stmtInsert = $db->prepare($queryInsert);
$stmtInsert->execute([
    ':kategori_id' => 1,
    ':kode'        => 'BRG001',
    ':nama'        => 'Mouse Wireless Logitech',
    ':harga'       => 150000,
    ':stok'        => 25
]);

// 3. UPDATE dengan Prepared Statement
$queryUpdate = "UPDATE produk SET harga = :harga, stok = :stok WHERE id = :id";
$stmtUpdate = $db->prepare($queryUpdate);
$stmtUpdate->execute([
    ':harga' => 145000,
    ':stok'  => 30,
    ':id'    => 1
]);

// 4. DELETE dengan Prepared Statement
$queryDelete = "DELETE FROM produk WHERE id = :id";
$stmtDelete = $db->prepare($queryDelete);
$stmtDelete->execute([':id' => 1]);
```

---

### UNIT 7: Membuat Dokumen Kode Program (J.620100.023.02)

#### A. Konsep Teori
Dokumentasi kode membuktikan bahwa pengembang tidak hanya bisa menulis kode, tetapi juga mampu membuat kode tersebut dipelihara (*maintainable*) oleh orang lain.

1. **Dokumentasi In-Code (PHPDoc / JSDoc)**:
   - Menjelaskan fungsi, parameter yang diterima (`@param`), tipe data yang dikembalikan (`@return`), serta kemungkinan error yang dilempar (`@throws`).
2. **Dokumen Arsitektur Proyek (`README.md`)**:
   - Informasi instalasi langkah demi langkah.
   - Konfigurasi file `.env` atau `config.php`.
   - Cara import database SQL.
   - Kredensial default untuk pengujian (*username* dan *password* admin).

#### B. Standar Anotasi PHPDoc Resmi:

```php
<?php
/**
 * Class Autentikasi
 * Mengelola proses verifikasi kredensial pengguna dan sesi login
 *
 * @package App\Auth
 * @author  Nama Peserta Ujikom <peserta@email.com>
 * @version 1.0.0
 */
class Autentikasi
{
    /**
     * Objek koneksi PDO database
     * @var PDO
     */
    private PDO $db;

    /**
     * Inisialisasi dependensi database
     * @param PDO $db
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Memvalidasi login user berdasarkan username dan password
     *
     * @param string $username Username atau alamat email
     * @param string $password Password plain text yang diinput
     * @throws Exception Jika input kosong
     * @return array|null Mengembalikan data user jika valid, null jika gagal
     */
    public function login(string $username, string $password): ?array
    {
        if (empty($username) || empty($password)) {
            throw new Exception("Username dan password tidak boleh kosong!");
        }

        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :u LIMIT 1");
        $stmt->execute([':u' => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return null;
    }
}
```

---

### UNIT 8: Melakukan Debugging (J.620100.025.02)

#### A. Konsep Teori
Kemampuan mendiagnosis masalah, membaca pesan galat (*stack trace*), dan menyelesaikan masalah secara sistematis.

1. **3 Kategori Galat (Error)**:
   - **Syntax Error (Galat Sintaks)**: Kurang titik koma (`;`), kurung kurawal tidak tertutup, salah ketik kata kunci (*typo*). Ditemukan saat kompilasi/parsing.
   - **Runtime Error (Galat Saat Jalan)**: File tidak ditemukan (`require 'tidak_ada.php'`), memanggil fungsi/method yang belum dibuat, membagi angka dengan nol (*division by zero*), gagal konek database.
   - **Logical Error (Galat Logika)**: Program jalan tanpa error, tetapi hasil perhitungan/logikanya keliru (contoh: rumus diskon tertulis `subtotal + diskon` bukannya `subtotal - diskon`).
2. **Teknik Penelusuran**:
   - **PHP Dump**: `var_dump($variabel); die();` atau `print_r($array); exit();`.
   - **Browser DevTools**:
     - Buka tombol `F12` $\to$ Tab **Console** untuk melihat syntax error JavaScript.
     - Tab **Network** $\to$ Klik request $\to$ Tab **Response** / **Preview** untuk memeriksa response request AJAX atau pesan error 500 dari PHP.
   - **Error Handling Try-Catch**:
     - Tangkap galat menggunakan blok `try { ... } catch (Throwable $e) { ... }` agar aplikasi tidak langsung *crash* menampilkan layar putih (*white screen of death*).

#### B. Contoh Penanganan Error Transaksional yang Elegan:

```php
<?php
// File: controllers/proses_transaksi.php

$db = Database::getConnection();

try {
    // 1. Aktifkan Transaction (ACID)
    $db->beginTransaction();

    // Kurangi stok barang
    $stmtStok = $db->prepare("UPDATE produk SET stok = stok - :qty WHERE id = :id AND stok >= :qty");
    $stmtStok->execute([':qty' => 2, ':id' => 5]);

    if ($stmtStok->rowCount() === 0) {
        throw new Exception("Stok barang tidak mencukupi untuk melakukan transaksi!");
    }

    // Catat header transaksi
    $stmtTrx = $db->prepare("INSERT INTO transaksi (kode_transaksi, total) VALUES (:kode, :total)");
    $stmtTrx->execute([':kode' => 'TRX-' . time(), ':total' => 300000]);

    // 2. Jika semua query sukses, commit ke database
    $db->commit();

    echo "Transaksi berhasil disimpan!";
} catch (Exception $e) {
    // 3. Jika ada satupun yang gagal, batalkan semua perubahan!
    $db->rollBack();

    // Catat pesan error teknis ke file log server
    error_log("[ERROR TRANSAKSI]: " . $e->getMessage());

    // Tampilkan pesan ramah ke pengguna
    echo "Terjadi kesalahan saat memproses transaksi: " . htmlspecialchars($e->getMessage());
}
```

---

### UNIT 9: Melaksanakan Pengujian Unit Program (J.620100.033.02)

#### A. Konsep Teori
Unit testing menguji unit terkecil kode (fungsi atau method) secara terisolasi untuk memastikan logika bekerja sesuai ekspektasi.

1. **Kriteria Pengujian**:
   - **Positive Testing (Happy Path)**: Uji dengan data input normal yang valid.
   - **Negative Testing**: Uji dengan data invalid (input kosong, format email salah, password terlalu pendek).
   - **Boundary / Edge Case**: Uji nilai ambang batas (stok 0, harga 0, angka negatif, bilangan desimal panjang).
2. **Struktur Pengujian (AAA Pattern)**:
   - **Arrange**: Menyiapkan variabel input dan objek.
   - **Act**: Menjalankan fungsi/method yang diuji.
   - **Assert**: Membandingkan hasil aktual (*actual result*) dengan nilai yang diharapkan (*expected result*).

#### B. Contoh Script Pengujian Unit Mandiri (Tanpa Composer / Native Runner):
*Sangat berguna jika di ruangan ujian tidak ada koneksi internet untuk menginstal PHPUnit!*

```php
<?php
// File: tests/TestKalkulatorDiskon.php

require_once __DIR__ . '/../helpers/utility.php';

class SimpleTestRunner
{
    private int $passCount = 0;
    private int $failCount = 0;

    public function assertEqual($expected, $actual, string $testName): void
    {
        if ($expected === $actual) {
            echo "[\033[32mPASS\033[0m] $testName\n";
            $this->passCount++;
        } else {
            echo "[\033[31mFAIL\033[0m] $testName | Ekspektasi: " . json_encode($expected) . ", Hasil Aktual: " . json_encode($actual) . "\n";
            $this->failCount++;
        }
    }

    public function tampilkanHasil(): void
    {
        echo "\n=====================================\n";
        echo "Total Pengujian: " . ($this->passCount + $this->failCount) . "\n";
        echo "Lolos (PASS)   : " . $this->passCount . "\n";
        echo "Gagal (FAIL)   : " . $this->failCount . "\n";
        echo "=====================================\n";
    }
}

// Menjalankan Pengujian
$test = new SimpleTestRunner();

// Kasus 1: Member belanja 1.500.000 (Harus dapat diskon 15%)
$test->assertEqual(15.0, tentukanTingkatDiskon(1500000, true), "Uji Diskon Member Belanja > 1 Juta");

// Kasus 2: Member belanja 600.000 (Harus dapat diskon 10%)
$test->assertEqual(10.0, tentukanTingkatDiskon(600000, true), "Uji Diskon Member Belanja 600 Ribu");

// Kasus 3: Non-member belanja 200.000 (Tidak dapat diskon / 0%)
$test->assertEqual(0.0, tentukanTingkatDiskon(200000, false), "Uji Non-Member Belanja < 500 Ribu");

// Kasus 4: Format Rupiah
$test->assertEqual("Rp 50.000", formatRupiah(50000), "Uji Format Mata Uang Rupiah");

$test->tampilkanHasil();
```

---

## ARSITEKTUR PROYEK REFERENSI

Gunakan struktur folder yang memisahkan logika (MVC sederhana) agar saat asesor melihat file proyek Anda, mereka langsung melihat penerapan best practices:

```text
UJIKOMPEMROGRAMAN/
│
├── assets/                       # Aset Frontend Pihak Ketiga (Unit 5)
│   ├── css/
│   │   ├── bootstrap.min.css
│   │   ├── dataTables.bootstrap5.min.css
│   │   └── style.css            # Custom CSS
│   └── js/
│       ├── bootstrap.bundle.min.js
│       ├── jquery-3.7.1.min.js
│       ├── jquery.dataTables.min.js
│       ├── sweetalert2.all.min.js
│       └── app.js               # Inisialisasi library JS
│
├── config/
│   └── Database.php             # Koneksi Database Singleton PDO (Unit 6)
│
├── classes/                      # Implementasi OOP (Unit 4)
│   ├── Model.php                # Abstract Parent Model
│   ├── Produk.php               # Class Entitas Produk
│   ├── Kategori.php             # Class Entitas Kategori
│   └── User.php                 # Class Entitas Autentikasi
│
├── helpers/                      # Pemrograman Terstruktur (Unit 3)
│   └── utility.php              # Format rupiah, sanitasi input, perhitungan
│
├── views/                        # Tampilan HTML Antarmuka (Unit 1, 2, 5)
│   ├── layouts/
│   │   ├── header.php
│   │   ├── navbar.php
│   │   └── footer.php
│   ├── produk/
│   │   ├── index.php            # Tampilan Data Produk (DataTables)
│   │   ├── create.php           # Form Tambah Produk
│   │   └── edit.php             # Form Edit Produk
│   └── login.php
│
├── controllers/                  # Pemrosesan Alur Bisnis (Unit 1, 3, 8)
│   ├── login_proses.php
│   ├── logout.php
│   ├── produk_simpan.php
│   ├── produk_update.php
│   └── produk_hapus.php
│
├── database/                     # Skrip Basis Data (Unit 6)
│   └── schema_ujikom.sql        # File DDL & Sample Data
│
├── tests/                        # Pengujian Unit (Unit 9)
│   ├── TestKalkulatorDiskon.php # Skrip Unit Test
│   └── Laporan_Uji_Unit.pdf     # Tabel hasil uji
│
├── README.md                     # Dokumentasi Proyek (Unit 7)
└── PANDUAN_MATERI_UJIKOM.md      # Modul Master Ini
```

---

## TEMPLATE PORTOFOLIO DOKUMEN UJIKOM

Cetak atau simpan template di bawah ini ke dalam proyek Anda sebagai bukti portofolio untuk asesor.

### Template 1: Dokumen Pengujian Unit (Test Case Report)

| ID Kasus Uji | Nama Modul / Fungsi | Skenario Masukan (Input Data) | Hasil yang Diharapkan (Expected) | Hasil Aktual (Actual) | Status |
|---|---|---|---|---|---|
| **TC-AUTH-01** | Autentikasi Login | Username: `admin`, Password: `password123` (Valid) | Berhasil login, redirect ke halaman dashboard, simpan user di session | Redirect ke `/dashboard.php` | **PASS** |
| **TC-AUTH-02** | Autentikasi Login | Username: `admin`, Password: `salah` (Invalid) | Muncul pesan error "Password tidak cocok!", tetap di halaman login | Pesan galat muncul | **PASS** |
| **TC-AUTH-03** | Autentikasi Login | Username: ``, Password: `` (Kosong) | Validasi form HTML5 / PHP menolak input kosong | Muncul notifikasi "Wajib diisi" | **PASS** |
| **TC-CRUD-01** | Tambah Produk | Kode: `BRG-01`, Nama: `Buku`, Harga: `5000`, Stok: `10` | Data tersimpan di tabel `produk`, redirect dengan alert sukses | Data muncul di tabel | **PASS** |
| **TC-CRUD-02** | Tambah Produk | Kode: `BRG-01` (Duplikat) | Database menolak (Unique constraint), pesan "Kode sudah ada" | Alert error duplikasi | **PASS** |
| **TC-CALC-01** | Perhitungan Diskon | Subtotal: `1.000.000`, Status Member: `True` | Diskon 10% = `100.000`, Total Bayar = `900.000` | Nilai bayar `900.000` | **PASS** |
| **TC-SEC-01** | Keamanan SQL Injection | Input Username: `' OR '1'='1` | Query PDO menolak injeksi, sistem tidak tertembus | Login ditolak | **PASS** |
| **TC-SEC-02** | Keamanan XSS | Input Nama Produk: `<script>alert(1)</script>` | Script di-escape menjadi `&lt;script&gt;`, tidak mengeksekusi popup JS | Teks tampil biasa | **PASS** |

---

### Template 2: README.md Standar Sertifikasi

Salin format berikut ke file `README.md` aplikasi Anda:

```markdown
# Sistem Pengelolaan Inventaris dan Penjualan Barang (Ujikom)

Aplikasi berbasis web untuk pencatatan inventaris barang dan transaksi penjualan, dibangun untuk memenuhi standar kompetensi SKKNI skema Pemrograman Web (Junior Web Developer).

## 1. Prasyarat Sistem (Prerequisites)
- Web Server: Apache (XAMPP / Laragon)
- Bahasa Pemrograman: PHP >= 8.0 (Ekstensi PDO dan MySQL aktif)
- Database Server: MySQL / MariaDB >= 10.4
- Web Browser: Google Chrome / Microsoft Edge versi terbaru

## 2. Panduan Instalasi
1. Pindahkan folder proyek ini ke direktori web root:
   - XAMPP: `C:/xampp/htdocs/UJIKOMPEMROGRAMAN`
   - Laragon: `C:/laragon/www/UJIKOMPEMROGRAMAN`
2. Buka phpMyAdmin di browser (`http://localhost/phpmyadmin`).
3. Buat database baru dengan nama: `db_ujikom`.
4. Import file database yang berada pada path: `database/schema_ujikom.sql`.
5. Sesuaikan konfigurasi database jika username/password MySQL Anda berbeda di file `config/Database.php`.
6. Akses aplikasi melalui URL: `http://localhost/UJIKOMPEMROGRAMAN`.

## 3. Akun Pengguna Uji (Default Credentials)
- **Role Administrator**:
  - Username: `admin`
  - Password: `password123`
- **Role Kasir**:
  - Username: `kasir`
  - Password: `password123`

## 4. Fitur Utama Aplikasi
- [x] Autentikasi Login dan Logout berbasis Session yang aman.
- [x] Manajemen Master Data Produk (CRUD: Create, Read, Update, Delete).
- [x] Relasi Data Antara Tabel Produk dan Tabel Kategori Barang.
- [x] Tampilan interaktif dengan DataTables (Pencarian & Pagination instan).
- [x] Konfirmasi penghapusan data interaktif dengan SweetAlert2.
- [x] Pencegahan keamanan dari serangan SQL Injection menggunakan PDO Prepared Statement.
- [x] Sanitasi input form terhadap serangan Cross-Site Scripting (XSS).
```

---

### Template 3: Kamus Data Database

**Tabel: `kategori`**
| Nama Kolom | Tipe Data | Keterangan | Constraint |
|---|---|---|---|
| `id` | `INT(11)` | ID unik kategori | Primary Key, Auto Increment |
| `nama_kategori` | `VARCHAR(100)` | Nama kategori barang | Not Null |
| `keterangan` | `TEXT` | Deskripsi singkat | Nullable |

**Tabel: `produk`**
| Nama Kolom | Tipe Data | Keterangan | Constraint |
|---|---|---|---|
| `id` | `INT(11)` | ID unik produk | Primary Key, Auto Increment |
| `kategori_id` | `INT(11)` | Relasi ke tabel kategori | Foreign Key (`kategori.id`) |
| `kode_produk` | `VARCHAR(20)` | Kode barang unik (SKU) | Unique, Not Null |
| `nama_produk` | `VARCHAR(150)` | Nama lengkap barang | Not Null |
| `harga` | `DECIMAL(12,2)`| Harga satuan barang | Not Null, Default 0 |
| `stok` | `INT(11)` | Jumlah stok tersedia | Not Null, Default 0 |
| `created_at` | `TIMESTAMP` | Waktu data dibuat | Default CURRENT_TIMESTAMP |

---

## BANK TANYA JAWAB WAWANCARA ASESOR

Gunakan contekan jawaban ini saat asesmen lisan untuk membuktikan bahwa Anda sangat kompeten:

#### Q1: "Mengapa Anda menggunakan PDO dibanding MySQLi atau query biasa?"
> **Jawaban Mantap:**
> *"Saya menggunakan PDO (PHP Data Objects) karena memiliki tiga keunggulan utama: pertama, mendukung Prepared Statements secara native untuk menutup celah SQL Injection. Kedua, PDO bersifat database-agnostic, sehingga jika di masa depan sistem berganti dari MySQL ke PostgreSQL atau Oracle, kodenya jauh lebih mudah diadaptasi tanpa menulis ulang seluruh driver. Ketiga, PDO mendukung penanganan error transaksional berbasis Exception (`PDOException`), memudahkan debugging dan mekanisme rollback data."*

#### Q2: "Bagaimana sistem Anda mencegah serangan SQL Injection dan XSS?"
> **Jawaban Mantap:**
> *"Untuk mencegah **SQL Injection**, saya menggunakan parameterized query / prepared statement, di mana parameter data dipisahkan secara tegas dari struktur logika SQL sehingga karakter spesial seperti tanda kutip tidak akan dieksekusi sebagai perintah SQL. Untuk mencegah **XSS (Cross-Site Scripting)**, saya menggunakan fungsi sanitasi `htmlspecialchars()` dengan flag `ENT_QUOTES` pada setiap output yang dicetak ke browser, sehingga tag berbahaya seperti `<script>` akan diubah menjadi entitas HTML aman."*

#### Q3: "Di mana Anda menerapkan 4 pilar OOP pada kode ini?"
> **Jawaban Mantap:**
> *(Sambil membuka file `classes/Model.php` dan `classes/Produk.php`)*
> 1. *"**Encapsulation**: Properti `$kode`, `$nama`, dan `$harga` saya set `private` agar tidak dimodifikasi secara sembarangan dari luar, dan hanya bisa diakses via Getter & Setter yang memiliki validasi."*
> 2. *"**Inheritance**: Class `Produk` mewarisi properti koneksi dan method dari class induk `Model` menggunakan `extends`."*
> 3. *"**Polymorphism**: Method `ambilSemua()` di-override oleh class `Produk` untuk mengambil data spesifik dari tabel produk."*
> 4. *"**Abstraction**: Class induk `Model` saya deklarasikan sebagai `abstract class` yang mewajibkan seluruh child class mengimplementasikan method dasarnya."*

#### Q4: "Apa fungsi method `__construct()`?"
> **Jawaban Mantap:**
> *"Constructor adalah method ajaib (*magic method*) yang otomatis dieksekusi pertama kali ketika sebuah objek diinstansiasi dengan kata kunci `new`. Pada kode saya, constructor digunakan untuk *Dependency Injection*, yaitu menyuntikkan objek koneksi database PDO ke dalam class model."*

#### Q5: "Apa bedanya syntax error, runtime error, dan logical error?"
> **Jawaban Mantap:**
> *"**Syntax error** terjadi saat penulisan kode melanggar tata bahasa pemrograman, seperti kurang titik koma (`;`), dan program langsung gagal dieksekusi. **Runtime error** terjadi saat kode sintaksnya benar tetapi gagal saat dijalankan, seperti file tidak ditemukan atau koneksi database putus. Sedangkan **Logical error** adalah kondisi di mana program berjalan lancar tanpa pesan error, tetapi outputnya salah, misalnya kesalahan rumus perhitungan diskon."*

#### Q6: "Bagaimana cara Anda menguji bahwa aplikasi ini bekerja dengan benar?"
> **Jawaban Mantap:**
> *"Saya melakukan **Unit Testing** pada fungsi-fungsi kalkulasi dan validasi menggunakan skrip uji otomatis untuk membandingkan Expected Result dengan Actual Result pada skenario normal maupun boundary (ekstrem). Selain itu, saya membuat dokumen **Test Case Report** yang mencatat status PASS untuk setiap fitur sesuai spesifikasi soal."*

---

## CHEATSHEET MENANGANI ERROR POPULER SAAT UJIKOM

Jika saat demo di depan asesor tiba-tiba muncul error, jangan panik! Berikut solusinya:

| Pesan Error | Penyebab | Solusi Cepat |
|---|---|---|
| `Fatal error: Uncaught PDOException: SQLSTATE[HY000] [1049] Unknown database` | Nama database di MySQL belum dibuat atau salah ketik di `config.php`. | Buka phpMyAdmin, buat database dengan nama yang persis sama. |
| `Fatal error: Uncaught PDOException: SQLSTATE[23000]: Integrity constraint violation` | Menghapus data induk yang masih memiliki anak di tabel relasi (Foreign Key) atau duplikasi Primary Key. | Hapus data di tabel detail/anak terlebih dahulu, atau periksa ID yang dimasukkan. |
| `Warning: Cannot modify header information - headers already sent by...` | Ada output HTML, spasi kosong, atau baris baru sebelum fungsi `header('Location: ...')` atau `session_start()`. | Hapus spasi/baris kosong sebelum tag `<?php`. Pastikan tidak ada `echo` sebelum redirect. Hapus tag penutup `?>` di akhir file PHP murni. |
| `Fatal error: Call to undefined function...` | Typo penamaan fungsi atau file helper belum di-`require_once`. | Periksa ejaan nama fungsi dan pastikan file yang memuat fungsi tersebut sudah di-include di bagian paling atas. |
| `Notice: Undefined index / Undefined array key...` | Mencoba mengambil nilai `$_POST['nama']` yang form-nya belum disubmit atau atribut `name=""` di HTML tidak cocok. | Gunakan null coalescing operator: `$nama = $_POST['nama'] ?? '';` atau `isset($_POST['nama'])`. |
| DataTables tidak muncul / tabel berantakan | File jQuery belum dimuat SEBELUM file DataTables JS dimuat. | Pastikan urutan tag `<script>`: jQuery harus berada di baris paling atas sebelum DataTables dan Bootstrap. |

---

*Panduan ini siap digunakan sebagai materi belajar, contekan saat praktikum, dan bahan pertahanan saat wawancara asesmen kompetensi.*
