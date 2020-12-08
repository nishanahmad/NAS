<?php

function getWeekMonSun($weekOffset) {
    $dt = new DateTime();
    $dt->setIsoDate($dt->format('o'), $dt->format('W') + $weekOffset);
    return array(
        'Mon' => $dt->format('Y-m-d'),
        'Sun' => $dt->modify('+5 day')->format('Y-m-d'),
    );
}

var_dump(getWeekMonSun(-1));