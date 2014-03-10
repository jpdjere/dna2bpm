<?php

class Lib_13_header {  

    public function __construct($parameter) {
        /* Vars */
        $this->headerArr  = array("TIPO_DE_GARANTIA","MENOR_90_DIAS","MENOR_180_DIAS","MENOR_365_DIAS","MAYOR_365_DIAS","VALOR_CONTRAGARANTIAS");                       
        $this->result = array_diff_assoc($this->headerArr, array_change_key_case($parameter, CASE_UPPER));
        return $this->result;
    }
}
