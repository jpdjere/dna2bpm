<?php

class Lib_123_data extends MX_Controller {
    /* VALIDADOR ANEXO 12 */

    public function __construct($parameter) {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('sgr/tools');
        $this->load->model('sgr/sgr_model');
        
        $model_anexo = "model_12";
        $this->load->Model($model_anexo);

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
             *
             * @example NRO_ORDEN	DIA1	DIA2	DIA3	DIA4	DIA5	DIA6	DIA7	DIA8	DIA9	DIA10	DIA11	DIA12	DIA13	DIA14	DIA15	DIA16	DIA17	DIA18	DIA19	DIA20	DIA21	DIA22	DIA23	DIA24	DIA25	DIA26	DIA27	DIA28	DIA29	DIA30	DIA31
             * */
            for ($i = 0; $i <= count($parameterArr); $i++) {

                /* NRO_ORDEN
                 * Nro A.1
                 * Detail:
                 * El Número de garantía debe estar informado en el sistema y corresponder con alguno de los siguientes tipos de garantías:
                  GFMFO
                  GC1
                  GC2
                  GT
                 */

                if ($parameterArr[$i]['col'] == 1) {

                    $A_cell_value = "";
                    $code_error = "A.1";
                    //empty field Validation
                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                        array_push($stack, $result);
                    } else {
                        $A_cell_value = $parameterArr[$i]['fieldValue'];
                        $warranty_info = $this->$model_anexo->get_order_number($parameterArr[$i]['fieldValue']);
                        foreach ($warranty_info as $order_number){
                            var_dump($order_number[5214);
                            
                        }
                        
                        if (!$warranty_info) {
                            var_dump($parameterArr[$i]['fieldValue'],$warranty_info);
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], "No está registrado en el sistema el Nro de Orden:" . $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        } else {
                            $allow_words = array("GFMFO", "GC1", "GC2", "GT");
                            $return = check_word($parameterArr[$i]['fieldValue'], $allow_words);
                            if ($return) {
                                $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                                array_push($stack, $result);
                            }
                        }
                    }
                }

                /* DIA
                 * Nro B.1
                 * Detail:
                 * Los montos informados deben ser menores o iguales al Monto de Garantía Otorgado registrado en el Sistema. 
                 * Nro B.2
                 * Detail:
                 * Si algún día el saldo estuvo en Cero, deben informar “0”. Ningún campo puede estar vacío. 
                 */

                if ($parameterArr[$i]['col'] == 2) {
                    $code_error = "B.2";
                    //empty field Validation
                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {


                        $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                        array_push($stack, $result);
                    }
                }

                $range = range(2, 32);
                if (in_array($parameterArr[$i]['col'], $range)) {

//                    for ($j = 2; $j <= 32; $j++) {
//                        echo $i;
//                    }



                    $code_error = "B.2";
                    //empty field Validation
                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {


                        $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                        array_push($stack, $result);
                    }
                }
            } // END FOR LOOP->
        }
        var_dump($stack);
        exit();
        $this->data = $stack;
    }

}

