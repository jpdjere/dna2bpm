<?php

class Lib_125_error_legend {

    public function __construct() {
        $this->result = $this->return_legend($code, $row, $value);
    }

    function return_legend($code, $row, $value) {

        switch ($code) {
            case "A.1":
                $result_error = '<strong>Columna A - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Debe tener 11 caracteres sin guiones. Debe ser una CUIT válida.';
                break;
             case "A.2":
                $result_error = '<strong>Columna A - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Debe figurar en el Sistema con Garantías Otorgadas.';
                break;
            case "B.1":
                $result_error = '<strong>Columna B - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Debe tener 11 caracteres sin guiones. Debe ser una CUIT Válida.';
                break;
            case "B.2":
                $result_error = '<strong>Columna B - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Debe estar registrado en el Sistema asociado al CUIT del Socio Partícipe, informado al menos en una garantía otorgada.';
                break;            
            case "C.1":
                $result_error = '<strong>Columna C - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>El campo sólo podrá ser distinto de Cero en caso de que en el sistema esté registrado que el Socio Partícipe haya recibido alguno de los siguientes tipos de garantías: "GFEF0", "GFEF1", "GFEF2", "GFEF3", "GFOI0", "GFOI1", "GFOI2", "GFOI3", "GFP0", "GFP1", "GFP2", "GFP3", "GFCPD", "GFFF0", "GFFF1", "GFFF2", "GFFF3", "GFON0", "GFON1", "GFON2", "GFON3", "GFVCP", "GFMFO", "GFL0", "GFL1", "GFL2", "GFL3", "GFPB0", "GFPB1" o "GFPB2". Si el partícipe no posee garantías FINANCIERAS otorgadas, entonces en este campo debe ingresarse "0".';
                break;
            case "C.2":
                $result_error = '<strong>Columna C - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/> Formato de número. Acepta hasta dos decimales. Mayor a Cero.';
                break;
             case "D.1":
                $result_error = '<strong>Columna D - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>El campo sólo podrá ser distinto de Cero en el caso que en el sistema esté registrado que el Socio Partícipe haya recibido alguno de los siguientes tipos de garantías: "GC1" o "GC2". Si el Partícipe no posee garantías COMERCIALES otorgadas, entonces en este campo debe ingresarse "0".';
                break;
            case "D.2":
                $result_error = '<strong>Columna D - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Formato de número. Acepta hasta dos decimales. Mayor a Cero.';
                break;
            case "E.1":
                $result_error = '<strong>Columna E - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>El campo sólo podrá ser distinto de Cero en caso de que en el sistema esté registrado que el Socio Partícipe haya recibido alguno de los siguientes tipos de garantías: "GT". Si el partícipe no posee garantías TÉCNICAS otorgadas, entonces en este campo debe ingresarse "0".';
                break;
            case "E.2":
                $result_error = '<strong>Columna E - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>EFormato de número. Acepta hasta dos decimales. Mayor a Cero.';
                break;
            case "F.1":
                $result_error = '<strong>Columna F - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Debe tener 11 caracteres sin guiones.';
                break;
        }
        return $result_error;
    }

}
