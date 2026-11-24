<?php
session_start();
require_once __DIR__ . '/config/database.php';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$cari = trim($_GET['cari'] ?? '');
$limit = 5;
$page = max(1, (int) ($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;

$where = '';
$params = [];
if ($cari !== '') {
    $where = ' WHERE nis LIKE :cari OR nama LIKE :cari OR kelas LIKE :cari';
    $params['cari'] = "%$cari%";
}

$countStmt = $pdo->prepare('SELECT COUNT(*) FROM siswa' . $where);
$countStmt->execute($params);
$totalData = (int) $countStmt->fetchColumn();
$totalPage = (int) ceil($totalData / $limit);

$totalSiswa = (int) $pdo->query('SELECT COUNT(*) FROM siswa')->fetchColumn();
$totalJurusan = (int) $pdo->query(
    "SELECT COUNT(DISTINCT jurusan) FROM siswa WHERE jurusan <> ''"
)->fetchColumn();
$totalKelas = (int) $pdo->query(
    "SELECT COUNT(DISTINCT kelas) FROM siswa WHERE kelas <> ''"
)->fetchColumn();
if ($totalPage > 0 && $page > $totalPage) {
    $page = $totalPage;
    $offset = ($page - 1) * $limit;
}

$sql = 'SELECT * FROM siswa' . $where . ' ORDER BY id DESC LIMIT :limit OFFSET :offset';
$stmt = $pdo->prepare($sql);
foreach ($params as $key => $value) {
    $stmt->bindValue(':'.$key, $value, PDO::PARAM_STR);
}
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$data = $stmt->fetchAll();

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
$pesan = [
    'berhasil' => 'Data siswa berhasil ditambahkan.',
    'diubah' => 'Data siswa berhasil diperbarui.',
    'dihapus' => 'Data siswa berhasil dihapus.',
    'gagal' => 'Operasi gagal dilakukan.'
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manajemen Data Siswa</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="app-shell">
    <aside class="sidebar">
        <div class="brand">
            <span class="brand-mark">DS</span>
            <div>
                <strong>Data Sekolah</strong>
                <small>Admin Panel</small>
            </div>
        </div>
        <nav class="sidebar-nav" aria-label="Menu utama">
            <a class="nav-item active" href="index.php">
                <span class="nav-icon">⌂</span>
                Dashboard
            </a>
            <a class="nav-item" href="halaman/tambah.php">
                <span class="nav-icon">＋</span>
                Tambah Siswa
            </a>
        </nav>
    </aside>

    <main class="main-content">
        <header class="topbar">
            <div>
                <p class="eyebrow">Dashboard</p>
                <h2>Manajemen Data Siswa</h2>
            </div>
            <a href="halaman/tambah.php" class="btn btn-primary">+ Tambah Siswa</a>
        </header>

        <?php if ($flash): ?>
            <div class="alert alert-<?= $flash === 'gagal' ? 'danger' : 'success' ?>">
                <?= htmlspecialchars($pesan[$flash] ?? 'Operasi selesai.') ?>
            </div>
        <?php endif; ?>

        <section class="stats-grid" aria-label="Ringkasan data">
            <article class="stat-card stat-blue">
                <div class="stat-icon">👥</div>
                <div>
                    <span>Total Siswa</span>
                    <strong><?= $totalSiswa ?></strong>
                </div>
            </article>
            <article class="stat-card stat-green">
                <div class="stat-icon">🎓</div>
                <div>
                    <span>Jurusan</span>
                    <strong><?= $totalJurusan ?></strong>
                </div>
            </article>
            <article class="stat-card stat-orange">
                <div class="stat-icon">▦</div>
                <div>
                    <span>Kelas</span>
                    <strong><?= $totalKelas ?></strong>
                </div>
            </article>
            <article class="stat-card stat-purple">
                <div class="stat-icon">⌕</div>
                <div>
                    <span>Hasil Pencarian</span>
                    <strong><?= $totalData ?></strong>
                </div>
            </article>
        </section>

        <section class="data-section">
            <div class="section-header">
                <div>
                    <p class="eyebrow">Data siswa</p>
                    <h3>Daftar Siswa</h3>
                </div>
            </div>

            <form method="GET" class="search-form">
                <input type="text" name="cari" class="form-control"
                       placeholder="Cari nama, NIS, atau kelas..."
                       value="<?= htmlspecialchars($cari) ?>">
                <div class="search-actions">
                    <button class="btn btn-secondary">Cari</button>
                    <a href="index.php" class="btn btn-outline">Reset</a>
                </div>
            </form>

            <div class="table-wrapper">
        <table class="table">
            <thead>
            <tr>
                <th>No</th>
                <th>NIS</th>
                <th>Nama</th>
                <th>Kelas</th>
                <th>Aksi</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!$data): ?>
                <tr><td colspan="5">Tidak ada data.</td></tr>
            <?php else: ?>
                <?php $no = $offset + 1; ?>
                <?php foreach ($data as $siswa): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($siswa['nis']) ?></td>
                        <td><?= htmlspecialchars($siswa['nama']) ?></td>
                        <td><?= htmlspecialchars($siswa['kelas']) ?></td>
                        <td>
                        <div class="actions">
                            <a href="detail.php?id=<?= (int) $siswa['id'] ?>" class="btn btn-info btn-small">Detail</a>
                            <a href="halaman/edit.php?id=<?= (int) $siswa['id'] ?>" class="btn btn-warning btn-small">Edit</a>
                            <form action="proses/hapus.php" method="POST" class="inline-form"
                                  onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                <input type="hidden" name="id" value="<?= (int) $siswa['id'] ?>">
                                <input type="hidden" name="csrf_token"
                                       value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                                <button type="submit" class="btn btn-danger btn-small">Hapus</button>
                            </form>
                        </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
            </div>

            <?php if ($totalPage > 1): ?>
                <nav>
                    <ul class="pagination">
                        <?php for ($i = 1; $i <= $totalPage; $i++): ?>
                            <?php $query = http_build_query(['page' => $i, 'cari' => $cari]); ?>
                            <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                <a class="page-link" href="?<?= htmlspecialchars($query) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        </section>
    </main>
</div>
</body>
</html>
