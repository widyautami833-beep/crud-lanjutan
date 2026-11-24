<?php
session_start();
require '../config/database.php';
$error = $_SESSION['form_error'] ?? '';
unset($_SESSION['form_error']);
$old = $_SESSION['form_old'] ?? [];
unset($_SESSION['form_old']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Siswa</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<main class="container container-narrow">
    <div class="card">
        <div class="card-header"><h3>Tambah Data Siswa</h3></div>
        <div class="card-body">
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form action="../proses/tambah.php" method="POST">
                <div class="form-group"><label class="form-label">NIS *</label><input name="nis" class="form-control" required value="<?= htmlspecialchars($old['nis'] ?? '') ?>"></div>
                <div class="form-group"><label class="form-label">Nama *</label><input name="nama" class="form-control" required value="<?= htmlspecialchars($old['nama'] ?? '') ?>"></div>
                <div class="form-group"><label class="form-label">Kelas *</label><input name="kelas" class="form-control" required value="<?= htmlspecialchars($old['kelas'] ?? '') ?>"></div>
                <div class="form-group"><label class="form-label">Jurusan *</label><input name="jurusan" class="form-control" required value="<?= htmlspecialchars($old['jurusan'] ?? '') ?>"></div>
                <div class="form-group"><label class="form-label">Alamat</label><textarea name="alamat" class="form-control"><?= htmlspecialchars($old['alamat'] ?? '') ?></textarea></div>
                <div class="form-group"><label class="form-label">No HP</label><input name="no_hp" class="form-control" value="<?= htmlspecialchars($old['no_hp'] ?? '') ?>"></div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="../index.php" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</main>
</body>
</html>
