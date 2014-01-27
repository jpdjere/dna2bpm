<?php

class Lib_124_header {  

    public function __construct($parameter) {
        /* Vars */
        $this->headerArr  = array("NRO_GARANTIA","FECHA_REAFIANZA","SALDO_VIGENTE","REAFIANZADO","RAZON_SOCIAL","CUIT");                       
        $this->result = array_diff_assoc($this->headerArr, array_change_key_case($parameter, CASE_UPPER));
        return $this->result;
    }
}
