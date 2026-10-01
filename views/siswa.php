<?php
/**
 * Halaman Data Siswa
 * Fungsi: Manajemen data siswa sekolah (tambah, lihat, ubah, dan hapus)
 */

// Jika file ini diakses langsung dari URL browser (bukan lewat index.php), arahkan otomatis ke index.php
if (!isset($daftarSiswa)) {
    header('Location: ../index.php?page=siswa');
    exit;
}

$pageTitle = 'Data Siswa';
require_once __DIR__ . '/layouts/header.php';
?>

<!-- 1. Toolbar: Jumlah siswa, Form Pencarian, dan Tombol Tambah Siswa -->
<div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
    <div class="flex items-center space-x-2">
        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Daftar Siswa Terdaftar</h3>
        <span class="px-2 py-0.5 rounded text-[10px] font-mono font-semibold bg-slate-100 text-slate-600 border border-slate-200">
            <?= count($daftarSiswa) ?> Siswa
        </span>
    </div>
    <div class="flex items-center space-x-2">
        <!-- Form pencarian siswa berdasarkan nama atau NISN -->
        <form action="index.php" method="GET" class="flex items-center space-x-1.5">
            <input type="hidden" name="page" value="siswa">
            <div class="relative">
                <input type="text" name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" placeholder="Cari NISN / Nama..." 
                       class="pl-8 pr-3 py-1.5 text-xs rounded-lg border border-slate-300 w-48 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 focus:outline-none transition shadow-xs">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400">
                    <?= renderIcon('search', 'w-3.5 h-3.5') ?>
                </div>
            </div>
            <button type="submit" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold border border-slate-300 transition cursor-pointer">
                Cari
            </button>
            <?php if (!empty($_GET['q'])): ?>
                <a href="index.php?page=siswa" class="px-2.5 py-1.5 text-slate-500 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 rounded-lg transition text-xs font-medium border border-slate-200">Reset</a>
            <?php endif; ?>
        </form>
        <!-- Tombol untuk membuka modal popup tambah siswa -->
        <button type="button" onclick="bukaTambah()" class="px-3.5 py-1.5 bg-orange-600 hover:bg-orange-700 active:bg-orange-800 text-white rounded-lg text-xs font-semibold shadow-xs flex items-center space-x-1.5 transition cursor-pointer">
            <?= renderIcon('plus', 'w-3.5 h-3.5') ?>
            <span>Tambah Siswa</span>
        </button>
    </div>
</div>

<!-- 2. Tabel Data Siswa -->
<div class="bg-white rounded-xl shadow-xs border border-slate-300/80 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-xs">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-semibold uppercase text-[10px] tracking-wider">
                    <th class="px-4 py-3">NISN / NIS</th>
                    <th class="px-4 py-3">Nama Siswa</th>
                    <th class="px-4 py-3">Kelas & Rombel</th>
                    <th class="px-4 py-3">No. Telepon</th>
                    <th class="px-4 py-3">Alamat</th>
                    <th class="px-4 py-3 text-center w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (empty($daftarSiswa)): ?>
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-slate-400 font-medium">Tidak ada data siswa yang tercatat.</td>
                </tr>
                <?php else: ?>
                    <?php foreach ($daftarSiswa as $s): ?>
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="px-4 py-3">
                            <span class="font-mono font-medium text-xs text-slate-900 bg-slate-100 px-2 py-0.5 rounded border border-slate-200 inline-block"><?= htmlspecialchars($s['nisn']) ?></span>
                            <span class="text-[10px] text-slate-400 block mt-0.5 font-mono">NIS: <?= htmlspecialchars($s['nis']) ?></span>
                        </td>
                        <td class="px-4 py-3 font-semibold text-slate-900"><?= htmlspecialchars($s['nama']) ?></td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-0.5 rounded bg-slate-100 font-semibold text-slate-700 text-[11px] border border-slate-200"><?= htmlspecialchars($s['nama_kelas']) ?></span>
                        </td>
                        <td class="px-4 py-3 text-slate-600 font-mono text-[11px]"><?= htmlspecialchars($s['no_telp']) ?></td>
                        <td class="px-4 py-3 text-slate-500 max-w-xs truncate"><?= htmlspecialchars($s['alamat']) ?></td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center space-x-1.5">
                                <!-- Tombol direct untuk cek pembayaran siswa ini -->
                                <a href="index.php?page=cek_pembayaran&cari_nisn=<?= urlencode($s['nisn']) ?>" title="Cek Status Pembayaran" 
                                   class="p-1.5 rounded-lg bg-orange-50 text-orange-600 hover:bg-orange-100 border border-orange-200 transition cursor-pointer">
                                    <?= renderIcon('cek', 'w-3.5 h-3.5') ?>
                                </a>
                                <!-- Tombol edit siswa -->
                                <button type="button" onclick='bukaEdit(<?= json_encode($s) ?>)' title="Edit Siswa" 
                                        class="p-1.5 rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200 border border-slate-200 transition cursor-pointer">
                                    <?= renderIcon('pencil', 'w-3.5 h-3.5') ?>
                                </button>
                                <!-- Tombol hapus siswa (hanya admin yang bisa) -->
                                <?php if ($userLogin['level'] === 'admin'): ?>
                                <button type="button" onclick="konfirmasiHapus('index.php?action=siswa_hapus&nisn=<?= urlencode($s['nisn']) ?>', 'Hapus data siswa <?= htmlspecialchars($s['nama']) ?>?')" title="Hapus Siswa" 
                                        class="p-1.5 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 border border-rose-200 transition cursor-pointer">
                                    <?= renderIcon('trash', 'w-3.5 h-3.5') ?>
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

<!-- 3. Modal Popup: Tambah Siswa Baru -->
<div id="modalTambah" class="fixed inset-0 z-50 hidden bg-slate-900/60 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full border border-slate-200 overflow-hidden">
        <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
            <h4 class="font-bold text-slate-900 text-xs uppercase tracking-wider">Tambah Siswa Baru</h4>
            <button type="button" onclick="tutupTambah()" class="text-slate-400 hover:text-slate-600 text-sm leading-none">&times;</button>
        </div>
        <form action="index.php?action=siswa_simpan" method="POST" class="p-5 space-y-3.5 text-xs">
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-slate-700 font-semibold mb-1">NISN (10 Digit)</label>
                    <input type="text" name="nisn" maxlength="10" required placeholder="0061234501" 
                           class="w-full px-3 py-2 rounded-lg border border-slate-300 font-mono text-slate-900 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 focus:outline-none transition shadow-xs">
                </div>
                <div>
                    <label class="block text-slate-700 font-semibold mb-1">NIS (8 Digit)</label>
                    <input type="text" name="nis" maxlength="8" required placeholder="2024001" 
                           class="w-full px-3 py-2 rounded-lg border border-slate-300 font-mono text-slate-900 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 focus:outline-none transition shadow-xs">
                </div>
            </div>
            <div>
                <label class="block text-slate-700 font-semibold mb-1">Nama Lengkap</label>
                <input type="text" name="nama" required placeholder="Nama siswa" 
                       class="w-full px-3 py-2 rounded-lg border border-slate-300 text-slate-900 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 focus:outline-none transition shadow-xs">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-slate-700 font-semibold mb-1">Kelas</label>
                    <select name="id_kelas" required class="w-full px-3 py-2 rounded-lg border border-slate-300 text-slate-900 bg-white focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 focus:outline-none transition shadow-xs cursor-pointer">
                        <?php foreach ($daftarKelas as $k): ?>
                            <option value="<?= $k['id_kelas'] ?>"><?= htmlspecialchars($k['nama_kelas']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-slate-700 font-semibold mb-1">Tarif SPP</label>
                    <select name="id_spp" required class="w-full px-3 py-2 rounded-lg border border-slate-300 text-slate-900 bg-white focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 focus:outline-none transition shadow-xs cursor-pointer">
                        <?php foreach ($daftarSpp as $sp): ?>
                            <option value="<?= $sp['id_spp'] ?>">Thn <?= $sp['tahun'] ?> (<?= formatRupiah($sp['nominal']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-slate-700 font-semibold mb-1">No. Telepon / WhatsApp</label>
                <input type="text" name="no_telp" required placeholder="0812xxxxxxxx" 
                       class="w-full px-3 py-2 rounded-lg border border-slate-300 text-slate-900 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 focus:outline-none transition shadow-xs">
            </div>
            <div>
                <label class="block text-slate-700 font-semibold mb-1">Alamat Lengkap</label>
                <textarea name="alamat" rows="2" required placeholder="Alamat tempat tinggal..." 
                          class="w-full px-3 py-2 rounded-lg border border-slate-300 text-slate-900 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 focus:outline-none transition shadow-xs"></textarea>
            </div>
            <div class="pt-2 flex justify-end space-x-2">
                <button type="button" onclick="tutupTambah()" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg transition cursor-pointer">Batal</button>
                <button type="submit" class="px-4 py-2 bg-orange-600 hover:bg-orange-700 active:bg-orange-800 text-white rounded-lg font-semibold transition cursor-pointer shadow-xs">Simpan Siswa</button>
            </div>
        </form>
    </div>
</div>

<!-- 4. Modal Popup: Edit Data Siswa -->
<div id="modalEdit" class="fixed inset-0 z-50 hidden bg-slate-900/60 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full border border-slate-200 overflow-hidden">
        <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
            <h4 class="font-bold text-slate-900 text-xs uppercase tracking-wider">Perbarui Data Siswa</h4>
            <button type="button" onclick="tutupEdit()" class="text-slate-400 hover:text-slate-600 text-sm leading-none">&times;</button>
        </div>
        <form action="index.php?action=siswa_update" method="POST" class="p-5 space-y-3.5 text-xs">
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-slate-700 font-semibold mb-1">NISN (Tetap)</label>
                    <input type="text" id="edit_nisn" name="nisn" readonly 
                           class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-slate-100 font-mono text-slate-500 cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-slate-700 font-semibold mb-1">NIS</label>
                    <input type="text" id="edit_nis" name="nis" maxlength="8" required 
                           class="w-full px-3 py-2 rounded-lg border border-slate-300 font-mono text-slate-900 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 focus:outline-none transition shadow-xs">
                </div>
            </div>
            <div>
                <label class="block text-slate-700 font-semibold mb-1">Nama Lengkap</label>
                <input type="text" id="edit_nama" name="nama" required 
                       class="w-full px-3 py-2 rounded-lg border border-slate-300 text-slate-900 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 focus:outline-none transition shadow-xs">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-slate-700 font-semibold mb-1">Kelas</label>
                    <select id="edit_kelas" name="id_kelas" required class="w-full px-3 py-2 rounded-lg border border-slate-300 text-slate-900 bg-white focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 focus:outline-none transition shadow-xs cursor-pointer">
                        <?php foreach ($daftarKelas as $k): ?>
                            <option value="<?= $k['id_kelas'] ?>"><?= htmlspecialchars($k['nama_kelas']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-slate-700 font-semibold mb-1">Tarif SPP</label>
                    <select id="edit_spp" name="id_spp" required class="w-full px-3 py-2 rounded-lg border border-slate-300 text-slate-900 bg-white focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 focus:outline-none transition shadow-xs cursor-pointer">
                        <?php foreach ($daftarSpp as $sp): ?>
                            <option value="<?= $sp['id_spp'] ?>">Thn <?= $sp['tahun'] ?> (<?= formatRupiah($sp['nominal']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-slate-700 font-semibold mb-1">No. Telepon / WhatsApp</label>
                <input type="text" id="edit_telp" name="no_telp" required 
                       class="w-full px-3 py-2 rounded-lg border border-slate-300 text-slate-900 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 focus:outline-none transition shadow-xs">
            </div>
            <div>
                <label class="block text-slate-700 font-semibold mb-1">Alamat Lengkap</label>
                <textarea id="edit_alamat" name="alamat" rows="2" required 
                          class="w-full px-3 py-2 rounded-lg border border-slate-300 text-slate-900 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 focus:outline-none transition shadow-xs"></textarea>
            </div>
            <div class="pt-2 flex justify-end space-x-2">
                <button type="button" onclick="tutupEdit()" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg transition cursor-pointer">Batal</button>
                <button type="submit" class="px-4 py-2 bg-orange-600 hover:bg-orange-700 active:bg-orange-800 text-white rounded-lg font-semibold transition cursor-pointer shadow-xs">Perbarui Siswa</button>
            </div>
        </form>
    </div>
</div>

<!-- 5. Javascript untuk mengontrol buka/tutup modal dan mengisi form saat klik edit -->
<script>
    function bukaTambah() { document.getElementById('modalTambah').classList.remove('hidden'); }
    function tutupTambah() { document.getElementById('modalTambah').classList.add('hidden'); }
    function bukaEdit(d) {
        document.getElementById('edit_nisn').value = d.nisn;
        document.getElementById('edit_nis').value = d.nis;
        document.getElementById('edit_nama').value = d.nama;
        document.getElementById('edit_kelas').value = d.id_kelas;
        document.getElementById('edit_spp').value = d.id_spp;
        document.getElementById('edit_telp').value = d.no_telp;
        document.getElementById('edit_alamat').value = d.alamat;
        document.getElementById('modalEdit').classList.remove('hidden');
    }
    function tutupEdit() { document.getElementById('modalEdit').classList.add('hidden'); }
</script>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
