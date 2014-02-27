<?php

class Lib_13_error_legend {

    public function __construct() {
        $this->result = $this->return_legend($code, $row, $value);
    }

    function return_legend($code, $row, $value) {

        switch ($code) {
            case "A.1":
                $result_error = '<strong>Columna A - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Sólo pude contener alguno de los tipos de Garantía aceptados de acuerdo a lo que se lista en el Modelo de importación. Pueden estar listados todos o sólo algunos.';
                break;
            case "B.1":
                $result_error = '<strong>Columna B - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Formato de número. Acepta hasta dos decimales. Debe ser mayor que cero.';
                break;
            case "B.2":
                $result_error = '<strong>Columna B - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>La sumatoria de las columnas B a E debe ser mayor a Cero.';
                break;
            case "C.1":
                $result_error = '<strong>Columna C - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Formato de número. Acepta hasta dos decimales. Debe ser mayor que cero.';
                break;
            case "D.1":
                $result_error = '<strong>Columna D - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Formato de número. Acepta hasta dos decimales.Debe ser mayor que cero.';
                break;
            case "E.1":
                $result_error = '<strong>Columna E - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Formato de número. Acepta hasta dos decimales.Debe ser mayor que cero.';
                break;
            case "F.1":
                $result_error = '<strong>Columna F - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Formato de número. Acepta hasta dos decimales. Debe ser mayor que cero.';
                break;
        }
        return $result_error;
    }

}
