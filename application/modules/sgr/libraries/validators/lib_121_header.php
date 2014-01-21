<?php

class Lib_121_header {  

    public function __construct($parameter) {
        /* Vars */
        $this->headerArr  = array("NRO_ORDEN","NRO_CUOTA","VENCIMIENTO","CUOTA_GTA_PESOS","CUOTA_MENOR_PESOS");                       
        $this->result = array_diff_assoc($this->headerArr, array_change_key_case($parameter, CASE_UPPER));        
        
        return $this->result;
    }
}
