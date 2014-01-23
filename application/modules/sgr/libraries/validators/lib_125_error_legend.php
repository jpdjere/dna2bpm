<?php

class Lib_125_error_legend {

    public function __construct() {
        $this->result = $this->return_legend($code, $row, $value);
    }

    function return_legend($code, $row, $value) {

        switch ($code) {
            case "A.1":
                $result_error = '(Fila Nro.' . $row . ') A: Debe tener 11 caracteres sin guiones.';
                break;
             case "A.2":
                $result_error = '(Fila Nro.' . $row . ') A: Debe figura en el Sistema con Garantías Otorgadas en el Sistema (Anexo 12)';
                break;
            case "B.1":
                $result_error = '(Fila Nro.' . $row . ') B: Debe tener 11 caracteres sin guiones.';
                break;
            case "B.2":
                $result_error = '(Fila Nro.' . $row . ') B: Debe estar registrado en el Sistema asociado al CUIT del Socio Partícipe informado al menos en una garantía otorgada.';
                break;            
            case "C.1":
                $result_error = '(Fila Nro.' . $row . ') C: Formato de número. Acepta hasta dos decimales.';
                break;
            case "C.2":
                $result_error = '(Fila Nro.' . $row . ') C: El campo sólo podría ser mayor a Cero sólo en caso de que en el sistema esté registrado que el Socio Partícipe haya recibido alguno de los siguientes tipos de garantías: GFEF0, GFEF1, GFEF2, GFEF3, GFOI0, GFOI1, GFOI2, GFOI3, GFP0, GFP1, GFP2, GFP3, GFCPD, GFFF0, GFFF1, GFFF2, GFFF3, GFON0, GFON1, GFON2, FON3, GFVCP, GFMFO, GFL0, GFL1, GFL2, GFL3, GFPB0, GFPB1 o GFPB2.';
                break;
             case "D.1":
                $result_error = '(Fila Nro.' . $row . ') D: Formato de número. Acepta hasta dos decimales.';
                break;
            case "D.2":
                $result_error = '(Fila Nro.' . $row . ') D: El campo sólo podría ser mayor a Cero sólo en caso de que en el sistema esté registrado que el Socio Partícipe haya recibido alguno de los siguientes tipos de garantías: GC1 o GC2.';
                break;
            case "E.1":
                $result_error = '(Fila Nro.' . $row . ') E: Formato de número. Acepta hasta dos decimales.';
                break;
            case "E.2":
                $result_error = '(Fila Nro.' . $row . ') E: El campo sólo podría ser mayor a Cero sólo en caso de que en el sistema esté registrado que el Socio Partícipe haya recibido alguno de los siguientes tipos de garantías: GT';
                break;
            case "F.1":
                $result_error = '(Fila Nro.' . $row . ') F: Debe tener 11 caracteres sin guiones.';
                break;
        }
        return $result_error;
    }

}
