<?php
/**
 * Layout Header Aplikasi SPP
 * Tampilan sidebar abu-abu lembut (adem di mata, tidak silau) dengan aksen oranye
 */
$currentPage = $_GET['page'] ?? 'dashboard';
$userLogin   = $_SESSION['user'] ?? null;
?>
<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'SPP') ?> - Sistem Pembayaran SPP</title>
    
    <!-- Font Plus Jakarta Sans & JetBrains Mono untuk angka/kode -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (Prioritas Lokal Offline + Cadangan CDN) -->
    <script src="assets/js/tailwind.js"></script>
    <script>
        if (typeof tailwind === 'undefined') {
            document.write('<script src="https://cdn.tailwindcss.com"><\/script>');
        }
    </script>
    <script>
        if (typeof tailwind !== 'undefined') {
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['"Plus Jakarta Sans"', '-apple-system', 'BlinkMacSystemFont', 'sans-serif'],
                            mono: ['"JetBrains Mono"', 'monospace'],
                        },
                        colors: {
                            brand: {
                                50: '#fff7ed',
                                100: '#ffedd5',
                                200: '#fed7aa',
                                500: '#f97316',
                                600: '#ea580c',
                                700: '#c2410c',
                            }
                        }
                    }
                }
            };
        }
    </script>
    <!-- SweetAlert2 (Prioritas Lokal Offline + Cadangan CDN) -->
    <script src="assets/js/sweetalert2.all.min.js"></script>
    <script>
        if (typeof Swal === 'undefined') {
            document.write('<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"><\/script>');
        }
    </script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        /* Fallback CSS agar SVG tidak pernah membesar liar saat offline/loading */
        svg { display: inline-block; vertical-align: middle; max-width: 100%; }
        svg.w-4 { width: 1rem !important; height: 1rem !important; }
        svg.w-5 { width: 1.25rem !important; height: 1.25rem !important; }
        svg.w-6 { width: 1.5rem !important; height: 1.5rem !important; }
        @media print {
            .no-print { display: none !important; }
            .print-only { display: block !important; }
            body { background: #fff !important; color: #000 !important; }
        }
    </style>
</head>
<!-- Background utama sedikit lebih gelap dari sidebar biar kontrasnya pas dan adem -->
<body class="bg-[#e9edf2] text-slate-800 antialiased h-full flex flex-col font-sans">

<?php if ($userLogin): ?>
<div class="flex-1 flex overflow-hidden">
    <!-- SIDEBAR: Warna abu-abu lembut (#f1f3f6), adem di mata & beda dari background utama -->
    <aside class="w-64 bg-[#f1f3f6] text-slate-700 flex-shrink-0 flex flex-col justify-between border-r border-slate-300/80 no-print select-none z-20">
        <div class="flex flex-col flex-1 overflow-y-auto">
            <!-- 1. Header Logo & Nama Sekolah -->
            <div class="h-16 px-5 border-b border-slate-300/70 flex items-center space-x-3 bg-[#f1f3f6]">
                <div class="w-8 h-8 rounded-lg bg-orange-600 flex items-center justify-center text-white font-bold text-xs shadow-xs">
                    <?= renderIcon('pembayaran', 'w-4 h-4') ?>
                </div>
                <div class="min-w-0 flex-1">
                    <h1 class="text-xs font-bold tracking-wider text-slate-900 uppercase leading-none">SMAN 1 TENJO</h1>
                    <p class="text-[11px] text-slate-500 font-medium truncate mt-1">Sistem SPP Digital</p>
                </div>
            </div>

            <!-- 2. Navigasi 7 Menu Utama -->
            <div class="px-3 py-4 flex-1 space-y-5">
                <div>
                    <p class="px-3 mb-2 text-[10px] font-bold uppercase tracking-wider text-slate-400">Menu Utama</p>
                    <nav class="space-y-1 text-xs font-medium">
                        <!-- Menu 1: Dashboard -->
                        <a href="index.php?page=dashboard" 
                           class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-colors <?= $currentPage === 'dashboard' ? 'bg-orange-600 text-white font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/70' ?>">
                            <?= renderIcon('dashboard', 'w-4 h-4') ?>
                            <span>Dashboard</span>
                        </a>

                        <!-- Menu 2: Data Kelas -->
                        <a href="index.php?page=kelas" 
                           class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-colors <?= $currentPage === 'kelas' ? 'bg-orange-600 text-white font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/70' ?>">
                            <?= renderIcon('kelas', 'w-4 h-4') ?>
                            <span>Data Kelas</span>
                        </a>

                        <!-- Menu 3: Data Siswa -->
                        <a href="index.php?page=siswa" 
                           class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-colors <?= $currentPage === 'siswa' ? 'bg-orange-600 text-white font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/70' ?>">
                            <?= renderIcon('siswa', 'w-4 h-4') ?>
                            <span>Data Siswa</span>
                        </a>

                        <!-- Menu 4: Pembayaran SPP (Entri Transaksi) -->
                        <a href="index.php?page=pembayaran" 
                           class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-colors <?= $currentPage === 'pembayaran' ? 'bg-orange-600 text-white font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/70' ?>">
                            <?= renderIcon('pembayaran', 'w-4 h-4') ?>
                            <span>Pembayaran SPP</span>
                        </a>

                        <!-- Menu 5: Cek Pembayaran & Tunggakan -->
                        <a href="index.php?page=cek_pembayaran" 
                           class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-colors <?= $currentPage === 'cek_pembayaran' ? 'bg-orange-600 text-white font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/70' ?>">
                            <?= renderIcon('cek', 'w-4 h-4') ?>
                            <span>Cek Tunggakan</span>
                        </a>

                        <!-- Menu 6: Histori Pembayaran & Cetak Kuitansi -->
                        <a href="index.php?page=detail_pembayaran" 
                           class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-colors <?= $currentPage === 'detail_pembayaran' ? 'bg-orange-600 text-white font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/70' ?>">
                            <?= renderIcon('clock', 'w-4 h-4') ?>
                            <span>Histori Pembayaran</span>
                        </a>
                    </nav>
                </div>

                <!-- Menu 7: Khusus Admin (Kelola Petugas) -->
                <?php if ($userLogin['level'] === 'admin'): ?>
                <div>
                    <p class="px-3 mb-2 text-[10px] font-bold uppercase tracking-wider text-slate-400">Pengaturan</p>
                    <nav class="space-y-1 text-xs font-medium">
                        <a href="index.php?page=petugas" 
                           class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-colors <?= $currentPage === 'petugas' ? 'bg-orange-600 text-white font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/70' ?>">
                            <?= renderIcon('petugas', 'w-4 h-4') ?>
                            <span>Data Petugas</span>
                        </a>
                    </nav>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- 3. Profil Akun Petugas & Tombol Logout -->
        <div class="p-3 border-t border-slate-300/70 bg-[#eaeef3]">
            <div class="flex items-center justify-between p-2 rounded-lg bg-white border border-slate-300/70 shadow-xs">
                <div class="flex items-center space-x-2.5 min-w-0">
                    <div class="w-8 h-8 rounded-md bg-orange-100 text-orange-700 border border-orange-200 flex items-center justify-center font-bold text-xs font-mono shrink-0">
                        <?= strtoupper(substr($userLogin['nama_petugas'], 0, 2)) ?>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-semibold text-slate-900 truncate"><?= htmlspecialchars($userLogin['nama_petugas']) ?></p>
                        <p class="text-[10px] text-slate-500 font-mono capitalize"><?= htmlspecialchars($userLogin['level']) ?></p>
                    </div>
                </div>
                <!-- Tombol keluar dari sistem -->
                <a href="logout.php" title="Keluar" 
                   class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-md transition-colors">
                    <?= renderIcon('logout', 'w-4 h-4') ?>
                </a>
            </div>
        </div>
    </aside>

    <!-- AREA KONTEN UTAMA: Canvas #e9edf2 (kontras pas dengan kartu putih) -->
    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <!-- Topbar Sistem: Bersih, Rapi & Elegan -->
        <header class="h-16 bg-white border-b border-slate-300/70 px-6 flex items-center justify-between no-print sticky top-0 z-10 shadow-xs">
            <!-- Breadcrumb halaman aktif -->
            <div class="flex items-center space-x-2 text-xs">
                <span class="text-slate-400 font-medium">Sistem SPP</span>
                <span class="text-slate-300">/</span>
                <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wide"><?= htmlspecialchars($pageTitle ?? 'Menu') ?></h2>
            </div>

            <!-- Tanggal hari ini & role user -->
            <div class="flex items-center space-x-3 text-xs shrink-0">
                <span class="text-slate-500 font-medium"><?= date('d M Y') ?></span>
                <span class="px-2.5 py-1 rounded text-[11px] font-bold uppercase tracking-wider <?= $userLogin['level'] === 'admin' ? 'bg-orange-50 text-orange-700 border border-orange-200' : 'bg-slate-100 text-slate-700 border border-slate-200' ?>">
                    <?= htmlspecialchars($userLogin['level']) ?>
                </span>
            </div>
        </header>

        <!-- Area Isi Konten Halaman -->
        <main class="flex-1 p-6 bg-[#e9edf2]">
<?php endif; ?>
