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
                        } else {
                            $A1_field_value = $parameterArr[$i]['fieldValue'];
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

                    //Check Numeric Validation
                    if ($parameterArr[$i]['fieldValue'] != "") {
                        $return = $this->check_is_numeric($parameterArr[$i]['fieldValue']);
                        if ($return != NULL) {
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                            array_push($stack, $result);
                        }
                    }
                }

                /*
                 * FECHA_DE_TRANSACCION
                  OPCIONAL. De ser completado, deben ser datos numéricos.
                 */

                if ($parameterArr[$i]['col'] == 32) {

                    $code_error = "AF.1";
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
                 * MODALIDAD AG                 * 
                 * 
                 * El campo no puede estar vacío y debe contener uno de los siguientes parámetros:
                  SUSCRIPCION
                  TRANSFERENCIA
                  En caso de que en la Columna A se complete la opción “DISMINUCION DE CAPITAL SOCIAL”, solo puede contener la opción “SUSCRIPCION”                *
                 *
                 */

                if ($parameterArr[$i]['col'] == 33) {

                    $code_error = "AG.1";

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
                        $allow_words = array("SUSCRIPCION", "TRANSFERENCIA");
                        $return = $this->check_word($parameterArr[$i]['fieldValue'], $allow_words);
                        if ($return != NULL) {
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                            array_push($stack, $result);
                        } else {
                            $AG_field_value = $parameterArr[$i]['fieldValue'];
                        }


                        //CUSTOM VALIDATION AG.2
                        if ($A1_field_value == "DISMINUCION DE CAPITAL SOCIAL") {
                            $code_error = "AG.2";
                            $allow_words = array("SUSCRIPCION");
                            $return = $this->check_word($parameterArr[$i]['fieldValue'], $allow_words);
                            if ($return != NULL) {
                                $result["error_code"] = $code_error;
                                $result["error_row"] = $parameterArr[$i]['row'];
                                $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                                array_push($stack, $result);
                            }
                        }
                    }
                }


                /*
                 * CAPITAL_SUSCRIPTO	ACCIONES_SUSCRIPTAS	CAPITAL_INTEGRADO	ACCIONES_INTEGRADAS
                 * AH.1, AI.1, AJ.1, AK.1
                 * 
                 * 
                 * El campo no puede estar vacío y debe contener dígitos numéricos.                 *
                 */

                if ($parameterArr[$i]['col'] >= 34 && $parameterArr[$i]['col'] <= 38) {

                    switch ($parameterArr[$i]['col']) {
                        case 34:
                            $code_error = "AH.1";

                            //empty field Validation
                            $return = $this->check_empty($parameterArr[$i]['fieldValue']);
                            if ($return != NULL) {
                                $result["error_code"] = $code_error;
                                $result["error_row"] = $parameterArr[$i]['row'];
                                $result["error_input_value"] = "empty";
                                array_push($stack, $result);
                            }
                            //Check Numeric Validation
                            if ($parameterArr[$i]['fieldValue'] != "") {
                                $return = $this->check_is_numeric($parameterArr[$i]['fieldValue']);
                                if ($return != NULL) {
                                    $result["error_code"] = $code_error;
                                    $result["error_row"] = $parameterArr[$i]['row'];
                                    $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                                    array_push($stack, $result);
                                }
                            }

                            break;

                        case 35:
                            $code_error = "AI.1";
                            //empty field Validation
                            $return = $this->check_empty($parameterArr[$i]['fieldValue']);
                            if ($return != NULL) {
                                $result["error_code"] = $code_error;
                                $result["error_row"] = $parameterArr[$i]['row'];
                                $result["error_input_value"] = "empty";
                                array_push($stack, $result);
                            }
                            //Check Numeric Validation
                            if ($parameterArr[$i]['fieldValue'] != "") {
                                $return = $this->check_is_numeric($parameterArr[$i]['fieldValue']);
                                if ($return != NULL) {
                                    $result["error_code"] = $code_error;
                                    $result["error_row"] = $parameterArr[$i]['row'];
                                    $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                                    array_push($stack, $result);
                                }
                            }
                            break;

                        case 36:
                            $code_error = "AJ.1";
                            //empty field Validation
                            $return = $this->check_empty($parameterArr[$i]['fieldValue']);
                            if ($return != NULL) {
                                $result["error_code"] = $code_error;
                                $result["error_row"] = $parameterArr[$i]['row'];
                                $result["error_input_value"] = "empty";
                                array_push($stack, $result);
                            }
                            //Check Numeric Validation
                            if ($parameterArr[$i]['fieldValue'] != "") {
                                $return = $this->check_is_numeric($parameterArr[$i]['fieldValue']);
                                if ($return != NULL) {
                                    $result["error_code"] = $code_error;
                                    $result["error_row"] = $parameterArr[$i]['row'];
                                    $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                                    array_push($stack, $result);
                                }
                            }
                            break;

                        case 37:
                            $code_error = "AK.1";
                            //empty field Validation
                            $return = $this->check_empty($parameterArr[$i]['fieldValue']);
                            if ($return != NULL) {
                                $result["error_code"] = $code_error;
                                $result["error_row"] = $parameterArr[$i]['row'];
                                $result["error_input_value"] = "empty";
                                array_push($stack, $result);
                            }
                            //Check Numeric Validation
                            if ($parameterArr[$i]['fieldValue'] != "") {
                                $return = $this->check_is_numeric($parameterArr[$i]['fieldValue']);
                                if ($return != NULL) {
                                    $result["error_code"] = $code_error;
                                    $result["error_row"] = $parameterArr[$i]['row'];
                                    $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                                    array_push($stack, $result);
                                }
                            }
                            break;
                    }
                }


                if ($parameterArr[$i]['col'] >= 39 && $parameterArr[$i]['col'] <= 40) {
                    switch ($parameterArr[$i]['col']) {
                        case 39:
                            $code_error = "AL.1";

                            if ($AG_field_value == "SUSCRIPCION" && ($A1_field_value == "INCORPORACION" || $A1_field_value == "INCREMENTO DE TENENCIA ACCIONARIA")) {
                                //CHECK FOR EMPTY
                                $return = $this->check_for_empty($parameterArr[$i]['fieldValue']);
                                if ($return != NULL) {
                                    $result["error_code"] = $code_error;
                                    $result["error_row"] = $parameterArr[$i]['row'];
                                    $result["error_input_value"] = "not empty";
                                    array_push($stack, $result);
                                }
                            } else if ($A1_field_value == "DISMINUCION DE CAPITAL SOCIAL") {
                                //do something
                                $code_error = "AL.2";
                                $return = $this->check_empty($parameterArr[$i]['fieldValue']);
                                if ($return != NULL) {
                                    $result["error_code"] = $code_error;
                                    $result["error_row"] = $parameterArr[$i]['row'];
                                    $result["error_input_value"] = "empty";
                                    array_push($stack, $result);
                                }
                            } else if ($AG_field_value == "TRANSFERENCIA") {
                                $code_error = "AL.3";
                                $return = $this->check_empty($parameterArr[$i]['fieldValue']);
                                if ($return != NULL) {
                                    $result["error_code"] = $code_error;
                                    $result["error_row"] = $parameterArr[$i]['row'];
                                    $result["error_input_value"] = "empty";
                                    array_push($stack, $result);
                                }
                            }


                            break;

                        case 40:
                            $code_error = "AM.1";
                            break;
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

    function check_for_empty($parameter) {
        if ($parameter != NULL) {
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
