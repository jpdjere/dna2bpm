<?php

class Lib_122_error_legend {

    public function __construct() {
        $this->result = $this->return_legend($code, $row, $value);
    }

    function return_legend($code, $row, $value) {

        switch ($code) {
            case "A.1": 
                $result_error = '(Fila Nro.'. $row .') A: El Número de garantía debe estar informado en el sistema.';
                break;            
             case "B.1": 
                $result_error = '(Fila Nro.'. $row .') B: Una misma cuota de una garantía no puede figurar dos veces en el archivo importado.';
                break;                     
             case "C.1": 
                $result_error = '(Fila Nro.'. $row .') C: Formato numérico de cinco dígitos sin decimales. Debe ser posterior a la fecha de emisión de la garantía informada en la Columna C del Anexo 12.';
                break;
            case "D.1": 
                $result_error = '(Fila Nro.'. $row .') D: Formato numérico de cinco dígitos sin decimales. La fecha debe encontrarse dentro del período que se está informando.';
                break;
            case "E.1": 
                $result_error = '(Fila Nro.'. $row .') E: Formato numérico. Aceptar hasta dos decimales.';
                break;
            case "F.1": 
                $result_error = '(Fila Nro.'. $row .') F: Formato numérico. Aceptar hasta dos decimales. El monto debe ser inferior al registrado en como Monto de Garantí Otorgada del Anexo 12.';
                break;
        }
        return $result_error;
    }

}
