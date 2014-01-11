<?php

class Lib_06_error_legend {

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
        }

        return $result_error;
    }

}
