<?php
/**
 * Halaman Data Kelas
 * Fungsi: Manajemen data rombongan belajar (rombel) dan kompetensi keahlian siswa
 */

// Jika file ini diakses langsung dari URL browser (bukan lewat index.php), arahkan otomatis ke index.php
if (!isset($daftarKelas)) {
    header('Location: ../index.php?page=kelas');
    exit;
}

$pageTitle = 'Data Kelas';
require_once __DIR__ . '/layouts/header.php';
?>

<!-- 1. Toolbar: Total Kelas dan Tombol Tambah -->
<div class="mb-4 flex items-center justify-between">
    <div class="flex items-center space-x-2">
        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Daftar Rombongan Belajar</h3>
        <span class="px-2 py-0.5 rounded text-[10px] font-mono font-semibold bg-slate-100 text-slate-600 border border-slate-200">
            <?= count($daftarKelas) ?> Kelas
        </span>
    </div>
    <button type="button" onclick="bukaTambah()" class="px-3.5 py-1.5 bg-orange-600 hover:bg-orange-700 active:bg-orange-800 text-white rounded-lg text-xs font-semibold shadow-xs flex items-center space-x-1.5 transition cursor-pointer">
        <?= renderIcon('plus', 'w-3.5 h-3.5') ?>
        <span>Tambah Kelas</span>
    </button>
</div>

<!-- 2. Tabel Data Kelas -->
<div class="bg-white rounded-xl shadow-xs border border-slate-300/80 overflow-hidden">
    <table class="w-full text-left border-collapse text-xs">
        <thead>
            <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-semibold uppercase text-[10px] tracking-wider">
                <th class="px-4 py-3 w-12 text-center">No</th>
                <th class="px-4 py-3">Nama Rombel / Kelas</th>
                <th class="px-4 py-3">Kompetensi Keahlian</th>
                <th class="px-4 py-3 text-center w-28">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            <?php if (empty($daftarKelas)): ?>
            <tr><td colspan="4" class="px-4 py-8 text-center text-slate-400 font-medium">Belum ada data rombongan belajar.</td></tr>
            <?php else: ?>
                <?php $no = 1; foreach ($daftarKelas as $k): ?>
                <tr class="hover:bg-slate-50/70 transition-colors">
                    <td class="px-4 py-3 text-center text-slate-400 font-mono text-[11px]"><?= $no++ ?></td>
                    <td class="px-4 py-3 font-semibold text-slate-900 whitespace-nowrap"><?= htmlspecialchars($k['nama_kelas']) ?></td>
                    <td class="px-4 py-3 text-slate-600 font-medium"><?= htmlspecialchars($k['kompetensi_keahlian']) ?></td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex items-center justify-center space-x-1.5">
                            <!-- Tombol edit kelas -->
                            <button type="button" onclick='bukaEdit(<?= json_encode($k) ?>)' title="Edit Kelas" 
                                    class="p-1.5 rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200 border border-slate-200 transition cursor-pointer">
                                <?= renderIcon('pencil', 'w-3.5 h-3.5') ?>
                            </button>
                            <!-- Tombol hapus kelas (khusus level admin) -->
                            <?php if ($userLogin['level'] === 'admin'): ?>
                            <button type="button" onclick="konfirmasiHapus('index.php?action=kelas_hapus&id=<?= $k['id_kelas'] ?>', 'Hapus rombel <?= htmlspecialchars($k['nama_kelas']) ?>?')" title="Hapus Kelas" 
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

<!-- 3. Modal Popup: Tambah Kelas Baru -->
<div id="modalTambah" class="fixed inset-0 z-50 hidden bg-slate-900/60 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-sm w-full border border-slate-200 overflow-hidden">
        <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
            <h4 class="font-bold text-slate-900 text-xs uppercase tracking-wider">Tambah Kelas Baru</h4>
            <button type="button" onclick="tutupTambah()" class="text-slate-400 hover:text-slate-600 text-sm leading-none">&times;</button>
        </div>
        <form action="index.php?action=kelas_simpan" method="POST" class="p-5 space-y-3.5 text-xs">
            <div>
                <label class="block text-slate-700 font-semibold mb-1">Nama Rombel / Kelas</label>
                <input type="text" name="nama_kelas" required placeholder="Contoh: XII RPL 1" 
                       class="w-full px-3 py-2 rounded-lg border border-slate-300 text-slate-900 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 focus:outline-none transition shadow-xs">
            </div>
            <div>
                <label class="block text-slate-700 font-semibold mb-1">Kompetensi Keahlian</label>
                <input type="text" name="kompetensi_keahlian" required placeholder="Contoh: Rekayasa Perangkat Lunak" 
                       class="w-full px-3 py-2 rounded-lg border border-slate-300 text-slate-900 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 focus:outline-none transition shadow-xs">
            </div>
            <div class="pt-2 flex justify-end space-x-2">
                <button type="button" onclick="tutupTambah()" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg transition cursor-pointer">Batal</button>
                <button type="submit" class="px-4 py-2 bg-orange-600 hover:bg-orange-700 active:bg-orange-800 text-white rounded-lg font-semibold transition cursor-pointer shadow-xs">Simpan Rombel</button>
            </div>
        </form>
    </div>
</div>

<!-- 4. Modal Popup: Edit Kelas -->
<div id="modalEdit" class="fixed inset-0 z-50 hidden bg-slate-900/60 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-sm w-full border border-slate-200 overflow-hidden">
        <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
            <h4 class="font-bold text-slate-900 text-xs uppercase tracking-wider">Perbarui Data Kelas</h4>
            <button type="button" onclick="tutupEdit()" class="text-slate-400 hover:text-slate-600 text-sm leading-none">&times;</button>
        </div>
        <form action="index.php?action=kelas_update" method="POST" class="p-5 space-y-3.5 text-xs">
            <input type="hidden" id="edit_id" name="id_kelas">
            <div>
                <label class="block text-slate-700 font-semibold mb-1">Nama Rombel / Kelas</label>
                <input type="text" id="edit_nama" name="nama_kelas" required 
                       class="w-full px-3 py-2 rounded-lg border border-slate-300 text-slate-900 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 focus:outline-none transition shadow-xs">
            </div>
            <div>
                <label class="block text-slate-700 font-semibold mb-1">Kompetensi Keahlian</label>
                <input type="text" id="edit_komp" name="kompetensi_keahlian" required 
                       class="w-full px-3 py-2 rounded-lg border border-slate-300 text-slate-900 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 focus:outline-none transition shadow-xs">
            </div>
            <div class="pt-2 flex justify-end space-x-2">
                <button type="button" onclick="tutupEdit()" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg transition cursor-pointer">Batal</button>
                <button type="submit" class="px-4 py-2 bg-orange-600 hover:bg-orange-700 active:bg-orange-800 text-white rounded-lg font-semibold transition cursor-pointer shadow-xs">Perbarui Rombel</button>
            </div>
        </form>
    </div>
</div>

<!-- 5. Javascript untuk buka/tutup modal edit dan tambah kelas -->
<script>
    function bukaTambah() { document.getElementById('modalTambah').classList.remove('hidden'); }
    function tutupTambah() { document.getElementById('modalTambah').classList.add('hidden'); }
    function bukaEdit(d) {
        document.getElementById('edit_id').value = d.id_kelas;
        document.getElementById('edit_nama').value = d.nama_kelas;
        document.getElementById('edit_komp').value = d.kompetensi_keahlian;
        document.getElementById('modalEdit').classList.remove('hidden');
    }
    function tutupEdit() { document.getElementById('modalEdit').classList.add('hidden'); }
</script>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
