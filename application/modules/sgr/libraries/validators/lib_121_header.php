<?php

class Lib_121_header {  

    public function __construct($parameter) {
        /* Vars */
        $this->headerArr  = array("NRO_ORDEN","NRO_CUOTA","VENCIMIENTO","CUOTA_GTA_PESOS","CUOTA_MENOR_PESOS");                       
        $xlsheader=array_change_key_case($parameter, CASE_UPPER);
        $this->result = array_diff_assoc( $this->headerArr, $xlsheader);
        foreach($this->result as $k=>$v){
            $ingresado=(empty($xlsheader[$k]))?('Campo vacio'):($xlsheader[$k]);
            $this->result[$k]="Valor correcto: $v -> Valor ingresado: [$ingresado]";
        }
        return $this->result;
    }
}
