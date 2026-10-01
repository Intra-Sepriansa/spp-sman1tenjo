<?php
/**
 * Koneksi Database MySQL menggunakan PDO
 * Pola Singleton: Memastikan koneksi database hanya dibuat 1 kali agar hemat memori server
 */
class Database
{
    // Simpan objek koneksi database tunggal
    private static ?PDO $instance = null;

    // Pengaturan database lokal MySQL di komputer
    private const DB_HOST = '127.0.0.1';
    private const DB_PORT = '3306';
    private const DB_NAME = 'db_spp_sekolah';
    private const DB_USER = 'root';
    private const DB_PASS = '';

    // Constructor dibuat private agar class ini tidak bisa di-new sembarangan dari luar
    private function __construct()
    {
    }

    /**
     * Ambil objek koneksi database
     * Kalau belum ada koneksi, buat koneksi baru. Kalau sudah ada, pakai koneksi yang lama.
     */
    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $dsn = "mysql:host=" . self::DB_HOST . ";port=" . self::DB_PORT . ";dbname=" . self::DB_NAME . ";charset=utf8mb4";
            
            // Pengaturan keamanan PDO:
            // 1. ATTR_ERRMODE => Menampilkan error jika query bermasalah
            // 2. ATTR_DEFAULT_FETCH_MODE => Mengembalikan data dalam bentuk array asosiatif
            // 3. ATTR_EMULATE_PREPARES => false agar Prepared Statement asli berjalan dan 100% kebal SQL Injection
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                self::$instance = new PDO($dsn, self::DB_USER, self::DB_PASS, $options);
            } catch (PDOException $e) {
                // Tampilkan pesan error jika server database MySQL mati atau salah password
                die("Gagal menghubungkan ke database MySQL: " . $e->getMessage());
            }
        }

        return self::$instance;
    }
}
