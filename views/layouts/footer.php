<?php
/**
 * Footer Layout
 *
 * @package Views/Layouts
 * @author  Intra Sepriansa <intra@sekolah.sch.id>
 */
$flash = getFlashMessage();
?>
<?php if (isset($_SESSION['user'])): ?>
        </main>
    </div>
</div>
<?php endif; ?>

<!-- Skrip Notifikasi SweetAlert2 Bersih -->
<script>
    <?php if ($flash): ?>
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: '<?= $flash['type'] === 'success' ? 'success' : ($flash['type'] === 'error' ? 'error' : 'info') ?>',
            title: '<?= $flash['type'] === 'success' ? 'Berhasil' : ($flash['type'] === 'error' ? 'Gagal' : 'Informasi') ?>',
            text: '<?= addslashes($flash['message']) ?>',
            confirmButtonColor: '#ea580c',
            timer: 2500,
            timerProgressBar: true
        });
    } else {
        alert('<?= addslashes($flash['message']) ?>');
    }
    <?php endif; ?>

    function konfirmasiHapus(url, pesan) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: pesan || 'Data yang dihapus tidak dapat dipulihkan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ea580c',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        } else {
            if (confirm(pesan || 'Data yang dihapus tidak dapat dipulihkan!')) {
                window.location.href = url;
            }
        }
    }
</script>
</body>
</html>
