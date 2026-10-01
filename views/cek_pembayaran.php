<?php
/**
 * Halaman Cek Pembayaran & Tunggakan SPP Siswa
 * Sekolah: SMAN 1 TENJO
 * Fungsi: Memeriksa histori pembayaran siswa, melihat bulan yang belum dibayar (tunggakan), dan verifikasi transaksi
 */

// Jika file ini diakses langsung dari URL browser (bukan lewat index.php), arahkan otomatis ke index.php
if (!isset($daftarSiswa)) {
    header('Location: ../index.php?page=cek_pembayaran');
    exit;
}

// Inisialisasi variabel default
$daftarSiswa    = $daftarSiswa ?? [];
$cariNisn       = $cariNisn ?? (!empty($_GET['cari_nisn']) ? bersihkanInput($_GET['cari_nisn']) : ($daftarSiswa[0]['nisn'] ?? ''));
$siswaDitemukan = $siswaDitemukan ?? null;
if (empty($siswaDitemukan) && !empty($cariNisn) && !empty($daftarSiswa)) {
    foreach ($daftarSiswa as $s) {
        if ($s['nisn'] === $cariNisn) {
            $siswaDitemukan = $s;
            break;
        }
    }
}
$riwayatSiswa   = $riwayatSiswa ?? [];
$daftarMenunggu = $daftarMenunggu ?? [];
$semuaTerbayar  = $semuaTerbayar ?? [];

$daftarBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
$tahunAktif  = date('Y');

// Hitung bulan yang belum dibayar untuk siswa yang dipilih
$bulanLunas = [];
$bulanBelum = [];
$totalTunggakanSiswa = 0;

if ($siswaDitemukan) {
    $tarifPerBulan = (int)($siswaDitemukan['nominal_spp'] ?? 0);
    foreach ($riwayatSiswa as $r) {
        if ((string)$r['tahun_dibayar'] === (string)$tahunAktif && $r['status_verifikasi'] === 'Terverifikasi') {
            $bulanLunas[] = $r['bulan_dibayar'];
        }
    }
    foreach ($daftarBulan as $b) {
        if (!in_array($b, $bulanLunas)) {
            $bulanBelum[] = $b;
        }
    }
    $totalTunggakanSiswa = count($bulanBelum) * $tarifPerBulan;
}

$pageTitle = 'Cek Tunggakan';
require_once __DIR__ . '/layouts/header.php';
?>

<!-- 1. Form Pencarian / Pilih Siswa -->
<div class="bg-white rounded-xl shadow-xs border border-slate-300/80 p-5 mb-6">
    <label for="cari_nisn" class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-2">
        Pilih / Cari Siswa
    </label>
    <form action="index.php" method="GET" class="flex flex-col sm:flex-row gap-2 max-w-xl">
        <input type="hidden" name="page" value="cek_pembayaran">
        <select id="cari_nisn" name="cari_nisn" onchange="this.form.submit()" 
                class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 bg-white font-medium text-slate-800 focus:outline-none focus:border-orange-500 cursor-pointer">
            <option value="">-- Pilih Siswa --</option>
            <?php foreach ($daftarSiswa as $s): ?>
                <option value="<?= $s['nisn'] ?>" <?= $cariNisn === $s['nisn'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($s['nama']) ?> (<?= htmlspecialchars($s['nama_kelas']) ?>) - <?= $s['nisn'] ?>
                </option>
            <?php endforeach; ?>
        </select>
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg text-xs font-semibold">
                Cari
            </button>
            <?php if (!empty($cariNisn)): ?>
                <a href="index.php?page=cek_pembayaran" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-medium border border-slate-300">Reset</a>
            <?php endif; ?>
        </div>
    </form>

    <!-- Informasi Siswa yang Dipilih -->
    <?php if (!empty($cariNisn)): ?>
        <div class="mt-5 pt-5 border-t border-slate-200">
            <?php if (!empty($siswaDitemukan)): ?>
                <!-- Ringkasan Data Siswa -->
                <div class="bg-slate-50 p-4 rounded-lg border border-slate-200 mb-4 text-xs">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <div>
                            <span class="text-slate-500">Nama Siswa:</span>
                            <span class="font-bold text-slate-900 ml-1"><?= htmlspecialchars($siswaDitemukan['nama']) ?></span>
                        </div>
                        <div>
                            <span class="text-slate-500">NISN / NIS:</span>
                            <span class="font-mono text-slate-800 ml-1"><?= htmlspecialchars($siswaDitemukan['nisn']) ?> / <?= htmlspecialchars($siswaDitemukan['nis']) ?></span>
                        </div>
                        <div>
                            <span class="text-slate-500">Kelas:</span>
                            <span class="font-semibold text-slate-800 ml-1 whitespace-nowrap"><?= htmlspecialchars($siswaDitemukan['nama_kelas']) ?></span> <span class="text-slate-500">(<?= htmlspecialchars($siswaDitemukan['kompetensi_keahlian']) ?>)</span>
                        </div>
                        <div>
                            <span class="text-slate-500">Tarif SPP:</span>
                            <span class="font-bold font-mono text-slate-900 ml-1"><?= formatRupiah($siswaDitemukan['nominal_spp']) ?> / bulan</span>
                        </div>
                    </div>
                </div>

                <!-- Tabel Gabungan: Status & Riwayat Pembayaran SPP 12 Bulan Siswa -->
                <div class="mb-5">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-3">
                        <div>
                            <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">
                                Status & Riwayat Pembayaran SPP 12 Bulan (Tahun <?= $tahunAktif ?>)
                            </h4>
                            <p class="text-[11px] text-slate-500 mt-0.5">Rangkuman terpadu tagihan per bulan, kode transaksi, tanggal bayar, dan status pelunasan.</p>
                        </div>
                        <div class="text-xs font-semibold flex items-center space-x-2 shrink-0">
                            <span><span class="text-slate-500 font-normal">Tunggakan:</span> <span class="text-rose-600 font-mono font-bold"><?= formatRupiah($totalTunggakanSiswa) ?></span> <span class="text-slate-400 font-normal">(<?= count($bulanBelum) ?> bln)</span></span>
                            <?php if (!empty($bulanBelum)): ?>
                            <a href="index.php?page=pembayaran&nisn=<?= $siswaDitemukan['nisn'] ?>&bulan=<?= implode(',', $bulanBelum) ?>" 
                               class="px-2.5 py-1 bg-orange-600 hover:bg-orange-700 text-white rounded text-[11px] font-semibold transition cursor-pointer shadow-xs inline-flex items-center gap-1">
                                <span>Bayar Sekaligus</span>
                                <span>&rarr;</span>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="rounded-lg border border-slate-200 overflow-hidden overflow-x-auto bg-white">
                        <table class="w-full text-left border-collapse text-xs whitespace-nowrap">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-semibold uppercase text-[10px] tracking-wider">
                                    <th class="px-3 py-2.5 w-10 text-center">No</th>
                                    <th class="px-3 py-2.5">Bulan</th>
                                    <th class="px-3 py-2.5">Tarif SPP</th>
                                    <th class="px-3 py-2.5">Kode Transaksi</th>
                                    <th class="px-3 py-2.5">Tgl Bayar</th>
                                    <th class="px-3 py-2.5">Petugas</th>
                                    <th class="px-3 py-2.5">Status</th>
                                    <th class="px-3 py-2.5 text-center">Aksi / Kuitansi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php $no = 1; foreach ($daftarBulan as $bln): ?>
                                    <?php 
                                    $trx = null;
                                    foreach ($riwayatSiswa as $r) {
                                        if (strcasecmp($r['bulan_dibayar'], $bln) === 0 && (string)$r['tahun_dibayar'] === (string)$tahunAktif) {
                                            $trx = $r;
                                            break;
                                        }
                                    }
                                    ?>
                                    <tr class="hover:bg-slate-50/80 transition-colors">
                                        <td class="px-3 py-2.5 text-center text-slate-400 font-mono text-[11px]"><?= $no++ ?></td>
                                        <td class="px-3 py-2.5 font-semibold text-slate-800"><?= $bln ?></td>
                                        <td class="px-3 py-2.5 font-mono text-slate-700 font-semibold"><?= formatRupiah($tarifPerBulan) ?></td>
                                        <td class="px-3 py-2.5">
                                            <?php if ($trx): ?>
                                                <span class="font-mono text-[11px] font-semibold text-orange-600 bg-orange-50 px-2 py-0.5 rounded border border-orange-200">
                                                    <?= htmlspecialchars($trx['kode_transaksi']) ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-slate-300 font-mono">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-3 py-2.5 text-slate-600 text-[11px] font-mono">
                                            <?= $trx ? date('d/m/Y', strtotime($trx['tgl_bayar'])) : '<span class="text-slate-300">-</span>' ?>
                                        </td>
                                        <td class="px-3 py-2.5 text-slate-600 text-[11px]">
                                            <?= $trx ? htmlspecialchars($trx['nama_petugas']) : '<span class="text-slate-300">-</span>' ?>
                                        </td>
                                        <td class="px-3 py-2.5">
                                            <?php if ($trx && $trx['status_verifikasi'] === 'Terverifikasi'): ?>
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                    Lunas
                                                </span>
                                            <?php elseif ($trx && $trx['status_verifikasi'] === 'Menunggu Verifikasi'): ?>
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                    Menunggu
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                    Belum Bayar
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-3 py-2.5 text-center">
                                            <?php if ($trx && $trx['status_verifikasi'] === 'Terverifikasi'): ?>
                                                <a href="index.php?page=detail_pembayaran&q=<?= urlencode($trx['kode_transaksi']) ?>" 
                                                   class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded text-[11px] font-semibold inline-flex items-center gap-1 border border-slate-300 transition" 
                                                   title="Lihat Histori & Cetak Kuitansi">
                                                    <?= renderIcon('printer', 'w-3.5 h-3.5') ?>
                                                    <span>Kuitansi</span>
                                                </a>
                                            <?php elseif ($trx && $trx['status_verifikasi'] === 'Menunggu Verifikasi'): ?>
                                                <span class="text-amber-600 text-[11px] font-medium bg-amber-50 px-2 py-0.5 rounded border border-amber-200">Proses</span>
                                            <?php else: ?>
                                                <a href="index.php?page=pembayaran&nisn=<?= $siswaDitemukan['nisn'] ?>&bulan=<?= $bln ?>" 
                                                   class="px-2.5 py-1 bg-orange-600 hover:bg-orange-700 text-white rounded text-[11px] font-semibold inline-block transition shadow-xs">
                                                    Bayar
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Jika ada riwayat transaksi di luar tahun aktif, tampilkan tabel ringkas di bawahnya -->
                <?php 
                $transaksiTahunLain = array_filter($riwayatSiswa, fn($r) => (string)$r['tahun_dibayar'] !== (string)$tahunAktif);
                ?>
                <?php if (!empty($transaksiTahunLain)): ?>
                <div class="mb-5">
                    <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-2">
                        Riwayat Transaksi Tahun Lainnya
                    </h4>
                    <div class="rounded-lg border border-slate-200 overflow-hidden overflow-x-auto bg-white">
                        <table class="w-full text-left border-collapse text-xs whitespace-nowrap">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-semibold uppercase text-[10px]">
                                    <th class="px-3 py-2">No</th>
                                    <th class="px-3 py-2">Kode</th>
                                    <th class="px-3 py-2">Tanggal</th>
                                    <th class="px-3 py-2">Bulan & Tahun</th>
                                    <th class="px-3 py-2">Nominal</th>
                                    <th class="px-3 py-2">Petugas</th>
                                    <th class="px-3 py-2">Status</th>
                                    <th class="px-3 py-2 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php $noLain = 1; foreach ($transaksiTahunLain as $r): ?>
                                <tr class="hover:bg-slate-50">
                                    <td class="px-3 py-2 text-slate-400 font-mono text-[11px]"><?= $noLain++ ?></td>
                                    <td class="px-3 py-2 font-mono text-orange-600 font-semibold"><?= htmlspecialchars($r['kode_transaksi']) ?></td>
                                    <td class="px-3 py-2 text-slate-600"><?= date('d/m/Y', strtotime($r['tgl_bayar'])) ?></td>
                                    <td class="px-3 py-2 font-semibold text-slate-800"><?= htmlspecialchars($r['bulan_dibayar']) ?> <?= htmlspecialchars($r['tahun_dibayar']) ?></td>
                                    <td class="px-3 py-2 font-mono font-semibold text-slate-900"><?= formatRupiah($r['jumlah_bayar']) ?></td>
                                    <td class="px-3 py-2 text-slate-600"><?= htmlspecialchars($r['nama_petugas']) ?></td>
                                    <td class="px-3 py-2">
                                        <?php if ($r['status_verifikasi'] === 'Terverifikasi'): ?>
                                            <span class="text-emerald-700 font-semibold bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200 text-[10px]">Terverifikasi</span>
                                        <?php else: ?>
                                            <span class="text-amber-700 font-semibold bg-amber-50 px-2 py-0.5 rounded border border-amber-200 text-[10px]">Menunggu</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-3 py-2 text-center">
                                        <a href="index.php?page=detail_pembayaran&q=<?= urlencode($r['kode_transaksi']) ?>" 
                                           class="px-2 py-0.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded text-[10px] font-medium border border-slate-300">
                                            Kuitansi
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>

            <?php else: ?>
                <div class="p-3 rounded-lg bg-rose-50 border border-rose-200 text-rose-700 text-xs">
                    Siswa dengan NISN <?= htmlspecialchars($cariNisn) ?> tidak ditemukan.
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
