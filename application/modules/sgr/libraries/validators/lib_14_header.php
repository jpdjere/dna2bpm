<?php

class Lib_14_header {  

    public function __construct($parameter) {
        /* Vars */
        $this->headerArr  = array("FECHA_MOVIMIENTO","NRO_GARANTIA","CAIDA","RECUPERO","INCOBRABLES_PERIODO","GASTOS_EFECTUADOS_PERIODO","RECUPERO_GASTOS_PERIODO","GASTOS_INCOBRABLES_PERIODO");                       
        $xlsheader=array_change_key_case($parameter, CASE_UPPER);
        $this->result = array_diff_assoc( $this->headerArr, $xlsheader);
        foreach($this->result as $k=>$v){
            $ingresado=(empty($xlsheader[$k]))?('Campo vacio'):($xlsheader[$k]);
            $this->result[$k]="Valor correcto: $v -> Valor ingresado: [$ingresado]";
        }
        return $this->result;
    }
}
