<?php

class Lib_141_data extends MX_Controller {
    /* VALIDADOR ANEXO 14 */

    public function __construct($parameter) {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('sgr/tools');
        $this->load->model('sgr/sgr_model');


        $this->load->Model('model_14');
        $this->load->Model('model_141');
        $this->load->Model('model_12');
        $this->load->Model('model_125');
        /* Vars 
         * 
         * $parameters =  
         * $parameterArr[0]['fieldValue'] 
         * $parameterArr[0]['row'] 
         * $parameterArr[0]['col']
         * $parameterArr[0]['count']
         * 
         */


        $sgrArr = $this->sgr_model->get_sgr();
        foreach ($sgrArr as $sgr) {
            $this->sgr_id = (float) $sgr['id'];
            $this->sgr_nombre = $sgr['1693'];
            $this->sgr_cuit = $sgr['1695'];
        }

        $result = array();
        $parameterArr = (array) $parameter;

        $A_cell_array = array();
        $order_num = array();
        $A3_check = array();
        $A3_check_125 = $this->model_125->cuits_by_period($this->session->userdata['period']);


        /**
         * BASIC VALIDATION
         * 
         * @param 
         * @type PHP
         * @name ...
         * @author Diego             
         * @example 
         * CUIT_PARTICIPE	CANT_GTIAS_VIGENTES	HIPOTECARIAS	PRENDARIAS	FIANZA	OTRAS	REAFIANZA	MORA_EN_DIAS	CLASIFICACION_DEUDOR
         * */
        for ($i = 0; $i <= count($parameterArr); $i++) {

            $param_col = (isset($parameterArr[$i]['col'])) ? $parameterArr[$i]['col'] : 0;

            /* CUIT_PARTICIPE
             * Nro A.1
             * Detail:
             * Debe tener 11 caracteres sin guiones.
             * Nro A.2
             * Detail:
             * Debe figura en el Sistema con Garantías Otorgadas (Anexo 12)
             */

            if ($param_col == 1) {
                $A_cell_value = "";

                $return = check_empty($parameterArr[$i]['fieldValue']);
                if ($return) {
                    $code_error = "A.1";
                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                } else {
                    $A_cell_value = $parameterArr[$i]['fieldValue'];
                    $A_cell_array[] = $A_cell_value;

                    $return = cuit_checker($A_cell_value);
                    if (!$return) {
                        $code_error = "A.1";
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }

                    $B_warranty_info = $this->model_12->get_warranty_partner_left($parameterArr[$i]['fieldValue']);
                    if (!$B_warranty_info) {
                        $code_error = "A.2";
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }


                    /* A.3 */
                    $A3_check[] = $A_cell_value;



                    //valida mongo
                }
            }

            /* CANT_GTIAS_VIGENTES
             * Nro B.1
             * Detail:
             * Si se detecta que el CUIT está informando en el ANEXO 12.5, debe tener formato número y aceptar números enteros mayores a Cero. De lo contrario, debe estar vacío.                
             */

            if ($param_col == 2) {
                $code_error = "B.1";

                $return = check_empty($parameterArr[$i]['fieldValue']);
                if ($return) {

                    if (in_array($A_cell_value, $A3_check_125)) {
                        $b_is_number = check_is_numeric_no_decimal($parameterArr[$i]['fieldValue'], 0);

                        if ($b_is_number == false)
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                }
            }

            /* HIPOTECARIAS
             * Nro C.1
             * Detail:
             * De completarse, debe tomar valor numérico mayor a cero y aceptar hasta dos decimales.
             */
            if ($param_col == 3) {
                $code_error = "C.1";
                $return = check_empty($parameterArr[$i]['fieldValue']);
                if (!$return) {
                    $C_check = validate_two_decimals_no_cero($parameterArr[$i]['fieldValue'], 2, true);

                    if ($C_check)
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                }
            }

            /* PRENDARIAS
             * Nro D.1
             * Detail:De completarse, debe tomar valor numérico mayor a cero y aceptar hasta dos decimales.                
             */
            if ($param_col == 4) {

                $code_error = "D.1";
                $return = check_empty($parameterArr[$i]['fieldValue']);
                if (!$return) {
                    $D_check = validate_two_decimals_no_cero($parameterArr[$i]['fieldValue'], 2, true);

                    if ($D_check)
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                }
            }

            /* FIANZA
             * Nro E.1
             * Detail:
             * De completarse, debe tomar valor numérico mayor a cero y aceptar hasta dos decimales.
             */
            if ($param_col == 5) {

                $code_error = "E.1";
                $return = check_empty($parameterArr[$i]['fieldValue']);
                if (!$return) {
                    $E_check = validate_two_decimals_no_cero($parameterArr[$i]['fieldValue'], 2, true);

                    if ($E_check)
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                }
            }

            /* OTRAS
             * Nro F.1
             * Detail:
             * De completarse, debe tomar valor numérico mayor a cero y aceptar hasta dos decimales.
             */
            if ($param_col == 6) {
                $code_error = "F.1";
                $return = check_empty($parameterArr[$i]['fieldValue']);
                if (!$return) {
                    $F_check = validate_two_decimals_no_cero($parameterArr[$i]['fieldValue'], 2, true);

                    if ($F_check)
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                }
            }

            /* REAFIANZA
             * G.1
             * Detail: 
             * Debe completarse sólo en caso de que el CUIT informado en la Columna A del importador se encuentre previamente informado en el Sistema mediante ANEXO 12.4. 
             * De lo contrario, debe estar vacío.De completarse, debe tomar valor numérico mayor a cero y aceptar hasta dos decimales.
             */
            if ($param_col == 7) {
                $code_error = "G.1";

                /* CHECK ANEXO 12/4 */

                if ($parameterArr[$i]['fieldValue'] != "") {
                    $return = check_decimal($parameterArr[$i]['fieldValue'], 2, true);
                    if ($return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                }
            }

            /* MORA_EN_DIAS 
             * H.1
             * Detail: 
             * De estar completo debe tener formato numérico, positivo y entero, sin decimales. Debe validar que si en el proceso de importación detecta que el CUIT informado en la Columna A tiene saldos de deuda positivos (Saldo Calculado por el Sistema sobre la información histórica de los movimientos del FDR Contingente informados mediante ANEXO 14), esta columna deberá estar completa. Esto está relacionado con el proceso indicado en las Validaciones de Impresión de las PARTICULARIDADES DE IMPRESIÓN de este Anexo.
             */
            if ($param_col == 8) {
                $code_error = "H.1";
                $return = check_empty($parameterArr[$i]['fieldValue']);
                if (!$return) {
                    $H_check = check_is_numeric_no_decimal($parameterArr[$i]['fieldValue'], 2, true);
                    if ($H_check == false)
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                }
            }

            /* MORA_EN_DIAS 
             * I.1
             * Detail: 
             * Debe validar que si en el proceso de importación detecta que el CUIT informado en la Columna A tiene saldos de deuda positivos (Saldo Calculado por el Sistema sobre la información histórica de los movimientos del FDR Contingente informados mediante ANEXO 14), esta columna deberá estar completa.
             * I.2
             * Detail:
             * De estar completo, debe tomar alguno de los siguientes parámetros:1,2,3,4,5
             */
            if ($param_col == 9) {
                $code_error = "I.1";
                if (!empty($parameterArr[$i]['fieldValue'])) {
                    $nums = array("1", "2", "3", "4", "5");
                    if (!in_array($parameterArr[$i]['fieldValue'], $nums)) {
                        $code_error = "I.2";
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                }
            }
        } // END FOR LOOP->



        /* EXTRA VALIDATION A.3 */
        foreach ($A3_check_125 as $cuit_125) {
            if (!in_array($cuit_125, $A3_check)) {
                $code_error = "A.3";
                $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "CUIT " . $cuit_125);
            }
        }


        /* EXTRA VALIDATION A.4 */

        foreach (repeatedElements($A_cell_array) as $arr) {
            $code_error = "A.4";
            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "CUIT Repetido " . $arr['value']);
        }

        /* EXTRA VALIDATION A.5 */

        $A5_check = $this->model_141->find_141_balance_sgr();

        if (!isset($A5_check))
            $A5_check = array();

        foreach ($A5_check as $key => $value) {

            if (!in_array($value, $A_cell_array)) {
               
                
              # $A5_by_cuit = $this->model_141->garantias_balance_by_cuit($value);

              # var_dump($A5_by_cuit);

                $code_error = "A.5";
                $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "(Valor No Encontrado) " . $value);
            }
        }


        var_dump($result);
        exit();
        $this->data = $result;
    }

}
