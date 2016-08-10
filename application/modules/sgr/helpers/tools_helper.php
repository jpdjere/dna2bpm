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
function money_format_custom($parameter, $entero = null) {

    if ($parameter == NULL) {
        $parameter = 0;
    }

    if ($entero) {
        $parameter = "$" . @number_format($parameter, 0, ",", ".");
    } else {
        $parameter = "$" . @number_format($parameter, 2, ",", ".");
    }
    return $parameter;
}

function percent_format_custom($parameter) {

    if ($parameter == NULL) {
        $parameter = 0;
    }

    if ($_POST['excel'] == 1) {
        $parameter = @number_format($parameter, 2, ",", ".");
    } else {
        $parameter = @number_format($parameter, 2, ",", ".") . "%";
    }
    return $parameter;
}

function money_format_xls($parameter) {
    $parameter = (float) $parameter;
    $parameter = str_replace(".", ",", $parameter);

    return $parameter;
}

function money_format_integer($parameter) {
    $parameter = "$" . @number_format($parameter, 0, ",", ".");
    return $parameter;
}

function percent_format_integer($parameter) {
    $parameter = round($parameter);
    return $parameter . "%";
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
    if (empty($parameter))
        return true;
}

function check_for_empty($parameter) {
   if (!empty($parameter))
        return true;
}

function check_word($parameter, $allow_words) {

    $var = md5(strtoupper($parameter));
    $new_arr = array();
    foreach ($allow_words as $each) {
        $new_arr[] = md5($each);
    }

    if (!in_array($var, $new_arr)) {
        return $var;
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




function parameterised_province($parameter){
    
    $pcia_arr = array("BUENOS AIRES CONURBANO",
        "BUENOS AIRES INTERIOR",
        "CABA",
        "CATAMARCA",
        "CHACO",
        "CHUBUT",
        "CORDOBA",
        "CORRIENTES",
        "ENTRE RIOS",
        "FORMOSA",
        "JUJUY",
        "LA PAMPA",
        "LA RIOJA",
        "MENDOZA",
        "MISIONES",
        "NEUQUEN",
        "RIO NEGRO",
        "SALTA",
        "SAN JUAN",
        "SAN LUIS",
        "SANTA CRUZ",
        "SANTA FE",
        "SANTIAGO DEL ESTERO",
        "TIERRA DEL FUEGO",
        "TUCUMAN");
        
        if(in_array($parameter, $pcia_arr)){
            return $parameter;
        }
        
    
}

function check_date_for_resolution($date) {
    
    $rtn = NULL;
    
    $t = strtotime($date);
    
    
    
    switch(true) {
        case ($t >= 1435806000 && $t <= 1458097200):  
            /* Desde el 02/07/2015 los límites Pyme son los siguientes (Resolución 357/2015)
             * 2015-07-02 - 2016-03-16 
             */
            $rtn = "357/2015";
        break;
        
        case ($t > 1458183600):   //
            /* Desde el 17/03/2016 los límites Pyme son los siguientes (Resolución 11/2016)
             * 17/03/2016 - 0/0
             */
            $rtn = '11/2016';
        break;
    }
    return $rtn;
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

/* PERIOD */

function check_period($var, $period) {
    $valida_fecha = date("m-Y", mktime(0, 0, 0, 1, -1 + $var, 1900));
    if ($valida_fecha != $period) {
        return true;
    }
}

function check_period_and_later($var, $period) {

    $valida_fecha = translate_date($var);
    list($getPeriodMonth, $getPeriodYear ) = explode("-", $period);

    $datetime1 = new DateTime($valida_fecha);
    $datetime2 = new DateTime($getPeriodYear . '-' . $getPeriodMonth . '-01');
    $interval = $datetime1->diff($datetime2);

    if ($interval->invert == 0 && $interval->days != 0)
        return true;
}

function check_period_minor($parameter, $period) {

    list($getYear, $getMonth) = explode("/", $parameter);
    list($getPeriodMonth, $getPeriodYear) = explode("-", $period);

    $check_date = mktime(0, 0, 0, date($getMonth), date(01), date($getYear));
    $period = mktime(0, 0, 0, date($getPeriodMonth), date(01), date($getPeriodYear));
    if ($check_date > $period) {
        return true;
    }
}

function translate_period_date($period) {
    list($period_month, $period_year) = explode("-", $period);

    $period_day = '01';
    $realtime = date("$period_year-$period_month-$period_day H:i:s");
    $mongotime = New Mongodate(strtotime($realtime));
    return $mongotime;
}

function translate_dna2_period_date($period) {
    list($period_month, $period_year) = explode("_", $period);

    $period_day = '01';
    $realtime = date("$period_year-$period_month-$period_day H:i:s");
    $mongotime = New Mongodate(strtotime($realtime));
    return $mongotime;
}

function translate_date_xls($date) {
    $new_date = unixtojd(strtotime($date)) - gregoriantojd(1, 1, 1900);
    return $new_date;
}

function first_month_date($period, $invert = null) {

    if ($invert)
        list($getPeriodYear, $getPeriodMonth) = explode("-", $period);
    else
        list($getPeriodMonth, $getPeriodYear) = explode("-", $period);

    $month_date = "01";
    $endDate = new MongoDate(strtotime($getPeriodYear . "-" . $getPeriodMonth . "-" . $month_date));

    return $endDate;
}

function last_month_date($period, $invert = null) {

    if ($period) {

        if ($invert)
            list($getPeriodYear, $getPeriodMonth) = explode("-", $period);
        else
            list($getPeriodMonth, $getPeriodYear) = explode("-", $period);

        $month_date = date("t", mktime(1, 1, 1, $getPeriodMonth, 1, $getPeriodYear));
        $endDate = new MongoDate(strtotime($getPeriodYear . "-" . $getPeriodMonth . "-" . $month_date));

        return $endDate;
    }
}

function period_before($period) {

    list($getPeriodMonth, $getPeriodYear) = explode("-", $period);
    $fecha = date($getPeriodYear . '-' . $getPeriodMonth . '-28');
    $endDate = strtotime('-1 month', strtotime($fecha));

    return date("m-Y", $endDate);
}

function period_print_format($period) {
// 2014-06
    list($month, $year) = explode("-", $period);
    return $year . "-" . $month;
}

function check_decimal_minor_equal($number, $decimal = 2, $positive = null) {

    $number = str_replace(",", ".", $number);
    $status = false;

    $value = isfloat($number);
    if ($value) {
        $places_count = strlen(substr(strrchr($number, "."), 1));
        if ($places_count > $decimal) {
            $status = true;
        }

        if ($positive) {
            $number = (int) $number;
            if ($number <= 0) {
                $status = true;
            }
        }
    } else {
        $status = true;
    }

    return $status;
}

function check_decimal($number, $decimal = 2, $positive = null) {
    $number = str_replace(",", ".", $number);
    $status = false;

    $value = isfloat($number);
    if ($value) {
        $places_count = strlen(substr(strrchr($number, "."), 1));
        if ($places_count > $decimal) {
            $status = true;
        }

        if ($positive) {
            $number = (int) $number;
            if ($number < 0) {
                $status = true;
            }
        }
    } else {
        $status = true;
    }

    return $status;
}

/* Fix #15151 */

function validate_two_decimals($number, $decimal = 2, $positive = null) {
    $number = str_replace(",", ".", $number);
    $status = false;

    $value = isfloat($number);
    if ($value) {
        $places_count = strlen(substr(strrchr($number, "."), 1));
        if ($places_count > $decimal) {
            $status = true;
        }

        if ($positive) {
            $number = (int) $number;
            if ($number < 0) {
                $status = true;
            }
        }
    } else {
        $status = true;
    }

    return $status;
}

function validate_two_decimals_no_cero($number, $decimal = 2, $positive = null) {

    $get = validate_two_decimals($number, $decimal = 2, $positive = null);
    $status = $get;

    if ($number == 0)
        $status = true;

    return $status;
}

/* VALIDATE POSTIVE/NEGATIVE */

function validate_two_decimals_ex($number) {
    /* Change to positive */
    $number = ($number < 0) ? abs($number) : $number;
    $patron = '^[0-9]+\.[0-9]{2}$';

    if (preg_match('/' . $patron . '/', $number))
        return false;
    else
        return true;
}

function isfloat($f) {
    $value = ($f == (string) (float) $f);
    return $value;
}

function check_zip_code($parameter) {

    $num_length = strlen((string) $parameter);
    if ($num_length == 8 || $num_length == 4)
        return false;
    else
        return true;
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

function check_clanae_ciu($parameter) {

    $num_length = strlen((string) $parameter);
    $range = range(5, 6);
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
    if (is_numeric($parameter)) {
        return true;
    } else {
        $parameter = "http://" . $parameter;
        if (!filter_var($parameter, FILTER_VALIDATE_URL)) {
            return true;
        }
    }
}

function check_is_numeric($number) {
    $value = isfloat($number);
    if (!$value) {
        return true;
    }
}

function check_is_numeric_no_decimal($number, $mayor = null) {   
    $int_options = array("options" =>
        array(
            "min_range" => 0
            //, "max_range" => 256
    ));

    $int_options = ($mayor) ? $int_options : null;

    return (filter_var($number, FILTER_VALIDATE_INT, $int_options));
}

//function check_is_numeric_no_decimal_plus_bis($number, $mayor = null) {
//
//    $int_options = array("options" =>
//        array(
//            "min_range" => 0
//        //, "max_range" => 256
//    ));
//
//    $int_options = ($mayor) ? $int_options : null;
//
//    $is_mumber = (filter_var($number, FILTER_VALIDATE_INT, $int_options));
//
//
//
//    if (!$is_mumber) {
//
//        $mystring = strtoupper($number);
//        $findme = 'BIS';
//        $pos = strpos($mystring, $findme);
//
//        if ($pos)
//            return (int) $number;
//    } else {
//        return $is_mumber;
//    }
//}

function check_is_numeric_range($number, $minor, $mayor) {

    $int_options = array("options" =>
        array(
            "min_range" => $minor
            , "max_range" => $mayor
    ));

    $int_number = (int) $number;
    $check_num = ($int_number == 0) ? 1 : $int_number;

    return (filter_var($check_num, FILTER_VALIDATE_INT, $int_options));
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

function check_cnv_syntax_i4($code) {
    preg_match_all('/^\$([A-Z]{3})(\d{9})/', $code, $match);
    $text = $match[1][0];
    $num = $match[2][0];
    return $text;
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
//AGROPECUARIO - 1
//, INDUSTRIA Y MINERIA - 2
//, COMERCIO - 3
//, SERVICIOS - 4
//, CONSTRUCCION - 5
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
    if ((int) strlen($cuit) != 11) {
        return false;
    }

    if (ctype_alpha($cuit)) {
        return false;
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

/* DATES */

function translate_date($parameter) {
    if ($parameter == "" || $parameter == NULL) {
        exit();
    }
    $parameter = mktime(0, 0, 0, 1, -1 + $parameter, 1900);
    return strftime("%Y-%m-%d", $parameter);
}

function translate_for_mongo($parameter) {
    $result = strftime("%Y-%m-%d %H:%M:%S", mktime(0, 0, 0, 1, -1 + $parameter, 1900));
    return $result;
}

function translate_mysql_date($date) {
    $realtime = date("$date H:i:s");
    $mongotime = New Mongodate(strtotime($realtime));
    return $mongotime;
}

function mongodate_to_print($date) {
    $check_year = (int) date('Y', $date->sec);
    if ($check_year > 1970)
        return date('Y-m-d', $date->sec);
}

/**
 * Buscador en array de 2 dimensiones
 *
 * @param Boolean (true) 
 * @type php
 * @author Diego
 * @name array_search2d
 *
 * */
function array_search2d($needle, $haystack) {
    for ($i = 0, $l = count($haystack); $i < $l; ++$i) {
        if (@in_array($needle, $haystack[$i]))
            return $i;
    }
    return false;
}

/*
 * bool consecutive numbers( int $numero1, int $numero2, int $numero3)
 * Devuelve true si los 3 números son consecutivos, false en caso contrario.
 */

function consecutive_numbers($num1, $num2, $num3) {
    return ($num2 - $num1 == 1 && $num3 - $num2 == 1) ? true : false;
}

function three_fields($fields_arr) {
    foreach ($fields_arr as $key => $value) {
        if (is_null($value) || $value == "") {
            unset($fields_arr[$key]);
        }
    }
    var_dump(count($fields_arr));
}

function Bisiesto($period) {
    list($month, $year) = explode("-", $period);
    return date('L', mktime(1, 1, 1, 1, 1, $year));
}

function return_error_array($code, $row, $value) {
    $result = array();
    $result["error_code"] = $code;
    $result["error_row"] = $row;
    $result["error_input_value"] = $value;

    return $result;
}

function count_shares($data) {
//agrupar por cuit 
    $group = array();
    foreach ($data as $i) {
        $catUser = explode(".", $i[0]);
        $user = $catUser[1];
        $group[$user][] = array(
            "gridGroupName" => $user,
            "shares" => $i[1]);
    }
//sumar columnas - totales 
    $final_shares = 0;
    foreach ($group as $k => $i) {
        $sum_shares = 0;
        foreach ($group[$k] as $r) {
//acumulados
            $sum_shares += $r['shares'];

//totales Finales
            $final_shares += $r['shares'];
        }


        $group[$k]['acumulados'] = array("shares" => $sum_shares);
    }

    $group['total_shares'] = array("shares" => $final_shares);
    return $group;
}

/* FIND REPEATED */

function repeatedElements($array, $returnWithNonRepeatedItems = false) {
    $repeated = array();

    foreach ((array) $array as $value) {
        $inArray = false;

        foreach ($repeated as $i => $rItem) {
            if ($rItem['value'] === $value) {
                $inArray = true;
                ++$repeated[$i]['count'];
            }
        }

        if (false === $inArray) {
            $i = count($repeated);
            $repeated[$i] = array();
            $repeated[$i]['value'] = $value;
            $repeated[$i]['count'] = 1;
        }
    }

    if (!$returnWithNonRepeatedItems) {
        foreach ($repeated as $i => $rItem) {
            if ($rItem['count'] === 1) {
                unset($repeated[$i]);
            }
        }
    }

    sort($repeated);

    return $repeated;
}

/* CONSECUTIVE */

function consecutive($array) {
    $numAnt = array();
    foreach ($array as $pos => $num) {
        $return_arr = array();
//        echo "$pos $num /";
        if ($pos > 0) {
// se compara desde el segundo elemento de la matris
// ahora para saber si es un numero consecutivo le sumamos uno al numero anterior si es igual al numero
// actual guardamos una varible indicando que el numero es consecutivo
            $resto = $pos - 1;
            if ((@$numAnt[$resto] + 1) == $num) {
                
            } else {
                $return_arr[] = $num;
            }
        }
        $numAnt[$pos] = $num;
    }
    return $return_arr;
}

/* === Consecutives bis === */

function check_consecutive_values($array) {

    for ($i = 0; $i < count($array); $i++) {

// we need at leaset 2 items to compare return true
        if (count($array) < 2)
            return true;
// check if there is one more item to compare..        
        if (isset($array[$i + 1])) {
            if (($array[$i] + 1) != $array[$i + 1])
                return false;
        }
    }
    return true;
}

function array_mesh() {
// Combine multiple associative arrays and sum the values for any common keys
// The function can accept any number of arrays as arguments
// The values must be numeric or the summed value will be 0
// Get the number of arguments being passed
    $numargs = func_num_args();

// Save the arguments to an array
    $arg_list = func_get_args();

// Create an array to hold the combined data
    $out = array();

// Loop through each of the arguments
    for ($i = 0; $i < $numargs; $i++) {
        $in = $arg_list[$i]; // This will be equal to each array passed as an argument
// Loop through each of the arrays passed as arguments
        foreach ($in as $key => $value) {
// If the same key exists in the $out array
            if (array_key_exists($key, $out)) {
// Sum the values of the common key
                $sum = $in[$key] + $out[$key];
// Add the key => value pair to array $out
                $out[$key] = $sum;
            } else {
// Add to $out any key => value pairs in the $in array that did not have a match in $out
                $out[$key] = $in[$key];
            }
        }
    }

    return $out;
}

function calc_anexo_14($caidas, $get_historic_data, $number) {
    $sum_CAIDA = array_sum(array($get_historic_data['CAIDA'], $caidas['CAIDA']));
    $sum_RECUPERO = array_sum(array($get_historic_data['RECUPERO'], $caidas['RECUPERO']));
    $sum_INCOBRABLES_PERIODO = array_sum(array($get_historic_data['INCOBRABLES_PERIODO'], $caidas['INCOBRABLES_PERIODO']));
    $sum_RECUPEROS = array_sum(array($sum_RECUPERO, $sum_INCOBRABLES_PERIODO));


    $get_RECUPEROS = bccomp($sum_RECUPEROS, $sum_CAIDA);
    if ($get_RECUPEROS == 1) {
        $error_text = "( Nro de Orden " . $number . " Caidas: " . $sum_CAIDA . " ) " . $sum_RECUPEROS . "/" . $sum_INCOBRABLES_PERIODO;
        return $error_text;
    }
}

function calc_anexo_14_gastos($gastos, $get_historic_data, $number) {
    $sum_GASTOS_EFECTUADOS_PERIODO = array_sum(array($get_historic_data['GASTOS_EFECTUADOS_PERIODO'], $gastos['GASTOS_EFECTUADOS_PERIODO']));
    $sum_RECUPERO_GASTOS_PERIODO = array_sum(array($get_historic_data['RECUPERO_GASTOS_PERIODO'], $gastos['RECUPERO_GASTOS_PERIODO']));
    $sum_GASTOS_INCOBRABLES_PERIODO = array_sum(array($get_historic_data['GASTOS_INCOBRABLES_PERIODO'], $gastos['GASTOS_INCOBRABLES_PERIODO']));
    $sum_GASTOS = array_sum(array($sum_RECUPERO_GASTOS_PERIODO, $sum_GASTOS_INCOBRABLES_PERIODO));

    $get_GASTOS = bccomp($sum_GASTOS, $sum_GASTOS_EFECTUADOS_PERIODO);
    if ($get_GASTOS == 1) {
        $error_text = "( Nro de Orden " . $number . " Gastos por Gestión de Recuperos : " . $sum_GASTOS_EFECTUADOS_PERIODO . " ) " . $sum_RECUPERO_GASTOS_PERIODO . "/" . $sum_GASTOS_INCOBRABLES_PERIODO;
        return $error_text;
    }
}

function calc_anexo_201($aporte, $get_historic_data, $number) {
    $sum_APORTE = array_sum(array($get_historic_data['APORTE'], $caidas['APORTE']));
    $sum_RETIRO = array_sum(array($get_historic_data['RETIRO'], $caidas['RETIRO']));

    $get_RETIRO = bccomp($sum_RETIRO, $sum_APORTE);
    if ($get_RETIRO == 1) {
        $error_text = "( Nro de Aporte " . $number . " Aporte: " . $sum_CAIDA . " ) " . $sum_RETIRO;
        return $error_text;
    }
}

function translate_anexos_dna2($anexo) {
    switch ($anexo) {
        case '06':
            return 'sgr_socios';
            break;

        case 'sgr_socios':
            return '06';
            break;

        case '061':
            return 'sgr_anexo17_2';
            break;

        case 'sgr_anexo17_2':
            return '061';
            break;

        case '062':
            return 'sgr_socios_4';
            break;

        case 'sgr_socios_4':
            return '062';
            break;

        case '09':
            return 'sgr_pdf';
            break;

        case 'sgr_pdf':
            return '09';
            break;

        case '12':
            return 'sgr_garantias';
            break;

        case 'sgr_garantias':
            return '12';
            break;

        case '122':
            return 'sgr_anexo12bis';
            break;

        case 'sgr_anexo12bis':
            return '122';
            break;

        case '13':
            return 'sgr_anexo13';
            break;

        case 'sgr_anexo13':
            return '13';
            break;

        case '14':
            return 'sgr_fdr_contingente';
            break;

        case 'sgr_fdr_contingente':
            return '14';
            break;

        case 'sgr_anexo14bis':
            return '141';
            break;

         case '141':
            return 'sgr_anexo14bis';
            break;         

        case '15':
            return 'sgr_anexo15';
            break;

        case 'sgr_anexo15':
            return '15';
            break;

        case '16':
            return 'sgr_anexo16';
            break;

        case 'sgr_anexo16':
            return '16';
            break;

        case '201':
            return 'sgr_fdr_integrado';
            break;

        case 'sgr_fdr_integrado':
            return '201';
            break;
    }
}

function translate_anexos_dna2_urls($anexo) {
    switch ($anexo) {
        case '06':
            return 'SGR_socios';
            break;

        case '061':
            return 'SGR_anexo_17_2';
            break;

        case '062':
            return 'SGR_socios_4';
            break;


        case '09':
            return 'SGR_PDF';
            break;

        case '12':
            return 'SGR_anexo_12';
            break;

        case '122':
            return 'SGR_anexo_12_bis';
            break;

        case '14':
            return 'SGR_FDR_contingente';
            break;

        case '141':
            return 'sgr_anexo14bis';
            break;
    

        case '201':
            return 'SGR_FDR_integrado';
            break;
    }
}

function translate_month_spanish($code) {
    $replace = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    $search = array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");

    return str_replace($search, $replace, $code);
}

/* Fix 10% for dollar quotation */

function fix_ten_cents($value, $value2) {

    $percent = (int) ($value * 0.01);
    $percent_negative = (int) ($value * -0.01);

    $range = range($percent_negative, $percent);

    $diff = $value - $value2;
    $diff = (int) $diff;

    if ($diff < 0) {
        if (!in_array($diff, $range))
            return true;
    }
}

function sum_values($value1, $value2) {
    $value1 = (float) $value1;
    $value2 = (float) $value2;

    $result = array_sum(array($value1, $value2));

    return $result;
}

function clean_spaces($string) {
    $string = preg_replace("([ ]+)", "", $string);
    return $string;
}

/* For debugging purposes ONLY */

function switch_users_debug($iduser) {

    switch ($iduser) {
        /* GARANTIZAR */
        case -4357375:
        case -1464904928:
        case 1540219296:
        case -1112224739:
            $iduser = 253029915;
            break;

        case -1716989292:
            $iduser = -1113969851;
            break;

        case -2040780000:
        case -1933410388:
        case 875267711:
            $iduser = 1841376084;
            break;
    }


    return $iduser;
}

function deleteFromArray(&$array, $deleteIt, $useOldKeys = FALSE) {
    $key = array_search($deleteIt, $array, TRUE);
    if ($key === FALSE)
        return FALSE;
    unset($array[$key]);
    if (!$useOldKeys)
        $array = array_values($array);
    return TRUE;
}

function array_item_or_false($item, $array, $default = FALSE) {
    if (!isset($array[$item]) OR $array[$item] == "") {
        return $default;
    }

    return $array[$item];
}

function dot_by_coma($parameter) {
    $value = str_replace(".", ",", $parameter);

    if (!preg_match('/,/', $value))
        $value = $value . ",00";

    return $value;
}

function coma_by_dot($parameter) {

    $value = (int) $parameter . ",00";

    return $value;
}

/* CRYPT */

function encryptIt($q) {
    $cryptKey = 'qJB0rGtIn5UB1xG03efyCp';
    $qEncoded = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($cryptKey), $q, MCRYPT_MODE_CBC, md5(md5($cryptKey))));
    return( $qEncoded );
}

function decryptIt($q) {
    $cryptKey = 'qJB0rGtIn5UB1xG03efyCp';
    $qDecoded = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($cryptKey), base64_decode($q), MCRYPT_MODE_CBC, md5(md5($cryptKey))), "\0");
    return( $qDecoded );
}

function get_reference_year($period) {

    list($pmonth, $pyear) = explode("-", $period);
    $pyear = (int) $pyear;

    if ($pyear < 2014)
        return $pyear;
}

function exclude_validation_12($result, $period) {

    $rtn = $result;

    /* control year */
    $pyear = get_reference_year($period);

    $redo_result = array();
    $unset_array = array('B.2', 'T.1');

    foreach ($result as $to_set) {
        if (!in_array($to_set['error_code'], $unset_array))
            $redo_result[] = $to_set;
    }

    if (isset($pyear))
        $rtn = $redo_result;

    return $rtn;
}

function link_report_fn() {


    $link = '<a href="' . base_url() . 'sgr/reports/show_last_report" target="_self" class="btn btn-block btn-warning hide_offline">
                <i class="fa fa-print"></i> Imprimir el Ultimo Reporte Generado
            </a>';

    return $link;
}

function link_report_and_back_fn() {
    $link = link_report_fn() . '<a href="' . base_url() . 'sgr/reports" target="_self" class="btn btn-block btn-primary hide_offline">
                <i class="fa fa-print"></i> Volver
            </a>';

    echo $link;
}

function css_reports_fn() {
    $css_link = '<link rel="stylesheet" href="/dna2bpm/sgr/assets/jscript/bootstrap/css/bootstrap.min.css" /> 
        <link rel="stylesheet" href="/dna2bpm/sgr/assets/css/font-awesome-4.0.3/css/font-awesome.min.css" />
        <link rel="stylesheet" href="/dna2bpm/sgr/assets/jscript/jquery-ui-1.10.2.custom/css/smoothness/jquery-ui-1.10.2.custom.min.css" />	
        <link rel="stylesheet" href="/dna2bpm/sgr/assets/css/extra-icons.css" />       
        <link rel="stylesheet" href="/dna2bpm/sgr/assets/css/sgr.css" />';
    echo $css_link;
}

function css_central_fn() {
    $css_link = '<link rel="stylesheet" href="/dna2bpm/sgr/assets/jscript/bootstrap/css/bootstrap.min.css" /> 
        <link rel="stylesheet" href="/dna2bpm/sgr/assets/css/font-awesome-4.0.3/css/font-awesome.min.css" />
        <link rel="stylesheet" href="/dna2bpm/sgr/assets/jscript/jquery-ui-1.10.2.custom/css/smoothness/jquery-ui-1.10.2.custom.min.css" />	
        <link rel="stylesheet" href="/dna2bpm/sgr/assets/css/extra-icons.css" />';
    echo $css_link;
}

function header_arr($anexo) {

        switch($anexo){
        
        case '06':
        $headerArr = array('SGR', 'CUIT SGR', 'ID', 'Periodo', 'Tipo de Operacion', 'Tipo de Socio (A/B)', 'C.U.I.T.', 'Apellido y nombre o Razon Social', 'Provincia', 'Partido / Municipio / Comuna', 'Localidad', 'Codigo Postal', 'Calle', 'Nro.', 'Piso', 'Dto. / Oficina', 'Telefono 1', 'Telefono 2', 'E-mail', 'Pagina Web', 'Codigo', 'Sector', 'Mes/Ano 1', 'Monto 1', 'Tipo Origen 1', 'Mes/Ano 2', 'Monto 2', 'Tipo Origen 2', 'Mes/Ano 3', 'Monto 3', 'Tipo Origen 3', 'Facturacion Promedio', 'Tipo de Empresa', 'Condicion de Inscripcion AFIP', 'Cantidad de Empleados al Cierre del ultimo Ejercicio', 'Tipo de Acta', 'Fecha', 'Acta Nro.', 'Fecha de transaccion', 'Modalidad de compra de acciones', 'Capital Suscripto', 'Capital Integrado', 'CUIT Cedente', 'Apellido y nombre o Razon Social Cedemte', 'Caracter del Cedente', 'Archivo SIPRIN SGR');
        break;
        case '141':
            $headerArr = array('SGR', 'CUIT SGR', 'ID', 'PERIODO', 'C.U.I.T', 'SOCIO PARTICIPE', 'CANT GTIAS VIGENTES', 'MONTO DE GARANTIAS VIGENTES', 'HIPOTECARIAS', 'PRENDARIAS', 'FIANZA', 'OTRAS', 'TOTAL', 'REAFIANZA', 'MORA EN DIAS', 'CLASIFICACION DEUDOR', 'MONTO ADEUDADO', 'CANTIDAD GARANTIAS AFRONTADAS', 'CANTIDAD GARANTIAS', 'Archivo SIPRIN SGR');        
        break;
        case '15':
            $headerArr = array(
            'ID',
            'PERIODO',
            'SGR',
            'CUIT SGR',
            'INCISO ART 25',
            'DESCRIPCION',    
            'IDENTIFICACION',
            'EMISOR',
            'CUIT EMISOR',
            'ENTIDAD DESPOSITARIA',
            'CUIT DEPOSITARIO',
            'MONEDA',
            'MONTO',
            'Archivo SIPRIN SGR');        
        break;

        }
        
        return $headerArr;
    }

function report_account_records_fn($incremental) {
    echo "<pre>" . $incremental . " Registros.......</pre>";
}

function duplicate_in_array($var) {
    $res = array_diff($var, array_diff(array_unique($var), array_diff_assoc($var, array_unique($var))));
    return $res;
}

function warranty_number_checker($number) {
    $re = "/[(.)(,)(*)]/";

    if (preg_match($re, $number, $matches))
        return true;
}

function stripAccents($string) {

    //$string = trim($string);

    $string = str_replace(
            array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'), array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'), $string
    );

    $string = str_replace(
            array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'), array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'), $string
    );

    $string = str_replace(
            array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'), array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'), $string
    );

    $string = str_replace(
            array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'), array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'), $string
    );

    $string = str_replace(
            array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'), array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'), $string
    );

    $string = str_replace(
            array('ñ', 'Ñ', 'ç', 'Ç'), array('n', 'N', 'c', 'C',), $string
    );

    //Esta parte se encarga de eliminar cualquier caracter extraño
    $string = str_replace(
            array("\\", "¨", "º", "-", "~",
        "#", "@", "|", "!", "\"",
        "·", "$", "%", "&", "/",
        "(", ")", "?", "'", "¡",
        "¿", "[", "^", "`", "]",
        "+", "}", "{", "¨", "´",
        ">", "< ", ";", ",", ":",
        "."), '', $string
    );


    return $string;
}
