<?php

class Lib_141_error_legend {

    public function __construct() {
        $this->result = $this->return_legend($code, $row, $value);
    }

    function return_legend($code, $row, $value) {

        switch ($code) {
            case "A.1":
                $result_error = '<strong>Columna A - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Debe tener 11 caracteres sin guiones.';
                break;
            case "A.2":                
                $result_error = '<strong>Columna A - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Debe figura en el Sistema con Garantías Otorgadas (Anexo 12).';
                break;
            case "A.3":                
                $result_error = '<strong>Columna A - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Debe validar respecto al ANEXO 12.5, que todos los CUIT que en él estén informados también lo estén en este archivo. No puede haber menos, pero sí puede haber más.';
                break;
            case "A.4":                
                $result_error = '<strong>Columna A - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Cada CUIT sólo puede figurar una vez en el archivo.';
                break;
            case "A.5":                
                $result_error = '<strong>Columna A - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Debe validar que todos los CUIT que tengan saldos de deuda positivos (Saldo Calculado por el Sistema sobre la información histórica de los movimientos del FDR Contingente informados mediante ANEXO 14) estén listados en el archivo. Puede haber más, pero no menos.';
                break;
            case "B.1":
                $result_error = '<strong>Columna B - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>OPCIONAL. Si se detecta que el CUIT está informando en el ANEXO 12.5, debe tener formato número y aceptar números enteros mayores a Cero. De lo contrario, debe estar vacío.';
                break;            
            case "C.1":
                $result_error = '<strong>Columna C - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>OPCIONAL. De completarse, debe tomar valor numérico mayor a cero y aceptar hasta dos decimales.';
                break;            
            case "D.1":
                $result_error = '<strong>Columna D - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>OPCIONAL. De completarse, debe tomar valor numérico mayor a cero y aceptar hasta dos decimales.';
                break;            
            case "E.1":
                $result_error = '<strong>Columna E - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>OPCIONAL. De completarse, debe tomar valor numérico mayor a cero y aceptar hasta dos decimales.';
                break;            
            case "F.1":
                $result_error = '<strong>Columna F - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>OPCIONAL. De completarse, debe tomar valor numérico mayor a cero y aceptar hasta dos decimales.';
                break;            
            case "G.1":
                $result_error = '<strong>Columna G - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Debe completarse sólo en caso de que el CUIT informado en la Columna A del importador se encuentre previamente informado en el Sistema mediante ANEXO 12.4. De lo contrario, debe estar vacío.De completarse, debe tomar valor numérico mayor a cero y aceptar hasta dos decimales.';
                break;            
            case "H.1":
                $result_error = '<strong>Columna H - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>OPCIONAL. De estar completo debe tener formato numérico, positivo y entero, sin decimales. Debe validar que si en el proceso de importación detecta que el CUIT informado en la Columna A tiene saldos de deuda positivos (Saldo Calculado por el Sistema sobre la información histórica de los movimientos del FDR Contingente informados mediante ANEXO 14), esta columna deberá estar completa. Esto está relacionado con el proceso indicado en las Validaciones de Impresión de las PARTICULARIDADES DE IMPRESIÓN de este Anexo.';
                break;
            case "I.1":
                $result_error = '<strong>Columna H - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>OPCIONAL. Debe validar que si en el proceso de importación detecta que el CUIT informado en la Columna A tiene saldos de deuda positivos (Saldo Calculado por el Sistema sobre la información histórica de los movimientos del FDR Contingente informados mediante ANEXO 14), esta columna deberá estar completa.';
                break;
            case "I.2":
                $result_error = '<strong>Columna H - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>De estar completo, debe tomar alguno de los siguientes parámetros:1,2,3,4.';
                break;
        }
        return $result_error;
    }

}
