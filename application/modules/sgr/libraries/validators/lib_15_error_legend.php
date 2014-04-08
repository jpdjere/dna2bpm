<?php

class Lib_15_error_legend {

    public function __construct() {
        $this->result = $this->return_legend($code, $row, $value);
    }

    function return_legend($code, $row, $value) {

        switch ($code) {
            case "A.1":
                $result_error = '<strong>Columna A - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Debe estar compuesta por alguno de los parámetros autorizados en el Anexo adjunto "OPCIONES DE INVERSIÓN".';
                break;
            case "B.1":
                $result_error = '<strong>Columna B - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Se puede completar la cantidad de filas que sean necesarias. Si una fila se completa, todos sus campos deben estar llenos.';
                break;
            case "C.1":
                $result_error = '<strong>Columna C - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Se puede completar la cantidad de filas que sean necesarias. Si una fila se completa, todos sus campos deben estar llenos.';
                break;
            case "D.1":
                $result_error = '<strong>Columna D - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>En caso de que el CUIT de la Columna E del importador ya está registrado en la Base de Datos del Sistema, este tomará en cuenta el nombre allí registrado. En caso contrario, se mantendrá provisoriamente el nombre informado por la SGR. De no estar registrado algún CUIT, se deberá agregar al Sistema a la lista EMISORES DE OPCIONES DE INVERSIÓN (HOY AÚN NO EXISTE) así queda registrado para el futuro.';
                break;
            case "D.2":
                $result_error = '<strong>Columna D - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>En caso de que la OPCIÓN DE INVERSIÓN indicada en la Columna A sea D, J o K, este campo deberá estar vacío.';
                break;
            case "E.1":
                $result_error = '<strong>Columna E - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Debe tener 11 caracteres numéricos sin guiones. Debe ser una CUIT Válida.';
                break;
            case "E.2":
                $result_error = '<strong>Columna E - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/> En caso de que la OPCIÓN DE INVERSIÓN indicada en la Columna A sea D, J o K, este campo deberá estar vacío.';
                break;
            case "E.3":
                $result_error = '<strong>Columna E - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>En caso de que la OPCIÓN DE INVERSIÓN indicada en la Columna A sea D, J o K, este campo deberá estar vacío.';
                break;
            case "F.1":
                $result_error = '<strong>Columna F - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/> En caso de que el CUIT de la Columna G del importador ya está registrado en la Base de Datos del Sistema, este tomará en cuenta el nombre allí registrado. En caso contrario, se mantendrá provisoriamente el nombre informado por la SGR.';
                break;
            case "G.1":
                $result_error = '<strong>Columna G - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Debe tener 11 caracteres numéricos sin guiones. Debe estar incluida en el anexo ENTIDADES DEPOSITARIAS. Si no lo está, deberá informarse la misma a la DSyC para que sea incluida.';
                break;
             case "H.1":
                $result_error = '<strong>Columna H - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Debe contener uno de los siguientes parámetros: "Pesos Argentinos"/"Dolares Americanos".';
                break;
             case "I.1":
                $result_error = '<strong>Columna I - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Debe tener formato numérico y aceptar hasta dos decimales. Debe ser mayor que cero.';
                break;
            case "I.2":
                $result_error = '<strong>Columna I - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>La suma total de las inversiones debe ser igual al Saldo de la Columna 7 – Saldo del Aporte Disponible, de la Impresión del Anexo 20.2 más el saldo de la Columna D del importador de dicho Anexo.';
                break;            
        }
        return $result_error;
    }

}
