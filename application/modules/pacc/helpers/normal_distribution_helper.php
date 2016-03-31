<?php

function purebell($min = 0, $max = 1, $std_deviation = 2, $step = 1) {
    $rand1 = (float) mt_rand() / (float) mt_getrandmax();
    $rand2 = (float) mt_rand() / (float) mt_getrandmax();
    $gaussian_number = sqrt(-2 * log($rand1)) * cos(2 * M_PI * $rand2);
    $mean = ($max + $min) / 2;
    $random_number = ($gaussian_number * $std_deviation) + $mean;
    $random_number = round($random_number / $step) * $step;
    if ($random_number < $min || $random_number > $max) {
        $random_number = purebell($min, $max, $std_deviation);
    }
    return $random_number;
}

function translate_currency($value = null) {
    if ($value) {
        $translate = str_replace(',', '.', str_replace('.', '', $value)) * 1;
        return (float) $translate;
    }
}

function api_money_format($value) {
    $rtn = "$" . number_format($value, 0, ",", ".");
    return $rtn;
}

function date_for_print($value) {
    
    var_dump($value);

    $rtn = $value;

    if (!empty($value)) {
        list ($yeard, $monthd, $day_explode) = explode("-", $value);
        $dayd = explode(" ", $day_explode);

        $rtn = $yeard . "/" . $monthd . "/" . $dayd[0];
    }

    return $rtn;
}

/* GET diff between now & parameter */

function get_date_diff($parameter) {
    $segundos = strtotime('now') - strtotime($parameter);
    $diferencia_dias = intval($segundos / 60 / 60 / 24);
    return $diferencia_dias;
}
