# PANDUAN LOKASI KODE & FITUR APLIKASI SPP
**SMAN 1 TENJO — SISTEM PEMBAYARAN SPP DIGITAL**
*Dokumen Cepat Menjawab Pertanyaan Asesor Saat Uji Kompetensi Keahlian (UJIKOM)*

---

## 1. TABEL PINTAS: LETAK KODE FITUR-FITUR PENTING

Jika Asesor meminta kamu menunjukkan kodingan tertentu, langsung buka file dan nomor baris berikut:

| Pertanyaan Asesor | Berkas (File) | Baris Kode | Fungsi / Penjelasan Singkat |
|---|---|---|---|
| **Pencegah Bayar Ganda (Anti-Double Pay)** | [`index.php`](index.php#L234-L247) | Baris 234 – 247 | Memeriksa apakah bulan & tahun sudah lunas sebelum data disimpan |
| **Pencegah Bayar Ganda di Frontend (JS)** | [`views/pembayaran.php`](views/pembayaran.php#L210-L245) | Baris 210 – 245 | Otomatis mengunci (*disable*) checkbox bulan yang sudah lunas |
| **Bayar Multi-Bulan (Beberapa Bulan Sekaligus)** | [`index.php`](index.php#L201-L270) | Baris 201 – 270 | Menerima array bulan terpilih dan menyimpannya dalam 1 transaksi ACID |
| **Kalkulasi Tunggakan & Sisa Bulan Siswa** | [`views/cek_pembayaran.php`](views/cek_pembayaran.php#L25-L63) | Baris 25 – 63 | Mencari selisih 12 bulan dengan bulan yang sudah berstatus lunas |
| **Cetak Kuitansi Formal SMAN 1 TENJO** | [`views/detail_pembayaran.php`](views/detail_pembayaran.php#L128-L260) | Baris 128 – 260 | Template kuitansi resmi 1 lembar A4 siap print langsung tanpa tab baru |
| **Fungsi JavaScript Cetak Kuitansi** | [`views/detail_pembayaran.php`](views/detail_pembayaran.php#L262-L330) | Baris 262 – 330 | Mengisi data dinamis ke template lalu memicu `window.print()` |
| **Pencegahan SQL Injection (PDO Native)** | [`config/Database.php`](config/Database.php#L27-L50) | Baris 27 – 50 | Opsi `PDO::ATTR_EMULATE_PREPARES => false` dan prepared statements |
| **Pencegahan XSS (Cross-Site Scripting)** | [`helpers/utility.php`](helpers/utility.php#L8-L24) | Baris 8 – 24 | Fungsi `bersihkanInput()` menggunakan `htmlspecialchars(..., ENT_QUOTES)` |
| **Enkripsi Kata Sandi Petugas (Bcrypt)** | [`classes/Model.php`](classes/Model.php#L79-L88) | Baris 79 – 88 | Menggunakan algoritma aman `password_hash($password, PASSWORD_BCRYPT)` |
| **Verifikasi Login Password Hash** | [`classes/Model.php`](classes/Model.php#L50-L76) | Baris 50 – 76 | Memvalidasi kata sandi dengan fungsi bawaan PHP `password_verify()` |
| **Format Mata Uang Rupiah** | [`helpers/utility.php`](helpers/utility.php#L27-L39) | Baris 27 – 39 | Fungsi `formatRupiah()` menggunakan `number_format()` standar Indonesia |
| **Pencarian Siswa (NISN / Nama)** | [`classes/Model.php`](classes/Model.php#L316-L334) | Baris 316 – 334 | Kueri SQL `WHERE s.nisn LIKE :kw1 OR s.nama LIKE :kw2` |
| **Database Transaction (ACID)** | [`index.php`](index.php#L252-L270) | Baris 252 – 270 | Menggunakan `$db->beginTransaction()` dan `$db->commit()` / `rollBack()` |
| **Pola OOP Inheritance & Abstraction** | [`classes/Model.php`](classes/Model.php#L12-L32) | Baris 12 – 32 | `abstract class Model` diwarisi oleh `PetugasModel extends Model` |
| **Proteksi Hak Akses Role (RBAC)** | [`helpers/utility.php`](helpers/utility.php#L77-L89) | Baris 77 – 89 | Fungsi `cekRole(['admin'])` menolak petugas membuka menu data petugas |

---

## 2. BEDAH KODE 7 MENU UTAMA APLIKASI

### Menu 1: Dashboard Ringkasan Kas & Siswa
- **File Tampilan**: [`views/dashboard.php`](views/dashboard.php)
- **File Pemroses Data**: [`index.php`](index.php#L344-L348)
- **File Kueri Database**: [`classes/Model.php`](classes/Model.php#L485-L499)

**Rincian Fitur & Letak Kodenya:**
1. **4 Kartu Metrik Utama** ([`views/dashboard.php#L32-L85`](views/dashboard.php#L32-L85)):
   - Total Siswa Aktif
   - Total Rombel Kelas
   - Total Transaksi Masuk
   - Total Kas SPP Masuk (hanya menjumlahkan transaksi yang berstatus Terverifikasi)
2. **Kueri Hitung Statistik** ([`classes/Model.php#L485-L499`](classes/Model.php#L485-L499)):
   - Menggunakan fungsi agregasi SQL `COUNT(*)` dan `SUM(jumlah_bayar)`.
3. **Tabel 5 Transaksi Terbaru** ([`views/dashboard.php#L88-L135`](views/dashboard.php#L88-L135)):
   - Mengambil 5 riwayat setoran paling akhir menggunakan `array_slice($semuaPembayaran, 0, 5)`.

---

### Menu 2: Data Kelas (Rombel & Jurusan)
- **File Tampilan**: [`views/kelas.php`](views/kelas.php)
- **File Pemroses Aksi Form**: [`index.php`](index.php#L106-L139)
- **File Kueri Database**: [`classes/Model.php`](classes/Model.php#L142-L212)

**Rincian Fitur & Letak Kodenya:**
1. **Daftar Tabel Kelas** ([`views/kelas.php#L30-L75`](views/kelas.php#L30-L75)):
   - Menampilkan nomor urut, nama rombel (misal: XII RPL 1), kompetensi keahlian, dan tombol aksi.
2. **Modal Tambah Kelas** ([`views/kelas.php#L77-L105`](views/kelas.php#L77-L105)):
   - Form popup untuk input nama rombel dan jurusan keahlian.
3. **Simpan Kelas Baru** ([`index.php#L107-L114`](index.php#L107-L114)):
   - Menangani `action=kelas_simpan` dengan sanitasi input.
4. **Edit Kelas** ([`index.php#L116-L125`](index.php#L116-L125)):
   - Menangani `action=kelas_update` untuk mengubah nama/jurusan kelas.
5. **Proteksi Hapus Kelas (Foreign Key Integrity)** ([`index.php#L128-L138`](index.php#L128-L138)):
   - Menangani `action=kelas_hapus`. Jika kelas masih memiliki siswa terdaftar, database otomatis menolak via Foreign Key constraint dan menampilkan flash error ramah.

---

### Menu 3: Data Siswa (Master Siswa)
- **File Tampilan**: [`views/siswa.php`](views/siswa.php)
- **File Pemroses Aksi Form**: [`index.php`](index.php#L53-L104)
- **File Kueri Database**: [`classes/Model.php`](classes/Model.php#L275-L365)

**Rincian Fitur & Letak Kodenya:**
1. **Pencarian Siswa Cepat** ([`views/siswa.php#L25-L42`](views/siswa.php#L25-L42)):
   - Form input pencarian berdasarkan NISN atau Nama Siswa.
2. **Kueri Pencarian Multi-Kolom** ([`classes/Model.php#L316-L334`](classes/Model.php#L316-L334)):
   - `SELECT ... WHERE s.nisn LIKE :kw1 OR s.nama LIKE :kw2 OR s.nis LIKE :kw3` dengan parameter unik PDO.
3. **Modal Tambah Siswa** ([`views/siswa.php#L112-L178`](views/siswa.php#L112-L178)):
   - Form input NISN (10 digit), NIS, Nama Lengkap, dropdown pilihan Kelas, dropdown penetapan SPP, no telepon, dan alamat.
4. **Simpan Siswa Baru** ([`index.php#L53-L70`](index.php#L53-L70)):
   - Menangani `action=siswa_simpan` dengan try-catch terhadap duplicate key NISN/NIS.
5. **Tombol Pintas Cek Pembayaran** ([`views/siswa.php#L88-L92`](views/siswa.php#L88-L92)):
   - Tombol ikon centang pada baris siswa untuk langsung melompat ke riwayat SPP siswa tersebut.

---

### Menu 4: Pembayaran SPP (Entri Transaksi Kasir)
- **File Tampilan**: [`views/pembayaran.php`](views/pembayaran.php)
- **File Pemroses Aksi Form**: [`index.php`](index.php#L201-L286)
- **File Kueri Database**: [`classes/Model.php`](classes/Model.php#L370-L500)

**Rincian Fitur & Letak Kodenya:**
1. **Dropdown Siswa Terhubung Tarif SPP** ([`views/pembayaran.php#L42-L62`](views/pembayaran.php#L42-L62)):
   - Menyimpan atribut HTML `data-nominal` dan `data-kelas` sehingga saat siswa dipilih, informasi tarif otomatis terbaca tanpa perlu reload halaman.
2. **Grid 12 Checkbox Bulan** ([`views/pembayaran.php#L92-L124`](views/pembayaran.php#L92-L124)):
   - Checkbox untuk bulan Januari sampai Desember.
   - Bulan yang sudah lunas otomatis dinonaktifkan (*disabled*) dan berlabel `(Lunas)`.
3. **Tombol Pintas Pilihan Bulan (JS)** ([`views/pembayaran.php#L98-L108`](views/pembayaran.php#L98-L108)):
   - `Pilih Semua Tunggakan`: Mencentang semua bulan yang belum dibayar.
   - `1 Bulan Saja`: Mencentang 1 bulan terdekat yang belum lunas.
4. **Kalkulator Nominal Otomatis (Real-time)** ([`views/pembayaran.php#L310-L336`](views/pembayaran.php#L310-L336)):
   - Fungsi `hitungTotal()` mengalikan jumlah bulan yang dicentang dengan tarif SPP per bulan siswa.
   - Mengubah teks tombol submit secara dinamis: `Bayar 2 Bulan (Rp 700.000)`.
5. **Validasi Anti-Double Payment Backend** ([`index.php#L234-L247`](index.php#L234-L247)):
   - Mengecek method `PembayaranModel::cekSudahBayar($nisn, $bulan, $tahun)`. Jika sudah ada, transaksi langsung dibatalkan demi keamanan.
6. **Eksekusi Database Transaction (ACID)** ([`index.php#L252-L270`](index.php#L252-L270)):
   - `$db->beginTransaction()` $\to$ simpan tiap bulan yang dicentang $\to$ buat kode transaksi unik $\to$ `$db->commit()`.
   - Menjamin saldo kas dan catatan bulan selalu 100% sinkron.

---

### Menu 5: Cek Tunggakan (Pengecekan Status 12 Bulan)
- **File Tampilan**: [`views/cek_pembayaran.php`](views/cek_pembayaran.php)
- **File Pemroses Data**: [`index.php`](index.php#L366-L373)

**Rincian Fitur & Letak Kodenya:**
1. **Kalkulasi Sisa Tunggakan (PHP)** ([`views/cek_pembayaran.php#L25-L63`](views/cek_pembayaran.php#L25-L63)):
   - Mencari selisih (*diff*) antara 12 nama bulan resmi dengan data bulan yang sudah pernah dibayar siswa.
   - Menghitung `total_tunggakan = count($bulanBelum) * $tarifPerBulan`.
2. **Dropdown Pilih Siswa** ([`views/cek_pembayaran.php#L68-L93`](views/cek_pembayaran.php#L68-L93)):
   - Dilengkapi fungsi `onchange="this.form.submit()"` sehingga begitu nama siswa dipilih, data status langsung muncul.
3. **Tabel Status 12 Bulan Siswa** ([`views/cek_pembayaran.php#L136-L187`](views/cek_pembayaran.php#L136-L187)):
   - Menampilkan No, Bulan, Tarif SPP, Badge Status (**Lunas** warna hijau / **Belum Bayar** warna merah), dan tombol **Bayar**.
4. **Tombol "Bayar Sekaligus ->"** ([`views/cek_pembayaran.php#L128-L133`](views/cek_pembayaran.php#L128-L133)):
   - Mengarahkan langsung ke halaman pembayaran dengan seluruh bulan tunggakan otomatis tercentang (`&bulan=Maret,April,...`).
5. **Tabel Rekap Tunggakan Seluruh Siswa** ([`views/cek_pembayaran.php#L237-L302`](views/cek_pembayaran.php#L237-L302)):
   - Memberikan visibilitas cepat kepada petugas mengenai siapa saja siswa yang masih nunggak, bulan apa saja yang belum dibayar, dan berapa total rupiah tunggakannya.

---

### Menu 6: Histori Pembayaran & Cetak Kuitansi
- **File Tampilan**: [`views/detail_pembayaran.php`](views/detail_pembayaran.php)
- **File Pemroses Data**: [`index.php`](index.php#L383-L388)
- **File Hapus Transaksi**: [`index.php`](index.php#L288-L300)

**Rincian Fitur & Letak Kodenya:**
1. **Filter Bulan & Pencarian Riwayat** ([`views/detail_pembayaran.php#L29-L53`](views/detail_pembayaran.php#L29-L53)):
   - Filter dropdown per bulan tagihan dan input pencarian nama siswa / kode transaksi.
2. **Tabel Histori Pembayaran Lengkap** ([`views/detail_pembayaran.php#L55-L125`](views/detail_pembayaran.php#L55-L125)):
   - Menampilkan Kode Transaksi unik, Tanggal Bayar, Siswa, Kelas, Periode, Jumlah Bayar, Metode, Status Terverifikasi, Petugas Penerima, dan Tombol Cetak.
3. **Template Resmi Kuitansi SMAN 1 TENJO** ([`views/detail_pembayaran.php#L128-L260`](views/detail_pembayaran.php#L128-L260)):
   - Didesain khusus pas **1 lembar A4** dengan Kop Surat Resmi Dinas Pendidikan Jawa Barat / SMAN 1 TENJO.
   - Memiliki tabel rincian biaya, teks terbilang rupiah, tanggal terbit, serta kolom tanda tangan petugas kasir (**Intra Sepriansa**) dan siswa/wali.
4. **Skrip Cetak Langsung Tanpa Tab Baru (JS)** ([`views/detail_pembayaran.php#L262-L330`](views/detail_pembayaran.php#L262-L330)):
   - Fungsi `cetakLangsung(data)` mengisi elemen kuitansi secara dinamis dan langsung memicu `window.print()`.

---

### Menu 7: Data Petugas (Pengguna Sistem — Khusus Admin)
- **File Tampilan**: [`views/petugas.php`](views/petugas.php)
- **File Pemroses Aksi Form**: [`index.php`](index.php#L141-L198)
- **File Kueri Database**: [`classes/Model.php`](classes/Model.php#L31-L137)

**Rincian Fitur & Letak Kodenya:**
1. **Tabel Petugas & Level Akses** ([`views/petugas.php#L31-L75`](views/petugas.php#L31-L75)):
   - Menampilkan Nama, Username, dan Badge Role (`admin` atau `petugas`).
2. **Modal Tambah Petugas** ([`views/petugas.php#L78-L125`](views/petugas.php#L78-L125)):
   - Input Nama Lengkap, Username, Password, dan pilihan Hak Akses (Admin / Petugas).
3. **Enkripsi Kata Sandi Bcrypt** ([`classes/Model.php#L79-L88`](classes/Model.php#L79-L88)):
   - Disimpan menggunakan `password_hash($password, PASSWORD_BCRYPT)`.
4. **Proteksi Anti Self-Delete** ([`index.php#L183-L196`](index.php#L183-L196)):
   - Petugas yang sedang login tidak dapat menghapus akunnya sendiri demi mencegah akun admin terkunci.

---

## 3. BEDAH FILE PENDUKUNG & LOGIKA SISTEM

### 1. `config/Database.php` (Koneksi Database PDO Singleton)
- **Baris 8 – 22**: Konstruktor `private` agar class tidak bisa di-instansiasi sembarangan dari luar (Pola Singleton).
- **Baris 27 – 52**: Method `Database::getConnection()`.
- **Baris 37 – 40**: Opsi PDO Krusial:
  - `PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION` (Error berbasis exception).
  - `PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC` (Output array asosiatif murni).
  - `PDO::ATTR_EMULATE_PREPARES => false` (Memaksa MySQL native prepared statements agar kebal SQL Injection).

### 2. `helpers/utility.php` (Kumpulan Helper Terstruktur)
- **Baris 8 – 24**: `bersihkanInput(?string $data)` $\to$ Menghapus spasi liar, menghilangkan backslash, dan mengonversi entitas HTML berbahaya dengan `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')`.
- **Baris 27 – 39**: `formatRupiah(int|float $nominal)` $\to$ Mengubah angka menjadi `Rp 350.000`.
- **Baris 42 – 59**: `setFlashMessage()` & `getFlashMessage()` $\to$ Notifikasi session pop-up sekali tampil.
- **Baris 62 – 74**: `cekLogin()` $\to$ Memeriksa sesi `$_SESSION['user']`. Jika belum ada, otomatis diredirect ke login.
- **Baris 77 – 89**: `cekRole(array $allowedRoles)` $\to$ Proteksi hak akses peran (Role-Based Access Control).
- **Baris 92 – 150**: `renderIcon(string $name)` $\to$ Generator ikon SVG Heroicons murni tanpa menggunakan emoji.

### 3. `logout.php` (Pengakhiran Sesi Aman)
- **Baris 1 – 18**:
  - `session_unset()` $\to$ Menghapus seluruh variabel sesi.
  - `session_destroy()` $\to$ Menghancurkan session ID di server.
  - Redirect kembali ke `index.php?page=login` dengan pesan sukses.

---

## 4. CONTEKAN MENJAWAB PERTANYAAN POPULER ASESOR

Gunakan panduan jawaban ini saat kamu diminta menjelaskan kode di layar laptop:

#### Q1: "Coba tunjukkan di mana kode pencegahan pembayaran ganda (Anti-Double Payment)!"
> **Buka:** [`index.php`](index.php#L234-L247) (Baris 234 – 247) dan [`views/pembayaran.php`](views/pembayaran.php#L210-L245) (Baris 210 – 245)
>
> **Penjelasan ke Asesor:**
> *"Sistem kami memiliki proteksi lapis ganda, Pak/Bu: Di sisi **frontend** (file `pembayaran.php`), JavaScript otomatis memeriksa riwayat bayar siswa; jika suatu bulan sudah pernah lunas, checkbox bulan tersebut langsung kami kunci (*disabled*) dan diberi label (Lunas). Di sisi **backend** (file `index.php`), sebelum menyimpan kami memanggil method `cekSudahBayar()` di MySQL. Jika ada data duplikat, proses langsung kami tolak sehingga tidak akan pernah terjadi pembayaran ganda."*

#### Q2: "Bagaimana cara kerja pembayaran beberapa bulan sekaligus (multi-bulan)?"
> **Buka:** [`views/pembayaran.php`](views/pembayaran.php#L112-L135) (Baris 112 – 135) dan [`index.php`](index.php#L249-L270) (Baris 249 – 270)
>
> **Penjelasan ke Asesor:**
> *"Kasir cukup mencentang bulan-bulan yang ingin dibayar pada checkbox 12 bulan (tersedia juga tombol pintas 'Pilih Semua Tunggakan'). Nominal total otomatis dikalikan tarif SPP siswa. Saat tombol simpan diklik, backend membungkus seluruh data bulan tersebut ke dalam **Database Transaction (`beginTransaction` dan `commit`)**. Jadi semua bulan tersimpan dengan aman, kode kuitansi diterbitkan unik per bulan, dan saldo kas bertambah akurat."*

#### Q3: "Di mana kodingan untuk menghitung tunggakan siswa?"
> **Buka:** [`views/cek_pembayaran.php`](views/cek_pembayaran.php#L25-L63) (Baris 25 – 63)
>
> **Penjelasan ke Asesor:**
> *"Di bagian atas file `cek_pembayaran.php`, kami mengambil daftar 12 bulan standar (Januari sampai Desember), lalu kami cocokkan dengan riwayat transaksi siswa yang berstatus 'Terverifikasi' di database. Bulan-bulan yang belum ada transaksinya dimasukkan ke dalam array `$bulanBelum`. Total tunggakan diperoleh dari jumlah bulan belum bayar dikali tarif SPP bulanan siswa tersebut."*

#### Q4: "Bagaimana sistem Anda mencegah serangan SQL Injection dan XSS?"
> **Buka:** [`config/Database.php`](config/Database.php#L35-L40) (Baris 35 – 40) dan [`helpers/utility.php`](helpers/utility.php#L8-L24) (Baris 8 – 24)
>
> **Penjelasan ke Asesor:**
> *- "Untuk **SQL Injection**, kami menggunakan **PDO Prepared Statements Native** (`PDO::ATTR_EMULATE_PREPARES => false`). Seluruh input disalurkan melalui parameter terikat (`:param`), sehingga kueri SQL tidak bisa dimanipulasi oleh karakter jahat.*
> *- Untuk **XSS (Cross-Site Scripting)**, setiap data masukan disaring oleh fungsi `bersihkanInput()` menggunakan `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')` sebelum diproses atau dicetak ke layar."*

#### Q5: "Di mana kodingan penerapan konsep OOP (PBO)?"
> **Buka:** [`classes/Model.php`](classes/Model.php#L12-L38) (Baris 12 – 38)
>
> **Penjelasan ke Asesor:**
> *- **Abstraction**: Diterapkan pada `abstract class Model` dengan method abstrak `ambilSemua()` dan `ambilBerdasarkanId()`.*
> *- **Inheritance**: Seluruh model anak seperti `PetugasModel`, `SiswaModel`, dan `PembayaranModel` mewarisi properti koneksi `$db` dari induknya menggunakan sintaks `extends Model`.*
> *- **Encapsulation**: Properti koneksi `$db` berstatus `protected`, dan konstanta koneksi di `Database.php` berstatus `private`.*
> *- **Polymorphism**: Method `ambilSemua()` di-override oleh tiap class model sesuai kebutuhan tabelnya masing-masing.*

---

*Dokumen panduan ini disusun rapi dan akurat agar mempermudah navigasi kode sumber saat pengujian verifikasi portofolio UJIKOM.*
