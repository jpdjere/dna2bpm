<?php

class Lib_121_error_legend {

    public function __construct() {
        $this->result = $this->return_legend($code, $row, $value);
    }

    function return_legend($code, $row, $value) {

        switch ($code) {
            case "A.1": 
                $result_error = '(Fila Nro.'. $row .') A: El Número se debe corresponder con alguna de las Garantías informadas mediante el Anexo 12 del mismo período y apara la cual figura que el Sistema de Amortización o la periodicidad de los pagos fue informado como “OTRO” (Columnas R y S del Anexo 12).';
                break;            
             case "B.1": 
                $result_error = '(Fila Nro.'. $row .') B: Por lo menos debe tener dos cuotas. Si tiene sólo una está mal. La numeración debe empezar en 1 y ser correlativa dentro de cada garantía.';
                break;                     
             case "C.1": 
                $result_error = '(Fila Nro.'. $row .') C: Formato numérico de cinco dígitos sin decimales. Debe ser posterior a la fecha de emisión de la garantía informada en la Columna C del Anexo 12.';
                break;
            case "D.1": 
                $result_error = '(Fila Nro.'. $row .') D: Formato numérico. Aceptar hasta dos decimales. La suma de las cuotas de una misma garantía debe ser igual al monto informado para esa misa garantía en la Columna E del Anexo 12.';
                break;
            case "E.1": 
                $result_error = '(Fila Nro.'. $row .') E: Formato numérico. Aceptar hasta dos decimales. La suma de las cuotas de una misma garantía debe ser igual al monto informado para esa misa garantía en la Columna L del Anexo 12.';
                break;  
        }
        return $result_error;
    }

}
