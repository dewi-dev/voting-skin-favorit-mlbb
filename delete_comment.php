<?php
session_start();
require_once('config.php');

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_id'])) {
    $user_id = $_SESSION['user_id'];
    $comment_id = $_POST['comment_id'];

    $stmt = $db->prepare("SELECT * FROM comments WHERE id = ? AND user_id = ?");
    $stmt->execute([$comment_id, $user_id]);
    $comment = $stmt->fetch();

    if ($comment) {
        $delete = $db->prepare("DELETE FROM comments WHERE id = ?");
        $delete->execute([$comment_id]);
    }

    header('Location: results.php');
    exit;
}
?>