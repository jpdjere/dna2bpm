<?php

class Lib_126_data extends MX_Controller {
    /* VALIDADOR ANEXO 12.5 */

    public function __construct($parameter) {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('sgr/tools');
        $this->load->model('sgr/sgr_model');
        $this->load->Model("model_12");

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
        $positive_validation = true;
        $decimal = 2;
        /**
         * BASIC VALIDATION
         * 
         * @param 
         * @type PHP
         * @name ...
         * @author Diego             *
         * @example CUIT_PART	CUIT_ACREEDOR	SLDO_FINANC	SLDO_COMER	SLDO_TEC
         * */
        for ($i = 0; $i <= count($parameterArr); $i++) {




            $param_col = (isset($parameterArr[$i]['col'])) ? $parameterArr[$i]['col'] : 0;




            /* OTORGAMIENTO_PERIODO
             * Nro A.1
             * Detail:
             * La celda no puede estar vacía. Debe contener formato numérico con hasta dos decimales. Debe ser mayor o igual a cero.             
             */



            if ($param_col == 1) {

                $code_error = "A.1";

                if (empty($parameterArr[$i]['fieldValue']) && $parameterArr[$i]['fieldValue'] != 0) {
                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                } else {
                    $return = validate_two_decimals($parameterArr[$i]['fieldValue'], $decimal, $positive_validation);
                    if ($return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                }
            }


            /* OTORGAMIENTO_PERIODO_PREVIO
             * Nro B.1
             * Detail:
             * La celda no puede estar vacía. Debe contener formato numérico con hasta dos decimales. Debe ser mayor o igual a cero.
             */

            if ($param_col == 2) {
                $code_error = "B.1";

                if (empty($parameterArr[$i]['fieldValue']) && $parameterArr[$i]['fieldValue'] != 0) {
                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                } else {
                    $return = validate_two_decimals($parameterArr[$i]['fieldValue'], $decimal, $positive_validation);
                    if ($return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                }
            }

            /* OTORGAMIENTO_PERIODO_PREVIO
             * Nro C.1
             * Detail:
             * La celda no puede estar vacía. Debe contener formato numérico con hasta dos decimales. Debe ser mayor o igual a cero.
             */

            if ($param_col == 3) {
                $code_error = "C.1";

                if (empty($parameterArr[$i]['fieldValue']) && $parameterArr[$i]['fieldValue'] != 0) {
                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                } else {
                    $return = validate_two_decimals($parameterArr[$i]['fieldValue'], $decimal, $positive_validation);
                    if ($return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                }
            }

            /* OTORGAMIENTO_PERIODO_PREVIO
             * Nro D.1
             * Detail:
             * La celda no puede estar vacía. Debe contener formato numérico con hasta dos decimales. Debe ser mayor o igual a cero.
             */

            if ($param_col == 4) {
                $code_error = "D.1";

                if (empty($parameterArr[$i]['fieldValue']) && $parameterArr[$i]['fieldValue'] != 0) {
                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                } else {
                    $return = validate_two_decimals($parameterArr[$i]['fieldValue'], $decimal, $positive_validation);
                    if ($return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                }
            }


            if ($i > 4) {
                $result = array();
                $code_error = "VG.1";
                $result[] = return_error_array($code_error, 2, '-');
            }
        } // END FOR LOOP->





        /* debug($result);
          exit(); */

        $this->data = $result;
    }

}
