<?php

class Lib_150_error_legend {

    public function __construct() {
        if (isset($code))
            $this->result = $this->return_legend($code, $row, $value);
    }

    function return_legend($code, $row, $value) {

        switch ($code) {
            case "A.1":
                $result_error = '<strong>Columna A - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Debe estar compuesta por alguno de los parámetros establecidos en la Columna A del Anexo adjunto (OPCIONES DE INVERSIÓN) a tales efectos.';
                break;
            case "B.1":
                $result_error = '<strong>Columna A - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Para cada Opción de Inversión informada en la Columna A del importador, sólo puede aceptar la identificación asociada a cada una de ellas en la Columna C del archivo “ANEXO 15 - PARAMETRIZACIÓN DESCRIPCIONES OPCIONES DE INVERSIÓN”.';
                break;    
            case "B.2":
                $result_error = '<strong>Columna A - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Si la IDENTIFICACION informada en la Columna B del IMPORTADOR es “FUTURO DOLAR”, debe existir en el mismo archivo la IDENTIFICACION “REGULARIZACION FUTURO DOLAR”.';
                break;
            case "C.1":
                $result_error = '<strong>Columna A - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>En caso de que la OPCIÓN DE INVERSIÓN indicada en la Columna A del importador sea A, B, C, D, E, I, J, K ó L este campo deberá estar vacío.';
                break;
            case "C.2":
                $result_error = '<strong>Columna A - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Si la opción INCISO_ART_25 (columna A) es G, solo puede aceptar las CUIT’s del archivo ANEXO 15 - CUIT PAISES INCISO G.';
                break;
            case "D.1":
                $result_error = '<strong>Columna A - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Debe tener 11 caracteres numéricos sin guiones. Debe validar que se corresponda con alguno de los CUIT detallados en el ANEXO 15 - CUIT ENTIDADES DEPOSITARIAS, donde se listan las ENTIDADES DEPOSITARIAS habilitadas a tales efectos.';
                break;
            case "D.2":
                $result_error = '<strong>Columna A - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Si y solo si la opción INCISO_ART_25 (columna A) es H, debe permitir que el CUIT Entidad depositaria sea 30528994012 - MERCADO A TERMINO DE ROSARIO SA, además de los establecidos en el listado OPCIONES DE INVERSIÓN.';
                break;
            case "E.1":
                $result_error = '<strong>Columna A - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Debe contener uno de los siguientes parámetros:Pesos Argentinos, Dolares Americanos';
                break;
            case "E.2":
                $result_error = '<strong>Columna A - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Si la opción INCISO_ART_25 (columna A) del archivo ANEXO 15 – IMPORTADOR  es G, solo puede aceptar Dolares Americanos';
                break;
           case "F.1":
                $result_error = '<strong>Columna A - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Debe ser mayor a cero, tener formato numérico y aceptar hasta dos decimales.';
                break;    
           case "F.2":
                $result_error = '<strong>Columna A - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Si en la columna A ingresaron la opción D o K, debe permitir valores entre -1300000 y 99999999999, con formato numérico y aceptar hasta dos decimales.';
                break;  
           case "F.3":
                $result_error = '<strong>Columna A - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Si la IDENTIFICACIÓN (columna B del IMPORTADOR) es “REGULARIZACION FUTURO DOLAR”, el monto debe ser el mismo de la IDENTIFICACION “FUTURO DOLAR”, pero en negativo.';
                break;    
           case "F.4":
                $result_error = '<strong>Columna A - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/> Debe validar que la suma total de las inversiones sea igual al Saldo de la Columna 7 – Saldo del Aporte Disponible, de la Impresión del Anexo 20.2 más el saldo de la Columna D del importado de dicho Anexo.';
                break;      
        }
        return $result_error;
    }

}