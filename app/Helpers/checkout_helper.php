<?php
/**
 * Checkout Helper
 *
 * Berisi fungsi-fungsi perhitungan untuk fitur Promo Akhir Tahun:
 * 1. Biaya Jasa (service fee)
 * 2. Diskon Voucher
 * 3. Free Mouse (hadiah langsung)
 */

if (!function_exists('get_voucher_list')) {
    /**
     * Daftar kode voucher yang tersedia beserta persentase diskonnya.
     */
    function get_voucher_list(): array
    {
        return [
            'PROMO2025'  => 0.10,
            'PROMO2026'  => 0.15,
            'AKHIRTAHUN' => 0.25,
        ];
    }
}

if (!function_exists('hitung_biaya_jasa')) {
    /**
     * Menghitung biaya jasa (service fee).
     * <= 10.000.000 => 1%
     * >  10.000.000 => 2%
     */
    function hitung_biaya_jasa(float $total_harga): float
    {
        if ($total_harga <= 10000000) {
            return $total_harga * 0.01;
        }

        return $total_harga * 0.02;
    }
}

if (!function_exists('hitung_diskon_voucher')) {
    /**
     * Menghitung nilai diskon voucher dari total harga pembelian.
     * Kode tidak valid / kosong => diskon 0.
     */
    function hitung_diskon_voucher(float $total_harga, ?string $voucher_code): float
    {
        $vouchers = get_voucher_list();
        $code     = strtoupper(trim((string) $voucher_code));

        if ($code === '' || !isset($vouchers[$code])) {
            return 0;
        }

        return $total_harga * $vouchers[$code];
    }
}

if (!function_exists('hitung_free_mouse')) {
    /**
     * Menentukan nilai free mouse (hadiah langsung).
     * Total > 15.000.000 => Rp150.000, selain itu 0.
     */
    function hitung_free_mouse(float $total_harga): float
    {
        return $total_harga > 15000000 ? 150000 : 0;
    }
}
