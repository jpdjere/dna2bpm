<?php

class Lib_12_header {  

    public function __construct($parameter) {
        /* Vars */
        $this->headerArr  = array("NRO","CUIT_PARTICIPE","ORIGEN","TIPO","IMPORTE","MONEDA","LIBRADOR_NOMBRE","LIBRADOR_CUIT","NRO_OPERACION_BOLSA","ACREEDOR","CUIT_ACREEDOR","IMPORTE_CRED_GARANT","MONEDA_CRED_GARANT","TASA","PUNTOS_ADIC_CRED_GARANT","PLAZO","GRACIA","PERIODICIDAD","SISTEMA","DESTINO_CREDITO");                       
        $this->result = array_diff_assoc($this->headerArr, array_change_key_case($parameter, CASE_UPPER));        
        
        return $this->result;
    }
}
