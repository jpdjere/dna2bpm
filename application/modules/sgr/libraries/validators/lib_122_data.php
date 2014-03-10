<?php

class Lib_122_data extends MX_Controller {
    /* VALIDADOR ANEXO 12 */

    public function __construct($parameter) {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('sgr/tools');
        $this->load->model('sgr/sgr_model');

        $model_anexo = "model_12";
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
        $cuota_arr = array();

        for ($i = 1; $i <= $parameterArr[0]['count']; $i++) {

            /**
             * BASIC VALIDATION
             * 
             * @param 
             * @type PHP
             * @name ...
             * @author Diego
             *
             * @example NRO_GARANTIA	NUMERO_CUOTA_CUYO_VENC_MODIFICA	FECHA_VENC_CUOTA FECHA_VENC_CUOTA_NUEVA	MONTO_CUOTA SALDO_AL_VENCIMIENTO
             * */
            for ($i = 0; $i <= count($parameterArr); $i++) {

                /* NRO_GARANTIA
                 * Nro A.1
                 * Detail:
                 * El Número de garantía debe estar informado en el sistema
                 */

                if ($parameterArr[$i]['col'] == 1) {

                    $code_error = "A.1";
                    $A_cell_value = $parameterArr[$i]['fieldValue'];
                    
                    //empty field Validation
                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                        array_push($stack, $result);
                    }

                    //Valida contra Mongo
                    $warranty_info = $this->$model_anexo->get_order_number($parameterArr[$i]['fieldValue']);
                    if (!$warranty_info) {
                        $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                        array_push($stack, $result);
                    }
                }


                /* NUMERO_CUOTA_CUYO_VENC_MODIFICA
                 * Nro B.1
                 * Detail:
                 * Una misma cuota de una garantía no puede figurar dos veces en el archivo importado.
                 */

                if ($parameterArr[$i]['col'] == 2) {
                    $code_error = "B.1";
                    //empty field Validation
                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                        array_push($stack, $result);
                    } else {
                        $cuota_arr[] = $parameterArr[$i]['fieldValue'] . "*" . $A_cell_value;
                        $B_cell_value = (int) $parameterArr[$i]['fieldValue'];

                        $return = check_is_numeric_no_decimal($parameterArr[$i]['fieldValue']);
                        if ($return || $B_cell_value < 1) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }
                    }
                }

                /* FECHA_VENC_CUOTA
                 * Nro C.1
                 * Detail:
                 * Formato numérico de cinco dígitos sin decimales. Debe ser posterior a la fecha de emisión de la garantía informada en la Columna C del Anexo 12.
                 */
                if ($parameterArr[$i]['col'] == 3) {
                    $code_error = "C.1";
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
                        /* C.2 */
                        $C_cell_date_format = strftime("%Y-%m-%d", mktime(0, 0, 0, 1, -1 + $parameterArr[$i]['fieldValue'], 1900));

                        foreach ($warranty_info as $nro_orden) {
                            $datetime1 = new DateTime($nro_orden['5215']);
                            $datetime2 = new DateTime($C_cell_date_format);
                            $interval = $datetime1->diff($datetime2);
                            $result_dates = (int) $interval->format('%R%a');

                            if ($result_dates < 1) {

                                $code_error = "C.2";
                                $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                                array_push($stack, $result);
                            }
                        }
                    }

                    //Valida contra Mongo
                }

                /* FECHA_VENC_CUOTA_NUEVA
                 * Nro D.1
                 * Detail:
                 * Formato numérico de cinco dígitos sin decimales. La fecha debe encontrarse dentro del período que se está informando.
                 */

                if ($parameterArr[$i]['col'] == 4) {
                    $code_error = "D.1";
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

                        /* PERIOD */
                        $return = check_period($parameterArr[$i]['fieldValue'], $this->session->userdata['period']);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }
                    }
                }

                /* MONTO_CUOTA
                 * Nro E.1
                 * Detail:
                 * Formato numérico. Aceptar hasta dos decimales.
                 */

                if ($parameterArr[$i]['col'] == 5) {
                    $code_error = "E.1";
                    //empty field Validation
                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                        array_push($stack, $result);
                    } else {
                        $E_cell_value = (int) $parameterArr[$i]['fieldValue'];
                        $return = check_decimal($parameterArr[$i]['fieldValue'],2,true);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }
                    }
                }

                /* SALDO_AL_VENCIMIENTO
                 * Nro F.1
                 * Detail:
                 * Formato numérico. Aceptar hasta dos decimales. El monto debe ser inferior al registrado en como Monto de Garantí Otorgada del Anexo 12.';
                 */

                if ($parameterArr[$i]['col'] == 6) {
                    $code_error = "F.1";
                    //empty field Validation
                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                        array_push($stack, $result);
                    } else {
                        $F_cell_value = (float) $parameterArr[$i]['fieldValue'];
                        $return = check_decimal($parameterArr[$i]['fieldValue'], 2, true);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }


                        /* F.2 */
                        foreach ($warranty_info as $order_number) {
                            $amount_warranty = (float) $order_number[5218];
                            if ($F_cell_value > $amount_warranty) {
                                $code_error = "F.2";
                                $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue'] . " (" . $order_number[5218] . ")");
                                array_push($stack, $result);
                            }
                        }
                    }

                    //Valida contra Mongo
                }
            } // END FOR LOOP->
        }

        /* EXTRA VALIDATION B.2 */
        foreach (repeatedElements($cuota_arr) as $arr) {
            $code_error = "B.2";
            list($cuota, $warranty) = explode('*', $arr['value']);
            $result = return_error_array($code_error, $parameterArr[$i]['row'], "Cuota Repetida " . $cuota . " para la Garantía " . $warranty);
            array_push($stack, $result);
        }


        //exit();
        $this->data = $stack;
    }

}

