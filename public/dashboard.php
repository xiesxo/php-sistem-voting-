<?php 
// Memulai sesi dan menghubungkan ke database
require '../includes/session.php';
require '../config/db.php';

// Mengambil informasi role dan user_id dari sesi
$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>

    <!-- Memuat Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Gaya kustom untuk tampilan dashboard -->
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            background: #f4f0fb;
            overflow-x: hidden;
        }

        .navbar {
            background-color: #6f42c1;
        }

        .navbar .nav-link {
            color: #fff;
            transition: 0.3s;
        }

        .navbar .nav-link:hover {
            color: #ffc107;
        }

        .main-container {
            min-height: calc(100vh - 56px); /* agar konten tidak tertutup navbar */
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 15px;
        }

        .dashboard-box {
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
        }

        .dashboard-row {
            display: flex;
            flex-wrap: wrap;
        }

        .dashboard-img {
            flex: 1 1 300px;
            max-width: 50%;
        }

        .dashboard-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .dashboard-text {
            flex: 1 1 300px;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
        }

        .btn-custom {
            background-color: #6f42c1;
            color: #fff;
            border-radius: 25px;
            padding: 0.6rem 1.5rem;
            font-weight: bold;
        }

        .btn-custom:hover {
            background-color: #5a32a3;
            color: #ffc107;
        }

        @media (max-width: 768px) {
            .dashboard-img, .dashboard-text {
                max-width: 100%;
                flex: 1 1 100%;
            }
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

<!-- Konten Utama Dashboard -->
<div class="main-container">
    <div class="dashboard-box">
        <div class="dashboard-row">
            
            <!-- Gambar sambutan -->
            <div class="dashboard-img m-4">
                <img src="../assets/homeimage.jpg" class="img-fluid" style="width: 400px; height: 400px;" alt="vote">
            </div>

            <!-- Teks sambutan dan tombol -->
            <div class="dashboard-text">
                <h2 class="fw-bold text-primary ">Selamat datang</h2>
                <h4 class="text-secondary pb-3"><?= $_SESSION['username'] ?> (<?= $_SESSION['role'] ?>)</h4>
                <p class="my-3 mb-0 pb-5"><b><em>E-Vote: Voting Online Cepat & Aman | Fast & Secure Online Voting</em></b></p>
                <p>Join the vote — your voice matters! 🙌</p>
                
                <!-- Tombol langsung ke voting -->
                <a href="vote.php" class="btn btn-custom ">Ikuti Voting</a>
            </div>
        </div>
    </div>
</div>

<!-- Script Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
