<?php
/**
 * Halaman Form Pembayaran SPP
 * Sekolah: SMAN 1 TENJO
 * Fungsi: Menginput data pembayaran SPP (Mendukung 1 bulan maupun beberapa bulan sekaligus)
 */

// Jika file ini diakses langsung dari URL browser (bukan lewat index.php), arahkan otomatis ke index.php
if (!isset($daftarSiswa)) {
    header('Location: ../index.php?page=pembayaran');
    exit;
}

$periodeTerbayar = $periodeTerbayar ?? [];
$paramNisn       = bersihkanInput($_GET['nisn'] ?? '');
$paramBulan      = bersihkanInput($_GET['bulan'] ?? '');
$pageTitle       = 'Pembayaran';
require_once __DIR__ . '/layouts/header.php';

// Daftar 12 nama bulan untuk pilihan periode SPP
$daftarBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
$tahunAktif  = date('Y');
?>

<div class="max-w-2xl mx-auto">
    <!-- Kartu Form Input Pembayaran -->
    <div class="bg-white rounded-xl shadow-xs border border-slate-300/80 p-6">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-5">
            <div>
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Form Pembayaran SPP</h3>
                <p class="text-xs text-slate-500 mt-0.5">Catat setoran iuran SPP siswa (Bisa bayar beberapa bulan langsung)</p>
            </div>
            <!-- Pintasan link ke histori transaksi -->
            <a href="index.php?page=detail_pembayaran" class="text-xs font-semibold text-orange-600 hover:text-orange-700 transition flex items-center space-x-1">
                <span>Lihat Riwayat</span>
                <span aria-hidden="true">&rarr;</span>
            </a>
        </div>

        <form action="index.php?action=pembayaran_simpan" method="POST" class="space-y-4 text-xs" id="formBayar">
            <!-- 1. Pilihan Siswa -->
            <div>
                <label for="pilih_siswa" class="block text-slate-700 font-semibold mb-1.5">Pilih Siswa</label>
                <div class="relative">
                    <select id="pilih_siswa" name="nisn" required onchange="gantiSiswa()" 
                            class="w-full pl-3 pr-8 py-2.5 rounded-lg border border-slate-300 text-slate-900 bg-white focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 focus:outline-none transition shadow-xs cursor-pointer">
                        <option value="">-- Pilih Siswa dari Database --</option>
                        <?php foreach ($daftarSiswa as $s): ?>
                            <option value="<?= $s['nisn'] ?>" 
                                    <?= $paramNisn === $s['nisn'] ? 'selected' : '' ?>
                                    data-idspp="<?= $s['id_spp'] ?>"
                                    data-nominal="<?= $s['nominal_spp'] ?>"
                                    data-nama="<?= htmlspecialchars($s['nama']) ?>"
                                    data-kelas="<?= htmlspecialchars($s['nama_kelas']) ?>">
                                <?= htmlspecialchars($s['nama']) ?> — <?= $s['nisn'] ?> (<?= htmlspecialchars($s['nama_kelas']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <input type="hidden" id="id_spp_siswa" name="id_spp">
            </div>

            <!-- Ringkasan Profil Siswa Singkat (Muncul Otomatis saat Siswa Dipilih) -->
            <div id="infoSiswaPilihan" class="hidden p-3 bg-slate-50 rounded-lg border border-slate-200 text-slate-700">
                <div class="flex items-center justify-between text-xs">
                    <div>
                        <span class="text-slate-400 font-medium">Siswa:</span>
                        <strong id="labelNamaSiswa" class="text-slate-900 font-semibold ml-1">-</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 font-medium">Tarif SPP:</span>
                        <strong id="labelTarifSiswa" class="text-orange-600 font-mono font-bold ml-1">Rp 0</strong>
                        <span class="text-slate-400">/ bulan</span>
                    </div>
                </div>
            </div>

            <!-- 2. Tanggal Bayar & Tahun Dibayar -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="tgl_bayar" class="block text-slate-700 font-semibold mb-1.5">Tanggal Bayar</label>
                    <input type="date" id="tgl_bayar" name="tgl_bayar" value="<?= date('Y-m-d') ?>" required 
                           class="w-full px-3 py-2 rounded-lg border border-slate-300 text-slate-900 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 focus:outline-none transition shadow-xs">
                </div>
                <div>
                    <label for="tahun_dibayar" class="block text-slate-700 font-semibold mb-1.5">Tahun Dibayar</label>
                    <input type="text" id="tahun_dibayar" name="tahun_dibayar" value="<?= $tahunAktif ?>" required oninput="gantiSiswa()" 
                           class="w-full px-3 py-2 rounded-lg border border-slate-300 text-slate-900 font-mono focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 focus:outline-none transition shadow-xs">
                </div>
            </div>

            <!-- 3. Pilihan Bulan yang Dibayar (Mendukung Pembayaran Beberapa Bulan Sekaligus) -->
            <div>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1.5 mb-2">
                    <label class="block text-slate-700 font-semibold">
                        Pilih Periode Bulan yang Dibayar:
                    </label>
                    <div class="flex items-center space-x-1.5">
                        <button type="button" onclick="pilihSemuaTunggakan()" class="px-2 py-0.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded text-[11px] font-medium border border-slate-300 cursor-pointer">
                            Pilih Semua Tunggakan
                        </button>
                        <button type="button" onclick="pilihSatuBulanTerdekat()" class="px-2 py-0.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded text-[11px] font-medium border border-slate-300 cursor-pointer">
                            1 Bulan Saja
                        </button>
                        <button type="button" onclick="resetPilihanBulan()" class="px-2 py-0.5 text-slate-400 hover:text-slate-600 text-[11px] cursor-pointer">
                            Batal
                        </button>
                    </div>
                </div>

                <!-- Grid Checkbox 12 Bulan -->
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2" id="wadahBulan">
                    <?php foreach ($daftarBulan as $bln): ?>
                    <label id="box_bulan_<?= $bln ?>" class="p-2.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 flex items-center justify-between cursor-pointer transition select-none">
                        <div class="flex items-center space-x-2">
                            <input type="checkbox" name="bulan_dibayar[]" value="<?= $bln ?>" id="chk_<?= $bln ?>" onchange="hitungTotal()" 
                                   class="chk-bulan rounded text-orange-600 focus:ring-orange-500 border-slate-300 w-3.5 h-3.5 cursor-pointer">
                            <span class="font-medium text-slate-800 text-xs"><?= $bln ?></span>
                        </div>
                        <span id="badge_<?= $bln ?>" class="text-[10px] text-slate-400 font-semibold"></span>
                    </label>
                    <?php endforeach; ?>
                </div>

                <!-- Rincian Total Bulan & Nominal Terpilih -->
                <div class="mt-3 p-3 bg-amber-50/70 rounded-lg border border-amber-200 text-xs flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
                    <div>
                        <span class="text-amber-900 font-medium">Bulan Terpilih:</span>
                        <strong id="teksBulanTerpilih" class="text-amber-950 font-semibold ml-1">Belum ada bulan yang dipilih</strong>
                    </div>
                    <div class="sm:text-right">
                        <span class="text-amber-900 font-medium">Total Nominal:</span>
                        <strong id="teksTotalNominal" class="text-rose-700 font-mono font-bold text-sm ml-1">Rp 0</strong>
                    </div>
                </div>
            </div>

            <!-- 4. Metode Bayar (Otomatis Terverifikasi / Lunas Langsung) -->
            <div class="pt-1">
                <label for="metode_pembayaran" class="block text-slate-700 font-semibold mb-1.5">Metode Pembayaran</label>
                <select id="metode_pembayaran" name="metode_pembayaran" required 
                        class="w-full px-3 py-2 rounded-lg border border-slate-300 text-slate-900 bg-white focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 focus:outline-none transition shadow-xs cursor-pointer">
                    <option value="Tunai">Tunai (Kasir Loket Sekolah)</option>
                    <option value="Transfer Bank">Transfer Bank</option>
                </select>
                <input type="hidden" id="status_verifikasi" name="status_verifikasi" value="Terverifikasi">
            </div>

            <!-- 5. Tombol Simpan -->
            <div class="pt-3">
                <button type="submit" id="btnSubmitBayar" class="w-full py-2.5 bg-orange-600 hover:bg-orange-700 active:bg-orange-800 text-white rounded-lg font-semibold shadow-xs flex items-center justify-center space-x-1.5 transition cursor-pointer">
                    <?= renderIcon('check', 'w-4 h-4') ?>
                    <span id="btnSubmitText">Simpan Transaksi Pembayaran</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 6. Script Javascript: Otomatisasi Multi-Bulan & Proteksi Pembayaran Ganda -->
<script>
    const riwayatTerbayar = <?= json_encode($periodeTerbayar) ?>;
    const daftarBulanAsli = <?= json_encode($daftarBulan) ?>;
    let paramBulanUrl = <?= json_encode($paramBulan) ?>;

    function formatRupiahJs(angka) {
        return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function gantiSiswa() {
        const sel = document.getElementById('pilih_siswa');
        const opt = sel.options[sel.selectedIndex];
        const nisn = sel.value;
        const tahun = document.getElementById('tahun_dibayar').value.trim();
        const infoBox = document.getElementById('infoSiswaPilihan');

        if (!nisn) {
            infoBox.classList.add('hidden');
            document.getElementById('id_spp_siswa').value = '';
            resetStateBulan();
            hitungTotal();
            return;
        }

        const idSpp = opt.getAttribute('data-idspp');
        const nominal = parseInt(opt.getAttribute('data-nominal') || 0);
        const nama = opt.getAttribute('data-nama');
        const kelas = opt.getAttribute('data-kelas');

        document.getElementById('id_spp_siswa').value = idSpp;
        document.getElementById('labelNamaSiswa').innerText = `${nama} (${kelas})`;
        document.getElementById('labelTarifSiswa').innerText = formatRupiahJs(nominal);
        infoBox.classList.remove('hidden');

        // Filter riwayat pembayaran siswa pada tahun yang dipilih
        const bayarSiswa = riwayatTerbayar.filter(d => d.nisn === nisn && d.tahun_dibayar === tahun);

        // Update status masing-masing checkbox bulan
        daftarBulanAsli.forEach(bln => {
            const chk = document.getElementById('chk_' + bln);
            const box = document.getElementById('box_bulan_' + bln);
            const badge = document.getElementById('badge_' + bln);
            const trx = bayarSiswa.find(d => d.bulan_dibayar.toLowerCase() === bln.toLowerCase());

            if (trx) {
                // Bulan sudah dibayar/lunas -> disable checkbox
                chk.checked = false;
                chk.disabled = true;
                box.classList.remove('hover:bg-slate-50', 'bg-white', 'border-slate-200', 'border-orange-500', 'bg-orange-50/40');
                box.classList.add('bg-slate-100', 'border-slate-200', 'opacity-60', 'cursor-not-allowed');
                badge.innerText = trx.status_verifikasi === 'Terverifikasi' ? 'Lunas' : 'Menunggu';
                badge.className = 'text-[10px] font-bold ' + (trx.status_verifikasi === 'Terverifikasi' ? 'text-emerald-700' : 'text-amber-700');
            } else {
                // Bulan belum dibayar -> aktifkan
                chk.disabled = false;
                box.classList.remove('bg-slate-100', 'opacity-60', 'cursor-not-allowed');
                box.classList.add('bg-white', 'hover:bg-slate-50');
                badge.innerText = 'Belum';
                badge.className = 'text-[10px] font-semibold text-rose-600';
            }
        });

        // Jika ada param bulan dari URL (misal klik 'Bayar' dari halaman Cek Pembayaran)
        if (paramBulanUrl) {
            const bulanList = paramBulanUrl.split(',').map(s => s.trim().toLowerCase());
            daftarBulanAsli.forEach(bln => {
                const chk = document.getElementById('chk_' + bln);
                if (!chk.disabled && bulanList.includes(bln.toLowerCase())) {
                    chk.checked = true;
                }
            });
            paramBulanUrl = null; // Cukup sekali saat inisialisasi
        } else {
            // Jika belum ada yang dicentang, centang otomatis 1 bulan terdekat yang belum lunas
            pilihSatuBulanTerdekat();
        }

        hitungTotal();
    }

    function resetStateBulan() {
        daftarBulanAsli.forEach(bln => {
            const chk = document.getElementById('chk_' + bln);
            const box = document.getElementById('box_bulan_' + bln);
            const badge = document.getElementById('badge_' + bln);
            chk.checked = false;
            chk.disabled = false;
            box.className = 'p-2.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 flex items-center justify-between cursor-pointer transition select-none';
            badge.innerText = '';
        });
    }

    function pilihSemuaTunggakan() {
        daftarBulanAsli.forEach(bln => {
            const chk = document.getElementById('chk_' + bln);
            if (!chk.disabled) {
                chk.checked = true;
            }
        });
        hitungTotal();
    }

    function pilihSatuBulanTerdekat() {
        let sudahPilih = false;
        daftarBulanAsli.forEach(bln => {
            const chk = document.getElementById('chk_' + bln);
            if (!chk.disabled && !sudahPilih) {
                chk.checked = true;
                sudahPilih = true;
            } else {
                chk.checked = false;
            }
        });
        hitungTotal();
    }

    function resetPilihanBulan() {
        daftarBulanAsli.forEach(bln => {
            const chk = document.getElementById('chk_' + bln);
            if (!chk.disabled) {
                chk.checked = false;
            }
        });
        hitungTotal();
    }

    function hitungTotal() {
        const sel = document.getElementById('pilih_siswa');
        const opt = sel.options[sel.selectedIndex];
        const nominal = parseInt(opt ? opt.getAttribute('data-nominal') || 0 : 0);
        const checkboxes = document.querySelectorAll('.chk-bulan:checked');
        const bulanTerpilih = [];

        checkboxes.forEach(c => {
            bulanTerpilih.push(c.value);
            // Highlight box yang dicentang
            const box = document.getElementById('box_bulan_' + c.value);
            if (box && !c.disabled) {
                box.classList.add('border-orange-500', 'bg-orange-50/40');
            }
        });

        // Un-highlight box yang tidak dicentang
        document.querySelectorAll('.chk-bulan:not(:checked)').forEach(c => {
            const box = document.getElementById('box_bulan_' + c.value);
            if (box && !c.disabled) {
                box.classList.remove('border-orange-500', 'bg-orange-50/40');
            }
        });

        const totalNominal = bulanTerpilih.length * nominal;
        const btnSubmit = document.getElementById('btnSubmitBayar');
        const btnText = document.getElementById('btnSubmitText');

        if (bulanTerpilih.length === 0) {
            document.getElementById('teksBulanTerpilih').innerText = 'Belum ada bulan yang dipilih';
            document.getElementById('teksTotalNominal').innerText = 'Rp 0';
            btnText.innerText = 'Pilih minimal 1 bulan untuk bayar';
            btnSubmit.disabled = true;
            btnSubmit.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            document.getElementById('teksBulanTerpilih').innerText = `${bulanTerpilih.length} Bulan (${bulanTerpilih.join(', ')})`;
            document.getElementById('teksTotalNominal').innerText = formatRupiahJs(totalNominal);
            btnText.innerText = `Bayar ${bulanTerpilih.length} Bulan (${formatRupiahJs(totalNominal)})`;
            btnSubmit.disabled = false;
            btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }

    // Inisialisasi otomatis jika ada parameter nisn dari URL
    window.addEventListener('DOMContentLoaded', () => {
        if (document.getElementById('pilih_siswa').value) {
            gantiSiswa();
        }
    });
</script>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
