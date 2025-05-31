<?php
session_start();
require_once('config.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

//Cek vote user
$stmt = $db->prepare("SELECT * FROM votes WHERE user_id = ?");
$stmt->execute([$user_id]);
$vote = $stmt->fetch(PDO::FETCH_ASSOC);
$hasVoted = $vote !== false;

//hapus vote
if (isset($_GET['delete']) && $hasVoted) {
    $stmt = $db->prepare("DELETE FROM votes WHERE user_id = ?");
    $stmt->execute([$user_id]);
    header("Location: voting.php");
    exit();
}

//submit vote
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $skin_id = $_POST['skin_id'];

    if ($hasVoted) {
        $stmt = $db->prepare("UPDATE votes SET skin_id = ? WHERE user_id = ?");
        $stmt->execute([$skin_id, $user_id]);
    } else {
        $stmt = $db->prepare("INSERT INTO votes (user_id, skin_id) VALUES (?, ?)");
        $stmt->execute([$user_id, $skin_id]);
    }

    header("Location: results.php");
    exit();
}

//Ambil semua skin
$skins = $db->query("SELECT * FROM skins")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Voting Skin Favorit</title>
    <link rel="stylesheet" href="style.css">
    <script>
    function confirmVote(skinId) {
        if (confirm("Apakah kamu yakin memilih skin ini?")) {
            document.getElementById('voteForm' + skinId).submit();
        }
    }
    function confirmDelete() {
        return confirm("Apakah kamu yakin ingin menghapus pilihanmu?");
    }
    </script>
</head>
<body class="bg-voting">

    <div class="navbar">
        <a href="dashboard.php">🏠 Beranda</a>
        <a href="results.php">📊 Hasil</a>
        <a href="logout.php">🚪 Logout</a>
    </div>

    <div class="emoji-float">🌸💖</div>
    <!-- Judul dengan Emoji Bintang -->
    <div class="welcome-message">
    <span class="star top-right">✨</span>
    <h1 class="title-center">🗳️ Voting Skin Favorit</h1>
    <span class="star bottom-left">✨</span>
    </div>

    <div class="grid-container">
        <?php foreach ($skins as $skin): ?>
            <div class="skin-card">
                <span class="star top-right">✨</span>
                <img src="assets/images/<?= $skin['image'] ?>" alt="<?= $skin['name'] ?>">
                <h3><?= $skin['name'] ?></h3>
                <p><?= $skin['description'] ?></p>

            <div class="vote-actions">
            <form method="POST" id="voteForm<?= $skin['id'] ?>" class="vote-form">
                <input type="hidden" name="skin_id" value="<?= $skin['id'] ?>">
                <button type="button" onclick="confirmVote(<?= $skin['id'] ?>)">
                <?= ($hasVoted && $vote['skin_id'] == $skin['id']) ? "Memilih Ini" : ($hasVoted ? "Ganti" : "Vote 💌") ?>
                </button>
            </form>

        <?php if ($hasVoted && $vote['skin_id'] == $skin['id']): ?>
            <form method="GET" action="voting.php" onsubmit="return confirmDelete();" class="delete-form">
                <input type="hidden" name="delete" value="true">
                <button type="submit" class="delete-button">Hapus Vote</button>
            </form>
        <?php endif; ?>
        </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>