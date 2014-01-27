<?php

class Lib_13_error_legend {

    public function __construct() {
        $this->result = $this->return_legend($code, $row, $value);
    }

    function return_legend($code, $row, $value) {

        switch ($code) {
            case "A.1":
                $result_error = '(Fila Nro.' . $row . ') A: Sólo pude contener alguno de los tipos de Garantía aceptados de acuerdo a lo que se lista en el Modelo de importación. Pueden estar listados todos o sólo algunos.';
                break;
            case "B.1":
                $result_error = '(Fila Nro.' . $row . ') B: Formato de número. Acepta hasta dos decimales.';
                break;
            case "C.1":
                $result_error = '(Fila Nro.' . $row . ') C: Formato de número. Acepta hasta dos decimales.';
                break;
            case "D.1":
                $result_error = '(Fila Nro.' . $row . ') D: Formato de número. Acepta hasta dos decimales.';
                break;
            case "E.1":
                $result_error = '(Fila Nro.' . $row . ') E: Formato de número. Acepta hasta dos decimales.';
                break;
            case "F.1":
                $result_error = '(Fila Nro.' . $row . ') F: Formato de número. Acepta hasta dos decimales.';
                break;
        }
        return $result_error;
    }

}
