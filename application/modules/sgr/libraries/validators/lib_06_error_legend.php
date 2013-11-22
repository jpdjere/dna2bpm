<?php

class Lib_06_error_legend {

    public function __construct() {
        $this->result = $this->return_legend($code, $row, $value);
    }

    function return_legend($code, $row, $value) {

        switch ($code) {
            case "A.1":
                $result_error = '(Fila Nro. '. $row .')A:Tipo de Operación:El campo no puede estar vacío y debe contener uno de los siguientes parámetros:INCORPORACION, INCREMENTO TENENCIA ACCIONARIA, DISMINUCION DE CAPITAL SOCIAL';
                break;

            case "B.1":
                $result_error = '(Fila Nro. '. $row .')B:Tipo de Socio:El campo no puede estar vacío y debe contener uno de los siguientes parámetros:A,B';
                break;

            case "AC.1":
                $result_error = '(Fila Nro. '. $row .')AC:Tipo de Acta:El campo no puede estar vacío y debe contener uno de los siguientes parámetros:AGE – Acta de Asamblea General Extraordinaria,AGO – Acta de Asamblea General Ordinaria,ACA – Acta de Consejo de Administración,EC – Estatuto Constitutivo';
                break;

            case "AD.1":
                $result_error = '(Fila Nro. '. $row .')AD:Fecha de Acta:El campo no puede estar vacío y debe contener cinco dígitos numéricos.';
                break;

            case "AE.1":
                $result_error = '(Fila Nro. '. $row .')AE:Número de Acta:OPCIONAL. De ser completado, deben ser datos numéricos.';
                break;

            case "AE.1":
                $result_error = '(Fila Nro. '. $row .')AF:Fecha de Transacción:El campo no puede estar vacío y debe contener cinco dígitos numéricos. La fecha debe estar dentro del período informado.';
                break;

            case "AG.1":
                $result_error = '(Fila Nro. '. $row .')AG:Modalidad de Adquisición/Venta de Acciones:El campo no puede estar vacío y debe contener uno de los siguientes parámetros:SUSCRIPCION,TRANSFERENCIA';
                break;

            case "AG.2":
                $result_error = '(Fila Nro. '. $row .')AG:Modalidad de Adquisición/Venta de Acciones:En caso de que en la Columna A se complete la opción “DISMINUSION DE CAPITAL SOCIAL”, solo puede contener la opción “SUSCRIPCION”';
                break;

            case "AH.1":
                $result_error = '(Fila Nro. '. $row .')AH:Capital Suscripto:El campo no puede estar vacío y debe contener dígitos numéricos.';
                break;
            case "AI.1":
                $result_error = '(Fila Nro. '. $row .')AI:Acciones Suscriptas:El campo no puede estar vacío y debe contener dígitos numéricos.';
                break;

            case "AJ.1":
                $result_error = '(Fila Nro. '. $row .')AJ:Capital Integrado:El campo no puede estar vacío y debe contener dígitos numéricos.';
                break;

            case "AK.1":
                $result_error = '(Fila Nro. '. $row .')AK:Acciones Integradas:El campo no puede estar vacío y debe contener dígitos numéricos.';
                break;

            case "AH.2":
                $result_error = '(Fila Nro. '. $row .')AH:Capital y Acciones Suscriptas e Integradas:En caso de que en la columna AG se completa la opción TRANSFERENCIA, se debe chequear en los movimientos históricos que el Socio Cedente (Columnas AL y AM)tenga los saldos a ser transferidos y que corresponden al tipo de Acción que corresponda, “A” o “B”. De no tenerlos, se debe rechazar la importación.';
                break;

            case "AI.2":
                $result_error = '(Fila Nro. '. $row .')AI:Capital y Acciones Suscriptas e Integradas:En caso de que en la columna AG se completa la opción TRANSFERENCIA, se debe chequear en los movimientos históricos que el Socio Cedente (Columnas AL y AM)tenga los saldos a ser transferidos y que corresponden al tipo de Acción que corresponda, “A” o “B”. De no tenerlos, se debe rechazar la importación.';
                break;

            case "AJ.2":
                $result_error = '(Fila Nro. '. $row .')AJ:Capital y Acciones Suscriptas e Integradas:En caso de que en la columna AG se completa la opción TRANSFERENCIA, se debe chequear en los movimientos históricos que el Socio Cedente (Columnas AL y AM)tenga los saldos a ser transferidos y que corresponden al tipo de Acción que corresponda, “A” o “B”. De no tenerlos, se debe rechazar la importación.';
                break;

            case "AK.2":
                $result_error = '(Fila Nro. '. $row .')AK:Capital y Acciones Suscriptas e Integradas:En caso de que en la columna AG se completa la opción TRANSFERENCIA, se debe chequear en los movimientos históricos que el Socio Cedente (Columnas AL y AM)tenga los saldos a ser transferidos y que corresponden al tipo de Acción que corresponda, “A” o “B”. De no tenerlos, se debe rechazar la importación.';
                break;

            case "AL.1":
                $result_error = '(Fila Nro. '. $row .')AL:CUIT del Cedente:En caso de que en la columna “A” se completen las opciones “INCORPORACIÓN” o “INCREMENTO DE TENENCIA ACCIONARIA” y en la columna AG se complete la modalidad SUSCRIPCION, estas dos columnas deben estar vacías.';
                break;

            case "AL.2":
                $result_error = '(Fila Nro. '. $row .')AL:CUIT del Cedente:En caso de que en la columna “A” se complete la opción “DISMINUSIÓN DE CAPITAL SOCIAL”, estas dos columnas NO PUEDEN ESTAR VACÍAS.';
                break;

            case "AL.3":$result_error = '(Fila Nro. '. $row .')AL:CUIT del Cedente:En caso de que en la columna AG se complete la modalidad TRANSFERENCIA, estas dos columnas NO PUEDEN ESTAR VACÍAS.';
                break;

            case "AM.1":
                $result_error = '(Fila Nro. '. $row .')AM:Carácter de la Cesión:En caso de que en la columna “A” se completen las opciones “INCORPORACIÓN” o “INCREMENTO DE TENENCIA ACCIONARIA” y en la columna AG se complete la modalidad SUSCRIPCION, estas dos columnas deben estar vacías.';
                break;

            case "AM.2":
                $result_error = '(Fila Nro. '. $row .')AM:Carácter de la Cesión:En caso de que en la columna “A” se complete la opción “DISMINUSIÓN DE CAPITAL SOCIAL”, estas dos columnas NO PUEDEN ESTAR VACÍAS.';
                break;

            case "AM.3":
                $result_error = '(Fila Nro. '. $row .')AM:Carácter de la Cesión:En caso de que en la columna AG se complete la modalidad TRANSFERENCIA, estas dos columnas NO PUEDEN ESTAR VACÍAS.';
                break;

            case "AM.4":
                $result_error = '(Fila Nro. '. $row .')AM:Carácter de la Cesión:De ser completada, debe contener uno de los siguientes parámetros:DISMINUCION DE TENENCIA ACCIONARIA,DESVINCULACIÓN';
                break;

            case "B.2":
                $result_error = '(Fila Nro. '. $row .')B:Tipo de Socio:Debe verificar EN TODAS LAS SGR que el Socio que se Incorpora (Columnas C y D)no posea saldos positivos de acciones del carácter contrario al tipo de socio que se está incorporando (Si se incorpora como Partícipe que no sea Protector en ninguna SGR y viceversa).';
                break;

            case "C.1":
                $result_error = '(Fila Nro. '. $row .')C:Cuit:El campo no puede estar vacío y  debe tener 11 caracteres sin guiones. El CUIT debe cumplir el “ALGORITMO VERIFICADOR”.';
                break;

            case "D.1":
                $result_error = '(Fila Nro. '. $row .')D:Nombre o Razón Social:El campo no puede estar vacío. En caso de que el CUIT informado ya está registrado en la Base de Datos del Sistema, este tomará en cuenta el nombre allí registrado. En caso contrario, se mantendrá provisoriamente el nombre informado por la SGR.';
                break;

            case "E.1":
                $result_error = '(Fila Nro. '. $row .')E:Provincia:El campo no puede estar vacío y debe contener sólo caracteres alfabéticos.';
                break;

            case "F.1":
                $result_error = '(Fila Nro. '. $row .')F:Partido/Municipio/Comuna:El campo no puede estar vacío.';
                break;

            case "G.1":
                $result_error = '(Fila Nro. '. $row .')G:Localidad:El campo no puede estar vacío.';
                break;

            case "H.1":
                $result_error = '(Fila Nro. '. $row .')H:Código Postal:El campo no puede estar vacío. Debe contener 8 dígitos. El primero y los tres últimos alfabéticos, el segundo, tercero, cuarto y quinto numéricos.';
                break;

            case "I.1":
                $result_error = '(Fila Nro. '. $row .')I:Calle:El campo no puede estar vacío.';
                break;

            case "J.1":
                $result_error = '(Fila Nro. '. $row .')J:Número:El campo no puede estar vacío.';
                break;

            case "M.1":$result_error = '(Fila Nro. '. $row .')M:Código de Área:El campo no puede estar vacío. Debe tener entre 2 y 4 dígitos (sin el cero adelante)..';
                break;
            case "N.1":$result_error = '(Fila Nro. '. $row .')N:Teléfono:El campo no puede estar vacío. Debe tener entre 6 y 10 dígitos..';
                break;
            case "O.1":$result_error = '(Fila Nro. '. $row .')O:Email:OPCIONA. De completarse, que tenga formato de dirección de correo electrónico..';
                break;
            case "P.1":$result_error = '(Fila Nro. '. $row .')P:WEB:OPCIONA. De completarse, que tenga formato de dirección de página web..';
                break;
            case "Q.1":$result_error = '(Fila Nro. '. $row .')Q:Código de Actividad:El campo no puede estar vacío. Debe validar que sea alguno de los que figuran en el Archivo Excel que va aparte (se llaman código CIU que es igual a la CLANAE pero con un digito menos).';
                break;
            case "AA.1":$result_error = '(Fila Nro. '. $row .')AA:Condición de Inscripción ante AFIP:"El campo no puede estar vacío y debe contener uno de los siguientes parámetros:
,EXCENTO 
,INSCRIPTO 
,MONOSTRIBUTISTA ".';
                break;
            case "AH.3, AI.3, AJ.3, AK.3":$result_error = '(Fila Nro. '. $row .')AH, AI, AJ, AK:Capital y Acciones Suscriptas e Integradas:Se debe chequear en los movimientos históricos que el Socio Incorporado no tenga saldos positivos en ninguna de estas variables en la SGR en la que se está incorporando. De tenerlos, sean clase “A” o “B” debe rechazar la importación..';
                break;
            case "Q.2":$result_error = '(Fila Nro. '. $row .')Q:Código de Actividad:"Debe validar que coincida con alguno de los Códigos del CIU (Ver listado Excel enviado a tales efectos)
Existen unos tipos de actividad (que están pintados de rojo en el Excel) que por normativa deberían comunicarse con nosotros antes de poder subirlo. Con lo cual, el validador, si descubriera un socio que se quiere incorporar con uno de los códigos pintados en rojo no debería dejar cargarlo y el mensaje a tirarles seria: ""El tipo de actividad del socio (tal) requiere ser chequeado con la DSyCSGR. Por favor comuníquese con ellos a tales fines. Gracias."" (¿¿¿¿¿????) Esto estaba escrito. Que significa???????????????????".';
                break;
            case "R.1":$result_error = '(Fila Nro. '. $row .')R:Año/Mes 1:OPCIONAL. Si las COLUMNAS S o T tienen datos, esta debe tener datos..';
                break;
            case "R.2":$result_error = '(Fila Nro. '. $row .')R::De ser informado debe tener el siguiente formato: xxxx/xx, correspondientes al formato AÑO/MES; que los dígitos del mes estén entre 01 y 12..';
                break;
            case "R.3":$result_error = '(Fila Nro. '. $row .')R::De ser informado, el año debe ser no más de dos o tres años anterior al del período de incorporación que se está informando..';
                break;
            case "S.1":$result_error = '(Fila Nro. '. $row .')S:Monto 1:OPCIONAL. Si las COLUMNAS R o T tienen datos, esta debe tener datos..';
                break;
            case "S.2":$result_error = '(Fila Nro. '. $row .')::De ser completado, debe contener caracteres numéricos..';
                break;
            case "T.1":$result_error = '(Fila Nro. '. $row .')T:Tipo Origen 1:OPCIOINAL. Si las COLUMNAS R o S tienen datos, esta debe tener datos..';
                break;
            case "T.2":$result_error = '(Fila Nro. '. $row .')T::"De ser completado, debe contener uno de los siguientes parámetros:
,BALANCES 
,CERTIFICACION DE INGRESOS 
,DDJJ IMPUESTOS ".';
                break;
            case "U.1":$result_error = '(Fila Nro. '. $row .')U:Año/Mes 2:OPCIONAL. Si las COLUMNAS R, V o W tienen datos, esta debe tener datos..';
                break;
            case "U.2":$result_error = '(Fila Nro. '. $row .')U::De ser informado debe tener el siguiente formato: xxxx/xx, correspondientes al formato AÑO/MES; que los dígitos del mes estén entre 01 y 12..';
                break;
            case "U.3":$result_error = '(Fila Nro. '. $row .')U::Si la COLUMNA R tiene datos, el año aquí informado debe ser el inmediato posterior a aquel. De lo contrario, sólo puede ser uno a dos años anterior al del período de incorporación que se está informando..';
                break;
            case "V.1":$result_error = '(Fila Nro. '. $row .')V:Monto 2:OPCIONAL. Si las COLUMNAS U o W tiene datos, esta debe tener datos..';
                break;
            case "V.2":$result_error = '(Fila Nro. '. $row .')V:Monto 2:De ser completado, debe contener caracteres numéricos..';
                break;
            case "W.1":$result_error = '(Fila Nro. '. $row .')W:Tipo Origen 2:OPCIONAL. Si las COLUMNAS U o V tiene datos, esta debe tener datos..';
                break;
            case "W.2":$result_error = '(Fila Nro. '. $row .')W:Tipo Origen 2:"De ser completado, debe contener uno de los siguientes parámetros:
,BALANCES 
,CERTIFICACION DE INGRESOS 
,DDJJ IMPUESTOS ".';
                break;
            case "X.1":$result_error = '(Fila Nro. '. $row .')X:Año/Mes 3:El campo no puede estar vacío. De ser informado debe tener el siguiente formato: xxxx/xx, correspondientes al formato AÑO/MES; que los dígitos del mes estén entre 01 y 12..';
                break;
            case "X.2":$result_error = '(Fila Nro. '. $row .')X::Si la COLUMNA U tiene datos, el año aquí informado debe ser el inmediato posterior a aquel. De lo contrario, sólo puede ser igual al del período que se está informando o un año anterior..';
                break;
            case "Y.1":$result_error = '(Fila Nro. '. $row .')Y:Monto 3:El campo no puede estar vacío y debe contener caracteres numéricos..';
                break;
            case "Z.1":$result_error = '(Fila Nro. '. $row .')Z:Tipo Origen 3:"El campo no puede estar vacío y debe contener uno de los siguientes parámetros:
,BALANCES 
,CERTIFICACION DE INGRESOS 
,DDJJ IMPUESTOS 
,ESTIMACION ".';
                break;
            case "Z.2":$result_error = '(Fila Nro. '. $row .')Z::Si se completó ESTIMACIÓN, el año informado en la COLUMNA X debe corresponder con el del período de incorporación que se está informando, y las COLUMNAS R a W deben estar vacías..';
                break;
            case "S.3, V.3, Y.2":$result_error = '(Fila Nro. '. $row .')S, V, Y:Promedio Montos Facturados:El Promedio de los Tres años debe ser menor al límite establecido por la normativa vigente (Resolución SEPyMEyDR Nº 24/2001, modificatorias y complementarias) para cada sector de actividad. Estos parámetros cambian a lo largo del tiempo, por lo que las validaciones, a su vez, deben respetar los límites correspondientes a cada período informado (Para los casos en que se rectifiquen períodos viejos)..';
                break;
            case "AB.1":$result_error = '(Fila Nro. '. $row .')AB:Cantidad de Empleados:El campo no puede estar vacío y debe contener caracteres numéricos mayores a Cero..';
                break;
            case "Q.3":$result_error = '(Fila Nro. '. $row .')Q:Código de Actividad:TODAS DEBEN ESTAR VACÍAS.';
                break;
            case "R.4":$result_error = '(Fila Nro. '. $row .')R:Año/Mes 1:TODAS DEBEN ESTAR VACÍAS.';
                break;
            case "S.4":$result_error = '(Fila Nro. '. $row .')S:Monto 1:TODAS DEBEN ESTAR VACÍAS.';
                break;
            case "T.3":$result_error = '(Fila Nro. '. $row .')T:Tipo Origen 1:TODAS DEBEN ESTAR VACÍAS.';
                break;
            case "U.4":$result_error = '(Fila Nro. '. $row .')U:Año/Mes 2:TODAS DEBEN ESTAR VACÍAS.';
                break;
            case "V.4":$result_error = '(Fila Nro. '. $row .')V:Monto 2:TODAS DEBEN ESTAR VACÍAS.';
                break;
            case "W.3":$result_error = '(Fila Nro. '. $row .')W:Tipo Origen 2:TODAS DEBEN ESTAR VACÍAS.';
                break;
            case "X.3":$result_error = '(Fila Nro. '. $row .')X:Año/Mes 3:TODAS DEBEN ESTAR VACÍAS.';
                break;
            case "Y.3":$result_error = '(Fila Nro. '. $row .')Y:Monto 3:TODAS DEBEN ESTAR VACÍAS.';
                break;
            case "Z.3":$result_error = '(Fila Nro. '. $row .')Z:Tipo Origen 3:TODAS DEBEN ESTAR VACÍAS.';
                break;
            case "AB.2":$result_error = '(Fila Nro. '. $row .')AB:Cantidad de Empleados:TODAS DEBEN ESTAR VACÍAS.';
                break;
            case "B.3":$result_error = '(Fila Nro. '. $row .')B:Tipo de Socio:Debe verificar que el Tipo de Socio se correspondan con el carácter que el Socio tiene en ese momento en la SGR. Si es Partícipe, puede incrementar la tenencia de Acciones Clase A, y si es protector sólo de Acciones Clase B..';
                break;
            case "C.2":$result_error = '(Fila Nro. '. $row .')C:Cuit:El campo no puede estar vacío y  debe tener 11 caracteres sin guiones..';
                break;
            case "D.3":$result_error = '(Fila Nro. '. $row .')D:Nombre o Razón Social:El campo no puede estar vacío. En caso de que el CUIT informado ya está registrado en la Base de Datos del Sistema, este tomará en cuenta el nombre allí registrado. En caso contrario, se mantendrá provisoriamente el nombre informado por la SGR..';
                break;
            case "E.2":$result_error = '(Fila Nro. '. $row .')E:Provincia:DEBEN ESTAR VACÍAS.';
                break;
            case "F.2":$result_error = '(Fila Nro. '. $row .')F:Partido/Municipio/Comuna:DEBEN ESTAR VACÍAS.';
                break;
            case "G.2":$result_error = '(Fila Nro. '. $row .')G:Localidad:DEBEN ESTAR VACÍAS.';
                break;
            case "H.2":$result_error = '(Fila Nro. '. $row .')H:Código Postal:DEBEN ESTAR VACÍAS.';
                break;
            case "I.2":$result_error = '(Fila Nro. '. $row .')I:Calle:DEBEN ESTAR VACÍAS.';
                break;
            case "J.2":$result_error = '(Fila Nro. '. $row .')J:Número:DEBEN ESTAR VACÍAS.';
                break;
            case "K.2":$result_error = '(Fila Nro. '. $row .')K:Piso:DEBEN ESTAR VACÍAS.';
                break;
            case "L.2":$result_error = '(Fila Nro. '. $row .')L:Dpto. / Oficina:DEBEN ESTAR VACÍAS.';
                break;
            case "M.2":$result_error = '(Fila Nro. '. $row .')M:Código de Área:DEBEN ESTAR VACÍAS.';
                break;
            case "M.2":$result_error = '(Fila Nro. '. $row .')N:Teléfono:DEBEN ESTAR VACÍAS.';
                break;
            case "O.2":$result_error = '(Fila Nro. '. $row .')O:Email:DEBEN ESTAR VACÍAS.';
                break;
            case "P.2":$result_error = '(Fila Nro. '. $row .')P:WEB:DEBEN ESTAR VACÍAS.';
                break;
            case "Q.4":$result_error = '(Fila Nro. '. $row .')Q:Código de Actividad:DEBEN ESTAR VACÍAS.';
                break;
            case "R.5":$result_error = '(Fila Nro. '. $row .')R:Año/Mes 1:DEBEN ESTAR VACÍAS.';
                break;
            case "S.5":$result_error = '(Fila Nro. '. $row .')S:Monto 1:DEBEN ESTAR VACÍAS.';
                break;
            case "T.4":$result_error = '(Fila Nro. '. $row .')T:Tipo Origen 1:DEBEN ESTAR VACÍAS.';
                break;
            case "U.5":$result_error = '(Fila Nro. '. $row .')U:Año/Mes 2:DEBEN ESTAR VACÍAS.';
                break;
            case "V.5":$result_error = '(Fila Nro. '. $row .')V:Monto 2:DEBEN ESTAR VACÍAS.';
                break;
            case "W.4":$result_error = '(Fila Nro. '. $row .')W:Tipo Origen 2:DEBEN ESTAR VACÍAS.';
                break;
            case "X.4":$result_error = '(Fila Nro. '. $row .')X:Año/Mes 3:DEBEN ESTAR VACÍAS.';
                break;
            case "Y.4":$result_error = '(Fila Nro. '. $row .')Y:Monto 3:DEBEN ESTAR VACÍAS.';
                break;
            case "Z.4":$result_error = '(Fila Nro. '. $row .')Z:Tipo Origen 3:DEBEN ESTAR VACÍAS.';
                break;
            case "AA.2":$result_error = '(Fila Nro. '. $row .')AA:Condición de Inscripción ante AFIP:DEBEN ESTAR VACÍAS.';
                break;
            case "AB.3":$result_error = '(Fila Nro. '. $row .')AB:Cantidad de Empleados:DEBEN ESTAR VACÍAS.';
                break;


            case "":
                $result_error = '(Fila Nro. '. $row .')';
                break;
            case "":
                $result_error = '(Fila Nro. '. $row .')';
                break;

            case "":
                $result_error = '(Fila Nro. '. $row .')';
                break;
            case "":
                $result_error = '(Fila Nro. '. $row .')';
                break;

            case "":
                $result_error = '(Fila Nro. '. $row .')';
                break;
            case "":
                $result_error = '(Fila Nro. '. $row .')';
                break;

            case "":
                $result_error = '(Fila Nro. '. $row .')';
                break;
        }

        return $result_error;
    }

}
