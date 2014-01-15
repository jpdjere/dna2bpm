<?php

class Lib_062_header {  

    public function __construct($parameter) {
        /* Vars
         * 
         * CUIT	ANIO_MES	FACTURACION	EMPLEADOS	TIPO_ORIGEN
         *  */
        $this->headerArr  = array("CUIT","ANIO_MES","FACTURACION","EMPLEADOS","TIPO_ORIGEN");                       
        $this->result = array_diff_assoc($this->headerArr, array_change_key_case($parameter, CASE_UPPER));        
        
        return $this->result;
    }
}
