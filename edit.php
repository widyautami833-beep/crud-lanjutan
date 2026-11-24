<?php
require '../config/database.php';

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
session_start();
$error = $_SESSION['form_error'] ?? '';
unset($_SESSION['form_error']);
$old = $_SESSION['form_old'] ?? $siswa;
unset($_SESSION['form_old']);
$escape = static fn ($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Siswa</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<main class="container container-narrow">
    <div class="card">
        <div class="card-header"><h3>Edit Data Siswa</h3></div>
        <div class="card-body">
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= $escape($error) ?></div>
            <?php endif; ?>
            <form action="../proses/update.php" method="POST">
                <input type="hidden" name="id" value="<?= (int) $id ?>">
                <div class="form-group"><label class="form-label">NIS *</label><input name="nis" class="form-control" required value="<?= $escape($old['nis']) ?>"></div>
                <div class="form-group"><label class="form-label">Nama *</label><input name="nama" class="form-control" required value="<?= $escape($old['nama']) ?>"></div>
                <div class="form-group"><label class="form-label">Kelas *</label><input name="kelas" class="form-control" required value="<?= $escape($old['kelas']) ?>"></div>
                <div class="form-group"><label class="form-label">Jurusan *</label><input name="jurusan" class="form-control" required value="<?= $escape($old['jurusan']) ?>"></div>
                <div class="form-group"><label class="form-label">Alamat</label><textarea name="alamat" class="form-control"><?= $escape($old['alamat'] ?? '') ?></textarea></div>
                <div class="form-group"><label class="form-label">No HP</label><input name="no_hp" class="form-control" value="<?= $escape($old['no_hp'] ?? '') ?>"></div>
                <button type="submit" class="btn btn-warning">Update Data</button>
                <a href="../index.php" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</main>
</body>
</html>
