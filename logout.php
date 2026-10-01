<?php
/**
 * Logout Handler
 * Menghapus seluruh sesi pengguna dan mengalihkan ke halaman login
 *
 * @package Auth
 * @author  Intra Sepriansa <intra@sekolah.sch.id>
 */
require_once __DIR__ . '/helpers/utility.php';

$_SESSION = [];
session_regenerate_id(true);

setFlashMessage('success', 'Anda telah berhasil keluar dari sistem SPP Digital.');
header('Location: index.php?page=login');
exit;
