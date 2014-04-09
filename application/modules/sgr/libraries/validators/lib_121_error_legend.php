<?php

class Lib_121_error_legend {

    public function __construct() {
        $this->result = $this->return_legend($code, $row, $value);
    }

    function return_legend($code, $row, $value) {

        switch ($code) {
            case "A.1": 
                $result_error = '<strong>Columna A - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>El Número se debe corresponder con alguna de las Garantías informadas mediante el Anexo 12 del mismo período, y para la cual el Sistema de Amortización o la periodicidad de los pagos informado fue “OTRO” (Columnas "R" y "S" del Anexo 12).';
                break;            
             case "B.1": 
                $result_error = '<strong>Columna B - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>La garantía debe poseer al menos dos cuotas. La numeración debe empezar en 1 y ser correlativa dentro de cada garantía.';
                break;                     
             case "C.1": 
                $result_error = '<strong>Columna C - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Debe ser un numéro de cinco dígitos sin decimales. Ej. Para la fecha "01/10/2013" debe ingresarse "41548" (formato general). Debe ser posterior a la fecha de emisión de la garantía informada en la Columna C del Anexo 12.';
                break;
            case "D.1": 
                $result_error = '<strong>Columna D - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Formato numérico. Acepta hasta dos decimales.';
                break;
            case "D.2.A": 
                $result_error = '<strong>Columna D - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>La suma de las cuotas de una misma garantía debe ser igual al monto informado para esa misma garantía en la Columna "E" del Anexo 12.';
                break;
            case "D.2.B": 
                $result_error = '<strong>Columna D - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>GARANTÍAS EN DÓLARES: La suma de las cuotas de una misma garantía debe ser igual al monto en pesos informado para esa misma garantía en la columna E del anexo 12, dividido el Tipo de Cambio de la fecha de otorgamiento, multiplicado por el Tipo de Cambio vigente al último día del período que se está informando.';
                break;
            case "E.1": 
                $result_error = '<strong>Columna E - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Formato numérico. Acepta hasta dos decimales.';
                break;  
            case "E.2": 
                $result_error = '<strong>Columna E - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>La suma de las cuotas de una misma garantía debe ser igual al monto informado para esa misa garantía en la Columna "L" del Anexo 12.';
                break; 
        }
        return $result_error;
    }

}
