<?php

use Illuminate\Support\Facades\DB;

if (!function_exists('photo_price')) {
    function photo_price(string $photoName): float
    {
        return (float) DB::table('photo_packages')
            ->where('name', $photoName)
            ->value('price') ?? 0;
    }
}

if (!function_exists('additional_seat_price')) {
    function additional_seat_price(string $seattype): float
    {
        return (float) DB::table('additional_seat_packages')
            ->where('name', $seattype)
            ->value('price') ?? 0;
    }
}

if (!function_exists('shuttle_seat_price')) {
    function shuttle_seat_price(string $seattype): float
    {
        return (float) DB::table('shuttle_seat_packages')
            ->where('name', $seattype)
            ->value('price') ?? 0;
    }
}