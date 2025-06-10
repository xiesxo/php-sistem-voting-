<?php
require '../includes/session.php';
require '../config/db.php';

$user_id = $_SESSION['user_id'];
$poll_id = isset($_GET['poll_id']) ? $_GET['poll_id'] : null;

// Hanya validasi polling jika diperlukan
if ($poll_id) {
    $stmt = $pdo->prepare("SELECT * FROM polls WHERE id = ?");
    $stmt->execute([$poll_id]);
    $poll = $stmt->fetch(); // $poll akan berisi data polling atau false jika tidak ditemukan
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sukses</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(to bottom right, #f4f4f4, #e6dff4);
            flex-direction: column;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
            font-family: sans-serif; /* Font default untuk halaman */
        }

        .success-animation {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 60px;
            background-color:rgb(247, 247, 247);
            border-radius: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-align: center;
        }

        .message { /* Ini akan menggantikan h2 standar */
            background-color: #8a2be2; /* Ungu */
            color: white;
            padding: 12px 30px;
            border-radius: 25px;
            margin-bottom: 25px;
            font-size: 17px;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: inline-block;
        }

        .checkmark__circle {
            stroke-dasharray: 166;
            stroke-dashoffset: 166;
            stroke-width: 2;
            stroke-miterlimit: 10;
            stroke: #4CAF50; /* Warna hijau untuk lingkaran */
            fill: none;
            animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
        }

        .checkmark {
            width: 100px; /* Ukuran ikon */
            height: 100px;
            border-radius: 50%;
            display: block;
            stroke-width: 3;
            stroke: #fff; /* Warna tanda centang */
            stroke-miterlimit: 10;
            margin-bottom: 25px;
            box-shadow: inset 0px 0px 0px #4CAF50;
            animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
        }

        .checkmark__check {
            transform-origin: 50% 50%;
            stroke-dasharray: 48;
            stroke-dashoffset: 48;
            animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
        }

        @keyframes stroke {
            100% {
                stroke-dashoffset: 0;
            }
        }

        @keyframes scale {
            0%, 100% {
                transform: none;
            }
            50% {
                transform: scale3d(1.1, 1.1, 1);
            }
        }

        @keyframes fill {
            100% {
                box-shadow: inset 0px 0px 0px 60px #4CAF50; /* Mengisi lingkaran */
            }
        }

        .button-kembali {
            background-color: #333; /* Hitam */
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 15px;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none; /* Menghilangkan underline dari <a> */
            display: inline-block; /* Agar padding dan style lain bekerja baik */
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 5px;
    
        }

        .button-kembali:hover {
            background-color: #555;
        }

        .session-message { /* Styling untuk pesan session tambahan */
            margin-top: 15px;
            padding: 10px;
            background-color: #e7f4e7; /* Latar hijau muda */
            border: 1px solid #c8e6c9; /* Border hijau lebih tua */
            color: #2e7d32; /* Teks hijau tua */
            border-radius: 5px;
            max-width: 90%; /* Agar tidak terlalu lebar di layar besar */
        }
    </style>

    
</head>
<body>
    <!-- Animasi sukses setelah voting berhasil -->
    <div class="success-animation">
        <!-- Pesan teks animasi -->
        <p class="message">🥳Suara Berhasil Disimpan🥳</p>

        <!-- SVG checkmark animasi sebagai simbol sukses -->
        <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
            <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
            <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
        </svg>

        <!-- Tombol kembali ke dashboard -->
        <a href='dashboard.php' class="button-kembali">Kembali</a>
    </div>

    <!-- Menampilkan pesan dari sesi jika tersedia -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="session-message">
            <?= htmlspecialchars($_SESSION['message']); // Lindungi dari XSS dengan htmlspecialchars ?>
        </div>
        <?php unset($_SESSION['message']); // Hapus pesan agar tidak muncul lagi saat refresh ?>
    <?php endif; ?>

</body>
</html>