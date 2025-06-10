<?php
require '../includes/session.php';
require '../config/db.php';
require '../includes/tcpdf/tcpdf.php';


if (!isset($_GET['poll_id'])) {
    die("Poll ID tidak ditemukan.");
}

$poll_id = $_GET['poll_id'];
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Cek apakah user adalah creator atau voter yang berhak
$query = $role === 'creator' ?
    "SELECT * FROM polls WHERE id = ? AND creator_id = ?" :
    "SELECT p.* FROM polls p JOIN votes v ON p.id = v.poll_id WHERE p.id = ? AND v.user_id = ?";

$stmt = $pdo->prepare($query);
$stmt->execute([$poll_id, $user_id]);
$poll = $stmt->fetch();

if (!$poll) {
    die("Anda tidak memiliki akses ke polling ini.");
}

// Ambil opsi dan hasil voting
$opt_stmt = $pdo->prepare("SELECT * FROM poll_options WHERE poll_id = ?");
$opt_stmt->execute([$poll_id]);
$options = $opt_stmt->fetchAll();

$vote_stmt = $pdo->prepare("SELECT COUNT(*) AS total_votes FROM votes WHERE poll_id = ?");
$vote_stmt->execute([$poll_id]);
$total_votes = $vote_stmt->fetch()['total_votes'];

// Buat dokumen PDF
$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);

// Judul
$pdf->Write(0, "Hasil Voting: " . $poll['title'], '', 0, 'C', true, 0, false, false, 0);
$pdf->Ln(5);

// Isi hasil polling
foreach ($options as $opt) {
    $count_stmt = $pdo->prepare("SELECT COUNT(*) AS count FROM votes WHERE option_id = ?");
    $count_stmt->execute([$opt['id']]);
    $count = $count_stmt->fetch()['count'];
    $percentage = $total_votes > 0 ? round(($count / $total_votes) * 100, 2) : 0;

    $pdf->Write(0, "{$opt['option_text']} : {$count} suara ({$percentage}%)", '', 0, 'L', true, 0, false, false, 0);
}

$pdf->Ln(3);
$pdf->Write(0, "Total Suara: {$total_votes}", '', 0, 'L', true, 0, false, false, 0);

// Output PDF
$pdf->Output("hasil_voting_{$poll_id}.pdf", 'I');
