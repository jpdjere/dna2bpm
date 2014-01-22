<?php

class Lib_12_error_legend {

    public function __construct() {
        $this->result = $this->return_legend($code, $row, $value);
    }

    function return_legend($code, $row, $value) {

        switch ($code) {
            case "A.1": 
                $result_error = '(Fila Nro.'. $row .') A: El Número no puede estar cargado previamente en el Sistema en la misma SGR, así como tampoco puede estar repetido en el archivo que se está importando.';
                break;            
             case "B.1": 
                $result_error = '(Fila Nro.'. $row .') B: Debe tener 11 caracteres numéricos sin guiones. Debe verificarse que el CUIT esté registrado en el sistema como Socio Partícipe (Clase A) y que tengas saldo positivo de tenencia accionaria.';
                break;
            case "B.2": 
                $result_error = '(Fila Nro.'. $row .') B: Debe verificar que para cada CUIT informado se cuente con información de Facturación y Cantidad de Empleados informados mediante ANEXOS 6 o 6.2 correspondiente al año anterior al período que se está informando.';
                break;           
             case "C.1": 
                $result_error = '(Fila Nro.'. $row .') C: Debe contener cinco dígitos numéricos. La fecha debe estar dentro del período informado.';
                break;
            case "D.1": 
                $result_error = '(Fila Nro.'. $row .') D: Debe contener uno de los parámetros establecidos en el Anexo adjunto donde se lista el Tipo de Garantías.';
                break;
            case "E.1": 
                $result_error = '(Fila Nro.'. $row .') E: Aceptar hasta dos decimales.';
                break;            
            case "F.1": 
                $result_error = '(Fila Nro.'. $row .') F: Debe contener uno de los siguientes parámetros: PESOS ARGENTINOS/DOLARES AMERICANOS';
                break;
            case "G.1": 
                $result_error = '(Fila Nro.'. $row .') G : Sólo deberá estar completo en caso de que en la Columna D – Tipo de Garantía Otorgada, se haya completado alguna de las siguientes opciones:GFFF1/GFFF2/GFFF3/CFCPD';
                break;
            case "G.2": 
                $result_error = '(Fila Nro.'. $row .') G : Si la Columna D se completó con la opción CFCPD, deberá tener el siguiente formato: 4 LETRAS Y 9 NÚMEROS. Ej. CUAV250200005 Las cuatro letras deben coincidir con el Código asignado a cada SGR por la CNV.';
                break;
            case "G.3": 
                $result_error = '(Fila Nro.'. $row .') G : Si la Columna D se completó con alguna de las siguientes Opciones: GFON1/GFON2/GFON3/ deberá tener el siguiente formato: 3 Letras, un Numero, una letra. Ej. OAH1P
';
                break;
            case "H.1": 
                $result_error = '(Fila Nro.'. $row .') H : Sólo deberá estar completo en caso de que en la Columna D – Tipo de Garantía Otorgada, se haya completado alguna de las siguientes opciones:GFFF1/GFFF2/GFFF3/CFCPD.';
                break;
            case "H.2": 
                $result_error = '(Fila Nro.'. $row .') H : .Debe tener 11 caracteres sin guiones.';
                break;
            case "I.1": 
                $result_error = '(Fila Nro.'. $row .') I : Sólo deberá estar completo en caso de que en la Columna D – Tipo de Garantía Otorgada, se haya completado alguna de las siguientes opciones: CFCPD/GFON1/GFON2/GFON3';
                break;
            case "I.2": 
                $result_error = '(Fila Nro.'. $row .') I : Si la Columna D se completó con la opción CFCPD, deberá tener el siguiente formato: 4 LETRAS Y 9 NÚMEROS. Ej. CUAV250200005 Las cuatro letras deben coincidir con el Código asignado a cada SGR por la CNV. Se adjunta Anexo con Códigos.';
                break;
            case "I.3": 
                $result_error = '(Fila Nro.'. $row .') I : Si la Columna D se completó con alguna de las siguientes Opciones:GFON1/GFON2/GFON3/ deberá tener el siguiente formato: 3 Letras, un Numero, una letra. Ej. OAH1P';
                break;
            case "J.1": 
                $result_error = '(Fila Nro.'. $row .') J : En caso de que el CUIT informado ya está registrado en la Base de Datos del Sistema, este tomará en cuenta el nombre allí registrado. En caso contrario, se mantendrá provisoriamente el nombre informado por la SGR.';
                break;
            case "K.1": 
                $result_error = '(Fila Nro.'. $row .') K : Debe tener 11 caracteres sin guiones.';
                break;
            case "K.2": 
                $result_error = '(Fila Nro.'. $row .') K : Si el Tipo de Garantía informado en la Columna D es alguno de los siguientes:GFCPD, GFFF1, GFFF2, GFFF3, GFON1, GFON2, GFON3, GFMFO debe validar que hayan informado alguno de los CUIT detallados en el Anexo adjunto, donde se listan los Mercados de Valores donde se realizan las operaciones.';
                break;
            case "K.3   ": 
                $result_error = '(Fila Nro.'. $row .') K : i el Tipo de Garantía informado en la Columna D es alguno de los siguientes:GFEF1, GFEF2, GFEF3 Debe validar que hayan informado alguno de los CUIT detallados en el Anexo adjunto, donde se listan los BANCOS COMERCIALES que son los únicos pueden aceptar dichos tipos de garantías.';
                break;
            case "L.2": 
                $result_error = '(Fila Nro.'. $row .') L : Aceptar hasta dos decimales.';
                break;
            case "M.2": 
                $result_error = '(Fila Nro.'. $row .') M : Debe contener uno de los siguientes parámetros: Pesos Argentinos/Dolares Americanos';
                break;
            case "N.1": 
                $result_error = '(Fila Nro.'. $row .') N : Debe contener uno de los siguientes parámetros:FIJA/LIBOR/BADLAR PU (Badlar Bancos Públicos)/BADLAR PR (Badlar Bancos Privados)/TEC/TEBP';
                break;
            case "P.1": 
                $result_error = '(Fila Nro.'. $row .') P : Debe ser un campo numérico, sin decimales, y mayor a cero.';
                break;
            case "P.2": 
                $result_error = '(Fila Nro.'. $row .') P : Si en la Columna “D” el Tipo de Garantía seleccionado fue GFCPD, el plazo debe ser mayor a cero y meno a 365 (366 si implica un año bisiesto).';
                break;
            case "Q.1": 
                $result_error = '(Fila Nro.'. $row .') Q : Debe ser un campo numérico, sin decimales, y mayor a cero.';
                break;
            case "Q.2": 
                $result_error = '(Fila Nro.'. $row .') Q : Si en la Columna R se indicó PAGO ÚNICO, el valor aquí indicado debe ser igual al valor indicado en la Columna P.';
                break;
            case "R.1": 
                $result_error = '(Fila Nro.'. $row .') R : Debe contener uno de los siguientes parámetros:PAGO UNICO/MENSUAL/BIMESTRAL/TRIMESTRAL/CUATRIMESTRAL/SEMESTRAL/ANUAL/OTRO';
                break;
            case "R.2": 
                $result_error = '(Fila Nro.'. $row .') R : Si en la Columna “D” el Tipo de Garantía seleccionado fue GFCPD, este campo sólo puede indicar PAGO UNICO.';
                break;
            case "S.1": 
                $result_error = '(Fila Nro.'. $row .') S : Debe contener uno de los siguientes parámetros:PAGO UNICO/FRANCES/ALEMAN/AMERICANO/OTRO';
                break;
            case "S.2": 
                $result_error = '(Fila Nro.'. $row .') S : Si en la Columna “D” el Tipo de Garantía seleccionado fue GFCPD, este campo sólo puede indicar PAGO UNICO.';
                break;
            case "S.3": 
                $result_error = '(Fila Nro.'. $row .') S : Si en la Columna “T” se indicó que la Periodicidad de los pagos es PAGO UNICO, este campo sólo puede indicar PAGO UNICO.';
                break;
            case "T.1": 
                $result_error = '(Fila Nro.'. $row .')  : Debe contener uno de los siguientes parámetros:OBRA CIVIL/BIENES DE CAPITAL/INMUEBLES/CAPITAL DE TRABAJO/PROYECTO DE INVERSIÓN';
                break;            
        }
        return $result_error;
    }

}
