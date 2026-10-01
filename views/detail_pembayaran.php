<?php
/**
 * Halaman Detail Pembayaran
 * Fungsi: Menampilkan seluruh riwayat transaksi SPP, filter pencarian, dan cetak kuitansi resmi
 */

// Jika file ini diakses langsung dari URL browser (bukan lewat index.php), arahkan otomatis ke index.php
if (!isset($daftarPembayaran)) {
    header('Location: ../index.php?page=detail_pembayaran');
    exit;
}

$pageTitle = 'Histori Pembayaran';
require_once __DIR__ . '/layouts/header.php';

$filterBulan = $_GET['bulan'] ?? '';
$cari        = $_GET['q'] ?? '';
$daftarBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
?>

<!-- 1. Filter Pencarian Bulan & Kata Kunci (no-print) -->
<div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 no-print">
    <div class="flex items-center space-x-2">
        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Histori Pembayaran Siswa</h3>
        <span class="px-2 py-0.5 rounded text-[10px] font-mono font-semibold bg-slate-100 text-slate-600 border border-slate-200">
            <?= count($daftarPembayaran) ?> Transaksi
        </span>
    </div>
    <!-- Form filter berdasarkan bulan dan pencarian nama/kode -->
    <form action="index.php" method="GET" class="flex items-center space-x-2 text-xs">
        <input type="hidden" name="page" value="detail_pembayaran">
        <select name="bulan" onchange="this.form.submit()" 
                class="px-3 py-1.5 rounded-lg border border-slate-300 bg-white text-slate-800 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 focus:outline-none transition shadow-xs cursor-pointer">
            <option value="">Semua Bulan</option>
            <?php foreach ($daftarBulan as $b): ?>
                <option value="<?= $b ?>" <?= $filterBulan === $b ? 'selected' : '' ?>><?= $b ?></option>
            <?php endforeach; ?>
        </select>
        <div class="relative">
            <input type="text" name="q" value="<?= htmlspecialchars($cari) ?>" placeholder="Cari siswa / kode..." 
                   class="pl-8 pr-3 py-1.5 rounded-lg border border-slate-300 w-44 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 focus:outline-none transition shadow-xs">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400">
                <?= renderIcon('search', 'w-3.5 h-3.5') ?>
            </div>
        </div>
        <button type="submit" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold border border-slate-300 transition cursor-pointer">
            Cari
        </button>
        <?php if (!empty($filterBulan) || !empty($cari)): ?>
            <a href="index.php?page=detail_pembayaran" class="px-2.5 py-1.5 text-slate-500 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 rounded-lg transition font-medium border border-slate-200">Reset</a>
        <?php endif; ?>
    </form>
</div>

<!-- 2. Tabel Histori Seluruh Transaksi Pembayaran (no-print) -->
<div class="bg-white rounded-xl shadow-xs border border-slate-300/80 overflow-hidden no-print">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-xs whitespace-nowrap">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-semibold uppercase text-[10px] tracking-wider">
                    <th class="px-4 py-3 whitespace-nowrap">Kode</th>
                    <th class="px-4 py-3 whitespace-nowrap">Tanggal</th>
                    <th class="px-4 py-3 whitespace-nowrap">Siswa</th>
                    <th class="px-4 py-3 whitespace-nowrap">Kelas</th>
                    <th class="px-4 py-3 whitespace-nowrap">Periode</th>
                    <th class="px-4 py-3 whitespace-nowrap">Jumlah Bayar</th>
                    <th class="px-4 py-3 whitespace-nowrap">Metode</th>
                    <th class="px-4 py-3 whitespace-nowrap">Status</th>
                    <th class="px-4 py-3 whitespace-nowrap">Petugas</th>
                    <th class="px-4 py-3 text-center whitespace-nowrap w-24">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (empty($daftarPembayaran)): ?>
                <tr>
                    <td colspan="10" class="px-4 py-8 text-center text-slate-400 font-medium">Tidak ada data transaksi pembayaran yang sesuai.</td>
                </tr>
                <?php else: ?>
                    <?php foreach ($daftarPembayaran as $p): ?>
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="font-mono font-semibold text-xs text-orange-600 bg-orange-50 px-2 py-0.5 rounded border border-orange-200 whitespace-nowrap inline-block"><?= htmlspecialchars($p['kode_transaksi']) ?></span>
                        </td>
                        <td class="px-4 py-3 text-slate-600 font-medium whitespace-nowrap"><?= date('d/m/Y', strtotime($p['tgl_bayar'])) ?></td>
                        <td class="px-4 py-3 font-semibold text-slate-900 whitespace-nowrap"><?= htmlspecialchars($p['nama_siswa']) ?></td>
                        <td class="px-4 py-3 text-slate-600 font-medium whitespace-nowrap"><?= htmlspecialchars($p['nama_kelas']) ?></td>
                        <td class="px-4 py-3 font-medium text-slate-700 whitespace-nowrap"><?= htmlspecialchars($p['bulan_dibayar']) ?> <?= htmlspecialchars($p['tahun_dibayar']) ?></td>
                        <td class="px-4 py-3 text-slate-600 font-medium whitespace-nowrap">
                            <span class="inline-block whitespace-nowrap"><?= htmlspecialchars($p['metode_pembayaran']) ?></span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <?php if ($p['status_verifikasi'] === 'Terverifikasi'): ?>
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200 whitespace-nowrap">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Terverifikasi
                                </span>
                            <?php elseif ($p['status_verifikasi'] === 'Ditolak'): ?>
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-rose-50 text-rose-700 border border-rose-200 whitespace-nowrap">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                    Ditolak
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-amber-50 text-amber-700 border border-amber-200 whitespace-nowrap">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    Menunggu
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3 text-slate-500 text-[11px] font-medium whitespace-nowrap"><?= htmlspecialchars($p['nama_petugas']) ?></td>
                        <td class="px-4 py-3 text-center whitespace-nowrap">
                            <div class="inline-flex items-center justify-center space-x-1.5">
                                <!-- Tombol Cetak Kuitansi Langsung (Icon Printer) -->
                                <button type="button" onclick='cetakLangsung(<?= json_encode($p) ?>)' 
                                        class="p-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-300 transition cursor-pointer shadow-xs inline-flex items-center justify-center" 
                                        title="Cetak Kuitansi Transaksi">
                                    <?= renderIcon('printer', 'w-4 h-4') ?>
                                </button>
                                <!-- Tombol Hapus Transaksi (Icon Trash, Khusus Admin) -->
                                <?php if (strtolower($userLogin['level'] ?? '') === 'admin'): ?>
                                <button type="button" onclick="konfirmasiHapus('index.php?action=pembayaran_hapus&id=<?= $p['id_pembayaran'] ?>', 'Hapus transaksi <?= htmlspecialchars($p['kode_transaksi']) ?>?')" 
                                        class="p-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 transition cursor-pointer shadow-xs inline-flex items-center justify-center" 
                                        title="Hapus Transaksi">
                                    <?= renderIcon('trash', 'w-4 h-4') ?>
                                </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ==================================================================== -->
<!-- 3. TEMPLAT FORMAL KUITANSI SMAN 1 TENJO (HANYA DITAMPILKAN SAAT PRINT) -->
<!-- ==================================================================== -->
<div id="printKuitansi" style="display: none;">
    <div style="max-width: 190mm; margin: 0 auto; font-family: 'Times New Roman', serif; font-size: 11pt; color: #000; line-height: 1.35;">
        
        <!-- KOP SURAT RESMI SMAN 1 TENJO -->
        <div style="text-align: center; border-bottom: 3px double #000; padding-bottom: 8px; margin-bottom: 14px;">
            <p style="font-size: 11pt; font-weight: bold; text-transform: uppercase; margin: 0;">PEMERINTAH DAERAH PROVINSI JAWA BARAT</p>
            <p style="font-size: 11pt; font-weight: bold; text-transform: uppercase; margin: 0;">DINAS PENDIDIKAN</p>
            <p style="font-size: 10pt; font-weight: bold; text-transform: uppercase; margin: 0;">CABANG DINAS PENDIDIKAN WILAYAH I</p>
            <h1 style="font-size: 16pt; font-weight: bold; text-transform: uppercase; margin: 2px 0;">SMA NEGERI 1 TENJO</h1>
            <p style="font-size: 9pt; margin: 0;">Jl. Raya Babakan Tenjo, Kec. Tenjo, Kab. Bogor, Jawa Barat 16370</p>
            <p style="font-size: 8.5pt; margin: 0;">Laman: www.sman1tenjo.sch.id &bull; Pos-el: info@sman1tenjo.sch.id</p>
        </div>

        <!-- JUDUL DOKUMEN -->
        <div style="text-align: center; margin: 12px 0 16px 0;">
            <h2 style="font-size: 13pt; font-weight: bold; text-decoration: underline; text-transform: uppercase; margin: 0;">KUITANSI PEMBAYARAN SPP</h2>
            <p style="font-size: 10pt; font-family: Arial, sans-serif; margin-top: 3px; font-weight: bold;">Nomor: <span id="pr_kode">-</span></p>
        </div>

        <!-- TABEL RINCIAN PEMBAYARAN FORMAL (TANPA IKON) -->
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 14px; font-size: 10.5pt;">
            <tr>
                <td style="width: 28%; padding: 4px 0;">Telah Diterima Dari</td>
                <td style="width: 3%; text-align: center;">:</td>
                <td style="width: 69%; font-weight: bold;"><span id="pr_siswa">-</span></td>
            </tr>
            <tr>
                <td style="padding: 4px 0;">NISN</td>
                <td style="text-align: center;">:</td>
                <td><span id="pr_nisn">-</span></td>
            </tr>
            <tr>
                <td style="padding: 4px 0;">Kelas</td>
                <td style="text-align: center;">:</td>
                <td><span id="pr_kelas">-</span></td>
            </tr>
            <tr>
                <td style="padding: 4px 0;">Untuk Pembayaran</td>
                <td style="text-align: center;">:</td>
                <td>Iuran SPP Bulan <strong><span id="pr_periode">-</span></strong></td>
            </tr>
            <tr>
                <td style="padding: 4px 0;">Tanggal Pembayaran</td>
                <td style="text-align: center;">:</td>
                <td><span id="pr_tgl">-</span></td>
            </tr>
            <tr>
                <td style="padding: 4px 0;">Metode Pembayaran</td>
                <td style="text-align: center;">:</td>
                <td><span id="pr_metode">-</span></td>
            </tr>
            <tr>
                <td style="padding: 4px 0;">Status Transaksi</td>
                <td style="text-align: center;">:</td>
                <td><strong><span id="pr_status">-</span></strong></td>
            </tr>
        </table>

        <!-- KOTAK JUMLAH & TERBILANG -->
        <div style="border: 1.5px solid #000; padding: 10px 14px; margin: 14px 0; background: #fff;">
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #ccc; padding-bottom: 6px; margin-bottom: 6px; font-weight: bold;">
                <span>JUMLAH:</span>
                <span id="pr_nominal" style="font-size: 13pt; font-family: Arial, sans-serif;">Rp 0,-</span>
            </div>
            <div style="font-size: 10pt; font-style: italic;">
                Terbilang: <strong># <span id="pr_terbilang">-</span> #</strong>
            </div>
        </div>

        <!-- KETERANGAN RESMI -->
        <p style="font-size: 8.5pt; font-style: italic; color: #333; margin: 14px 0 24px 0;">
            * Kuitansi ini merupakan bukti pembayaran sah yang diterbitkan melalui Sistem SPP Digital SMAN 1 TENJO. Harap disimpan dengan baik.
        </p>

        <!-- PENGESAHAN TANDA TANGAN (PAS 1 LEMBAR) -->
        <table style="width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 10pt;">
            <tr>
                <td style="width: 50%; text-align: center; vertical-align: top;">
                    Siswa / Penyetor,
                    <div style="height: 55px;"></div>
                    <p style="font-weight: bold; text-decoration: underline;">( <span id="pr_ttd_siswa">............................................</span> )</p>
                </td>
                <td style="width: 50%; text-align: center; vertical-align: top;">
                    Bogor, <span id="pr_tgl_cetak"><?= date('d F Y') ?></span><br>
                    Petugas Loket Pembayaran,
                    <div style="height: 55px;"></div>
                    <p style="font-weight: bold; text-decoration: underline;">( <span id="pr_petugas">Petugas Administrasi</span> )</p>
                    <p style="font-size: 8.5pt; font-family: Arial, sans-serif; color: #444;">SMAN 1 TENJO</p>
                </td>
            </tr>
        </table>
    </div>
</div>

<!-- ==================================================================== -->
<!-- 4. GAYA CETAK OTOMATIS: PAS 1 LEMBAR & HANYA TAMPILKAN KUITANSI -->
<!-- ==================================================================== -->
<style>
@media print {
    @page {
        size: A4 portrait;
        margin: 12mm 15mm;
    }
    html, body {
        background: #fff !important;
        color: #000 !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    /* Sembunyikan semua elemen web, navigasi, sidebar, header, tabel web */
    body * {
        visibility: hidden !important;
    }
    .no-print {
        display: none !important;
    }
    /* Hanya tampilkan area kuitansi formal SMAN 1 TENJO */
    #printKuitansi, #printKuitansi * {
        visibility: visible !important;
    }
    #printKuitansi {
        display: block !important;
        position: absolute !important;
        left: 0 !important;
        top: 0 !important;
        width: 100% !important;
        background: #fff !important;
        padding: 0 !important;
        margin: 0 !important;
    }
}
</style>

<!-- ==================================================================== -->
<!-- 5. JAVASCRIPT: CETAK LANGSUNG KE DIALOG PRINT BROWSER (TANPA HALAMAN BARU) -->
<!-- ==================================================================== -->
<script>
// Fungsi terbilang rupiah murni JavaScript
function terbilangJs(angka) {
    angka = Math.abs(parseInt(angka, 10)) || 0;
    const baca = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas'];
    let hasil = '';
    if (angka < 12) {
        hasil = ' ' + baca[angka];
    } else if (angka < 20) {
        hasil = terbilangJs(angka - 10) + ' Belas';
    } else if (angka < 100) {
        hasil = terbilangJs(Math.floor(angka / 10)) + ' Puluh ' + terbilangJs(angka % 10);
    } else if (angka < 200) {
        hasil = ' Seratus ' + terbilangJs(angka - 100);
    } else if (angka < 1000) {
        hasil = terbilangJs(Math.floor(angka / 100)) + ' Ratus ' + terbilangJs(angka % 100);
    } else if (angka < 2000) {
        hasil = ' Seribu ' + terbilangJs(angka - 1000);
    } else if (angka < 1000000) {
        hasil = terbilangJs(Math.floor(angka / 1000)) + ' Ribu ' + terbilangJs(angka % 1000);
    } else if (angka < 1000000000) {
        hasil = terbilangJs(Math.floor(angka / 1000000)) + ' Juta ' + terbilangJs(angka % 1000000);
    }
    return hasil.replace(/\s+/g, ' ').trim();
}

// Konversi tanggal YYYY-MM-DD ke format formal Indonesia
function formatTglIndo(tglStr) {
    if (!tglStr) return '-';
    const bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    const p = tglStr.split('-');
    if (p.length === 3) {
        const b = bulan[parseInt(p[1], 10) - 1] || p[1];
        return parseInt(p[2], 10) + ' ' + b + ' ' + p[0];
    }
    return tglStr;
}

// Handler saat tombol Cetak diklik: langsung isi data & panggil window.print()
function cetakLangsung(d) {
    document.getElementById('pr_kode').innerText = d.kode_transaksi;
    document.getElementById('pr_siswa').innerText = d.nama_siswa;
    document.getElementById('pr_nisn').innerText = d.nisn;
    document.getElementById('pr_kelas').innerText = d.nama_kelas;
    document.getElementById('pr_periode').innerText = d.bulan_dibayar + ' ' + d.tahun_dibayar;
    document.getElementById('pr_tgl').innerText = formatTglIndo(d.tgl_bayar);
    document.getElementById('pr_metode').innerText = d.metode_pembayaran;
    document.getElementById('pr_status').innerText = d.status_verifikasi ? d.status_verifikasi.toUpperCase() : 'LUNAS';
    document.getElementById('pr_nominal').innerText = 'Rp ' + Number(d.jumlah_bayar).toLocaleString('id-ID') + ',-';
    document.getElementById('pr_terbilang').innerText = terbilangJs(d.jumlah_bayar) + ' Rupiah';
    document.getElementById('pr_ttd_siswa').innerText = d.nama_siswa;
    document.getElementById('pr_petugas').innerText = d.nama_petugas;

    // Langsung buka dialog print printer / PDF browser tanpa reload dan tanpa halaman baru
    window.print();
}
</script>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
