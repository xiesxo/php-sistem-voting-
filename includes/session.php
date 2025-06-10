<?php
// Memulai session untuk mengakses data sesi pengguna
session_start();

// Mengecek apakah pengguna sudah login (memiliki user_id dalam session)
if (!isset($_SESSION['user_id'])) {
    // Jika belum login, arahkan ke halaman login
    header("Location: ../public/login.php");
    exit; // Menghentikan eksekusi skrip lebih lanjut
}
?>
