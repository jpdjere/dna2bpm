<?php

class Lib_124_header {  

    public function __construct($parameter) {
        /* Vars */
        $this->headerArr  = array("NRO_GARANTIA","FECHA_REAFIANZA","SALDO_VIGENTE","REAFIANZADO","RAZON_SOCIAL","CUIT");                       
        $xlsheader=array_change_key_case($parameter, CASE_UPPER);
        $this->result = array_diff_assoc( $this->headerArr, $xlsheader);
        foreach($this->result as $k=>$v){
            $this->result[$k]="Valor correcto: $v -> Valor ingresado: ".$xlsheader[$k];
        }
        return $this->result;
    }
}
