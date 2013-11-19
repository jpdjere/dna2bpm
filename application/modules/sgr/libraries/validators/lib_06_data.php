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
                    if ($return) {
                        $result["error_code"] = $code_error;
                        $result["error_row"] = $parameterArr[$i]['row'];
                        $result["error_input_value"] = "empty";
                        array_push($stack, $result);
                    }

                    //Value Validation
                    if ($parameterArr[$i]['fieldValue'] != "") {
                        $allow_words = array("INCORPORACION", "INCREMENTO TENENCIA ACCIONARIA", "DISMINUCION DE CAPITAL SOCIAL");
                        $return = $this->check_word($parameterArr[$i]['fieldValue'], $allow_words);
                        if ($return) {
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
                    if ($return) {
                        $result["error_code"] = $code_error;
                        $result["error_row"] = $parameterArr[$i]['row'];
                        $result["error_input_value"] = "empty";
                        array_push($stack, $result);
                    }
                    //Value Validation
                    if ($parameterArr[$i]['fieldValue'] != "") {
                        $allow_words = array("A", "B");
                        $return = $this->check_word($parameterArr[$i]['fieldValue'], $allow_words);
                        if ($return) {
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
                    if ($return) {
                        $result["error_code"] = $code_error;
                        $result["error_row"] = $parameterArr[$i]['row'];
                        $result["error_input_value"] = "empty";
                        array_push($stack, $result);
                    }
                    //Value Validation
                    if ($parameterArr[$i]['fieldValue'] != "") {
                        $allow_words = array("AGE", "AGO", "ACA", "EC");
                        $return = $this->check_word($parameterArr[$i]['fieldValue'], $allow_words);
                        if ($return) {
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
                    if ($return) {
                        $result["error_code"] = $code_error;
                        $result["error_row"] = $parameterArr[$i]['row'];
                        $result["error_input_value"] = "empty";
                        array_push($stack, $result);
                    }
                    //Check Date Validation
                    if ($parameterArr[$i]['fieldValue'] != "") {
                        $return = $this->check_date_format($parameterArr[$i]['fieldValue']);
                        if ($return) {
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
                        if ($return) {
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
                        if ($return) {
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
                    if ($return) {
                        $result["error_code"] = $code_error;
                        $result["error_row"] = $parameterArr[$i]['row'];
                        $result["error_input_value"] = "empty";
                        array_push($stack, $result);
                    }
                    //Value Validation
                    if ($parameterArr[$i]['fieldValue'] != "") {
                        $allow_words = array("SUSCRIPCION", "TRANSFERENCIA");
                        $return = $this->check_word($parameterArr[$i]['fieldValue'], $allow_words);
                        if ($return) {
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
                            if ($return) {
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
                            if ($return) {
                                $result["error_code"] = $code_error;
                                $result["error_row"] = $parameterArr[$i]['row'];
                                $result["error_input_value"] = "empty";
                                array_push($stack, $result);
                            }
                            //Check Numeric Validation
                            if ($parameterArr[$i]['fieldValue'] != "") {
                                $return = $this->check_is_numeric($parameterArr[$i]['fieldValue']);
                                if ($return) {
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
                            if ($return) {
                                $result["error_code"] = $code_error;
                                $result["error_row"] = $parameterArr[$i]['row'];
                                $result["error_input_value"] = "empty";
                                array_push($stack, $result);
                            }
                            //Check Numeric Validation
                            if ($parameterArr[$i]['fieldValue'] != "") {
                                $return = $this->check_is_numeric($parameterArr[$i]['fieldValue']);
                                if ($return) {
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
                            if ($return) {
                                $result["error_code"] = $code_error;
                                $result["error_row"] = $parameterArr[$i]['row'];
                                $result["error_input_value"] = "empty";
                                array_push($stack, $result);
                            }
                            //Check Numeric Validation
                            if ($parameterArr[$i]['fieldValue'] != "") {
                                $return = $this->check_is_numeric($parameterArr[$i]['fieldValue']);
                                if ($return) {
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
                            if ($return) {
                                $result["error_code"] = $code_error;
                                $result["error_row"] = $parameterArr[$i]['row'];
                                $result["error_input_value"] = "empty";
                                array_push($stack, $result);
                            }
                            //Check Numeric Validation
                            if ($parameterArr[$i]['fieldValue'] != "") {
                                $return = $this->check_is_numeric($parameterArr[$i]['fieldValue']);
                                if ($return) {
                                    $result["error_code"] = $code_error;
                                    $result["error_row"] = $parameterArr[$i]['row'];
                                    $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                                    array_push($stack, $result);
                                }
                            }
                            break;
                    }
                }


                if ($parameterArr[$i]['col'] >= 38 && $parameterArr[$i]['col'] <= 39) {

                    switch ($parameterArr[$i]['col']) {
                        case 38:
                            if ($AG_field_value == "SUSCRIPCION" && ($A1_field_value == "INCORPORACION" || $A1_field_value == "INCREMENTO DE TENENCIA ACCIONARIA")) {
                                //CHECK FOR EMPTY
                                $code_error = "AL.1";
                                $return = $this->check_for_empty($parameterArr[$i]['fieldValue']);
                                if ($return) {
                                    $result["error_code"] = $code_error;
                                    $result["error_row"] = $parameterArr[$i]['row'];
                                    $result["error_input_value"] = "not empty";
                                    array_push($stack, $result);
                                }
                            } else if ($A1_field_value == "DISMINUCION DE CAPITAL SOCIAL") {
                                //do something
                                $code_error = "AL.2";
                                $return = $this->check_empty($parameterArr[$i]['fieldValue']);
                                if ($return) {
                                    $result["error_code"] = $code_error;
                                    $result["error_row"] = $parameterArr[$i]['row'];
                                    $result["error_input_value"] = "empty";
                                    array_push($stack, $result);
                                }
                            } else if ($AG_field_value == "TRANSFERENCIA") {
                                $code_error = "AL.3";
                                $return = $this->check_empty($parameterArr[$i]['fieldValue']);
                                if ($return) {
                                    $result["error_code"] = $code_error;
                                    $result["error_row"] = $parameterArr[$i]['row'];
                                    $result["error_input_value"] = "empty";
                                    array_push($stack, $result);
                                }
                            }
                            break;

                        case 39:
                            if ($AG_field_value == "SUSCRIPCION" && ($A1_field_value == "INCORPORACION" || $A1_field_value == "INCREMENTO DE TENENCIA ACCIONARIA")) {
                                //CHECK FOR EMPTY
                                $code_error = "AM.1";
                                $return = $this->check_for_empty($parameterArr[$i]['fieldValue']);
                                if ($return) {
                                    $result["error_code"] = $code_error;
                                    $result["error_row"] = $parameterArr[$i]['row'];
                                    $result["error_input_value"] = "not empty";
                                    array_push($stack, $result);
                                }
                            } else if ($A1_field_value == "DISMINUCION DE CAPITAL SOCIAL") {
                                //do something
                                $code_error = "AM.2";
                                $return = $this->check_empty($parameterArr[$i]['fieldValue']);
                                if ($return) {
                                    $result["error_code"] = $code_error;
                                    $result["error_row"] = $parameterArr[$i]['row'];
                                    $result["error_input_value"] = "empty";
                                    array_push($stack, $result);
                                }
                            } else if ($AG_field_value == "TRANSFERENCIA") {
                                $code_error = "AM.3";
                                $return = $this->check_empty($parameterArr[$i]['fieldValue']);
                                if ($return) {
                                    $result["error_code"] = $code_error;
                                    $result["error_row"] = $parameterArr[$i]['row'];
                                    $result["error_input_value"] = "empty";
                                    array_push($stack, $result);
                                }
                            }

                            $code_error = "AM.4";
                            if ($parameterArr[$i]['fieldValue'] != "") {
                                $allow_words = array("DISMINUCION DE TENENCIA ACCIONARIA", "DESVINCULACION");

                                $return = $this->check_word($parameterArr[$i]['fieldValue'], $allow_words);
                                if ($return) {
                                    $result["error_code"] = $code_error;
                                    $result["error_row"] = $parameterArr[$i]['row'];
                                    $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                                    array_push($stack, $result);
                                }
                            }
                            break;
                    }
                }


                /////////////////////////////////////////
                /*
                 * 2. VALIDADORES PARTICULARES
                 * 2.1. COLUMNA A - TIPO DE OPERACIÓN: “INCORPORACIÓN”
                 *                  
                 */


                if ($A1_field_value == "INCORPORACION") {
                    /*
                     * CUIT
                     * El campo no puede estar vacío y  debe tener 11 caracteres sin guiones. El CUIT debe cumplir el “ALGORITMO VERIFICADOR”.
                     */

                    if ($parameterArr[$i]['col'] == 3) {
                        $code_error = "C.1";
                        //Check Empry
                        $return = $this->check_empty($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = "empty";
                            array_push($stack, $result);
                        }

                        if ($parameterArr[$i]['fieldValue'] != "") {
                            $return = $this->cuit_checker(str_replace("-", "", $parameterArr[$i]['fieldValue']));
                            if (!$return) {
                                $result["error_code"] = $code_error;
                                $result["error_row"] = $parameterArr[$i]['row'];
                                $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                                array_push($stack, $result);
                            }
                        }
                    }

                    /*
                     * NOMBRE
                     * El campo no puede estar vacío. En caso de que el CUIT informado ya está registrado en la Base de Datos del Sistema, este tomará en cuenta el nombre allí registrado. En caso contrario, se mantendrá provisoriamente el nombre informado por la SGR.
                     */
                    if ($parameterArr[$i]['col'] == 4) {
                        $code_error = "D.1";
                        //Check Empry
                        $return = $this->check_empty($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = "empty";
                            array_push($stack, $result);
                        }
                    }


                    /*
                     * PROVINCIA
                     * El campo no puede estar vacío. En caso de que el CUIT informado ya está registrado en la Base de Datos del Sistema, este tomará en cuenta el nombre allí registrado. En caso contrario, se mantendrá provisoriamente el nombre informado por la SGR.
                     */
                    if ($parameterArr[$i]['col'] == 5) {
                        $code_error = "E.1";
                        //Check Empry
                        $return = $this->check_empty($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = "empty";
                            array_push($stack, $result);
                        }

                        if ($parameterArr[$i]['fieldValue'] != "") {
                            $allow_words = array("CAPITAL FEDERAL", "BUENOS AIRES", "CATAMARCA", "CORDOBA", "CHUBUT", "CHACO", "CORRIENTES", "ENTRE RIOS", "FORMOSA", "JUJUY", "LA PAMPA", "LA RIOJA", "MISIONES", "MENDOZA", "NEUQUEN", "RIO NEGRO", "SALTA", "SANTA CRUZ", "SANTIAGO DEL ESTERO", "SANTA FE", "SAN JUAN", "SAN LUIS", "TIERRA DEL FUEGO", "TUCUMAN");

                            $return = $this->check_word($parameterArr[$i]['fieldValue'], $allow_words);
                            if ($return) {
                                $result["error_code"] = $code_error;
                                $result["error_row"] = $parameterArr[$i]['row'];
                                $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                                array_push($stack, $result);
                            }
                        }
                    }

                    /*
                     * PARTIDO_MUNICIPIO_COMUNA
                     * El campo no puede estar vacío.
                     */
                    if ($parameterArr[$i]['col'] == 6) {
                        $code_error = "F.1";
                        //Check Empry
                        $return = $this->check_empty($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = "empty";
                            array_push($stack, $result);
                        }
                    }

                    /*
                     * LOCALIDAD
                     * El campo no puede estar vacío.
                     */
                    if ($parameterArr[$i]['col'] == 7) {
                        $code_error = "G.1";
                        //Check Empry
                        $return = $this->check_empty($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = "empty";
                            array_push($stack, $result);
                        }
                    }

                    /*
                     * CODIGO_POSTAL
                     * El campo no puede estar vacío. Debe contener 8 dígitos. El primero y los tres últimos alfabéticos, el segundo, tercero, cuarto y quinto numéricos.
                     */
                    if ($parameterArr[$i]['col'] == 8) {
                        $code_error = "H.1";
                        //Check Empry
                        $return = $this->check_empty($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = "empty";
                            array_push($stack, $result);
                        }

                        if ($parameterArr[$i]['fieldValue'] != "") {
                            $return = $this->check_zip_code($parameterArr[$i]['fieldValue']);
                            if ($return) {
                                $result["error_code"] = $code_error;
                                $result["error_row"] = $parameterArr[$i]['row'];
                                $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                                array_push($stack, $result);
                            }
                        }
                    }
                    
                    /*
                     * CALLE
                     * El campo no puede estar vacío.
                     */
                    if ($parameterArr[$i]['col'] == 9) {
                        $code_error = "I.1";
                        //Check Empry
                        $return = $this->check_empty($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = "empty";
                            array_push($stack, $result);
                        }
                    }
                    
                    /*
                     * NRO
                     * El campo no puede estar vacío.
                     */
                    if ($parameterArr[$i]['col'] == 10) {
                        $code_error = "J.1";
                        //Check Empry
                        $return = $this->check_empty($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = "empty";
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
            return true;
        }
    }

    function check_for_empty($parameter) {
        if ($parameter != NULL) {
            return true;
        }
    }

    function check_word($parameter, $allow_words) {
        if (!in_array(strtoupper($parameter), $allow_words)) {
            return true;
        }
    }

    function check_date_format($parameter) {
        $num_length = strlen((string) $parameter);
        if ($num_length != 5) {
            return true;
        }
    }

    function check_zip_code($parameter) {
        $num_length = strlen((string) $parameter);
        if ($num_length != 8) {
            return true;
        }
    }

    function check_is_numeric($parameter) {
        if (!is_numeric($parameter)) {
            return true;
        }
    }

    function check_is_alphabetic($parameter) {
        if (!ctype_alpha($parameter)) {
            return true;
        }
    }

    //FUNCION VALIDA CUIT
    function cuit_checker($cuit) {
        $cadena = str_split($cuit);

        $result = $cadena[0] * 5;
        $result += $cadena[1] * 4;
        $result += $cadena[2] * 3;
        $result += $cadena[3] * 2;
        $result += $cadena[4] * 7;
        $result += $cadena[5] * 6;
        $result += $cadena[6] * 5;
        $result += $cadena[7] * 4;
        $result += $cadena[8] * 3;
        $result += $cadena[9] * 2;

        $div = intval($result / 11);
        $resto = $result - ($div * 11);

        if ($resto == 0) {
            if ($resto == $cadena[10]) {
                return true;
            } else {
                return false;
            }
        } elseif ($resto == 1) {
            if ($cadena[10] == 9 AND $cadena[0] == 2 AND $cadena[1] == 3) {
                return true;
            } elseif ($cadena[10] == 4 AND $cadena[0] == 2 AND $cadena[1] == 3) {
                return true;
            }
        } elseif ($cadena[10] == (11 - $resto)) {
            return true;
        } else {
            return false;
        }
    }

}
