<?php

class Lib_125_header {  

    public function __construct($parameter) {
        /* Vars */
        $this->headerArr  = array("CUIT_PART","CUIT_ACREEDOR","SLDO_FINANC","SLDO_COMER","SLDO_TEC");                       
        $xlsheader=array_change_key_case($parameter, CASE_UPPER);
        $this->result = array_diff_assoc( $this->headerArr, $xlsheader);
        foreach($this->result as $k=>$v){
            $this->result[$k]="Valor correcto: $v -> Valor ingresado: ".$xlsheader[$k];
        }
        return $this->result;
    }
}
