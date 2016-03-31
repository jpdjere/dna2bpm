<?php

class Lib_201_error_legend {

    public function __construct() {
        if (isset($code))
            $this->result = $this->return_legend($code, $row, $value);
    }

    function return_legend($code, $row, $value) {

        switch ($code) {
            case "A.1":
                $result_error = '<strong>Columna A - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Debe tener formato numérico mayor a cero, entero, sin decimales.';
                break;
            case "A.2":
                $result_error = '<strong>Columna A - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Si lo que se está informando es un Aporte (Columna D), debe validarse con los movimientos históricos que están cargados en el Sistema que el número informado no exista y sea correlativo al último informado.';
                break;
            case "A.3":
                $result_error = '<strong>Columna A - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>En un mismo archivo no se puede repetir el mismo número para los casos en que se estén informando Aportes (Columna D).';
                break;
            case "A.4":
                $result_error = '<strong>Columna A - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>En caso de que se esté informando un retiro (Columna E), el número de Aporte debe estar previamente registrado en el Sistema o en el mismo archivo que se está importando, en cuyo caso debe corresponder a un Aporte (Columna D) y tener Fecha de Movimiento (Columna B) anterior a la Fecha de Movimiento (Columna B) del retiro informado.';
                break;
            case "A.5":
                $result_error = '<strong>Columna A - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>En caso de que se esté informando un Retiro de Rendimientos (Columna G), el número de Aporte debe estar previamente registrado en el Sistema o en el mismo archivo que se está importando, en cuyo caso debe corresponder a un Aporte (Columna D) y tener Fecha de Movimiento (Columna B) anterior a la Fecha de Movimiento (Columna B) del retiro de Rendimientos informado.';
                break;
            case "B.1":
                $result_error = '<strong>Columna B - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Debe ser un numéro de cinco dígitos sin decimales. Ej. Para la fecha "01/10/2013" debe ingresarse "41548" (formato general).';
                break;
            case "B.2":
                $result_error = '<strong>Columna B - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Debe verificar que todas las fechas informadas se encuentren dentro del período que se está importando.';
                break;
            case "B.3":
                $result_error = '<strong>Columna B - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Para los casos en que se estén informando Retiros (Columna E), no puede darse que para un mismo número haya informada dos files en la que la Fecha de Movimiento (Columna B) sea la misma.';
                break;
            case "B.4":
                $result_error = '<strong>Columna B - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Para los casos en que se estén informando Retiro de Rendimientos (Columna G), no puede darse que para un mismo número haya informada dos files en la que la Fecha de Movimiento (Columna B) sea la misma.';
                break;
            case "B.5":
                $result_error = '<strong>Columna B - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Para el caso en que se esté informando un Aporte, debe verificar que no haya dos filas con la misma fecha y el mismo número de CUIT en la columna C.';
                break;
            case "C.1":
                $result_error = '<strong>Columna C - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Debe tener 11 dígitos sin guiones. Deberá ser una CUIT válida.';
                break;
            case "C.2":
                $result_error = '<strong>Columna C - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>En caso de que se trate de un Retiro (Columna E) o un Retiro de Rendimientos (Columna G), el campo debe estar vacío y el Sistema tomará el CUIT registrado previamente en el mismo para el número de aporte informado.';
                break;
            case "C.3.1":
                $result_error = '<strong>Columna C - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>En caso de que se trate de un Aporte (Columna D), debe verificar que el CUIT pertenece a un Socio Protector incorporado como Socio B y con Tenencia de Acciones positivas.';
                break;
             case "C.3.2":
                $result_error = '<strong>Columna C - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>La validación C.3.1 no corre para los FAE. Los Socios B que aportes a los FAE siempre van a tener CERO acciones.';
                break;
            case "D.1":
                $result_error = '<strong>Columna D - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>De estar completa debe tomar Formato Numérico mayor a cero y aceptar hasta 2 decimales.';
                break;
            case "E.1":
                $result_error = '<strong>Columna E - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/> Si la columna D está completa, esta debe estar vacía.';
                break;
            case "E.2":
                $result_error = '<strong>Columna E - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/> De estar completa debe tomar Formato Numérico mayor a cero y aceptar hasta 2 decimales.';
                break;
            case "E.3":
                $result_error = '<strong>Columna E - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Debe verificar que el monto retirado (sumado a los demás montos que ya se hubieran retirado anteriormente de dicho aporte), no exceda el monto del aporte.';
                break;
            case "F.1":
                $result_error = '<strong>Columna F - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Si la columna D está completa, esta debe estar vacía.';
                break;
            case "F.2":
                $result_error = '<strong>Columna F - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/> Si la Columna E está vacía, esta debe estar vacía.';
                break;
            case "F.3":
                $result_error = '<strong>Columna F - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/> Si la Columna E está completa, esta debe estar completa.';
                break;
            case "F.4":
                $result_error = '<strong>Columna F - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>De estar completa, debe tomar Formato Numérico mayor o igual a cero y  aceptar hasta 2 decimales.';
                break;
            case "G.1":
                $result_error = '<strong>Columna G - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Si la columna D está completa, esta debe estar vacía.';
                break;
            case "G.2":
                $result_error = '<strong>Columna G - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>De estar completa debe tomar Formato Numérico mayor a cero y aceptar hasta 2 decimales.';
                break;
            case "HP.1":
                $result_error = '<strong>Columna HP - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/> H-P: Ninguna de las columnas comprendidas entre H y P pueden estar vacias';
                break;
            case "HP.2":
                $result_error = '<strong>Columna HP - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/> H-P: Todas  las columnas comprendidas entre H y P deben estar vacias para informacion anterior a 2014';
                break;
            case "Q.1":
                $result_error = '<strong>Columna Q - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Q: Debe tener formato numérico de cinco dígitos sin decimales.';
                break;
            case "Q.2":
                $result_error = '<strong>Columna Q - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Q: Debe estar vacia para informacion anterior a 2014.';
                break;
            case "R.1":
                $result_error = '<strong>Columna R - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>R: OPCIONAL. De ser completada debe tener formato numérico sin decimales y ser mayor a cero.';
                break;
            case "VG.1":
                $result_error = '<strong>Columna V - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/> Alguna de la siguientes columnas debe contener datos: D, E, F o G.';
                break;
        }
        return $result_error;
    }

}
