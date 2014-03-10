<?php

class Lib_202_header {

    public function __construct($parameter) {
        /* Vars */
        $this->headerArr = array(
            "NUMERO_DE_APORTE",
            "CONTINGENTE_PROPORCIONAL_ASIGNADO",
            "DEUDA_PROPORCIONAL_ASIGNADA",
            "RENDIMIENTO_ASIGNADO"
        );
        $this->result = array_diff_assoc($this->headerArr, array_change_key_case($parameter, CASE_UPPER));
        return $this->result;
    }

}
