<?php
// Memulai sesi untuk mengakses data session yang aktif
session_start();

// Menghancurkan seluruh data sesi (mengeluarkan user dari sistem)
session_destroy();

// Mengarahkan pengguna kembali ke halaman login setelah logout
header("Location: login.php");
exit; // Menghentikan eksekusi script setelah redirect
?>
