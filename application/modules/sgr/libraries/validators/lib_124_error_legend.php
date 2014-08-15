<?php

class Lib_124_error_legend {

    public function __construct() {
        if (isset($code))
            $this->result = $this->return_legend($code, $row, $value);
    }

    function return_legend($code, $row, $value) {

        switch ($code) {
            case "A.1":
                $result_error = '<strong>Columna A - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>El Número de garantía debe estar informado en el sistema.';
                break;
            case "B.1":
                $result_error = '<strong>Columna B - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Debe ser un numéro de cinco dígitos sin decimales. Ej. Para la fecha "01/10/2013" debe ingresarse "41548" (formato general).';
                break;
            case "B.2":
                $result_error = '<strong>Columna B - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>La fecha debe ser igual o posterior a la fecha de entrada en vigencia de la garantía que está registrada en el sistema.';
                break;
            case "B.3":
                $result_error = '<strong>Columna B - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>La fecha debe estar comprendida dentro del período que se está informando.';
                break;
            case "C.1":
                $result_error = '<strong>Columna C - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Formato de número. Acepta hasta dos decimales.  Debe ser mayor a cero.';
                break;
            case "D.1":
                $result_error = '<strong>Columna D - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Formato numérico mayor a Cero y menor o igual a Uno, acepta hasta dos decimales.';
                break;
            case "E.1":
                $result_error = '<strong>Columna E - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>En caso de que el CUIT informado en la Columna "E" ya está registrado en la Base de Datos del Sistema, este tomará en cuenta el nombre allí registrado. En caso contrario, se mantendrá provisoriamente el nombre informado por la SGR.';
                break;
            case "F.1":
                $result_error = '<strong>Columna F - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Debe tener 11 caracteres sin guiones. Debe ser un CUIT Válido.';
                break;
        }
        return $result_error;
    }

}
