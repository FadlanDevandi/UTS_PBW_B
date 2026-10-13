<?php
class Kendaraan
{
    public string $platNomor;
    public string $jenis;
    public float $durasiJam;
    public string $waktuMasuk;

    public function __construct(string $platNomor, string $jenis, float $durasiJam)
    {
        // Teknik string: trim, strtoupper, dan normalisasi spasi.
        $platNomor = preg_replace('/\s+/', ' ', trim($platNomor));
        $this->platNomor = strtoupper($platNomor);
        $this->jenis = $jenis;
        $this->durasiJam = $durasiJam;
        $this->waktuMasuk = date('d-m-Y H:i:s');
    }

    public function getInformasi(): string
    {
        return $this->platNomor . ' • ' . $this->jenis;
    }

    public function getTarifPerJam(): int
    {
        return ($this->jenis === 'Motor') ? TARIF_MOTOR : TARIF_MOBIL;
    }
}
