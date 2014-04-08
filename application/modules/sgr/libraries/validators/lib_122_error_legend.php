<?php

class Lib_122_error_legend {

    public function __construct() {
        $this->result = $this->return_legend($code, $row, $value);
    }

    function return_legend($code, $row, $value) {

        switch ($code) {
            case "A.1": 
                $result_error = '<strong>Columna A - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>El Número de garantía debe estar informado en el sistema.';
                break;            
             case "B.1": 
                $result_error = '<strong>Columna B - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Debe tener formato numérico, entero, mayor que cero.';
                break; 
            case "B.2": 
                $result_error = '<strong>Columna B - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/> Una misma cuota de una garantía no puede figurar dos veces en el archivo importado.';
                break;
             case "C.1": 
                $result_error = '<strong>Columna C - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/> Debe ser un numéro de cinco dígitos sin decimales. Ej. Para la fecha "01/10/2013" debe ingresarse "41548" (formato general).';
                break;
             case "C.2": 
                $result_error = '<strong>Columna C - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Fecha debe ser posterior a la fecha de emisión de la garantía que fue indicada en la columna “A”.';
                break;
            case "D.1": 
                $result_error = '<strong>Columna D - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Formato numérico de cinco dígitos sin decimales. La fecha debe encontrarse dentro del período que se está informando. - Ej. Para la fecha "01/10/2013" debe ingresarse "41548".';
                break;
            case "E.1": 
                $result_error = '<strong>Columna E - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Formato numérico, mayor que cero. Acepta hasta dos decimales.';
                break;
            case "F.1": 
                $result_error = '<strong>Columna F - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Formato numérico. Acepta hasta dos decimales. ';
                break;
            case "F.2.A": 
                $result_error = '<strong>Columna F - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>El monto debe ser inferior al registrado en el sistema como “Monto de la Garantía” para la indicada en la columna “A”.';
                break;
             case "F.2.B": 
                $result_error = '<strong>Columna F - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>El monto debe ser inferior al registrado en el sistema como “Monto de la Garantía” para la indicada en la columna “A”.';
                break;
        }
        return $result_error;
    }

}
