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
             * SALDO_PROMEDIO_GARANTIAS_VIGENTES	
             * SALDO_PROMEDIO_PONDERADO_GARANTIAS_VIGENTES_80_HASTA_FEB_2010	
             * SALDO_PROMEDIO_PONDERADO_GARANTIAS_VIGENTES_120_HASTA_FEB_2010	
             * SALDO_PROMEDIO_PONDERADO_GARANTIAS_VIGENTES_80_DESDE_FEB_2010	
             * SALDO_PROMEDIO_PONDERADO_GARANTIAS_VIGENTES_120_DESDE_FEB_2010	
             * SALDO_PROMEDIO_PONDERADO_GARANTIAS_VIGENTES_80_DESDE_ENE_2011	
             * SALDO_PROMEDIO_PONDERADO_GARANTIAS_VIGENTES_120_DESDE_ENE_2011	
             * SALDO_PROMEDIO_FDR_TOTAL_COMPUTABLE	
             * SALDO_PROMEDIO_FDR_CONTINGENTE
             * */
            for ($i = 0; $i <= count($parameterArr); $i++) {

                /* PROMEDIO_SALDO_MENSUAL
                 * Nro A.1
                 * Detail:
                 * Debe contener alguno de los siguientes parámetros:
                  ENERO
                  FEBRERO
                  MARZO
                  ABRIL
                  MAYO
                  JUNIO
                  JULIO
                  AGOSTO
                  SEPTIEMBRE
                  OCTUBRE
                  NOVIEMBRE
                  DICIEMBRE
                 */
                if ($parameterArr[$i]['col'] == 1) {
                    $code_error = "A.1";
                    //empty field Validation
                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result["error_code"] = $code_error;
                        $result["error_row"] = $parameterArr[$i]['row'];
                        $result["error_input_value"] = "empty";
                        array_push($stack, $result);
                    }
                    //Value Validation
                    if (isset($parameterArr[$i]['fieldValue'])) {
                        $B_field_value = "";
                        $allow_words = array("ENERO",
                            "FEBRERO",
                            "MARZO",
                            "ABRIL",
                            "MAYO",
                            "JUNIO",
                            "JULIO",
                            "AGOSTO",
                            "SEPTIEMBRE",
                            "OCTUBRE",
                            "NOVIEMBRE",
                            "DICIEMBRE");
                        $return = check_word($parameterArr[$i]['fieldValue'], $allow_words);
                        if ($return) {
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                            array_push($stack, $result);
                        }
                    }
                }

                /* DESCRIPCION
                 * Nro BJ.1
                 * Detail:
                 * Debe contener formato numérico sin decimales.
                 */
                
                $range = range(2, 10);
                if (in_array($parameterArr[$i]['col'], $range)) {
                    $code_error = "BJ.1";
                    //empty field Validation                    
                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result["error_code"] = $code_error;
                        $result["error_row"] = $parameterArr[$i]['row'];
                        $result["error_input_value"] = "empty";
                        array_push($stack, $result);
                    }
                }                
            } // END FOR LOOP->
        }
        $this->data = $stack;
    }

}
