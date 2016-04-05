<?php

class Lib_122_header {  

    public function __construct($parameter) {
        /* Vars */
        $this->headerArr  = array("NRO_GARANTIA","NUMERO_CUOTA_CUYO_VENC_MODIFICA","FECHA_VENC_CUOTA","FECHA_VENC_CUOTA_NUEVA","MONTO_CUOTA","SALDO_AL_VENCIMIENTO");                       
        $xlsheader=array_change_key_case($parameter, CASE_UPPER);
        $this->result = array_diff_assoc( $this->headerArr, $xlsheader);
        foreach($this->result as $k=>$v){
            $ingresado=(empty($xlsheader[$k]))?('Campo vacio'):($xlsheader[$k]);
            $this->result[$k]="Valor correcto: $v -> Valor ingresado: [$ingresado]";
        }
        return $this->result;
    }
}
