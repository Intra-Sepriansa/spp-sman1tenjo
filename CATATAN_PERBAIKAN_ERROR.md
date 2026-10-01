# Catatan Perbaikan: Error Icon Membesar Layar Penuh

Dokumen ringkas penjelasan masalah tampilan dan langkah perbaikannya.

---

## 1. Masalah (Error)

Saat membuka aplikasi (`index.php?page=dashboard` / halaman login), muncul **satu icon kartu pembayaran (SVG) raksasa yang memenuhi seluruh layar browser**, dan tampilan desain lainnya hilang/rusak.

---

## 2. Penyebab Utama

1. **Ketergantungan CDN Online**: Framework Tailwind CSS awalnya hanya dimuat via CDN internet (`cdn.tailwindcss.com`). Saat internet terputus, lab sekolah offline, atau sinyal lambat, file CSS gagal diunduh browser.
2. **SVG Tanpa Batas Ukuran**: Tag `<svg>` di kode belum memiliki atribut HTML `width` & `height`. Saat CSS Tailwind gagal termuat, browser otomatis merender icon selebar 100% layar monitor.

---

## 3. Cara Diperbaiki

### A. Sediakan Aset Lokal (*Offline-First*)

Mengunduh library agar aplikasi bisa berjalan tanpa koneksi internet:

- `assets/js/tailwind.js` (Tailwind CSS Lokal)
- `assets/js/sweetalert2.all.min.js` (Popup Notifikasi Lokal)

### B. Kunci Ukuran SVG di `helpers/utility.php`

Memperbarui fungsi `renderIcon()` agar menyematkan atribut `width` dan `height` eksplisit (16px, 20px, 24px) langsung pada tag `<svg>`:

```php
return '<svg width="' . $size . '" height="' . $size . '" class="' . $class . '" ...>' . $paths[$name] . '</svg>';
```

### C. Pasang CSS Fallback di `login.php` & `header.php`

Menambahkan aturan CSS darurat di dalam tag `<style>`:

```css
svg { display: inline-block; vertical-align: middle; max-width: 100%; }
svg.w-4 { width: 1rem !important; height: 1rem !important; }
svg.w-5 { width: 1.25rem !important; height: 1.25rem !important; }
```

### D. Skrip Prioritas Lokal + Cadangan CDN

Memuat file JS lokal terlebih dahulu, lalu beralih ke CDN hanya jika file lokal tidak ditemukan, serta memproteksi konfigurasi dari error:

```html
<script src="assets/js/tailwind.js"></script>
<script>
    if (typeof tailwind === 'undefined') {
        document.write('<script src="https://cdn.tailwindcss.com"><\/script>');
    }
</script>
```

---

## 4. Hasil

Aplikasi kini **100% aman dijalankan secara offline** tanpa internet, dan icon dijamin tidak akan pernah membesar liar lagi.
