<?php

class Lib_06_header {  

    public function __construct($parameter) {
        /* Vars */
        $this->headerArr  = array("TIPO_OPERACION","TIPO_SOCIO","CUIT","NOMBRE","PROVINCIA","PARTIDO_MUNICIPIO_COMUNA","LOCALIDAD","CODIGO_POSTAL","CALLE","NRO","PISO","DTO_OFICINA","CODIGO_AREA","TELEFONO","EMAIL","WEB","CODIGO_ACTIVIDAD_AFIP","ANIO_MES1","MONTO","TIPO_ORIGEN","ANIO_MES2","MONTO2","TIPO_ORIGEN2","ANIO_MES3","MONTO3","TIPO ORIGEN3","CONDICION_INSCRIPCION_AFIP","CANTIDAD_DE_EMPLEADOS","TIPO_ACTA","FECHA_ACTA","ACTA_NRO","FECHA_DE_TRANSACCION","MODALIDAD","CAPITAL_SUSCRIPTO","CAPITAL_INTEGRADO","CEDENTE_CUIT");                       
        $xlsheader=array_change_key_case($parameter, CASE_UPPER);
        $this->result = array_diff_assoc( $this->headerArr, $xlsheader);
        foreach($this->result as $k=>$v){
            $ingresado=(empty($xlsheader[$k]))?('Campo vacio'):($xlsheader[$k]);
            $this->result[$k]="Valor correcto: $v -> Valor ingresado: [$ingresado]";
        }
        return $this->result;
    }
}
