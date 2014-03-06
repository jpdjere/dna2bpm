<?php

class Lib_06_data extends MX_Controller {
    /* VALIDADOR ANEXO 06 */

    public function __construct($parameter) {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('sgr/tools');
        $this->load->model('sgr/sgr_model');

        $model_anexo = "model_06";
        $this->load->Model($model_anexo);

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
                  INTEGRACION PENDIENTE
                 */

                if ($parameterArr[$i]['col'] == 1) {

                    $code_error = "A.1";

                    //empty field Validation
                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                        array_push($stack, $result);
                    }

                    //Value Validation
                    if ($parameterArr[$i]['fieldValue'] != "") {
                        $A_cell_value = "";
                        $allow_words = array("INCORPORACION", "INCREMENTO DE TENENCIA ACCIONARIA", "DISMINUCION DE CAPITAL SOCIAL", "INTEGRACION PENDIENTE");
                        $return = check_word($parameterArr[$i]['fieldValue'], $allow_words);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        } else {
                            $A_cell_value = $parameterArr[$i]['fieldValue'];
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
                        $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                        array_push($stack, $result);
                    }
                    //Value Validation
                    if ($parameterArr[$i]['fieldValue'] != "") {
                        $B_cell_value = "";
                        $allow_words = array("A", "B");
                        $return = check_word($parameterArr[$i]['fieldValue'], $allow_words);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        } else {
                            $B_cell_value = $parameterArr[$i]['fieldValue'];
                        }
                    }
                }

                if ($parameterArr[$i]['col'] == 3) {

                    $C_cell_value = $parameterArr[$i]['fieldValue'];
                }


                /* TIPO_ACTA
                 * El campo no puede estar vacío y debe contener uno de los siguientes parámetros:
                  AGE – Acta de Asamblea General Extraordinaria
                  AGO – Acta de Asamblea General Ordinaria
                  ACA – Acta de Consejo de Administración
                  EC – Estatuto Constitutivo
                 */
                if ($parameterArr[$i]['col'] == 3) {
                    
                }

                if ($parameterArr[$i]['col'] == 29) {

                    $code_error = "AC.1";
                    //empty field Validation
                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                        array_push($stack, $result);
                    }
                    //Value Validation
                    if ($parameterArr[$i]['fieldValue'] != "") {
                        $allow_words = array("AGE", "AGO", "ACA", "EC");
                        $return = check_word($parameterArr[$i]['fieldValue'], $allow_words);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
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
                        $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                        array_push($stack, $result);
                    }
                    //Check Date Validation
                    if ($parameterArr[$i]['fieldValue'] != "") {
                        $return = check_date_format($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
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
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
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
                        $AF_cell_value = "";
                        $return = check_date_format($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        } else {
                            /* VALIDACION R.3 */
                            if (!in_array($check_diff, range(0, 3))) {
                                $code_error = "R.3";


                                $result["error_input_value"] = $check_diff;
                                array_push($stack, $result);
                            }
                        }
                        /* PERIOD */
                        $return = check_period($parameterArr[$i]['fieldValue'], $this->session->userdata['period']);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }
                    }
                }



                /*
                 * MODALIDAD AG 
                 * El campo no puede estar vacío y debe contener uno de los siguientes parámetros:
                  SUSCRIPCION
                  TRANSFERENCIA
                  En caso de que en la Columna A se complete la opción “DISMINUCION DE CAPITAL SOCIAL”, solo puede contener la opción “SUSCRIPCION”                *
                 *
                 */

                if ($parameterArr[$i]['col'] == 33) {

                    $code_error = "AG.1";
                    $AG_cell_value = $parameterArr[$i]['fieldValue'];

                    //empty field Validation
                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                        array_push($stack, $result);
                    } else {
                        $allow_words = array("SUSCRIPCION", "TRANSFERENCIA");
                        $return = check_word($parameterArr[$i]['fieldValue'], $allow_words);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }

                        /*
                         * CUSTOM VALIDATION AG.2
                         * El campo no puede estar vacío y debe contener el siguientes parámetro:
                          SUSCRIPCIÓN
                         * CUSTOM VALIDATION C-AB
                         * C, D, E, F, G, H, I, J, K, L, M, N, O, P, Q, R, S, T, U, V, W, X, Y, Z, AA, AB
                          DEBE ESTAR VACÍAS

                         */
                        if ($A_cell_value == "DISMINUCION DE CAPITAL SOCIAL") {
                            $code_error = "AG.2";
                            $allow_words = array("SUSCRIPCION");
                            $return = check_word($parameterArr[$i]['fieldValue'], $allow_words);
                            if ($return) {
                                $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                                array_push($stack, $result);
                            }

                            $code_error = "C-AB";
                            $is_empty_arr = array(
                                17 => 'R.2',
                                18 => 'S.2',
                                19 => 'T.2',
                                20 => 'U.2',
                                21 => 'V.2',
                                22 => 'W.2',
                                23 => 'X.2',
                                24 => 'Y.2',
                                2 => 'Z.2',
                                27 => 'AB.1'
                            );
                            $ord_arr = array();

                            foreach ($is_empty_arr as $col_num => $error_code) {
                                //empty field Validation
                                $return = check_empty($parameterArr[$col_num]['fieldValue']);
                                if (!$return) {
                                    $result["error_input_value"] = $col_num;
                                    array_push($stack, $result);
                                }

                                if (false !== ($pos = array_search2d($error_code, $stack))) {
                                    $ord_arr[] = $pos;
                                }
                            }

                            foreach ($ord_arr as $ord_num) {
                                unset($stack[$ord_num]);
                            }
                        }
                    }
                }


                /*
                 * CAPITAL_SUSCRIPTO CAPITAL_INTEGRADO	
                 * AH.1, AI.1
                 * El campo no puede estar vacío y debe contener dígitos numéricos enteros, sin decimales.
                 * AH.3
                 * Si en la Columna A se completa la opción “INTEGRACIÓN PENDIENTE”, este campo debe tomar valor CERO. 
                 * AI.8
                  Si en la Columna A se completa la opción “INTEGRACIÓN PENDIENTE”, este campo debe tomar valor mayor a CERO y se debe verificar que el valor indicado sea menor o igual a la diferencia entre los saldos previos de Capital Suscripto y Capital Integrado. Es decir, sólo puede realizar una “INTEGRACIÓN PENDIENTE”, en caso de que haya SUSCRIPTO CAPITAL sin haberlo integrado.
                 * 
                 */
                $range = range(34, 35);
                if (in_array($parameterArr[$i]['col'], $range)) {
                    switch ($parameterArr[$i]['col']) {
                        case 34:
                            $AH_cell_value = (int) $parameterArr[$i]['fieldValue'];
                            $code_error = "AH.1";
                            //empty field Validation
                            $return = check_empty($parameterArr[$i]['fieldValue']);
                            if ($return) {
                                $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                                array_push($stack, $result);
                            } else {
                                //Check Numeric Validation
                                $return = check_is_numeric_no_decimal($parameterArr[$i]['fieldValue']);
                                if ($return) {
                                    $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                                    array_push($stack, $result);
                                }
                            }


                            /* AH3 */
                            if ($A_cell_value == "INTEGRACION PENDIENTE" && $AH_cell_value != 0) {
                                $code_error = "AH.3";
                                $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                                array_push($stack, $result);
                            }

                            break;

                        case 35:
                            $AI_cell_value = $parameterArr[$i]['fieldValue'];
                            $code_error = "AI.1";
                            //empty field Validation
                            $return = check_empty($parameterArr[$i]['fieldValue']);
                            if ($return) {
                                $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                                array_push($stack, $result);
                            } else {
                                $return = check_is_numeric_no_decimal($parameterArr[$i]['fieldValue']);
                                if ($return) {
                                    $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                                    array_push($stack, $result);
                                }

                                if ($A_cell_value == "INTEGRACION PENDIENTE") {


                                    $balance = $this->$model_anexo->shares($C_cell_value, $B_cell_value);
                                    $balance_integrated = $this->$model_anexo->shares($C_cell_value, $B_cell_value, 5598);

                                    $subscribed = $balance + $AH_cell_value;
                                    $integrated = $balance_integrated + $AI_cell_value;

                                    if ($parameterArr[$i]['fieldValue'] < 0) {
                                        $code_error = "AI.8";
                                        $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                                        array_push($stack, $result);
                                    }


                                    $diff_int_sus = $integrated - $subscribed;

                                    if ($diff_int_sus < (int) $parameterArr[$i]['fieldValue']) {
                                        $code_error = "AI.8";
                                        $result = return_error_array($code_error, $parameterArr[$i]['row'], "Saldo Integrado: " . $integrated . " - Saldo Suscripto: " . $subscribed);
                                        array_push($stack, $result);
                                    }
                                }
                            }
                            break;
                    }
                }

                if ($parameterArr[$i]['col'] == 36) {
                    $AL_cell_value = $parameterArr[$i]['fieldValue'];
                    if ($AG_cell_value == "SUSCRIPCION" && ($A_cell_value == "INCORPORACION" || $A_cell_value == "INCREMENTO DE TENENCIA ACCIONARIA")) {
                        //CHECK FOR EMPTY
                        $code_error = "AJ.1";
                        $return = check_for_empty($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }
                    }

                    if ($A_cell_value == "DISMINUCION DE CAPITAL SOCIAL") {
                        $return = check_empty($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $code_error = "AJ.2";
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                            array_push($stack, $result);
                        }
                    }

                    if ($AG_cell_value == "TRANSFERENCIA") {
                        $code_error = "AJ.3";
                        $return = check_empty($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                            array_push($stack, $result);
                        }
                    }


                    /* CALC AVERAGE */
                    $sector = $this->sgr_model->clae2013($ciu);
                    if ($A_cell_value == "INCORPORACION") {



                        /* C.2 */
                        $subscribed = $this->$model_anexo->shares($C_cell_value, $B_cell_value);
                        $integrated = $this->$model_anexo->shares($C_cell_value, $B_cell_value, 5598);
                        $saldo = array_sum(array($subscribed, $integrated));
                        if ($saldo != 0) {
                            $code_error = "C.2";
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], "Saldo: " . $saldo . ' para ' . $C_cell_value . "(" . $subscribed . "-" . $integrated . ")");
                            array_push($stack, $result);
                        }

                        $calcPromedio = ($S2_cell_value != "") ? 1 : 0;
                        $calcPromedio += ($V2_cell_value != "") ? 1 : 0;
                        $calcPromedio += ($Y2_cell_value != "") ? 1 : 0;
                        if ($calcPromedio != 0) {
                            $montosArr = array($S2_cell_value, $V2_cell_value, $Y2_cell_value);
                            $sumaMontos = array_sum($montosArr);
                            $average_amount = $sumaMontos / $calcPromedio;
                        }
                        if (!$sector) {
                            $code_error = "Q.2";
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], "Código  errorneo (" . $ciu . ")");
                            array_push($stack, $result);
                        } else {
                            $isPyme = $this->sgr_model->get_company_size($sector, $average_amount);
                            if (!$isPyme) {
                                $code_error = "S.3";
                                $result = return_error_array($code_error, $parameterArr[$i]['row'], "No califica como PYME (" . $ciu . ") / Sector Code: (" . $sector . ") / Promedio: (" . $average_amount . ")");
                                array_push($stack, $result);
                            }
                        }
                    }

                    /* "INCREMENTO DE TENENCIA ACCIONARIA" */
                    if ($A_cell_value == "INCREMENTO DE TENENCIA ACCIONARIA") {
                        /* B.3 */
                        $balance = $balance = $this->$model_anexo->shares($C_cell_value, $B_cell_value);
                        if ($balance == 0) {
                            $code_error = "B.3";
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }

                        /* C.3 */
                        $return = check_empty($C_cell_value);
                        if ($return) {
                            $code_error = "C.3";
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                            array_push($stack, $result);
                        }
                    }


                    /*

                     * AI.3
                      Si en la columna AG se completó la opción “TRANSFERENCIA”, el valor aquí indicado debe ser igual al valor indicado en la Columna AH.
                     * AI.4
                      Si en la Columna A se completó la opción “INCORPORACIÓN” y en la Columna AG se completó la opción “SUSCRIPCIÓN”, el valor aquí indicado debe ser mayor o igual al 50% del valor indicado en la Columna AH y a lo sumo igual a este último.
                     */


                    if ($parameterArr[$i]['fieldValue'] != "") {
                        $balance = $this->$model_anexo->shares($parameterArr[$i]['fieldValue'], $B_cell_value);                        /*
                         * AH.4
                         * Si la columna AJ está completa, se debe verificar que el Socio Cedente informado en la misma posea la cantidad de Capital Suscripto 
                         * para transferir, y que corresponden al tipo de Acción que posea, “A” o “B”. 
                         * De no poseerlo, se debe rechazar la importación. 
                         */
                        //  echo "<br> balance " . $balance . $parameterArr[$i]['fieldValue']."->". $B_cell_value . "->" . $AH_cell_value;

                        if ($balance < $AH_cell_value) {
                            $code_error = "AH.4";
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }

                        /* AH.2
                          Sin en la Columna A se completó la opción “INCORPORACION”, INCREMENTO DE TENENCIA ACCIONARIA”, o “DISMINUSIÓN DE CAPITAL SOCIAL”, debe tomar valor mayor a cero.
                         * */

                        if ($A_cell_value != "INTEGRACION PENDIENTE") {
                            if ($AH_cell_value < 0) {
                                $code_error = "AH.2";
                                $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                                array_push($stack, $result);
                            }
                        }

                        /* AI.2
                          Si la columna AJ está completa, se debe verificar que el Socio Cedente
                         * informado en la misma posea la cantidad de Capital Integrado para transferir, 
                         * y que corresponda al tipo de Acción que posea, “A” o “B”. De no poseerlo, se debe rechazar la importación.
                         */


                        $balance_integrado = $this->$model_anexo->shares("30711529523", "A", 5598);
                        var_dump($balance_integrado, $parameterArr[$i]['fieldValue'], $B_cell_value);
                        exit();

                        $balance_integrado = $this->$model_anexo->shares($parameterArr[$i]['fieldValue'], $B_cell_value, 5598);
                        if ($balance_integrated < $AI_cell_value) {
                            $code_error = "AI.2";
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue'] . "(" . $balance_integrated . ")");
                            array_push($stack, $result);
                        }

                        if ($AG_cell_value == "TRANSFERENCIA") {
                            if ($AI_cell_value != $AH_cell_value) {
                                $code_error = "AI.3";
                                $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                                array_push($stack, $result);
                            }
                        }

                        if ($A_cell_value == "INCORPORACION" && $AG_cell_value == "SUSCRIPCION") {
                            $code_error = "AI.4";
                            $AH_percent = $AH_cell_value / 2;
                            $range = range($AH_percent, $AH_cell_value);
                            if (!in_array($AI_cell_value, $range)) {
                                $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                                array_push($stack, $result);
                            }
                        }
                    }

                    $partner = $parameterArr[$i]['fieldValue'];
                    $subscribed = $this->$model_anexo->shares($partner, $B_cell_value);
                    $integrated = $this->$model_anexo->shares($partner, $B_cell_value, 5598);

                    /** AI.5
                      El saldo de Capital Integrado nunca puede ser mayor al Saldo de Capital Suscripto.
                     */
                    if ($integrated > $subscribed) {
                        $code_error = "AI.5";
                        $result = return_error_array($code_error, $parameterArr[$i]['row'], "Integrado: " . $integrated . " - Suscripto: " . $subscribed);
                        array_push($stack, $result);
                    }
                }

                /////////////////////////////////////////
                /*
                 * 2. VALIDADORES PARTICULARES
                 * 2.1. COLUMNA A - TIPO DE OPERACIÓN: “INCORPORACIÓN”
                 *                  
                 */


                if ($A_cell_value == "INCORPORACION") {
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
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                            array_push($stack, $result);
                        } else {

                            $return = cuit_checker($parameterArr[$i]['fieldValue']);
                            if (!$return) {
                                $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                                array_push($stack, $result);
                            } else {
                                /* VALIDO EN TODAS LAS */
                                $balance = $this->$model_anexo->shares_others_sgrs($C_cell_value, $B_cell_value);
                                if ($balance != 0) {
                                    $code_error = "B.2";
                                    $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                                    array_push($stack, $result);
                                }
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
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
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
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                            array_push($stack, $result);
                        } else {
                            $allow_words = array("CAPITAL FEDERAL", "BUENOS AIRES", "CATAMARCA", "CORDOBA", "CHUBUT", "CHACO", "CORRIENTES", "ENTRE RIOS", "FORMOSA", "JUJUY", "LA PAMPA", "LA RIOJA", "MISIONES", "MENDOZA", "NEUQUEN", "RIO NEGRO", "SALTA", "SANTA CRUZ", "SANTIAGO DEL ESTERO", "SANTA FE", "SAN JUAN", "SAN LUIS", "TIERRA DEL FUEGO", "TUCUMAN");

                            $return = check_word($parameterArr[$i]['fieldValue'], $allow_words);
                            if ($return) {
                                $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
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
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
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
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
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
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                            array_push($stack, $result);
                        } else {
                            $return = check_zip_code($parameterArr[$i]['fieldValue']);
                            if ($return) {
                                $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
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
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
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
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
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
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                            array_push($stack, $result);
                        } else {
                            $return = check_area_code($parameterArr[$i]['fieldValue']);
                            if (!$return) {
                                $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
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
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                            array_push($stack, $result);
                        } else {
                            $return = check_phone_number($parameterArr[$i]['fieldValue']);
                            if (!$return) {
                                $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
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
                                $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
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
                                $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
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
                        $return = check_empty($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                            array_push($stack, $result);
                        } else {
                            $ciu = $parameterArr[$i]['fieldValue'];
                        }
                    }

                    /*
                     * CONDICION_INSCRIPCION_AFIP
                     * El campo no puede estar vacío y debe contener uno de los siguientes parámetros:
                      EXCENTO
                      INSCRIPTO
                      MONOTRIBUTISTA
                     */
                    if ($parameterArr[$i]['col'] == 27) {

                        $code_error = "AA.1";
                        //empty field Validation
                        $return = check_empty($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                            array_push($stack, $result);
                        } else {
                            $allow_words = array("EXCENTO", "INSCRIPTO", "MONOTRIBUTISTA");
                            $return = check_word($parameterArr[$i]['fieldValue'], $allow_words);
                            if ($return) {
                                $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
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
                if ($B_cell_value == "A") {
                    $range = range(18, 20);
                    if (in_array($parameterArr[$i]['col'], $range)) {

                        switch ($parameterArr[$i]['col']) {

                            case 18: //ANIO_MES1                              
                                $R_cell_value = "";
                                $R2_cell_value = "";
                                if ($parameterArr[$i]['fieldValue'] != "") {
                                    $return = check_date($parameterArr[$i]['fieldValue']);
                                    if (!$return) {
                                        $code_error = "R.2";
                                        $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                                        array_push($stack, $result);
                                    } else {
                                        $R_cell_value = $parameterArr[$i]['fieldValue'];
                                        $R2_cell_value = $return;

                                        list($first_year_to_check) = explode("/", $R2_cell_value);
                                        list($n, $period_to_check) = explode("-", $this->session->userdata['period']);
                                        $check_diff = (int) $period_to_check - (int) $first_year_to_check;
                                    }
                                }

                                break;

                            case 19://MONTO
                                //Check Numeric Validation
                                $S2_cell_value = "";
                                if ($parameterArr[$i]['fieldValue'] != "") {
                                    $code_error = "S.2";
                                    $return = check_is_numeric($parameterArr[$i]['fieldValue']);
                                    if ($return) {
                                        $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                                        array_push($stack, $result);
                                    } else {
                                        $S2_cell_value = $parameterArr[$i]['fieldValue'];
                                        $average_amount_1 = $S2_cell_value;
                                    }
                                }
                                break;

                            case 20://TIPO_ORIGEN
                                //Value Validation
                                $T2_cell_value = $parameterArr[$i]['fieldValue'];
                                if ($parameterArr[$i]['fieldValue'] != "") {
                                    $code_error = "T.2";
                                    $allow_words = array("BALANCES", "CERTIFICACION DE INGRESOS", "DDJJ IMPUESTOS");
                                    $return = check_word($parameterArr[$i]['fieldValue'], $allow_words);
                                    if ($return) {
                                        $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                                        array_push($stack, $result);
                                    }
                                }


                                /* CHECK ONE FOR ALL */
                                if ((bool) $R_cell_value || (bool) $S2_cell_value || (bool) $T2_cell_value) {
                                    if (!(bool) $R_cell_value || !(bool) $S2_cell_value || !(bool) $T2_cell_value) {
                                        $code_error = "R.1";
                                        $result_error_input_value = $R_cell_value . "*" . $S2_cell_value . "*" . $T2_cell_value;
                                        $result = return_error_array($code_error, $parameterArr[$i]['row'], $result_error_input_value);
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
                                $U_cell_value = "";
                                $U2_cell_value = "";
                                if ($parameterArr[$i]['fieldValue'] != "") {
                                    $return = check_date($parameterArr[$i]['fieldValue']);
                                    if (!$return) {
                                        $code_error = "U.2";
                                        $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                                        array_push($stack, $result);
                                    } else {

                                        list($second_year_to_check) = explode("/", $parameterArr[$i]['fieldValue']);
                                        list($n, $period_to_check) = explode("-", $this->session->userdata['period']);
                                        $check_diff2 = (int) $first_year_to_check + 1;


                                        if ($check_diff2 != $second_year_to_check) {
                                            $code_error = "U.3";
                                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                                            array_push($stack, $result);
                                        } else {
                                            $U_cell_value = $parameterArr[$i]['fieldValue'];
                                            $U2_cell_value = $return;
                                        }
                                    }
                                }

                                break;

                            case 22://MONTO
                                //Check Numeric Validation
                                $V2_cell_value = "";
                                if ($parameterArr[$i]['fieldValue'] != "") {
                                    $code_error = "V.2";
                                    $return = check_is_numeric($parameterArr[$i]['fieldValue']);
                                    if ($return) {
                                        $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                                        array_push($stack, $result);
                                    } else {
                                        $V2_cell_value = $parameterArr[$i]['fieldValue'];
                                        $average_amount_2 = $V2_cell_value;
                                    }
                                }
                                break;

                            case 23://TIPO_ORIGEN
                                //Value Validation
                                $W2_cell_value = "";
                                if ($parameterArr[$i]['fieldValue'] != "") {
                                    $code_error = "W.2";
                                    $allow_words = array("BALANCES", "CERTIFICACION DE INGRESOS", "DDJJ IMPUESTOS");
                                    $return = check_word($parameterArr[$i]['fieldValue'], $allow_words);
                                    if ($return) {
                                        $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                                        array_push($stack, $result);
                                    } else {
                                        $W2_cell_value = $parameterArr[$i]['fieldValue'];
                                    }
                                }


                                /* CHECK ONE FOR ALL */
                                if ((bool) $U_cell_value || (bool) $V2_cell_value || (bool) $W2_cell_value) {
                                    if (!(bool) $U_cell_value || !(bool) $V2_cell_value || !(bool) $W2_cell_value) {
                                        $code_error = "U.1";
                                        $result_error_input_value = $U_cell_value . "*" . $V2_cell_value . "*" . $W2_cell_value;
                                        $result = return_error_array($code_error, $parameterArr[$i]['row'], $result_error_input_value);
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
                                $X_cell_value = $parameterArr[$i]['fieldValue'];
                                $X2_cell_value = "";
                                $code_error = "X.2";
                                if ($parameterArr[$i]['fieldValue'] != "") {
                                    $return = check_date($parameterArr[$i]['fieldValue']);
                                    if (!$return) {


                                        $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                                        array_push($stack, $result);
                                    } else {

                                        list($last_year_to_check) = explode("/", $parameterArr[$i]['fieldValue']);
                                        list($n, $period_to_check) = explode("-", $this->session->userdata['period']);
                                        $check_diff3 = (int) $second_year_to_check + 1;

                                        if ($check_diff3 != $last_year_to_check) {


                                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                                            array_push($stack, $result);
                                        } else {
                                            $X2_cell_value = $return;
                                        }
                                    }
                                }

                                break;

                            case 25://MONTO
                                //Check Numeric Validation                                
                                $Y2_cell_value = "";
                                if ($parameterArr[$i]['fieldValue'] != "") {
                                    $code_error = "Y.2";
                                    $return = check_is_numeric($parameterArr[$i]['fieldValue']);
                                    if ($return) {


                                        $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                                        array_push($stack, $result);
                                    } else {
                                        $Y2_cell_value = $parameterArr[$i]['fieldValue'];
                                        $average_amount_3 = $Y2_cell_value;
                                    }
                                }
                                break;

                            case 26://TIPO_ORIGEN
                                //Value Validation
                                $Z2_cell_value = "";
                                if ($parameterArr[$i]['fieldValue'] != "") {
                                    $code_error = "Z.2";
                                    $allow_words = array("BALANCES", "CERTIFICACION DE INGRESOS", "DDJJ IMPUESTOS", "ESTIMACION");
                                    $return = check_word($parameterArr[$i]['fieldValue'], $allow_words);
                                    if ($return) {
                                        $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                                        array_push($stack, $result);
                                    } else {
                                        $Z2_cell_value = $parameterArr[$i]['fieldValue'];
                                    }
                                }


                                /* CHECK ONE FOR ALL */
                                if ((bool) $X_cell_value || (bool) $Y2_cell_value || (bool) $Z2_cell_value) {
                                    if (!(bool) $X_cell_value || !(bool) $Y2_cell_value || !(bool) $Z2_cell_value) {
                                        $code_error = "X.1";
                                        $result_error_input_value = $X_cell_value . "*" . $Y2_cell_value . "*" . $Z2_cell_value;
                                        $result = return_error_array($code_error, $parameterArr[$i]['row'], $result_error_input_value);
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
                        if ($A_cell_value == "INCORPORACION") {
                            $code_error = "AB.1";

                            /* AVERAGE AMOUNT */
                            $average_amount = $average_amount_1 + $average_amount_2 + $average_amount_3;
                            $average_amount_1 = 0;
                            $average_amount_2 = 0;
                            $average_amount_3 = 0;


                            $return = check_empty($parameterArr[$i]['fieldValue']);
                            if ($return) {
                                $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty." . $A_cell_value);
                                array_push($stack, $result);
                            } else {
                                //Check Numeric Validation
                                $return = check_is_numeric($parameterArr[$i]['fieldValue']);
                                if ($return) {
                                    $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
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

                if ($B_cell_value == "B") {
                    $range = range(18, 26);
                    if (in_array($parameterArr[$i]['col'], $range)) {

                        switch ($parameterArr[$i]['col']) {

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
                            $code_error = "Q-AB";
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }
                    }

                    if ($parameterArr[$i]['col'] == 28) {
                        $code_error = "AB.2";
                        //Check for Empty
                        $return = check_for_empty($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }
                    }
                }


                /////////////////////////////////////////
                /*
                 * 2. VALIDADORES PARTICULARES
                 * 2.2. COLUMNA A - TIPO DE OPERACIÓN: “INCREMENTO DE TENENCIA ACCIONARIA”
                 */

                if ($A_cell_value == "INCREMENTO DE TENENCIA ACCIONARIA") {

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
                        if ($A_cell_value != "DISMINUCION DE CAPITAL SOCIAL") {
                            $return = check_for_empty($parameterArr[$i]['fieldValue']);
                            if ($return) {
                                $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
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
                if ($A_cell_value == "DISMINUCION DE CAPITAL SOCIAL") {
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
                        if ($A_cell_value != "DISMINUCION DE CAPITAL SOCIAL") {
                            $return = check_for_empty($parameterArr[$i]['fieldValue']);
                            if ($return) {
                                $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
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
                            $result["error_input_value"] = $parameterArr[$i]['fieldValue'] . "empty";
                            array_push($stack, $result);
                        }
                        //Value Validation
                        if ($parameterArr[$i]['fieldValue'] != "") {
                            $allow_words = array("SUSCRIPCION");
                            $return = check_word($parameterArr[$i]['fieldValue'], $allow_words);
                            if ($return) {
                                $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                                array_push($stack, $result);
                            }
                        }
                    }
                }
            }
        }
//        var_dump($stack);
        //exit();
        $this->data = $stack;
    }

}
