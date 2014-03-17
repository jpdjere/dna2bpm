<?php

class Lib_123_error_legend {

    public function __construct() {
        $this->result = $this->return_legend($code, $row, $value);
    }

    function return_legend($code, $row, $value) {

        switch ($code) {
            case "A.1":
                $result_error = '<strong>Columna A - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>El Número de garantía debe estar informado en el sistema y corresponder con alguno de los siguientes tipos de garantías:GFMFO/GC1/GC2/GT';
                break;
            case "B.1":
                $result_error = '<strong>Columna B - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Los montos informados deben ser menores o iguales al Monto de Garantía Otorgado registrado en el Sistema.';
                break;
            case "B.2":
                $result_error = '<strong>Columna B - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Si algún día el saldo estuvo en Cero, deben informar “0”. Ningún campo puede estar vacío.';
                break;
            case "B.3":
                $result_error = '<strong>Columna B - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>El monto ingresado debe tener formato numérico, mayor a cero, hasta dos decimales.';
                break;
        }
        return $result_error;
    }

}
