<?php
session_start();
require_once('config.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

//Ambil data skin dan vote
$stmt = $db->query("
    SELECT s.id, s.name, s.image, s.description, COUNT(v.id) AS votes
    FROM skins s
    LEFT JOIN votes v ON s.id = v.skin_id
    GROUP BY s.id
    ORDER BY votes DESC
");
$skins = $stmt->fetchAll(PDO::FETCH_ASSOC);

//Ambil komentar
$comments = $db->query("
    SELECT c.id, c.comment, c.created_at, c.user_id, u.username, s.name AS skin_name
    FROM comments c
    JOIN users u ON c.user_id = u.id
    JOIN skins s ON c.skin_id = s.id
    ORDER BY c.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);

//Tambah komentar
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $skin_id = $_POST['skin_id'];
    $comment = $_POST['comment'];
    $stmt = $db->prepare("INSERT INTO comments (user_id, skin_id, comment) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $skin_id, $comment]);
    header("Location: results.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hasil Voting Skin Favorit</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-results">

 <div class="emoji-float">🌸💖</div>
    
    <div class="navbar">
        <a href="dashboard.php">🏠 Beranda</a>
        <a href="voting.php">🗳️ Voting</a>
        <a href="logout.php">🚪 Logout</a>
    </div>

<!-- Judul dengan Emoji Bintang -->
<div class="results-header">
    <span class="star top-right">✨</span>
    <h1 class="title-center">📊 Hasil Voting Skin Favorit</h1>
    <span class="star bottom-left">✨</span>
</div>

<!-- Diagram Hasil Voting -->
<div class="results-container">
    <?php foreach ($skins as $index => $skin): ?>
        <div class="result-item">
            <div class="rank-number"><?= $index + 1 ?>.</div>
            <img src="assets/images/<?= $skin['image'] ?>" alt="<?= $skin['name'] ?>" class="result-img">
            <div class="result-skin-info">
                <strong class="skin-name"><?= $skin['name'] ?></strong>
                <div class="bar-vote">
                    <div class="bar-fill" style="width: <?= $skin['votes'] * 10 ?>px;">
                        <?= $skin['votes'] ?><?= $skin['votes'] != 1 ? 's' : '' ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

    <!-- Komentar -->
    <div class="comments-section">
        <h2>💬 Komentar</h2>
        <form method="POST">
            <select name="skin_id" required>
                <option value="">Pilih Skin</option>
                <?php foreach ($skins as $skin): ?>
                    <option value="<?= $skin['id'] ?>"><?= $skin['name'] ?></option>
                <?php endforeach; ?>
            </select>
            <textarea name="comment" placeholder="Tulis komentar..." required></textarea>
            <button type="submit">Kirim Komentar</button>
        </form>

        <div class="comments-list">
    <?php foreach ($comments as $index => $comment): ?>
        <div class="comment-box">
            <div class="star-corner">✨</div>
            <strong>No <?= $index + 1 ?>. <?= htmlspecialchars($comment['username']) ?> (<?= htmlspecialchars($comment['skin_name']) ?>)</strong>
            <p><?= htmlspecialchars($comment['comment']) ?></p>
            <small><?= $comment['created_at'] ?></small>

            <?php if ($comment['user_id'] == $user_id): ?>
                <div class="comment-actions">
                    <form method="POST" action="edit_comment.php" style="display:inline;">
                        <input type="hidden" name="comment_id" value="<?= $comment['id'] ?>">
                        <button type="submit">Edit</button>
                    </form>
                    <form method="POST" action="delete_comment.php" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus komentar ini?');">
                        <input type="hidden" name="comment_id" value="<?= $comment['id'] ?>">
                        <button type="submit">Hapus</button>
                    </form>
                </div>
            <?php endif; ?>
            </div>
        <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
