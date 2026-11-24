<?php
session_start();
require '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}

if (
    empty($_SESSION['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')
) {
    die('Permintaan tidak valid.');
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    die('ID tidak valid.');
}

try {
    $stmt = $pdo->prepare('DELETE FROM siswa WHERE id = ?');
    $stmt->execute([$id]);
    $_SESSION['flash'] = 'dihapus';
} catch (PDOException $e) {
    $_SESSION['flash'] = 'gagal';
}

header('Location: ../index.php');
exit;
