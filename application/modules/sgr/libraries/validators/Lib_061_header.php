<?php

class Lib_061_header {  

    public function __construct($parameter) {
        /* Vars */
        $this->headerArr  = array("CUIT_SOCIO_INCORPORADO","TIENE_VINCULACION","CUIT_VINCULADO","RAZON_SOCIAL_VINCULADO","TIPO_RELACION_VINCULACION","PORCENTAJE_ACCIONES");                       
        $xlsheader=array_change_key_case($parameter, CASE_UPPER);
        $this->result = array_diff_assoc( $this->headerArr, $xlsheader);
        foreach($this->result as $k=>$v){
            $ingresado=(empty($xlsheader[$k]))?('Campo vacio'):($xlsheader[$k]);
            $this->result[$k]="Valor correcto: $v -> Valor ingresado: [$ingresado]";
        }
        return $this->result;
    }
}
