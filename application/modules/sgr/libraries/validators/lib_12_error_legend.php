<?php

class Lib_12_error_legend {

    public function __construct() {
        $this->result = $this->return_legend($code, $row, $value);
    }

    function return_legend($code, $row, $value) {
        switch ($code) {           
            case "A.1":
                $result_error = '<strong>Columna A - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>El Número de Garantía no puede estar cargado previamente en el Sistema, así como tampoco puede estar repetido en el archivo que se está importando.';
                break;
            case "B.1":
                $result_error = '<strong>Columna B - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Debe tener 11 caracteres numéricos sin guiones. Debe verificarse que el CUIT esté registrado en el sistema como Socio Partícipe (Clase A) y que tenga saldo positivo de tenencia accionaria.';
                break;
            case "B.2":
                $result_error = '<strong>Columna B - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Debe verificar que para cada CUIT informado se cuente con información de Facturación y Cantidad de Empleados informados mediante ANEXOS 6 o 6.2 correspondiente al año anterior al período que se está informando. Ej. Si se están informando las garantías otorgadas en Enero de 2014, deben haber informado previamente la información de Facturación y Cantidad de Empleados del año 2013. Valida solo el año de carga, y no el mes. ';
                break;
            case "B.3":
                $result_error = '<strong>Columna B - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Debe verificar en el sistema que para el CUIT informado el Saldo de Capital Integrado sea igual que el Saldo de Capital suscripto (Art. 50 Ley 24.467).';
                break;
            case "C.1":
                $result_error = '<strong>Columna C - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Debe ser un numéro de cinco dígitos sin decimales. Ej. Para la fecha "01/10/2013" debe ingresarse "41548" (formato general). La fecha debe estar dentro del período informado.';
                break;
            case "D.1":
                $result_error = '<strong>Columna D - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Debe contener uno de los códigos asignados a un tipo de garantía válida (Ej. "GFCPD", "GFEF0", "GFMFO"). ';
                break;
            case "E.1":
                $result_error = '<strong>Columna E - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>El número ingresado aceptará hasta dos decimales.';
                break;
            case "F.1":
                $result_error = '<strong>Columna F - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Debe contener uno de los siguientes parámetros: "Pesos Argentinos" o "Dolares Americanos". Si la Columna "D" se completó con la opción GFCPD, la moneda de origen sólo podrá ser "Pesos Argentinos".';
                break;
            case "G.1":
                $result_error = '<strong>Columna G - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Sólo deberá estar completo en caso de que en la Columna "D" – "Tipo de Garantía Otorgada", se haya completado alguna de las siguientes opciones: "GFFF0"/"GFFF1"/"GFFF2"/"GFFF3"/"GFPB"/"GFCPD".';
                break;
            case "G.2":
                $result_error = '<strong>Columna G.2 - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>En caso de que el CUIT informado ya está registrado en la Base de Datos del Sistema, este tomará en cuenta el nombre allí registrado. En caso contrario, se mantendrá provisoriamente el nombre informado por la SGR.';
                break;
            case "H.1":
                $result_error = '<strong>Columna H - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Sólo deberá estar completo en caso de que en la Columna "D" – "Tipo de Garantía Otorgada", se haya completado alguna de las siguientes opciones:"GFFF0"/"GFFF1"/"GFFF2"/"GFFF3"/"GFPB"/"GFCPD".';
                break;
            case "H.2":
                $result_error = '<strong>Columna H - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>De completarse, debe tener 11 caracteres sin guiones y corresponder a un CUIT válido.';
                break;
            case "I.1":
                $result_error = '<strong>Columna I - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Sólo deberá estar completo en caso de que en la Columna "D" – "Tipo de Garantía Otorgada", se haya completado alguna de las siguientes opciones: "GFCPD"/"GFON0"/"GFON1"/"GFON2".';
                break;
            case "I.2":
                $result_error = '<strong>Columna I - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Si la Columna "D" se completó con la opción "GFCPD" o "GFPB", deberá tener el siguiente formato: 4 LETRAS Y 9 NÚMEROS. (Ej. "CUAV250200005"). Las cuatro letras deben coincidir con el Código asignado a cada SGR por la CNV.';
                break;
            case "I.3":
                $result_error = '<strong>Columna I - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Si la Columna "D" se completó con alguna de las siguientes Opciones:"GFON0"/GFON1/GFON2/GFON3/GFVCP. deberá tener el siguiente formato: 3 Letras, un Numero, una letra. Ej. "OAH1P".';
                break;
            case "I.4":
                $result_error = '<strong>Columna I - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Si la Columna "D" se completó con alguna de las siguientes Opciones:"GFPB". deberá tener el siguiente formato: $, 3 Letras, 9 Números. (Ej. "$CUA000000001") y coincidir con el código asignado para cada SGR.';
                break;
            case "J.1":
                $result_error = '<strong>Columna J - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Debe tener 11 caracteres sin guiones.';
                break;
            case "K.1":
                $result_error = '<strong>Columna K - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Debe tener 11 caracteres sin guiones.';
                break;
            case "K.2":
                $result_error = '<strong>Columna K - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Si el Tipo de Garantía informado en la Columna "D" es alguno de los siguientes:"GFCPD", "GFVCP", "GFPB", "GFFF1", "GFFF2", "GFFF3", "GFON1", "GFON2", "GFON3", "GFMFO", el CUIT informado deberá estar incluido en el Anexo donde se listan los Mercados de Valores donde se realizan las operaciones.';
                break;
            case "K.3":
                $result_error = '<strong>Columna K - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Si el Tipo de Garantía informado en la Columna "D" es alguno de los siguientes: "GFEF0", "GFEF1", "GFEF2", "GFEF3" , el CUIT informado deberá estar incluido en el anexo donde se listan los BANCOS COMERCIALES que son los únicos pueden aceptar dichos tipos de garantías.';
                break;
            case "L.1":
                $result_error = '<strong>Columna L - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Debe tener formato numérico. Acepta hasta dos decimales. No puede tomar valores negativos.';
                break;
            case "M.2":
                $result_error = '<strong>Columna M - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Si la Columna "D" se completó con la opción "GFCPD", la moneda de origen sólo podrá ser "Pesos Argentinos".';
                break;
            case "N.1":
                $result_error = '<strong>Columna N - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Debe contener uno de los siguientes parámetros: "FIJA"/"LIBOR"/"BADLARPU" (Badlar Bancos Públicos)/"BADLARPR" (Badlar Bancos Privados)/"TEC"/"TEBP".';
                break;
            case "O.1":
                $result_error = '<strong>Columna O - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Debe tomar valores entre -20 y -1 o entre 1 y 20 (es decir, no puede tomar valores entre -1 y 1).';
                break;
            case "O.2":
                $result_error = '<strong>Columna O - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Si en la Columna N se indicó que la tasa es “FIJA”, debe tomar un valor entre -25 y 50, con dos decimales.';
                break;
            case "P.1":
                $result_error = '<strong>Columna P - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Debe ser un campo numérico, sin decimales, y mayor a cero.';
                break;
            case "P.2":
                $result_error = '<strong>Columna P - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/> Si en la Columna “D” el Tipo de Garantía seleccionado fue "GFCPD", el plazo debe ser mayor a cero y menor a 365 (366 si implica un año bisiesto).';
                break;
            case "P.3":
                $result_error = '<strong>Columna P - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Si en la Columna “D” el Tipo de Garantía seleccionado fue "GFVCP", el plazo debe ser mayor a cero y menor a 365 (366 si implica un año bisiesto).';
                break;
            case "P.4":
                $result_error = '<strong>Columna P - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>El plazo informado debe encontrarse dentro de los límites para cada una de las Garantías (Establecido mediante el anexo correspondiente).';
                break;
            case "P.5":
                $result_error = '<strong>Columna P - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Para las garantías cuyo acreedor sea "FONAPYME", el plazo máximo no podrá exceder de 2555 días.';
                break;
            case "Q.1":
                $result_error = '<strong>Columna Q - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Debe ser un campo numérico, sin decimales, y mayor a cero.';
                break;
             case "Q.2":
                $result_error = '<strong>Columna Q - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Si en la Columna "R" se indicó "PAGO ÚNICO", el valor aquí indicado debe ser igual al valor indicado en la Columna "P".';
                break;
             case "R.1":
                $result_error = '<strong>Columna R - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Debe contener uno de los siguientes parámetros:"PAGO UNICO"/"MENSUAL"/"BIMESTRAL"/"TRIMESTRAL"/"CUATRIMESTRAL"/"SEMESTRAL"/"ANUAL"/"OTRO"';
                break;
             case "R.2":
                $result_error = '<strong>Columna R - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Si en la Columna “D” el Tipo de Garantía seleccionado fue "GFCPD" o "GFVCP", este campo sólo puede indicar "PAGO UNICO".';
                break;
             case "S.1":
                $result_error = '<strong>Columna S - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>S - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>S - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Debe contener uno de los siguientes parámetros:"PAGO UNICO"/"FRANCES"/"ALEMAN"/"AMERICANO"/"OTRO".';
                break;
             case "S.2":
                $result_error = '<strong>Columna S - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>S - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Si en la Columna “D” el Tipo de Garantía seleccionado fue GFCPD o GFVCP, este campo sólo puede indicar PAGO UNICO.';
                break;
             case "S.3":
                $result_error = '<strong>Columna S - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>S - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Si en la Columna “T” se indicó que la Periodicidad de los pagos es "PAGO UNICO", este campo sólo puede indicar "PAGO UNICO".';
                break;
            case "T.1":
                $result_error = '<strong>Columna T - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>T - Fila Nro.'. $row .' - Código Validación '.$code.'</strong><br/>Debe contener uno de los siguientes parámetros:"OBRA CIVIL"/"BIENES DE CAPITAL"/"INMUEBLES"/"CAPITAL DE TRABAJO"/"PROYECTO DE INVERSIÓN".';
                break;
        }
        return $result_error;
    }

}
