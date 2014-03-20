<?php

class Lib_201_data extends MX_Controller {
    /* VALIDADOR ANEXO 201 */

    public function __construct($parameter) {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('sgr/tools');
        $this->load->model('sgr/sgr_model');
        
        $this->period = $this->session->userdata['period'];
        
        /* PARTNER INFO */
        $model_06 = 'model_06';
        $this->load->Model($model_06);

        $model_anexo = 'model_201';
        $this->load->Model($model_anexo);
        
        /* UPDATE MONGO/DNA2 */
        $mysql_model_201 = "mysql_model_201";
        $this->load->Model($mysql_model_201);


        $this->$mysql_model_201->active_periods_dna2("201", $this->period);

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

        $this->$model_anexo->clear_tmp($insert_tmp);
        $input_num = array();



        for ($i = 1; $i <= $parameterArr[0]['count']; $i++) {
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
            for ($i = 0; $i <= count($parameterArr); $i++) {

                /* NUMERO_DE_APORTE
                 * Nro A.1
                 * Detail:
                 * Debe tener formato numérico, entero sin decimales.
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
                if ($parameterArr[$i]['col'] == 1) {
                    $insert_tmp = array();
                    $code_error = "A.1";
                    $A_cell_value = "";
                    $get_input_number = "";
                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                        array_push($stack, $result);
                    } else {
                        $A_cell_value = $parameterArr[$i]['fieldValue'];
                        $input_num[] = $A_cell_value;

                        $get_input_number = $this->$model_anexo->get_input_number($A_cell_value);
                        $return = check_is_numeric_no_decimal($parameterArr[$i]['fieldValue'], true);
                        if (!$return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
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
                if ($parameterArr[$i]['col'] == 2) {
                    $code_error = "B.1";
                    $B_cell_value = "";

                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                        array_push($stack, $result);
                    } else {
                        $B_cell_value = $parameterArr[$i]['fieldValue'];
                        $return = check_date_format($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }

                        $return = check_period($parameterArr[$i]['fieldValue'], $this->session->userdata['period']);
                        if ($return) {
                            $code_error = "B.2";
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
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
                if ($parameterArr[$i]['col'] == 3) {

                    if ($parameterArr[$i]['fieldValue'] != "") {
                        $code_error = "C.1";
                        $C_cell_value = $parameterArr[$i]['fieldValue'];
                        $return = cuit_checker($parameterArr[$i]['fieldValue']);
                        if (!$return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }
                    }
                }

                /* APORTE
                 * Nro D.2
                 * Detail:
                 * Formato Numérico. Debe aceptar hasta 2 decimales.                  
                 * CAMBIA EN EL INSTRUCTIVO
                 */
                if ($parameterArr[$i]['col'] == 4) {
                    $code_error = "D.1";
                    $D_cell_value = null;
                    if ($parameterArr[$i]['fieldValue'] != "") {
                        $D_cell_value = (int) $parameterArr[$i]['fieldValue'];
                        $return = check_decimal($parameterArr[$i]['fieldValue'], 2, true);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }

                        /* C.3 */
                        $lte_date = new MongoDate(strtotime(translate_for_mongo(($B_cell_value + 1))));


                        $balance = $this->$model_06->shares_active_left_until_date($C_cell_value, $lte_date);
                        if ($balance == 0) {
                            $code_error = "C.3";
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $C_cell_value);
                            array_push($stack, $result);
                        }

                        $insert_tmp['NUMERO_DE_APORTE'] = (int) $A_cell_value;
                        $insert_tmp['FECHA_MOVIMIENTO'] = $B_cell_value;
                        $insert_tmp['APORTE'] = $parameterArr[$i]['fieldValue'];
                        $this->$model_anexo->save_tmp($insert_tmp);
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
                if ($parameterArr[$i]['col'] == 5) {
                    $E_cell_value = null;
                    if ($parameterArr[$i]['fieldValue'] != "") {
                        $code_error = "E.2";
                        $E_cell_value = (int) $parameterArr[$i]['fieldValue'];
                        $b3_array[] = $A_cell_value . '*' . $B_cell_value;

                        $return = check_decimal($parameterArr[$i]['fieldValue'], 2, true);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }

                        if ($D_cell_value != "") {
                            $code_error = "E.1";
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }

                        $insert_tmp['NUMERO_DE_APORTE'] = (int) $A_cell_value;
                        $insert_tmp['FECHA_MOVIMIENTO'] = $B_cell_value;
                        $insert_tmp['RETIRO'] = $parameterArr[$i]['fieldValue'];
                        $this->$model_anexo->save_tmp($insert_tmp);
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
                if ($parameterArr[$i]['col'] == 6) {
                    $F_cell_value = null;



                    if ($E_cell_value != null && $parameterArr[$i]['fieldValue'] == "") {
                        $code_error = "F.3";
                        $return = check_for_empty($parameterArr[$i]['fieldValue']);
                        $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                        array_push($stack, $result);
                    }


                    $code_error = "F.4";
                    if ($parameterArr[$i]['fieldValue'] != "") {
                        $F_cell_value = (int) $parameterArr[$i]['fieldValue'];
                        $return = check_decimal($parameterArr[$i]['fieldValue'], 2, true);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }


                        if ($D_cell_value != null) {
                            $code_error = "F.1";
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }

                        if ($E_cell_value == null) {
                            $code_error = "F.2";
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
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
//                        $result = return_error_array($code_error, $parameterArr[$i]['row'], $E_cell_value . $parameterArr[$i]['fieldValue']);
//                        array_push($stack, $result);
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
                if ($parameterArr[$i]['col'] == 7) {

                    $code_error = "G.1";
                    $G_cell_value = null;
                    if ($parameterArr[$i]['fieldValue'] != "") {

                        $G_cell_value = (int) $parameterArr[$i]['fieldValue'];
                        $b4_array[] = $A_cell_value . '*' . $B_cell_value;


                        $return = check_decimal($parameterArr[$i]['fieldValue'], 2, true);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }

                        if ($D_cell_value != "") {
                            $code_error = "G.1";
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }

                        $insert_tmp['NUMERO_DE_APORTE'] = (int) $A_cell_value;
                        $insert_tmp['FECHA_MOVIMIENTO'] = $B_cell_value;
                        $insert_tmp['RETIRO_DE_RENDIMIENTOS'] = $parameterArr[$i]['fieldValue'];
                        $this->$model_anexo->save_tmp($insert_tmp);
                    }

                    /*
                     * Nro C.2
                     * Detail: En caso de que se trate de un Retiro (Columna E) o un Retiro de Rendimientos (Columna G), 
                     * el campo DEBE ESTAR VACÍO y el Sistema tomará el CUIT registrado previamente en el mismo para el número de aporte informado. 
                     */

                    if ($C_cell_value && ($E_cell_value || $G_cell_value)) {
                        $code_error = "C.2";
                        $result = return_error_array($code_error, $parameterArr[$i]['row'], $C_cell_value);
                        array_push($stack, $result);
                    }
                }

                /* ESPECIE
                 * Nro H.1 -> P.1
                 * Detail:
                 * Debe permitir informar cualquier cosa. Siempre deben estar completos..
                 */
                $range = range(8, 16);
                if (in_array($parameterArr[$i]['col'], $range)) {
                    $code_error = "HP.1";
                    //empty field Validation
                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                        array_push($stack, $result);
                    }
                }

                /* FECHA_ACTA
                 * Nro Q.1
                 * Detail:
                 * Debe tener formato numérico de cinco dígitos sin decimales.
                 */
                if ($parameterArr[$i]['col'] == 17) {
                    $code_error = "Q.1";
                    //empty field Validation
                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {


                        $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                        array_push($stack, $result);
                    } else {
                        $return = check_date_format($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }
                    }
                }

                /* NRO_ACTA
                 * Nro R.1
                 * Detail:
                 * Debe tener formato numérico de cinco dígitos sin decimales.
                 */
                if ($parameterArr[$i]['col'] == 18) {
                    $code_error = "R.1";
                    if ($parameterArr[$i]['fieldValue'] != "") {
                        $R_cell_value = (int) $parameterArr[$i]['fieldValue'];


                        $return = check_is_numeric_no_decimal($parameterArr[$i]['fieldValue'],true);
                        if (!$return || $R_cell_value < 1) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
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
                        $order_number_array_aporte[] = $A_cell_value;
                    }

                    /* En una misma fila no pueden estar completas a la vez los campos de las columnas D, E y G, sólo se debe permitir que esté completo uno de esos tres campos. */

                    $D_value = ($D_cell_value) ? 1 : 0;
                    $E_value = ($E_cell_value) ? 1 : 0;
                    $F_value = ($F_cell_value) ? 1 : 0;
                    $G_value = ($G_cell_value) ? 1 : 0;
                    $cols_count = array($D_value, $F_value, $E_value, $G_value);
                    if (array_sum($cols_count) < 1) {
                        $code_error = "VG.1";
                        $result = return_error_array($code_error, $parameterArr[$i]['row'], "No pueden estar vacias las D,E,F,G");
                        array_push($stack, $result);
                    }
                }
            } // END FOR LOOP->
        }


        $input_num_unique = array_unique($input_num);

        foreach ($input_num_unique as $number) {

            $number = (int) $number;
            /* MOVEMENT DATA */
            $get_historic_data = $this->$model_anexo->get_movement_recursive($number);
            $get_temp_data = $this->$model_anexo->get_tmp_movement_data($number);

            $sum_APORTE = array_sum(array($get_historic_data['APORTE'], $get_temp_data['APORTE']));
            $sum_RETIRO = array_sum(array($get_historic_data['RETIRO'], $get_temp_data['RETIRO']));


            /* A.2 */


            if ($get_historic_data['APORTE'] > 0) {
                $code_error = "A.2";
                $result = return_error_array($code_error, $parameterArr[$i]['row'], $number);
                array_push($stack, $result);
            }


            $get_last_input_number = $this->$model_anexo->get_last_input();
            if ($number < $get_last_input_number) {
                $code_error = "A.2";
                $result = return_error_array($code_error, $parameterArr[$i]['row'], $number . " No es correlativo al ultimo informado.");
                array_push($stack, $result);
            }


            /* A.3 */
            if ($get_temp_data['TOTAL'] > 1) {
                $code_error = "A.3";
                $result = return_error_array($code_error, $parameterArr[$i]['row'], $number);
                array_push($stack, $result);
            }

            /* A.4 */
            if ($get_temp_data['RETIRO'] > 0) {
                if ($sum_APORTE == 0) {
                    $code_error = "A.4";
                    $result = return_error_array($code_error, "", $get_temp_data['RETIRO']);
                    array_push($stack, $result);
                } else {
                    /* E.3 */
                    if ($sum_RETIRO > $sum_APORTE) {
                        $code_error = "E.3";
                        $result = return_error_array($code_error, "", "( Nro de Aporte " . $number . " Aporte: " . $sum_APORTE . " ) " . $sum_RETIRO);
                        array_push($stack, $result);
                    }
                }

                /* B.3 */
                $query_param = 'RETIRO';
                $get_retiros_tmp = $this->$model_anexo->get_retiros_tmp($number, $query_param);
                $retiros_arr = array();
                foreach ($get_retiros_tmp as $o) {
                    $date = $o;
                    $retiros_arr[] = date('Y-m-d', $date->sec);
                }

                foreach (repeatedElements($retiros_arr) as $arr) {
                    $code_error = "B.3";
                    $result = return_error_array($code_error, "", $arr['value']);
                    array_push($stack, $result);
                }

                /* B.4 */
                $query_param = 'RETIRO_DE_RENDIMIENTOS';
                $get_retiros_tmp = $this->$model_anexo->get_retiros_tmp($number, $query_param);
                $retiros_arr = array();
                foreach ($get_retiros_tmp as $o) {
                    $date = $o;
                    $retiros_arr[] = date('Y-m-d', $date->sec);
                }

                foreach (repeatedElements($retiros_arr) as $arr) {
                    $code_error = "B.4";
                    $result = return_error_array($code_error, "", $arr['value']);
                    array_push($stack, $result);
                }



                foreach ($get_retiros_tmp as $retiros) {
                    $aporte = $this->$model_anexo->get_aporte_tmp($number, $retiros);
                    $return_calc = calc_anexo_201($aporte, $get_historic_data, $number);
                    if ($return_calc) {
                        $code_error = "A.4";
                        $result = return_error_array($code_error, "", "[" . $query_param . "] " . $return_calc);
                        array_push($stack, $result);
                    }
                }
            }
        }
        // exit();
        $this->data = $stack;
    }

}
