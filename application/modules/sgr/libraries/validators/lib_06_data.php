<?php

class Lib_06_data {
    /* VALIDADOR ANEXO 06 */

    public function __construct($parameter) {
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

                /* TIPO_OPERACION
                 * Nro A.1
                 * Detail:
                 * El campo no puede estar vacío y debe contener uno de los siguientes parámetros:
                  INCORPORACION
                  INCREMENTO TENENCIA ACCIONARIA
                  DISMINUCION DE CAPITAL SOCIAL
                 */

                if ($parameterArr[$i]['col'] == 1) {

                    $code_error = "A.1";

                    //empty field Validation
                    $return = $this->check_empty($parameterArr[$i]['fieldValue']);
                    if ($return != NULL) {
                        $result["error_code"] = $code_error;
                        $result["error_row"] = $parameterArr[$i]['row'];
                        $result["error_input_value"] = "empty";
                        array_push($stack, $result);
                    }

                    //Value Validation
                    if ($parameterArr[$i]['fieldValue'] != "") {
                        $allow_words = array("INCORPORACION", "INCREMENTO TENENCIA ACCIONARIA", "DISMINUCION DE CAPITAL SOCIAL");
                        $return = $this->check_word($parameterArr[$i]['fieldValue'], $allow_words);
                        if ($return != NULL) {
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                            array_push($stack, $result);
                        }
                    }
                }

                /* TIPO_SOCIO
                 * Nro A.2
                 * Detail:
                 * El campo no puede estar vacío y debe contener uno de los siguientes parámetros:
                  A
                  B
                 */

                if ($parameterArr[$i]['col'] == 2) {

                    $code_error = "B.1";

                    //empty field Validation
                    $return = $this->check_empty($parameterArr[$i]['fieldValue']);
                    if ($return != NULL) {
                        $result["error_code"] = $code_error;
                        $result["error_row"] = $parameterArr[$i]['row'];
                        $result["error_input_value"] = "empty";
                        array_push($stack, $result);
                    }
                    //Value Validation
                    if ($parameterArr[$i]['fieldValue'] != "") {
                        $allow_words = array("A", "B");
                        $return = $this->check_word($parameterArr[$i]['fieldValue'], $allow_words);
                        if ($return != NULL) {
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                            array_push($stack, $result);
                        }
                    }
                }


                /* TIPO_ACTA
                 * El campo no puede estar vacío y debe contener uno de los siguientes parámetros:
                  AGE – Acta de Asamblea General Extraordinaria
                  AGO – Acta de Asamblea General Ordinaria
                  ACA – Acta de Consejo de Administración
                  EC – Estatuto Constitutivo
                 */

                if ($parameterArr[$i]['col'] == 29) {
                    
                    $code_error = "AC.1";
                    
                    //empty field Validation
                    $return = $this->check_empty($parameterArr[$i]['fieldValue']);
                    if ($return != NULL) {
                        $result["error_code"] = $code_error;
                        $result["error_row"] = $parameterArr[$i]['row'];
                        $result["error_input_value"] = "empty";
                        array_push($stack, $result);
                    }
                    //Value Validation
                    if ($parameterArr[$i]['fieldValue'] != "") {
                        $allow_words = array("AGE", "AGO", "ACA", "EC");
                        $return = $this->check_word($parameterArr[$i]['fieldValue'], $allow_words);
                        if ($return != NULL) {
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                            array_push($stack, $result);
                        }
                    }
                }

                /* FECHA_ACTA
                  El campo no puede estar vacío y debe contener cinco dígitos numéricos.
                 */

                if ($parameterArr[$i]['col'] == 30) {
                    
                    $code_error = "AD.1";
                    
                    //empty field Validation
                    $return = $this->check_empty($parameterArr[$i]['fieldValue']);
                    if ($return != NULL) {
                        $result["error_code"] = $code_error;
                        $result["error_row"] = $parameterArr[$i]['row'];
                        $result["error_input_value"] = "empty";
                        array_push($stack, $result);
                    }
                    //Check Date Validation
                    if ($parameterArr[$i]['fieldValue'] != "") {                        
                        $return = $this->check_date_format($parameterArr[$i]['fieldValue']);
                        if ($return != NULL) {
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                            array_push($stack, $result);
                        }
                    }
                }


                /*
                 * ACTA_NRO
                  OPCIONAL. De ser completado, deben ser datos numéricos.
                 */

                if ($parameterArr[$i]['col'] == 31) {
                    
                    $code_error = "AE.1";
                    
                    //Check Date Validation
                    if ($parameterArr[$i]['fieldValue'] != "") {                        
                       
                        $return = $this->check_is_numeric($parameterArr[$i]['fieldValue']);
                        if ($return != NULL) {
                            $result["error_code"] = $code_error; $result["error_row"] = $parameterArr[$i]['row']; $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                            array_push($stack, $result);
                        }
                    }
                }

                /////////////////////////////////////////
            }
        }


        $this->data = $stack;
        //return $this->data;
    }

    function check_empty($parameter) {
        if ($parameter == NULL) {
            return "error" . $parameter;
        }
    }

    function check_word($parameter, $allow_words) {
        if (!in_array(strtoupper($parameter), $allow_words)) {
            return "error" . $parameter;
        }
    }

    function check_date_format($parameter) {
        $num_length = strlen((string) $parameter);
        if ($num_length != 5) {
            return "error" . $parameter;
        }
    }

    function check_is_numeric($parameter) {        
        if (!is_numeric($parameter)) {
            return "error" . $parameter;
        }
    }

}
