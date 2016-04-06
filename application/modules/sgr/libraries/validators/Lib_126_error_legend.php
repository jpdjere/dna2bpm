<?php

class Lib_126_error_legend {

    public function __construct() {
        if (isset($code))
            $this->result = $this->return_legend($code, $row, $value);
    }

    function return_legend($code, $row, $value) {

        switch ($code) {
            case "VG.1":
                $result_error = '<strong>Columna A - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Sólo puede ser informada una fila.';
                break;
            case "A.1":
                $result_error = '<strong>Columna A - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>La celda no puede estar vacía. Debe contener formato numérico con hasta dos decimales. Debe ser mayor o igual a cero.';
                break;
            case "B.1":
                $result_error = '<strong>Columna A - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>La celda no puede estar vacía. Debe contener formato numérico con hasta dos decimales. Debe ser mayor o igual a cero.';
                break;
             case "C.1":
                $result_error = '<strong>Columna A - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>La celda no puede estar vacía. Debe contener formato numérico con hasta dos decimales. Debe ser mayor o igual a cero.';
                break;
             case "D.1":
                $result_error = '<strong>Columna A - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>La celda no puede estar vacía. Debe contener formato numérico con hasta dos decimales. Debe ser mayor o igual a cero.';
                break;
        }
        return $result_error;
    }

}
