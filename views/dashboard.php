<?php
/**
 * Halaman Dashboard
 * Fungsi: Menampilkan rangkuman statistik data sekolah dan 5 transaksi pembayaran terbaru
 */

// Jika file ini diakses langsung dari URL browser (bukan lewat index.php), arahkan otomatis ke index.php
if (!isset($statistik)) {
    header('Location: ../index.php?page=dashboard');
    exit;
}

// Inisialisasi variabel default agar aman dari error undefined variable
$statistik = $statistik ?? ['total_siswa' => 0, 'total_kelas' => 0, 'total_transaksi' => 0, 'total_kas' => 0];
$transaksiTerkini = $transaksiTerkini ?? [];

$pageTitle = 'Dashboard';
require_once __DIR__ . '/layouts/header.php';
?>

<!-- 1. Empat Kartu Statistik Ringkas -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <!-- Kartu Total Siswa -->
    <div class="bg-white rounded-xl p-5 border border-slate-300/80 shadow-xs flex items-center justify-between">
        <div>
            <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Total Siswa</p>
            <h4 class="text-2xl font-bold tracking-tight text-slate-900 mt-1"><?= $statistik['total_siswa'] ?></h4>
            <span class="text-[11px] text-slate-400 font-medium">Siswa terdaftar aktif</span>
        </div>
        <div class="w-10 h-10 rounded-lg bg-slate-50 border border-slate-200 text-slate-600 flex items-center justify-center">
            <?= renderIcon('siswa', 'w-5 h-5') ?>
        </div>
    </div>

    <!-- Kartu Total Kelas -->
    <div class="bg-white rounded-xl p-5 border border-slate-300/80 shadow-xs flex items-center justify-between">
        <div>
            <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Total Kelas</p>
            <h4 class="text-2xl font-bold tracking-tight text-slate-900 mt-1"><?= $statistik['total_kelas'] ?></h4>
            <span class="text-[11px] text-slate-400 font-medium">Rombel & keahlian</span>
        </div>
        <div class="w-10 h-10 rounded-lg bg-slate-50 border border-slate-200 text-slate-600 flex items-center justify-center">
            <?= renderIcon('kelas', 'w-5 h-5') ?>
        </div>
    </div>

    <!-- Kartu Jumlah Transaksi -->
    <div class="bg-white rounded-xl p-5 border border-slate-300/80 shadow-xs flex items-center justify-between">
        <div>
            <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Transaksi SPP</p>
            <h4 class="text-2xl font-bold tracking-tight text-slate-900 mt-1"><?= $statistik['total_transaksi'] ?></h4>
            <span class="text-[11px] text-slate-400 font-medium">Riwayat pembayaran</span>
        </div>
        <div class="w-10 h-10 rounded-lg bg-slate-50 border border-slate-200 text-slate-600 flex items-center justify-center">
            <?= renderIcon('pembayaran', 'w-5 h-5') ?>
        </div>
    </div>

    <!-- Kartu Total Kas Masuk (Nominal Uang) -->
    <div class="bg-white rounded-xl p-5 border border-slate-300/80 shadow-xs flex items-center justify-between">
        <div>
            <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Kas SPP Masuk</p>
            <h4 class="text-xl font-bold tracking-tight text-orange-600 mt-1"><?= formatRupiah($statistik['total_kas']) ?></h4>
            <span class="text-[11px] text-slate-400 font-medium">Total penerimaan</span>
        </div>
        <div class="w-10 h-10 rounded-lg bg-orange-50 border border-orange-200 text-orange-600 flex items-center justify-center">
            <?= renderIcon('spp', 'w-5 h-5') ?>
        </div>
    </div>
</div>

<!-- 2. Tabel 5 Transaksi Terakhir -->
<div class="bg-white rounded-xl shadow-xs border border-slate-300/80 overflow-hidden">
    <div class="px-5 py-3.5 border-b border-slate-100 flex items-center justify-between bg-white">
        <div class="flex items-center space-x-2">
            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">5 Transaksi Terakhir</h3>
            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-600 border border-slate-200">Terbaru</span>
        </div>
        <!-- Link untuk membuka seluruh histori transaksi -->
        <a href="index.php?page=detail_pembayaran" class="text-xs font-semibold text-orange-600 hover:text-orange-700 transition flex items-center space-x-1">
            <span>Lihat Semua</span>
            <span aria-hidden="true">&rarr;</span>
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-xs">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-semibold uppercase text-[10px] tracking-wider">
                    <th class="px-4 py-3 whitespace-nowrap">Kode</th>
                    <th class="px-4 py-3">Siswa</th>
                    <th class="px-4 py-3">Kelas</th>
                    <th class="px-4 py-3">Periode</th>
                    <th class="px-4 py-3">Nominal</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Petugas</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (empty($transaksiTerkini)): ?>
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-slate-400 font-medium">Belum ada transaksi pembayaran yang tercatat.</td>
                </tr>
                <?php else: ?>
                    <?php foreach ($transaksiTerkini as $trx): ?>
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="font-mono font-semibold text-xs text-orange-600 bg-orange-50 px-2 py-0.5 rounded border border-orange-200 whitespace-nowrap inline-block"><?= htmlspecialchars($trx['kode_transaksi']) ?></span>
                        </td>
                        <td class="px-4 py-3 font-semibold text-slate-900"><?= htmlspecialchars($trx['nama_siswa']) ?></td>
                        <td class="px-4 py-3 text-slate-600 font-medium"><?= htmlspecialchars($trx['nama_kelas']) ?></td>
                        <td class="px-4 py-3 font-medium text-slate-700"><?= htmlspecialchars($trx['bulan_dibayar']) ?> <?= htmlspecialchars($trx['tahun_dibayar']) ?></td>
                        <td class="px-4 py-3 font-bold font-mono text-slate-900"><?= formatRupiah($trx['jumlah_bayar']) ?></td>
                        <td class="px-4 py-3">
                            <!-- Badge status: Hijau kalau Terverifikasi, Kuning kalau Menunggu -->
                            <?php if ($trx['status_verifikasi'] === 'Terverifikasi'): ?>
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Terverifikasi
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-amber-50 text-amber-700 border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    Menunggu
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3 text-slate-500 text-[11px] font-medium"><?= htmlspecialchars($trx['nama_petugas']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
