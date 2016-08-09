<?php

class Lib_125_data extends MX_Controller {
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
        $vg2_arr = array();



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

            /* CUIT_PART
             * Nro A.1
             * Detail:
             * Debe tener 11 caracteres sin guiones.
             * Nro A.2
             * Detail:
             * Debe figura en el Sistema con Garantías Otorgadas en el Sistema (Anexo 12)
             */

            if ($param_col == 1) {
                $A_cell_value = "";

                $code_error = "A.1";
                $sharer_info = $this->model_12->get_sharer_left($parameterArr[$i]['fieldValue']);
                //  var_dump($sharer_info);
                $return = check_empty($parameterArr[$i]['fieldValue']);
                if ($return) {
                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                } else {
                    $A_cell_value = $parameterArr[$i]['fieldValue'];

                    $return = cuit_checker($parameterArr[$i]['fieldValue']);
                    if (!$return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }

                    if (!$sharer_info) {
                        $code_error = "A.2";
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
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

            if ($param_col == 2) {
                $code_error = "B.1";
                //empty field Validation
                $return = check_empty($parameterArr[$i]['fieldValue']);
                if ($return) {
                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                } else {

                    $vg2_arr[] = $parameterArr[$i]['fieldValue'] . "*" . $A_cell_value;

                    $return = cuit_checker($parameterArr[$i]['fieldValue']);
                    if (!$return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }

                    
                    $code_error = "B.2";
                    $creditor_info = $this->model_12->get_creditor($A_cell_value, $parameterArr[$i]['fieldValue']);
                    if (!$creditor_info) {
                          $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            
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
            if ($param_col == 3) {
                //empty field Validation
                $code_error = "C.1";



                $haygarantia = false;
                $C2_array = array("GFEF0", "GFEF1", "GFEF2", "GFEF3", "GFOI0", "GFOI1", "GFOI2", "GFOI3", "GFP0", "GFP1", "GFP2", "GFP3", "GFCPD", "GFFF0", "GFFF1", "GFFF2", "GFFF3", "GFON0", "GFON1", "GFON2", "GFON3", "GFVCP", "GFMFO", "GFL0", "GFL1", "GFL2", "GFL3", "GFPB0", "GFPB1", "GFPB2",
                    "I.1.1", "I.1.2", "I.1.3", "I.1.4", "I.2.1", "I.2.2", "I.2.3", "I.2.4", "I.3.1", "I.3.2", "I.3.3", "I.3.4", "I.4.1", "I.4.2", "I.4.3", "I.5.1", "I.5.2", "I.5.3", "I.5.4", "I.6.1", "I.6.2", "I.7.1", "I.7.2", "I.8", "FINANCIERA");
                foreach ($sharer_info as $info) {

                    $warranty_type = (array) clean_spaces($info['5216']);

                    if (in_array($warranty_type[0], $C2_array)) {
                        $haygarantia = true;
                    }
                }

                $int_value = (int) $parameterArr[$i]['fieldValue'];
                if ($haygarantia) {
                    // garantia activa campo mayor a cero  
                    $code_error = "C.1";
                    // Empty
                    if (empty($parameterArr[$i]['fieldValue']) && $parameterArr[$i]['fieldValue'] != '0') {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                    } else {
                        // Check positive
                        $return = check_decimal($parameterArr[$i]['fieldValue'], 2, true);
                        $code_error = "C.2";
                        if ($return) {
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue'] . " (" . $warranty_type[0] . ")");
                        }
                    }
                } else {
                    // sin garantia
                    $code_error = "C.1";
                    if ($int_value != 0) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "(" . $warranty_type[0] . ") - " . $parameterArr[$i]['fieldValue']);
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
            if ($param_col == 4) {
                //empty field Validation

                $haygarantia = false;
                $D1_array = array("GC1", "GC2", "II.1.1", "II.1.2", "II.1.3a", "II.1.3b", "II.1.4", "II.2.1", "II.2.2", "II.2.3a", "II.2.3b", "II.2.4", "COMERCIAL");
                foreach ($sharer_info as $info) {
                    $warranty_type = (array) clean_spaces($info['5216']);
                    if (in_array($warranty_type[0], $D1_array)) {
                        $haygarantia = true;
                    }
                }

                $int_value = (int) $parameterArr[$i]['fieldValue'];



                if ($haygarantia) {
                    // garantia activa                   

                    if ($int_value != 0) {
                        // check D.2
                        $code_error = "D.2";
                        $return = check_decimal($parameterArr[$i]['fieldValue'], 2, true);
                        if ($return) {
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue'] . " (" . $warranty_type[0] . ")");
                        }
                    }
                } else {
                    // Garantia no activa
                    $code_error = "D.1";
                    if ($int_value != 0) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "..." . $parameterArr[$i]['fieldValue']);
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
            if ($param_col == 5) {

                $haygarantia = false;
                $E1_array = array("GT", "III.1.1", "III.1.2", "III.1.3", "III.2.1", "III.2.2", "III.2.3", "III.2.4", "III.3", "TECNICA");
                foreach ($sharer_info as $info) {
                    $warranty_type = (array) clean_spaces($info['5216']);
                    if (in_array($warranty_type[0], $E1_array)) {
                        $haygarantia = true;
                    }
                }

                $int_value = (int) $parameterArr[$i]['fieldValue'];
                if ($haygarantia) {
                    // garantia activa campo mayor a cero  
                    //empty field Validation
                   if (empty($parameterArr[$i]['fieldValue']) && $parameterArr[$i]['fieldValue'] != '0') {
                        $code_error = "E.1";
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                    } else {
                        // Check positivo
                        $code_error = "E.2";
                        $return = check_decimal($parameterArr[$i]['fieldValue'], 2, true);
                        if ($return) {
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue'] . " (" . $warranty_type[0] . ")");
                        }
                    }
                } else {
                    // sin garantia
                    $code_error = "E.1";
                    if ($int_value != 0) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                }
            }
        } // END FOR LOOP->


        /* EXTRA VALIDATION VG.2 */
        foreach (repeatedElements($vg2_arr) as $arr) {
            $result = array();
            $code_error = "VG.2";
            list($creditor, $sharer) = explode('*', $arr['value']);
            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "CUIT Participe: " . $sharer . ",  Acreedor: " . $creditor);
        }

        /*  debug($result);
          exit(); */

        $this->data = $result;
    }

}
