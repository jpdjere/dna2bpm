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
        $xlsheader=array_change_key_case($parameter, CASE_UPPER);
        $this->result = array_diff_assoc( $this->headerArr, $xlsheader);
        foreach($this->result as $k=>$v){
            $ingresado=(empty($xlsheader[$k]))?('Campo vacio'):($xlsheader[$k]);
            $this->result[$k]="Valor correcto: $v -> Valor ingresado: [$ingresado]";
        }
        return $this->result;
    }

}
