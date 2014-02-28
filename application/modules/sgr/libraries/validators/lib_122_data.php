<?php

class Lib_122_data extends MX_Controller {
    /* VALIDADOR ANEXO 12 */

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
                    //empty field Validation
                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {


                        $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                        array_push($stack, $result);
                    }

                    //Valida contra Mongo
                    $warranty_info = $this->sgr_model->get_warranty_data($parameterArr[$i]['fieldValue'], $this->session->userdata['period']);
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
                    }
                    //Check Date Validation
                    if (isset($parameterArr[$i]['fieldValue'])) {
                        $return = check_date_format($parameterArr[$i]['fieldValue']);
                        if ($return) {


                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
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
                    }

                    if (isset($parameterArr[$i]['fieldValue'])) {
                        $return = check_decimal($parameterArr[$i]['fieldValue']);
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

                if ($parameterArr[$i]['col'] == 5) {
                    $code_error = "F.1";
                    //empty field Validation
                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                        array_push($stack, $result);
                    }

                    if (isset($parameterArr[$i]['fieldValue'])) {
                        $return = check_decimal($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }
                    }

                    //Valida contra Mongo
                }
            } // END FOR LOOP->
        }
        /* EXTRA VALIDATION B.1 */

        foreach (repeatedElements($cuota_arr) as $arr) {
            $code_error = "B.1";
            list($cuota, $warranty) = explode('*', $arr['value']);
            $result = return_error_array($code_error, $parameterArr[$i]['row'], "Cuota Repetida " . $cuota . " para la Garantía " . $warranty);
            array_push($stack, $result);
        }



        $this->data = $stack;
    }

}

