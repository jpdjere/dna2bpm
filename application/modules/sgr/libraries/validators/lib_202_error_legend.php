<?php

class Lib_202_error_legend {

    public function __construct() {
        if (isset($code))
            $this->result = $this->return_legend($code, $row, $value);
    }

    function return_legend($code, $row, $value) {

        switch ($code) {
            case "VG.2":
                $result_error = '<strong>Columna VG - Fila Nro.' - ' - Código Validación ' . $code . '</strong><br/>Desde que una SGR informó el 1º aporte al fondo de Riesgo, este anexo no puede estar en ningún período SIN MOVIMIENTOS, siempre tienen que informar los saldos. SALVO QUE, todos los aportes tengan saldo cero, el Anexo 14 tenga saldo cero y el Anexo 15 tenga saldo cero.';
                break;
            case "A.1":
                $result_error = '<strong>Columna A - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Formato numérico sin decimales.';
                break;
            case "A.2":
                $result_error = '<strong>Columna A - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Debe validar que el número de aporte se encuentre registrado en el Sistema.';
                break;
            case "A.3":
                $result_error = '<strong>Columna A - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Debe validar que, al menos, se encuentren listados todos los números de aportes que, tengan SALDOS DE APORTE mayores a Cero.';
                break;
            case "A.4":
                $result_error = '<strong>Columna A - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Si para un determinado Número de Aporte el SALDO DE APORTE es cero y aún así es informado, debe validar que la Columna C o la Columna D, o ambas, tengan un monto informado mayor a cero y hasta con dos decimales.';
                break;
            case "B.1":
                $result_error = '<strong>Columna B - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Valor con formato numérico positivo,  acepta hasta dos decimales.';
                break;
            case "B.2":
                $result_error = '<strong>Columna B - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Debe ser menor o igual que el saldo del aporte calculado por el Sistema en función de los movimientos históricos informados mediante Anexo 20.1.';
                break;
            case "C.1":
                $result_error = '<strong>Columna C - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>OPCIONAL. Valor con formato numérico positivo,  que acepte hasta dos decimales.';
                break;
            case "D.1":
                $result_error = '<strong>Columna D - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Valor con formato numérico,  acepta hasta dos decimales.';
                break;
        }
        return $result_error;
    }

}
