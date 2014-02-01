<?php

class Lib_06_data extends MX_Controller {
    /* VALIDADOR ANEXO 06 */

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
                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result["error_code"] = $code_error;
                        $result["error_row"] = $parameterArr[$i]['row'];
                        $result["error_input_value"] = "empty";
                        array_push($stack, $result);
                    }

                    //Value Validation
                    if (isset($parameterArr[$i]['fieldValue'])) {
                        $A1_field_value = "";
                        $allow_words = array("INCORPORACION", "INCREMENTO DE TENENCIA ACCIONARIA", "DISMINUCION DE CAPITAL SOCIAL");
                        $return = check_word($parameterArr[$i]['fieldValue'], $allow_words);
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
                 * Nro B.1
                 * Detail:
                 * El campo no puede estar vacío y debe contener uno de los siguientes parámetros:
                  A
                  B
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
                    if (isset($parameterArr[$i]['fieldValue'])) {
                        $B1_field_value = "";
                        $allow_words = array("A", "B");
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
                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result["error_code"] = $code_error;
                        $result["error_row"] = $parameterArr[$i]['row'];
                        $result["error_input_value"] = "empty";
                        array_push($stack, $result);
                    }
                    //Value Validation
                    if (isset($parameterArr[$i]['fieldValue'])) {
                        $allow_words = array("AGE", "AGO", "ACA", "EC");
                        $return = check_word($parameterArr[$i]['fieldValue'], $allow_words);
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
                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result["error_code"] = $code_error;
                        $result["error_row"] = $parameterArr[$i]['row'];
                        $result["error_input_value"] = "empty";
                        array_push($stack, $result);
                    }
                    //Check Date Validation
                    if (isset($parameterArr[$i]['fieldValue'])) {
                        $return = check_date_format($parameterArr[$i]['fieldValue']);
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
                        $return = check_is_numeric($parameterArr[$i]['fieldValue']);
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
                    if (isset($parameterArr[$i]['fieldValue'])) {
                        $AF_field_value = "";
                        $return = check_date_format($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                            array_push($stack, $result);
                        } else {

                            $fecha = mktime(0, 0, 0, 1, -1 + $parameterArr[$i]['fieldValue'], 1900);
                            $AF_field_value = strftime("%Y", $fecha);
                            /* VALIDACION R.3 */
                            $resto = $AF_field_value - $R2_field_value;
                            if ($resto > 4 && $R2_field_value) {
                                $code_error = "R.3";
                                $result["error_code"] = $code_error;
                                $result["error_row"] = $parameterArr[$i]['row'];
                                $result["error_input_value"] = $AF_field_value . "-" . $R2_field_value;
                                array_push($stack, $result);
                            }
                        }
                        /* PERIOD */
                        $return = check_period($parameterArr[$i]['fieldValue'], $this->session->userdata['period']);
                        $code_error = "AF.1";
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
                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result["error_code"] = $code_error;
                        $result["error_row"] = $parameterArr[$i]['row'];
                        $result["error_input_value"] = "empty";
                        array_push($stack, $result);
                    }
                    //Value Validation
                    if (isset($parameterArr[$i]['fieldValue'])) {
                        $AG_field_value = "";
                        $allow_words = array("SUSCRIPCION", "TRANSFERENCIA");
                        $return = check_word($parameterArr[$i]['fieldValue'], $allow_words);
                        if ($return) {
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                            array_push($stack, $result);
                        } else {
                            $AG_field_value = $parameterArr[$i]['fieldValue'];
                        }



                        /*
                         * CUSTOM VALIDATION AG.2
                         * El campo no puede estar vacío y debe contener el siguientes parámetro:
                          SUSCRIPCIÓN
                         * CUSTOM VALIDATION CAB
                         * C, D, E, F, G, H, I, J, K, L, M, N, O, P, Q, R, S, T, U, V, W, X, Y, Z, AA, AB
                          DEBE ESTAR VACÍAS

                         */
                        if ($A1_field_value == "DISMINUCION DE CAPITAL SOCIAL") {
                            $code_error = "AG.2";
                            $allow_words = array("SUSCRIPCION");
                            $return = check_word($parameterArr[$i]['fieldValue'], $allow_words);
                            if ($return) {
                                $result["error_code"] = $code_error;
                                $result["error_row"] = $parameterArr[$i]['row'];
                                $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                                array_push($stack, $result);
                            }

                            $code_error = "CAB";
                            $is_empty_arr = array(
                                17 => 'R.2',
                                19 => 'S.2',
                                20 => 'T.2',
                                21 => 'U.2',
                                22 => 'V.2',
                                23 => 'W.2',
                                24 => 'X.2',
                                25 => 'Y.2',
                                26 => 'Z.2',
                                28 => 'AB.1'
                            );
                            $ord_arr = array();

                            foreach ($is_empty_arr as $col_num => $error_code) {
                                if (false !== ($pos = array_search2d($error_code, $stack))) {
                                    $ord_arr[] = $pos;
                                }

                                //NOT empty field Validation
                                $return = check_empty($parameterArr[$col_num]['fieldValue']);
                                var_dump($return, $col_num);
                                if (!$return) {
                                    $result["error_code"] = $code_error;
                                    $result["error_row"] = $parameterArr[$col_num]['row'];
                                    $result["error_input_value"] = $parameterArr[$col_num]['fieldValue'];
                                    array_push($stack, $result);
                                }
                            }

                            foreach ($ord_arr as $ord_num) {
                                unset($stack[$ord_num]);
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
                            $return = check_empty($parameterArr[$i]['fieldValue']);
                            if ($return) {
                                $result["error_code"] = $code_error;
                                $result["error_row"] = $parameterArr[$i]['row'];
                                $result["error_input_value"] = "empty";
                                array_push($stack, $result);
                            }
                            //Check Numeric Validation
                            if (isset($parameterArr[$i]['fieldValue'])) {
                                $return = check_is_numeric($parameterArr[$i]['fieldValue']);
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
                            $return = check_empty($parameterArr[$i]['fieldValue']);
                            if ($return) {
                                $result["error_code"] = $code_error;
                                $result["error_row"] = $parameterArr[$i]['row'];
                                $result["error_input_value"] = "empty";
                                array_push($stack, $result);
                            }
                            //Check Numeric Validation
                            if (isset($parameterArr[$i]['fieldValue'])) {
                                $return = check_is_numeric($parameterArr[$i]['fieldValue']);
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
                            $return = check_empty($parameterArr[$i]['fieldValue']);
                            if ($return) {
                                $result["error_code"] = $code_error;
                                $result["error_row"] = $parameterArr[$i]['row'];
                                $result["error_input_value"] = "empty";
                                array_push($stack, $result);
                            }
                            //Check Numeric Validation
                            if (isset($parameterArr[$i]['fieldValue'])) {
                                $return = check_is_numeric($parameterArr[$i]['fieldValue']);
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
                            $return = check_empty($parameterArr[$i]['fieldValue']);
                            if ($return) {
                                $result["error_code"] = $code_error;
                                $result["error_row"] = $parameterArr[$i]['row'];
                                $result["error_input_value"] = "empty";
                                array_push($stack, $result);
                            }
                            //Check Numeric Validation
                            if (isset($parameterArr[$i]['fieldValue'])) {
                                $return = check_is_numeric($parameterArr[$i]['fieldValue']);
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
                                $return = check_for_empty($parameterArr[$i]['fieldValue']);
                                if ($return) {
                                    $result["error_code"] = $code_error;
                                    $result["error_row"] = $parameterArr[$i]['row'];
                                    $result["error_input_value"] = "not empty";
                                    array_push($stack, $result);
                                }
                            } else if ($A1_field_value == "DISMINUCION DE CAPITAL SOCIAL") {
                                //do something
                                $code_error = "AL.2";
                                $return = check_empty($parameterArr[$i]['fieldValue']);
                                if ($return) {
                                    $result["error_code"] = $code_error;
                                    $result["error_row"] = $parameterArr[$i]['row'];
                                    $result["error_input_value"] = "empty";
                                    array_push($stack, $result);
                                }
                            } else if ($AG_field_value == "TRANSFERENCIA") {
                                $code_error = "AL.3";
                                $return = check_empty($parameterArr[$i]['fieldValue']);
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
                                $return = check_for_empty($parameterArr[$i]['fieldValue']);
                                if ($return) {
                                    $result["error_code"] = $code_error;
                                    $result["error_row"] = $parameterArr[$i]['row'];
                                    $result["error_input_value"] = "not empty";
                                    array_push($stack, $result);
                                }
                            } else if ($A1_field_value == "DISMINUCION DE CAPITAL SOCIAL") {
                                //do something
                                $code_error = "AM.2";
                                $return = check_empty($parameterArr[$i]['fieldValue']);
                                if ($return) {
                                    $result["error_code"] = $code_error;
                                    $result["error_row"] = $parameterArr[$i]['row'];
                                    $result["error_input_value"] = "empty";
                                    array_push($stack, $result);
                                }
                            } else if ($AG_field_value == "TRANSFERENCIA") {
                                $code_error = "AM.3";
                                $return = check_empty($parameterArr[$i]['fieldValue']);
                                if ($return) {
                                    $result["error_code"] = $code_error;
                                    $result["error_row"] = $parameterArr[$i]['row'];
                                    $result["error_input_value"] = "empty";
                                    array_push($stack, $result);
                                }
                            }

                            $code_error = "AM.4";
                            if (isset($parameterArr[$i]['fieldValue'])) {
                                $allow_words = array("DISMINUCION DE TENENCIA ACCIONARIA", "DESVINCULACION");

                                $return = check_word($parameterArr[$i]['fieldValue'], $allow_words);
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
                     * NO puede estar repetido dentro del mismo excel
                     */



                    if ($parameterArr[$i]['col'] == 3) {
                        $code_error = "C.1";
                        //Check Empry
                        $return = check_empty($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = "empty";
                            array_push($stack, $result);
                        }

                        if (isset($parameterArr[$i]['fieldValue'])) {
                            $return = cuit_checker($parameterArr[$i]['fieldValue']);
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
                        $return = check_empty($parameterArr[$i]['fieldValue']);
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
                        $return = check_empty($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = "empty";
                            array_push($stack, $result);
                        }

                        if (isset($parameterArr[$i]['fieldValue'])) {
                            $allow_words = array("CAPITAL FEDERAL", "BUENOS AIRES", "CATAMARCA", "CORDOBA", "CHUBUT", "CHACO", "CORRIENTES", "ENTRE RIOS", "FORMOSA", "JUJUY", "LA PAMPA", "LA RIOJA", "MISIONES", "MENDOZA", "NEUQUEN", "RIO NEGRO", "SALTA", "SANTA CRUZ", "SANTIAGO DEL ESTERO", "SANTA FE", "SAN JUAN", "SAN LUIS", "TIERRA DEL FUEGO", "TUCUMAN");

                            $return = check_word($parameterArr[$i]['fieldValue'], $allow_words);
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
                        $return = check_empty($parameterArr[$i]['fieldValue']);
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
                        $return = check_empty($parameterArr[$i]['fieldValue']);
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
                        $return = check_empty($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = "empty";
                            array_push($stack, $result);
                        }

                        if (isset($parameterArr[$i]['fieldValue'])) {
                            $return = check_zip_code($parameterArr[$i]['fieldValue']);
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
                        $return = check_empty($parameterArr[$i]['fieldValue']);
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
                        $return = check_empty($parameterArr[$i]['fieldValue']);
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
                        $return = check_empty($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = "empty";
                            array_push($stack, $result);
                        }
                        if (isset($parameterArr[$i]['fieldValue'])) {
                            $return = check_area_code($parameterArr[$i]['fieldValue']);
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
                        $return = check_empty($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = "empty";
                            array_push($stack, $result);
                        }
                        if (isset($parameterArr[$i]['fieldValue'])) {
                            $return = check_phone_number($parameterArr[$i]['fieldValue']);
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
                            $return = check_email($parameterArr[$i]['fieldValue']);
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
                            $return = check_web($parameterArr[$i]['fieldValue']);
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
                        if ($B1_field_value == "A") {
                            $return = check_empty($parameterArr[$i]['fieldValue']);
                            if ($return) {
                                $result["error_code"] = $code_error;
                                $result["error_row"] = $parameterArr[$i]['row'];
                                $result["error_input_value"] = "empty";
                                array_push($stack, $result);
                            }
                        }
                        if (isset($parameterArr[$i]['fieldValue'])) {
                            $return = ciu(cerosClanae($parameterArr[$i]['fieldValue']));
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
                        $return = check_empty($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = "empty";
                            array_push($stack, $result);
                        }
                        //Value Validation
                        if (isset($parameterArr[$i]['fieldValue'])) {
                            $allow_words = array("EXCENTO", "INSCRIPTO", "MONOSTRIBUTISTA");
                            $return = check_word($parameterArr[$i]['fieldValue'], $allow_words);
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
                 * 2.1.1. COLUMNA B - TIPO DE SOCIO: “A”
                 *                  
                 */


                if ($B1_field_value == "A") {
                    $range = range(18, 20);
                    if (in_array($parameterArr[$i]['col'], $range)) {

                        switch ($parameterArr[$i]['col']) {

                            case 18: //ANIO_MES1                              
                                $R1_field_value = "";
                                $R2_field_value = "";
                                if (isset($parameterArr[$i]['fieldValue'])) {
                                    $return = check_date($parameterArr[$i]['fieldValue']);
                                    if (!$return) {
                                        $code_error = "R.2";
                                        $result["error_code"] = $code_error;
                                        $result["error_row"] = $parameterArr[$i]['row'];
                                        $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                                        array_push($stack, $result);
                                    } else {
                                        $R1_field_value = $parameterArr[$i]['fieldValue'];
                                        $R2_field_value = $return;
                                    }
                                }

                                break;

                            case 19://MONTO
                                //Check Numeric Validation
                                $S2_field_value = "";
                                if (isset($parameterArr[$i]['fieldValue'])) {
                                    $code_error = "S.2";
                                    $return = check_is_numeric($parameterArr[$i]['fieldValue']);
                                    if ($return) {
                                        $result["error_code"] = $code_error;
                                        $result["error_row"] = $parameterArr[$i]['row'];
                                        $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                                        array_push($stack, $result);
                                    } else {
                                        $S2_field_value = $parameterArr[$i]['fieldValue'];
                                        $average_amount_1 = $S2_field_value;
                                    }
                                }
                                break;

                            case 20://TIPO_ORIGEN
                                //Value Validation
                                $T2_field_value = "";
                                if (isset($parameterArr[$i]['fieldValue'])) {
                                    $code_error = "T.2";
                                    $allow_words = array("BALANCES", "CERTIFICACION DE INGRESOS", "DDJJ IMPUESTOS");
                                    $return = check_word($parameterArr[$i]['fieldValue'], $allow_words);
                                    if ($return) {
                                        $result["error_code"] = $code_error;
                                        $result["error_row"] = $parameterArr[$i]['row'];
                                        $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                                        array_push($stack, $result);
                                    } else {
                                        $T2_field_value = $parameterArr[$i]['fieldValue'];
                                    }
                                }


                                /* CHECK ONE FOR ALL */
                                if ((bool) $R1_field_value || (bool) $S2_field_value || (bool) $T2_field_value) {
                                    if (!(bool) $R1_field_value || !(bool) $S2_field_value || !(bool) $T2_field_value) {
                                        $code_error = "R.1";
                                        $result["error_code"] = $code_error;
                                        $result["error_row"] = $parameterArr[$i]['row'];
                                        $result["error_input_value"] = $R1_field_value . "*" . $S2_field_value . "*" . $T2_field_value;
                                        array_push($stack, $result);
                                    }
                                }

                                break;
                        }
                    }


                    $range = range(21, 23);
                    if (in_array($parameterArr[$i]['col'], $range)) {
                        switch ($parameterArr[$i]['col']) {
                            case 21: //ANIO_MES2                              
                                $U1_field_value = "";
                                $U2_field_value = "";
                                if (isset($parameterArr[$i]['fieldValue'])) {
                                    $return = check_date($parameterArr[$i]['fieldValue']);
                                    if (!$return) {
                                        $code_error = "U.2";
                                        $result["error_code"] = $code_error;
                                        $result["error_row"] = $parameterArr[$i]['row'];
                                        $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                                        array_push($stack, $result);
                                    } else {
                                        $U1_field_value = $parameterArr[$i]['fieldValue'];
                                        $U2_field_value = $return;
                                    }
                                }

                                break;

                            case 22://MONTO
                                //Check Numeric Validation
                                $V2_field_value = "";
                                if (isset($parameterArr[$i]['fieldValue'])) {
                                    $code_error = "V.2";
                                    $return = check_is_numeric($parameterArr[$i]['fieldValue']);
                                    if ($return) {
                                        $result["error_code"] = $code_error;
                                        $result["error_row"] = $parameterArr[$i]['row'];
                                        $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                                        array_push($stack, $result);
                                    } else {
                                        $V2_field_value = $parameterArr[$i]['fieldValue'];
                                        $average_amount_2 = $V2_field_value;
                                    }
                                }
                                break;

                            case 23://TIPO_ORIGEN
                                //Value Validation
                                $W2_field_value = "";
                                if (isset($parameterArr[$i]['fieldValue'])) {
                                    $code_error = "W.2";
                                    $allow_words = array("BALANCES", "CERTIFICACION DE INGRESOS", "DDJJ IMPUESTOS");
                                    $return = check_word($parameterArr[$i]['fieldValue'], $allow_words);
                                    if ($return) {
                                        $result["error_code"] = $code_error;
                                        $result["error_row"] = $parameterArr[$i]['row'];
                                        $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                                        array_push($stack, $result);
                                    } else {
                                        $W2_field_value = $parameterArr[$i]['fieldValue'];
                                    }
                                }


                                /* CHECK ONE FOR ALL */
                                if ((bool) $U1_field_value || (bool) $V2_field_value || (bool) $W2_field_value) {
                                    if (!(bool) $U1_field_value || !(bool) $V2_field_value || !(bool) $W2_field_value) {
                                        $code_error = "U.1";
                                        $result["error_code"] = $code_error;
                                        $result["error_row"] = $parameterArr[$i]['row'];
                                        $result["error_input_value"] = $U1_field_value . "*" . $V2_field_value . "*" . $W2_field_value;
                                        array_push($stack, $result);
                                    }
                                }

                                break;
                        }
                    }


                    $range = range(24, 26);
                    if (in_array($parameterArr[$i]['col'], $range)) {

                        switch ($parameterArr[$i]['col']) {
                            case 24: //ANIO_MES3                                        
                                $X1_field_value = "";
                                $X2_field_value = "";
                                if (isset($parameterArr[$i]['fieldValue'])) {
                                    $return = check_date($parameterArr[$i]['fieldValue']);
                                    if (!$return) {
                                        $code_error = "X.2";
                                        $result["error_code"] = $code_error;
                                        $result["error_row"] = $parameterArr[$i]['row'];
                                        $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                                        array_push($stack, $result);
                                    } else {
                                        $X1_field_value = $parameterArr[$i]['fieldValue'];
                                        $X2_field_value = $return;
                                    }
                                }

                                break;

                            case 25://MONTO
                                //Check Numeric Validation                                
                                $Y2_field_value = "";
                                if (isset($parameterArr[$i]['fieldValue'])) {
                                    $code_error = "Y.2";
                                    $return = check_is_numeric($parameterArr[$i]['fieldValue']);
                                    if ($return) {
                                        $result["error_code"] = $code_error;
                                        $result["error_row"] = $parameterArr[$i]['row'];
                                        $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                                        array_push($stack, $result);
                                    } else {
                                        $Y2_field_value = $parameterArr[$i]['fieldValue'];
                                        $average_amount_3 = $Y2_field_value;
                                    }
                                }
                                break;

                            case 26://TIPO_ORIGEN
                                //Value Validation
                                $Z2_field_value = "";
                                if (isset($parameterArr[$i]['fieldValue'])) {
                                    $code_error = "Z.2";
                                    $allow_words = array("BALANCES", "CERTIFICACION DE INGRESOS", "DDJJ IMPUESTOS", "ESTIMACION");
                                    $return = check_word($parameterArr[$i]['fieldValue'], $allow_words);
                                    if ($return) {
                                        $result["error_code"] = $code_error;
                                        $result["error_row"] = $parameterArr[$i]['row'];
                                        $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                                        array_push($stack, $result);
                                    } else {
                                        $Z2_field_value = $parameterArr[$i]['fieldValue'];
                                    }
                                }


                                /* CHECK ONE FOR ALL */
                                if ((bool) $X1_field_value || (bool) $Y2_field_value || (bool) $Z2_field_value) {
                                    if (!(bool) $X1_field_value || !(bool) $Y2_field_value || !(bool) $Z2_field_value) {
                                        $code_error = "X.1";
                                        $result["error_code"] = $code_error;
                                        $result["error_row"] = $parameterArr[$i]['row'];
                                        $result["error_input_value"] = $X1_field_value . "*" . $Y2_field_value . "*" . $Z2_field_value;
                                        array_push($stack, $result);
                                    }
                                }
                                break;
                        }
                    }

                    /*
                     * CANTIDAD_DE_EMPLEADOS
                     * El campo no puede estar vacío y debe contener caracteres numéricos mayores a Cero.
                     */
                    if ($parameterArr[$i]['col'] == 28) {
                        if ($A1_field_value != "INCREMENTO DE TENENCIA ACCIONARIA") {
                            $code_error = "AB.1";

                            /* AVERAGE AMOUNT */
                            $average_amount = $average_amount_1 + $average_amount_2 + $average_amount_3; //array($Y2_field_value, $V2_field_value, $S2_field_value);
                            /* echo "<pre>";
                              var_dump($average_amount);
                              echo "</pre>"; */

                            $average_amount_1 = 0;
                            $average_amount_2 = 0;
                            $average_amount_3 = 0;


                            $return = check_empty($parameterArr[$i]['fieldValue']);
                            if ($return) {
                                $result["error_code"] = $code_error;
                                $result["error_row"] = $parameterArr[$i]['row'];
                                $result["error_input_value"] = "empty";
                                array_push($stack, $result);
                            } else {
                                //Check Numeric Validation
                                $return = check_is_numeric($parameterArr[$i]['fieldValue']);
                                if ($return) {
                                    $result["error_code"] = $code_error;
                                    $result["error_row"] = $parameterArr[$i]['row'];
                                    $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                                    array_push($stack, $result);
                                }
                            }
                        }
                    }
                }




                /////////////////////////////////////////
                /*
                 * 2. VALIDADORES PARTICULARES
                 * 2.1.2. COLUMNA B - TIPO DE SOCIO: “B”
                 *                  
                 */


                if ($B1_field_value == "B") {
                    $range = range(17, 26);
                    if (in_array($parameterArr[$i]['col'], $range)) {

                        switch ($parameterArr[$i]['col']) {
                            case 17: //Código de Actividad
                                $code_error = "Q.3";
                                break;
                            case 18: //Año/Mes 1
                                $code_error = "R.4";
                                break;
                            case 19: //Monto 1
                                $code_error = "S.4";
                                break;
                            case 20: //Tipo Origen 1
                                $code_error = "T.3";
                                break;
                            case 21: //Año/Mes 2
                                $code_error = "U.4";
                                break;
                            case 22: //Monto 2
                                $code_error = "V.4";
                                break;
                            case 23: //Tipo Origen 2
                                $code_error = "W.3";
                                break;
                            case 24: //Año/Mes 3
                                $code_error = "X.4";
                                break;
                            case 25: //Monto 3
                                $code_error = "Y.4";
                                break;
                            case 26: //Tipo Origen 3
                                $code_error = "Z.3";
                                break;
                        }

                        //Check for Empty
                        $return = check_for_empty($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = "not empty";
                            array_push($stack, $result);
                        }
                    }

                    if ($parameterArr[$i]['col'] == 28) {
                        $code_error = "AB.2";
                        //Check for Empty
                        $return = check_for_empty($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = "not empty";
                            array_push($stack, $result);
                        }
                    }
                }


                /////////////////////////////////////////
                /*
                 * 2. VALIDADORES PARTICULARES
                 * 2.2. COLUMNA A - TIPO DE OPERACIÓN: “INCREMENTO DE TENENCIA ACCIONARIA”
                 *                  
                 */


                if ($A1_field_value == "INCREMENTO DE TENENCIA ACCIONARIA") {
                    $range = range(5, 28);
                    if (in_array($parameterArr[$i]['col'], $range)) {
                        switch ($parameterArr[$i]['col']) {
                            case 5: //Provincia
                                $code_error = "E.2";
                                break;
                            case 6: //Partido/Municipio/Comuna
                                $code_error = "F.2";
                                break;
                            case 7: //Localidad
                                $code_error = "G.2";
                                break;
                            case 8: //Código Postal
                                $code_error = "H.2";
                                break;
                            case 9: //Calle
                                $code_error = "I.2";
                                break;
                            case 10: //Número
                                $code_error = "J.2";
                                break;
                            case 11: //Piso
                                $code_error = "K.2";
                                break;
                            case 12: //Dpto. / Oficina
                                $code_error = "L.2";
                                break;
                            case 13: //Código de Área
                                $code_error = "M.2";
                                break;
                            case 14: //Teléfono
                                $code_error = "N.2";
                                break;
                            case 15: //Email
                                $code_error = "O.2";
                                break;
                            case 16: //WEB
                                $code_error = "P.2";
                                break;
                            case 17: //Código de Actividad
                                $code_error = "Q.4";
                                break;
                            case 18: //Año/Mes 1
                                $code_error = "R.5";
                                break;
                            case 19: //Monto 1
                                $code_error = "S.5";
                                break;
                            case 20: //Tipo Origen 1
                                $code_error = "T.4";
                                break;

                            case 21: //Año/Mes 2
                                $code_error = "U.5";
                                break;
                            case 22: //Monto 2
                                $code_error = "V.5";
                                break;
                            case 23: //Tipo Origen 2
                                $code_error = "W.4";
                                break;

                            case 24: //Año/Mes 3
                                $code_error = "X.5";
                                break;
                            case 25: //Monto 3
                                $code_error = "Y.5";
                                break;
                            case 26: //Tipo Origen 3
                                $code_error = "Z.4";
                                break;
                            case 27: //Condición de Inscripción ante AFIP
                                $code_error = "AA.2";
                                break;
                            case 28: //Cantidad de Empleados
                                $code_error = "AB.3";
                                break;
                        }
                        //Check for Empty
                        if ($A1_field_value != "DISMINUCION DE CAPITAL SOCIAL") {
                            $return = check_for_empty($parameterArr[$i]['fieldValue']);
                            if ($return) {
                                $result["error_code"] = $code_error;
                                $result["error_row"] = $parameterArr[$i]['row'];
                                $result["error_input_value"] = "not empty" . $A1_field_value;
                                array_push($stack, $result);
                            }
                        }
                    }
                }

                /////////////////////////////////////////
                /*
                 * 2. VALIDADORES PARTICULARES
                 * 2.2. COLUMNA A - TIPO DE OPERACIÓN: “DISMINUSIÓN DE CAPITAL SOCIAL”
                 *                  
                 */
                if ($A1_field_value == "DISMINUCION DE CAPITAL SOCIAL") {
                    $range = range(3, 28);
                    if (in_array($parameterArr[$i]['col'], $range)) {
                        switch ($parameterArr[$i]['col']) {
                            case 3: //CUIT
                                $code_error = "C.2";
                                break;
                            case 4: //NOMBRE
                                $code_error = "D.2";
                                break;
                            case 5: //Provincia
                                $code_error = "E.2";
                                break;
                            case 6: //Partido/Municipio/Comuna
                                $code_error = "F.2";
                                break;
                            case 7: //Localidad
                                $code_error = "G.2";
                                break;
                            case 8: //Código Postal
                                $code_error = "H.2";
                                break;
                            case 9: //Calle
                                $code_error = "I.2";
                                break;
                            case 10: //Número
                                $code_error = "J.2";
                                break;
                            case 11: //Piso
                                $code_error = "K.2";
                                break;
                            case 12: //Dpto. / Oficina
                                $code_error = "L.2";
                                break;
                            case 13: //Código de Área
                                $code_error = "M.2";
                                break;
                            case 14: //Teléfono
                                $code_error = "N.2";
                                break;
                            case 15: //Email
                                $code_error = "O.2";
                                break;
                            case 16: //WEB
                                $code_error = "P.2";
                                break;
                            case 17: //Código de Actividad
                                $code_error = "Q.4";
                                break;
                            case 18: //Año/Mes 1
                                $code_error = "R.5";
                                break;
                            case 19: //Monto 1
                                $code_error = "S.5";
                                break;
                            case 20: //Tipo Origen 1
                                $code_error = "T.4";
                                break;

                            case 21: //Año/Mes 2
                                $code_error = "U.5";
                                break;
                            case 22: //Monto 2
                                $code_error = "V.5";
                                break;
                            case 23: //Tipo Origen 2
                                $code_error = "W.4";
                                break;

                            case 24: //Año/Mes 3
                                $code_error = "X.5";
                                break;
                            case 25: //Monto 3
                                $code_error = "Y.5";
                                break;
                            case 26: //Tipo Origen 3
                                $code_error = "Z.4";
                                break;
                            case 27: //Condición de Inscripción ante AFIP
                                $code_error = "AA.2";
                                break;
                            case 28: //Cantidad de Empleados
                                $code_error = "AB.3";
                                break;
                        }
                        //Check for Empty
                        if ($A1_field_value != "DISMINUCION DE CAPITAL SOCIAL") {
                            $return = check_for_empty($parameterArr[$i]['fieldValue']);
                            if ($return) {
                                $result["error_code"] = $code_error;
                                $result["error_row"] = $parameterArr[$i]['row'];
                                $result["error_input_value"] = "not empty";
                                array_push($stack, $result);
                            }
                        }
                    }


                    /*
                     * MODALIDAD
                     * El campo no puede estar vacío y debe contener el siguientes parámetro:
                      SUSCRIPCIÓN
                     */
                    if ($parameterArr[$i]['col'] == 33) {
                        $code_error = "AG.2";
                        //empty field Validation
                        $return = check_empty($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = $parameterArr[$i]['fieldValue'] . "empty";
                            array_push($stack, $result);
                        }
                        //Value Validation
                        if (isset($parameterArr[$i]['fieldValue'])) {
                            $allow_words = array("SUSCRIPCION");
                            $return = check_word($parameterArr[$i]['fieldValue'], $allow_words);
                            if ($return) {
                                $result["error_code"] = $code_error;
                                $result["error_row"] = $parameterArr[$i]['row'];
                                $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                                array_push($stack, $result);
                            }
                        }
                    }
                }

                if ($parameterArr[$i]['col'] == 17) {


                    /* CALC AVERAGE */
                    $calcPromedio = ($S2_field_value != "") ? 1 : 0;
                    $calcPromedio += ($V2_field_value != "") ? 1 : 0;
                    $calcPromedio += ($Y2_field_value != "") ? 1 : 0;
                    if ($calcPromedio != 0) {
                        $montosArr = array($S2_field_value, $V2_field_value, $Y2_field_value);
                        $sumaMontos = array_sum($montosArr);
                        $average_amount = $sumaMontos / $calcPromedio;
                    }

                    $sector = $this->sgr_model->clae2013($code);
                    $isPyme = $this->sgr_model->get_company_size($sector, $average_amount);
                    if (!$isPyme) {
                        $code_error = "S.3";
                        $result["error_code"] = $code_error;
                        $result["error_row"] = $parameterArr[$i]['row'];
                        $result["error_input_value"] = "No califica como PYME";
                        array_push($stack, $result);
                    }
                }
            }
        }
        $this->data = $stack;
    }

}