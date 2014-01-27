<?php

class Lib_201_header {

    public function __construct($parameter) {
        /* Vars */
        $this->headerArr = array("NUMERO_DE_APORTE",
            "FECHA_MOVIMIENTO",
            "CUIT_PROTECTOR",
            "APORTE",
            "RETIRO",
            "RETENCION_POR_CONTINGENTE",
            "RETIRO_DE_RENDIMIENTOS",
            "ESPECIE",
            "TITULAR_ORIG",
            "NRO_CTA_OR",
            "ENTIDAD_OR",
            "ENT_DEP_OR",
            "TITULAR_DEST",
            "NRO_DEST",
            "ENTIDAD_DEST",
            "ENT_DEP_DEST",
            "FECHA_ACTA",
            "NRO_ACTA");
        $this->result = array_diff_assoc($this->headerArr, array_change_key_case($parameter, CASE_UPPER));
        return $this->result;
    }

}
