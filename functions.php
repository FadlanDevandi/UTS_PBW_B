<?php
// Konstanta tarif simulasi parkir.
define('TARIF_MOTOR', 3000);
define('TARIF_MOBIL', 5000);
define('BATAS_DURASI_MAKSIMAL', 720);

/**
 * Function procedural 1:
 * Menghitung biaya berdasarkan jenis dan durasi parkir.
 */
function hitungBiayaParkir(string $jenis, float $durasi): int
{
    $tarifPerJam = ($jenis === 'Motor') ? TARIF_MOTOR : TARIF_MOBIL;
    $durasiTagihan = max(1, (int) ceil($durasi));

    return $tarifPerJam * $durasiTagihan;
}

/**
 * Function procedural 2:
 * Menentukan status berdasarkan durasi aktual.
 */
function tentukanStatusParkir(float $durasi): string
{
    if ($durasi <= 2) {
        return 'Normal';
    } elseif ($durasi <= 5) {
        return 'Menengah';
    }

    return 'Parkir Lama';
}

/**
 * Function tambahan untuk format rupiah.
 */
function formatRupiah(int $nominal): string
{
    return 'Rp' . number_format($nominal, 0, ',', '.');
}

/**
 * Validasi input menggunakan WHILE.
 * Mengembalikan daftar pesan kesalahan; array kosong berarti valid.
 */
function validasiDataParkir(string $plat, string $jenis, $durasiInput): array
{
    $kesalahan = [];
    $dataWajib = [$plat, $jenis, $durasiInput];
    $label = ['Plat nomor', 'Jenis kendaraan', 'Durasi parkir'];
    $i = 0;

    while ($i < count($dataWajib)) {
        if ($dataWajib[$i] === '' || $dataWajib[$i] === null) {
            $kesalahan[] = $label[$i] . ' wajib diisi.';
        }
        $i++;
    }

    if (!in_array($jenis, ['Motor', 'Mobil'], true)) {
        $kesalahan[] = 'Jenis kendaraan tidak valid.';
    }

    if (!is_numeric($durasiInput)) {
        $kesalahan[] = 'Durasi harus berupa angka.';
    } else {
        $durasi = (float) $durasiInput;
        if ($durasi <= 0 || $durasi > BATAS_DURASI_MAKSIMAL) {
            $kesalahan[] = 'Durasi harus lebih dari 0 dan maksimal 720 jam.';
        }
    }

    // Teknik string: cek panjang plat setelah normalisasi.
    if ($plat !== '' && strlen($plat) < 3) {
        $kesalahan[] = 'Plat nomor terlalu pendek.';
    }

    return $kesalahan;
}
