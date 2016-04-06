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
        $result = array();
        $parameterArr = (array) $parameter;
        $cuota_arr = array();


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

            $param_col = (isset($parameterArr[$i]['col'])) ? $parameterArr[$i]['col'] : 0;

            /* NRO_GARANTIA
             * Nro A.1
             * Detail:
             * El Número de garantía debe estar informado en el sistema
             */

            if ($param_col == 1) {

                $code_error = "A.1";
                $A_cell_value = $parameterArr[$i]['fieldValue'];

                if (empty($parameterArr[$i]['fieldValue'])) {
                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                }

                //Valida contra Mongo
                $warranty_info = $this->$model_anexo->get_order_number_left($parameterArr[$i]['fieldValue']);
                if (!$warranty_info) {
                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                }
            }


            /* NUMERO_CUOTA_CUYO_VENC_MODIFICA
             * Nro B.1
             * Detail:
             * Una misma cuota de una garantía no puede figurar dos veces en el archivo importado.
             */

            if ($param_col == 2) {
                $code_error = "B.1";
                  if (empty($parameterArr[$i]['fieldValue'])) {
                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                } else {
                    $cuota_arr[] = $parameterArr[$i]['fieldValue'] . "*" . $A_cell_value;
                    $B_cell_value = (int) $parameterArr[$i]['fieldValue'];

                    $return = check_is_numeric_no_decimal($parameterArr[$i]['fieldValue'], true);
                    if (!$return || $B_cell_value < 1) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                }
            }

            /* FECHA_VENC_CUOTA
             * Nro C.1
             * Detail:
             * Formato numérico de cinco dígitos sin decimales. Debe ser posterior a la fecha de emisión de la garantía informada en la Columna C del Anexo 12.
             */
            if ($param_col == 3) {
                $code_error = "C.1";
               if (empty($parameterArr[$i]['fieldValue'])) {
                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                } else {
                    $return = check_date_format($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
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
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
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

            if ($param_col == 4) {
                $code_error = "D.1";
               if (empty($parameterArr[$i]['fieldValue'])) {
                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                } else {
                    $D_cell_value = $parameterArr[$i]['fieldValue'];
                    $return = check_date_format($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }

                    /* PERIOD */
                    $return = check_period_and_later($parameterArr[$i]['fieldValue'], $this->session->userdata['period']);
                    if ($return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                }
            }

            /* MONTO_CUOTA
             * Nro E.1
             * Detail:
             * Formato numérico. Aceptar hasta dos decimales.
             */

            if ($param_col == 5) {
                $code_error = "E.1";
              if (empty($parameterArr[$i]['fieldValue'])) {
                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                } else {
                    $E_cell_value = (int) $parameterArr[$i]['fieldValue'];
                    $return = check_decimal($parameterArr[$i]['fieldValue'], 2, true);
                    if ($return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                }
            }

            /* SALDO_AL_VENCIMIENTO
             * Nro F.1
             * Detail:
             * Formato numérico. Aceptar hasta dos decimales. El monto debe ser inferior al registrado en como Monto de Garantí Otorgada del Anexo 12.';
             */

            if ($param_col == 6) {
                $code_error = "F.1";
                
                if (empty($parameterArr[$i]['fieldValue']) && $parameterArr[$i]['fieldValue'] != 0) {
                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                } else {
                    $F_cell_value = (float) $parameterArr[$i]['fieldValue'];
                    $return = check_decimal($parameterArr[$i]['fieldValue'], 2, true);
                    if ($return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }


                    /* F.2 */
                    foreach ($warranty_info as $order_number) {
                        $amount_warranty = (float) $order_number[5218];
                        $currency = $order_number['5219'][0];
                        $origin = $order_number['5215'];
                        $new_expiry = $D_cell_value - 1;


                        if ($currency == 2) {
                            $dollar_quotation_origin = $this->sgr_model->get_dollar_quotation(translate_date_xls($origin));
                            $dollar_quotation_new_expiry = $this->sgr_model->get_dollar_quotation($new_expiry);
                            //$dollar_quotation_period = $this->sgr_model->get_dollar_quotation_period();

                            $new_dollar_value = ($amount_warranty / $dollar_quotation_origin) * $dollar_quotation_new_expiry;

                            $a = (int) $new_dollar_value;
                            $b = (int) $F_cell_value;

                            $fix_ten_cents = fix_ten_cents($a, $b);

                            if ($fix_ten_cents) {
                                $code_error = "F.2.B";
                                $result[] = return_error_array($code_error, $parameterArr[$i]['row'], money_format_custom($F_cell_value) . ' Monto disponible para el Nro. Orden  = ' . $order_number[5214] . '  (' . $amount_warranty . '/' . $dollar_quotation_origin . '*' . $dollar_quotation_new_expiry . ' = ' . money_format_custom($new_dollar_value) . ' )');
                            }
                        } else {
                            if ($F_cell_value > $amount_warranty) {
                                $code_error = "F.2.A";
                                $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue'] . " (" . $order_number[5218] . ")");
                            }
                        }
                    }
                }

                //Valida contra Mongo
            }
        } // END FOR LOOP->


        /* EXTRA VALIDATION B.2 */
        foreach (repeatedElements($cuota_arr) as $arr) {
            $code_error = "B.2";
            list($cuota, $warranty) = explode('*', $arr['value']);
            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "Cuota Repetida " . $cuota . " para la Garantía " . $warranty);
        }


        /*debug($result);
        exit();*/
        $this->data = $result;
    }

}
