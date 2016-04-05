<?php

class Lib_201_data extends MX_Controller {
    /* VALIDADOR ANEXO 201 */

    public function __construct($parameter) {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('sgr/tools');
        $this->load->model('sgr/sgr_model');


        /* PARTNER INFO */
        $this->load->Model('model_06');
        $this->load->Model('model_201');


        $sgrArr = $this->sgr_model->get_sgr();
        foreach ($sgrArr as $sgr) {

            $this->sgr_id = (float) $sgr['id'];
            $this->sgr_nombre = $sgr['1693'];
            $this->sgr_cuit = $sgr['1695'];
        }

        /* Vars 
         * 
         * $parameters =  
         * $parameterArr[0]['fieldValue'] 
         * $parameterArr[0]['row'] 
         * $parameterArr[0]['col']
         * $parameterArr[0]['count']
         * 
         */


        $result = array();
        $parameterArr = (array) $parameter;

        $this->model_201->clear_tmp($insert_tmp);
        $input_num = array();
        $output_num = array();
        $array_cuit = array();

        //for ($j = 1; $j <= $parameterArr[0]['count']; $j++) {


        /**
         * BASIC VALIDATION
         * 
         * @param 
         * @type PHP
         * @name ...
         * @author Diego             
         * @example 
         * NUMERO_DE_APORTE	
         * FECHA_MOVIMIENTO	
         * CUIT_PROTECTOR	
         * APORTE	
         * RETIRO	
         * RETENCION_POR_CONTINGENTE	
         * RETIRO_DE_RENDIMIENTOS	
         * ESPECIE	
         * TITULAR_ORIG	
         * NRO_CTA_OR	
         * ENTIDAD_OR	
         * ENT_DEP_OR	
         * TITULAR_DEST	
         * NRO_DEST	
         * ENTIDAD_DEST	
         * ENT_DEP_DEST	
         * FECHA_ACTA	
         * NRO_ACTA
         * */
        $p = 0;
        $q = 0;
        for ($i = 0; $i <= count($parameterArr); $i++) {
            //var_dump($parameterArr[$i]);
            $param_col = (isset($parameterArr[$i]['col'])) ? $parameterArr[$i]['col'] : 0;
            //echo '$param_col:'.$param_col.'</br>';
            //var_dump($param_col,$parameterArr[$i]['row']);

            /* NUMERO_DE_APORTE
             * Nro A.1
             * Detail:
             * Debe tener formato numérico mayor a cero, entero, sin decimales.
             * Nro A.3
             * Detail: 
              En un mismo archivo no se puede repetir el mismo número para los casos en que se estén informando Aportes (Columna D).
             * Nro A.5
             * Detail: 
              Para los casos en que se estén informando Retiros (Columna E), no puede darse que para un mismo número haya informada dos files en la que la Fecha de Movimiento (Columna B) sea la misma.
             * Nro A.6
             * Detail: 
              Para los casos en que se estén informando Retiro de Rendimientos (Columna G), no puede darse que para un mismo número haya informada dos files en la que la Fecha de Movimiento (Columna B) sea la misma.

             */
            if ($param_col == 1) {

                $code_error = "A.1";
                $A_cell_value = "";
                $get_input_number = "";
                $return = check_empty($parameterArr[$i]['fieldValue']);
                if ($return) {
                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                } else {
                    $A_cell_value = $parameterArr[$i]['fieldValue'];
                    $get_input_number = $this->model_201->get_input_number($A_cell_value);
                    $exist_input_number = $this->model_201->exist_input_number($A_cell_value);

                    $return = check_is_numeric_no_decimal($parameterArr[$i]['fieldValue'], true);
                    if (!$return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                }
            }

            /* FECHA_MOVIMIENTO
             * Nro B.1
             * Detail:
             * Debe tener formato numérico de cinco dígitos sin decimales.
             * Nro B.2
             * Detail:
              Debe verificar que todas las fechas informadas se encuentren dentro del período que se está importando.

             */
            if ($param_col == 2) {

                $code_error = "B.1";
                $B_cell_value = "";

                $return = check_empty($parameterArr[$i]['fieldValue']);
                if ($return) {
                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                } else {
                    $B_cell_value = $parameterArr[$i]['fieldValue'];
                    $return = check_date_format($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }

                    $return = check_period($parameterArr[$i]['fieldValue'], $this->session->userdata['period']);
                    if ($return) {
                        $code_error = "B.2";
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                }
            }

            /* CUIT_PROTECTOR
             * Nro C.1
             * Detail:
             * Debe tener 11 dígitos sin guiones.                
             * Nro C.3
             * Detail:
              En caso de que se trate de un Aporte (Columna D), debe verificar que el CUIT pertenece a un Socio Protector incorporado como Socio B y con Tenencia de Acciones positivas.

             */
            if ($param_col == 3) {

                $C_cell_value = "";

                if ($parameterArr[$i]['fieldValue'] != "") {

                    $array_cuit[] = $parameterArr[$i]['fieldValue'] . "*" . $B_cell_value;

                    $code_error = "C.1";
                    $C_cell_value = $parameterArr[$i]['fieldValue'];
                    $return = ($C_cell_value == "22222222222")? : cuit_checker($parameterArr[$i]['fieldValue']);
                    if (!$return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                } else {
                    $z = $i + 1;

                    if (($parameterArr[$z]['fieldValue']) != "") {
                        $code_error = "C.1";
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                }
            }

            /* APORTE
             * Nro D.2
             * Detail:
             * Formato Numérico. Debe aceptar hasta 2 decimales.                  
             * CAMBIA EN EL INSTRUCTIVO
             */
            if ($param_col == 4) {
                $code_error = "D.1";
                $D_cell_value = null;
                if ($parameterArr[$i]['fieldValue'] != "") {
                    $D_cell_value = (int) $parameterArr[$i]['fieldValue'];
                    $return = check_decimal($parameterArr[$i]['fieldValue'], 2, true);
                    if ($return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }

                    /* C.3.1 */
                    $lte_date = new MongoDate(strtotime(translate_for_mongo(($B_cell_value))));

                    /* FRE CHECK CUIT */
                    if ($this->session->userdata['fre_session']) {
                        $balance = $this->model_06->shares_active_left_until_date_fre($C_cell_value, $lte_date);
                        if ($balance > 1) {
                            $code_error = "C.3.2";
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $C_cell_value);
                        }
                    } else {
                        /* NO FRE */
                        $balance = $this->model_06->shares_active_left_until_date($C_cell_value, $lte_date);

                        if ($balance == 0) {
                            $code_error = "C.3.1";
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $C_cell_value);
                        }
                    }

                   

                    $insert_tmp = array();
                    $insert_tmp['NUMERO_DE_APORTE'] = (int) $A_cell_value;
                    $insert_tmp['FECHA_MOVIMIENTO'] = $B_cell_value;
                    $insert_tmp['APORTE'] = $parameterArr[$i]['fieldValue'];
                    $this->model_201->save_tmp($insert_tmp);
                }
            }

            /* RETIRO
             * Nro E.1
             * Detail:
             * Si la columna D está completa, esta debe estar vacía.
             * Nro E.2
             * Detail:
             * Formato Numérico. Debe aceptar hasta 2 decimales.                  
             */
            if ($param_col == 5) {

                $C2_is_true = false;
                $E_cell_value = null;
                if ($parameterArr[$i]['fieldValue'] != "") {
                    $C2_is_true = true;
                    $code_error = "E.2";
                    $E_cell_value = (int) $parameterArr[$i]['fieldValue'];
                    $b3_array[] = $A_cell_value . '*' . $B_cell_value;

                    $return = check_decimal($parameterArr[$i]['fieldValue'], 2, true);
                    if ($return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }

                    if ($D_cell_value != "") {
                        $code_error = "E.1";
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }


                    /* A.4 */
                    if (!$exist_input_number) {
                        $code_error = "A.4";
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $A_cell_value);
                    } else {
                        $output_num[] = $A_cell_value;
                    }


                    $insert_tmp = array();
                    $insert_tmp['NUMERO_DE_APORTE'] = (int) $A_cell_value;
                    $insert_tmp['FECHA_MOVIMIENTO'] = $B_cell_value;
                    $insert_tmp['RETIRO'] = $parameterArr[$i]['fieldValue'];
                    $this->model_201->save_tmp($insert_tmp);
                }
            }

            /* RETENCION_POR_CONTINGENTE
             * Nro F.1
             * Detail:
              Si la columna D está completa, esta debe estar vacía.
             * Nro F.2
             * Detail:
              Si la Columna E está vacía, esta debe estar vacía.
             * Nro F.3
             * Detail:
              Si la Columna E está completa, esta debe estar completa.
             * Nro F.4
             * Detail:
              De estar completa, debe tomar Formato Numérico mayor o igual a cero y  aceptar hasta 2 decimales.
             */
            if ($param_col == 6) {
                $F_cell_value = null;



                if ($E_cell_value != null && $parameterArr[$i]['fieldValue'] == "") {
                    $code_error = "F.3";
                    $return = check_for_empty($parameterArr[$i]['fieldValue']);
                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                }


                $code_error = "F.4";
                if ($parameterArr[$i]['fieldValue'] != "") {
                    $F_cell_value = (int) $parameterArr[$i]['fieldValue'];
                    $return = check_decimal($parameterArr[$i]['fieldValue'], 2, true);
                    if ($return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }


                    if ($D_cell_value != null) {
                        $code_error = "F.1";
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }

                    if ($E_cell_value == null) {
                        $code_error = "F.2";
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                }




                /*
                 * Nro D.1
                 * Detail:
                 * Si las Columnas E o F están completas, esta debe estar vacía.
                 */
//                    if ($D_cell_value) {
//                        if($E_cell_value || $F_cell_value){
//                        $code_error = "D.1";
//                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $E_cell_value . $parameterArr[$i]['fieldValue']);
//                        
//                        }
//                    }
            }

            /* RETIRO_DE_RENDIMIENTOS
             * Nro G.1
             * Detail:                 
              Si la columna D está completa, esta debe estar vacía.
             * Nro G.2
             * Detail:
             * De estar completa, debe tomar Formato Numérico mayor a cero y aceptar hasta 2 decimales.
             */
            if ($param_col == 7) {

                $code_error = "G.1";
                $G_cell_value = null;
                if ($parameterArr[$i]['fieldValue'] != "") {
                    $C2_is_true = true;
                    $G_cell_value = (int) $parameterArr[$i]['fieldValue'];
                    $b4_array[] = $A_cell_value . '*' . $B_cell_value;


                    $return = check_decimal($parameterArr[$i]['fieldValue'], 2, true);
                    if ($return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }


//                        if (!isset($exist_input_number)) {
//                            $code_error = "A.5";
//                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $A_cell_value);                            
//                        }

                    if ($D_cell_value != "") {
                        $code_error = "G.1";
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }

                    $insert_tmp = array();
                    $insert_tmp['NUMERO_DE_APORTE'] = (int) $A_cell_value;
                    $insert_tmp['FECHA_MOVIMIENTO'] = $B_cell_value;
                    $insert_tmp['RETIRO_DE_RENDIMIENTOS'] = $parameterArr[$i]['fieldValue'];
                    $this->model_201->save_tmp($insert_tmp);
                }

                /*
                 * Nro C.2
                 * Detail: En caso de que se trate de un Retiro (Columna E) o un Retiro de Rendimientos (Columna G), 
                 * el campo DEBE ESTAR VACÍO y el Sistema tomará el CUIT registrado previamente en el mismo para el número de aporte informado. 
                 */
                $code_error = "C.2";

                if ($C_cell_value) {
                    if ($C2_is_true) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $C_cell_value);
                    }
                }


//                    if ($C_cell_value && $C2_is_true) {
//                      
//                    } else {
//                        if (!$exist_input_number) {
//                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $C_cell_value);
//                            
//                        }
//                    }
            }

            /* ESPECIE
             * Nro H.1 -> P.1
             * Detail:
             * Debe permitir informar cualquier cosa. Siempre deben estar completos..
             */
            $range = range(8, 16);
            if (in_array($param_col, $range)) {

                $pyear = get_reference_year($this->session->userdata['period']);

                if (isset($pyear)) {
                    $code_error = "HP.2";

                    $return = check_for_empty($parameterArr[$i]['fieldValue']);
                    if ($return)
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                } else {
                    $code_error = "HP.1";

                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                    }
                }
            }

            /* FECHA_ACTA
             * Nro Q.1
             * Detail:
             * Debe tener formato numérico de cinco dígitos sin decimales.
             */
            if ($param_col == 17) {

                $pyear = get_reference_year($this->session->userdata['period']);

                if (isset($pyear)) {
                    $code_error = "Q.2";

                    $return = check_for_empty($parameterArr[$i]['fieldValue']);
                    if ($return)
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                } else {

                    $code_error = "Q.1";
                    //empty field Validation
                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                    } else {
                        $return = check_date_format($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                        }
                    }
                }
            }

            /* NRO_ACTA
             * Nro R.1
             * Detail:
             * Debe tener formato numérico de cinco dígitos sin decimales.
             */
            if ($param_col == 18) {
                $code_error = "R.1";
                if ($parameterArr[$i]['fieldValue'] != "") {
                    $R_cell_value = (int) $parameterArr[$i]['fieldValue'];


                    $return = check_is_numeric_no_decimal($parameterArr[$i]['fieldValue'], true);
                    if (!$return || $R_cell_value < 1) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                }

                /* Nro A.2
                 * Detail: 
                 * Si lo que se está informando es un Aporte (Columna D), 
                 * debe validar con los movimientos históricos que están cargados en el Sistema que el 
                 * número informado no exista y sea correlativo al último informado. 
                 */
                $code_error = "A.2";
                $order_number_array[] = $A_cell_value;
                if ($D_cell_value) {
                    $input_num[] = $A_cell_value;
                }

                /* En una misma fila no pueden estar completas a la vez los campos de las columnas D, E y G, sólo se debe permitir que esté completo uno de esos tres campos. */

                $D_value = ($D_cell_value) ? 1 : 0;
                $E_value = ($E_cell_value) ? 1 : 0;
                $F_value = ($F_cell_value) ? 1 : 0;
                $G_value = ($G_cell_value) ? 1 : 0;
                $cols_count = array($D_value, $F_value, $E_value, $G_value);
                if (array_sum($cols_count) < 1) {
                    $code_error = "VG.1";
                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "No pueden estar vacias las D,E,F,G");
                }
            }
        } // END FOR LOOP->
        //}






        /* INPUT NAMES *********************************************************************************** */
        $input_num_unique = array_unique($input_num);
        $get_min = ($input_num_unique) ? (int) @min($input_num_unique) : "";


        foreach ($input_num_unique as $number) {
            $number = (int) $number;
            /* MOVEMENT DATA */
            $get_historic_data = $this->model_201->get_movement_recursive($number);
            $get_temp_data = $this->model_201->get_tmp_movement_data($number);

            $sum_APORTE = array_sum(array($get_historic_data['APORTE'], $get_temp_data['APORTE']));
            $sum_RETIRO = array_sum(array($get_historic_data['RETIRO'], $get_temp_data['RETIRO']));


            /* A.2 */

            if ($get_historic_data['APORTE'] > 0) {
                $code_error = "A.2";
                $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $number);
            }


            $get_last_input_number = $this->model_201->get_last_input();


            $check_consecutive_array = array($get_last_input_number, $get_min);
            $check_consecutive = check_consecutive_values($check_consecutive_array);
            $code_error = "A.2";

            if (!$check_consecutive) {
                $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $number . " No es correlativo al ultimo registrado.");
            }



            /* A.3 */
            if ($get_temp_data['TOTAL'] > 1) {
                $code_error = "A.3";
                $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $number);
            }

            /* A.4 */
            if ($get_temp_data['RETIRO'] > 0) {
                if ($sum_APORTE == 0) {
                    $code_error = "A.4";
                    $result[] = return_error_array($code_error, "", $get_temp_data['RETIRO']);
                } else {
                    /* E.3 */
                    if ((int) $sum_RETIRO > (int) $sum_APORTE) {
                        $code_error = "E.3";
                        $result[] = return_error_array($code_error, "", "( Nro de Aporte " . $number . " Aporte: " . $sum_APORTE . " ) " . $sum_RETIRO);
                    }
                }

                /* B.3 */
                $query_param = 'RETIRO';
                $get_retiros_tmp = $this->model_201->get_retiros_tmp($number, $query_param);
                $retiros_arr = array();
                foreach ($get_retiros_tmp as $o) {
                    $date = $o;
                    $retiros_arr[] = date('Y-m-d', $date->sec);
                }

                foreach (repeatedElements($retiros_arr) as $arr) {
                    $code_error = "B.3";
                    $result[] = return_error_array($code_error, "--", $arr['value']);
                }

                /* B.4 */
                $query_param = 'RETIRO_DE_RENDIMIENTOS';
                $get_retiros_tmp = $this->model_201->get_retiros_tmp($number, $query_param);
                $retiros_arr = array();
                foreach ($get_retiros_tmp as $o) {
                    $date = $o;
                    $retiros_arr[] = date('Y-m-d', $date->sec);
                }

                foreach (repeatedElements($retiros_arr) as $arr) {
                    $code_error = "B.4";
                    $result[] = return_error_array($code_error, "", $arr['value']);
                }

                foreach ($get_retiros_tmp as $retiros) {
                    $aporte = $this->model_201->get_aporte_tmp($number, $retiros);
                    $return_calc = calc_anexo_201($aporte, $get_historic_data, $number);
                    if ($return_calc) {
                        $code_error = "A.5";
                        $result[] = return_error_array($code_error, "", "[" . $query_param . "] " . $return_calc);
                    }
                }
            }
        }


        /* OUTPUT NAMES ********************************************************************************** */
        $output_num_unique = array_unique($output_num);

        foreach ($output_num_unique as $number) {

            $number = (int) $number;
            /* MOVEMENT DATA */
            $get_historic_data = $this->model_201->get_movement_recursive($number);
            $get_temp_data = $this->model_201->get_tmp_movement_data($number);

            $sum_APORTE = array_sum(array($get_historic_data['APORTE'], $get_temp_data['APORTE']));
            $sum_RETIRO = array_sum(array($get_historic_data['RETIRO'], $get_temp_data['RETIRO']));


            /* A.4 */

            if ($get_temp_data['RETIRO'] > 0) {
                if ($sum_APORTE == 0) {
                    $code_error = "A.4";
                    $result[] = return_error_array($code_error, "", $get_temp_data['RETIRO']);
                } else {

                    /* E.3 */
                    if ((int) $sum_RETIRO > (int) $sum_APORTE) {
                        $code_error = "E.3";
                        $result[] = return_error_array($code_error, "", "( Nro de Aporte " . $number . " Aporte: " . $sum_APORTE . " ) " . $sum_RETIRO);
                    }
                }

                /* B.3 */
                $query_param = 'RETIRO';
                $get_retiros_tmp = $this->model_201->get_retiros_tmp($number, $query_param);
                $retiros_arr = array();
                foreach ($get_retiros_tmp as $o) {
                    $date = $o;
                    $retiros_arr[] = date('Y-m-d', $date->sec);
                }




                foreach (repeatedElements($retiros_arr) as $arr) {
                    $code_error = "B.3";
                    $result[] = return_error_array($code_error, "-", $arr['value']);
                }

                /* B.4 */
                $query_param = 'RETIRO_DE_RENDIMIENTOS';
                $get_retiros_tmp = $this->model_201->get_retiros_tmp($number, $query_param);
                $retiros_arr = array();
                foreach ($get_retiros_tmp as $o) {
                    $date = $o;
                    $retiros_arr[] = date('Y-m-d', $date->sec);
                }

                foreach (repeatedElements($retiros_arr) as $arr) {
                    $code_error = "B.4";
                    $result[] = return_error_array($code_error, "", $arr['value']);
                }


                foreach ($get_retiros_tmp as $retiros) {
                    $aporte = $this->model_201->get_aporte_tmp($number, $retiros);
                    $return_calc = calc_anexo_201($aporte, $get_historic_data, $number);
                    if ($return_calc) {
                        $code_error = "A.5";
                        $result[] = return_error_array($code_error, "", "[" . $query_param . "] " . $return_calc);
                    }
                }
            }
        }

        /* B.5 */
        $res = duplicate_in_array($array_cuit);
        foreach (array_unique($res) as $v) {
            list($rCuit, $rDate) = explode("*", $v);
            $code_error = "B.5";
            $result[] = return_error_array($code_error, "N/A", $rCuit . " / " . $rDate);
        }



        if (!check_consecutive_values($input_num_unique)) {
            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "No son correlativos entre si.");
        }

        //debug($result);        exit();
        $this->data = $result;
    }

}
