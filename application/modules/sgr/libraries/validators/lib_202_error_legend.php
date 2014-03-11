<?php

class Lib_202_error_legend {

    public function __construct() {
        $this->result = $this->return_legend($code, $row, $value);
    }

    function return_legend($code, $row, $value) {

        switch ($code) {
            case "A.1":
                $result_error = '<strong>Columna A - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Formato numérico sin decimales.';
                break;
            case "A.2":
                $result_error = '<strong>Columna A - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Debe validar que el número de aporte se encuentre registrado en el Sistema.';
                break;
            case "A.3":
                $result_error = '<strong>Columna A - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Debe validar que, al menos, se encuentren listados todos los números de aportes que, tengan SALDOS DE APORTE mayores a Cero.';
                break;
            case "A.4":
                $result_error = '<strong>Columna A - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Si para un determinado Número de Aporte el SALDO DE APORTE, es cero, debe validar que la Columna B sea Cero y que la Columna D tenga un monto informado.';
                break;
            case "B.1":
                $result_error = '<strong>Columna B - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Valor con formato numérico positivo,  que acepte hasta dos decimales.';
                break;
            case "B.2":
                $result_error = '<strong>Columna B - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Debe ser menor o igual que el saldo del aporte calculado por el Sistema en función de los movimientos históricos informados mediante Anexo 20.1.';
                break;
            case "C.1":
                $result_error = '<strong>Columna C - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>OPCIONAL. Valor con formato numérico positivo,  que acepte hasta dos decimales.';
                break;
            case "D.1":
                $result_error = '<strong>Columna D - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Valor con formato numérico,  que acepte hasta dos decimales.';
                break;
        }
        return $result_error;
    }

}
