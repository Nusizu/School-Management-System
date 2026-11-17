<?php

namespace App\Support;

use Illuminate\Support\Carbon;

/**
 * Pemformat tampilan terpusat supaya seluruh sistem konsisten.
 */
class Format
{
    /** Angka dengan pemisah ribuan titik, contoh: 10.000 */
    public static function number($value, int $decimals = 0): string
    {
        return number_format((float) $value, $decimals, ',', '.');
    }

    /** Tanggal pendek Indonesia, contoh: 1 Feb 2024 (opsional dengan jam). */
    public static function date($value, bool $withTime = false): string
    {
        if (blank($value)) {
            return '-';
        }

        $date = Carbon::parse($value)->locale('id');

        return $date->translatedFormat($withTime ? 'j M Y, H:i' : 'j M Y');
    }

    /** Tanggal panjang Indonesia, contoh: Minggu, 15 November 2026 (default: hari ini). */
    public static function longDate($value = null): string
    {
        return Carbon::parse($value ?? now())->locale('id')->translatedFormat('l, j F Y');
    }
}
