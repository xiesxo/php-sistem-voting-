<?php  
// Memulai sesi dan menghubungkan ke database
require '../includes/session.php';
require '../config/db.php'; //menghubungkan ke database

// Mengambil informasi role dan user_id dari sesi
$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Styles -->
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
            min-height: calc(100vh - 56px);
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
            margi
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

        /* Typing effect styles */
        #typing-text {
            display: inline-block;
            white-space: nowrap;
            overflow: hidden;
            border-right: 2px solid #6f42c1;
            animation: blinkCursor 0.8s step-end infinite;
            padding-right: 3px;
            font-weight: normal;
            vertical-align: top;
        }

        #typing-placeholder {
            visibility: hidden;
            pointer-events: none;
            user-select: none;
            position: absolute;
        }

        @keyframes blinkCursor {
            50% { border-color: transparent; }
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
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand text-white fw-bold" href="#">E-Vote</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link fw-bold" href="dashboard.php">Home</a></li>
                    <li class="nav-item">
                        <a class="nav-link fw-bold <?= $role !== 'creator' ? 'disabled text-secondary' : '' ?>" 
                           href="<?= $role === 'creator' ? 'create_poll.php' : '#' ?>" 
                           onclick="<?= $role !== 'creator' ? 'alert(\'Hanya creator yang dapat membuat polling.\')' : '' ?>">
                            Buat Voting
                        </a>
                    </li>
                    <li class="nav-item"><a class="nav-link fw-bold" href="vote.php">Ikut Voting</a></li>
                    <li class="nav-item"><a class="nav-link fw-bold" href="hasil_voting.php">Hasil Voting</a></li>
                    <li class="nav-item"><a class="nav-link fw-bold" href="logout.php">Log Out</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-container">
        <div class="dashboard-box">
            <div class="dashboard-row">
                <div class="dashboard-img m-4">
                    <img src="../assets/homeimage.jpg" class="img-fluid" style="width: 400px; height: 400px;" alt="vote">
                </div>
                <div class="dashboard-text">
                    <h2 class="fw-bold text-primary">Selamat datang</h2>
                    <h4 class="text-secondary pb-3 position-relative" style="height: 28px;">
                        <span id="typing-text" class="fw-normal text-dark"></span>
                        <!--Menampilkan username dan role dengan efek ketik-->
                        <span id="typing-placeholder" class="fw-normal text-dark d-inline-block">
                            <?= $_SESSION['username'] ?> (<?= $_SESSION['role'] ?>)
                        </span>
                    </h4>
                    <p class="my-3 mb-0 pb-5"><b><em>E-Vote: Voting Online Cepat & Aman | Fast & Secure Online Voting</em></b></p>
                    <p>Join the vote, your voice matters! 🙌</p>
                    <a href="vote.php" class="btn btn-custom">Ikuti Voting</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include '../includes/footer.php'; ?>

    <!-- Bootstrap Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Typing Effect Script -->
    <script>
        const texts = [
            "<?= $_SESSION['username'] ?> (<?= $_SESSION['role'] ?>)"
        ];
        const typingSpeed = 100;
        const erasingSpeed = 60;
        const delayAfterTyping = 2000;
        const delayAfterErasing = 500;

        let textIndex = 0;
        let charIndex = 0;
        let isDeleting = false;

        const typingElement = document.getElementById("typing-text");

        function type() {
            const currentText = texts[textIndex];

            if (!isDeleting) {
                typingElement.textContent = currentText.substring(0, charIndex++);
                if (charIndex <= currentText.length) {
                    setTimeout(type, typingSpeed);
                } else {
                    isDeleting = true;
                    setTimeout(type, delayAfterTyping);
                }
            } else {
                typingElement.textContent = currentText.substring(0, charIndex--);
                if (charIndex >= 0) {
                    setTimeout(type, erasingSpeed);
                } else {
                    isDeleting = false;
                    textIndex = (textIndex + 1) % texts.length;
                    setTimeout(type, delayAfterErasing);
                }
            }
        }

        document.addEventListener("DOMContentLoaded", () => {
            if (typingElement) type();
        });
    </script>
    <!-- End of Typing Effect Script -->
</body>
</html>
