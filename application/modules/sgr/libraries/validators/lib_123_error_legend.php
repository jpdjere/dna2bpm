<?php

class Lib_123_error_legend {

    public function __construct() {
        $this->result = $this->return_legend($code, $row, $value);
    }

    function return_legend($code, $row, $value) {

        switch ($code) {
            case "A.1":
                $result_error = '<strong>Columna A - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>El Número de garantía debe estar informado en el sistema y corresponder con alguno de los siguientes tipos de garantías:"GFMFO"/"GC1"/"GC2"/"GT"';
                break;
            case "B.1.A":
                $result_error = '<strong>Columna B - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Los montos informados deben ser menores o iguales al Monto de Garantía Otorgado registrado en el Sistema.';
                break;
            case "B.1.B":
                $result_error = '<strong>Columna B - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>GARANTÍAS EN DÓLARES: los montos informados deben ser inferiores o iguales al monto en pesos informado para esa misma garantía en la columna E del anexo 12, dividido el Tipo de Cambio de la fecha de otorgamiento, multiplicado por el Tipo de Cambio vigente al último día del período que se está informando.';
                break;
            
            case "B.2":
                $result_error = '<strong>Columna B - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Ningún campo puede estar vacío. Si algún día el saldo estuvo en Cero, se debe informar “0”. ';
                break;
            case "B.3":
                $result_error = '<strong>Columna B - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>El monto ingresado debe tener formato numérico, mayor a cero, hasta dos decimales.';
                break;
        }
        return $result_error;
    }

}
