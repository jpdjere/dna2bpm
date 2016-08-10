<?php

class Lib_202_data extends MX_Controller {
    /* VALIDADOR ANEXO 20.2 */

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

        $result = array();
        $parameterArr = (array) $parameter;

        $A_array_value = array();
        $A3_array = array();
        $A4_array = array();


        $A3_array = $this->$model_201->exist_input_all();       
        $A4_array = $this->$model_201->exist_input_all(0);



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


            $param_col = (isset($parameterArr[$i]['col'])) ? $parameterArr[$i]['col'] : 0;

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
            if ($param_col == 1) {


                $get_anexo_data = false;
                $get_input_number_check = 0;


                if (empty($parameterArr[$i]['fieldValue'])) {
                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                } else {

                    $A_cell_value = $parameterArr[$i]['fieldValue'];
                    $A_array_value[] = (int) $A_cell_value;

                    $get_anexo_data = $this->$model_201->exist_input_number_left($A_cell_value);
                    if ($get_anexo_data)
                        $get_input_number_check = (int) $this->$model_201->get_input_number_left($A_cell_value);//Support #25707 (int)
                    else {
                        $code_error = "A.2";
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $A_cell_value);                        
                    }
                }
            }


            /* CONTINGENTE_PROPORCIONAL_ASIGNADO
             * Nro B.1
             * Detail:
             * Se puede completar la cantidad de filas que sean necesarias. Si una fila se completa, todos sus campos deben estar llenos.                
             */
            if ($param_col == 2) {

                $B_cell_value = false;
                $code_error = "B.1";

                if(!empty($parameterArr[$i]['fieldValue'])) { 
                    $B_cell_value = (float) $parameterArr[$i]['fieldValue'];

                    $return = validate_two_decimals($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                }
            }

            /* DEUDA_PROPORCIONAL_ASIGNADA
             * Nro C.1
             * Detail:
             * OPCIONAL. Valor con formato numérico positivo,  que acepte hasta dos decimales.
             */
            if ($param_col == 3) {
                $code_error = "C.1";
                
                if(!empty($parameterArr[$i]['fieldValue'])) {   
                    $C_cell_value = $parameterArr[$i]['fieldValue'];
                    $return = validate_two_decimals($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                }
            }

            /* RENDIMIENTO_ASIGNADO
             * Nro D.1
             * Detail:
             * Valor con formato numérico,  que acepte hasta dos decimales.
             * 
             */
            if ($param_col == 4) {

                $code_error = "D.1";
                if (empty($parameterArr[$i]['fieldValue']) && $parameterArr[$i]['fieldValue'] != 0) {
                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                } else {
                    $D_cell_value = $parameterArr[$i]['fieldValue'];
                    $return = validate_two_decimals($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                }


                $return = check_is_numeric_no_decimal($A_cell_value, true);
                if (!$return) {
                    $code_error = "A.1";
                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $A_cell_value);
                } else {
                    if (in_array($A_cell_value, $A4_array)) {
                        /* ESTA EN EL SISTEMA */
                        $a4_check_array = array($C_cell_value, $D_cell_value);
                        $a4_check = array_sum($a4_check_array);

                        if ($a4_check == 0) {
                            $code_error = "A.4";
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $A_cell_value);
                        }


                        /* ????????????????????????????????? Support #11501 */
//                            $a = (int) $B_cell_value;
//                            $b = (int) $get_input_number_check;

                        $result_comp = bccomp($B_cell_value, $get_input_number_check, 2); // 0                            

                        if ($result_comp > 0) {
                            $code_error = "B.2";
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $B_cell_value . " <br>Valor del Sistema: " . $get_input_number_check);
                        }
                    }
                }
            }
        } // END FOR LOOP->



        /* A.3 */
        $A3_result = array_diff(array_unique($A3_array), array_unique($A_array_value));

        if ($A3_result) {
            foreach ($A3_result as $A3) {
                $code_error = "A.3";
                $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "Resta el Nro de Aporte: " . $A3);
            }
        }
        /*   debug($result);       
         exit();
         */

        $this->data = $result;
    }
}
