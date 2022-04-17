<?php
function price_format($price)
{
    return number_format(ceil($price), 0, '', ' ') . ' ₽';
}
function remaining_time(string $closetime, string $nowtime): array
{
    $dt_diff = strtotime($closetime) - strtotime($nowtime);
    if (is_date_valid($closetime) == false || $dt_diff < 0) {
        $time = ['hours' => 0, 'minutes' => 0];
        return $time;
    }
    $hours = floor($dt_diff / 3600);
    $minutes = floor($dt_diff % 3600 / 60);
    $time = [
        'hours' => $hours,
        'minutes' => $minutes
    ];
    return $time;
}
