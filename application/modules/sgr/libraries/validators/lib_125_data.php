<?php

class Lib_125_data extends MX_Controller {
    /* VALIDADOR ANEXO 12.5 */

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
             * @author Diego             *
             * @example CUIT_PART	CUIT_ACREEDOR	SLDO_FINANC	SLDO_COMER	SLDO_TEC
             * */
            for ($i = 0; $i <= count($parameterArr); $i++) {

                /* CUIT_PART
                 * Nro A.1
                 * Detail:
                 * Debe tener 11 caracteres sin guiones.
                 * Nro A.2
                 * Detail:
                 * Debe figura en el Sistema con Garantías Otorgadas en el Sistema (Anexo 12)
                 */

                if ($parameterArr[$i]['col'] == 1) {
                    $A_cell_value = "";
                    $code_error = "A.1";
                    $sharer_info = $this->$model_anexo->get_sharer_left($parameterArr[$i]['fieldValue']);
                          //  var_dump($sharer_info);
                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                        array_push($stack, $result);
                    } else {
                        $A_cell_value = $parameterArr[$i]['fieldValue'];

                        $return = cuit_checker($parameterArr[$i]['fieldValue']);
                        if (!$return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }

                        if (!$sharer_info) {
                            $code_error = "A.2";
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }
                    }
                }

                /* CUIT_ACREEDOR
                 * Nro B.1
                 * Detail:
                 * Debe tener 11 caracteres sin guiones.
                 * Nro B.2
                 * Detail:
                 * Debe estar registrado en el Sistema asociado al CUIT del Socio Partícipe informado al menos en una garantía otorgada.
                 */

                if ($parameterArr[$i]['col'] == 2) {
                    $code_error = "B.1";
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
                        }

                        $code_error = "B.2";
                        $creditor_info = $this->$model_anexo->get_creditor($A_cell_value, $parameterArr[$i]['fieldValue']);
                        if (!$creditor_info) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }
                    }
                }

                /* SLDO_FINANC
                 * Nro C.1
                 * Detail:
                 * Formato de número. Acepta hasta dos decimales.
                 * Nro C.2
                 * Detail:
                 * El campo sólo podría ser mayor a Cero sólo en caso de que en el sistema esté registrado que el Socio Partícipe haya recibido alguno de los siguientes tipos de garantías: GFEF0, GFEF1, GFEF2, GFEF3, GFOI0, GFOI1, GFOI2, GFOI3, GFP0, GFP1, GFP2, GFP3, GFCPD, GFFF0, GFFF1, GFFF2, GFFF3, GFON0, GFON1, GFON2, FON3, GFVCP, GFMFO, GFL0, GFL1, GFL2, GFL3, GFPB0, GFPB1 o GFPB2.
                 */
                if ($parameterArr[$i]['col'] == 3) {
                    //empty field Validation
                    $code_error = "C.1";


                    
                        $haygarantia = false;
                        $C2_array = array("GFEF0", "GFEF1", "GFEF2", "GFEF3", "GFOI0", "GFOI1", "GFOI2", "GFOI3", "GFP0", "GFP1", "GFP2", "GFP3", "GFCPD", "GFFF0", "GFFF1", "GFFF2", "GFFF3", "GFON0", "GFON1", "GFON2", "FON3", "GFVCP", "GFMFO", "GFL0", "GFL1", "GFL2", "GFL3", "GFPB0", "GFPB1", "GFPB2");
                        foreach ($sharer_info as $info) { 
                            if (in_array($info['5216'][0], $C2_array)) {
                                $haygarantia = true;
                            }
                        }

                        $int_value = (int) $parameterArr[$i]['fieldValue'];
                        if ($haygarantia) {
                            // garantia activa campo mayor a cero  
                            $code_error = "C.1";
                                // Empty
                                $return = check_empty($parameterArr[$i]['fieldValue']);
                                if ($return) {
                                    $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                                    array_push($stack, $result);
                                }else{
                                    // Check positive
                                    $return = check_decimal($parameterArr[$i]['fieldValue'], 2, true);
                                    $code_error = "C.2";
                                    if ($return) {
                                        $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue'] . " (".$info['5216'][0].")");
                                        array_push($stack, $result);
                                    }
                                }       
                        }else{
                            // sin garantia
                            $code_error = "C.1";
                            if ($int_value!=0) {
                                $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                                array_push($stack, $result);
                            }

                        }



                    


                    //Valida contra Mongo
                }

                /* SLDO_COMER
                 * Nro D.1
                 * Detail:
                 * Formato de número. Acepta hasta dos decimales.
                 * Nro D.2
                 * Detail:
                 * El campo sólo podría ser mayor a Cero sólo en caso de que en el sistema esté registrado que el Socio Partícipe haya recibido alguno de los siguientes tipos de garantías: GC1 o GC2.
                 */
                if ($parameterArr[$i]['col'] == 4) {
                     //empty field Validation

                        $haygarantia = false;
                        $D1_array = array("GC1", "GC2");
                        foreach ($sharer_info as $info) { 
                            if (in_array($info['5216'][0], $D1_array)) {
                                $haygarantia = true;
                            }
                        }

                        $int_value = (int) $parameterArr[$i]['fieldValue'];
                        if ($haygarantia) {
                            // garantia activa           
                            $return = check_empty($parameterArr[$i]['fieldValue']);                     
                            if ($return) {
                                // Check empty
                                $code_error = "D.1";
                                $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                                array_push($stack, $result);
                            }else{
                                // check D.2
                                $code_error = "D.2";
                                $return = check_decimal($parameterArr[$i]['fieldValue'], 2, true);
                                if ($return) {
                                    $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue'] . " (".$info['5216'][0].")");
                                    array_push($stack, $result);
                                }
                            }                           
                        }else{
                            // Garantia no activa
                            $code_error = "D.1";
                            if ($int_value!=0) {
                                $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                                array_push($stack, $result);
                            }

                        }
                    
                }

                /* SLDO_TEC
                 * Nro E.1
                 * Detail:
                 * Formato de número. Acepta hasta dos decimales.
                 * Nro E.2
                 * Detail:
                 * El campo sólo podría ser mayor a Cero sólo en caso de que en el sistema esté registrado que el Socio Partícipe haya recibido alguno de los siguientes tipos de garantías: GT
                 */
                if ($parameterArr[$i]['col'] == 5) {

                    $haygarantia = false;
                    $E1_array = array("GT");
                    foreach ($sharer_info as $info) { 
                        if (in_array($info['5216'][0], $E1_array)) {
                            $haygarantia = true;
                        }
                    }

                    $int_value = (int) $parameterArr[$i]['fieldValue'];
                    if ($haygarantia) {
                        // garantia activa campo mayor a cero  
                            //empty field Validation
                            $return = check_empty($parameterArr[$i]['fieldValue']);
                            if ($return) {
                                $code_error = "E.1";
                                $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                                array_push($stack, $result);
                            } else{
                                // Check positivo
                                $code_error = "E.2";
                                $return = check_decimal($parameterArr[$i]['fieldValue'], 2, true);
                                if ($return) {
                                    $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue'] . " (".$info['5216'][0].")");
                                    array_push($stack, $result);
                                }
                            }    
                    }else{
                        // sin garantia
                        $code_error = "E.1";
                        if ($int_value!=0) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }

                    }
                    
                }
            } // END FOR LOOP->
        }

    $result = return_error_array("-", "-", "--Dummy--");
    array_push($stack, $result);
                            
        $this->data = $stack;
    }

}
