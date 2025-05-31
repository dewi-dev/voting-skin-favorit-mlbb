<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Beranda - voting</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-dashboard">

    <div class="navbar">
        <a href="voting.php">🗳️ Voting</a>
        <a href="results.php">📊 Hasil</a>
        <a href="logout.php">🚪 Logout</a>
    </div>

    <!-- Ornamen emoji -->
    <div class="emoji-float">🌸💖</div>

    <!-- Animasi ucapan selamat datang -->
    <div class="welcome-message">
            <span class="star top-right">✨</span>
        <h1 class="fade-in">Selamat Datang di Voting Skin</h1>
        <h2 class="fade-in-delay">Cara Voting❓</h2>
        <ol class="fade-in-delay">
            <li class="fade-in-delay">Pergi ke halaman voting pada tombol di kanan atas↗️</li>
            <li class="fade-in-delay">Pilih skin favorit dan klik tombol vote di bawahnya⬇️</li>
            <li class="fade-in-delay">pergi ke halaman hasil untuk melihat rank vote↗️</li>
            <li class="fade-in-delay">Di bawah rank tambahkan komentar untuk skin-Mu💬</li>
        </ol>  
        <h2 class="fade-in-delay">📌CATATAN :</h2>
              <ul class="fade-in-delay">
            <li class="fade-in-delay">Kamu bisa mengubah dan menghapus vote skin-Mu‼</li>
            <li class="fade-in-delay">Kamu bisa edit dan hapus komentar yang dibuat</li>
            <li class="fade-in-delay">Kamu bisa logout dan login kembali dengan akun yang sama</li>
        </ul>
            <span class="star bottom-left">✨</span>
    </div>
</body>
</html>
