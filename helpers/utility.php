<?php
/**
 * File Kumpulan Fungsi Bantuan (Utility / Helper)
 * Berisi fungsi-fungsi umum yang sering dipakai di berbagai halaman
 */

// Mulai session PHP jika belum aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * 1. Mencegah serangan XSS (Cross-Site Scripting)
 * Menghapus spasi liar dan mengubah karakter bahaya seperti < > jadi entitas HTML aman
 */
function bersihkanInput(?string $data): string
{
    if ($data === null) {
        return '';
    }
    $data = trim($data);
    $data = stripslashes($data);
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

/**
 * 2. Mengubah angka biasa menjadi format Rupiah Indonesia
 * Contoh: 250000 -> "Rp 250.000"
 */
function formatRupiah(int|float $nominal): string
{
    return "Rp " . number_format($nominal, 0, ',', '.');
}

/**
 * 3. Menyimpan notifikasi pesan flash sementara ke dalam session
 * Jenis: 'success' (berhasil), 'error' (gagal/salah), 'info'
 */
function setFlashMessage(string $type, string $message): void
{
    $_SESSION['flash_message'] = [
        'type'    => $type,
        'message' => $message,
    ];
}

/**
 * Mengambil dan langsung menghapus notifikasi pesan flash dari session
 * Supaya pesan tidak muncul berulang kali setelah halaman di-refresh
 */
function getFlashMessage(): ?array
{
    if (isset($_SESSION['flash_message'])) {
        $flash = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $flash;
    }
    return null;
}

/**
 * 4. Cek apakah user sudah login
 * Kalau belum ada data login di session, langsung lempar kembali ke halaman login
 */
function cekLogin(): void
{
    if (!isset($_SESSION['user']) || empty($_SESSION['user']['id_petugas'])) {
        setFlashMessage('error', 'Sesi Anda telah berakhir. Silakan login terlebih dahulu.');
        header('Location: index.php?page=login');
        exit;
    }
}

/**
 * 5. Cek hak akses / role level user
 * Kalau level user tidak ada dalam daftar yang diizinkan, tolak akses dan kembalikan ke dashboard
 */
function cekRole(array $allowedRoles): void
{
    cekLogin();
    $userRole = $_SESSION['user']['level'] ?? '';
    if (!in_array($userRole, $allowedRoles, true)) {
        setFlashMessage('error', 'Akses ditolak! Anda tidak memiliki izin untuk membuka menu ini.');
        header('Location: index.php?page=dashboard');
        exit;
    }
}

/**
 * 6. Mengubah nominal angka menjadi format terbilang teks bahasa Indonesia formal
 * Contoh: 350000 -> "Tiga Ratus Lima Puluh Ribu"
 */
function terbilang(int $angka): string
{
    $angka = abs($angka);
    $baca = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas'];
    $hasil = '';

    if ($angka < 12) {
        $hasil = ' ' . $baca[$angka];
    } elseif ($angka < 20) {
        $hasil = terbilang($angka - 10) . ' Belas';
    } elseif ($angka < 100) {
        $hasil = terbilang((int)($angka / 10)) . ' Puluh ' . terbilang($angka % 10);
    } elseif ($angka < 200) {
        $hasil = ' Seratus ' . terbilang($angka - 100);
    } elseif ($angka < 1000) {
        $hasil = terbilang((int)($angka / 100)) . ' Ratus ' . terbilang($angka % 100);
    } elseif ($angka < 2000) {
        $hasil = ' Seribu ' . terbilang($angka - 1000);
    } elseif ($angka < 1000000) {
        $hasil = terbilang((int)($angka / 1000)) . ' Ribu ' . terbilang($angka % 1000);
    } elseif ($angka < 1000000000) {
        $hasil = terbilang((int)($angka / 1000000)) . ' Juta ' . terbilang($angka % 1000000);
    } elseif ($angka < 1000000000000) {
        $hasil = terbilang((int)($angka / 1000000000)) . ' Miliar ' . terbilang($angka % 1000000000);
    }

    return trim(preg_replace('/\s+/', ' ', $hasil));
}

/**
 * 7. Menampilkan icon SVG murni Heroicons Outline
 * Menggunakan kode SVG resmi, bebas emoji sesuai instruksi ujikom
 */
function renderIcon(string $name, string $class = 'w-5 h-5'): string
{
    // Tentukan fallback ukuran pixel agar icon tidak pernah membesar liar jika CSS belum selesai termuat
    $size = '20';
    if (strpos($class, 'w-4') !== false) {
        $size = '16';
    } elseif (strpos($class, 'w-3') !== false) {
        $size = '12';
    } elseif (strpos($class, 'w-6') !== false) {
        $size = '24';
    } elseif (strpos($class, 'w-8') !== false) {
        $size = '32';
    }

    $paths = [
        'dashboard' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>',
        'siswa'     => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>',
        'kelas'     => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>',
        'spp'       => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>',
        'petugas'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>',
        'pembayaran'=> '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>',
        'cek'       => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>',
        'logout'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>',
        'login'     => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>',
        'plus'      => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>',
        'pencil'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>',
        'trash'     => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>',
        'search'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>',
        'check'     => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>',
        'printer'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>',
        'lock'      => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>',
        'user'      => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>',
        'clock'     => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
        'alert'     => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>',
    ];

    if (!isset($paths[$name])) {
        return '';
    }

    return '<svg width="' . $size . '" height="' . $size . '" class="' . $class . '" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">' . $paths[$name] . '</svg>';
}
