<?php

class Lib_150_data extends MX_Controller {
   
         
    /* VALIDADOR ANEXO 150 */

    public function __construct($parameter) {
    
        
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('sgr/tools');
        $this->load->model('sgr/sgr_model');
        
        
        $dolar_futuro = false;

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






        /**
         * BASIC VALIDATION
         * 
         * @param 
         * @type PHP
         * @name ...
         * @author Diego             
         * @example 
         * INCISO_ART_25	
         * IDENTIFICACION	
         * CUIT_EMISOR	
         * CUIT_DEPOSITARIO	
         * MONEDA	
         * MONTO
         * */
        for ($i = 0; $i <= count($parameterArr); $i++) {
          

            $param_col = (isset($parameterArr[$i]['col'])) ? $parameterArr[$i]['col'] : 0;

            /* INCISO_ART_25
             * Nro A.1
             * Detail:
             * Debe estar compuesta por alguno de los parámetros establecidos en la Columna A de Anexo adjunto (OPCIONES DE INVERSIÓN) a tales efectos.   
             */
            if ($param_col == 1) {
                $code_error = "A.1";
                //empty field Validation                    
                $return = check_empty($parameterArr[$i]['fieldValue']);
                if ($return) {
                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                } else {
                    $A_cell_value = $parameterArr[$i]['fieldValue'];
                    $options = $this->sgr_model->get_investment_options($parameterArr[$i]['fieldValue']);
                    if (!$options) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                }
            }

            /* IDENTIFICACION
             * Nro B.1
             * Detail:
             * Para cada Opción de Inversión informada en la Columna A del importador, sólo puede aceptar la identificación asociada a cada una de ellas en la Columna C del archivo “ANEXO 15 - PARAMETRIZACIÓN DESCRIPCIONES OPCIONES DE INVERSIÓN”.
             * Nro B.2
             * Detail:
             * Si la IDENTIFICACION informada en la Columna B del IMPORTADOR es “FUTURO DOLAR”, debe existir en el mismo archivo la IDENTIFICACION “REGULARIZACION FUTURO DOLAR”.
            */
            if ($param_col == 2) {
                $code_error = "B.1";
                //empty field Validation                    
                $return = check_empty($parameterArr[$i]['fieldValue']);
                if ($return) {
                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                } else {
                    
                    $options = $this->sgr_model->get_investment_options_parametrization($A_cell_value, $parameterArr[$i]['fieldValue']);
                    if (!$options) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                    
                    /*"B.2"*/
                    if($parameterArr[$i]['row']=="FUTURO DOLAR")
                            $dolar_futuro = true;
                    
                    
                }
            }
            

            /* CUIT_EMISOR
             * Nro C.1
             * Detail:
             * En caso de que la OPCIÓN DE INVERSIÓN indicada en la Columna A del importador sea A, B, C, D, E, I, J, K ó L este campo deberá estar vacío.
             * Nro C.2
             * Detail:
             * Si la opción INCISO_ART_25 (columna A) es G, solo puede aceptar las CUIT’s del archivo ANEXO 15 - CUIT PAISES INCISO G.
             */
            if ($param_col == 3) {
                $code_error = "C.1";
                $A1_arr = array("A", "B", "C", "D", "E", "I", "J", "K" , "L");
                if (in_array($A_cell_value, $A1_arr)) {
                    $return = check_for_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                } else {
                    $code_error = "C.2";
                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                    } else {
                        
                        if($A_cell_value=="G"){
                            $get_value = $this->sgr_model->get_cuit_paises_inciso_g($parameterArr[$i]['fieldValue']);
                            if (!$get_value) 
                                $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);    
                            }
                    }
                }
            }

            

            /* CUIT_DEPOSITARIO
             * Nro D.1
             * Detail:
             * Debe tener 11 caracteres numéricos sin guiones. Debe validar que se corresponda con alguno de los CUIT detallados en el ANEXO 15 - CUIT ENTIDADES DEPOSITARIAS, donde se listan las ENTIDADES DEPOSITARIAS habilitadas a tales efectos.
             * Nro D.2
             * Detail:
             * Si y solo si la opción INCISO_ART_25 (columna A) es H, debe permitir que el CUIT Entidad depositaria sea 30528994012 - MERCADO A TERMINO DE ROSARIO SA, además de los establecidos en el listado OPCIONES DE INVERSIÓN.
             */
            if ($param_col == 4) {
                /* G.2 */
                if ($parameterArr[$i]['fieldValue'] == '30528994012') {
                    echo 'Valor:' . $parameterArr[$i]['fieldValue'] . '</br>';
                    if ($A_cell_value != 'H') {
                        $code_error = "D.2";
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                } else {
                    $code_error = "D.1";
                    //empty field Validation                    
                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                    }

                    $get_depositories = $this->sgr_model->get_depositories($parameterArr[$i]['fieldValue']);
                    $get_cuit_ext_company = $this->sgr_model->get_cuit_ext_company($parameterArr[$i]['fieldValue']);

                    $get_value = ($get_depositories) ? $get_depositories : $get_cuit_ext_company;

                    if (!$get_value) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                }
            }

            /* MONEDA
             * Nro E.1
             * Detail:
             * Debe contener uno de los siguientes parámetros:Pesos Argentinos,Dolares Americanos
             * Nro E.2
             * Detail:
             * Si la opción INCISO_ART_25 (columna A) del archivo ANEXO 15 – IMPORTADOR  es G, solo puede aceptar Dolares Americanos
             */
            if ($param_col == 5) {

                $code_error = "E.1";

                //empty field Validation
                $return = check_empty($parameterArr[$i]['fieldValue']);
                if ($return) {
                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                }
                
                if($A_cell_value=="G"){
                    $code_error = "E.1";
                    $allow_words = array("DOLARES AMERICANOS"); 
                } else{
                    $allow_words = array("PESOS ARGENTINOS", "DOLARES AMERICANOS");
                } 

                if (isset($parameterArr[$i]['fieldValue'])) {
                    $return = check_word($parameterArr[$i]['fieldValue'], $allow_words);
                    if ($return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                }
            }

            /* MONTO
             * Nro F.1
             * Detail: 
             * Debe ser mayor a cero, tener formato numérico y aceptar hasta dos decimales.
             * Nro F.2
             * Detail: 
             * Si en la columna A ingresaron la opción D o K, debe permitir valores entre -1300000 y 99999999999, con formato numérico y aceptar hasta dos decimales.
             * Nro F.3
             * Detail:
             * Si la IDENTIFICACIÓN (columna B del IMPORTADOR) es “REGULARIZACION FUTURO DOLAR”, el monto debe ser el mismo de la IDENTIFICACION “FUTURO DOLAR”, pero en negativo.Si
             * Nro F.4
             * Detail:
             * Debe validar que la suma total de las inversiones sea igual al Saldo de la Columna 7 – Saldo del Aporte Disponible, de la Impresión del Anexo 20.2 más el saldo de la Columna D del importado de dicho Anexo.
             */

            if ($param_col == 6) {
                $code_error = "F.1";

                $return = check_empty($parameterArr[$i]['fieldValue']);
                if ($return) {
                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                } else {

                    $I_cell_value = (int) $parameterArr[$i]['fieldValue'];

                    $A_cell_value_arr = array('D', 'K');
                    if (!in_array($A_cell_value, $A_cell_value_arr)) {
                        $code_error = "F.2";
                        $return = check_decimal($parameterArr[$i]['fieldValue'], 2, true);
                        if ($return) {
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                        }
                    } else {

                        if ($I_cell_value < -1300000) {                            
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                        } else {
                            $return = check_decimal($parameterArr[$i]['fieldValue'], 2);
                            if ($return) {
                                $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            }
                        }
                    }

                   
                }
            }
        } // END FOR LOOP->
        /*var_dump($result);        exit();*/
        $this->data = $result;
    }

}
