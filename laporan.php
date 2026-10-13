<?php
session_start();
require_once __DIR__ . '/functions.php';

$daftarParkir = $_SESSION['parkir'] ?? [];
$totalPendapatan = 0;
$totalKendaraan = count($daftarParkir);

// FOR: menghitung total pendapatan dari array transaksi.
$dataTransaksi = array_values($daftarParkir);
for ($i = 0; $i < count($dataTransaksi); $i++) {
    $totalPendapatan += (int) $dataTransaksi[$i]['biaya'];
}

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Parkir</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="topbar">
    <div class="container nav">
        <a class="brand" href="index.php">Parkir<span>Ku</span></a>
        <nav><a href="index.php">Input Kendaraan</a><a class="active" href="laporan.php">Laporan Parkir</a></nav>
    </div>
</header>
<main class="container">
    <section class="page-heading">
        <p class="eyebrow">REKAP TRANSAKSI</p>
        <h1>Laporan Parkir</h1>
        <p class="muted">Daftar transaksi yang tersimpan selama sesi browser ini aktif.</p>
    </section>

    <section class="summary-grid">
        <article class="summary-card"><span>Total kendaraan</span><strong><?= $totalKendaraan ?></strong></article>
        <article class="summary-card"><span>Total pendapatan simulasi</span><strong><?= formatRupiah($totalPendapatan) ?></strong></article>
    </section>

    <section class="panel table-panel">
        <div class="section-heading">
            <h2>Data Transaksi</h2>
            <a class="button secondary small" href="index.php">+ Transaksi Baru</a>
        </div>
        <div class="table-scroll">
            <table>
                <thead>
                    <tr>
                        <th>No.</th><th>Nomor Tiket</th><th>Plat Nomor</th><th>Jenis</th>
                        <th>Durasi</th><th>Tarif/Jam</th><th>Total Bayar</th><th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($totalKendaraan > 0): ?>
                    <?php
                    $nomor = 1;
                    // FOR: menampilkan semua data transaksi dalam tabel HTML.
                    for ($i = 0; $i < count($dataTransaksi); $i++):
                        $kodeTiket = array_keys($daftarParkir)[$i];
                        $data = $dataTransaksi[$i];
                    ?>
                    <tr>
                        <td><?= $nomor ?></td>
                        <td><?= e($kodeTiket) ?></td>
                        <td><?= e($data['plat']) ?></td>
                        <td><?= e($data['jenis']) ?></td>
                        <td><?= e(number_format((float) $data['durasi'], 1, ',', '.')) ?> jam</td>
                        <td><?= formatRupiah((int) $data['tarif']) ?></td>
                        <td class="money"><?= formatRupiah((int) $data['biaya']) ?></td>
                        <td><span class="pill"><?= e($data['status']) ?></span></td>
                    </tr>
                    <?php $nomor++; endfor; ?>
                <?php else: ?>
                    <tr><td colspan="8" class="empty">Belum ada transaksi. Silakan input kendaraan terlebih dahulu.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
    <p class="footnote">Data menggunakan session PHP, sehingga bukan penyimpanan permanen dan dapat hilang saat sesi berakhir.</p>
</main>
<footer><div class="container">Proyek Kelompok PBW • Integrasi Pertemuan 1–7</div></footer>
</body>
</html>
