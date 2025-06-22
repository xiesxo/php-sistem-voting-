<?php 
// Memuat file session untuk memastikan pengguna login
require '../includes/session.php';

// Memuat konfigurasi koneksi database
require '../config/db.php';

// Mengambil informasi user dari sesi
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Jika role adalah 'voter', arahkan ke dashboard karena hanya 'creator' yang bisa membuat polling
if ($role === 'voter') {
  header('Location: dashboard.php');
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Buat Polling</title>

  <!-- Memuat Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Gaya tampilan halaman -->
  <style>
    html, body {
      height: 100%;
      margin: 0;
      padding: 0;
      background: #f4f0fb;
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

    .form-container {
      background-color: white;
      border-radius: 15px;
      padding: 40px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      max-width: 600px;
      margin: auto;
    }

    label {
      font-weight: 500;
      color: #6f42c1;
    }

    input.form-control, .form-control:focus {
      border-radius: 25px;
      border: 1px solid #b99bdb;
    }

    .btn-primary {
      background-color: #6f42c1;
      border: none;
      border-radius: 25px;
      padding: 10px 25px;
      font-weight: bold;
    }

    .btn-primary:hover {
      background-color: #5a34a1;
      transform: scale(1.02);
    }

    .btn-outline {
      border: 1px solid #6f42c1;
      color: #6f42c1;
      border-radius: 25px;
      padding: 6px 16px;
    }

    .btn-outline:hover {
      background-color: #6f42c1;
      color: white; 
    }
  </style>
</head>

<body>
<div class="main-wrapper">

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

  <!-- Form Buat Polling -->
  <div class="content my-2 mb-5 mt-5">
    <div class="form-container">
      <h3 class="text-center mb-4 text-primary fw-bold">Buat Polling Baru</h3>
      
      <!-- Form pengisian polling baru -->
      <form action="../actions/create_poll_action.php" method="POST">
        <!-- Input Judul Polling -->
        <div class="mb-3">
          <label for="title">Judul Polling</label>
          <input type="text" name="title" class="form-control" placeholder="Judul polling..." required>
        </div>

        <!-- Input Batas Waktu Polling -->
        <div class="mb-3">
          <label for="deadline">Batas Waktu</label>
          <input type="datetime-local" name="deadline" class="form-control" required>
        </div>

        <!-- Input opsi pilihan polling -->
        <div id="options-container">
          <label>Opsi</label>
          <input type="text" name="options[]" class="form-control mb-2" placeholder="Opsi 1" required>
          <input type="text" name="options[]" class="form-control mb-2" placeholder="Opsi 2" required>
        </div>

        <!-- Tombol untuk menambah opsi polling -->
        <button type="button" class="btn btn-outline mt-2" onclick="addOption()">Tambah Opsi</button>

        <!-- Tombol submit polling -->
        <div class="text-end mt-4">
          <button type="submit" class="btn btn-primary">Simpan Polling</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Fungsi JavaScript untuk menambah input opsi polling -->
<script>
  function addOption() {
    const container = document.getElementById('options-container');
    const input = document.createElement('input');
    input.type = 'text';
    input.name = 'options[]';
    input.className = 'form-control mt-2';
    input.placeholder = 'Opsi tambahan';
    input.required = true;
    container.appendChild(input);
  }
</script>
    <!-- Memuat footer -->
    <?php include '../includes/footer.php'; ?>
<!-- Memuat Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
