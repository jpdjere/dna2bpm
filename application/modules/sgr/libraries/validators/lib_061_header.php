<?php

class Lib_061_header {  

    public function __construct($parameter) {
        /* Vars */
        $this->headerArr  = array("CUIT_SOCIO_INCORPORADO","TIENE_VINCULACION","CUIT_VINCULADO","RAZON_SOCIAL_VINCULADO","TIPO_RELACION_VINCULACION","PORCENTAJE_ACCIONES");                       
        $this->result = array_diff_assoc($this->headerArr, array_change_key_case($parameter, CASE_UPPER));        
        
        return $this->result;
    }
}
