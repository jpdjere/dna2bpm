<?php

class Lib_16_data extends MX_Controller {
    /* VALIDADOR ANEXO 16 */

    public function __construct($parameter) {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('sgr/tools');
        $this->load->model('sgr/sgr_model');

        /* Vars 
         * 
         * $parameters =  
         * $parameterArr[0]['fieldValue'] 
         * $parameterArr[0]['row'] 
         * $parameterArr[0]['col']
         * $parameterArr[0]['count']
         * 
         */
        $stack = array();
        $original_array = array();
        $parameterArr = (array) $parameter;
        $result = array("error_code" => "", "error_row" => "", "error_input_value" => "");

        for ($i = 1; $i <= $parameterArr[0]['count']; $i++) {
            /**
             * BASIC VALIDATION
             * 
             * @param 
             * @type PHP
             * @name ...
             * @author Diego             
             * @example 
             * PROMEDIO_SALDO_MENSUAL	
             *  SALDO_PROMEDIO_GARANTIAS_VIGENTES
              SALDO_PROMEDIO_PONDERADO_GARANTIAS_VIGENTES_80_HASTA_FEB_2010
              SALDO_PROMEDIO_PONDERADO_GARANTIAS_VIGENTES_120_HASTA_FEB_2010
              SALDO_PROMEDIO_PONDERADO_GARANTIAS_VIGENTES_80_DESDE_FEB_2010
              SALDO_PROMEDIO_PONDERADO_GARANTIAS_VIGENTES_120_DESDE_FEB_2010
              SALDO_PROMEDIO_PONDERADO_GARANTIAS_VIGENTES_80_DESDE_ENE_2011
              SALDO_PROMEDIO_PONDERADO_GARANTIAS_VIGENTES_120_DESDE_ENE_2011
              SALDO_PROMEDIO_FDR_TOTAL_COMPUTABLE
              SALDO_PROMEDIO_FDR_CONTINGENTE
             * */
            for ($i = 0; $i <= count($parameterArr); $i++) {

                /* DESCRIPCION
                 * Nro BJ.1
                 * Detail:
                 * Debe contener formato numérico sin decimales.
                 */

                $range = range(1, 9);
                if (in_array($parameterArr[$i]['col'], $range)) {
                    $code_error = "BJ.1";
                    //empty field Validation                    
                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                        array_push($stack, $result);
                    } else {
                        $return = check_is_numeric_no_decimal($parameterArr[$i]['fieldValue'],true);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }
                    }
                }
            } // END FOR LOOP->
        }
        exit();
        $this->data = $stack;
    }

}
