<?php

class Lib_13_data extends MX_Controller {
    /* VALIDADOR ANEXO 13 */

    public function __construct($parameter) {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('sgr/tools');
        $this->load->model('sgr/sgr_model');

        $this->period = $this->session->userdata['period'];

        /* Vars 
         * 
         * $parameters =  
         * $parameterArr[0]['fieldValue'] 
         * $parameterArr[0]['row'] 
         * $parameterArr[0]['col']
         * $parameterArr[0]['count']
         * 
         */
        
        
        /* ARRAYS */
        $result = array();
        $parameterArr = (array) $parameter;
        



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

            $param_col = (isset($parameterArr[$i]['col'])) ? $parameterArr[$i]['col'] : 0;

            /* TIPO_DE_GARANTIA
             * Nro A.1
             * Detail:
             * Debe tener 11 caracteres sin guiones.
             * Nro A.2
             * Detail:
             * Sólo pude contener alguno de los tipos de Garantía aceptados de acuerdo a lo que se lista en el Modelo de importación. Pueden estar listados todos o sólo algunos.
             */

            if ($param_col == 1) {
                $code_error = "A.1";
                $A_cell_value = NULL;
                $return = check_empty($parameterArr[$i]['fieldValue']);
                if ($return) {
                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                    
                } else {
                    $A_cell_value = $parameterArr[$i]['fieldValue'];
                    $types = $this->sgr_model->get_warranty_type($parameterArr[$i]['fieldValue'], $this->period);
                    if (!$types) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                        
                    }
                }
            }

            /* MENOR_90_DIAS
             * Nro B.1
             * Detail:
             * Formato de número. Acepta hasta dos decimales.                 
             */

            if ($param_col == 2) {
                $code_error = "B.1";
                $B_cell_value = NULL;
                if ($parameterArr[$i]['fieldValue'] != "") {
                    $B_cell_value = (int) $parameterArr[$i]['fieldValue'];
                    $return = check_decimal($parameterArr[$i]['fieldValue'], 2, true);
                    if ($return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                        
                    }
                }
            }

            /* MENOR_180_DIAS
             * Nro C.1
             * Detail:
             * Formato de número. Acepta hasta dos decimales.                
             */
            if ($param_col == 3) {
                //empty field Validation
                $code_error = "C.1";
                $C_cell_value = NULL;
                if ($parameterArr[$i]['fieldValue'] != "") {
                    $C_cell_value = (int) $parameterArr[$i]['fieldValue'];
                    $return = check_decimal($parameterArr[$i]['fieldValue'], 2, true);
                    if ($return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                        
                    }
                }
            }




            /* MENOR_365_DIAS
             * Nro D.1
             * Detail:
             * Formato de número. Acepta hasta dos decimales.                 
             */
            if ($param_col == 4) {

                $code_error = "D.1";
                $D_cell_value = NULL;
                if ($parameterArr[$i]['fieldValue'] != "") {
                    $D_cell_value = (int) $parameterArr[$i]['fieldValue'];
                    $return = check_decimal($parameterArr[$i]['fieldValue'], 2, true);
                    if ($return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                        
                    }
                }
            }

            /* MAYOR_365_DIAS
             * Nro E.1
             * Detail:
             * Formato de número. Acepta hasta dos decimales.                 
             */
            if ($param_col == 5) {
                //empty field Validation
                $code_error = "E.1";
                $E_cell_value = NULL;
                if ($parameterArr[$i]['fieldValue'] != "") {
                    $E_cell_value = (int) $parameterArr[$i]['fieldValue'];
                    $return = check_decimal($parameterArr[$i]['fieldValue'], 2, true);
                    if ($return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                        
                    }
                }

                /* VALOR_CONTRAGARANTIAS
                 * Nro F.1
                 * Detail:
                 * Formato de número. Acepta hasta dos decimales.                 
                 */
                if ($param_col == 6) {
                    $F_cell_value = NULL;
                    $code_error = "F.1";

                    if ($parameterArr[$i]['fieldValue'] != "") {
                        $F_cell_value = (int) $parameterArr[$i]['fieldValue'];
                        $return = check_decimal($parameterArr[$i]['fieldValue'], 2, true);
                        if ($return) {
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            
                        }
                    }

                    /* SUMA B-F */
                    $result_sum = array_sum(array($B_cell_value, $C_cell_value, $D_cell_value, $E_cell_value, $F_cell_value));
                    if ($result_sum == 0) {
                        $code_error = "B.2";
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                        
                    }
                }
            } // END FOR LOOP->
        }


        $this->data = $result;
    }

}
