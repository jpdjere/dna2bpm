<?php

class Lib_12_header {  

    public function __construct($parameter) {
        /* Vars */
        $this->headerArr  = array("NRO","CUIT_PARTICIPE","ORIGEN","TIPO","IMPORTE","MONEDA","LIBRADOR_NOMBRE","LIBRADOR_CUIT","NRO_OPERACION_BOLSA","ACREEDOR","CUIT_ACREEDOR","IMPORTE_CRED_GARANT","MONEDA_CRED_GARANT","TASA","PUNTOS_ADIC_CRED_GARANT","PLAZO","GRACIA","PERIODICIDAD","SISTEMA","DESTINO_CREDITO");                       
        $xlsheader=array_change_key_case($parameter, CASE_UPPER);
        $this->result = array_diff_assoc( $this->headerArr, $xlsheader);
        foreach($this->result as $k=>$v){
            $ingresado=(empty($xlsheader[$k]))?('Campo vacio'):($xlsheader[$k]);
            $this->result[$k]="Valor correcto: $v -> Valor ingresado: [$ingresado]";
        }
        return $this->result;
    }
}
