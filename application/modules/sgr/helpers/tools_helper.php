<?php

/**
 * Convierte en formato de moneda
 *
 * @param Boolean (true) en el caso de Null genera - para definir el dato vacio (false) imprime al dato el formato de moneda.
 * @type php
 * @author Diego
 * @name money_format
 *
 * */
function money_format_custom($parameter) {


    if ($_POST['excel'] == 1) {
        $parameter = ($parameter != NULL) ? @number_format($parameter, 2, ",", ".") : "";
    } else {
        $parameter = ($parameter != NULL) ? "$" . @number_format($parameter, 2, ",", ".") : "   ";
    }
    return $parameter;
}

function compact_serialized($serialized) {
    $mydata = array();
    foreach ($serialized as $v) {
        $mydata[$v['name']] = $v['value'];
    }
    return $mydata;
}

// Profile    
function get_gravatar($email) {
    $code = md5(strtolower(trim($email)));
    return "http://www.gravatar.com/avatar/$code?d=mm";
}

function iso_encode($d) {
    $date = date_create_from_format('d-m-Y', $d);
    return date_format($date, 'Y-m-d');
}

function iso_decode($d) {
    $date = date_create_from_format('Y-m-d', $d);
    return date_format($date, 'd-m-Y');
}

function key_finder($pajar, $key, $value) {
    foreach ($pajar as $item) {
        if ($item[$key] == $value)
            return $item;
    }
}

/*
 * VALIDATION FUNCTIONS
 */

function debug($parameter) {
    return "<pre>" . var_dump($parameter) . "</pre><hr>";
}

function check_empty($parameter) {
    if ($parameter == NULL) {
        return true;
    }
}

function check_for_empty($parameter) {
    if ($parameter != NULL) {
        return true;
    }
}

function check_word($parameter, $allow_words) {
    if (!in_array(strtoupper($parameter), $allow_words)) {
        return true;
    }
}

function check_date_format($parameter) {
    if (ctype_alpha($parameter)) {
        return true;
        exit();
    }

    $num_length = strlen((string) $parameter);
    if ($num_length != 5) {
        return true;
    }
}

function check_date($parameter) {
    list($year, $month) = explode("/", $parameter);
    $mm = $month;
    $dd = "10";
    $yyyy = $year;

    If (@checkdate($mm, $dd, $yyyy)) {
        return $yyyy;
    }
}

function check_period($var, $period) {
    $valida_fecha = date("m-Y", mktime(0, 0, 0, 1, -1 + $var, 1900));
    if ($valida_fecha != $period) {
        return true;
    }
}

function check_period_minor($parameter, $period) {

    if (isset($parameter) || isset($period)) {
        list($getYear, $getMonth) = explode("/", $parameter);
        list($getPeriodMonth, $getPeriodYear) = explode("-", $period);

        $check_date = mktime(0, 0, 0, date($getMonth), date(01), date($getYear));
        $period = mktime(0, 0, 0, date($getPeriodMonth), date(01), date($getPeriodYear));
        if ($check_date > $period) {
            return true;
        }
    }
}

function check_decimal($number, $decimal = 2) {
    /* Chck if number */
    if (ctype_alpha($number)) {
        return true;
        exit();
    }
    $m_factor = pow(10, $decimal);
    if ((int) ($number * $m_factor) == $number * $m_factor)
        return false;
    else
        return true;
}

function check_zip_code($parameter) {
    $num_length = strlen((string) $parameter);
    if ($num_length != 8) {
        return true;
    }
}

function check_area_code($parameter) {
    if ($parameter[0] == 0) {
        $parameter = substr($parameter, 1);
    }

    $num_length = strlen((string) $parameter);
    $range = range(2, 4);
    if (in_array($num_length, $range)) {
        return true;
    }
}

function check_phone_number($parameter) {

    $num_length = strlen((string) $parameter);
    $range = range(6, 10);
    if (in_array($num_length, $range)) {
        return true;
    }
}

function check_email($parameter) {
    if (!filter_var($parameter, FILTER_VALIDATE_EMAIL)) {
        return true;
    }
}

function check_web($parameter) {
    if (!filter_var($parameter, FILTER_VALIDATE_URL)) {
        return true;
    }
}

function check_is_numeric($parameter) {
    if (!is_numeric($parameter)) {
        return true;
    }
}

function check_is_alphabetic($parameter) {
    if (!ctype_alpha($parameter)) {
        return true;
    }
}

/* CHECK CNV SYNTAX */

function check_cnv_syntax($code) {
    preg_match_all('/^([^\d]+)(\d+)/', $code, $match);
    $text = $match[1][0];
    $num = $match[2][0];

    if (strlen($text) == 4 && strlen($num) == 9) {
        return $text;
    }
}

function check_cnv_syntax_alt($code) {
    preg_match_all('/^([^\d]+)(\d+)/', $code, $match);
    $text = $match[1][0];
    $num = $match[2][0];

    if (strlen($text) == 3 && strlen($num) == 1) {
        return $text;
    }
}

/* CHECK MVL CUITS */

function check_mvl_cuit($cuit) {
    /*
      Mercado de Valores de Buenos Aires	MVBA	30-52531837-7
      Mercado de Valores de Rosario	MROS	30-52917787-5
      Mercado de Valores de Córdoba	MCOR	30-54286940-9
      Mercado de Valores de Mendoza	MMZA	33-53787772-9
      Mercado de Valores del Litoral	MLIT	33-65982192-9
      Mercado de Valores de Bahía Blanca	MVBB	30-66415280-7
      Mercado de Valores de Santa Fe	MSFE	30-56627523-2
     */

    $mvl_arr = array("30525318377", "30529177875", "30542869409", "33537877729", "33659821929", "30664152807", "30566275232");
    if (in_array($mvl_arr, $cuit)) {
        return true;
    }
}

/* FIX CLANAE TO CIU */

function cerosClanae($num) {
    $range = range(11111, 990000);
    if (in_array($num, $range)) {
        if (strlen($num) == 5) {
            return "0" . $num;
        } else {
            return $num;
        }
    }
}

/* CIU */

function ciu($sector) {
//AGROPECUARIO
//, INDUSTRIA Y MINERIA
//, COMERCIO
//, SERVICIOS
//, CONSTRUCCION
//, ADMINISTRACION PUBLICA
//, SERVICIO DOMESTICO u ORGANISMOS INTERNACIONALES
    $newSectorCode = substr($sector, 0, 3);
    $sectorCode = substr($sector, 0, 2);
    $sector_value = "";

    $codesArr = array('01', '02', '05');
    if (in_array($sectorCode, $codesArr)) {
        $sector_value = 1;
    }


    $codesArr = array('10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31', '32', '33', '34', '35', '36', '37', '72');
    if (in_array($sectorCode, $codesArr)) {
        $sector_value = 2;
    }

    $codesArr = array('50', '51', '52');
    if (in_array($sectorCode, $codesArr)) {
        $sector_value = 3;
    }

    $codesArr = array('40', '41', '55', '60', '61', '62', '63', '64', '65', '66', '67', '70', '71', '73', '74', '80', '85', '90', '91', '92', '93');
    if (in_array($sectorCode, $codesArr)) {
        $sector_value = 4;
    }

    $codesArr = array('45');
    if (in_array($sectorCode, $codesArr)) {
        $sector_value = 5;
    }

    $codesArr = array('75');
    if (in_array($sectorCode, $codesArr)) {
        $sector_value = 6;
    }

    $codesArr = array('95');
    if (in_array($sectorCode, $codesArr)) {
        $sector_value = 7;
    }

    $codesArr = array('99');
    if (in_array($sectorCode, $codesArr)) {
        $sector_value = 8;
    }

    /*
     * ?ARTICULO 3° Resolución 50/2013 ?
     * Resolución N° 24/2001. Modificación.
     */

    $codesArr = array('921');
    if (in_array($newSectorCode, $codesArr)) {
        $sector_value = 2;
    }

    return $sector_value;
}

//FUNCION VALIDA CUIT
function cuit_checker($cuit) {

    if (ctype_alpha($cuit)) {
        return false;
        exit();
    }

    if (strstr($cuit, '-')) {
        return false;
        exit();
    }

    /* VALIDATOR ALGORITHM */
    $cadena = str_split($cuit);

    $result = $cadena[0] * 5;
    $result += $cadena[1] * 4;
    $result += $cadena[2] * 3;
    $result += $cadena[3] * 2;
    $result += $cadena[4] * 7;
    $result += $cadena[5] * 6;
    $result += $cadena[6] * 5;
    $result += $cadena[7] * 4;
    $result += $cadena[8] * 3;
    $result += $cadena[9] * 2;

    $div = intval($result / 11);
    $resto = $result - ($div * 11);

    if ($resto == 0) {
        if ($resto == $cadena[10]) {
            return true;
        } else {
            return false;
        }
    } elseif ($resto == 1) {
        if ($cadena[10] == 9 AND $cadena[0] == 2 AND $cadena[1] == 3) {
            return true;
        } elseif ($cadena[10] == 4 AND $cadena[0] == 2 AND $cadena[1] == 3) {
            return true;
        }
    } elseif ($cadena[10] == (11 - $resto)) {
        return true;
    } else {
        return false;
    }
}

/*
 * PROCESS FNS
 */

function translate_date($parameter) {
    if ($parameter == "" || $parameter == NULL) {
        exit();
    }
    $parameter = mktime(0, 0, 0, 1, -1 + $parameter, 1900);
    return strftime("%Y/%m/%d", $parameter);
}

/**
 * Convierte en formato de moneda
 *
 * @param Boolean (true) en el caso de Null genera - para definir el dato vacio (false) imprime al dato el formato de moneda.
 * @type php
 * @author Diego
 * @name money_format
 *
 * */
function _money_format($parameter) {

    if ($_POST['excel'] == 1) {
        $parameter = ($parameter != NULL) ? @number_format($parameter, 2, ",", ".") : "";
    } else {
        $parameter = ($parameter != NULL) ? "$" . @number_format($parameter, 2, ",", ".") : "   ";
    }
    return $parameter;
}
