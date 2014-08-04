<?php

class Lib_125_error_legend {

    public function __construct() {
        $this->result = $this->return_legend($code, $row, $value);
    }

    function return_legend($code, $row, $value) {

        switch ($code) {
              case "VG.2":
                $result_error = '<strong>Columna A - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Solo puede haber una combinación de CUIT PARTICIPE + CUIT ACREEDOR por archivo';
                break;
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
                $result_error = '<strong>Columna C - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>El campo sólo podría ser distinto de Cero sólo en caso de que en el sistema esté registrado que el Socio Partícipe haya recibido alguno de los siguientes tipos de garantías: GFEF0, GFEF1, GFEF2, GFEF3, GFOI0, GFOI1, GFOI2, GFOI3, GFP0, GFP1, GFP2, GFP3, GFCPD, GFFF0, GFFF1, GFFF2, GFFF3, GFON0, GFON1, GFON2, GFON3, GFVCP, GFMFO, GFL0, GFL1, GFL2, GFL3, GFPB0, GFPB1, GFPB2, I.1.1, I.1.2, I.1.3, I.1.4, I.2.1, I.2.2, I.2.3, I.2.4, I.3.1, I.3.2, I.3.3, I.3.4, I.4.1, I.4.2, I.4.3, I.5.1, I.5.2, I.5.3, I.5.4, I.6.1, I.6.2, I.7.1, I.7.2, I.8, FINANCIERA.';
                break;
            case "C.2":
                $result_error = '<strong>Columna C - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/> Formato de número. Acepta hasta dos decimales. Mayor a Cero.';
                break;
             case "D.1":
                $result_error = '<strong>Columna D - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>El campo sólo podrá ser distinto de Cero en el caso que en el sistema esté registrado que el Socio Partícipe haya recibido alguno de los siguientes tipos de garantías: "GC1" , "GC2", "II.1.1", "II.1.2", "II.1.3a", "II.1.3b", "II.1.4", "II.2.1", "II.2.2", "II.2.3a", "II.2.3b", "II.2.4" o "COMERCIAL". Si el Partícipe no posee garantías COMERCIALES otorgadas, entonces en este campo debe ingresarse "0".';
                break;
            case "D.2":
                $result_error = '<strong>Columna D - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Formato de número. Acepta hasta dos decimales. Mayor a Cero.';
                break;
            case "E.1":
                $result_error = '<strong>Columna E - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>El campo sólo podrá ser distinto de Cero en caso de que en el sistema esté registrado que el Socio Partícipe haya recibido alguno de los siguientes tipos de garantías: "GT", "III.1.1", "III.1.2", "III.1.3", "III.2.1", "III.2.2", "III.2.3", "III.2.4", "III.3" o "TECNICA". Si el partícipe no posee garantías TÉCNICAS otorgadas, entonces en este campo debe ingresarse "0".';
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
