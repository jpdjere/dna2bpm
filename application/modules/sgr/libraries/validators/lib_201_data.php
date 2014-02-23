<?php

class Lib_201_data extends MX_Controller {
    /* VALIDADOR ANEXO 12.5 */

    public function __construct($parameter) {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('sgr/tools');
        $this->load->model('sgr/sgr_model');

        /* PARTNER INFO */
        $model_06 = 'model_06';
        $this->load->Model($model_06);

        $model_201 = 'model_201';
        $this->load->Model($model_201);

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
        $order_number_array = array();
        $order_number_array_aporte = array();

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
                 * Nro A.4
                 * Detail: 
                  En caso de que se esté informando un retiro (Columna E), el número de Aporte debe estar previamente registrado en el Sistema o en el mismo archivo que se está importando, en cuyo caso debe corresponder a un Aporte (Columna D) y tener Fecha de Movimiento (Columna B) anterior a la Fecha de Movimiento (Columna B) del retiro informado.
                 * Nro A.5
                 * Detail: 
                  Para los casos en que se estén informando Retiros (Columna E), no puede darse que para un mismo número haya informada dos files en la que la Fecha de Movimiento (Columna B) sea la misma.
                 * Nro A.6
                 * Detail: 
                  Para los casos en que se estén informando Retiro de Rendimientos (Columna G), no puede darse que para un mismo número haya informada dos files en la que la Fecha de Movimiento (Columna B) sea la misma.

                 */
                if ($parameterArr[$i]['col'] == 1) {
                    $code_error = "A.1";
                    //empty field Validation                    
                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                        array_push($stack, $result);
                    } else {
                        $A_cell_value = $parameterArr[$i]['fieldValue'];
                        $return = check_decimal($parameterArr[$i]['fieldValue']);
                        if ($return) {
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
                    //empty field Validation
                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                        array_push($stack, $result);
                    }
                    //Check Date Validation
                    if (isset($parameterArr[$i]['fieldValue'])) {
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
                        $code_error = "C.3";
                        $C_cell_value = $parameterArr[$i]['fieldValue'];
                        $return = cuit_checker($parameterArr[$i]['fieldValue']);
                        if (!$return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        } else {
                            $partner_data = $this->$model_06->get_partner_period($parameterArr[$i]['fieldValue'], $this->session->userdata['period']);
                            if ($partner_data[5272] != 'B') {

                                $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                                array_push($stack, $result);
                            }

                            $buy = $this->$model_06->buy_shares($parameterArr[$i]['fieldValue'], 'B');
                            $sell = $this->$model_06->sell_shares($parameterArr[$i]['fieldValue'], 'B');
                            $balance = $buy - $sell;
                            if ($balance == 0) {
                                $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                                array_push($stack, $result);
                            }
                        }
                    }
                }

                /* APORTE
                 * Nro D.2
                 * Detail:
                 * Formato Numérico. Debe aceptar hasta 2 decimales.                 
                 */
                if ($parameterArr[$i]['col'] == 4) {
                    $code_error = "D.2";
                    if (isset($parameterArr[$i]['fieldValue'])) {
                        $D_cell_value = $parameterArr[$i]['fieldValue'];
                        $return = check_decimal($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }
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

                    if ($parameterArr[$i]['fieldValue'] != "") {
                        $code_error = "E.2";
                        $E_cell_value = $parameterArr[$i]['fieldValue'];
                        $return = check_decimal($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }

                        if ($D_cell_value != "") {
                            $code_error = "E.1";
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }
                    }
                }

                /* RETENCION_POR_CONTINGENTE
                 * Nro F.1
                 * Detail:
                 * Si la columna D está completa, esta debe estar vacía.
                 * Nro F.1
                 * Detail:
                 * Formato Numérico. Debe aceptar hasta 2 decimales.   
                 */
                if ($parameterArr[$i]['col'] == 6) {

                    $code_error = "F.2";
                    $F_cell_value = $parameterArr[$i]['fieldValue'];

                    if (isset($parameterArr[$i]['fieldValue'])) {
                        $return = check_decimal($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }

                        if ($D_cell_value != "") {
                            $code_error = "F.1";
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }
                    }


                    /*
                     * Nro D.1
                     * Detail:
                     * Si las Columnas E o F están completas, esta debe estar vacía.
                     */
                    if ($E_cell_value && $parameterArr[$i]['fieldValue'] && $D_cell_value) {
                        $code_error = "D.1";
                        $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                        array_push($stack, $result);
                    }
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

                    if ($parameterArr[$i]['fieldValue'] != "") {
                        $G_cell_value = $parameterArr[$i]['fieldValue'];
                        $return = check_decimal($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }

                        if ($D_cell_value != "") {
                            $code_error = "E.1";
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }
                    }

                    /*
                     * Nro C.2
                     * Detail: En caso de que se trate de un Retiro (Columna E) o un Retiro de Rendimientos (Columna G), 
                     * el campo debe estar vacío y el Sistema tomará el CUIT 
                     * registrado previamente en el mismo para el número de aporte informado. 
                     */

                    if ($E_cell_value || $G_cell_value && $C_cell_value) {
                        $code_error = "C.2";
                        $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                        array_push($stack, $result);
                    } else if (!$C_cell_value) {

                        var_dump("entre" . $parameterArr[$i]['row']);
                        $code_error = "C.1";
                        $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
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
                    C .
                            $return = check_is_numeric($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                        array_push($stack, $result);
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


                    $code_error = "A.4";
                    //Valida contra Mongo

                    $code_error = "A.5";
                    //Valida contra Mongo

                    $code_error = "A.6";
                    //Valida contra Mongo                    

                    /* En una misma fila no pueden estar completas a la vez los campos de las columnas D, E y G, sólo se debe permitir que esté completo uno de esos tres campos. */
                    $code_error = "VG.1";
                    $D_value = ($D_cell_value) ? 1 : 0;
                    $E_value = ($E_cell_value) ? 1 : 0;
                    $G_value = ($G_cell_value) ? 1 : 0;
                    $cols_count = array($D_value, $E_value, $G_value);

                    if (array_sum($cols_count) < 1) {
                        $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                        array_push($stack, $result);
                    }
                }
            } // END FOR LOOP->
        }




        foreach ($order_number_array_aporte as $order_number) {
            if (in_array($order_number, $order_number_array)) {
                $search_cuit = (array_keys($order_number_array, $order_number));
                $counter = count($search_cuit);
                if ($counter > 1) {
                    $code_error = "A.2";
                    $result = return_error_array($code_error, "-", $order_number . " Total de Veces: " . $counter);
                    array_push($stack, $result);
                }
            }
        }

        $check_consecutive = consecutive($order_number_array_aporte);
        if ($check_consecutive) {
            $code_error = "A.2";
            $result = return_error_array($code_error, "-", "Los valores en NUMERO_DE_APORTE no son consecutivos");
            array_push($stack, $result);
        }        
        $get_max_order_number = $this->$model_201->get_last_input_number($A_cell_value);       

        foreach ($order_number_array_aporte as $number) {
            if ($number <= $get_max_order_number) {
                $code_error = "A.2";
                $result = return_error_array($code_error, "-", "El número de aporte " . $number . " ya fue registrado en el sistema");
                array_push($stack, $result);
            }
        }

        //var_dump($stack);
        $this->data = $stack;
    }

}
