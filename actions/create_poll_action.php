<?php
// Menghubungkan ke database dan memulai session
require '../config/db.php';
require '../includes/session.php';

// Ambil data dari form POST
$title = $_POST['title'];          // Judul polling
$deadline = $_POST['deadline'];    // Batas waktu polling
$options = $_POST['options'];      // Array opsi polling
$creator_id = $_SESSION['user_id']; // ID pengguna yang membuat polling (creator)

// Masukkan data polling ke tabel polls
$stmt = $pdo->prepare("INSERT INTO polls (title, deadline, creator_id) VALUES (?, ?, ?)");
$stmt->execute([$title, $deadline, $creator_id]);

// Dapatkan ID polling yang baru saja dimasukkan
$poll_id = $pdo->lastInsertId();

// Masukkan tiap opsi ke tabel poll_options terkait dengan poll_id
foreach ($options as $option) {
    $stmt = $pdo->prepare("INSERT INTO poll_options (poll_id, option_text) VALUES (?, ?)");
    $stmt->execute([$poll_id, $option]);
}

// Setelah berhasil, redirect ke halaman dashboard
header("Location: ../public/dashboard.php");
exit;
?>
