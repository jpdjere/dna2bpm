<?php

class Lib_06_header {  

    public function __construct($parameter) {
        /* Vars */
        $this->headerArr  = array("TIPO_OPERACION","TIPO_SOCIO","CUIT","NOMBRE","PROVINCIA","PARTIDO_MUNICIPIO_COMUNA","LOCALIDAD","CODIGO_POSTAL","CALLE","NRO","PISO","DTO_OFICINA","TELEFONO1","TELEFONO2","EMAIL","WEB","CODIGO_ACTIVIDAD_AFIP","ANIO_MES1","MONTO","TIPO_ORIGEN","ANIO_MES2","MONTO2","TIPO_ORIGEN2","ANIO_MES3","MONTO3","TIPO ORIGEN3","REGIMEN_IVA","CANTIDAD_DE_EMPLEADOS","TIPO_ACTA","FECHA_ACTA","ACTA_NRO","FECHA_DE_TRANSACCION","MODALIDAD","CAPITAL_SUSCRIPTO","ACCIONES_SUSCRIPTAS","CAPITAL_INTEGRADO","ACCIONES_INTEGRADAS","CEDENTE_CUIT","CEDENTE_CARACTERISTICA");                       
        $this->result = array_diff_assoc($this->headerArr, array_change_key_case($parameter, CASE_UPPER));        
        
        return $this->result;
    }
}
