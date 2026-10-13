<?php
session_start();
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/classes/Kendaraan.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$platInput = (string) ($_POST['plat'] ?? '');
$jenisInput = (string) ($_POST['jenis'] ?? '');
$durasiInput = $_POST['durasi'] ?? '';

// Konversi string input ke float dilakukan setelah validasi numerik.
$platNormal = strtoupper(preg_replace('/\s+/', ' ', trim($platInput)));
$kesalahan = validasiDataParkir($platNormal, $jenisInput, $durasiInput);

if (count($kesalahan) > 0) {
    $_SESSION['pesan_error'] = implode(' ', $kesalahan);
    header('Location: index.php');
    exit;
}

$durasi = (float) $durasiInput;
$kendaraan = new Kendaraan($platNormal, $jenisInput, $durasi);
$biaya = hitungBiayaParkir($kendaraan->jenis, $kendaraan->durasiJam);
$status = tentukanStatusParkir($kendaraan->durasiJam);

// DO-WHILE: menghasilkan kode tiket dan memastikan loop berjalan minimal sekali.
do {
    $nomorTiket = 'PK-' . date('ymd-His') . '-' . random_int(100, 999);
} while (isset($_SESSION['parkir'][$nomorTiket]));

// Lima tipe data ditunjukkan dalam proyek:
// string ($platNormal), integer ($biaya), float ($durasi),
// boolean ($transaksiBerhasil), dan array ($dataTransaksi).
$transaksiBerhasil = true;
$dataTransaksi = [
    'plat' => $kendaraan->platNomor,
    'jenis' => $kendaraan->jenis,
    'durasi' => $kendaraan->durasiJam,
    'tarif' => $kendaraan->getTarifPerJam(),
    'biaya' => $biaya,
    'status' => $status,
    'waktu' => $kendaraan->waktuMasuk
];

if ($transaksiBerhasil) {
    $_SESSION['parkir'][$nomorTiket] = $dataTransaksi;
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
    <title>Transaksi Berhasil</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="topbar">
    <div class="container nav">
        <a class="brand" href="index.php">Parkir<span>Ku</span></a>
        <nav><a href="index.php">Input Kendaraan</a><a href="laporan.php">Laporan Parkir</a></nav>
    </div>
</header>
<main class="container result-wrap">
    <section class="panel result-panel">
        <div class="success-mark">✓</div>
        <p class="eyebrow">TRANSAKSI BERHASIL</p>
        <h1>Tiket parkir dibuat</h1>
        <p class="muted">Simpan nomor tiket berikut sebagai bukti transaksi.</p>
        <div class="ticket-code"><?= e($nomorTiket) ?></div>
        <div class="result-total">
            <span>Total pembayaran</span>
            <strong><?= formatRupiah($biaya) ?></strong>
        </div>
        <div class="detail-list">
            <div><span>Plat nomor</span><strong><?= e($kendaraan->platNomor) ?></strong></div>
            <div><span>Jenis kendaraan</span><strong><?= e($kendaraan->jenis) ?></strong></div>
            <div><span>Durasi aktual</span><strong><?= e(number_format($durasi, 1, ',', '.')) ?> jam</strong></div>
            <div><span>Durasi tagihan</span><strong><?= max(1, (int) ceil($durasi)) ?> jam</strong></div>
            <div><span>Tarif per jam</span><strong><?= formatRupiah($kendaraan->getTarifPerJam()) ?></strong></div>
            <div><span>Status parkir</span><strong><span class="pill"><?= e($status) ?></span></strong></div>
            <div><span>Waktu transaksi</span><strong><?= e($kendaraan->waktuMasuk) ?></strong></div>
        </div>
        <div class="actions">
            <a class="button primary" href="index.php">Input Kendaraan Lagi</a>
            <a class="button secondary" href="laporan.php">Lihat Laporan</a>
        </div>
    </section>
</main>
<footer><div class="container">Proyek Kelompok PBW • Integrasi Pertemuan 1–7</div></footer>
</body>
</html>
