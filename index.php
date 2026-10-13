<?php
session_start();
require_once __DIR__ . '/functions.php';

if (!isset($_SESSION['parkir'])) {
    $_SESSION['parkir'] = [];
}

$pesanError = $_SESSION['pesan_error'] ?? '';
unset($_SESSION['pesan_error']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Parkir Kendaraan</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="topbar">
    <div class="container nav">
        <a class="brand" href="index.php">Parkir<span>Ku</span></a>
        <nav>
            <a class="active" href="index.php">Input Kendaraan</a>
            <a href="laporan.php">Laporan Parkir</a>
        </nav>
    </div>
</header>

<main class="container">
    <section class="hero">
        <div>
            <p class="eyebrow">SISTEM MANAJEMEN PARKIR</p>
            <h1>Parkir lebih mudah,<br><span>biaya lebih terukur.</span></h1>
            <p class="muted">Masukkan data kendaraan untuk menghitung biaya parkir dan membuat tiket transaksi.</p>
        </div>
        <div class="hero-icon" aria-hidden="true">P</div>
    </section>

    <?php if ($pesanError !== ''): ?>
        <div class="alert error"><?= htmlspecialchars($pesanError, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <section class="panel form-panel">
        <div class="section-heading">
            <div>
                <p class="eyebrow">TRANSAKSI BARU</p>
                <h2>Input Kendaraan</h2>
            </div>
            <span class="pill">Tarif simulasi</span>
        </div>

        <form action="proses.php" method="POST" class="parking-form">
            <div class="field">
                <label for="plat">Plat nomor</label>
                <input id="plat" name="plat" type="text" placeholder="Contoh: B 1234 ABC" maxlength="15" required>
                <small>Spasi di awal/akhir akan dihapus otomatis.</small>
            </div>
            <div class="field">
                <label for="jenis">Jenis kendaraan</label>
                <select id="jenis" name="jenis" required>
                    <option value="Motor">Motor — Rp3.000/jam</option>
                    <option value="Mobil">Mobil — Rp5.000/jam</option>
                </select>
            </div>
            <div class="field">
                <label for="durasi">Durasi parkir (jam)</label>
                <input id="durasi" name="durasi" type="number" min="0.1" max="720" step="0.1" placeholder="Contoh: 2.5" required>
                <small>Durasi tagihan dibulatkan ke atas per jam, minimal 1 jam.</small>
            </div>
            <button class="button primary" type="submit">Hitung Biaya & Buat Tiket <span>→</span></button>
        </form>
    </section>

    <section class="rate-grid">
        <article class="rate-card">
            <div class="rate-icon">🏍</div>
            <p class="muted">Tarif motor</p>
            <h3>Rp3.000 <small>/ jam</small></h3>
        </article>
        <article class="rate-card">
            <div class="rate-icon">🚗</div>
            <p class="muted">Tarif mobil</p>
            <h3>Rp5.000 <small>/ jam</small></h3>
        </article>
        <article class="rate-card">
            <div class="rate-icon">📋</div>
            <p class="muted">Transaksi tersimpan</p>
            <h3><?= count($_SESSION['parkir']) ?> <small>kendaraan</small></h3>
        </article>
    </section>
    <p class="footnote">Catatan: tarif di aplikasi ini hanya untuk simulasi tugas, bukan tarif parkir resmi.</p>
</main>
<footer><div class="container">Proyek Kelompok PBW • Integrasi Pertemuan 1–7</div></footer>
</body>
</html>
