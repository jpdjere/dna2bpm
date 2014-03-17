<?php

class Lib_16_header {

    public function __construct($parameter) {
        /* Vars */       
        /*								
*/
        $this->headerArr = array(
            "SALDO_PROMEDIO_PONDERADO_GARANTIAS_VIGENTES_80_HASTA_FEB_2010",
            "SALDO_PROMEDIO_PONDERADO_GARANTIAS_VIGENTES_120_HASTA_FEB_2010",
            "SALDO_PROMEDIO_PONDERADO_GARANTIAS_VIGENTES_80_DESDE_FEB_2010",
            "SALDO_PROMEDIO_PONDERADO_GARANTIAS_VIGENTES_120_DESDE_FEB_2010",
            "SALDO_PROMEDIO_PONDERADO_GARANTIAS_VIGENTES_80_DESDE_ENE_2011",
            "SALDO_PROMEDIO_PONDERADO_GARANTIAS_VIGENTES_120_DESDE_ENE_2011",
            "SALDO_PROMEDIO_FDR_TOTAL_COMPUTABLE",
            "SALDO_PROMEDIO_FDR_CONTINGENTE"
        );
        
        $this->result = array_diff_assoc($this->headerArr, array_change_key_case($parameter, CASE_UPPER));
        return $this->result;
    }

}
