<?php

class Lib_121_data extends MX_Controller {
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
        $b1_array = array();
        $d2_nro_array = array();
        $d2_sum = 0;
        $e2_sum = 0;

        for ($i = 1; $i <= $parameterArr[0]['count']; $i++) {

            /**
             * BASIC VALIDATION
             * 
             * @param 
             * @type PHP
             * @name ...
             * @author Diego
             *
             * @example NRO  CUIT_PARTICIPE	ORIGEN	TIPO	IMPORTE	MONEDA	LIBRADOR_NOMBRE	LIBRADOR_CUIT	NRO_OPERACION_BOLSA	ACREEDOR	CUIT_ACREEDOR	IMPORTE_CRED_GARANT	MONEDA_CRED_GARANT	TASA	PUNTOS_ADIC_CRED_GARANT	PLAZO	GRACIA	PERIODICIDAD	SISTEMA	DESTINO_CREDITO
             * */
            for ($i = 0; $i <= count($parameterArr); $i++) {

                $param_col = (isset($parameterArr[$i]['col'])) ? $parameterArr[$i]['col'] : 0;

                /* NRO_ORDEN
                 * Nro A.1
                 * Detail:
                 * El Número se debe corresponder con alguna de las Garantías informadas mediante el Anexo 12 del mismo período 
                 * y apara la cual figura que el Sistema de Amortización o la periodicidad de los pagos fue informado como “OTRO” 
                 * (Columnas R y S del Anexo 12).
                 */

                if ($param_col == 1) {

                    $code_error = "A.1";
                    $A_cell_value = $parameterArr[$i]['fieldValue'];
                    //empty field Validation
                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $A_cell_value = false;
                        $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                        array_push($stack, $result);
                    }

                    $warranty_info = $this->$model_anexo->get_order_number_left($parameterArr[$i]['fieldValue']);

                    if ($warranty_info) {
                        $warrantyArr = array($warranty_info[0]['5227'][0]);
                        if (!in_array('04', $warrantyArr)) {
                            $A_cell_value = false;
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }
                    } else {
                        // warranty_info no trae 
                        $A_cell_value = false;
                        $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                        array_push($stack, $result);
                    }
                }

                /* NRO_CUOTA
                 * Nro B.1
                 * Detail:
                 * Por lo menos debe tener dos cuotas. Si tiene sólo una está mal. La numeración debe empezar en 1 y ser correlativa dentro de cada garantía.
                 */

                if ($param_col == 2) {
                    $code_error = "B.1";
                    //empty field Validation
                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {


                        $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                        array_push($stack, $result);
                    }



                    if ($parameterArr[0]['count'] < 3) {

                        $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                        array_push($stack, $result);
                    }

                    $b1_array[$A_cell_value][] = $parameterArr[$i]['fieldValue'];
                }

                /* VENCIMIENTO
                 * Nro C.1
                 * Detail:
                 * Formato numérico de cinco dígitos sin decimales. Debe ser posterior a la fecha de emisión de la garantía informada en la Columna C del Anexo 12.
                 */
                if ($param_col == 3) {
                    $code_error = "C.1";
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
                    }

                    //vto. de la cuota sea posterior a la de emisión de la garantía
                    if (isset($parameterArr[$i - 2]['fieldValue'])) {
                        $nro = $parameterArr[$i - 2]['fieldValue'];
                        $item = $this->$model_anexo->get_order_number_left($nro);

                        if (isset($item[0][5215])) {
                            $fecha_emision = $item[0][5215];
                            $fecha_row = translate_date($parameterArr[$i]['fieldValue']);
                            if ($fecha_row < $fecha_emision) {
                                $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue'] . "($fecha_row)");
                                array_push($stack, $result);
                            }
                        }
                    }

                    //Valida contra Mongo
                }

                /* CUOTA_GTA_PESOS
                 * Nro D.1
                 * Detail:
                 * Formato numérico. Aceptar hasta dos decimales. La suma de las cuotas de una misma garantía debe ser igual al monto informado para esa misa garantía en la Columna E del Anexo 12.
                 */

                if ($param_col == 4) {
                    $code_error = "D.1";
                    //empty field Validation
                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {

                        $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                        array_push($stack, $result);
                    }

                    // Decimal check 
                    if (isset($parameterArr[$i]['fieldValue'])) {
                        $return = check_decimal($parameterArr[$i]['fieldValue'], 2, true);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        } else {
                            // D2
                            $d2_sum+=(float) $parameterArr[$i]['fieldValue'];

                            $d2_nro = $parameterArr[$i - 3]['fieldValue'];                            
                            $d2_nro_array[$A_cell_value][] = $d2_nro;
                        }
                    }

                    //Valida contra Mongo
                }

                /* CUOTA_MENOR_PESOS
                 * Nro E.1
                 * Detail:
                 * Formato numérico. Aceptar hasta dos decimales. La suma de las cuotas de una misma garantía debe ser igual al monto informado para esa misa garantía en la Columna L del Anexo 12.
                 */

                if ($param_col == 5) {
                    $code_error = "E.1";
                    //empty field Validation
                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                        array_push($stack, $result);
                    }
                    // Check decimal y positivo
                    if (isset($parameterArr[$i]['fieldValue'])) {
                        $return = check_decimal($parameterArr[$i]['fieldValue'], 2, true);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        } else {
                            // E2
                            $e2_sum+=(float) $parameterArr[$i]['fieldValue'];
                            $e2_nro = $parameterArr[$i - 4]['fieldValue'];
                        }
                    }

                    //Valida contra Mongo
                }
            } // END FOR LOOP->
        }

        // ============ Validation B.1 ==


        foreach ($b1_array as $keyvalue) {

            if (!check_consecutive_values($keyvalue)) {
                $code_error = "B.1";
                $result = return_error_array($code_error, "-", "Los números de cuotas deben ser consecutivos");
                array_push($stack, $result);
            }
        }


        // ============ Validation D.2.A 
        
        //if (!empty($d2_nro)) {
        foreach ($d2_nro_array as $d2val){
            $item = $this->$model_anexo->get_order_number_left($d2val);


            foreach ($item as $itm) {
                $currency = $itm[5219][0];
                $amount = $itm[5218];
            }

            if ($currency == "2") {
                $code_error = "D.2.B";
                $dollar_quotation_origin = $this->sgr_model->get_dollar_quotation(translate_date_xls($origin));
                $dollar_quotation_period = $this->sgr_model->get_dollar_quotation_period();
                $new_dollar_value = ($amount / $dollar_quotation_origin) * $dollar_quotation_period;

                $a = (int) $new_dollar_value;
                $b = (int) $d2_sum;

                $fix_ten_cents = fix_ten_cents($a, $b);

                if ($fix_ten_cents) {
                    $result = return_error_array($code_error, $parameterArr[$i]['row'], money_format_custom($d2_sum) . ' Monto disponible para el Nro. Orden  ' . $A_cell_value . ' =  (' . money_format_custom($amount) . '/' . money_format_custom($dollar_quotation_origin) . '*' . money_format_custom($dollar_quotation_period) . ' = ' . money_format_custom($new_dollar_value) . ')');
                    array_push($stack, $result);
                }
            } else {
                if (isset($amount)) {
                    $code_error = "D.2.A";

                    $result_comp = bccomp($d2_sum, $amount, 2); // 0
                    if ($result_comp != 0) {
                        $result = return_error_array($code_error, "-", "Monto: " . $amount . " / Suma:" . $d2_sum);
                        array_push($stack, $result);
                    }
                }
            }
        }

        // ============ Validation E.2 


        if (!empty($e2_nro)) {

            if ($currency == "2") {
                $code_error = "E.2.B";

                $dollar_quotation_origin = $this->sgr_model->get_dollar_quotation(translate_date_xls($origin));
                $dollar_quotation_period = $this->sgr_model->get_dollar_quotation_period();
                $new_dollar_value = ($amount / $dollar_quotation_origin) * $dollar_quotation_period;

                $a = (int) $new_dollar_value;
                $b = (int) $e2_sum;

                $fix_ten_cents = fix_ten_cents($a, $b);

                if ($fix_ten_cents) {
                    $result = return_error_array($code_error, $parameterArr[$i]['row'], money_format_custom($e2_sum) . ' Monto disponible para el Nro. Orden  ' . $A_cell_value . ' =  (' . money_format_custom($amount) . '/' . money_format_custom($dollar_quotation_origin) . '*' . money_format_custom($dollar_quotation_period) . ' = ' . money_format_custom($new_dollar_value) . ')');
                    array_push($stack, $result);
                }
            } else {
                $item = $this->$model_anexo->get_order_number_left($e2_nro);
                $code_error = "E.2.A";

                if (isset($amount)) {
                    $result_comp = bccomp($e2_sum, $amount, 2); // 0
                    if ($result_comp != 0) {
                        $result = return_error_array($code_error, "-", $e2_sum . " de " . $amount);
                        array_push($stack, $result);
                    }
                }
            }
        }

        //debug($stack);        exit();
        $this->data = $stack;
    }

}
