<?php

class Lib_15_header {  

    public function __construct($parameter) {
        /* Vars */
        $this->headerArr  = array("INCISO_ART_25","DESCRIPCION","IDENTIFICACION","EMISOR","CUIT_EMISOR","ENTIDAD_DESPOSITARIA","CUIT_DEPOSITARIO","MONEDA","MONTO");                       
        $xlsheader=array_change_key_case($parameter, CASE_UPPER);
        $this->result = array_diff_assoc( $this->headerArr, $xlsheader);
        foreach($this->result as $k=>$v){
            $ingresado=(empty($xlsheader[$k]))?('Campo vacio'):($xlsheader[$k]);
            $this->result[$k]="Valor correcto: $v -> Valor ingresado: [$ingresado]";
        }
        return $this->result;
    }
}
