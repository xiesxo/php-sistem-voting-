<?php
require '../includes/session.php';
require '../config/db.php';

$poll_id = $_POST['poll_id'];
$option_id = $_POST['option_id'];
$user_id = $_SESSION['user_id'];

// Cek apakah sudah voting
$check = $pdo->prepare("SELECT * FROM votes WHERE poll_id = ? AND user_id = ?");
$check->execute([$poll_id, $user_id]);

if ($check->rowCount() > 0) {
    $_SESSION['message'] = "Anda sudah memilih.";
    header("Location: ../public/poll_result.php?poll_id=$poll_id");
    exit;
}

// Simpan suara
$stmt = $pdo->prepare("INSERT INTO votes (user_id, poll_id, option_id) VALUES (?, ?, ?)");
$stmt->execute([$user_id, $poll_id, $option_id]);

$_SESSION['message'] = "Suara berhasil disimpan.";
header("Location: ../public/poll_result.php?poll_id=$poll_id");
exit;
?>
