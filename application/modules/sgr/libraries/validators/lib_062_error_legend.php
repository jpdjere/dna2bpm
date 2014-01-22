<?php

class Lib_062_error_legend {

    public function __construct() {
        $this->result = $this->return_legend($code, $row, $value);
    }

    function return_legend($code, $row, $value) {

        switch ($code) {
            case "A.1": 
                $result_error = '(Fila Nro.'. $row .') A: Debe tener 11 caracteres numéricos sin guiones.';
                break;
            case "A.2": 
                $result_error = '(Fila Nro.'. $row .') A: Debe corresponder con algún Socio Partícipe de la SGR que tenga saldo de acciones mayor a Cero.';
                break;
            case "B.1": 
                $result_error = '(Fila Nro.'. $row .') B: Todos los Socios que fueron informados como Incorporados en el Anexo 6 – Movimientos de Capital Social, deben figurar en esta columna.';
                break;
             case "B.2": 
                $result_error = '(Fila Nro.'. $row .') B: El año debe ser igual o menor al del período en que se está informando.';
                break;
            case "C.1": 
                $result_error = '(Fila Nro.'. $row .') C: Debe ser formato numérico';
                break;
            case "D.1": 
                $result_error = '(Fila Nro.'. $row .') D: Numero entero mayor a cero.';
                break;
            case "E.1": 
                $result_error = '(Fila Nro.'. $row .') E: Debe contener uno de los siguientes parámetros: BALANCES/CERTIFICACION DE INGRESOS/DDJJ IMPUESTOS';
                break;
        }

        return $result_error;
    }

}
