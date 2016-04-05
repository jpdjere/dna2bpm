<?php

class Lib_126_header {  

    public function __construct($parameter) {
        /* Vars 	
         *          */
        $this->headerArr  = array("OTORGAMIENTO_PERIODO","OTORGAMIENTO_PERIODO_PREVIO","ADM_FDR","ASESORAMIENTO");                       
        $xlsheader=array_change_key_case($parameter, CASE_UPPER);
        $this->result = array_diff_assoc( $this->headerArr, $xlsheader);
        foreach($this->result as $k=>$v){
            $this->result[$k]="Valor correcto: $v -> Valor ingresado: ".$xlsheader[$k];
        }
        return $this->result;
    }
}
