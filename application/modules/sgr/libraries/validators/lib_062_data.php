<?php

class Lib_062_data extends MX_Controller {
    /* VALIDADOR ANEXO 061 */

    public function __construct($parameter) {
        parent::__construct();
        $this->load->library('session');

        $this->load->helper('sgr/tools');
         /* PARTNER INFO */
        $model_06 = 'model_06';
        $this->load->Model($model_06);

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

                /* CUIT
                 * Nro A.1
                 * Detail:
                 * Debe tener 11 caracteres numéricos sin guiones.               
                 */

                if ($parameterArr[$i]['col'] == 1) {

                    $code_error = "A.1";

                    //empty field Validation
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
                            $partner_data = $this->$model_06->get_partner($parameterArr[$i]['fieldValue'], $this->session->userdata['period']);
                            if (!$partner_data) {
                                $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                                array_push($stack, $result);
                            }
                            foreach ($partner_data as $partner) {
                                if ($partner[5272][0] != 'B') {
                                    $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                                    array_push($stack, $result);
                                } else {
                                    $balance = $this->$model_06->shares_active_partners($parameterArr[$i]['fieldValue'], 'B');
                                    if ($balance == 0) {
                                        $result = return_error_array($code_error, $parameterArr[$i]['row'], "NO tiene saldo suficiente " . $balance);
                                        array_push($stack, $result);
                                    }
                                }
                            }
                        }
                    }

                    $code_error = "A.2";
                    //Valida contra Mongo
                }

                /* ANIO_MES
                 * Nro B.1
                 * Detail:
                 * Debe tener el siguiente formato: xxxx/xx, correspondientes al formato AÑO/MES; que los dígitos del mes estén entre 01 y 12.
                 * Nro B.2
                 * Detail:
                 * El año debe ser igual o menor al del período en que se está informando.
                 */

                if ($parameterArr[$i]['col'] == 2) {

                    $code_error = "B.1";
                    //empty field Validation
                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                        array_push($stack, $result);
                    } else {
                        $code_error = "B.2";
                        $return = check_date($parameterArr[$i]['fieldValue']);
                        if (!$return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }

                        /* PERIOD */
                        $code_error = "B.2";
                        list($y_post, $m_post) = explode("/", $parameterArr[$i]['fieldValue']);
                        list($m_period, $y_period) = explode("-", $this->session->userdata['period']);
                        $y_post = (int) $y_post;
                        $y_period = (int) $y_period;

                        if ($y_post > $y_period) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }
                    }
                }


                /* FACTURACION
                 * Nro C.1
                 * Detail:
                 * Debe ser formato numérico y aceptar hasta dos decimales.
                 */
                if ($parameterArr[$i]['col'] == 3) {
                    if ($parameterArr[$i]['fieldValue'] != "") {
                        $code_error = "C.1";
                        $return = check_decimal($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        } else {
                            $C_cell_value = (int) $parameterArr[$i]['fieldValue'];
                            if ($C_cell_value < 1) {
                                $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                                array_push($stack, $result);
                            }
                        }
                    }
                }
                /* EMPLEADOS
                 * Nro D.1
                 * Detail:
                 * Numero entero mayor a cero.
                 */
                if ($parameterArr[$i]['col'] == 4) {
                    //empty field Validation
                    $code_error = "D.1";
                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                        array_push($stack, $result);
                    } else {
                        $return = check_is_numeric($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        } else {
                            $D_cell_value = (int) $parameterArr[$i]['fieldValue'];
                            if ($D_cell_value < 1) {
                                $result = return_error_array($code_error, $parameterArr[$i]['row'], $D_cell_value);
                                array_push($stack, $result);
                            }
                        }
                    }
                }
                /* EMPLEADOS
                 * Nro E.1
                 * Detail:
                 * Numero entero mayor a cero.
                 */
                if ($parameterArr[$i]['col'] == 5) {
                    if (isset($parameterArr[$i]['fieldValue'])) {
                        $code_error = "E.1";
                        $allow_words = array("BALANCES", "CERTIFICACION DE INGRESOS", "DDJJ IMPUESTOS");
                        $return = check_word($parameterArr[$i]['fieldValue'], $allow_words);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }
                    }
                }
            }
        }

        $this->data = $stack;
    }

}
