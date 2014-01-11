<?php

class Lib_061_data extends MX_Controller {
    /* VALIDADOR ANEXO 061 */

    public function __construct($parameter) {
         parent::__construct();
        $this->load->library('session');
        $fn = 'tools_helper';
        $this->load->library("helpers/" . $fn);
        $this->load->helper('sgr/tools');
        
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

            /* Validacion Basica */
            for ($i = 0; $i <= count($parameterArr); $i++) {

                /* CUIT_SOCIO_INCORPORADO
                 * Nro A.1
                 * Detail:
                 * El campo no puede estar vacío y  debe tener 11 caracteres sin guiones.
                 * Nro A.2
                 * Detail:
                 * El CUIT debe estar en el ANEXO 6 – MOVIMIENTOS DE CAPITAL SOCIAL, informado en el período correspondiente como incorporado.
                 * Nro A.3
                 * Detail:
                 * Todos los Socios que fueron informados como Incorporados en el Anexo 6 – Movimientos de Capital Social, deben figurar en esta columna.
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
                    
                    //cuit checker
                    if ($parameterArr[$i]['fieldValue'] != "") {
                            $return = cuit_checker(str_replace("-", "", $parameterArr[$i]['fieldValue']));
                            if (!$return) {
                                $result["error_code"] = $code_error;
                                $result["error_row"] = $parameterArr[$i]['row'];
                                $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                                array_push($stack, $result);
                            }
                        }
                        
                     $code_error = "A.2";
                     //Valida contra Mongo
                     
                     $code_error = "A.3";
                     //Valida contra Mongo
                }

                /* TIENE_VINCULACION
                 * Nro B.1
                 * Detail:
                 * El campo no puede estar vacío y debe contener uno de los siguientes parámetros:
                    SI
                    NO
                 * Nro B.2
                 * Detail:
                 * Si el CUIT informado en la Columna A comienza con 30 o 33 (Correspondiente a Personas Jurídicas) la opción debe ser “SI”. 
                 * Nro B.3
                 * Detail:
                 * Si se indica la opción “NO” el CUIT no puede estar más de una vez en la Columna A de este Anexo,  y las Columnas C, D, E, y F deben estar vacías.
                 */

                if ($parameterArr[$i]['col'] == 2) {

                    $code_error = "B.1";

                    //empty field Validation
                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result["error_code"] = $code_error;
                        $result["error_row"] = $parameterArr[$i]['row'];
                        $result["error_input_value"] = "empty";
                        array_push($stack, $result);
                    }
                    //Value Validation
                    if ($parameterArr[$i]['fieldValue'] != "") {
                        $B1_field_value = "";
                        $allow_words = array("SI", "NO");
                        $return = check_word($parameterArr[$i]['fieldValue'], $allow_words);
                        if ($return) {
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                            array_push($stack, $result);
                        } else {
                            $B1_field_value = $parameterArr[$i]['fieldValue'];
                        }
                    }
                    
                    $code_error = "B.2";
                    
                    
                }

            }
        }       
        $this->data = $stack;
        //return $this->data;
    }
}
