<?php
/**
 * Halaman Login Sistem SPP
 * Fungsi: Autentikasi akun petugas kasir dan administrator sebelum masuk sistem
 */

// Jika file ini diakses langsung dari URL browser, arahkan ke controller utama
if (!function_exists('renderIcon')) {
    header('Location: ../index.php?page=login');
    exit;
}

$pageTitle = 'Login';
?>
<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SPP Digital</title>
    
    <!-- Font Plus Jakarta Sans & JetBrains Mono -->
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
        body { font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif; }
        /* Fallback CSS agar SVG tidak pernah membesar liar saat offline/loading */
        svg { display: inline-block; vertical-align: middle; max-width: 100%; }
        svg.w-4 { width: 1rem !important; height: 1rem !important; }
        svg.w-5 { width: 1.25rem !important; height: 1.25rem !important; }
        svg.w-6 { width: 1.5rem !important; height: 1.5rem !important; }
    </style>
</head>
<!-- Latar belakang canvas adem #e9edf2, nyaman di mata -->
<body class="bg-[#e9edf2] text-slate-800 min-h-screen flex items-center justify-center p-4">

<div class="w-full max-w-sm">
    <!-- Kartu Login Bersih & Rapi -->
    <div class="bg-white rounded-xl shadow-xs border border-slate-300/80 p-7">
        <!-- 1. Header Logo & Nama Aplikasi -->
        <div class="flex items-center space-x-3 mb-6 pb-4 border-b border-slate-100">
            <div class="w-9 h-9 bg-orange-600 text-white rounded-lg flex items-center justify-center shadow-xs">
                <?= renderIcon('pembayaran', 'w-5 h-5') ?>
            </div>
            <div>
                <h1 class="text-sm font-bold tracking-tight text-slate-900 uppercase">SMAN 1 TENJO</h1>
                <p class="text-xs text-slate-500">Sistem Pembayaran SPP</p>
            </div>
        </div>

        <!-- 2. Pesan Notifikasi (misal: username/password salah) -->
        <?php $flash = getFlashMessage(); ?>
        <?php if ($flash): ?>
            <div class="mb-5 p-3 rounded-lg text-xs font-medium <?= $flash['type'] === 'error' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-orange-50 text-orange-800 border border-orange-200' ?>">
                <?= htmlspecialchars($flash['message']) ?>
            </div>
        <?php endif; ?>

        <!-- 3. Form Input Username & Password -->
        <form action="index.php?action=login_proses" method="POST" class="space-y-4">
            <div>
                <label for="username" class="block text-xs font-semibold text-slate-700 mb-1.5">Username</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <?= renderIcon('user', 'w-4 h-4') ?>
                    </div>
                    <input type="text" id="username" name="username" required placeholder="Masukkan username" 
                           class="w-full pl-9 pr-3 py-2 rounded-lg border border-slate-300 text-xs text-slate-900 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 focus:outline-none transition shadow-xs">
                </div>
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold text-slate-700 mb-1.5">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <?= renderIcon('lock', 'w-4 h-4') ?>
                    </div>
                    <input type="password" id="password" name="password" required placeholder="Masukkan password" 
                           class="w-full pl-9 pr-3 py-2 rounded-lg border border-slate-300 text-xs text-slate-900 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 focus:outline-none transition shadow-xs">
                </div>
            </div>

            <!-- Tombol Submit Login -->
            <button type="submit" 
                    class="w-full py-2.5 px-4 rounded-lg bg-orange-600 hover:bg-orange-700 active:bg-orange-800 text-white font-semibold text-xs shadow-xs flex items-center justify-center space-x-1.5 transition-colors cursor-pointer">
                <?= renderIcon('login', 'w-4 h-4') ?>
                <span>Masuk Sekarang</span>
            </button>
        </form>
    </div>
</div>

</body>
</html>
