<?php

class Lib_15_data extends MX_Controller {
    /* VALIDADOR ANEXO 12.5 */

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
         * DESCRIPCION	
         * IDENTIFICACION	
         * EMISOR	
         * CUIT_EMISOR	
         * ENTIDAD_DESPOSITARIA	
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

            /* DESCRIPCION
             * Nro B.1
             * Detail:
             * Se puede completar la cantidad de filas que sean necesarias. Si una fila se completa, todos sus campos deben estar llenos.                
             */
            if ($param_col == 2) {
                $code_error = "B.1";
                //empty field Validation                    
                $return = check_empty($parameterArr[$i]['fieldValue']);
                if ($return) {
                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                } else {
                    /*"B.2"*/
                    if($parameterArr[$i]['row']=="FUTURO DOLAR")
                        $dolar_futuro = true;    
                }
            }

            /* IDENTIFICACION
             * Nro C.1
             * Detail:
             * Se puede completar la cantidad de filas que sean necesarias. Si una fila se completa, todos sus campos deben estar llenos.                 
             */
            if ($param_col == 3) {
                $code_error = "C.1";
                //empty field Validation                    
                $return = check_empty($parameterArr[$i]['fieldValue']);
                if ($return) {


                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                }
            }

            /* EMISOR
             * Nro D.1
             * Detail:
             * En caso de que el CUIT de la Columna E del importador ya está registrado en la Base de Datos del Sistema, este tomará en cuenta el nombre allí registrado. En caso contrario, se mantendrá provisoriamente el nombre informado por la SGR. De no estar registrado algún CUIT, se deberá agregar al Sistema a la lista EMISORES DE OPCIONES DE INVERSIÓN (HOY AÚN NO EXISTE) así queda registrado para el futuro.
             * Nro D.2
             * Detail:
             * En caso de que la OPCIÓN DE INVERSIÓN indicada en la Columna A sea D, J o K, este campo deberá estar vacío.
             */
            if ($param_col == 4) {

                $code_error = "D.2";
                $A1_arr = array("D", "J", "K");
                if (in_array($A_cell_value, $A1_arr)) {
                    $return = check_for_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                }
            }

            /* CUIT_EMISOR
             * Nro E.1
             * Detail:
             * Debe tener 11 caracteres numéricos sin guiones. Se le debe aplicar el Algoritmo Verificador de CUIT de forma de verificar que sea un CUIT existente.
             * Nro E.1
             * Detail:
             * En caso de que la OPCIÓN DE INVERSIÓN indicada en la Columna A sea D, J o K, este campo deberá estar vacío. 
             */
            if ($param_col == 5) {
                $code_error = "E.3";
                $A1_arr = array("D", "J", "K");
                if (in_array($A_cell_value, $A1_arr)) {
                    $return = check_for_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                } else {
                    $code_error = "E.1";
                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                    } else {
                        $return = cuit_checker($parameterArr[$i]['fieldValue']);
                        $get_value2 = $this->sgr_model->get_cuit_ext_company($parameterArr[$i]['fieldValue']);
                        $get_value = ($return) ? $return : $get_value2;

                        if (!$get_value) {
                            $code_error = "E.2";
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                        }
                    }
                }
            }

            /* ENTIDAD_DESPOSITARIA
             * Nro F.1
             * Detail:
             * Se puede completar la cantidad de filas que sean necesarias. Si una fila se completa, todos sus campos deben estar llenos.
             */
            if ($param_col == 6) {
                //empty field Validation
                $code_error = "F.1";
                //empty field Validation                    
                $return = check_empty($parameterArr[$i]['fieldValue']);
                if ($return) {
                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                }
                
                $code_error = "F.2";
                
            }

            /* CUIT_DEPOSITARIO
             * Nro G.1
             * Detail:
             * Debe estar compuesta por alguno de los parámetros establecidos en la Columna A de Anexo adjunto (OPCIONES DE INVERSIÓN) a tales efectos.                 
             */
            if ($param_col == 7) {
                /* G.2 */
                if ($parameterArr[$i]['fieldValue'] == '30528994012') {
                    echo 'Valor:' . $parameterArr[$i]['fieldValue'] . '</br>';
                    if ($A_cell_value != 'H') {
                        $code_error = "G.2";
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                } else {
                    $code_error = "G.1";
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
             * Nro H.1
             * Detail:
             * Numero entero mayor a cero.
             */
            if ($param_col == 8) {

                $code_error = "H.1";

                //empty field Validation
                $return = check_empty($parameterArr[$i]['fieldValue']);
                if ($return) {
                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                }

                if (isset($parameterArr[$i]['fieldValue'])) {
                    $allow_words = array("PESOS ARGENTINOS", "DOLARES AMERICANOS");
                    $return = check_word($parameterArr[$i]['fieldValue'], $allow_words);
                    if ($return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                }
            }

            /* MONTO
             * Nro I.1
             * Detail: 
             * Aceptar hasta dos decimales.
             * Nro I.2
             * Detail: 
             * Debe validar que la suma total de las inversiones sea igual al Saldo de la Columna 7 – Saldo del Aporte Disponible, de la Impresión del Anexo 20.2 más el saldo de la Columna D del importado de dicho Anexo.
             */

            if ($param_col == 9) {
                $code_error = "I.1";

                $return = check_empty($parameterArr[$i]['fieldValue']);
                if ($return) {
                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                } else {

                    $I_cell_value = (int) $parameterArr[$i]['fieldValue'];

                    $A_cell_value_arr = array('D', 'K', 'H');
                    if (!in_array($A_cell_value, $A_cell_value_arr)) {
                        $return = check_decimal($parameterArr[$i]['fieldValue'], 2, true);
                        if ($return) {
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                        }
                    } else {
                        $code_error = "I.B.1";

                        if ($I_cell_value < -20000000) {                            
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                        } else {
                            $return = check_decimal($parameterArr[$i]['fieldValue'], 2);
                            if ($return) {
                                $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            }
                        }
                    }

                    $code_error = "I.2";
                    //Valida contra Mongo
                }
            }
        } // END FOR LOOP->
        $this->data = $result;
    }

}
