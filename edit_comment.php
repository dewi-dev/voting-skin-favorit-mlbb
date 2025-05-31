<?php
session_start();
require_once('config.php');

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Aktifkan error
ini_set('display_errors', 1);
error_reporting(E_ALL);

$user_id = $_SESSION['user_id'];

// Jika disubmit (update komentar)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_id'], $_POST['comment'])) {
    $comment_id = $_POST['comment_id'];
    $new_comment = trim($_POST['comment']);

// Pastikan komentar milik user yang sedang login
    $stmt = $db->prepare("SELECT * FROM comments WHERE id = ? AND user_id = ?");
    $stmt->execute([$comment_id, $user_id]);
    $comment = $stmt->fetch();

    if ($comment) {
        $update = $db->prepare("UPDATE comments SET comment = ? WHERE id = ?");
        $update->execute([$new_comment, $comment_id]);
    }

    header('Location: results.php');
    exit;
}

// Jika user baru ingin edit (GET via POST ID dari results.php)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_id'])) {
    $comment_id = $_POST['comment_id'];

// Ambil komentar
    $stmt = $db->prepare("SELECT * FROM comments WHERE id = ? AND user_id = ?");
    $stmt->execute([$comment_id, $user_id]);
    $comment = $stmt->fetch();

    if (!$comment) {
        echo "Komentar tidak ditemukan atau bukan milik Anda.";
        exit;
    }
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>Edit Komentar</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body class="bg-results">
        <div class="navbar">
            <a href="dashboard.php">🏠 Beranda</a>
            <a href="voting.php">🗳️ Voting</a>
            <a href="logout.php">🚪 Logout</a>
        </div>

        <div class="comments-section">
            <h2>Edit Komentar Anda</h2>
            <form method="POST" action="edit_comment.php">
                <input type="hidden" name="comment_id" value="<?= $comment['id'] ?>">
                <textarea name="comment" required><?= htmlspecialchars($comment['comment']) ?></textarea>
                <button type="submit">Simpan Perubahan</button>
            </form>
        </div>
    </body>
    </html>
    <?php
} else {
    header('Location: results.php');
    exit;
}
?>