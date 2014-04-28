<?php

class Lib_202_data extends MX_Controller {
    /* VALIDADOR ANEXO 12.5 */

    public function __construct($parameter) {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('sgr/tools');
        $this->load->model('sgr/sgr_model');

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
        $A_array_value = array();
        $A3_array = array();
        $A4_array = array();
        $exist_input_all = array_unique($this->$model_201->exist_input_all());

        foreach ($exist_input_all as $each) {
            $exist_input_number_left = $this->$model_201->exist_input_number_left($each);
            if ($exist_input_number_left)
                $get_input_number_left = $this->$model_201->get_input_number_left($each);

            if ($get_input_number_left > 0)
                $A3_array[] = $each;

            if ($get_input_number_left == 0)
                $A4_array[] = $each;
        }

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
             * CONTINGENTE_PROPORCIONAL_ASIGNADO	
             * DEUDA_PROPORCIONAL_ASIGNADA	
             * RENDIMIENTO_ASIGNADO
             * */
            for ($i = 0; $i <= count($parameterArr); $i++) {

                /* NUMERO_DE_APORTE
                 * Nro A.1
                 * Detail:
                 * Formato numérico sin decimales.
                 * Nro A.2
                 * Detail:
                  Debe validar que el número de aporte se encuentre registrado en el Sistema.
                 * Nro A.3
                 * Detail:
                  Debe validar que, al menos, se encuentre listados todos los números de aportes que, tengan SALDOS DE APORTE mayores a Cero.
                 * Nro A.4
                 * Detail:
                  Si para un determinado Número de Aporte el SALDO DE APORTE, es cero, debe validar que la Columna B sea Cero y que la Columna D tenga un monto informado.
                 */
                if ($parameterArr[$i]['col'] == 1) {

                    //empty field Validation  
                    $get_anexo_data = false;
                    $get_input_number_check = 0;


                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                        array_push($stack, $result);
                    } else {

                        $A_cell_value = $parameterArr[$i]['fieldValue'];

                        $get_anexo_data = $this->$model_201->exist_input_number_left($A_cell_value);
                        if ($get_anexo_data)
                            $get_input_number_check = $this->$model_201->get_input_number_left($A_cell_value);

                        $A_array_value[] = (int) $A_cell_value;

                        if (!$get_anexo_data) {
                            $code_error = "A.2";
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $A_cell_value);
                            array_push($stack, $result);
                        }
                    }
                }


                /* CONTINGENTE_PROPORCIONAL_ASIGNADO
                 * Nro B.1
                 * Detail:
                 * Se puede completar la cantidad de filas que sean necesarias. Si una fila se completa, todos sus campos deben estar llenos.                
                 */
                if ($parameterArr[$i]['col'] == 2) {

                    $B_cell_value = false;
                    $code_error = "B.1";

                    if ($parameterArr[$i]['fieldValue'] != "") {
                        $B_cell_value = (float) $parameterArr[$i]['fieldValue'];

                        $return = validate_two_decimals($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }
                    }
                }

                /* DEUDA_PROPORCIONAL_ASIGNADA
                 * Nro C.1
                 * Detail:
                 * OPCIONAL. Valor con formato numérico positivo,  que acepte hasta dos decimales.
                 */
                if ($parameterArr[$i]['col'] == 3) {
                    $code_error = "C.1";

                    if ($parameterArr[$i]['fieldValue'] != "") {
                        $C_cell_value = $parameterArr[$i]['fieldValue'];
                        $return = validate_two_decimals($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }
                    }
                }

                /* RENDIMIENTO_ASIGNADO
                 * Nro D.1
                 * Detail:
                 * Valor con formato numérico,  que acepte hasta dos decimales.
                 * 
                 */
                if ($parameterArr[$i]['col'] == 4) {

                    $code_error = "D.1";
                    //empty field Validation                    
                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                        array_push($stack, $result);
                    } else {
                        $D_cell_value = $parameterArr[$i]['fieldValue'];
                        $return = validate_two_decimals($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }
                    }


                    $return = check_is_numeric_no_decimal($A_cell_value, true);
                    if (!$return) {
                        $code_error = "A.1";
                        $result = return_error_array($code_error, $parameterArr[$i]['row'], $A_cell_value);
                        array_push($stack, $result);
                    } else {
                        if (in_array($A_cell_value, $A4_array)) {
                            /* ESTA EN EL SISTEMA */
                            $a4_check_array = array($C_cell_value, $D_cell_value);
                            $a4_check = array_sum($a4_check_array);

                            if ($a4_check == 0) {
                                $code_error = "A.4";
                                $result = return_error_array($code_error, $parameterArr[$i]['row'], $A_cell_value);
                                array_push($stack, $result);
                            }


                            /* ????????????????????????????????? Support #11501 */
//                            $a = (int) $B_cell_value;
//                            $b = (int) $get_input_number_check;
                            
                            $result_comp =  bccomp($B_cell_value, $get_input_number_check, 2); // 0                            

                            if ($result_comp) {
                                $code_error = "B.2";
                                $result = return_error_array($code_error, $parameterArr[$i]['row'], $B_cell_value . " <br>Valor del Sistema: " . $get_input_number_check);
                                array_push($stack, $result);
                            }
                        }
                    }
                }
            } // END FOR LOOP->
        }


        /* A.3 */
        $A3_result = array_diff(array_unique($A3_array), array_unique($A_array_value));
        if ($A3_result) {
            foreach ($A3_result as $A3) {
                $code_error = "A.3";
                $result = return_error_array($code_error, $parameterArr[$i]['row'], "Resta el Nro de Aporte: " . $A3);
                array_push($stack, $result);
            }
        }
        debug($stack);        exit();
        $this->data = $stack;
    }

}
