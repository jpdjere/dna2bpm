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
                        } else {
                            $B1_field_value = $parameterArr[$i]['fieldValue'];
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
                        } else {

                            $fecha = mktime(0, 0, 0, 1, -1 + $parameterArr[$i]['fieldValue'], 1900);
                            $AF_field_value = strftime("%Y", $fecha);
                            
                            
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


                $range = range(34, 38);
                if (in_array($parameterArr[$i]['col'], $range)) {


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


                $range = range(38, 39);
                if (in_array($parameterArr[$i]['col'], $range)) {

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


                    /*
                     * CODIGO_AREA
                     * El campo no puede estar vacío. Debe tener entre 2 y 4 dígitos (sin el cero adelante).
                     */
                    if ($parameterArr[$i]['col'] == 13) {
                        $code_error = "M.1";

                        //Check Empry
                        $return = $this->check_empty($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = "empty";
                            array_push($stack, $result);
                        }
                        if ($parameterArr[$i]['fieldValue'] != "") {
                            $return = $this->check_area_code($parameterArr[$i]['fieldValue']);
                            if (!$return) {
                                $result["error_code"] = $code_error;
                                $result["error_row"] = $parameterArr[$i]['row'];
                                $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                                array_push($stack, $result);
                            }
                        }
                    }

                    /*
                     * TELEFONO
                     * El campo no puede estar vacío. Debe tener entre 6 y 10 dígitos.
                     */
                    if ($parameterArr[$i]['col'] == 14) {
                        $code_error = "N.1";
                        //Check Empry
                        $return = $this->check_empty($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = "empty";
                            array_push($stack, $result);
                        }
                        if ($parameterArr[$i]['fieldValue'] != "") {
                            $return = $this->check_phone_number($parameterArr[$i]['fieldValue']);
                            if (!$return) {
                                $result["error_code"] = $code_error;
                                $result["error_row"] = $parameterArr[$i]['row'];
                                $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                                array_push($stack, $result);
                            }
                        }
                    }

                    /*
                     * EMAIL
                     * OPCIONA. De completarse, que tenga formato de dirección de correo electrónico.
                     */
                    if ($parameterArr[$i]['col'] == 15) {
                        $code_error = "O.1";

                        if ($parameterArr[$i]['fieldValue'] != "") {
                            $return = $this->check_email($parameterArr[$i]['fieldValue']);
                            if ($return) {
                                $result["error_code"] = $code_error;
                                $result["error_row"] = $parameterArr[$i]['row'];
                                $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                                array_push($stack, $result);
                            }
                        }
                    }

                    /*
                     * WEB
                     * OPCIONA. De completarse, que tenga formato de dirección de página web.
                     */
                    if ($parameterArr[$i]['col'] == 16) {
                        $code_error = "P.1";

                        if ($parameterArr[$i]['fieldValue'] != "") {
                            $return = $this->check_web($parameterArr[$i]['fieldValue']);
                            if ($return) {
                                $result["error_code"] = $code_error;
                                $result["error_row"] = $parameterArr[$i]['row'];
                                $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                                array_push($stack, $result);
                            }
                        }
                    }


                    /*
                     * CODIGO_ACTIVIDAD_AFIP
                     * El campo no puede estar vacío. Debe tener entre 6 y 10 dígitos.
                     */
                    if ($parameterArr[$i]['col'] == 17) {
                        $code_error = "Q.1";
                        //Check Empry
                        $return = $this->check_empty($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = "empty..";
                            array_push($stack, $result);
                        }
                        if ($parameterArr[$i]['fieldValue'] != "") {
                            $return = $this->ciu($this->cerosClanae($parameterArr[$i]['fieldValue']));
                            if (!$return) {
                                $result["error_code"] = $code_error;
                                $result["error_row"] = $parameterArr[$i]['row'];
                                $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                                array_push($stack, $result);
                            }
                        }
                    }

                    /*
                     * CONDICION_INSCRIPCION_AFIP
                     * El campo no puede estar vacío y debe contener uno de los siguientes parámetros:
                      EXCENTO
                      INSCRIPTO
                      MONOSTRIBUTISTA
                     */
                    if ($parameterArr[$i]['col'] == 27) {

                        $code_error = "AA.1";

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
                            $allow_words = array("EXCENTO", "INSCRIPTO", "MONOSTRIBUTISTA");
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


                /////////////////////////////////////////
                /*
                 * 2. VALIDADORES PARTICULARES
                 * 2.1. COLUMNA A - TIPO DE OPERACIÓN: “INCORPORACIÓN”
                 *                  
                 */


                if ($B1_field_value == "A") {
                    $range = range(18, 20);
                    if (in_array($parameterArr[$i]['col'], $range)) {

                        switch ($parameterArr[$i]['col']) {

                            case 18:
                                $code_error = "R.1";
                                if ($parameterArr[$i]['fieldValue'] != "") {
                                    $return = $this->check_date($parameterArr[$i]['fieldValue']);                                    
                                    if (!$return) {
                                        $code_error = "R.2";
                                        $result["error_code"] = $code_error;
                                        $result["error_row"] = $parameterArr[$i]['row'];
                                        $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                                        array_push($stack, $result);
                                    } else {
                                        $R2_field_value = $return;
                                    }
                                }

                                break;

                            case 19:


                                break;
                        }
                    }
                }
                //////////////////////////////////////////
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

    function check_date($parameter) {
        list($year, $month) = explode("/", $parameter);
        $mm = $month;
        $dd = "10";
        $yyyy = $year;

        If (@checkdate($mm, $dd, $yyyy)) {
            return $yyyy;
        }
    }

    function check_zip_code($parameter) {
        $num_length = strlen((string) $parameter);
        if ($num_length != 8) {
            return true;
        }
    }

    function check_area_code($parameter) {
        if ($parameter[0] == 0) {
            $parameter = substr($parameter, 1);
        }

        $num_length = strlen((string) $parameter);
        $range = range(2, 4);
        if (in_array($num_length, $range)) {
            return true;
        }
    }

    function check_phone_number($parameter) {

        $num_length = strlen((string) $parameter);
        $range = range(6, 10);
        if (in_array($num_length, $range)) {
            return true;
        }
    }

    function check_email($parameter) {
        if (!filter_var($parameter, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
    }

    function check_web($parameter) {
        if (!filter_var($parameter, FILTER_VALIDATE_URL)) {
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

    /* FIX CLANAE TO CIU */

    function cerosClanae($num) {

        $range = range(11111, 990000);
        if (in_array($num, $range)) {
            if (strlen($num) == 5) {
                return "0" . $num;
            } else {
                return $num;
            }
        }
    }

    /* CIU */

    function ciu($sector) {
        //AGROPECUARIO
        //, INDUSTRIA Y MINERIA
        //, COMERCIO
        //, SERVICIOS
        //, CONSTRUCCION
        //, ADMINISTRACION PUBLICA
        //, SERVICIO DOMESTICO u ORGANISMOS INTERNACIONALES
        $newSectorCode = substr($sector, 0, 3);
        $sectorCode = substr($sector, 0, 2);
        $sector_value = "";

        $codesArr = array('01', '02', '05');
        if (in_array($sectorCode, $codesArr)) {
            $sector_value = 1;
        }


        $codesArr = array('10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31', '32', '33', '34', '35', '36', '37', '72');
        if (in_array($sectorCode, $codesArr)) {
            $sector_value = 2;
        }

        $codesArr = array('50', '51', '52');
        if (in_array($sectorCode, $codesArr)) {
            $sector_value = 3;
        }

        $codesArr = array('40', '41', '55', '60', '61', '62', '63', '64', '65', '66', '67', '70', '71', '73', '74', '80', '85', '90', '91', '92', '93');
        if (in_array($sectorCode, $codesArr)) {
            $sector_value = 4;
        }

        $codesArr = array('45');
        if (in_array($sectorCode, $codesArr)) {
            $sector_value = 5;
        }

        $codesArr = array('75');
        if (in_array($sectorCode, $codesArr)) {
            $sector_value = 6;
        }

        $codesArr = array('95');
        if (in_array($sectorCode, $codesArr)) {
            $sector_value = 7;
        }

        $codesArr = array('99');
        if (in_array($sectorCode, $codesArr)) {
            $sector_value = 8;
        }

        /*
         * ?ARTICULO 3° Resolución 50/2013 ?
         * Resolución N° 24/2001. Modificación.
         */

        $codesArr = array('921');
        if (in_array($newSectorCode, $codesArr)) {
            $sector_value = 2;
        }

        return $sector_value;
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
