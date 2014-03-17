<?php

class Lib_061_error_legend {

    public function __construct() {
        $this->result = $this->return_legend($code, $row, $value);
    }

    function return_legend($code, $row, $value) {

        switch ($code) {
            case "VG.1": 
                $result_error = '<strong>Columna A - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Si el Anexo 6 de un período fue informado “SIN MOVIMIENTOS”, para ese mismo período este anexo debe ser indicado como “SIN MOVIMIENTOS” ';
                break;
             case "VG.3": 
                $result_error = '<strong>Columna A - Código Validación '.$code.'</strong><br/>Todos los CUIT que figuran en la Columna A de este archivo, debe estar informado en la Columna C del ANEXO 6 del período correspondiente, y deben figurar como “INCORPORADO” en la Columna A de dicho anexo.';
                break;
            case "VG.4": 
                $result_error = '<strong>Columna A - Código Validación '.$code.'</strong><br/>Debe verificar en el Anexo 6 del período correspondiente que todos los CUIT informados en la Columna C del mismo y que en la Columna A figuran como “INCORPORACION”, deben figurar al menos una vez en esta columna.';
                break;
            case "A.1": 
                $result_error = '<strong>Columna A - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Si alguna de las columnas B a F está completa, este campo no puede estar vacío y  debe tener 11 caracteres sin guiones.';
                break;
            case "A.2": 
                $result_error = '<strong>Columna A - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>El CUIT debe estar informado en la Columna C del ANEXO 6 del período correspondiente, y deber figurar como “INCORPORADO” en la Columna A de dicho anexo.';
                break;
             case "B.1": 
                $result_error = '<strong>Columna B - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>El campo no puede estar vacío y debe contener uno de los siguientes parámetros: SI/NO';
                break;
            case "B.2": 
                $result_error = '<strong>Columna B - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Si el CUIT informado en la Columna A comienza con 30 o 33 (Correspondiente a Personas Jurídicas) la opción debe ser “SI”.';
                break;
            case "B.3": 
                $result_error = '<strong>Columna B - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Si se indica la opción “NO” el CUIT no puede estar más de una vez en la Columna A de este Anexo.';
                break;
             case "C.1": 
                $result_error = '<strong>Columna C - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Si en la Columna B se completó la opción “SI”, el campo no puede estar vacío y  debe tener 11 caracteres sin guiones. El CUIT debe cumplir el “ALGORITMO VERIFICADOR”.';
                break;
            case "C.2": 
                $result_error = '<strong>Columna C - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Si en la Columna E se completó la opción ASCENDENTE, se debe permitir que este capo tome, ADICIONALMENTE, alguno de los siguientes valores:  “11111111111”, código que se utilizará para indicar, de corresponder, la parte de las Acciones de la empresa que cotizan en el Mercado de Valores Nacional.Alguno de los códigos que figuran en el archivo adjunto “ANEXO 06.1 - CÓDIGOS EMPRESAS EXTRANJERAS”, los cuales se utilizaran para indicar que la tenencia accionaria está en poder de una persona física o jurídica extranjera.';
                break;
            case "D.1": 
                $result_error = '<strong>Columna D - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Si en la Columna B se completó la opción “SI”, el campo no puede estar vacío.';
                break;
            case "E.1": 
                $result_error = '<strong>Columna E - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Si en la Columna B se completó la opción “SI”, el campo no puede estar vacío y debe contener uno de los siguientes parámetros:ASCENDENTE/DESCENDENTE';
                break;
            case "E.2": 
                $result_error = '<strong>Columna E - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Si el número de CUIT informado en la Columna A empieza con 20, 23 o 27 (los tres correspondientes a personas físicas), y se indicó que el Socio SI tiene Relaciones de Vinculación (Columna B), la opción elegida sólo puede ser DESCENDENTE.';
                break;
            case "F.1": 
                $result_error = '<strong>Columna F - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Si en la Columna B se completó la opción “SI”, el campo no puede estar vacío.';
                break;
            case "F.2": 
                $result_error = '<strong>Columna F - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>De completarse, debe tener formato numérico y sólo debe tomar valores entre 0 y 1 y aceptar hasta 2 decimales.';
                break;
             case "F.3": 
                $result_error = '<strong>Columna F - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Para un mismo CUIT informado en la Columna A, los campos que en la Columna E indiquen ASCENDENTE, deben sumar 1, de forma de cerciorarse que estén informando el total de los Accionistas de la empresa.';
                break;
        }
        return $result_error;
    }

}
