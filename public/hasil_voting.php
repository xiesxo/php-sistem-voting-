<?php
// Memuat file session dan koneksi database
require '../includes/session.php';
require '../config/db.php';

// Ambil data role dan user ID dari session
$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];

// Ambil polling berdasarkan peran pengguna
if ($role === 'creator') {
    // Jika creator, ambil polling yang dibuatnya
    $stmt = $pdo->prepare("SELECT * FROM polls WHERE creator_id = ? ORDER BY deadline DESC");
    $stmt->execute([$user_id]);
} else {
    // Jika voter, ambil polling yang telah diikuti
    $stmt = $pdo->prepare("
        SELECT DISTINCT p.* FROM polls p
        JOIN votes v ON p.id = v.poll_id
        WHERE v.user_id = ? AND p.id IS NOT NULL
        ORDER BY p.deadline DESC
    ");
    $stmt->execute([$user_id]);
}

// Ambil semua hasil polling dari eksekusi query
$polls = $stmt->fetchAll();
?>
    
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hasil Voting</title>
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom Style -->
    <style>
        /* Gaya tampilan halaman */
        body { background-color: #f4f0fb; font-family: 'Segoe UI', sans-serif; }
        .navbar { background-color: #6f42c1; }
        .navbar .nav-link { color: #fff; transition: 0.3s; }
        .navbar .nav-link:hover { color: #ffc107; }

        .card-container {
            background-color: white;
            border-radius: 20px;
            padding: 30px;
            max-width: 600px;
            margin: 40px auto;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .poll-title {
            font-weight: bold;
            color: #6f42c1;
            margin-bottom: 20px;
            text-align: center;
            font-size: 24px;
        }

        .option-box {
            border: 2px solid #6f42c1;
            border-radius: 50px;
            padding: 10px 20px;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 16px;
            color: #444;
        }

        .badge-vote {
            background-color: #28a745;
            color: white;
            font-size: 14px;
            padding: 6px 12px;
            border-radius: 20px;
        }

        .total-vote {
            color: #666;
            font-size: 14px;
            margin-top: 10px;
            text-align: center;
        }

        .btn-hapus {
            background-color: #f44336;
            color: white;
            padding: 10px 30px;
            border: none;
            border-radius: 30px;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn-hapus:hover {
            background-color: #d32f2f;
            transform: scale(1.05);
        }

        .judul-box {
            background-color: white;
            border-radius: 20px;
            padding: 10px 30px;
            text-align: center;
            font-weight: bold;
            color: #6f42c1;
            font-size: 28px;
            margin: 30px auto;
            display: inline-block;
        }
    </style>
</head>
<body>

<!-- Navigasi Utama -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand text-white fw-bold" href="#">E-Vote</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <!-- Navigasi ke halaman dashboard -->
                <li class="nav-item"><a class="nav-link fw-bold" href="dashboard.php">Home</a></li>

                <!-- Navigasi ke halaman buat voting (khusus creator) -->
                <li class="nav-item">
                    <a class="nav-link fw-bold <?= $role !== 'creator' ? 'disabled text-secondary' : '' ?>" 
                       href="<?= $role === 'creator' ? 'create_poll.php' : '#' ?>" 
                       onclick="<?= $role !== 'creator' ? 'alert(\'Hanya creator yang dapat membuat polling.\')' : '' ?>">
                        Buat Voting
                    </a>
                </li>

                <!-- Navigasi ke halaman ikut voting -->
                <li class="nav-item"><a class="nav-link fw-bold" href="vote.php">Ikut Voting</a></li>

                <!-- Navigasi ke halaman hasil voting -->
                <li class="nav-item"><a class="nav-link fw-bold" href="hasil_voting.php">Hasil Voting</a></li>

                <!-- Logout -->
                <li class="nav-item"><a class="nav-link fw-bold" href="logout.php">Log Out</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Judul Halaman -->
<div class="container">
   <div class="d-flex justify-content-center">
      <h3 class="judul-box">Hasil Voting</h3>
   </div>

   <!-- Jika ada data polling -->
   <?php if ($polls): ?>
        <?php foreach ($polls as $poll): ?>
            <div class="card-container">
                <div class="poll-title"><?= htmlspecialchars($poll['title']) ?></div>

                <?php
                // Ambil semua opsi dari polling
                $opt_stmt = $pdo->prepare("SELECT * FROM poll_options WHERE poll_id = ?");
                $opt_stmt->execute([$poll['id']]);
                $options = $opt_stmt->fetchAll();

                // Hitung total suara dalam polling ini
                $vote_stmt = $pdo->prepare("SELECT COUNT(*) AS total_votes FROM votes WHERE poll_id = ?");
                $vote_stmt->execute([$poll['id']]);
                $total_votes = $vote_stmt->fetch()['total_votes'];
                ?>

                <!-- Tampilkan setiap opsi voting -->
                <?php foreach ($options as $option): 
                    // Hitung jumlah suara untuk opsi ini
                    $count_stmt = $pdo->prepare("SELECT COUNT(*) AS count FROM votes WHERE option_id = ?");
                    $count_stmt->execute([$option['id']]);
                    $count = $count_stmt->fetch()['count'];
                    $percentage = $total_votes > 0 ? round(($count / $total_votes) * 100) : 0;
                ?>
                    <div class="option-box">
                        <?= htmlspecialchars($option['option_text']) ?>
                        <span class="badge-vote"><?= $count ?> suara (<?= $percentage ?>%)</span>
                    </div>
                <?php endforeach; ?>

                <!-- Tampilkan total suara -->
                <div class="total-vote">Total Suara: <?= $total_votes ?></div>

                <!-- Tombol ekspor ke PDF -->
                <div class="text-center mt-2">
                    <a href="../actions/export_pdf.php?poll_id=<?= $poll['id'] ?>" 
                       class="btn btn-success" target="_blank">
                        Ekspor ke PDF
                    </a>
                </div>

                <!-- Jika role creator, tampilkan tombol hapus -->
                <?php if ($role === 'creator'): ?>
                    <div class="text-center mt-3">
                        <a href="../actions/delete_poll.php?poll_id=<?= $poll['id'] ?>"
                           class="btn btn-hapus"
                           onclick="return confirm('Yakin ingin menghapus polling ini?')">
                            Hapus Voting
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <!-- Jika tidak ada hasil voting -->
        <p class="text-center"><em>Tidak ada hasil voting yang tersedia.</em></p>
    <?php endif; ?>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
