<?php
require 'config/database.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    die('ID tidak valid.');
}

$stmt = $pdo->prepare('SELECT * FROM siswa WHERE id = ?');
$stmt->execute([$id]);
$siswa = $stmt->fetch();
if (!$siswa) {
    die('Data siswa tidak ditemukan.');
}

$escape = static fn ($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Siswa</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<main class="container container-narrow">
    <div class="card">
        <div class="card-header"><h3>Detail Data Siswa</h3></div>
        <div class="card-body">
            <dl class="detail-list">
                <dt>NIS</dt><dd><?= $escape($siswa['nis']) ?></dd>
                <dt>Nama</dt><dd><?= $escape($siswa['nama']) ?></dd>
                <dt>Kelas</dt><dd><?= $escape($siswa['kelas']) ?></dd>
                <dt>Jurusan</dt><dd><?= $escape($siswa['jurusan']) ?></dd>
                <dt>Alamat</dt><dd><?= nl2br($escape($siswa['alamat'] ?? '')) ?></dd>
                <dt>No HP</dt><dd><?= $escape($siswa['no_hp'] ?? '') ?></dd>
            </dl>
        </div>
        <div class="card-footer">
            <a href="index.php" class="btn btn-secondary">Kembali</a>
            <a href="halaman/edit.php?id=<?= (int) $siswa['id'] ?>" class="btn btn-warning">Edit</a>
        </div>
    </div>
</main>
</body>
</html>
