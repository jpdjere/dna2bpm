<?php

class Lib_141_header {  

    public function __construct($parameter) {
        /* Vars */
        $this->headerArr  = array("CUIT_PARTICIPE","CANT_GTIAS_VIGENTES","HIPOTECARIAS","PRENDARIAS","FIANZA","OTRAS","REAFIANZA","MORA_EN_DIAS","CLASIFICACION_DEUDOR");                       
        $this->result = array_diff_assoc($this->headerArr, array_change_key_case($parameter, CASE_UPPER));
        return $this->result;
    }
}
