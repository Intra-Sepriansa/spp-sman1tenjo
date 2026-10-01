<?php
/**
 * Halaman Data Petugas (Khusus Administrator)
 * Fungsi: Mengelola akun login petugas kasir dan administrator sistem
 */

// Jika file ini diakses langsung dari URL browser (bukan lewat index.php), arahkan otomatis ke index.php
if (!isset($daftarPetugas)) {
    header('Location: ../index.php?page=petugas');
    exit;
}

$pageTitle = 'Data Petugas';
require_once __DIR__ . '/layouts/header.php';
?>

<!-- 1. Toolbar: Total Petugas dan Tombol Tambah Petugas -->
<div class="mb-4 flex items-center justify-between">
    <div class="flex items-center space-x-2">
        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Manajemen Pengguna Petugas</h3>
        <span class="px-2 py-0.5 rounded text-[10px] font-mono font-semibold bg-slate-100 text-slate-600 border border-slate-200">
            <?= count($daftarPetugas) ?> Akun
        </span>
    </div>
    <button type="button" onclick="bukaTambah()" class="px-3.5 py-1.5 bg-orange-600 hover:bg-orange-700 active:bg-orange-800 text-white rounded-lg text-xs font-semibold shadow-xs flex items-center space-x-1.5 transition cursor-pointer">
        <?= renderIcon('plus', 'w-3.5 h-3.5') ?>
        <span>Tambah Petugas</span>
    </button>
</div>

<!-- 2. Tabel Data Petugas -->
<div class="bg-white rounded-xl shadow-xs border border-slate-300/80 overflow-hidden">
    <table class="w-full text-left border-collapse text-xs">
        <thead>
            <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-semibold uppercase text-[10px] tracking-wider">
                <th class="px-4 py-3">Nama Petugas</th>
                <th class="px-4 py-3">Username</th>
                <th class="px-4 py-3">Hak Akses (Level)</th>
                <th class="px-4 py-3 text-center w-28">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            <?php foreach ($daftarPetugas as $p): ?>
            <tr class="hover:bg-slate-50/70 transition-colors">
                <td class="px-4 py-3 font-semibold text-slate-900">
                    <?= htmlspecialchars($p['nama_petugas']) ?>
                </td>
                <td class="px-4 py-3 font-mono text-slate-700"><?= htmlspecialchars($p['username']) ?></td>
                <td class="px-4 py-3">
                    <span class="inline-block px-2.5 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider <?= $p['level'] === 'admin' ? 'bg-orange-50 text-orange-700 border border-orange-200' : 'bg-slate-100 text-slate-700 border border-slate-200' ?>">
                        <?= htmlspecialchars($p['level']) ?>
                    </span>
                </td>
                <td class="px-4 py-3 text-center">
                    <div class="flex items-center justify-center space-x-1.5">
                        <!-- Tombol edit akun petugas -->
                        <button type="button" onclick='bukaEdit(<?= json_encode($p) ?>)' title="Edit Akun" 
                                class="p-1.5 rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200 border border-slate-200 transition cursor-pointer">
                            <?= renderIcon('pencil', 'w-3.5 h-3.5') ?>
                        </button>
                        <!-- Proteksi: Petugas tidak bisa menghapus akunnya sendiri saat sedang login -->
                        <?php if ($p['id_petugas'] !== (int)$userLogin['id_petugas']): ?>
                        <button type="button" onclick="konfirmasiHapus('index.php?action=petugas_hapus&id=<?= $p['id_petugas'] ?>', 'Hapus akun petugas <?= htmlspecialchars($p['nama_petugas']) ?>?')" title="Hapus Akun" 
                                class="p-1.5 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 border border-rose-200 transition cursor-pointer">
                            <?= renderIcon('trash', 'w-3.5 h-3.5') ?>
                        </button>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- 3. Modal Popup: Tambah Petugas Baru -->
<div id="modalTambah" class="fixed inset-0 z-50 hidden bg-slate-900/60 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-sm w-full border border-slate-200 overflow-hidden">
        <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
            <h4 class="font-bold text-slate-900 text-xs uppercase tracking-wider">Tambah Petugas Baru</h4>
            <button type="button" onclick="tutupTambah()" class="text-slate-400 hover:text-slate-600 text-sm leading-none">&times;</button>
        </div>
        <form action="index.php?action=petugas_simpan" method="POST" class="p-5 space-y-3.5 text-xs">
            <div>
                <label class="block text-slate-700 font-semibold mb-1">Nama Petugas</label>
                <input type="text" name="nama_petugas" required placeholder="Nama lengkap petugas" 
                       class="w-full px-3 py-2 rounded-lg border border-slate-300 text-slate-900 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 focus:outline-none transition shadow-xs">
            </div>
            <div>
                <label class="block text-slate-700 font-semibold mb-1">Username Login</label>
                <input type="text" name="username" required placeholder="username" 
                       class="w-full px-3 py-2 rounded-lg border border-slate-300 font-mono text-slate-900 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 focus:outline-none transition shadow-xs">
            </div>
            <div>
                <label class="block text-slate-700 font-semibold mb-1">Password</label>
                <!-- Password akan di-hash menggunakan password_hash Bcrypt di backend -->
                <input type="password" name="password" required placeholder="Minimal 6 karakter" 
                       class="w-full px-3 py-2 rounded-lg border border-slate-300 text-slate-900 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 focus:outline-none transition shadow-xs">
            </div>
            <div>
                <label class="block text-slate-700 font-semibold mb-1">Tingkat Akses (Level)</label>
                <select name="level" required class="w-full px-3 py-2 rounded-lg border border-slate-300 text-slate-900 bg-white focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 focus:outline-none transition shadow-xs cursor-pointer">
                    <option value="petugas">Petugas Kasir</option>
                    <option value="admin">Administrator Sistem</option>
                </select>
            </div>
            <div class="pt-2 flex justify-end space-x-2">
                <button type="button" onclick="tutupTambah()" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg transition cursor-pointer">Batal</button>
                <button type="submit" class="px-4 py-2 bg-orange-600 hover:bg-orange-700 active:bg-orange-800 text-white rounded-lg font-semibold transition cursor-pointer shadow-xs">Simpan Akun</button>
            </div>
        </form>
    </div>
</div>

<!-- 4. Modal Popup: Edit Petugas -->
<div id="modalEdit" class="fixed inset-0 z-50 hidden bg-slate-900/60 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-sm w-full border border-slate-200 overflow-hidden">
        <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
            <h4 class="font-bold text-slate-900 text-xs uppercase tracking-wider">Perbarui Akun Petugas</h4>
            <button type="button" onclick="tutupEdit()" class="text-slate-400 hover:text-slate-600 text-sm leading-none">&times;</button>
        </div>
        <form action="index.php?action=petugas_update" method="POST" class="p-5 space-y-3.5 text-xs">
            <input type="hidden" id="edit_id" name="id_petugas">
            <div>
                <label class="block text-slate-700 font-semibold mb-1">Nama Petugas</label>
                <input type="text" id="edit_nama" name="nama_petugas" required 
                       class="w-full px-3 py-2 rounded-lg border border-slate-300 text-slate-900 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 focus:outline-none transition shadow-xs">
            </div>
            <div>
                <label class="block text-slate-700 font-semibold mb-1">Username Login</label>
                <input type="text" id="edit_user" name="username" required 
                       class="w-full px-3 py-2 rounded-lg border border-slate-300 font-mono text-slate-900 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 focus:outline-none transition shadow-xs">
            </div>
            <div>
                <label class="block text-slate-700 font-semibold mb-1">Password Baru (Opsional)</label>
                <input type="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah password" 
                       class="w-full px-3 py-2 rounded-lg border border-slate-300 text-slate-900 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 focus:outline-none transition shadow-xs">
            </div>
            <div>
                <label class="block text-slate-700 font-semibold mb-1">Tingkat Akses (Level)</label>
                <select id="edit_level" name="level" required class="w-full px-3 py-2 rounded-lg border border-slate-300 text-slate-900 bg-white focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 focus:outline-none transition shadow-xs cursor-pointer">
                    <option value="petugas">Petugas Kasir</option>
                    <option value="admin">Administrator Sistem</option>
                </select>
            </div>
            <div class="pt-2 flex justify-end space-x-2">
                <button type="button" onclick="tutupEdit()" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg transition cursor-pointer">Batal</button>
                <button type="submit" class="px-4 py-2 bg-orange-600 hover:bg-orange-700 active:bg-orange-800 text-white rounded-lg font-semibold transition cursor-pointer shadow-xs">Perbarui Akun</button>
            </div>
        </form>
    </div>
</div>

<!-- 5. Javascript untuk buka/tutup modal edit dan tambah petugas -->
<script>
    function bukaTambah() { document.getElementById('modalTambah').classList.remove('hidden'); }
    function tutupTambah() { document.getElementById('modalTambah').classList.add('hidden'); }
    function bukaEdit(d) {
        document.getElementById('edit_id').value = d.id_petugas;
        document.getElementById('edit_nama').value = d.nama_petugas;
        document.getElementById('edit_user').value = d.username;
        document.getElementById('edit_level').value = d.level;
        document.getElementById('modalEdit').classList.remove('hidden');
    }
    function tutupEdit() { document.getElementById('modalEdit').classList.add('hidden'); }
</script>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
