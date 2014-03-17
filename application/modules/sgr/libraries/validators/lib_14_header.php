<?php

class Lib_14_header {  

    public function __construct($parameter) {
        /* Vars */
        $this->headerArr  = array("FECHA_MOVIMIENTO","NRO_GARANTIA","CAIDA","RECUPERO","INCOBRABLES_PERIODO","GASTOS_EFECTUADOS_PERIODO","RECUPERO_GASTOS_PERIODO","GASTOS_INCOBRABLES_PERIODO");                       
        $this->result = array_diff_assoc($this->headerArr, array_change_key_case($parameter, CASE_UPPER));
        return $this->result;
    }
}
