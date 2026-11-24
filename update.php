<?php
session_start();
require '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$fields = ['nis', 'nama', 'kelas', 'jurusan', 'alamat', 'no_hp'];
$data = [];
foreach ($fields as $field) {
    $data[$field] = trim($_POST[$field] ?? '');
}

if (!$id || $id < 1) {
    die('ID tidak valid.');
}
if ($data['nis'] === '' || $data['nama'] === '' || $data['kelas'] === '' || $data['jurusan'] === '') {
    $_SESSION['form_error'] = 'NIS, nama, kelas, dan jurusan wajib diisi.';
    $_SESSION['form_old'] = $data;
    header('Location: ../halaman/edit.php?id=' . $id);
    exit;
}

try {
    $stmt = $pdo->prepare(
        'UPDATE siswa SET nis = ?, nama = ?, kelas = ?, jurusan = ?, alamat = ?, no_hp = ? WHERE id = ?'
    );
    $stmt->execute([
        $data['nis'], $data['nama'], $data['kelas'],
        $data['jurusan'], $data['alamat'], $data['no_hp'], $id
    ]);
    $_SESSION['flash'] = 'diubah';
} catch (PDOException $e) {
    $_SESSION['form_error'] = 'Gagal memperbarui data. Periksa apakah NIS sudah digunakan.';
    $_SESSION['form_old'] = $data;
    header('Location: ../halaman/edit.php?id=' . $id);
    exit;
}

header('Location: ../index.php');
exit;
