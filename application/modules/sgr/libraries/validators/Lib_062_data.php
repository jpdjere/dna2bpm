<?php

class Lib_062_data extends MX_Controller {
    /* VALIDADOR ANEXO 061 */

    public function __construct($parameter) {
        parent::__construct();
        $this->load->library('session');

        $this->load->helper('sgr/tools');

        /* PARTNER INFO */
        $this->load->Model('model_06');
        $this->load->Model('model_062');




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
        $A_array_values = array();


        /* Validacion Basica */

        for ($i = 0; $i <= count($parameterArr); $i++) {


            $param_col = (isset($parameterArr[$i]['col'])) ? $parameterArr[$i]['col'] : 0;

            /* CUIT
             * Nro A.1
             * Detail:
             * Debe tener 11 caracteres numéricos sin guiones.               
             */

            if ($param_col == 1) {

                $error_a = false;
                $code_error = "A.1";

                if (empty($parameterArr[$i]['fieldValue'])) {
                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                    $error_a = true;
                } else {

                    $A_array_values[] = $parameterArr[$i]['fieldValue'];

                    $return = cuit_checker($parameterArr[$i]['fieldValue']);
                    if (!$return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                        $error_a = true;
                    } else {
                        $code_error = "A.2";
                        $partner_data = $this->model_06->shares_active_left($parameterArr[$i]['fieldValue'], "A");

                        if ($partner_data == 0) {
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue'] . " Saldo " . $partner_data);
                            $error_a = true;
                        }
                    }
                }



                if ($error_a == false) {

                    /* B.3 CTRL */
                    $f_year = false;

                    /* FRE CONTROL */
                    if (isset($this->session->userdata['fre_session']))
                        $partner_data = $this->model_06->get_partner_left_fre($parameterArr[$i]['fieldValue']);
                    else
                        $partner_data = $this->model_06->get_partner_left($parameterArr[$i]['fieldValue']);

                    foreach ($partner_data as $partner) {
                        $f_year = ($this->session->userdata['period'] == $partner['25']) ? $partner['25'] : false;
                    }

                    /* FRE CONTROL */
                    if (isset($this->session->userdata['fre_session']))
                        $partner_data_62 = $this->model_062->get_partner_left_fre($parameterArr[$i]['fieldValue']);
                    else
                        $partner_data_62 = $this->model_062->get_partner_left($parameterArr[$i]['fieldValue']);


                    foreach ($partner_data as $partner) {
                        $f_year = ($this->session->userdata['period'] == $partner['period']) ? $partner['period'] : false;
                    }
                    
                    
                    
                }
            }

            /* ANIO_MES
             * Nro B.1
             * Detail:
             * Debe tener el siguiente formato: xxxx/xx, correspondientes al formato AÑO/MES; que los dígitos del mes estén entre 01 y 12.
             * Nro B.2
             * Detail:
             * El período ingresado en el archivo debe ser igual o hasta un año anterior al del período en que se está subiendo la información. Sólo debe verificar el año, no el mes.
             * Nro B.3
             * Detail:
             * Para el CUIT informado en la columna A, debe verificar que el año de facturación que están informando no esté ya informado anteriormente, tanto en el histórico del Anexo 6 como en el del Anexo 6.2.
             * Nro B.4
             * Detail:
             * Para el CUIT informado en la columna A, la fecha informada debe ser posterior a las registradas en el historial de los anexo 6 y/o anexo 6.2
             */

            if ($param_col == 2) {

                $code_error = "B.1";
                if (empty($parameterArr[$i]['fieldValue'])) {
                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                } else {
                    $code_error = "B.1";
                    $return = check_date($parameterArr[$i]['fieldValue']);
                    if (!$return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }

                    /* PERIOD */
                    $code_error = "B.2";
                    list($y_post, $m_post) = explode("/", $parameterArr[$i]['fieldValue']);
                    list($m_period, $y_period) = explode("-", $this->session->userdata['period']);

                    $check_diff = (int) $y_period - ((int) $y_post);

                    if (!in_array($check_diff, range(0, 1))) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }

                
                     
                    list($b_year, $b_month) = explode("/", $parameterArr[$i]['fieldValue']);
                    list($p_month, $p_year ) = explode("-", $this->session->userdata['period']);
                    
                    /* B.3 */
                    list($x_year, $x_month) = explode("/", $partner['25']);
                    if ($x_year == $b_year) {
                        $code_error = "B.3";
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }

                    /* B.4 */
                    if($f_year!=false){
                        
                        list($a_year, $a_month) = explode("/", $f_year);   
                       
                        $datetime1 = date($a_year . "-" . $a_month);
                        $datetime2 = date($p_year . "-" . $p_month);
                        
                        var_dump($f_year, $datetime1, $datetime2);
                        
                        if (strtotime($datetime1) < strtotime($datetime2)) {
                            $code_error = "B.4";
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);                        
                        }
                    }
                }
            }


            /* FACTURACION
             * Nro C.1
             * Detail:
             * Debe ser formato numérico y aceptar hasta dos decimales.
             */
            if ($param_col == 3) {
                if ($parameterArr[$i]['fieldValue'] != "") {
                    $code_error = "C.1";
                    $return = check_decimal($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    } else {
                        $C_cell_value = (int) $parameterArr[$i]['fieldValue'];
                        if ($C_cell_value < 1) {
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                        }
                    }
                }
            }
            /* EMPLEADOS
             * Nro D.1
             * Detail:
             * Numero entero mayor a cero.
             */
            if ($param_col == 4) {
                //empty field Validation
                $code_error = "D.1";
                if (empty($parameterArr[$i]['fieldValue'])) {
                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                } else {
                    $return = check_is_numeric($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    } else {
                        $D_cell_value = (int) $parameterArr[$i]['fieldValue'];
                        if ($D_cell_value < 1) {
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $D_cell_value);
                        }
                    }
                }
            }
            /* EMPLEADOS
             * Nro E.1
             * Detail:
             * Numero entero mayor a cero.
             */
            if ($param_col == 5) {
                if (isset($parameterArr[$i]['fieldValue'])) {
                    $code_error = "E.1";
                    $allow_words = array("BALANCES", "CERTIFICACION DE INGRESOS", "DDJJ IMPUESTOS");
                    $return = check_word($parameterArr[$i]['fieldValue'], $allow_words);
                    if ($return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                }
            }
        }


        /* CUITS
         * Nro A.3
         * Detail:
         * Cada CUIT sólo puede figurar una vez en el archivo.
         */
        $duplicated = duplicate_in_array($A_array_values);

        if ($duplicated) {
            foreach ($duplicated as $key => $value) {
                $code_error = "A.3";
                $result[] = return_error_array($code_error, $key, "CUIT: " . $value);
            }
        }
        
        /*var_dump($result);
        exit;*/

        $this->data = $result;
    }

}
