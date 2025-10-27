<?php

namespace App\Traits;

trait FormatDataTrait
{
    /**
     * Konversi angka (1..4) ke Romawi
     */
    protected function toRoman($number)
    {
        return [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV'][$number] ?? 'I';
    }

    /**
     * Robust parsing number dari string Excel / Google Sheets
     * Meng-handle: "Rp 1.234.567", "(1.234.567)", "1.234.567,00", "66,67%", "0", dll
     * Mengembalikan float.
     */
    protected function parseNumber($val)
    {
        if ($val === null || $val === '') return 0.0;
        if (is_numeric($val)) return (float)$val;

        $s = trim((string)$val);

        // tanda negatif dalam kurung: (1.234) => -1234
        $negative = false;
        if (preg_match('/^\((.*)\)$/', $s, $m)) {
            $s = $m[1];
            $negative = true;
        }

        // hilangkan rp / persen / spasi / NBSP
        $s = str_ireplace(['rp', '%', ' ', "\xc2\xa0"], '', $s);

        // Jika ada titik dan koma (format Indonesia: 1.234,56)
        if (strpos($s, '.') !== false && strpos($s, ',') !== false) {
            $s = str_replace('.', '', $s); // Hapus titik ribuan
            $s = str_replace(',', '.', $s); // Ganti koma desimal dengan titik
        } elseif (strpos($s, ',') !== false && strpos($s, '.') === false) {
            // hanya koma -> koma sebagai desimal
            $s = str_replace(',', '.', $s);
        } elseif (strpos($s, '.') !== false && strpos($s, ',') === false) {
            // hanya titik -> titik kemungkinan ribuan -> hapus titik
            $s = str_replace('.', '', $s);
        }

        // hapus karakter selain digit, minus dan titik
        $s = preg_replace('/[^0-9\.\-]/', '', $s);

        if ($s === '' || $s === '.' || $s === '-') return 0.0;

        $num = (float)$s;
        if ($negative) $num = -$num;

        return $num;
    }
}
