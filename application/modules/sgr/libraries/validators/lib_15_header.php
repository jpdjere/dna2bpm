<?php

class Lib_15_header {  

    public function __construct($parameter) {
        /* Vars */
        $this->headerArr  = array("INCISO_ART_25","DESCRIPCION","IDENTIFICACION","EMISOR","CUIT_EMISOR","ENTIDAD_DESPOSITARIA","CUIT_DEPOSITARIO","MONEDA","MONTO");                       
        $this->result = array_diff_assoc($this->headerArr, array_change_key_case($parameter, CASE_UPPER));
        return $this->result;
    }
}
