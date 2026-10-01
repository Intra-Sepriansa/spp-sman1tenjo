<?php
/**
 * Controller Utama & Pengatur Alur Halaman (Front Controller)
 * Menangani pengiriman form (action) dan menampilkan 7 halaman menu (routing)
 */

// Panggil file konfigurasi database, fungsi bantuan, dan class model
require_once __DIR__ . '/helpers/utility.php';
require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/classes/Model.php';

// Inisialisasi koneksi database dan objek-objek model
$db = Database::getConnection();
$petugasModel       = new PetugasModel($db);
$kelasModel         = new KelasModel($db);
$sppModel           = new SppModel($db);
$siswaModel         = new SiswaModel($db);
$pembayaranModel    = new PembayaranModel($db);
$cekPembayaranModel = new CekPembayaranModel($db);

// Ambil parameter action (jika ada form yang disubmit) dan page (halaman yang diminta)
$action = $_GET['action'] ?? null;
$page   = $_GET['page'] ?? 'dashboard';

// ====================================================================
// BAGIAN 1: PEMROSESAN AKSI FORM (ACTION HANDLER)
// ====================================================================
if ($action !== null) {
    
    // 1.1 Proses Verifikasi Login Petugas / Admin
    if ($action === 'login_proses') {
        $username = bersihkanInput($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        $user = $petugasModel->login($username, $password);
        if ($user) {
            $_SESSION['user'] = $user;
            setFlashMessage('success', 'Selamat datang kembali, ' . $user['nama_petugas']);
            header('Location: index.php?page=dashboard');
            exit;
        } else {
            setFlashMessage('error', 'Username atau password yang Anda masukkan salah!');
            header('Location: index.php?page=login');
            exit;
        }
    }

    // Pastikan user sudah login untuk semua aksi di bawah ini
    cekLogin();
    $currentUser = $_SESSION['user'];

    // 1.2 Simpan Data Siswa Baru
    if ($action === 'siswa_simpan') {
        $nisn    = bersihkanInput($_POST['nisn'] ?? '');
        $nis     = bersihkanInput($_POST['nis'] ?? '');
        $nama    = bersihkanInput($_POST['nama'] ?? '');
        $idKelas = (int)($_POST['id_kelas'] ?? 0);
        $alamat  = bersihkanInput($_POST['alamat'] ?? '');
        $noTelp  = bersihkanInput($_POST['no_telp'] ?? '');
        $idSpp   = (int)($_POST['id_spp'] ?? 0);

        try {
            $siswaModel->tambah($nisn, $nis, $nama, $idKelas, $alamat, $noTelp, $idSpp);
            setFlashMessage('success', 'Data siswa berhasil ditambahkan.');
        } catch (PDOException $e) {
            setFlashMessage('error', 'Gagal menyimpan: NISN atau NIS sudah terdaftar di sistem.');
        }
        header('Location: index.php?page=siswa');
        exit;
    }

    // 1.3 Ubah Data Siswa
    if ($action === 'siswa_update') {
        $nisn    = bersihkanInput($_POST['nisn'] ?? '');
        $nis     = bersihkanInput($_POST['nis'] ?? '');
        $nama    = bersihkanInput($_POST['nama'] ?? '');
        $idKelas = (int)($_POST['id_kelas'] ?? 0);
        $alamat  = bersihkanInput($_POST['alamat'] ?? '');
        $noTelp  = bersihkanInput($_POST['no_telp'] ?? '');
        $idSpp   = (int)($_POST['id_spp'] ?? 0);

        try {
            $siswaModel->ubah($nisn, $nis, $nama, $idKelas, $alamat, $noTelp, $idSpp);
            setFlashMessage('success', 'Data siswa berhasil diperbarui.');
        } catch (PDOException $e) {
            setFlashMessage('error', 'Gagal memperbarui data siswa.');
        }
        header('Location: index.php?page=siswa');
        exit;
    }

    // 1.4 Hapus Siswa (Hanya Administrator)
    if ($action === 'siswa_hapus') {
        cekRole(['admin']);
        $nisn = bersihkanInput($_GET['nisn'] ?? '');
        try {
            $siswaModel->hapus($nisn);
            setFlashMessage('success', 'Data siswa berhasil dihapus.');
        } catch (PDOException $e) {
            setFlashMessage('error', 'Gagal menghapus siswa.');
        }
        header('Location: index.php?page=siswa');
        exit;
    }

    // 1.5 Simpan Data Kelas Baru
    if ($action === 'kelas_simpan') {
        $namaKelas  = bersihkanInput($_POST['nama_kelas'] ?? '');
        $kompetensi = bersihkanInput($_POST['kompetensi_keahlian'] ?? '');
        $kelasModel->tambah($namaKelas, $kompetensi);
        setFlashMessage('success', 'Data rombel kelas berhasil ditambahkan.');
        header('Location: index.php?page=kelas');
        exit;
    }

    // 1.6 Ubah Data Kelas
    if ($action === 'kelas_update') {
        $idKelas    = (int)($_POST['id_kelas'] ?? 0);
        $namaKelas  = bersihkanInput($_POST['nama_kelas'] ?? '');
        $kompetensi = bersihkanInput($_POST['kompetensi_keahlian'] ?? '');
        $kelasModel->ubah($idKelas, $namaKelas, $kompetensi);
        setFlashMessage('success', 'Data kelas berhasil diubah.');
        header('Location: index.php?page=kelas');
        exit;
    }

    // 1.7 Hapus Kelas (Hanya Administrator)
    if ($action === 'kelas_hapus') {
        cekRole(['admin']);
        $idKelas = (int)($_GET['id'] ?? 0);
        try {
            $kelasModel->hapus($idKelas);
            setFlashMessage('success', 'Data kelas berhasil dihapus.');
        } catch (PDOException $e) {
            setFlashMessage('error', 'Gagal menghapus: Masih ada data siswa di kelas ini.');
        }
        header('Location: index.php?page=kelas');
        exit;
    }

    // 1.8 Simpan Petugas Baru (Hanya Administrator)
    if ($action === 'petugas_simpan') {
        cekRole(['admin']);
        $u = bersihkanInput($_POST['username'] ?? '');
        $p = $_POST['password'] ?? '';
        $n = bersihkanInput($_POST['nama_petugas'] ?? '');
        $l = bersihkanInput($_POST['level'] ?? 'petugas');
        try {
            $petugasModel->tambah($u, $p, $n, $l);
            setFlashMessage('success', 'Akun petugas berhasil dibuat.');
        } catch (PDOException $e) {
            setFlashMessage('error', 'Gagal: Username sudah digunakan oleh petugas lain!');
        }
        header('Location: index.php?page=petugas');
        exit;
    }

    // 1.9 Ubah Data Petugas
    if ($action === 'petugas_update') {
        cekRole(['admin']);
        $id = (int)($_POST['id_petugas'] ?? 0);
        $u  = bersihkanInput($_POST['username'] ?? '');
        $p  = !empty($_POST['password']) ? $_POST['password'] : null;
        $n  = bersihkanInput($_POST['nama_petugas'] ?? '');
        $l  = bersihkanInput($_POST['level'] ?? 'petugas');
        try {
            $petugasModel->ubah($id, $u, $p, $n, $l);
            // Kalau yang diubah akun sendiri, update juga data session yang aktif
            if ($id === (int)$currentUser['id_petugas']) {
                $_SESSION['user']['nama_petugas'] = $n;
                $_SESSION['user']['username']     = $u;
                $_SESSION['user']['level']        = $l;
            }
            setFlashMessage('success', 'Data akun petugas berhasil diperbarui.');
        } catch (PDOException $e) {
            setFlashMessage('error', 'Gagal: Username sudah digunakan oleh petugas lain!');
        }
        header('Location: index.php?page=petugas');
        exit;
    }

    // 1.10 Hapus Petugas
    if ($action === 'petugas_hapus') {
        cekRole(['admin']);
        $id = (int)($_GET['id'] ?? 0);
        if ($id === (int)$currentUser['id_petugas']) {
            setFlashMessage('error', 'Anda tidak bisa menghapus akun yang sedang dipakai login!');
        } else {
            try {
                $petugasModel->hapus($id);
                setFlashMessage('success', 'Akun petugas berhasil dihapus.');
            } catch (PDOException $e) {
                setFlashMessage('error', 'Gagal menghapus: Akun petugas ini memiliki riwayat transaksi/verifikasi di sistem.');
            }
        }
        header('Location: index.php?page=petugas');
        exit;
    }

    // 1.11 Simpan Transaksi Pembayaran SPP Baru (Mendukung 1 Bulan atau Beberapa Bulan Sekaligus)
    if ($action === 'pembayaran_simpan') {
        $nisn         = bersihkanInput($_POST['nisn'] ?? '');
        $idSpp        = (int)($_POST['id_spp'] ?? 0);
        $tglBayar     = bersihkanInput($_POST['tgl_bayar'] ?? date('Y-m-d'));
        $tahunDibayar = bersihkanInput($_POST['tahun_dibayar'] ?? date('Y'));
        $metode       = bersihkanInput($_POST['metode_pembayaran'] ?? 'Tunai');
        $status       = bersihkanInput($_POST['status_verifikasi'] ?? 'Terverifikasi');
        $idPetugas    = (int)$currentUser['id_petugas'];

        // Ambil daftar bulan yang dipilih (bisa berupa array atau string tunggal)
        $rawBulan = $_POST['bulan_dibayar'] ?? [];
        $daftarBulan = is_array($rawBulan) ? $rawBulan : [$rawBulan];
        $daftarBulan = array_values(array_filter(array_map('trim', $daftarBulan)));

        if (empty($nisn) || empty($daftarBulan)) {
            setFlashMessage('error', 'Gagal: Harap pilih siswa dan minimal satu bulan tagihan yang akan dibayar!');
            header('Location: index.php?page=pembayaran' . (!empty($nisn) ? '&nisn=' . $nisn : ''));
            exit;
        }

        // Ambil data master siswa untuk memastikan tarif SPP akurat
        $siswaData = $siswaModel->ambilBerdasarkanId($nisn);
        if (!$siswaData) {
            setFlashMessage('error', 'Gagal: Data siswa dengan NISN tersebut tidak ditemukan.');
            header('Location: index.php?page=pembayaran');
            exit;
        }

        if ($idSpp <= 0) {
            $idSpp = (int)$siswaData['id_spp'];
        }
        $tarifPerBulan = (int)$siswaData['nominal_spp'];

        // Validasi Anti-Double Payment: Periksa setiap bulan yang dipilih
        $bulanGanda = [];
        foreach ($daftarBulan as $bln) {
            $cek = $pembayaranModel->cekSudahBayar($nisn, $bln, $tahunDibayar);
            if ($cek) {
                $bulanGanda[] = $bln;
            }
        }

        if (!empty($bulanGanda)) {
            setFlashMessage('error', "Gagal: Pembayaran untuk bulan " . implode(', ', $bulanGanda) . " tahun {$tahunDibayar} sudah pernah tercatat/lunas sebelumnya!");
            header('Location: index.php?page=pembayaran&nisn=' . $nisn);
            exit;
        }

        // Simpan seluruh bulan yang dipilih dalam satu transaksi database ACID
        $suksesBulan = [];
        $totalNominalBayar = 0;
        $db->beginTransaction();
        try {
            foreach ($daftarBulan as $bln) {
                $kodeTransaksi = $pembayaranModel->buatKodeTransaksi();
                $insertId = $pembayaranModel->tambah(
                    $kodeTransaksi, $idPetugas, $nisn, $tglBayar, $bln, $tahunDibayar, $idSpp, $tarifPerBulan, $metode, $status
                );

                if ($insertId) {
                    $catatan = $status === 'Terverifikasi' 
                        ? 'Pembayaran lunas diterima oleh ' . $currentUser['nama_petugas'] 
                        : 'Menunggu verifikasi transfer kasir';
                    $cekPembayaranModel->tambahLog($insertId, $nisn, $status, $catatan, $idPetugas);
                    $suksesBulan[] = $bln;
                    $totalNominalBayar += $tarifPerBulan;
                }
            }
            $db->commit();

            if (count($suksesBulan) === 1) {
                setFlashMessage('success', "Pembayaran SPP bulan {$suksesBulan[0]} {$tahunDibayar} berhasil disimpan (" . formatRupiah($totalNominalBayar) . ").");
            } else {
                setFlashMessage('success', "Berhasil! Pembayaran untuk " . count($suksesBulan) . " bulan (" . implode(', ', $suksesBulan) . ") berhasil disimpan. Total: " . formatRupiah($totalNominalBayar));
            }
            header('Location: index.php?page=detail_pembayaran');
            exit;
        } catch (Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            setFlashMessage('error', 'Gagal memproses transaksi: ' . $e->getMessage());
            header('Location: index.php?page=pembayaran&nisn=' . $nisn);
            exit;
        }
    }

    // 1.12 Hapus Transaksi Pembayaran
    if ($action === 'pembayaran_hapus') {
        cekRole(['admin']);
        $id = (int)($_GET['id'] ?? 0);
        try {
            $pembayaranModel->hapus($id);
            setFlashMessage('success', 'Data transaksi berhasil dihapus.');
        } catch (PDOException $e) {
            setFlashMessage('error', 'Gagal menghapus data transaksi.');
        }
        header('Location: index.php?page=detail_pembayaran');
        exit;
    }

    // 1.13 Proses Verifikasi Pembayaran (Setujui / Tolak)
    if ($action === 'proses_verifikasi') {
        $idPembayaran = (int)($_POST['id_pembayaran'] ?? 0);
        $nisn         = bersihkanInput($_POST['nisn'] ?? '');
        $status       = bersihkanInput($_POST['status_verifikasi'] ?? 'Terverifikasi');
        $catatan      = bersihkanInput($_POST['catatan'] ?? '');
        $idPetugas    = (int)$currentUser['id_petugas'];

        $cekPembayaranModel->prosesVerifikasi($idPembayaran, $nisn, $status, $catatan, $idPetugas);
        setFlashMessage('success', 'Status verifikasi pembayaran berhasil disimpan.');
        header('Location: index.php?page=cek_pembayaran');
        exit;
    }

    header('Location: index.php?page=dashboard');
    exit;
}

// ====================================================================
// BAGIAN 2: PENGATUR TAMPILAN HALAMAN (ROUTING VIEW)
// ====================================================================

// Jika belum login dan mencoba buka selain halaman login, paksa ke login
if (!isset($_SESSION['user']) && $page !== 'login') {
    header('Location: index.php?page=login');
    exit;
}

// Jika sudah login dan mencoba buka halaman login lagi, arahkan ke dashboard
if (isset($_SESSION['user']) && $page === 'login') {
    header('Location: index.php?page=dashboard');
    exit;
}

// Buka tampilan view sesuai menu yang dipilih
switch ($page) {
    // Tampilan Form Login
    case 'login':
        require_once __DIR__ . '/views/login.php';
        break;

    // Menu 1: Dashboard Ringkasan Kas & Transaksi
    case 'dashboard':
        $statistik = $pembayaranModel->hitungStatistik();
        $transaksiTerkini = array_slice($pembayaranModel->ambilSemua(), 0, 5);
        require_once __DIR__ . '/views/dashboard.php';
        break;

    // Menu 2: Manajemen Data Rombel Kelas
    case 'kelas':
        $daftarKelas = $kelasModel->ambilSemua();
        require_once __DIR__ . '/views/kelas.php';
        break;

    // Menu 3: Manajemen Data Siswa
    case 'siswa':
        $queryCari   = bersihkanInput($_GET['q'] ?? '');
        $daftarSiswa = !empty($queryCari) ? $siswaModel->cari($queryCari) : $siswaModel->ambilSemua();
        $daftarKelas = $kelasModel->ambilSemua();
        $daftarSpp   = $sppModel->ambilSemua();
        require_once __DIR__ . '/views/siswa.php';
        break;

    // Menu 4: Cek Pembayaran & Tunggakan Siswa
    case 'cek_pembayaran':
        $daftarSiswa     = $siswaModel->ambilSemua();
        $cariNisn        = !empty($_GET['cari_nisn']) ? bersihkanInput($_GET['cari_nisn']) : ($daftarSiswa[0]['nisn'] ?? '');
        $siswaDitemukan  = !empty($cariNisn) ? $siswaModel->ambilBerdasarkanId($cariNisn) : null;
        $riwayatSiswa    = $siswaDitemukan ? $pembayaranModel->ambilRiwayatSiswa($cariNisn) : [];
        $semuaTerbayar   = $pembayaranModel->ambilSemuaPeriodeTerbayar();
        require_once __DIR__ . '/views/cek_pembayaran.php';
        break;

    // Menu 5: Form Entri Pembayaran SPP Baru (Multi-Bulan)
    case 'pembayaran':
        $daftarSiswa     = $siswaModel->ambilSemua();
        $periodeTerbayar = $pembayaranModel->ambilSemuaPeriodeTerbayar();
        require_once __DIR__ . '/views/pembayaran.php';
        break;

    // Menu 6: Detail Histori Transaksi & Cetak Kuitansi
    case 'detail_pembayaran':
        $filterBulan      = $_GET['bulan'] ?? '';
        $cari             = bersihkanInput($_GET['q'] ?? '');
        $daftarPembayaran = $pembayaranModel->ambilSemua($filterBulan ?: null, null, $cari ?: null);
        require_once __DIR__ . '/views/detail_pembayaran.php';
        break;

    // Menu 7: Manajemen Data Petugas (Khusus Administrator)
    case 'petugas':
        cekRole(['admin']);
        $daftarPetugas = $petugasModel->ambilSemua();
        require_once __DIR__ . '/views/petugas.php';
        break;

    // Jika menu tidak ditemukan, kembalikan ke dashboard
    default:
        header('Location: index.php?page=dashboard');
        exit;
}
