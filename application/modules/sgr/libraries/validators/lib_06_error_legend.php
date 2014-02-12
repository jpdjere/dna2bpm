<?php

class Lib_06_error_legend {

    public function __construct() {
        $this->result = $this->return_legend($code, $row, $value);
    }

    function return_legend($code, $row, $value) {

        switch ($code) {
            case "A.1":
                $result_error = '<strong>Columna A - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Tipo de Operación: El campo no puede estar vacío y debe contener uno de los siguientes parámetros: INCORPORACION, INCREMENTO TENENCIA ACCIONARIA, DISMINUCION DE CAPITAL SOCIAL';
                break;

            case "B.1":
                $result_error = '<strong>Columna B - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Tipo de Socio: El campo no puede estar vacío y debe contener uno de los siguientes parámetros: A,B';
                break;

            case "AC.1":
                $result_error = '<strong>Columna AC - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Tipo de Acta: El campo no puede estar vacío y debe contener uno de los siguientes parámetros: AGE – Acta de Asamblea General Extraordinaria,AGO – Acta de Asamblea General Ordinaria,ACA – Acta de Consejo de Administración,EC – Estatuto Constitutivo';
                break;

            case "AD.1":
                $result_error = '<strong>Columna AD - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Fecha de Acta: El campo no puede estar vacío y debe contener cinco dígitos numéricos.';
                break;

            case "AE.1":
                $result_error = '<strong>Columna AE - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Número de Acta: OPCIONAL. De ser completado, deben ser datos numéricos.';
                break;

            case "AF.1":
                $result_error = '<strong>Columna AF - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Fecha de Transacción: El campo no puede estar vacío y debe contener cinco dígitos numéricos. La fecha debe estar dentro del período informado.';
                break;

            case "AG.1":
                $result_error = '<strong>Columna AG - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Modalidad de Adquisición/Venta de Acciones: El campo no puede estar vacío y debe contener uno de los siguientes parámetros: SUSCRIPCION,TRANSFERENCIA';
                break;

            case "AG.2":
                $result_error = '<strong>Columna AG- Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Modalidad de Adquisición/Venta de Acciones: En caso de que en la Columna A se complete la opción "DISMINUSION DE CAPITAL SOCIAL", solo puede contener la opción "SUSCRIPCION"';
                break;

            case "AH.1":
                $result_error = '<strong>Columna AH - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Capital Suscripto: El campo no puede estar vacío y debe contener dígitos numéricos enteros, sin decimales.';
                break;
            case "AI.1":
                $result_error = '<strong>Columna AI - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Acciones Suscriptas: El campo no puede estar vacío y debe contener dígitos numéricos enteros, sin decimales.';
                break;

            case "AH.2":
                $result_error = '<strong>Columna AH - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>En caso de que en la columna AG se completa la opción TRANSFERENCIA, se debe chequear en los movimientos históricos que el Socio Cedente (Columnas AL) tenga los saldos a ser transferidos y que corresponden al tipo de Acción que corresponda, “A” o “B”.';
                break;

            case "AI.2":
                $result_error = '<strong>Columna AI - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>En caso de que en la columna AG se completa la opción TRANSFERENCIA, se debe chequear en los movimientos históricos que el Socio Cedente (Columnas AL) tenga los saldos a ser transferidos y que corresponden al tipo de Acción que corresponda, “A” o “B”. ';
                break;
            
            case "AJ.1":
                $result_error = '<strong>Columna AJ - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>En caso de que en la columna “A” se completen las opciones “INCORPORACIÓN” o “INCREMENTO DE TENENCIA ACCIONARIA” y en la columna AG se complete la modalidad SUSCRIPCION, esta columna debe estar vacía.';
                break;
            
            case "AJ.2":
                $result_error = '<strong>Columna AJ - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>En caso de que en la columna “A” se complete la opción “DISMINUSIÓN DE CAPITAL SOCIAL”, esta columna NO PUEDE ESTAR VACÍA.';
                break;

            case "AK.2":
                $result_error = '<strong>Columna AK - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>En caso de que en la columna AG se completa la opción TRANSFERENCIA, se debe chequear en los movimientos históricos que el Socio Cedente (Columnas AL y AM)tenga los saldos a ser transferidos y que corresponden al tipo de Acción que corresponda, "A" o "B".';
                break;

            case "AL.1":
                $result_error = '<strong>Columna AL - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>CUIT del Cedente: En caso de que en la columna "A" se completen las opciones "INCORPORACIÓN" o "INCREMENTO DE TENENCIA ACCIONARIA" y en la columna AG se complete la modalidad SUSCRIPCION, deben estar vacías.';
                break;

            case "AL.2":
                $result_error = '<strong>Columna AL - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>CUIT del Cedente: En caso de que en la columna "A" se complete la opción "DISMINUSIÓN DE CAPITAL SOCIAL", estas dos columnas NO PUEDEN ESTAR VACÍAS.';
                break;

            case "AL.3": $result_error = '<strong>Columna AL - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>CUIT del Cedente: En caso de que en la columna AG se complete la modalidad TRANSFERENCIA, estas dos columnas NO PUEDEN ESTAR VACÍAS.';
                break;

            case "AM.1":
                $result_error = '<strong>Columna AM - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Carácter de la Cesión: En caso de que en la columna "A" se completen las opciones "INCORPORACION" o "INCREMENTO DE TENENCIA ACCIONARIA" y en la columna AG se complete la modalidad SUSCRIPCION, debe estar vacía.';
                break;

            case "AM.2":
                $result_error = '<strong>Columna AM - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Carácter de la Cesión: En caso de que en la columna "A" se complete la opción "DISMINUCION DE CAPITAL SOCIAL", estas dos columnas NO PUEDEN ESTAR VACÍAS.';
                break;

            case "AM.3":
                $result_error = '<strong>Columna AM - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Carácter de la Cesión: En caso de que en la columna AG se complete la modalidad TRANSFERENCIA, estas dos columnas NO PUEDEN ESTAR VACÍAS.';
                break;

            case "AM.4":
                $result_error = '<strong>Columna AM - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Carácter de la Cesión: De ser completada, debe contener uno de los siguientes parámetros: DISMINUCION DE TENENCIA ACCIONARIA,DESVINCULACION';
                break;

            case "B.2":
                $result_error = '<strong>Columna B - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Tipo de Socio: Debe verificar EN TODAS LAS SGR que el Socio que se Incorpora (Columnas C y D)no posea saldos positivos de acciones del carácter contrario al tipo de socio que se está incorporando (Si se incorpora como Partícipe que no sea Protector en ninguna SGR y viceversa).';
                break;

            case "C.1":
                $result_error = '<strong>Columna C- Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Cuit: El campo no puede estar vacío y  debe tener 11 caracteres sin guiones. El CUIT debe cumplir el "ALGORITMO VERIFICADOR".';
                break;

            case "D.1":
                $result_error = '<strong>Columna D - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Nombre o Razón Social: El campo no puede estar vacío. En caso de que el CUIT informado ya está registrado en la Base de Datos del Sistema, este tomará en cuenta el nombre allí registrado. En caso contrario, se mantendrá provisoriamente el nombre informado por la SGR.';
                break;

            case "E.1":
                $result_error = '<strong>Columna E - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Provincia: El campo no puede estar vacío y debe contener sólo caracteres alfabéticos.';
                break;

            case "F.1":
                $result_error = '<strong>Columna F - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Partido/Municipio/Comuna: El campo no puede estar vacío.';
                break;

            case "G.1":
                $result_error = '<strong>Columna G - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Localidad: El campo no puede estar vacío.';
                break;

            case "H.1":
                $result_error = '<strong>Columna H - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Código Postal: El campo no puede estar vacío. Debe contener 8 dígitos. El primero y los tres últimos alfabéticos, el segundo, tercero, cuarto y quinto numéricos.';
                break;

            case "I.1":
                $result_error = '<strong>Columna I - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Calle: El campo no puede estar vacío.';
                break;

            case "J.1":
                $result_error = '<strong>Columna J - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Número: El campo no puede estar vacío.';
                break;

            case "M.1": $result_error = '<strong>Columna M - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Código de Área: El campo no puede estar vacío. Debe tener entre 2 y 4 dígitos (sin el cero adelante)..';
                break;
            case "N.1": $result_error = '<strong>Columna N - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Teléfono: El campo no puede estar vacío. Debe tener entre 6 y 10 dígitos..';
                break;
            case "O.1": $result_error = '<strong>Columna O - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Email: OPCIONA. De completarse, que tenga formato de dirección de correo electrónico..';
                break;
            case "P.1": $result_error = '<strong>Columna P - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>WEB: OPCIONA. De completarse, que tenga formato de dirección de página web.';
                break;
            case "Q.1": $result_error = '<strong>Columna Q - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Código de Actividad: El campo no puede estar vacío. Debe validar que sea alguno de los que figuran en el Archivo Excel que va aparte (se llaman código CIU que es igual a la CLANAE pero con un digito menos).';
                break;
            case "AA.1": $result_error = '<strong>Columna AA - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Condición de Inscripción ante AFIP: "El campo no puede estar vacío y debe contener uno de los siguientes parámetros: 
EXCENTO,INSCRIPTO,MONOSTRIBUTISTA".';
                break;
            case "AH.3": $result_error = '<strong>Columna ' . $code . ' - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Se debe chequear en los movimientos históricos que el Socio Incorporado no tenga saldos positivos en ninguna de estas variables en la SGR en la que se está incorporando. De tenerlos, sean clase “A” o “B” debe rechazar la importación.';
                break;
            case "AI.3": $result_error = '<strong>Columna ' . $code . ' - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Se debe chequear en los movimientos históricos que el Socio Incorporado no tenga saldos positivos en ninguna de estas variables en la SGR en la que se está incorporando. De tenerlos, sean clase “A” o “B” debe rechazar la importación.';
                break;
            case "AJ.3": $result_error = '<strong>Columna ' . $code . ' - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>En caso de que en la columna AG se complete la modalidad TRANSFERENCIA, esta columna NO PUEDE ESTAR VACÍA.';
                break;
            case "AK.3": $result_error = '<strong>Columna ' . $code . ' - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>EL SOCIO YA SE ENCUENTRA INCORPORADO A LA SGR, no puede incorporarlo nuevamente. Se debe chequear en los movimientos históricos que el Socio Incorporado no tenga saldos positivos en ninguna de estas variables en la SGR en la que se está incorporando. ';
                break;
            case "Q.2": $result_error = '<strong>Columna Q - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Código de Actividad: Debe validar que coincida con alguno de los Códigos del CIU';
                break;
            case "R.1": $result_error = '<strong>Columna R - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Año/Mes 1: OPCIONAL. Si las COLUMNAS S o T tienen datos, esta debe tener datos.';
                break;
            case "R.2": $result_error = '<strong>Columna R- Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>De ser informado debe tener el siguiente formato:  xxxx/xx, correspondientes al formato AÑO/MES; que los dígitos del mes estén entre 01 y 12..';
                break;
            case "R.3": $result_error = '<strong>Columna R- Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>  : De ser informado, el año debe ser no más de dos o tres años anterior al del período de incorporación que se está informando..';
                break;
            case "S.1": $result_error = '<strong>Columna S - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Monto 1: OPCIONAL. Si las COLUMNAS R o T tienen datos, esta debe tener datos..';
                break;
            case "S.2": $result_error = '<strong>Columna S - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Monto 1: De ser completado, debe contener caracteres numéricos, sin formato.';
                break;
            case "T.1": $result_error = '<strong>Columna T - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Tipo Origen 1: OPCIOINAL. Si las COLUMNAS R o S tienen datos, esta debe tener datos..';
                break;
            case "T.2": $result_error = '<strong>Columna T - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>"De ser completado, debe contener uno de los siguientes parámetros: 
,BALANCES 
,CERTIFICACION DE INGRESOS 
,DDJJ IMPUESTOS ".';
                break;
            case "U.1": $result_error = '<strong>Columna U - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Año/Mes 2: OPCIONAL. Si las COLUMNAS R, V o W tienen datos, esta debe tener datos..';
                break;
            case "U.2": $result_error = '<strong>Columna U - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Año/Mes 2: De ser informado debe tener el siguiente formato:  xxxx/xx, correspondientes al formato AÑO/MES; que los dígitos del mes estén entre 01 y 12..';
                break;
            case "U.3": $result_error = '<strong>Columna U - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Año/Mes 2: Si la COLUMNA R tiene datos, el año aquí informado debe ser el inmediato posterior a aquel. De lo contrario, sólo puede ser uno a dos años anterior al del período de incorporación que se está informando..';
                break;
            case "V.1": $result_error = '<strong>Columna V - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Monto 2: OPCIONAL. Si las COLUMNAS U o W tiene datos, esta debe tener datos..';
                break;
            case "V.2": $result_error = '<strong>Columna V - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Monto 2: De ser completado, debe contener caracteres numéricos, sin formato..';
                break;
            case "W.1": $result_error = '<strong>Columna W - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Tipo Origen 2: OPCIONAL. Si las COLUMNAS U o V tiene datos, esta debe tener datos..';
                break;
            case "W.2": $result_error = '<strong>Columna W - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Tipo Origen 2: "De ser completado, debe contener uno de los siguientes parámetros: 
,BALANCES 
,CERTIFICACION DE INGRESOS 
,DDJJ IMPUESTOS ".';
                break;
            case "X.1": $result_error = '<strong>Columna X - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Año/Mes 3: El campo no puede estar vacío. De ser informado debe tener el siguiente formato:  xxxx/xx, correspondientes al formato AÑO/MES; que los dígitos del mes estén entre 01 y 12..';
                break;
            case "X.2": $result_error = '<strong>Columna X - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Año/Mes 3: Si la COLUMNA U tiene datos, el año aquí informado debe ser el inmediato posterior a aquel. De lo contrario, sólo puede ser igual al del período que se está informando o un año anterior..';
                break;
            case "Y.1": $result_error = '<strong>Columna Y - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Monto 3: El campo no puede estar vacío y debe contener caracteres numéricos..';
                break;
            case "Z.1": $result_error = '<strong>Columna Z - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Tipo Origen 3: "El campo no puede estar vacío y debe contener uno de los siguientes parámetros: 
,BALANCES 
,CERTIFICACION DE INGRESOS 
,DDJJ IMPUESTOS 
,ESTIMACION ".';
                break;
            case "Z.2": $result_error = '<strong>Columna Z - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Si se completó ESTIMACIÓN, el año informado en la COLUMNA X debe corresponder con el del período de incorporación que se está informando, y las COLUMNAS R a W deben estar vacías..';
                break;
            case "S.3, V.3, Y.2": $result_error = '<strong>Columna ' . $code . ' - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Promedio Montos Facturados: El Promedio de los Tres años debe ser menor al límite establecido por la normativa vigente (Resolución SEPyMEyDR Nº 24/2001, modificatorias y complementarias) para cada sector de actividad. Estos parámetros cambian a lo largo del tiempo, por lo que las validaciones, a su vez, deben respetar los límites correspondientes a cada período informado (Para los casos en que se rectifiquen períodos viejos)..';
                break;
            case "AB.1": $result_error = '<strong>Columna AB - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Cantidad de Empleados: El campo no puede estar vacío y debe contener caracteres numéricos mayores a Cero..';
                break;
            case "Q.3": $result_error = '<strong>Columna Q - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Código de Actividad: DEBE ESTAR VACÍA.';
                break;
            case "R.4": $result_error = '<strong>Columna R- Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Año/Mes 1: DEBE ESTAR VACÍA.';
                break;
            case "S.4": $result_error = '<strong>Columna S - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Monto 1: DEBE ESTAR VACÍA.';
                break;
            case "T.3": $result_error = '<strong>Columna T- Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Tipo Origen 1: DEBE ESTAR VACÍA.';
                break;
            case "U.4": $result_error = '<strong>Columna U - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Año/Mes 2: DEBE ESTAR VACÍA.';
                break;
            case "V.4": $result_error = '<strong>Columna V - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Monto 2: DEBE ESTAR VACÍA.';
                break;
            case "W.3": $result_error = '<strong>Columna W - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Tipo Origen 2: DEBE ESTAR VACÍA.';
                break;
            case "X.3": $result_error = '<strong>Columna X- Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Año/Mes 3: DEBE ESTAR VACÍA.';
                break;
            case "Y.3": $result_error = '<strong>Columna Y - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Monto 3: DEBE ESTAR VACÍA.';
                break;
            case "Z.3": $result_error = '<strong>Columna Z- Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Tipo Origen 3: DEBE ESTAR VACÍA.';
                break;
            case "AB.2": $result_error = '<strong>Columna AB- Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Cantidad de Empleados: DEBE ESTAR VACÍA.';
                break;
            case "B.3": $result_error = '<strong>Columna B - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Tipo de Socio: Debe verificar que el Tipo de Socio se correspondan con el carácter que el Socio tiene en ese momento en la SGR. Si es Partícipe, puede incrementar la tenencia de Acciones Clase A, y si es protector sólo de Acciones Clase B.';
                break;
            case "C.2": $result_error = '<strong>Columna C - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Cuit: El campo no puede estar vacío y  debe tener 11 caracteres sin guiones..';
                break;
            case "D.3": $result_error = '<strong>Columna D - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Nombre o Razón Social: El campo no puede estar vacío. En caso de que el CUIT informado ya está registrado en la Base de Datos del Sistema, este tomará en cuenta el nombre allí registrado. En caso contrario, se mantendrá provisoriamente el nombre informado por la SGR..';
                break;
            case "E.2": $result_error = '<strong>Columna E - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Provincia: DEBEN ESTAR VACÍAS.';
                break;
            case "F.2": $result_error = '<strong>Columna F - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Partido/Municipio/Comuna: DEBEN ESTAR VACÍAS.';
                break;
            case "G.2": $result_error = '<strong>Columna G - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Localidad: DEBEN ESTAR VACÍAS.';
                break;
            case "H.2": $result_error = '<strong>Columna H - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Código Postal: DEBEN ESTAR VACÍAS.';
                break;
            case "I.2": $result_error = '<strong>Columna I - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Calle: DEBEN ESTAR VACÍAS.';
                break;
            case "J.2": $result_error = '<strong>Columna J - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Número: DEBEN ESTAR VACÍAS.';
                break;
            case "K.2": $result_error = '<strong>Columna K - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Piso: DEBEN ESTAR VACÍAS.';
                break;
            case "L.2": $result_error = '<strong>Columna L - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Dpto. / Oficina: DEBEN ESTAR VACÍAS.';
                break;
            case "M.2": $result_error = '<strong>Columna M - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Código de Área: DEBEN ESTAR VACÍAS.';
                break;
            case "M.2": $result_error = '<strong>Columna M - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Teléfono: DEBEN ESTAR VACÍAS.';
                break;
            case "O.2": $result_error = '<strong>Columna O - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Email: DEBEN ESTAR VACÍAS.';
                break;
            case "P.2": $result_error = '<strong>Columna P - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>WEB: DEBEN ESTAR VACÍAS.';
                break;
            case "Q.4": $result_error = '<strong>Columna Q - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Código de Actividad: DEBEN ESTAR VACÍAS.';
                break;
            case "R.5": $result_error = '<strong>Columna R - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Año/Mes 1: DEBEN ESTAR VACÍAS.';
                break;
            case "S.5": $result_error = '<strong>Columna S - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Monto 1: DEBEN ESTAR VACÍAS.';
                break;
            case "T.4": $result_error = '<strong>Columna T - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Tipo Origen 1: DEBEN ESTAR VACÍAS.';
                break;
            case "U.5": $result_error = '<strong>Columna U - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Año/Mes 2: DEBEN ESTAR VACÍAS.';
                break;
            case "V.5": $result_error = '<strong>Columna V - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Monto 2: DEBEN ESTAR VACÍAS.';
                break;
            case "W.4": $result_error = '<strong>Columna W - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Tipo Origen 2: DEBEN ESTAR VACÍAS.';
                break;
            case "X.4": $result_error = '<strong>Columna X - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Año/Mes 3: DEBEN ESTAR VACÍAS.';
                break;
            case "Y.4": $result_error = '<strong>Columna Y - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Monto 3: DEBEN ESTAR VACÍAS.';
                break;
            case "Z.4": $result_error = '<strong>Columna Z - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Tipo Origen 3: DEBEN ESTAR VACÍAS.';
                break;
            case "AA.2": $result_error = '<strong>Columna AA - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Condición de Inscripción ante AFIP: DEBEN ESTAR VACÍAS.';
                break;
            case "AB.3": $result_error = '<strong>Columna AB - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>Cantidad de Empleados: DEBEN ESTAR VACÍAS.';
                break;
            case "S.3": $result_error = '<strong>Columna S - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>El Promedio de los Tres años debe ser menor al límite establecido por la normativa vigente (Resolución SEPyMEyDR Nº 24/2001, modificatorias y complementarias) para cada sector de actividad.';
                break;
            case "C-AB": $result_error = '<strong>Columna ' . $code . ' - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>La columna/s (C, D, E, F, G, H, I, J, K, L, M, N, O, P, Q, R, S, T, U, V, W, X, Y, Z, AA, AB) debe/n estar vacia.';
                break;
            case "Q-AB": $result_error = '<strong>Columna ' . $code . ' - Fila Nro.' . $row . ' - Código Validación ' . $code . '</strong><br/>La columna/s (Q, R, S, T, U, V, W, X, Y, Z, AB) debe/n estar vacia.';
                break;
        }

        return $result_error;
    }

}
