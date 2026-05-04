<?php
// =====================================================
// Konfigurasi Koneksi Database
// Untuk produksi, gunakan environment variables atau
// file konfigurasi di luar web root yang tidak
// dicommit ke version control.
// =====================================================

define('DB_HOST',    getenv('DB_HOST')    ?: 'localhost');
define('DB_USER',    getenv('DB_USER')    ?: 'root');
define('DB_PASS',    getenv('DB_PASS')    ?: '');
define('DB_NAME',    getenv('DB_NAME')    ?: 'db_berita');
define('DB_CHARSET', 'utf8mb4');

/**
 * Membuat koneksi MySQLi dan mengembalikan object koneksi.
 * Jika koneksi gagal, fungsi akan melempar Exception.
 */
function getConnection(): mysqli
{
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        throw new RuntimeException('Koneksi database gagal: ' . $conn->connect_error);
    }

    $conn->set_charset(DB_CHARSET);

    return $conn;
}
