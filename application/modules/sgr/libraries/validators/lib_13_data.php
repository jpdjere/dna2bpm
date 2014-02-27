<?php

class Lib_13_data extends MX_Controller {
    /* VALIDADOR ANEXO 13 */

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

        for ($i = 1; $i <= $parameterArr[0]['count']; $i++) {
            /**
             * BASIC VALIDATION
             * 
             * @param 
             * @type PHP
             * @name ...
             * @author Diego             
             * @example 
             * TIPO_DE_GARANTIA	MENOR_90_DIAS	MENOR_180_DIAS	MENOR_365_DIAS	MAYOR_365_DIAS	VALOR_CONTRAGARANTIAS
             * */
            for ($i = 0; $i <= count($parameterArr); $i++) {

                /* TIPO_DE_GARANTIA
                 * Nro A.1
                 * Detail:
                 * Debe tener 11 caracteres sin guiones.
                 * Nro A.2
                 * Detail:
                 * Sólo pude contener alguno de los tipos de Garantía aceptados de acuerdo a lo que se lista en el Modelo de importación. Pueden estar listados todos o sólo algunos.
                 */

                if ($parameterArr[$i]['col'] == 1) {
                    $code_error = "A.1";
                    $A_cell_value = NULL;
                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                        array_push($stack, $result);
                    } else {                        
                        $A_cell_value = $parameterArr[$i]['fieldValue'];
                        $types = $this->sgr_model->get_warranty_type($parameterArr[$i]['fieldValue']);
                        if (!$types) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }
                    }
                }

                /* MENOR_90_DIAS
                 * Nro B.1
                 * Detail:
                 * Formato de número. Acepta hasta dos decimales.                 
                 */

                if ($parameterArr[$i]['col'] == 2) {
                    $code_error = "B.1";
                    $B_cell_value = NULL;
                    if ($parameterArr[$i]['fieldValue'] != "") {
                        $B_cell_value = (int) $parameterArr[$i]['fieldValue'];
                        $return = check_decimal($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }
                        if ($B_cell_value < 0) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }
                    }
                }

                /* MENOR_180_DIAS
                 * Nro C.1
                 * Detail:
                 * Formato de número. Acepta hasta dos decimales.                
                 */
                if ($parameterArr[$i]['col'] == 3) {
                    //empty field Validation
                    $code_error = "C.1";
                    $C_cell_value = NULL;
                    if ($parameterArr[$i]['fieldValue'] != "") {
                        $C_cell_value = (int) $parameterArr[$i]['fieldValue'];
                        $return = check_decimal($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }
                        if ($C_cell_value < 0) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }
                    }
                }




                /* MENOR_365_DIAS
                 * Nro D.1
                 * Detail:
                 * Formato de número. Acepta hasta dos decimales.                 
                 */
                if ($parameterArr[$i]['col'] == 4) {

                    $code_error = "D.1";
                    $D_cell_value = NULL;
                    if ($parameterArr[$i]['fieldValue'] != "") {
                        $D_cell_value = (int) $parameterArr[$i]['fieldValue'];
                        $return = check_decimal($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }
                        if ($D_cell_value < 0) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }
                    }
                }

                /* MAYOR_365_DIAS
                 * Nro E.1
                 * Detail:
                 * Formato de número. Acepta hasta dos decimales.                 
                 */
                if ($parameterArr[$i]['col'] == 5) {
                    //empty field Validation
                    $code_error = "E.1";
                    $E_cell_value = NULL;
                    if ($parameterArr[$i]['fieldValue'] != "") {
                        $E_cell_value = (int) $parameterArr[$i]['fieldValue'];
                        $return = check_decimal($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }
                        if ($E_cell_value < 0) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }
                    }
                }

                /* VALOR_CONTRAGARANTIAS
                 * Nro F.1
                 * Detail:
                 * Formato de número. Acepta hasta dos decimales.                 
                 */
                if ($parameterArr[$i]['col'] == 6) {
                    $F_cell_value = NULL;
                    $code_error = "F.1";

                    if ($parameterArr[$i]['fieldValue'] != "") {
                        $F_cell_value = (int) $parameterArr[$i]['fieldValue'];
                        $return = check_decimal($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }

                        if ($F_cell_value < 0) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }
                    }

                    /* SUMA B-F */
                    $result_sum = array_sum(array($B_cell_value, $C_cell_value, $D_cell_value, $E_cell_value, $F_cell_value));
                    if ($result_sum == 0) {
                        $code_error = "B.2";
                        $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                        array_push($stack, $result);
                    }
                }
            } // END FOR LOOP->
        }
        $this->data = $stack;
    }

}
