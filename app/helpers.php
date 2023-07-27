<?php

/**
 * @param string $datestr
 * @return array
 */
function rangeWeek($datestr)
{
    $dt = strtotime($datestr);
    return array(
        "start" => date('N', $dt) == 1 ? date('Y-m-d H:i:s', $dt) : date('Y-m-d H:i:s', strtotime('last monday', $dt)),
        "end" => date('N', $dt) == 7 ? date('Y-m-d 23:59:59', $dt) : date('Y-m-d 23:59:59', strtotime('next Sunday', $dt))
    );
}

/**
 * @param string $datestr
 * @return array
 */
function getDays()
{
    $days = [
        'pazartesi' => [
            'title' => 'Pazartesi',
            'index' => 0
        ],
        'sali' => [
            'title' => 'Salı',
            'index' => 1
        ],
        'carsamba' => [
            'title' => 'Çarşamba',
            'index' => 2
        ],
        'persembe' => [
            'title' => 'Perşembe',
            'index' => 3
        ],
        'cuma' => [
            'title' => 'Cuma',
            'index' => 4
        ],
        'cumartesi' => [
            'title' => 'Cumartesi',
            'index' => 5
        ],
    ];

    return $days;
}
