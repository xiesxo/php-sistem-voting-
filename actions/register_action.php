<?php
require '../config/db.php';  // Menghubungkan ke database
session_start();             // Memulai session (meskipun di sini belum digunakan)

$username = $_POST['username'];  
// Hash password menggunakan bcrypt agar aman saat disimpan di database
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
$role = $_POST['role'];      // Ambil role user dari form (creator/voter)

// Siapkan query insert data user baru ke tabel users
$stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");

// Eksekusi query dengan data username, hashed password, dan role
$stmt->execute([$username, $password, $role]);

// Setelah berhasil registrasi, redirect ke halaman login
header("Location: ../public/login.php");
exit;
?>
