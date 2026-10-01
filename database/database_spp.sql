-- ====================================================================
-- MODUL MASTER DATABASE UJIKOM - SISTEM PEMBAYARAN SPP SISWA
-- Penanggung Jawab Petugas : Intra Sepriansa
-- Skema Database           : db_spp_sekolah
-- Standar Kompetensi      : SKKNI Pemrograman Web (Unit 1 s/d 7)
-- ====================================================================

CREATE DATABASE IF NOT EXISTS `db_spp_sekolah` 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE `db_spp_sekolah`;

-- --------------------------------------------------------------------
-- 1. TABEL: petugas
-- Keterangan: Menyimpan data petugas yang menangani pembayaran SPP
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `cek_pembayaran`;
DROP TABLE IF EXISTS `pembayaran`;
DROP TABLE IF EXISTS `siswa`;
DROP TABLE IF EXISTS `spp`;
DROP TABLE IF EXISTS `kelas`;
DROP TABLE IF EXISTS `petugas`;

CREATE TABLE `petugas` (
    `id_petugas` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `nama_petugas` VARCHAR(100) NOT NULL,
    `level` ENUM('admin', 'petugas') NOT NULL DEFAULT 'petugas',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 2. TABEL: kelas
-- Keterangan: Menyimpan data kelas yang ada di sekolah
-- --------------------------------------------------------------------
CREATE TABLE `kelas` (
    `id_kelas` INT AUTO_INCREMENT PRIMARY KEY,
    `nama_kelas` VARCHAR(20) NOT NULL,
    `kompetensi_keahlian` VARCHAR(100) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 3. TABEL: spp
-- Keterangan: Menyimpan data besaran SPP per tahun ajaran
-- --------------------------------------------------------------------
CREATE TABLE `spp` (
    `id_spp` INT AUTO_INCREMENT PRIMARY KEY,
    `tahun` INT NOT NULL,
    `nominal` INT NOT NULL,
    `keterangan` VARCHAR(100) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 4. TABEL: siswa
-- Keterangan: Menyimpan data siswa yang bersekolah
-- --------------------------------------------------------------------
CREATE TABLE `siswa` (
    `nisn` CHAR(10) NOT NULL PRIMARY KEY,
    `nis` CHAR(8) NOT NULL UNIQUE,
    `nama` VARCHAR(100) NOT NULL,
    `id_kelas` INT NOT NULL,
    `alamat` TEXT NOT NULL,
    `no_telp` VARCHAR(15) NOT NULL,
    `id_spp` INT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_siswa_kelas` FOREIGN KEY (`id_kelas`) 
        REFERENCES `kelas` (`id_kelas`) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT `fk_siswa_spp` FOREIGN KEY (`id_spp`) 
        REFERENCES `spp` (`id_spp`) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 5. TABEL: pembayaran
-- Keterangan: Digunakan untuk mencatat transaksi dan status verifikasi pembayaran SPP
-- --------------------------------------------------------------------
CREATE TABLE `pembayaran` (
    `id_pembayaran` INT AUTO_INCREMENT PRIMARY KEY,
    `kode_transaksi` VARCHAR(30) NOT NULL UNIQUE,
    `id_petugas` INT NOT NULL,
    `nisn` CHAR(10) NOT NULL,
    `tgl_bayar` DATE NOT NULL,
    `bulan_dibayar` VARCHAR(20) NOT NULL,
    `tahun_dibayar` VARCHAR(4) NOT NULL,
    `id_spp` INT NOT NULL,
    `jumlah_bayar` INT NOT NULL,
    `metode_pembayaran` VARCHAR(30) NOT NULL DEFAULT 'Tunai',
    `status_verifikasi` ENUM('Terverifikasi', 'Menunggu Verifikasi', 'Ditolak') NOT NULL DEFAULT 'Terverifikasi',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_pembayaran_petugas` FOREIGN KEY (`id_petugas`) 
        REFERENCES `petugas` (`id_petugas`) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT `fk_pembayaran_siswa` FOREIGN KEY (`nisn`) 
        REFERENCES `siswa` (`nisn`) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT `fk_pembayaran_spp` FOREIGN KEY (`id_spp`) 
        REFERENCES `spp` (`id_spp`) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 6. TABEL: cek_pembayaran
-- Keterangan: Menyimpan log riwayat pemeriksaan & verifikasi pembayaran
-- --------------------------------------------------------------------
CREATE TABLE `cek_pembayaran` (
    `id_cek` INT AUTO_INCREMENT PRIMARY KEY,
    `id_pembayaran` INT NOT NULL,
    `nisn` CHAR(10) NOT NULL,
    `status_verifikasi` ENUM('Terverifikasi', 'Menunggu Verifikasi', 'Ditolak') NOT NULL,
    `catatan` TEXT DEFAULT NULL,
    `tgl_verifikasi` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `diverifikasi_oleh` INT NOT NULL,
    CONSTRAINT `fk_cek_pembayaran` FOREIGN KEY (`id_pembayaran`) 
        REFERENCES `pembayaran` (`id_pembayaran`) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT `fk_cek_siswa` FOREIGN KEY (`nisn`) 
        REFERENCES `siswa` (`nisn`) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT `fk_cek_petugas` FOREIGN KEY (`diverifikasi_oleh`) 
        REFERENCES `petugas` (`id_petugas`) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ====================================================================
-- SEED DATA AWAL (REALISTIS & SESUAI SPESIFIKASI)
-- Password default semua akun: password123
-- ====================================================================

-- Petugas: Intra Sepriansa (Admin) & Raden Surya Pratama (Petugas)
INSERT INTO `petugas` (`id_petugas`, `username`, `password`, `nama_petugas`, `level`) VALUES
(1, 'intra', '$2y$12$qDL8ZSR7Eqi.inZJ8U/tx.z93hF2lNpT4jYTNUE3UCrbC29pzpqE.', 'Intra Sepriansa', 'admin'),
(2, 'surya', '$2y$12$qDL8ZSR7Eqi.inZJ8U/tx.z93hF2lNpT4jYTNUE3UCrbC29pzpqE.', 'Raden Surya Pratama', 'petugas');

-- Kelas
INSERT INTO `kelas` (`id_kelas`, `nama_kelas`, `kompetensi_keahlian`) VALUES
(1, 'XII RPL 1', 'Rekayasa Perangkat Lunak'),
(2, 'XII TKJ 1', 'Teknik Komputer dan Jaringan'),
(3, 'XI RPL 1', 'Rekayasa Perangkat Lunak'),
(4, 'XI DKV 1', 'Desain Komunikasi Visual'),
(5, 'X PPLG 1', 'Pengembangan Perangkat Lunak dan Gim');

-- SPP (Besaran per tahun ajaran)
INSERT INTO `spp` (`id_spp`, `tahun`, `nominal`, `keterangan`) VALUES
(1, 2024, 250000, 'Tahun Ajaran 2023/2024'),
(2, 2025, 300000, 'Tahun Ajaran 2024/2025'),
(3, 2026, 350000, 'Tahun Ajaran 2025/2026');

-- Siswa (Nama realistis Indonesia)
INSERT INTO `siswa` (`nisn`, `nis`, `nama`, `id_kelas`, `alamat`, `no_telp`, `id_spp`) VALUES
('0061234501', '210101', 'Raden Arya Wiguna', 1, 'Jl. Menteng Asri No. 14, Jakarta Pusat', '081289123456', 3),
('0061234502', '210102', 'Amanda Putri Kirana', 1, 'Jl. Melati Indah No. 28, Jakarta Selatan', '081398765432', 3),
('0061234503', '210103', 'Dzaky Fathurrahman', 2, 'Jl. Kemang Raya No. 7, Jakarta Selatan', '085712348765', 3),
('0071234504', '220104', 'Nadira Shafa Azzahra', 3, 'Jl. Cempaka Putih No. 42, Jakarta Pusat', '081234567890', 2),
('0071234505', '220105', 'Rendy Pratama Kusuma', 4, 'Jl. Senopati Boulevard No. 19, Jakarta Selatan', '087812903456', 2);

-- Pembayaran
INSERT INTO `pembayaran` (`id_pembayaran`, `kode_transaksi`, `id_petugas`, `nisn`, `tgl_bayar`, `bulan_dibayar`, `tahun_dibayar`, `id_spp`, `jumlah_bayar`, `metode_pembayaran`, `status_verifikasi`) VALUES
(1, 'SPP-202601001', 1, '0061234501', '2026-01-10', 'Januari', '2026', 3, 350000, 'Transfer Bank', 'Terverifikasi'),
(2, 'SPP-202602002', 1, '0061234501', '2026-02-09', 'Februari', '2026', 3, 350000, 'Tunai', 'Terverifikasi'),
(3, 'SPP-202601003', 1, '0061234502', '2026-01-12', 'Januari', '2026', 3, 350000, 'Transfer Bank', 'Terverifikasi'),
(4, 'SPP-202601004', 2, '0061234503', '2026-01-15', 'Januari', '2026', 3, 350000, 'Tunai', 'Terverifikasi'),
(5, 'SPP-202603005', 1, '0071234504', '2026-03-05', 'Maret', '2026', 2, 300000, 'Transfer Bank', 'Menunggu Verifikasi');

-- Cek Pembayaran (Log Riwayat Verifikasi)
INSERT INTO `cek_pembayaran` (`id_cek`, `id_pembayaran`, `nisn`, `status_verifikasi`, `catatan`, `tgl_verifikasi`, `diverifikasi_oleh`) VALUES
(1, 1, '0061234501', 'Terverifikasi', 'Bukti mutasi rekening bank valid, dana SPP Januari 2026 telah masuk rekening resmi sekolah.', '2026-01-10 10:15:00', 1),
(2, 2, '0061234501', 'Terverifikasi', 'Pembayaran tunai diterima langsung di loket tata usaha oleh Intra Sepriansa.', '2026-02-09 09:30:00', 1),
(3, 3, '0061234502', 'Terverifikasi', 'Struk transfer ATM BCA cocok dengan nominal tagihan SPP.', '2026-01-12 14:20:00', 1),
(4, 4, '0061234503', 'Terverifikasi', 'Pembayaran tunai loket 2 diterima dan disahkan oleh Raden Surya Pratama.', '2026-01-15 11:00:00', 2),
(5, 5, '0071234504', 'Menunggu Verifikasi', 'Menunggu konfirmasi validasi transfer antardepartemen keuangan sekolah.', '2026-03-05 13:45:00', 1);
