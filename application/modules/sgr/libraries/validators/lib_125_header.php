<?php

class Lib_125_header {  

    public function __construct($parameter) {
        /* Vars */
        $this->headerArr  = array("CUIT_PART","CUIT_ACREEDOR","SLDO_FINANC","SLDO_COMER","SLDO_TEC");                       
        $this->result = array_diff_assoc($this->headerArr, array_change_key_case($parameter, CASE_UPPER));
        return $this->result;
    }
}
