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
                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                        array_push($stack, $result);
                    }

                    if (isset($parameterArr[$i]['fieldValue'])) {

                        $return = check_is_numeric_no_decimal($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $code_error = "A.1";
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }

                        $code_error = "A.2";
                        $get_input_number = $this->$model_201->get_input_number_left($parameterArr[$i]['fieldValue']);
                        if (!$get_input_number) {
                            $code_error = "A.2";
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }

                        $code_error = "A.3";
                        if($get_input_number>0){
                             $code_error = "A.3";
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
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
                    
                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                        array_push($stack, $result);
                    } else {
                        $B_cell_value = $parameterArr[$i]['fieldValue'];
                        $return = check_decimal($parameterArr[$i]['fieldValue'], 2, true);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        } else {
                            if ($parameterArr[$i]['fieldValue'] > $get_input_number) {
                                $code_error = "B.2";
                                $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                                array_push($stack, $result);
                            }
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
                        $return = check_decimal($parameterArr[$i]['fieldValue'], 2, true);
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
                        $return = check_decimal($parameterArr[$i]['fieldValue'], 2, true);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }
                    }
                }
            } // END FOR LOOP->
        }
        $this->data = $stack;
    }

}
