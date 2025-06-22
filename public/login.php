<?php 
    session_start(); // Mulai session agar bisa mengakses data session
    // Jika user sudah login (ada session user_id), langsung arahkan ke dashboard
    if (isset($_SESSION['user_id'])) {
        header("Location: dashboard.php");
        exit;
    }
    // Ambil pesan error dari session jika ada, lalu hapus supaya tidak muncul terus
    $error = $_SESSION['error'] ?? '';
    unset($_SESSION['error']);  
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login</title>

    <!-- Bootstrap CSS untuk gaya responsif dan elemen siap pakai -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts: League Spartan -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@100..900&display=swap" rel="stylesheet">

    <!-- Gaya kustom -->
    <style>
        body {
            background: linear-gradient(to bottom right, #f4f4f4, #e6dff4); /* Gradien latar */
            font-family: "League Spartan", sans-serif;
        }

        .login-card {
            max-width: 450px; /* Batas lebar form */
            background: white;
            border-radius: 20px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.1); /* Bayangan halus */
        }

        .login-title {
            font-size: 2rem;
            font-weight: bold;
            color: #5b1fa6;
        }

        .form-control-custom {
            border-radius: 25px;
            padding: 12px 20px;
            font-size: 1rem;
            border: 1px solid #7d4fcb;
        }

        .form-control-custom:focus {
            box-shadow: 0 0 0 0.2rem rgba(123, 80, 227, 0.25);
            border-color: #7b50e3;
        }

        .btn-login {
            background-color: #5b1fa6;
            color: white;
            border-radius: 25px;
            padding: 10px 0;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background-color: #3f1279;
            transform: scale(1.03);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.3);
        }

        .register-link {
            color: #5b1fa6;
            text-decoration: none;
        }

        .register-link:hover {
            text-decoration: underline;
        }

        .brand-text {
            font-weight: bold;
            font-size: 1.8rem;
            color: #5b1fa6;
        }

        .background-pattern {
            background: linear-gradient(to top left, #f8f8fc, #eee6fa); /* Tidak digunakan di body */
        }
    </style>
</head>
<body class="justify-content-center min-vh-100">
    <div class="container text-center">
        <!-- Logo / Judul Aplikasi -->
        <div class="row justify-content-start mb-2 p-4">
            <div class="col-aut">
                <span class="brand-text fs-1">E-Vote</span>
            </div>
        </div>

        <!-- Form Login -->
        <div class="row justify-content-center">
            <div class="login-card p-5">
                <h2 class="text-center fw-bold login-title mb-4">Login</h2>

                <!-- Menampilkan pesan error jika ada -->
                <?php if ($error): ?>
                    <p class="text-danger text-center"><?php echo $error; ?></p>
                <?php endif; ?>

                <!-- Form login -->
                <form method="POST" action="../actions/login_action.php">
                    <!-- Input username -->
                    <div class="mb-3">
                        <input type="text" name="username" class="form-control form-control-custom" required placeholder="Username">
                    </div>

                    <!-- Input password -->
                    <div class="mb-4">
                        <input type="password" name="password" class="form-control form-control-custom" required placeholder="Password">
                    </div>

                    <!-- Tombol login -->
                    <button type="submit" class="btn btn-login w-100">Log in</button>
                </form>

                <!-- Link ke halaman registrasi -->
                <p class="text-center mt-4">Belum punya akun? <a href="register.php" class="register-link">Register disini</a></p>
            </div>
        </div>
    </div>
</body>
</html>
