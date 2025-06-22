<?php
require '../includes/session.php';
require '../config/db.php';

// Ambil user_id dan role dari session
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Query untuk mengambil polling yang belum diikuti user dan masih aktif
$stmt = $pdo->prepare("
  SELECT * FROM polls 
  WHERE id NOT IN (
    SELECT poll_id FROM votes WHERE user_id = ?
  ) 
  AND deadline >= NOW()
");
$stmt->execute([$user_id]);
$available_polls = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Ikuti Voting</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

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
    .container-box {
      background-color: white;
      border-radius: 20px;
      padding: 2rem;
      max-width: 450px;
      margin: auto;
    }

    .vote-title {
      font-weight: 700;
      color: #9b51e0;
      font-size: 1.2rem;
      text-align: center;
      margin-bottom: 1.5rem;
    }

    .form-check {
      display: flex;
      align-items: center;
      background-color: white;
      border: 2px solid #9b51e0;
      border-radius: 50px;
      padding: 0.5rem 1rem;
      margin-bottom: 1rem;
    }

    .form-check-input {
      width: 24px;
      height: 24px;
      margin-right: 0.75rem;
      border: 2px solid #6a0dad;
      accent-color: #9b51e0;
    }

    .form-check-label {
      flex-grow: 1;
      padding: 0.5rem 1rem;
      border: 1px solid #6a0dad;
      border-radius: 25px;
      background-color: #f9f5ff;
      color: #6a0dad;
      font-weight: 500;
    }

    .form-check-input:checked {
      background-color: #6f42c1; 
      border-color: #6f42c1;
    }

    .form-check-input:focus {
      box-shadow: 0 0 0 0.25rem rgba(111, 66, 193, 0.25);
    }

    .btn-vote {
      background-color: #6a0dad; 
      color: white;
      border: none;
      border-radius: 50px;
      font-weight: 600;
      padding: 0.5rem 1.5rem;
      transition: background-color 0.3s ease, transform 0.3s ease;
      }

      .btn-vote:hover {
      background-color: #8e44ec; 
      transform: scale(1.05); 
      }

    .vote-section {
      display: flex;
      justify-content: space-around;
      align-items: start;
      flex-wrap: wrap;
      margin-top: 3rem;
    }

    .vote-placeholder {
      border: 3px dashed #a55fed;
      border-radius: 20px;
      padding: 3rem;
      text-align: center;
      color: gray;
      width: 100%;
      max-width: 400px;
      height: 100%;
      background-color: #f2f0f8;
    }

    .heading-center {
      text-align: center;
      font-weight: bold;
      color: #6a0dad;
      background-color: white;
      padding: 0.5rem 2rem;
      border-radius: 25px;
      display: inline-block;
      margin: auto;
    }

     .text-purple {
      color: #9b51e0;
    }

    .card {
      border-radius: 20px;
      background-color: #fff;
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

  <!-- ===== Konten Utama Voting ===== -->
  <div class="container my-5 ">
    <div class="d-flex justify-content-center">
      <h3 class="fw-bold bg-white rounded-pill py-3 px-4 d-inline-block text-purple mb-3 shadow-sm">
      Pilihan Voting
      </h3>
    </div>

    <!-- ===== Pesan Sukses dari Session ===== -->
    <?php if (isset($_SESSION['message'])): ?>
      <div class="alert alert-success text-center"><?= $_SESSION['message'] ?></div>
      <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <!-- ===== Jika Tidak Ada Polling ===== -->
    <?php if (empty($available_polls)): ?>
      <div class="alert alert-warning text-center rounded-4 p-5 fs-5 fw-semibold">
        Polling baru belum tersedia.
      </div>
    <?php else: ?>
      <!-- ===== Daftar Polling yang Bisa Diikuti ===== -->
      <div class="row justify-content-center">
        <?php foreach ($available_polls as $poll): ?>
          <div class="col-md-6 col-lg-4 mb-4">
            <div class="card shadow-sm rounded-4 p-4">
              <h4 class="text-center mb-4 text-capitalize fw-bold text-primary"><?= htmlspecialchars($poll['title']) ?></h4>
              <!-- ===== Form Voting ===== -->
              <form method="POST" action="../actions/vote_action.php">
                `<?php
                // Ambil opsi untuk polling ini
                $opt_stmt = $pdo->prepare("SELECT * FROM poll_options WHERE poll_id = ?");
                $opt_stmt->execute([$poll['id']]);
                $options = $opt_stmt->fetchAll();
                ?>
                <div class="vstack gap-2">
                  <?php foreach ($options as $option): ?>
                    <div class="form-check border rounded-pill px-5 py-2">
                      <input class="form-check-input me-2" type="radio" name="option_id" id="option<?= $option['id'] ?>" value="<?= $option['id'] ?>" required>
                      <label class="form-check-label fw-semibold text-capitalize" for="option<?= $option['id'] ?>">
                        <?= htmlspecialchars($option['option_text']) ?>
                      </label>
                    </div>
                  <?php endforeach; ?>
                </div>

                <input type="hidden" name="poll_id" value="<?= $poll['id'] ?>">
                <div class="d-grid mt-4">
                  <button type="submit" class="btn btn-vote">Kirim Suara</button>
                </div>
              </form>
              <!-- ===== End Form Voting ===== -->
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <!-- ===== End Daftar Polling ===== -->
    <?php endif; ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
