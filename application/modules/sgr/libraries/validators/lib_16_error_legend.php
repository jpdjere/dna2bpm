<?php

class Lib_16_error_legend {

    public function __construct() {
        if (isset($code))
            $this->result = $this->return_legend($code, $row, $value);
    }

    function return_legend($code, $row, $value) {

        switch ($code) {
            case "BJ.1":
                $result_error = '<strong>Columna ' . $code . ' - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Debe contener formato numérico sin decimales. Debe ser mayor o igual a cero.';
                break;
        }
        return $result_error;
    }

}
