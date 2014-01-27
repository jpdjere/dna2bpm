<?php

class Lib_124_error_legend {

    public function __construct() {
        $this->result = $this->return_legend($code, $row, $value);
    }

    function return_legend($code, $row, $value) {

        switch ($code) {
            case "A.1":
                $result_error = '(Fila Nro.' . $row . ') A: El Número de garantía debe estar informado en el sistema.';
                break;
            case "B.1":
                $result_error = '(Fila Nro.' . $row . ') B: Campo con formato de fecha. Numérico de cinco dígitos sin decimales.';
                break;
            case "B.2":
                $result_error = '(Fila Nro.' . $row . ') B: La fecha debe ser igual o posterior a la fecha de entrada en vigencia de la garantía que está registrada en el sistema.';
                break;
            case "B.3":
                $result_error = '(Fila Nro.' . $row . ') B: La fecha debe estar comprendida dentro del período informado.';
                break;
            case "C.1":
                $result_error = '(Fila Nro.' . $row . ') C: Formato de número. Acepta hasta dos decimales.';
                break;
             case "D.1":
                $result_error = '(Fila Nro.' . $row . ') D: No puede estar vacio.';
                break;
            case "E.1":
                $result_error = '(Fila Nro.' . $row . ') E: No puede estar vacio.';
                break;
            case "F.1":
                $result_error = '(Fila Nro.' . $row . ') F: Debe tener 11 caracteres sin guiones.';
                break;
        }
        return $result_error;
    }

}
