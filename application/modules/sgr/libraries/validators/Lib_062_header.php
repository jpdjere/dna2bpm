<?php

class Lib_062_header {  

    public function __construct($parameter) {
        /* Vars
         * 
         * CUIT	ANIO_MES	FACTURACION	EMPLEADOS	TIPO_ORIGEN
         *  */
        $this->headerArr  = array("CUIT","ANIO_MES","FACTURACION","EMPLEADOS","TIPO_ORIGEN");                       
        $xlsheader=array_change_key_case($parameter, CASE_UPPER);
        $this->result = array_diff_assoc( $this->headerArr, $xlsheader);
        foreach($this->result as $k=>$v){
            $ingresado=(empty($xlsheader[$k]))?('Campo vacio'):($xlsheader[$k]);
            $this->result[$k]="Valor correcto: $v -> Valor ingresado: [$ingresado]";
        }
        return $this->result;
    }
}
