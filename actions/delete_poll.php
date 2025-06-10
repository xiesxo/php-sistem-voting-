<?php
require '../config/db.php';
require '../includes/session.php';

$poll_id = $_GET['poll_id'];
$user_id = $_SESSION['user_id'];

// Cek apakah polling ini milik user (creator)
$stmt = $pdo->prepare("SELECT * FROM polls WHERE id = ? AND creator_id = ?");
$stmt->execute([$poll_id, $user_id]);
$poll = $stmt->fetch();

if ($poll) {
    // Hapus polling (otomatis menghapus opsi & vote terkait)
    $stmt = $pdo->prepare("DELETE FROM polls WHERE id = ?");
    $stmt->execute([$poll_id]);
}

header("Location: ../public/dashboard.php");
exit;
?>
