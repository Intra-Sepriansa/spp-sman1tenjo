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
$cariNisn       = $cariNisn ?? bersihkanInput($_GET['cari_nisn'] ?? '');
$siswaDitemukan = $siswaDitemukan ?? null;
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

// Rekap sederhana tunggakan semua siswa
$rekapTunggakan = [];
foreach ($daftarSiswa as $s) {
    $nisn = $s['nisn'];
    $tarif = (int)($s['nominal_spp'] ?? 0);
    $terbayar = array_filter($semuaTerbayar, fn($t) => $t['nisn'] === $nisn && (string)$t['tahun_dibayar'] === (string)$tahunAktif && $t['status_verifikasi'] === 'Terverifikasi');
    $lunasBln = array_map(fn($t) => $t['bulan_dibayar'], $terbayar);
    $unpaid   = array_values(array_diff($daftarBulan, $lunasBln));
    
    $rekapTunggakan[] = [
        'nisn'            => $nisn,
        'nama'            => $s['nama'],
        'nama_kelas'      => $s['nama_kelas'],
        'nominal_spp'     => $tarif,
        'bulan_belum'     => $unpaid,
        'total_tunggakan' => count($unpaid) * $tarif,
    ];
}

$pageTitle = 'Cek Pembayaran';
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
                            <span class="font-medium text-slate-800 ml-1"><?= htmlspecialchars($siswaDitemukan['nama_kelas']) ?> (<?= htmlspecialchars($siswaDitemukan['kompetensi_keahlian']) ?>)</span>
                        </div>
                        <div>
                            <span class="text-slate-500">Tarif SPP:</span>
                            <span class="font-bold font-mono text-slate-900 ml-1"><?= formatRupiah($siswaDitemukan['nominal_spp']) ?> / bulan</span>
                        </div>
                    </div>
                </div>

                <!-- Tabel Status Pembayaran SPP 12 Bulan Siswa -->
                <div class="mb-5">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">
                            Status Pembayaran SPP 12 Bulan (Tahun <?= $tahunAktif ?>)
                        </h4>
                        <div class="text-xs font-semibold flex items-center space-x-2">
                            <span><span class="text-slate-500 font-normal">Tunggakan:</span> <span class="text-rose-600 font-mono font-bold"><?= formatRupiah($totalTunggakanSiswa) ?></span> <span class="text-slate-400 font-normal">(<?= count($bulanBelum) ?> bln)</span></span>
                            <?php if (!empty($bulanBelum)): ?>
                            <a href="index.php?page=pembayaran&nisn=<?= $siswaDitemukan['nisn'] ?>&bulan=<?= implode(',', $bulanBelum) ?>" 
                               class="px-2.5 py-1 bg-orange-600 hover:bg-orange-700 text-white rounded text-[11px] font-semibold transition cursor-pointer shadow-xs">
                                Bayar Sekaligus &rarr;
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="rounded-lg border border-slate-200 overflow-hidden">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-semibold uppercase text-[10px]">
                                    <th class="px-3 py-2 w-12 text-center">No</th>
                                    <th class="px-3 py-2">Bulan</th>
                                    <th class="px-3 py-2">Tarif SPP</th>
                                    <th class="px-3 py-2">Status</th>
                                    <th class="px-3 py-2 text-center w-28">Aksi</th>
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
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-3 py-2 text-center text-slate-400 font-mono text-[11px]"><?= $no++ ?></td>
                                        <td class="px-3 py-2 font-semibold text-slate-800"><?= $bln ?></td>
                                        <td class="px-3 py-2 font-mono text-slate-700"><?= formatRupiah($tarifPerBulan) ?></td>
                                        <td class="px-3 py-2">
                                            <?php if ($trx && $trx['status_verifikasi'] === 'Terverifikasi'): ?>
                                                <span class="text-emerald-700 font-semibold bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200 text-[10px]">Lunas</span>
                                            <?php elseif ($trx && $trx['status_verifikasi'] === 'Menunggu Verifikasi'): ?>
                                                <span class="text-amber-700 font-semibold bg-amber-50 px-2 py-0.5 rounded border border-amber-200 text-[10px]">Menunggu Verifikasi</span>
                                            <?php else: ?>
                                                <span class="text-rose-700 font-semibold bg-rose-50 px-2 py-0.5 rounded border border-rose-200 text-[10px]">Belum Bayar</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-3 py-2 text-center">
                                            <?php if ($trx && $trx['status_verifikasi'] === 'Terverifikasi'): ?>
                                                <span class="text-slate-400 text-[11px]">-</span>
                                            <?php elseif ($trx && $trx['status_verifikasi'] === 'Menunggu Verifikasi'): ?>
                                                <span class="text-amber-600 text-[11px] font-medium">Proses</span>
                                            <?php else: ?>
                                                <a href="index.php?page=pembayaran&nisn=<?= $siswaDitemukan['nisn'] ?>&bulan=<?= $bln ?>" 
                                                   class="px-2.5 py-1 bg-orange-600 hover:bg-orange-700 text-white rounded text-[11px] font-medium inline-block transition">
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

                <!-- Tabel Riwayat Transaksi Siswa -->
                <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-2">Riwayat Pembayaran Siswa</h4>
                <div class="rounded-lg border border-slate-200 overflow-hidden">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-semibold uppercase text-[10px]">
                                <th class="px-3 py-2">No</th>
                                <th class="px-3 py-2">Kode</th>
                                <th class="px-3 py-2">Tanggal</th>
                                <th class="px-3 py-2">Bulan & Tahun</th>
                                <th class="px-3 py-2">Nominal</th>
                                <th class="px-3 py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php if (empty($riwayatSiswa)): ?>
                                <tr><td colspan="6" class="px-3 py-4 text-center text-slate-400">Belum ada riwayat transaksi pembayaran untuk siswa ini.</td></tr>
                            <?php else: ?>
                                <?php $no = 1; foreach ($riwayatSiswa as $r): ?>
                                <tr class="hover:bg-slate-50">
                                    <td class="px-3 py-2 text-slate-400 font-mono text-[11px]"><?= $no++ ?></td>
                                    <td class="px-3 py-2 font-mono text-orange-600 font-semibold"><?= htmlspecialchars($r['kode_transaksi']) ?></td>
                                    <td class="px-3 py-2 text-slate-600"><?= date('d/m/Y', strtotime($r['tgl_bayar'])) ?></td>
                                    <td class="px-3 py-2 font-semibold text-slate-800"><?= htmlspecialchars($r['bulan_dibayar']) ?> <?= htmlspecialchars($r['tahun_dibayar']) ?></td>
                                    <td class="px-3 py-2 font-mono font-semibold text-slate-900"><?= formatRupiah($r['jumlah_bayar']) ?></td>
                                    <td class="px-3 py-2">
                                        <?php if ($r['status_verifikasi'] === 'Terverifikasi'): ?>
                                            <span class="text-emerald-700 font-semibold bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200 text-[10px]">Terverifikasi</span>
                                        <?php else: ?>
                                            <span class="text-amber-700 font-semibold bg-amber-50 px-2 py-0.5 rounded border border-amber-200 text-[10px]">Menunggu</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            <?php else: ?>
                <div class="p-3 rounded-lg bg-rose-50 border border-rose-200 text-rose-700 text-xs">
                    Siswa dengan NISN <?= htmlspecialchars($cariNisn) ?> tidak ditemukan.
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<!-- 2. Tabel Daftar Siswa & Tunggakan SPP (Tahun Berjalan) -->
<div class="bg-white rounded-xl shadow-xs border border-slate-300/80 overflow-hidden mb-6">
    <div class="px-5 py-3.5 border-b border-slate-100 flex items-center justify-between">
        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">
            Daftar Siswa & Bulan Belum Bayar (Tahun <?= $tahunAktif ?>)
        </h4>
        <span class="text-xs text-slate-500 font-medium"><?= count($rekapTunggakan) ?> Siswa</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-xs">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-semibold uppercase text-[10px] tracking-wider">
                    <th class="px-4 py-3 w-12 text-center">No</th>
                    <th class="px-4 py-3">NISN & Nama Siswa</th>
                    <th class="px-4 py-3">Kelas</th>
                    <th class="px-4 py-3">Tarif / Bln</th>
                    <th class="px-4 py-3">Bulan Belum Dibayar</th>
                    <th class="px-4 py-3 text-right">Total Tunggakan</th>
                    <th class="px-4 py-3 text-center w-28">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php $no = 1; foreach ($rekapTunggakan as $rt): ?>
                <tr class="hover:bg-slate-50/70 transition-colors">
                    <td class="px-4 py-3 text-center text-slate-400 font-mono text-[11px]"><?= $no++ ?></td>
                    <td class="px-4 py-3">
                        <a href="index.php?page=cek_pembayaran&cari_nisn=<?= $rt['nisn'] ?>" class="font-bold text-slate-900 hover:text-orange-600 transition">
                            <?= htmlspecialchars($rt['nama']) ?>
                        </a>
                        <span class="text-slate-400 font-mono text-[10px] block">NISN: <?= htmlspecialchars($rt['nisn']) ?></span>
                    </td>
                    <td class="px-4 py-3 text-slate-700"><?= htmlspecialchars($rt['nama_kelas']) ?></td>
                    <td class="px-4 py-3 font-mono text-slate-800"><?= formatRupiah($rt['nominal_spp']) ?></td>
                    <td class="px-4 py-3 text-slate-700">
                        <?php if (empty($rt['bulan_belum'])): ?>
                            <span class="text-emerald-600 font-semibold">Lunas Semua</span>
                        <?php else: ?>
                            <span><?= implode(', ', $rt['bulan_belum']) ?></span>
                            <span class="text-rose-600 font-semibold text-[11px]">(<?= count($rt['bulan_belum']) ?> bln)</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3 text-right font-mono font-semibold <?= $rt['total_tunggakan'] > 0 ? 'text-rose-600' : 'text-emerald-600' ?>">
                        <?= formatRupiah($rt['total_tunggakan']) ?>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex items-center justify-center space-x-1.5">
                            <a href="index.php?page=cek_pembayaran&cari_nisn=<?= $rt['nisn'] ?>" 
                               class="px-2 py-1 rounded bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium text-[11px] border border-slate-300 transition">
                                Cek
                            </a>
                            <?php if (!empty($rt['bulan_belum'])): ?>
                            <a href="index.php?page=pembayaran&nisn=<?= $rt['nisn'] ?>&bulan=<?= implode(',', $rt['bulan_belum']) ?>" 
                               class="px-2 py-1 rounded bg-orange-600 hover:bg-orange-700 text-white font-medium text-[11px] transition"
                               title="Bayar Tunggakan Siswa Ini">
                                Bayar
                            </a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
