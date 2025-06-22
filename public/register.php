<?php 
session_start();// Memulai sesi untuk menyimpan data pengguna (seperti login, error, dll.)
// Jika user sudah login, langsung arahkan ke dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php"); 
    exit;
}
// Menyimpan pesan error dari sesi, jika ada, lalu menghapusnya
$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@100..900&display=swap" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(to bottom right, #f4f4f4, #e6dff4);
            font-family: "League Spartan", sans-serif;
        }

        .login-card {
            max-width: 450px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
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

        .custom-radio .form-check-input {
            width: 1.5rem;
            height: 1.5rem;
            border: 2px solid #6f42c1;
            margin-top: 0.3rem;
            transition: all 0.3s ease;
        }

        .custom-radio .form-check-input:checked {
            background-color: #6f42c1;
            border-color: #6f42c1;
        }

        .custom-radio .form-check-input:hover {
            box-shadow: 0 0 0 0.2rem rgba(111, 66, 193, 0.3);
        }

        .custom-radio .form-check {
            margin: 0 20px;
        }

    </style>
</head>

<body class="d-flex align-items-center justify-content-center min-vh-100">
    <div class="container text-center">
        <div class="row justify-content-start  pt-3">
            <div class="col-aut">
                <span class="brand-text fs-1">E-Vote</span>
            </div>
        </div>
        <!-- Kartu Register -->
        <div class="row justify-content-center">
            <div class="login-card p-5 ">
                <h2 class="text-center fw-bold login-title mb-4">Register</h2>
                <?php if ($error): ?>
                    <p class="text-danger text-center"><?php echo $error; ?></p>
                <?php endif; ?>
                 <!-- Form register -->
                <form action="../actions/register_action.php" method="POST">
                    <div class="mb-3">
                        <input type="text" name="username" class="form-control form-control-custom" placeholder="Username" required>
                    </div>
                    <div class="mb-4">
                        <input type="password" name="password" class="form-control form-control-custom" placeholder="Password" required>
                    </div>
                    <!-- Pilihan role --><!-- Pilihan role -->
                    <div class="mb-4 text-center">
                        <label class="form-label fw-semibold mb-2">Daftar sebagai :</label>
                        <div class="d-flex justify-content-center align-items-center custom-radio">
                            <div class="form-check d-flex align-items-center">
                                <input class="form-check-input me-2" type="radio" name="role" id="creator" value="creator" required>
                                <label class="form-check-label" for="creator">Creator</label>
                            </div>
                            <div class="form-check d-flex align-items-center">
                                <input class="form-check-input me-2" type="radio" name="role" id="voter" value="voter" required>
                                <label class="form-check-label" for="voter">Voter</label>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-login w-100">Register</button>
                </form>
                    <!-- Link ke login -->
                <p class="text-center mt-2 mb-2">Sudah punya akun? <a href="login.php" class="register-link">Login disini</a></p>
            </div>
        </div>
    </div>
</body>
</html>
