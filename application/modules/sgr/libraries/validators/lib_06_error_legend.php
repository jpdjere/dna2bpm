<?php

class Lib_06_error_legend {

    public function __construct() {
        $this->result = $this->return_legend($code, $row, $value);
    }

    function return_legend($code, $row, $value) {

        switch ($code) {
            case "VG.1":
                $result_error = '<strong>Columna A - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Se esta intentando INCORPORAR el mismo CUIT mas de una vez dentro del importador.';
                break;
            
            case "A.1":
                $result_error = '<strong>Columna A - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Tipo de Operación: El campo no puede estar vacío y debe contener uno de los siguientes parámetros: "INCORPORACION, INCREMENTO TENENCIA ACCIONARIA", "DISMINUCION DE CAPITAL SOCIAL", "INTEGRACION PENDIENTE".';
                break;

            case "B.1":
                $result_error = '<strong>Columna B - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Tipo de Socio: El campo no puede estar vacío y debe contener uno de los siguientes parámetros: "A", o "B".';
                break;

            case "AC.1":
                $result_error = '<strong>Columna AC - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Tipo de Acta: El campo no puede estar vacío y debe contener uno de los siguientes parámetros: AGE – (Si es Acta de Asamblea General Extraordinaria),"AGO" – (Si es Acta de Asamblea General Ordinaria),"ACA" – (Si es Acta de Consejo de Administración,"EC" – (Si es Estatuto Constitutivo).';
                break;

            case "AD.1":
                $result_error = '<strong>Columna AD - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/> Fecha de Acta: El campo no puede estar vacío y debe contener cinco dígitos numéricos (Formato General) - Ej. Para la fecha "01/10/2013" debe ingresarse "41548".';
                break;

            case "AE.1":
                $result_error = '<strong>Columna AE - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>AE - Número de Acta: OPCIONAL. De ser completado, deben ser datos numéricos.';
                break;

            case "AF.1":
                $result_error = '<strong>Columna AF - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>                AF - Fecha de Transacción: El campo no puede estar vacío y debe contener cinco dígitos numéricos (Formato General). La fecha debe estar dentro del período informado. - Ej. Para la fecha "01/10/2013" debe ingresarse "41548".';
                break;

            case "AG.1":
                $result_error = '<strong>Columna AG - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>AG - Modalidad de Adquisición/Venta de Acciones: El campo no puede estar vacío y debe contener uno de los siguientes parámetros: "SUSCRIPCION", o "TRANSFERENCIA".';
                break;

            case "AG.2":
                $result_error = '<strong>Columna AG- Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/> AG- Modalidad de Adquisición/Venta de Acciones: En caso de que en la Columna A se complete la opción "DISMINUCION DE CAPITAL SOCIAL", solo puede contener la opción "SUSCRIPCION".';
                break;

            case "AH.1":
                $result_error = '<strong>Columna AH - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>AH - Capital Suscripto: El campo no puede estar vacío y debe contener dígitos numéricos enteros, SIN DECIMALES.';
                break;
            case "AI.1":
                $result_error = '<strong>Columna AI - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>AI - Acciones Suscriptas: El campo no puede estar vacío y debe contener dígitos numéricos enteros, SIN DECIMALES.';
                break;

            case "AH.2":
                $result_error = '<strong>Columna AH - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>                AH - Sin en la Columna A se completó la opción “INCORPORACION”, “INCREMENTO DE TENENCIA ACCIONARIA” o “DISMINUCIÓN DE CAPITAL SOCIAL”, debe ingresarse un valor mayor a cero.';
                break;

            case "AI.2":
                $result_error = '<strong>Columna AI - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>AI - Si la columna AJ está completa, el Socio Cedente informado en la misma debe poseer la cantidad de Capital Integrado para transferir, correspondiente al tipo de Acción que posea, “A” o “B”. De no poseerlo, se rechazará la importación.';
                break;
            
            case "AJ.1":
                $result_error = '<strong>Columna AJ - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>AJ - En caso de que en la columna “A” se completen las opciones “INCORPORACIÓN” o “INCREMENTO DE TENENCIA ACCIONARIA” y en la columna AG se complete la modalidad "SUSCRIPCION", esta columna DEBE QUEDAR VACÍA.';
                break;
            
            case "AJ.2":
                $result_error = '<strong>Columna AJ - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>AL - CUIT del Cedente: En caso de que en la columna "A" se complete la opción "DISMINUCIÓN DE CAPITAL SOCIAL", esta columna NO PUEDE ESTAR VACÍA.';
                break;
            case "AJ.3": 
                $result_error = '<strong>Columna Columna AJ - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/> Columna AJ - En caso de que en la columna AG se complete la modalidad TRANSFERENCIA, esta columna NO PUEDE QUEDAR VACÍA.';
                break;
            case "AK.2":
                $result_error = '<strong>Columna AK - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>                AK - En caso de que en la columna AG se completa la opción TRANSFERENCIA, se debe chequear en los movimientos históricos que el Socio Cedente (Informado en las columnas "AL" y "AM") cuente con los saldos a ser transferidos, y que pertenezcan al tipo de Acción que corresponda, "A" o "B".';
                break;

            case "AL.1":
                $result_error = '<strong>Columna AL - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>AL - CUIT del Cedente: En caso de que en la columna "A" se completen las opciones "INCORPORACIÓN" o "INCREMENTO DE TENENCIA ACCIONARIA" y en la columna "AG" se complete la modalidad SUSCRIPCION, esta columna debe estar vacía.';
                break;

            case "AL.2":
                $result_error = '<strong>Columna AL - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>CUIT del Cedente: En caso de que en la columna "A" se complete la opción "DISMINUSIÓN DE CAPITAL SOCIAL", estas dos columnas NO PUEDEN ESTAR VACÍAS.';
                break;

            case "AL.3": $result_error = '<strong>Columna AL - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>AL - CUIT del Cedente: En caso de que en la columna "AG" se complete la modalidad "TRANSFERENCIA", esta columna NO PUEDE ESTAR VACÍA.';
                break;

            case "AM.1":
                $result_error = '<strong>Columna AM - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>AM - Carácter de la Cesión: En caso de que en la columna "A" se completen las opciones "INCORPORACION" o "INCREMENTO DE TENENCIA ACCIONARIA" y en la columna "AG" se complete la modalidad "SUSCRIPCION", esta columna debe estar vacía.';
                break;

            case "AM.2":
                $result_error = '<strong>Columna AM - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>AM - Carácter de la Cesión: En caso de que en la columna "A" se complete la opción "DISMINUCION DE CAPITAL SOCIAL", estas columna NO PUEDE ESTAR VACÍA.';
                break;

            case "AM.3":
                $result_error = '<strong>Columna AM - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>AM - Carácter de la Cesión: En caso de que en la columna "AG" se complete la modalidad TRANSFERENCIA, esta columna NO PUEDE ESTAR VACÍA.';
                break;

            case "AM.4":
                $result_error = '<strong>Columna AM - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>AM - Carácter de la Cesión: Debe contener solo uno de los siguientes parámetros: "DISMINUCION DE TENENCIA ACCIONARIA","DESVINCULACION".';
                break;

            case "B.2":
                $result_error = '<strong>Columna B - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Tipo de Socio: No se puede incorporar un Socio Protector como Socio Partícipe, y viceversa. Verifique la relación.';
                break;

            case "C.1":
                $result_error = '<strong>Columna C- Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>C- Cuit: El campo no puede estar vacío y  debe tener 11 caracteres sin guiones. Debe ser un CUIT Válido.';
                break;

            case "D.1":
                $result_error = '<strong>Columna D - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Nombre o Razón Social: El campo no puede estar vacío.';
                break;

            case "E.1":
                $result_error = '<strong>Columna E - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Provincia: El campo no puede estar vacío y debe contener sólo caracteres alfabéticos.';
                break;

            case "F.1":
                $result_error = '<strong>Columna F - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Partido/Municipio/Comuna: El campo no puede estar vacío.';
                break;

            case "G.1":
                $result_error = '<strong>Columna G - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/> Localidad: El campo no puede estar vacío.';
                break;

            case "H.1":
                $result_error = '<strong>Columna H - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Código Postal: El campo no puede estar vacío. Debe contener 8 dígitos y cumplir con el CPA (Código Postal Argentino) : El primero y los tres últimos alfabéticos, el segundo, tercero, cuarto y quinto numéricos.';
                break;

            case "I.1":
                $result_error = '<strong>Columna I - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/> I - Calle: El campo no puede estar vacío.';
                break;

            case "J.1":
                $result_error = '<strong>Columna J - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Número: El campo no puede estar vacío.';
                break;

            case "M.1": $result_error = '<strong>Columna M - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>M - Código de Área: El campo no puede estar vacío. Debe tener entre 2 y 4 dígitos e ingresarse sin cero adelante.';
                break;
            case "N.1": $result_error = '<strong>Columna N - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>N - Teléfono: El campo no puede estar vacío. Debe tener entre 6 y 10 dígitos.';
                break;
            case "O.1": $result_error = '<strong>Columna O - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>O - Email: OPCIONAL. Debe tener formato de correo electrónico (Ej. "controlsgr@industria.gob.ar").';
                break;
            case "P.1": $result_error = '<strong>Columna P - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>P - WEB: OPCIONAL. De completarse, que tenga formato de dirección de página web (Ej. "www.industria.gob.ar").';
                break;
            case "Q.1": $result_error = '<strong>Columna Q - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Q - Código de Actividad: El campo no puede estar vacío. Debe ser un Código C.I.I.U. Válido.';
                break;
            case "AA.1": $result_error = '<strong>Columna AA - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Condición de Inscripción ante AFIP: El campo no puede estar vacío y debe contener uno de los siguientes parámetros: "EXENTO","INSCRIPTO","MONOTRIBUTISTA".';
                break;
            case "AH.3": $result_error = '<strong>Columna ' . $code . ' - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Si en la columna "AG" se completó la opción “TRANSFERENCIA”, el valor aquí indicado debe ser igual al valor indicado en la Columna "AH".';
                break;
            case "AH.4": $result_error = '<strong>Columna ' . $code . ' - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Si la columna "AJ" está completa, el Socio Cedente informado en la misma debe poseer la cantidad de Capital Suscripto para transferir, correspondiente al tipo de Acción que posea, “A” o “B”. De no poseerlo, se rechazará la importación.';
                break;
            case "AI.3": $result_error = '<strong>Columna ' . $code . ' - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Si en la columna AG se completó la opción “TRANSFERENCIA”, el valor aquí indicado debe ser igual al valor indicado en la Columna AH.';
                break;
            case "AI.4": $result_error = '<strong>Columna ' . $code . ' - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Si en la Columna "A" se completó la opción “INCORPORACIÓN” y en la Columna "AG" se completó la opción “SUSCRIPCIÓN”, el valor aquí indicado debe ser mayor o igual al 50% del valor indicado en la Columna AH y a lo sumo igual a este último.';
                break;
            case "AI.5": $result_error = '<strong>Columna ' . $code . ' - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>El saldo de Capital Integrado nunca puede ser mayor al Saldo de Capital Suscripto.';
                break;
            case "AI.8": $result_error = '<strong>Columna ' . $code . ' - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Si en la Columna "A" se completa la opción “INTEGRACIÓN PENDIENTE”, este campo debe tomar valor mayor a CERO y se debe verificar que el valor indicado sea menor o igual a la diferencia entre los saldos previos de Capital Suscripto y Capital Integrado. Es decir, sólo se podrá realizar una “INTEGRACIÓN PENDIENTE”, en caso de que haya SUSCRIPTO CAPITAL sin haberlo integrado.';
                break;
            
            case "AK.3": $result_error = '<strong>Columna ' . $code . ' - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>EL SOCIO YA SE ENCUENTRA INCORPORADO A LA SGR, no puede incorporarlo nuevamente. ';
                break;
            case "Q.2": $result_error = '<strong>Columna Q - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Q - Código de Actividad: Debe coincidir con alguno de los Códigos del C.I.I.U.';
                break;
            case "R.1": $result_error = '<strong>Columna R - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>R - Año/Mes 1: OPCIONAL. Si las COLUMNAS "S" o "T" tienen datos, esta debe tener datos.';
                break;
            case "R.2": $result_error = '<strong>Columna R- Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>R- De ser informado debe tener el siguiente formato:  "xxxx/xx", correspondientes al formato AÑO/MES; que los dígitos del mes estén entre 01 y 12 (Ej. "2013/12").';
                break;
            case "R.3": $result_error = '<strong>Columna R- Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>R-   : De ser informado, el año debe ser no más de dos o tres años anterior al del período de incorporación que se está informando.';
                break;
            case "S.1": $result_error = '<strong>Columna S - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong>Monto 1: OPCIONAL. Si las COLUMNAS "R" o "T" tienen datos, esta debe tener datos..';
                break;
            case "S.2": $result_error = '<strong>Columna S - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/> Monto 1: De ser completado, debe contener caracteres numéricos, sin formato.';
                break;
            case "T.1": $result_error = '<strong>Columna T - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Tipo Origen 1: OPCIONAL. Si las COLUMNAS "R" o "S" tienen datos, esta debe tener datos.';
                break;
            case "T.2": $result_error = '<strong>Columna T - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>De ser completado, debe contener uno de los siguientes parámetros: "BALANCES" ,"CERTIFICACION DE INGRESOS" ,"DDJJ IMPUESTOS".';
                break;
            case "U.1": $result_error = '<strong>Columna U - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Año/Mes 2: OPCIONAL. Si las COLUMNAS "R", "V" o "W" tienen datos, esta debe tener datos.';
                break;
            case "U.2": $result_error = '<strong>Columna U - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Año/Mes 2: De ser informado debe tener el siguiente formato:  "xxxx/xx", correspondientes al formato AÑO/MES; que los dígitos del mes estén entre 01 y 12 (Ej. "2013/12").';
                break;
            case "U.3": $result_error = '<strong>Columna U - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Año/Mes 2: Si la COLUMNA "R" tiene datos, el año aquí informado debe ser el inmediato posterior a aquel. De lo contrario, sólo puede ser uno a dos años anterior al del período de incorporación que se está informando..';
                break;
            case "V.1": $result_error = '<strong>Columna V - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Monto 2: OPCIONAL. Si las COLUMNAS "U" o "W" tiene datos, esta debe tener datos.';
                break;
            case "V.2": $result_error = '<strong>Columna V - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Monto 2: De ser completado, debe contener caracteres numéricos, sin formato.';
                break;
            case "W.1": $result_error = '<strong>Columna W - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Tipo Origen 2: OPCIONAL. Si las COLUMNAS "U" o "V" tiene datos, esta debe tener datos.';
                break;
            case "W.2": $result_error = '<strong>Columna W - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/> Tipo Origen 2: De ser completado, debe contener uno de los siguientes parámetros: "BALANCES", "CERTIFICACION DE INGRESOS", "DDJJ IMPUESTOS".';
                break;
            case "X.1": $result_error = '<strong>Columna X - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Año/Mes 3: El campo no puede estar vacío. De ser informado debe tener el siguiente formato:  xxxx/xx, correspondientes al formato AÑO/MES; que los dígitos del mes estén entre 01 y 12..';
                break;
            case "X.2": $result_error = '<strong>Columna X - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/> Año/Mes 3: Si la COLUMNA "U" tiene datos, el año aquí informado debe ser el inmediato posterior a aquel. De lo contrario, sólo puede ser igual al del período que se está informando o un año anterior.';
                break;
            case "Y.1": $result_error = '<strong>Columna Y - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Monto 3: El campo no puede estar vacío y debe contener caracteres numéricos..';
                break;
            case "Z.1": $result_error = '<strong>Columna Z - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Tipo Origen 3: El campo no puede estar vacío y debe contener uno de los siguientes parámetros: "BALANCES", "CERTIFICACION DE INGRESOS", "DDJJ IMPUESTOS", "ESTIMACION".';
                break;
            case "Z.2": $result_error = '<strong>Columna Z - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Si se completó "ESTIMACIÓN", el año informado en la COLUMNA "X" debe corresponder con el del período de incorporación que se está informando, y las COLUMNAS "R" a "W" deben estar vacías.';
                break;
            case "S.3, V.3, Y.2": $result_error = '<strong>Columna ' . $code . ' - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Promedio Montos Facturados: Promedio Montos Facturados: El Promedio de los Tres años debe ser menor al límite establecido por la normativa vigente (Resolución SEPyMEyDR Nº 24/2001, modificatorias y complementarias) para cada sector de actividad.';
                break;
            case "AB.1": $result_error = '<strong>Columna AB - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Cantidad de Empleados: El campo no puede estar vacío y debe contener caracteres numéricos mayores a Cero.';
                break;
            case "Q.3": $result_error = '<strong>Columna Q - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Q - Código de Actividad: Está columna DEBE ESTAR VACÍA.';
                break;
            case "R.4": $result_error = '<strong>Columna R- Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>R- Año/Mes 1: Está columna DEBE ESTAR VACÍA.';
                break;
            case "S.4": $result_error = '<strong>Columna S - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Monto 1: Está columna DEBE ESTAR VACÍA.';
                break;
            case "T.3": $result_error = '<strong>Columna T- Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>T- Tipo Origen 1: Está columna DEBE ESTAR VACÍA.';
                break;
            case "U.4": $result_error = '<strong>Columna U - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Año/Mes 2: Está columna DEBE ESTAR VACÍA.';
                break;
            case "V.4": $result_error = '<strong>Columna V - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Monto 2: Está columna DEBE ESTAR VACÍA.';
                break;
            case "W.3": $result_error = '<strong>Columna W - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Tipo Origen 2: Está columna DEBE ESTAR VACÍA.';
                break;
            case "X.3": $result_error = '<strong>Columna X- Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>X- Año/Mes 3: Está columna DEBE ESTAR VACÍA.';
                break;
            case "Y.3": $result_error = '<strong>Columna Y - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Monto 3: Está columna DEBE ESTAR VACÍA.';
                break;
            case "Z.3": $result_error = '<strong>Columna Z- Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Z- Tipo Origen 3: Está columna DEBE ESTAR VACÍA.';
                break;
            case "AB.2": $result_error = '<strong>Columna AB- Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>AB- Cantidad de Empleados: Está columna DEBE ESTAR VACÍA.';
                break;
            case "B.3": $result_error = '<strong>Columna B - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Tipo de Socio: Debe verificar que el Tipo de Socio se correspondan con el carácter que el Socio tiene en ese momento en la SGR. Si es Partícipe, puede incrementar la tenencia de Acciones Clase A, y si es protector sólo de Acciones Clase B.';
                break;
            case "C.2": $result_error = '<strong>Columna C - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Debe verificar que el CUIT que se esté incorporando no esté incorporado previamente, para la cual deber verificar que no tenga saldos de Capital positivos (Tanto Suscriptos como Integrados), en la SGR en que se está incorporando.';
                break;
            case "C.3": $result_error = '<strong>Columna C - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>El campo no puede estar vacío y  debe tener 11 caracteres sin guiones.';
                break;
            case "D.3": $result_error = '<strong>Columna D - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Nombre o Razón Social: El campo no puede estar vacío.';
                break;
            case "E.2": $result_error = '<strong>Columna E - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Provincia: Está columna DEBE ESTAR VACÍA.';
                break;
            case "F.2": $result_error = '<strong>Columna F - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Partido/Municipio/Comuna: Está columna DEBE ESTAR VACÍA.';
                break;
            case "G.2": $result_error = '<strong>Columna G - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Localidad: Está columna DEBE ESTAR VACÍA.';
                break;
            case "H.2": $result_error = '<strong>Columna H - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Código Postal: Está columna DEBE ESTAR VACÍA.';
                break;
            case "I.2": $result_error = '<strong>Columna I - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>I - Calle: Está columna DEBE ESTAR VACÍA.';
                break;
            case "J.2": $result_error = '<strong>Columna J - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>J - Número: Está columna DEBE ESTAR VACÍA.';
                break;
            case "K.2": $result_error = '<strong>Columna K - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>K - Piso: Está columna DEBE ESTAR VACÍA.';
                break;
            case "L.2": $result_error = '<strong>Columna L - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>L - Dpto. / Oficina: Está columna DEBE ESTAR VACÍA.';
                break;
            case "M.2": $result_error = '<strong>Columna M - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>M - Código de Área: Está columna DEBE ESTAR VACÍA.';
                break;
            case "N.2": $result_error = '<strong>Columna M - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>N - Teléfono: Está columna DEBE ESTAR VACÍA.';
                break;
            case "O.2": $result_error = '<strong>Columna O - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>O - Email: Está columna DEBE ESTAR VACÍA.';
                break;
            case "P.2": $result_error = '<strong>Columna P - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>P - WEB: Está columna DEBE ESTAR VACÍA.';
                break;
            case "Q.4": $result_error = '<strong>Columna Q - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Q - Código de Actividad: Está columna DEBE ESTAR VACÍA.';
                break;
            case "R.5": $result_error = '<strong>Columna R - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>R - Año/Mes 1: Está columna DEBE ESTAR VACÍA.';
                break;
            case "S.5": $result_error = '<strong>Columna S - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Monto 1: Está columna DEBE ESTAR VACÍA.';
                break;
            case "T.4": $result_error = '<strong>Columna T - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Tipo Origen 1: Está columna DEBE ESTAR VACÍA.';
                break;
            case "U.5": $result_error = '<strong>Columna U - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Año/Mes 2: Está columna DEBE ESTAR VACÍA.';
                break;
            case "V.5": $result_error = '<strong>Columna V - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Monto 2: Está columna DEBE ESTAR VACÍA.';
                break;
            case "W.4": $result_error = '<strong>Columna W - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Tipo Origen 2: Está columna DEBE ESTAR VACÍA.';
                break;
            case "X.4": $result_error = '<strong>Columna X - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Año/Mes 3: Está columna DEBE ESTAR VACÍA.';
                break;
            case "Y.4": $result_error = '<strong>Columna Y - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Monto 3: Está columna DEBE ESTAR VACÍA.';
                break;
            case "Z.4": $result_error = '<strong>Columna Z - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Tipo Origen 3: Está columna DEBE ESTAR VACÍA.';
                break;
            case "AA.2": $result_error = '<strong>Columna AA - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Condición de Inscripción ante AFIP: Está columna DEBE ESTAR VACÍA.';
                break;
            case "AB.3": $result_error = '<strong>Columna AB - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Cantidad de Empleados: Está columna DEBE ESTAR VACÍA.';
                break;
            case "S.3": $result_error = '<strong>Columna S - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>El Promedio de los Tres años debe ser menor al límite establecido por la normativa vigente (Resolución SEPyMEyDR Nº 24/2001, modificatorias y complementarias) para cada sector de actividad.';
                break;
            case "C-AB": $result_error = '<strong>Columna ' . $code . ' - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>La/s columna/s (C, D, E, F, G, H, I, J, K, L, M, N, O, P, Q, R, S, T, U, V, W, X, Y, Z, AA, AB) debe/n estar vacia/s.';
                break;
            case "E-AB": $result_error = '<strong>Columna ' . $code . ' - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>La/s columna/s (E, F, G, H, I, J, K, L, M, N, O, P, Q, R, S, T, U, V, W, X, Y, Z, AA, AB) debe/n estar vacia/s.';
                break;
            case "Q-AB": $result_error = '<strong>Columna ' . $code . ' - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>La/s columna/s (R, S, T, U, V, W, X, Y, Z, AB) debe/n estar vacia/s.';
                break;
        }

        return $result_error;
    }

}