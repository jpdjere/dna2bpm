<?php

class Lib_06_data extends MX_Controller {
    /* VALIDADOR ANEXO 06 */

    public function __construct($parameter) {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('sgr/tools');
        $this->load->model('sgr/sgr_model');


        $this->load->Model('model_06');


        $sgrArr = $this->sgr_model->get_sgr();
        foreach ($sgrArr as $sgr) {

            $this->sgr_id = (float) $sgr['id'];
            $this->sgr_nombre = $sgr['1693'];
            $this->sgr_cuit = $sgr['1695'];
        }

        /* RESOLUCION */
        //$resolution_2013 = $this->sgr_model->resolution_date_2013();
       

        /* Vars 
         * 
         * $parameters =  
         * $parameterArr[0]['fieldValue'] 
         * $parameterArr[0]['row'] 
         * $parameterArr[0]['col']
         * $parameterArr[0]['count']
         * 
         */


        $parameterArr = (array) $parameter;
        $result = array();
        $b3_ext_arr = array();
        $C_array_value = array();

        $fre_A_array_garantizar = array(
            "FAE CORP",
            "FAE GARANTAXI",
            "FAE INCAA",
            "FAE PMSA",
            "FAE PRODAF",
            "FAE CATAMARCA",
            "FAE SANTA CRUZ",
            "FAE SANTA FE",
            "FAE SOCO RIL",
            "FAE YAGUAR"
        );
        $fre_A_array_cuyo = array(
            "FAE CUYO PYMES"
        );
        $B_cell_value = "";

        $A_incremento_o_fusion_array = array("INCREMENTO TENENCIA ACCIONARIA", "FUSION");

        /* Validacion Basica */
        for ($i = 0; $i <= count($parameterArr); $i++) {


            $param_col = (isset($parameterArr[$i]['col'])) ? $parameterArr[$i]['col'] : 0;


            /* TIPO_OPERACION
             * Nro A.1
             * Detail:
             * El campo no puede estar vacío y debe contener uno de los siguientes parámetros:
              INCORPORACION
              INCREMENTO TENENCIA ACCIONARIA
              DISMINUCION DE CAPITAL SOCIAL
              INTEGRACION PENDIENTE
              FAE CORP
              FAE GARANTAXI
              FAE CUYO PYMES
              FAE INCAA
              FAE PMSA
              FAE PRODAF
              FAE CATAMARCA
              FAE SANTA CRUZ
              FAE SANTA FE
              FAE SOCO RIL
              FAE YAGUAR
              FUSION
             */

            if ($param_col == 1) {
                $A_cell_value = "";
                $code_error = "A.1";


                $S2_cell_value = 0;
                $V2_cell_value = 0;
                $Y2_cell_value = 0;
                $montosArr = 0;


                if (empty($parameterArr[$i]['fieldValue'])) {
                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                } else {
                    $A_cell_value = $parameterArr[$i]['fieldValue'];
                    $allow_words = array("INCORPORACION", "INCREMENTO TENENCIA ACCIONARIA", "DISMINUCION DE CAPITAL SOCIAL", "INTEGRACION PENDIENTE", "FUSION");
                    /* FRE GARANTIZAR */
                    if ($this->sgr_id == 3826154295) {
                        $code_error = "A.2";
                        $allow_words = array_merge($allow_words, $fre_A_array_garantizar);
                    }

                    /* FRE CUYO */
                    if ($this->sgr_id == 2129915769) {
                        $code_error = "A.2";
                        $allow_words = array_merge($allow_words, $fre_A_array_cuyo);
                    }



                    $return = check_word($parameterArr[$i]['fieldValue'], $allow_words);
                    if ($return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
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

            if ($param_col == 2) {

                $code_error = "B.1";
                $B_cell_value = "";

                if (empty($parameterArr[$i]['fieldValue'])) {
                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                }
                //Value Validation
                if (!empty($parameterArr[$i]['fieldValue'])) {
                    $allow_words = array("A", "B");
                    $return = check_word($parameterArr[$i]['fieldValue'], $allow_words);
                    if ($return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    } else {
                        $B_cell_value = strtoupper($parameterArr[$i]['fieldValue']);
                    }

                    /* FAE */
                    $is_fae = strpos($A_cell_value, 'FAE');
                    $code_error = "B.2.5";
                    if ($is_fae !== false) {
                        if ($B_cell_value == "A")
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                }
            }

            if ($param_col == 3) {

                $C_cell_value = $parameterArr[$i]['fieldValue'];
                if ($C_cell_value) {

                    $subscribed = $this->model_06->shares($C_cell_value, $B_cell_value);


                    $integrated = $this->model_06->shares($C_cell_value, $B_cell_value, 5598);
                    //echo "<br>" . $C_cell_value ."->" . $subscribed. "| " . $integrated;
                }
            }
            
            /*
                 * CODIGO_ACTIVIDAD_AFIP
                 * El campo no puede estar vacío. El campo no puede estar vacío. Debe contener mínimo 5 y máximo 6 caracteres.
                 */
                if ($param_col == 17 && $A_cell_value=="INCORPORACION") {
                    $code_error = "Q.1";

                    if (empty($parameterArr[$i]['fieldValue'])) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                    } else {
                        $ciu = $parameterArr[$i]['fieldValue'];

                        $return = check_clanae_ciu($ciu);
                        if (!$return)
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                        
                    }
                }
                    

            /* TIPO_ACTA
             * El campo no puede estar vacío y debe contener uno de los siguientes parámetros:
              AGE – Acta de Asamblea General Extraordinaria
              AGO – Acta de Asamblea General Ordinaria
              ACA – Acta de Consejo de Administración
              EC – Estatuto Constitutivo
             */


            if ($param_col == 29) {

                $code_error = "AC.1";
                if (empty($parameterArr[$i]['fieldValue'])) {
                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                }
                //Value Validation
                if (!empty($parameterArr[$i]['fieldValue'])) {
                    $allow_words = array("AGE", "AGO", "ACA", "EC");
                    $return = check_word($parameterArr[$i]['fieldValue'], $allow_words);
                    if ($return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                }
            }

            /* FECHA_ACTA
              El campo no puede estar vacío y debe contener cinco dígitos numéricos.
             */

            if ($param_col == 30) {
                
                
                    
                $code_error = "AD.1";
                if (empty($parameterArr[$i]['fieldValue'])) {
                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                }
                //Check Date Validation
                if (!empty($parameterArr[$i]['fieldValue'])) {
                    $return = check_date_format($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                   
                }
            }
            
            
          
                    

            /*
             * ACTA_NRO
              OPCIONAL. De ser completado, deben ser datos numéricos.
             */

            if ($param_col == 31) {

                $code_error = "AE.1";

                //Check Numeric Validation
                if (!empty($parameterArr[$i]['fieldValue'])) {
                    $return = check_is_numeric($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                }
            }

            /*
             * FECHA_DE_TRANSACCION
              El campo no puede estar vacío y debe contener cinco dígitos numéricos. La fecha debe estar dentro del período informado.
             */

            if ($param_col == 32) {
                if ($is_fae === false) {
                    $code_error = "AF.1";
                    //Check Date Validation
                    if (!empty($parameterArr[$i]['fieldValue'])) {
                        $AF_cell_value = "";
                        $return = check_date_format($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                        }
                        /* PERIOD */
                        $return = check_period($parameterArr[$i]['fieldValue'], $this->session->userdata['period']);
                        if ($return) {
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                        }
                    } else {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                    }
                } else {
                    /* FAE */
                    $code_error = "AF.2.5";
                    $return = check_for_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                }
                
                
                    $resolution = NULL;
                    $inc_date = NULL;
                
                     $inc_date = strftime("%Y/%m/%d", mktime(0, 0, 0, 1, -1 + $parameterArr[$i]['fieldValue'], 1900));
                     $inc_mongo_date = new MongoDate(strtotime(str_replace("-", "/", $inc_date)));
                    
                     $resolution = $this->sgr_model->get_resolution($inc_date);
                     
                     
                   
                     
                     if ($A_cell_value == "INCORPORACION") {
                         
                         
                         
                    /* VALIDO EN TODAS LAS SGRS Beta Fn */

                    #$balance = $this->model_06->shares_others_sgrs($C_cell_value, $B_cell_value, $inc_mongo_date);

                        $suma = $this->model_06->shares_others_sgrs_beta(1695, $C_cell_value, $B_cell_value, $inc_mongo_date);
                        $resta = $this->model_06->shares_others_sgrs_beta(5248, $C_cell_value, $B_cell_value, $inc_mongo_date);

                        $balance = $suma-$resta;


                    if ($balance != 0) {
                        $code_error = "B.2";
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $C_cell_value);
                    }
                    
                        /*CODIGO_ACTIVIDAD_AFIP COL 17*/
                        if (!$resolution) {
                
                            $code_error = "Q.2";
                            $return = $this->sgr_model->clanae1999($ciu, $B_cell_value);
                
                            if (!$return)
                                $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $ciu);
                        }
                        else {
                            $code_error = ($resolution == '11/2016') ? "Q.4.A" : "Q.3";
                
                            $return = $this->sgr_model->clae2013($ciu, $B_cell_value, $resolution);
                
                            if (!$return)
                                $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $ciu );
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

            if ($param_col == 33) {

                if ($is_fae === false) {
                    $code_error = "AG.1";
                    $AG_cell_value = $parameterArr[$i]['fieldValue'];

                    if (empty($parameterArr[$i]['fieldValue'])) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                    } else {
                        $allow_words = array("SUSCRIPCION", "TRANSFERENCIA");
                        $return = check_word($parameterArr[$i]['fieldValue'], $allow_words);
                        if ($return) {
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
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
                                $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
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
                } else {
                    /* FAE */
                    $code_error = "AG.2.5";
                    $return = check_for_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
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
            if (in_array($param_col, $range)) {
                switch ($param_col) {
                    case 34:

                        if ($is_fae === false) {
                            $AH_cell_value = (int) $parameterArr[$i]['fieldValue'];
                            /* EXT B3 */
                            if ($A_cell_value == "INCORPORACION")
                                $b3_ext_arr[] = $C_cell_value . '*' . $AH_cell_value . "*" . $B_cell_value;

                            $code_error = "AH.1";
                            if (empty($parameterArr[$i]['fieldValue'])) {
                                $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                            } else {
                                //Check Numeric Validation
                                $return = check_is_numeric_no_decimal($parameterArr[$i]['fieldValue'], true);
                                if (!$return) {
                                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                                }
                            }


                            /* AH3 */
                            if ($A_cell_value == "INTEGRACION PENDIENTE" && $AH_cell_value != 0) {
                                $code_error = "AH.3";
                                $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            }
                        } else {
                            /* FAE */
                            $code_error = "AH.2.5";
                            $return = check_for_empty($parameterArr[$i]['fieldValue']);
                            if ($return) {
                                $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            }
                        }

                        break;

                    case 35:

                        if ($is_fae === false) {

                            $AI_cell_value = $parameterArr[$i]['fieldValue'];
                            $code_error = "AI.1";
                            if (empty($parameterArr[$i]['fieldValue'])) {
                                $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                            } else {
                                $return = check_is_numeric_no_decimal($parameterArr[$i]['fieldValue']);
                                if (!$return) {
                                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                                }

                                if ($A_cell_value == "INTEGRACION PENDIENTE") {


                                    $sum_subscribed = $subscribed + $AH_cell_value;
                                    $sum_integrated = $integrated + $AI_cell_value;

                                    if ($parameterArr[$i]['fieldValue'] < 0) {
                                        $code_error = "AI.8";
                                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                                    }


                                    $diff_int_sus = $sum_integrated - $sum_subscribed;

                                    if ($diff_int_sus < (int) $parameterArr[$i]['fieldValue']) {
                                        $code_error = "AI.8";
                                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "Saldo Integrado: " . $sum_integrated . " - Saldo Suscripto: " . $sum_subscribed);
                                    }
                                }
                            }
                        } else {
                            /* FAE */
                            $code_error = "AI.2.5";
                            $return = check_for_empty($parameterArr[$i]['fieldValue']);
                            if ($return) {
                                $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            }
                        }


                        break;
                }
            }


            /* CEDENTE */
            if ($param_col == 36) {
                if ($is_fae === false) {

                    $AL_cell_value = $parameterArr[$i]['fieldValue'];

                    $grantor_subscribed = null;
                    $grantor_integrated = null;

                    if ($AL_cell_value) {
                        $grantor_subscribed = $this->model_06->shares($AL_cell_value, $B_cell_value);
                        $grantor_integrated = $this->model_06->shares($AL_cell_value, $B_cell_value, 5598);
                    }

                    if ($AG_cell_value == "SUSCRIPCION" && ($A_cell_value == "INCORPORACION" || $A_cell_value == "INCREMENTO TENENCIA ACCIONARIA")) {
                        //CHECK FOR EMPTY
                        $code_error = "AJ.1";
                        $return = check_for_empty($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                        }
                    }

                    if ($A_cell_value == "DISMINUCION DE CAPITAL SOCIAL") {
                        if (empty($parameterArr[$i]['fieldValue'])) {
                            $code_error = "AJ.2";
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                        }
                    }

                    if ($AG_cell_value == "TRANSFERENCIA") {
                        $code_error = "AJ.3";
                        if (empty($parameterArr[$i]['fieldValue'])) {
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                        }
                    }



                    if (!$resolution)
                        $sector = $this->sgr_model->clanae1999($ciu, $B_cell_value);
                    else
                        $sector = $this->sgr_model->clae2013($ciu, $B_cell_value, $resolution);
                    /* CALC AVERAGE */
                   

                    if ($A_cell_value == "INCORPORACION") {
                        /* C.2 */

                        /* Se desvinculo? */
                        $grantor_character = null;
                        $partner_bground = $this->model_06->get_partner_left($C_cell_value);
                        foreach ($partner_bground as $pb)
                            $grantor_character = $pb[5292][0];


                        if ($grantor_character != 2) {
                            $saldo = array_sum(array($subscribed, $integrated));
                            if ($saldo != 0) {
                                $code_error = "C.2";
                                $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "Saldo:" . $saldo . ' para ' . $C_cell_value . "(" . $subscribed . "-" . $integrated . ")");
                            }
                        }



                        $calcPromedio = ($S2_cell_value == 0) ? 0 : 1;
                        $calcPromedio += ($V2_cell_value == 0) ? 0 : 1;
                        $calcPromedio += ($Y2_cell_value == 0) ? 0 : 1;

                        $average_amount = 0;


                        if ($calcPromedio != 0) {
                            $montosArr = array($S2_cell_value, $V2_cell_value, $Y2_cell_value);

                            $sumaMontos = array_sum($montosArr);
                            $average_amount = $sumaMontos / $calcPromedio;
                        }
                        if (!$sector) {

                            if (!$resolution)
                                $code_error = "Q.2";
                            else
                                $code_error = ($resolution=='11/2016') ? "Q.4.A" : "Q.3";


                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "Sector / Código  errorneo (" . $ciu . ")");
                        } else {
                            $isPyme = $this->sgr_model->get_company_size($sector, $average_amount,  $inc_date);
                            if (!$isPyme) {
                                $code_error = "S.3";
                                $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "No califica como PYME (" . $ciu . ") / Sector Code: (" . $sector . ") / Promedio: (" . $average_amount . ")");
                            }
                        }
                    }




                    /*

                     * AI.3
                      Si en la columna AG se completó la opción “TRANSFERENCIA”, el valor aquí indicado debe ser igual al valor indicado en la Columna AH.
                     * AI.4
                      Si en la Columna A se completó la opción “INCORPORACIÓN” y en la Columna AG se completó la opción “SUSCRIPCIÓN”, el valor aquí indicado debe ser mayor o igual al 50% del valor indicado en la Columna AH y a lo sumo igual a este último.
                     */


                    if (!empty($parameterArr[$i]['fieldValue'])) {
                        /*
                         * AH.4
                         * Si la columna AJ está completa, se debe verificar que el Socio Cedente informado en la misma 
                         * posea la cantidad de Capital Suscripto 
                         * para transferir, y que corresponden al tipo de Acción que posea, “A” o “B”. 
                         * De no poseerlo, se debe rechazar la importación. 
                         */
                        //echo "<br> balance " . $balance . $parameterArr[$i]['fieldValue']."->". $B_cell_value . "->" . $AH_cell_value;


                        if ($grantor_subscribed < $AH_cell_value) {
                            $code_error = "AH.4";
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue'] . " Transfiere:" . $AH_cell_value . ", Dispone de:" . $grantor_subscribed);
                        }

                        /* AH.2
                          Sin en la Columna A se completó la opción “INCORPORACION”, INCREMENTO TENENCIA ACCIONARIA”, o “DISMINUSIÓN DE CAPITAL SOCIAL”, debe tomar valor mayor a cero.
                         * */

                        if ($A_cell_value != "INTEGRACION PENDIENTE") {
                            if ($AH_cell_value < 0) {
                                $code_error = "AH.2";
                                $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            }
                        }

                        /* AI.2
                          Si la columna AJ está completa, se debe verificar que el Socio Cedente
                         * informado en la misma posea la cantidad de Capital Integrado para transferir, 
                         * y que corresponda al tipo de Acción que posea, “A” o “B”. De no poseerlo, se debe rechazar la importación.
                         */

                        if ($grantor_integrated < $AI_cell_value) {
                            $code_error = "AI.2";
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue'] . "(" . $grantor_integrated . ")");
                        }

                        if ($AG_cell_value == "TRANSFERENCIA") {
                            if ($AI_cell_value != $AH_cell_value) {
                                $code_error = "AI.3";
                                $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            }
                        }

                        if ($A_cell_value == "INCORPORACION" && $AG_cell_value == "SUSCRIPCION") {
                            $code_error = "AI.4";
                            $AH_percent = $AH_cell_value / 2;
                            $range = range($AH_percent, $AH_cell_value);
                            if (!in_array($AI_cell_value, $range)) {
                                $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            }
                        }
                    }



                    $subscribed = array_sum(array($grantor_subscribed, $AH_cell_value));
                    $integrated = array_sum(array($grantor_integrated, $AI_cell_value));


                     /* "INCREMENTO TENENCIA ACCIONARIA" */
                    if ($A_cell_value == "INCREMENTO TENENCIA ACCIONARIA") {
                        /* B.3 */
                        //if ($grantor_subscribed == 0) {
                        if ($subscribed == 0) {
                            $code_error = "B.3";
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $B_cell_value);
                        }

                        /* C.3 */
                        $return = check_empty($C_cell_value);
                        if ($return) {
                            $code_error = "C.3";
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                        }
                    }

                    /* "FUSION" */
                    if ($A_cell_value == "FUSION") {
                        /* C.3 */
                        $return = check_empty($C_cell_value);
                        if ($return) {
                            $code_error = "C.3";
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                        }
                    }


                    /** AI.5
                      El saldo de Capital Integrado nunca puede ser mayor al Saldo de Capital Suscripto.
                     */
                    if ($integrated > $subscribed) {
                        $code_error = "AI.5";
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $AL_cell_value . " | Integrado: " . $integrated . " - Suscripto: " . $subscribed);
                    }
                } else {
                    /* FAE */
                    $code_error = "AJ.2.5";
                    $return = check_for_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
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
                if ($param_col == 3) {
                    $code_error = "C.1";

                    //Check Empry
                    if (empty($parameterArr[$i]['fieldValue'])) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                    } else {
                        $C_array_value[] = $parameterArr[$i]['fieldValue'];
                        $return = cuit_checker($parameterArr[$i]['fieldValue']);
                        if (!$return) {
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                        } else {
                            /* VALIDO EN TODAS LAS */
                           /* $balance = $this->model_06->shares_others_sgrs($C_cell_value, $B_cell_value);
                            if ($balance != 0) {
                                $code_error = "B.2";
                                $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            }*/
                        }
                    }
                }

                /*
                 * NOMBRE
                 * El campo no puede estar vacío. En caso de que el CUIT informado ya está registrado en la Base de Datos del Sistema, este tomará en cuenta el nombre allí registrado. En caso contrario, se mantendrá provisoriamente el nombre informado por la SGR.
                 */
                if ($param_col == 4) {
                    $code_error = "D.1";
                    if (empty($parameterArr[$i]['fieldValue'])) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                    }
                }


                /*
                 * PROVINCIA
                 * El campo no puede estar vacío. En caso de que el CUIT informado ya está registrado en la Base de Datos del Sistema, este tomará en cuenta el nombre allí registrado. En caso contrario, se mantendrá provisoriamente el nombre informado por la SGR.
                 */
                if ($param_col == 5) {
                    $code_error = "E.1";
                    //Check Empry
                    if (empty($parameterArr[$i]['fieldValue'])) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                    } else {
                        $return = parameterised_province($parameterArr[$i]['fieldValue']);
                        if (!$return) {
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                        }
                    }
                }

                /*
                 * PARTIDO_MUNICIPIO_COMUNA
                 * El campo no puede estar vacío.
                 */
                if ($param_col == 6) {
                    $bs_as_code = null;
                    $code_error = "F.1";
                    if (empty($parameterArr[$i]['fieldValue'])) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                    } else {
                        $code_error = "F.1.B";

                        $bs_as_code = strtolower($parameterArr[$i]['fieldValue']);

                        if ($bs_as_code == "buenos aires") {
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                        }
                    }
                }

                /*
                 * LOCALIDAD
                 * El campo no puede estar vacío.
                 */
                if ($param_col == 7) {
                    $bs_as_code = null;
                    $code_error = "G.1";
                    if (empty($parameterArr[$i]['fieldValue'])) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                    } else {
                        $code_error = "G.1.B";

                        $bs_as_code = strtolower($parameterArr[$i]['fieldValue']);
                        if ($bs_as_code == "buenos aires") {
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                        }
                    }
                }

                /*
                 * CODIGO_POSTAL
                 * El campo no puede estar vacío. Debe contener 8 dígitos. El primero y los tres últimos alfabéticos, el segundo, tercero, cuarto y quinto numéricos.
                 * Fix #12454
                  Anexo 6 - Modificación Validador H.1
                 * "H.1. El campo no puede estar vacío. Debe contener 8 dígitos. El primero y los tres últimos alfabéticos, el segundo, tercero, cuarto y quinto numéricos, o debe contener 4 dígitos, todos numéricos."
                 */
                if ($param_col == 8) {
                    $code_error = "H.1";
                    if (empty($parameterArr[$i]['fieldValue'])) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                    } else {
                        $return = check_zip_code($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            //
                        }
                    }
                }

                /*
                 * CALLE
                 * El campo no puede estar vacío.
                 */
                if ($param_col == 9) {
                    $code_error = "I.1";
                    if (empty($parameterArr[$i]['fieldValue'])) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                    }
                }

                /*
                 * NRO
                 * El campo no puede estar vacío.
                 */
                if ($param_col == 10) {
                    $code_error = "J.1";
                    if (empty($parameterArr[$i]['fieldValue']) && $parameterArr[$i]['fieldValue'] != 0) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                    }
                }


                /*
                 * CODIGO_AREA
                 * El campo no puede estar vacío. Debe tener entre 2 y 4 dígitos (sin el cero adelante).
                 */
                if ($param_col == 13) {
                    $code_error = "M.1";

                    if (empty($parameterArr[$i]['fieldValue'])) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                    } else {
                        $return = check_area_code($parameterArr[$i]['fieldValue']);
                        if (!$return) {
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                        }
                    }
                }

                /*
                 * TELEFONO
                 * El campo no puede estar vacío. Debe tener entre 6 y 10 dígitos.
                 */
                if ($param_col == 14) {
                    $code_error = "N.1";
                    if (empty($parameterArr[$i]['fieldValue'])) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                    } else {
                        $return = check_phone_number($parameterArr[$i]['fieldValue']);
                        if (!$return) {
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                        }
                    }
                }

                /*
                 * EMAIL
                 * OPCIONA. De completarse, que tenga formato de dirección de correo electrónico.
                 */
                if ($param_col == 15) {
                    $code_error = "O.1";

                    if (!empty($parameterArr[$i]['fieldValue'])) {
                        $return = check_email($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                        }
                    }
                }

                /*
                 * WEB
                 * OPCIONA. De completarse, que tenga formato de dirección de página web.
                 */
                if ($param_col == 16) {
                    $code_error = "P.1";

                    if (!empty($parameterArr[$i]['fieldValue'])) {
                        $return = check_web($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                        }
                    }
                }


                

                /*
                 * CONDICION_INSCRIPCION_AFIP
                 * El campo no puede estar vacío y debe contener uno de los siguientes parámetros:
                  EXENTO
                  INSCRIPTO
                  MONOTRIBUTISTA
                 */
                if ($param_col == 27) {

                    $code_error = "AA.1";
                    if (empty($parameterArr[$i]['fieldValue'])) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                    } else {
                        $allow_words = array("EXENTO", "INSCRIPTO", "MONOTRIBUTISTA");
                        $return = check_word($parameterArr[$i]['fieldValue'], $allow_words);
                        if ($return) {
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
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


            if ($B_cell_value == "A" && $A_cell_value == "INCORPORACION") {
                $range = range(18, 20);
                if (in_array($param_col, $range)) {

                    switch ($param_col) {

                        case 18: //ANIO_MES1                              
                            $R_cell_value = "";
                            $R2_cell_value = "";
                            if (!empty($parameterArr[$i]['fieldValue'])) {
                                $return = check_date($parameterArr[$i]['fieldValue']);
                                if (!$return) {
                                    $code_error = "R.2";
                                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                                } else {
                                    $R_cell_value = $parameterArr[$i]['fieldValue'];
                                    $R2_cell_value = $return;

                                    list($first_year_to_check) = explode("/", $R2_cell_value);
                                    list($n, $period_to_check) = explode("-", $this->session->userdata['period']);
                                    $check_diff = (int) $period_to_check - ((int) $first_year_to_check);
                                    /* VALIDACION R.3 */
                                    if (!in_array($check_diff, range(0, 3))) {
                                        $code_error = "R.3";
                                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $check_diff);
                                    }
                                }
                            }

                            break;

                        case 19://MONTO
                            //Check Numeric Validation

                            if (!empty($parameterArr[$i]['fieldValue'])) {
                                $code_error = "S.2";
                                $return = check_is_numeric($parameterArr[$i]['fieldValue']);
                                if ($return) {
                                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                                } else {
                                    $S2_cell_value = $parameterArr[$i]['fieldValue'];
                                    $average_amount_1 = $S2_cell_value;
                                }
                            }
                            break;

                        case 20://TIPO_ORIGEN
                            //Value Validation
                            $T2_cell_value = $parameterArr[$i]['fieldValue'];
                            if (!empty($parameterArr[$i]['fieldValue'])) {

                                $code_error = "T.2";
                                $allow_words = array("BALANCES", "CERTIFICACION DE INGRESOS", "DDJJ IMPUESTOS");
                                $return = check_word($parameterArr[$i]['fieldValue'], $allow_words);
                                if ($return) {
                                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                                }
                            }


                            /* CHECK ONE FOR ALL */
                            if ((bool) $R_cell_value || (bool) $S2_cell_value || (bool) $T2_cell_value) {
                                if (!(bool) $R_cell_value || !(bool) $S2_cell_value || !(bool) $T2_cell_value) {
                                    $code_error = "R.1";
                                    $result_error_input_value = $R_cell_value . "*" . $S2_cell_value . "*" . $T2_cell_value;
                                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $result_error_input_value);
                                }
                            }

                            break;
                    }
                }


                $range = range(21, 23);
                if (in_array($param_col, $range)) {
                    switch ($param_col) {
                        case 21: //ANIO_MES2                              
                            $U_cell_value = "";
                            $U2_cell_value = "";
                            $error = false;
                            if (!empty($parameterArr[$i]['fieldValue'])) {
                                $return = check_date($parameterArr[$i]['fieldValue']);
                                if (!$return) {
                                    $code_error = "U.2";
                                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                                } else {
                                    $code_error = "U.3";
                                    list($U_year) = explode("/", $parameterArr[$i]['fieldValue']);
                                    list($n, $period_to_check) = explode("-", $this->session->userdata['period']);

                                    if ($R_cell_value) {
                                        // Existe R
                                        list($R_year) = explode("/", $R_cell_value);
                                        if ((int) $R_year + 1 != (int) $U_year) {
                                            $error = true;
                                        }
                                    } else {
                                        // No existe R, U puede ser de uno a dos años menor que periodo actual
                                        $dif = (int) $period_to_check - (int) $U_year;
                                        if ($dif != 1 && $dif != 2) {
                                            $error = true;
                                        }
                                    }
                                    // 
                                    if ($error) {
                                        $result[] = return_error_array($code_error, $parameterArr [$i] ['row'], $parameterArr [$i] ['fieldValue']);
                                    } else {
                                        $U_cell_value = $parameterArr [$i] ['fieldValue'];
                                        $U2_cell_value = $return;
                                    }
                                }
                            }

                            break;

                        case 22://MONTO
                            //Check Numeric Validation                                
                            if (!empty($parameterArr[$i]['fieldValue'])) {
                                $code_error = "V.2";
                                $return = check_is_numeric($parameterArr[$i]['fieldValue']);
                                if ($return) {
                                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                                } else {
                                    $V2_cell_value = $parameterArr[$i]['fieldValue'];
                                    $average_amount_2 = $V2_cell_value;
                                }
                            }
                            break;

                        case 23://TIPO_ORIGEN
                            //Value Validation
                            $W2_cell_value = "";
                            if (!empty($parameterArr[$i]['fieldValue'])) {
                                $code_error = "W.2";
                                $allow_words = array("BALANCES", "CERTIFICACION DE INGRESOS", "DDJJ IMPUESTOS");
                                $return = check_word($parameterArr[$i]['fieldValue'], $allow_words);
                                if ($return) {
                                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                                } else {
                                    $W2_cell_value = $parameterArr[$i]['fieldValue'];
                                }
                            }


                            /* CHECK ONE FOR ALL */
                            if ((bool) $U_cell_value || (bool) $V2_cell_value || (bool) $W2_cell_value) {
                                if (!(bool) $U_cell_value || !(bool) $V2_cell_value || !(bool) $W2_cell_value) {
                                    $code_error = "U.1";
                                    $result_error_input_value = $U_cell_value . "*" . $V2_cell_value . "*" . $W2_cell_value;
                                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $result_error_input_value);
                                }
                            }

                            break;
                    }
                }


                $range = range(24, 26);
                if (in_array($param_col, $range)) {

                    switch ($param_col) {
                        case 24: //ANIO_MES3 
                            $X_cell_value = null;
                            if (empty($parameterArr[$i]['fieldValue'])) {
                                $code_error = "X.1";
                                $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                            } else {
                                $X_cell_value = $parameterArr[$i]['fieldValue'];
                                $X2_cell_value = "";
                                $code_error = "X.2";

                                $return = check_date($parameterArr[$i]['fieldValue']);
                                if (!$return) {
                                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                                } else {
                                    list($last_year_to_check) = explode("/", $parameterArr[$i]['fieldValue']);
                                    list($n, $period_to_check) = explode("-", $this->session->userdata['period']);

                                    if (isset($second_year_to_check)) {
                                        // Columna U con data	
                                        if ((int) $second_year_to_check + 1 != (int) $last_year_to_check) {
                                            // El año debe de X debe ser U+1

                                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                                        }
                                    } else {
                                        // Columna U vacia
                                        if (!($last_year_to_check == $period_to_check || (int) $last_year_to_check == (int) $period_to_check - 1)) {
                                            // X debe ser mismo año que periodo o uno antes
                                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                                        } else {
                                            $X2_cell_value = $return;
                                        }
                                    }
                                }
                            }

                            break;

                        case 25://MONTO
                            //Check Numeric Validation                                

                            $Y2_cell_value = null;

                            if (!empty($parameterArr[$i]['fieldValue'])) {
                                $code_error = "Y.2";
                                $return = check_is_numeric($parameterArr[$i]['fieldValue']);
                                if ($return) {
                                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                                } else {
                                    $Y2_cell_value = $parameterArr[$i]['fieldValue'];
                                    $average_amount_3 = $Y2_cell_value;
                                }
                            }
                            break;

                        case 26://TIPO_ORIGEN
                            //Value Validation
                            $Z2_cell_value = null;

                            $code_error = "Z.1";

                            if (empty($parameterArr[$i]['fieldValue'])) {
                                $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                            } else {
                                $allow_words = array("BALANCES", "CERTIFICACION DE INGRESOS", "DDJJ IMPUESTOS", "ESTIMACION");
                                $return = check_word($parameterArr[$i]['fieldValue'], $allow_words);
                                if ($return) {
                                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                                } else {
                                    $Z2_cell_value = $parameterArr[$i]['fieldValue'];
                                }
                            }


                            /* CHECK ONE FOR ALL */
                            if ((bool) $X_cell_value || (bool) $Y2_cell_value || (bool) $Z2_cell_value) {
                                if (!(bool) $X_cell_value || !(bool) $Y2_cell_value || !(bool) $Z2_cell_value) {
                                    $code_error = "X.1";
                                    $result_error_input_value = $X_cell_value . "*" . $Y2_cell_value . "*" . $Z2_cell_value;
                                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $result_error_input_value);
                                }
                            }
                            break;
                    }
                }

                /*
                 * CANTIDAD_DE_EMPLEADOS
                 * El campo no puede estar vacío y debe contener caracteres numéricos mayores a Cero.
                 */
                if ($param_col == 28) {
                    if ($A_cell_value == "INCORPORACION") {
                        
                        $code_error = "AB.1";

                        /* AVERAGE AMOUNT */
                        //$average_amount = $average_amount_1 + $average_amount_2 + $average_amount_3;
                        $average_amount_1 = 0;
                        $average_amount_2 = 0;
                        $average_amount_3 = 0;

                        if (empty($parameterArr[$i]['fieldValue'])) {
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty." . $A_cell_value);
                        } else {
                            //Check Numeric Validation
                            $return = check_is_numeric($parameterArr[$i]['fieldValue']);
                            if ($return) {
                                $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            }

                            //Check Cero
                            if ($parameterArr[$i]['fieldValue'] == 0) {
                                $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
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
                if (in_array($param_col, $range)) {

                    switch ($param_col) {

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
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                }

                if ($param_col == 28) {
                    $code_error = "AB.2";
                    //Check for Empty
                    $return = check_for_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                }
            }


            /////////////////////////////////////////
            /*
             * 2. VALIDADORES PARTICULARES
             * 2.2. COLUMNA A - TIPO DE OPERACIÓN: “INCREMENTO TENENCIA ACCIONARIA”
             */


            if ($A_cell_value == "INCREMENTO TENENCIA ACCIONARIA") {

                $range = range(5, 28);
                if (in_array($param_col, $range)) {
                    
                    switch ($param_col) {
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
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                        }
                    }
                }
            }



            /////////////////////////////////////////
            /*
             * 2.6. COLUMNA A - TIPO DE OPERACIÓN: “FUSION”
             */


            if ($A_cell_value == "FUSION"){
                $range = range(5, 16);
                if (in_array($param_col, $range)) {
                    
                    switch ($param_col) {
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
                        
                    }
                    //Check for Empty
                    if ($A_cell_value != "DISMINUCION DE CAPITAL SOCIAL") {
                        $return = check_for_empty($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                        }
                    }
                }

                $range = range(18, 28);
                if (in_array($param_col, $range)) {
                    
                    switch ($param_col) {
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
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
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
                if (in_array($param_col, $range)) {
                    switch ($param_col) {
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
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                        }
                    }
                }



                /*
                 * MODALIDAD
                 * El campo no puede estar vacío y debe contener el siguientes parámetro:
                  SUSCRIPCIÓN
                 */
                if ($param_col == 33) {
                    $code_error = "AG.2";
                    if (empty($parameterArr[$i]['fieldValue'])) {
                        $result["error_input_value"] = $parameterArr[$i]['fieldValue'] . "empty";
                    }
                    //Value Validation
                    if (!empty($parameterArr[$i]['fieldValue'])) {
                        $allow_words = array("SUSCRIPCION");
                        $return = check_word($parameterArr[$i]['fieldValue'], $allow_words);
                        if ($return) {
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                        }
                    }
                }
            }
            
        }

        if (count(array_unique($C_array_value)) < count($C_array_value)) {
            $stack = array();
            $code_error = "VG.1";
            $result[] = return_error_array($code_error, "-", "");
        }
        
         
       # Support #27920
        if($this->idu==10)
            $result = array();


        /*
        var_dump($result);      
        exit(); */

        $this->data = $result;
    }

}
