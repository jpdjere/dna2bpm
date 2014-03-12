<?php

class Lib_062_error_legend {

    public function __construct() {
        $this->result = $this->return_legend($code, $row, $value);
    }

    function return_legend($code, $row, $value) {

        switch ($code) {
            case "A.1": 
                $result_error = '<strong>Columna A - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Debe tener 11 caracteres numéricos sin guiones.';
                break;
            case "A.2": 
                $result_error = '<strong>Columna A - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Debe corresponder con algún Socio Partícipe de la SGR que tenga saldo de acciones mayor a Cero.';
                break;
            case "B.1": 
                $result_error = '<strong>Columna B - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Debe tener el siguiente formato: xxxx/xx, correspondientes al formato AÑO/MES; que los dígitos del mes estén entre 01 y 12.';
                break;
             case "B.2": 
                $result_error = '<strong>Columna B - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>El período ingresado en el archivo debe ser igual o menor al del período en que se está informando.';
                break;
            case "C.1": 
                $result_error = '<strong>Columna C - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Debe ser formato numérico';
                break;
            case "D.1": 
                $result_error = '<strong>Columna D - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Numero entero mayor a cero.';
                break;
            case "E.1": 
                $result_error = '<strong>Columna E - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Debe contener uno de los siguientes parámetros: BALANCES/CERTIFICACION DE INGRESOS/DDJJ IMPUESTOS';
                break;
        }

        return $result_error;
    }

}
