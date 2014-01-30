<?php

class Lib_16_error_legend {

    public function __construct() {
        $this->result = $this->return_legend($code, $row, $value);
    }

    function return_legend($code, $row, $value) {

        switch ($code) {
            case "A.1":
                $result_error = '<strong>Columna A - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Debe contener alguno de los siguientes parámetros:ENERO/
FEBRERO/
MARZO/
ABRIL/
MAYO/
JUNIO/
JULIO/
AGOSTO/
SEPTIEMBRE/
OCTUBRE/
NOVIEMBRE/
DICIEMBRE';
                break;
            case "BJ.1":
                $result_error = '<strong>Columna '.$code.' - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Debe contener formato numérico sin decimales.';
                break;
        }
        return $result_error;
    }

}
