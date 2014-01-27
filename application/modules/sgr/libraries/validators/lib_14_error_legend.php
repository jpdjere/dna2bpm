<?php

class Lib_14_error_legend {

    public function __construct() {
        $this->result = $this->return_legend($code, $row, $value);
    }

    function return_legend($code, $row, $value) {

        switch ($code) {
            case "A.1":
                $result_error = '(Fila Nro.' . $row . ') A: Sólo pude contener alguno de los tipos de Garantía aceptados de acuerdo a lo que se lista en el Modelo de importación. Pueden estar listados todos o sólo algunos.';
                break;
            case "B.1":
                $result_error = '(Fila Nro.' . $row . ') B: Si se está informando la CAÍDA de una Garantía (Columna C del importador), debe validar que el número de garantía se encuentre registrado en el Sistema como que fue otorgada (Anexo 12).';
                break;
            case "B.2":
                $result_error = '(Fila Nro.' . $row . ') B: Si se está informando un RECUPERO (Columna D del importador), debe validar que el número de garantía registre previamente en el sistema (o en el mismo archivo que se está importando) una caída.';
                break;
            case "B.3":
                $result_error = '(Fila Nro.' . $row . ') B: Si se está informando un INCOBRABLE (Columna E del importador), debe validar que el número de garantía registre previamente en el sistema (o en el mismo archivo que se está importando) una caída.';
                break;
            case "B.4":
                $result_error = '(Fila Nro.' . $row . ') B: Si se está informando un GASTOS POR GESTIÓN DE RECUPERO (Columna F), debe validar que el número de garantía registre previamente en el sistema (o en el mismo archivo que se está importando) una caída.';
                break;
            case "B.5":
                $result_error = '(Fila Nro.' . $row . ') B: Si se está informando un RECUPERO DE GASTOS POR GESTIÓN DE RECUPERO (Columna G), debe validar que el número de garantía registre previamente en el sistema (o en el mismo archivo que se está importando) un GASTO POR GESTIÓN DE RECUPERO.';
                break;
            case "B.6":
                $result_error = '(Fila Nro.' . $row . ') B: Si se está informando un INCOBRABLE DE GASTOS POR GESTIÓN DE RECUPERO (Columna G), debe validar que el número de garantía registre previamente en el sistema (o en el mismo archivo que se está importando) un GASTO POR GESTIÓN DE RECUPERO.';
                break;
            case "C.1":
                $result_error = '(Fila Nro.' . $row . ') C: Debe ser un valor numérico y aceptar hasta 2 decimales.';
                break;
            case "C.2":
                $result_error = '(Fila Nro.' . $row . ') C: En caso de que la garantía haya sido otorgada en PESOS, debe validar que el importe sea menor o igual al Monto de la Garantía Otorgada informada mediante Anexo 12 registrado en el Sistema.';
                break;
            case "C.3":
                $result_error = '(Fila Nro.' . $row . ') C: En  caso de que la garantía haya sido otorgada en DÓLARES debe validar que el importe aquí informado sea menor o igual al Monto de la Garantía Otorgada informado mediante Anexo 12 registrado en el Sistema, dividido por el TIPO DE CAMBIO DEL día anterior al que fue otorgada la garantía y multiplicado por el TIPO DE CAMBIO del día anterior al que se está informando que se cayó la garantía.';
                break;
            case "D.1":
                $result_error = '(Fila Nro.' . $row . ') D: Debe ser un valor numérico y aceptar hasta 2 decimales.';
                break;
            case "D.2":
                $result_error = '(Fila Nro.' . $row . ') D: Debe validar que el número de garantía registre previamente en el sistema (o en el mismo archivo que se está importando) una caída.';
                break;
            case "D.3":
                $result_error = '(Fila Nro.' . $row . ') D: Debe validar que la suma de todos los RECUPEROS e INCOBRABLES registrados en el Sistema (incluidos los informados  en el archivo que se está importando) para una misma garantía no supere la suma de todas las caídas de esa misma garantía registradas en el Sistema (incluidos los informados  en el archivo que se está importando).';
                break;
            case "E.1":
                $result_error = '(Fila Nro.' . $row . ') E: Debe ser un valor numérico y aceptar hasta 2 decimales.';
                break;
            case "E.2":

                $result_error = '(Fila Nro.' . $row . ') E: Debe validar que el número de garantía registre previamente en el sistema (o en el mismo archivo que se está importando) una caída.';
                break;
            case "E.3":
                $result_error = '(Fila Nro.' . $row . ') E: Debe validar que la suma de todos los RECUPEROS e INCOBRABLES registrados en el Sistema (incluidos los informados  en el archivo que se está importando) para una misma garantía no supere la suma de todas las caídas de esa misma garantía registradas en el Sistema (incluidos los informados  en el archivo que se está importando).';
                break;
            case "F.1":
                $result_error = '(Fila Nro.' . $row . ') F: Debe ser un valor numérico y aceptar hasta 2 decimales.';
                break;
            case "F.2":
                $result_error = '(Fila Nro.' . $row . ') F: Debe validar que el número de garantía registre previamente en el sistema (o en el mismo archivo que se está importando) una caída.';
                break;
            case "G.1":
                $result_error = '(Fila Nro.' . $row . ') G: Debe ser un valor numérico y aceptar hasta 2 decimales.';
                break;
            case "G.2":
                $result_error = '(Fila Nro.' . $row . ') G: Debe validar que el número de garantía registre previamente en el sistema (o en el mismo archivo que se está importando) un GASTO POR GESTIÓN DE RECUPERO.';
                break;
            case "G.3":
                $result_error = '(Fila Nro.' . $row . ') G: Debe validar que la suma de todos los RECUPEROS POR GASTOS DE GESTIÓN DE RECUPEROS e INCOBRABLES POR GASTOS DE GESTIÓN DE RECUPEROS registrados en el Sistema (incluidos los informados  en el archivo que se está importando) para una misma garantía no supere la suma de todos los GASTOS POR GESTIÓN DE RECUPEROS de esa misma garantía registrados en el Sistema (incluidos los informados  en el archivo que se está importando).';
                break;
            case "H.1":
                $result_error = '(Fila Nro.' . $row . ') H: Debe ser un valor numérico y aceptar hasta 2 decimales.';
                break;
            case "H.2":
                $result_error = '(Fila Nro.' . $row . ') H: Debe validar que el número de garantía registre previamente en el sistema (o en el mismo archivo que se está importando) un GASTO POR GESTIÓN DE RECUPERO.';
                break;
            case "H.3":
                $result_error = '(Fila Nro.' . $row . ') H: Debe validar que la suma de todos los RECUPEROS POR GASTOS DE GESTIÓN DE RECUPEROS e INCOBRABLES POR GASTOS DE GESTIÓN DE RECUPEROS registrados en el Sistema (incluidos los informados  en el archivo que se está importando) para una misma garantía no supere la suma de todos los GASTOS POR GESTIÓN DE RECUPEROS de esa misma garantía registrados en el Sistema (incluidos los informados  en el archivo que se está importando).';
                break;
        }
        return $result_error;
    }

}
