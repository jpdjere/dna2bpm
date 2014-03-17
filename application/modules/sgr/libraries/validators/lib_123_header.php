<?php

class Lib_123_header {  

    public function __construct($parameter) {
        /* Vars */
        $this->headerArr  = array("NRO_ORDEN","DIA1","DIA2","DIA3","DIA4","DIA5","DIA6","DIA7","DIA8","DIA9","DIA10","DIA11","DIA12","DIA13","DIA14","DIA15","DIA16","DIA17","DIA18","DIA19","DIA20","DIA21","DIA22","DIA23","DIA24","DIA25","DIA26","DIA27","DIA28","DIA29","DIA30","DIA31");                       
        $this->result = array_diff_assoc($this->headerArr, array_change_key_case($parameter, CASE_UPPER));        
        
        return $this->result;
    }
}
