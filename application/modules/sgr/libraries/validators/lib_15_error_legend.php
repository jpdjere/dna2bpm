<?php

class Lib_15_error_legend {

    public function __construct() {
        $this->result = $this->return_legend($code, $row, $value);
    }

    function return_legend($code, $row, $value) {

        switch ($code) {
            case "A.1":
                $result_error = '(Fila Nro.' . $row . ') A: Debe estar compuesta por alguno de los parámetros establecidos en la Columna A de Anexo adjunto (OPCIONES DE INVERSIÓN) a tales efectos.';
                break;
            case "B.1":
                $result_error = '(Fila Nro.' . $row . ') B: Se puede completar la cantidad de filas que sean necesarias. Si una fila se completa, todos sus campos deben estar llenos.';
                break;
            case "C.1":
                $result_error = '(Fila Nro.' . $row . ') C: Se puede completar la cantidad de filas que sean necesarias. Si una fila se completa, todos sus campos deben estar llenos.';
                break;
            case "D.1":
                $result_error = '(Fila Nro.' . $row . ') D: En caso de que el CUIT de la Columna E del importador ya está registrado en la Base de Datos del Sistema, este tomará en cuenta el nombre allí registrado. En caso contrario, se mantendrá provisoriamente el nombre informado por la SGR. De no estar registrado algún CUIT, se deberá agregar al Sistema a la lista EMISORES DE OPCIONES DE INVERSIÓN (HOY AÚN NO EXISTE) así queda registrado para el futuro.';
                break;
            case "D.2":
                $result_error = '(Fila Nro.' . $row . ') D: En caso de que la OPCIÓN DE INVERSIÓN indicada en la Columna A sea D, J o K, este campo deberá estar vacío.';
                break;
            case "E.1":
                $result_error = '(Fila Nro.' . $row . ') E: Debe tener 11 caracteres numéricos sin guiones. Se le debe aplicar el Algoritmo Verificador de CUIT de forma de verificar que sea un CUIT existente.';
                break;
            case "E.2":
                $result_error = '(Fila Nro.' . $row . ') E: En caso de que la OPCIÓN DE INVERSIÓN indicada en la Columna A sea D, J o K, este campo deberá estar vacío.';
                break;
            case "F.1":
                $result_error = '(Fila Nro.' . $row . ') F: En caso de que el CUIT de la Columna G del importador ya está registrado en la Base de Datos del Sistema, este tomará en cuenta el nombre allí registrado. En caso contrario, se mantendrá provisoriamente el nombre informado por la SGR.';
                break;
            case "G.1":
                $result_error = '(Fila Nro.' . $row . ') G: Debe tener 11 caracteres numéricos sin guiones. Debe validar que se corresponda con alguno de los CUIT detallados en el Anexo adjunto, donde se listan las ENTIDADES DEPOSITARIAS habilitadas a tales efectos.';
                break;
             case "H.1":
                $result_error = '(Fila Nro.' . $row . ') H: Debe contener uno de los siguientes parámetros:Pesos Argentinos/Dolares Americanos';
                break;
             case "I.1":
                $result_error = '(Fila Nro.' . $row . ') I: Debe tener formato numérico y aceptar hasta dos decimales.';
                break;
            case "I.2":
                $result_error = '(Fila Nro.' . $row . ') I: Debe validar que la suma total de las inversiones sea igual al Saldo de la Columna 7 – Saldo del Aporte Disponible, de la Impresión del Anexo 20.2 más el saldo de la Columna D del importado de dicho Anexo.';
                break;            
        }
        return $result_error;
    }

}
