<?php
require '../config/db.php';  // Menghubungkan ke database
session_start();             // Memulai session untuk menyimpan data pengguna yang login

// Ambil data username dan password dari form POST
$username = $_POST['username'];
$password = $_POST['password'];

// Query untuk mencari user berdasarkan username
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

// Cek apakah user ditemukan dan password cocok (dengan password_verify untuk hash)
if ($user && password_verify($password, $user['password'])) {
    // Simpan data user ke session untuk digunakan di halaman lain
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];

    // Redirect ke dashboard setelah login berhasil
    header("Location: ../public/dashboard.php");
    exit;
} else {
    // Jika login gagal, simpan pesan error ke session dan redirect ke halaman login
    $_SESSION['error'] = "Username atau password salah.";
    header("Location: ../public/login.php");
    exit;
}
?>
