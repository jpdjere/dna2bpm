<?php

class Lib_061_error_legend {

    public function __construct() {
        $this->result = $this->return_legend($code, $row, $value);
    }

    function return_legend($code, $row, $value) {

        switch ($code) {
            case "A.1": 
                $result_error = '(Fila Nro.'. $row .') A: El campo no puede estar vacío y  debe tener 11 caracteres sin guiones.';
                break;
            case "A.2": 
                $result_error = '(Fila Nro.'. $row .') A: El CUIT debe estar en el ANEXO 6 – MOVIMIENTOS DE CAPITAL SOCIAL, informado en el período correspondiente como incorporado.';
                break;
            case "A.3": 
                $result_error = '(Fila Nro.'. $row .') A: Todos los Socios que fueron informados como Incorporados en el Anexo 6 – Movimientos de Capital Social, deben figurar en esta columna.';
                break;
             case "B.1": 
                $result_error = '(Fila Nro.'. $row .') B: El campo no puede estar vacío y debe contener uno de los siguientes parámetros:
                SI
                NO';
                break;
            case "B.2": 
                $result_error = '(Fila Nro.'. $row .') B: Si el CUIT informado en la Columna A comienza con 30 o 33 (Correspondiente a Personas Jurídicas) la opción debe ser “SI”.';
                break;
            case "B.3": 
                $result_error = '(Fila Nro.'. $row .') B: Si se indica la opción “NO” el CUIT no puede estar más de una vez en la Columna A de este Anexo,  y las Columnas C, D, E, y F deben estar vacías.';
                break;
        }

        return $result_error;
    }

}
