<?php

class Lib_141_header {  

    public function __construct($parameter) {
        /* Vars */
        $this->headerArr  = array("CUIT_PARTICIPE","CANT_GTIAS_VIGENTES","HIPOTECARIAS","PRENDARIAS","FIANZA","OTRAS","REAFIANZA","MORA_EN_DIAS","CLASIFICACION_DEUDOR");                       
        $xlsheader=array_change_key_case($parameter, CASE_UPPER);
        $this->result = array_diff_assoc( $this->headerArr, $xlsheader);
        foreach($this->result as $k=>$v){
            $ingresado=(empty($xlsheader[$k]))?('Campo vacio'):($xlsheader[$k]);
            $this->result[$k]="Valor correcto: $v -> Valor ingresado: [$ingresado]";
        }
        return $this->result;
    }
}
