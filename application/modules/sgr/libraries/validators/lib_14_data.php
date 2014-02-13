<?php

class Lib_14_data extends MX_Controller {
    /* VALIDADOR ANEXO 14 */

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
             * FECHA_MOVIMIENTO	NRO_GARANTIA	CAIDA	RECUPERO	INCOBRABLES_PERIODO	GASTOS_EFECTUADOS_PERIODO	RECUPERO_GASTOS_PERIODO	GASTOS_INCOBRABLES_PERIODO
             * */
            for ($i = 0; $i <= count($parameterArr); $i++) {

                /* FECHA_MOVIMIENTO
                 * Nro A.1
                 * Detail:
                 * Debe tener formato numérico de hasta 5 dígitos.
                 * Nro A.2
                 * Detail:
                 * La fecha debe estar dentro del período informado.
                 */

                if ($parameterArr[$i]['col'] == 1) {
                    $code_error = "A.1";
                    //empty field Validation
                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result["error_code"] = $code_error;
                        $result["error_row"] = $parameterArr[$i]['row'];
                        $result["error_input_value"] = "empty";
                        array_push($stack, $result);
                    }
                    //Check Date Validation
                    if (isset($parameterArr[$i]['fieldValue'])) {
                        $return = check_date_format($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                            array_push($stack, $result);
                        }

                        $code_error = "A.2";
                        /* PERIOD */
                        $return = check_period($parameterArr[$i]['fieldValue'], $this->session->userdata['period']);
                        if ($return) {
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                            array_push($stack, $result);
                        }
                    }
                }

                /* NRO_GARANTIA
                 * Nro B.1
                 * Detail:
                 * Si se está informando la CAÍDA de una Garantía (Columna C del importador), debe validar que el número de garantía se encuentre registrado en el Sistema como que fue otorgada (Anexo 12).
                 * Nro B.2
                 * Detail:
                 * Si se está informando un RECUPERO (Columna D del importador), debe validar que el número de garantía registre previamente en el sistema (o en el mismo archivo que se está importando) una caída.
                 * Nro B.3
                 * Detail:
                 * Si se está informando un INCOBRABLE (Columna E del importador), debe validar que el número de garantía registre previamente en el sistema (o en el mismo archivo que se está importando) una caída.
                 * Nro B.4
                 * Detail: 
                 * Si se está informando un GASTOS POR GESTIÓN DE RECUPERO (Columna F), debe validar que el número de garantía registre previamente en el sistema (o en el mismo archivo que se está importando) una caída.
                 * Nro B.5
                 * Detail: 
                 * Si se está informando un RECUPERO DE GASTOS POR GESTIÓN DE RECUPERO (Columna G), debe validar que el número de garantía registre previamente en el sistema (o en el mismo archivo que se está importando) un GASTO POR GESTIÓN DE RECUPERO.
                 * Nro B.6
                 * Detail: 
                 * Si se está informando un INCOBRABLE DE GASTOS POR GESTIÓN DE RECUPERO (Columna G), debe validar que el número de garantía registre previamente en el sistema (o en el mismo archivo que se está importando) un GASTO POR GESTIÓN DE RECUPERO.
                 */

                if ($parameterArr[$i]['col'] == 2) {
                    $code_error = "B.1";
                    //empty field Validation
                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result["error_code"] = $code_error;
                        $result["error_row"] = $parameterArr[$i]['row'];
                        $result["error_input_value"] = "empty";
                        array_push($stack, $result);
                    } else {
                        $warranty_info = $this->sgr_model->get_warranty_data($parameterArr[$i]['fieldValue'], $this->session->userdata['period']); 
                        var_dump($warranty_info, $parameterArr[$i]['fieldValue']);
                        
                    }
                    
                    
                }

                /* CAIDA
                 * Nro C.1
                 * Detail:
                 * Formato de número. Debe ser un valor numérico y aceptar hasta 2 decimales.
                 * Nro C.2
                 * Detail:
                 * En caso de que la garantía haya sido otorgada en PESOS, debe validar que el importe sea menor o igual al Monto de la Garantía Otorgada informada mediante Anexo 12 registrado en el Sistema.
                 * Nro C.3
                 * Detail:
                 * En  caso de que la garantía haya sido otorgada en DÓLARES debe validar que el importe aquí informado sea menor o igual al Monto de la Garantía Otorgada informado mediante Anexo 12 registrado en el Sistema, dividido por el TIPO DE CAMBIO DEL día anterior al que fue otorgada la garantía y multiplicado por el TIPO DE CAMBIO del día anterior al que se está informando que se cayó la garantía.
                 */
                if ($parameterArr[$i]['col'] == 3) {
                    //empty field Validation
                    $code_error = "C.1";
                    //empty field Validation
                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result["error_code"] = $code_error;
                        $result["error_row"] = $parameterArr[$i]['row'];
                        $result["error_input_value"] = "empty";
                        array_push($stack, $result);
                    }
                    //Check Date Validation
                    if (isset($parameterArr[$i]['fieldValue'])) {
                        $return = check_decimal($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                            array_push($stack, $result);
                        }

                        $code_error = "C.2";
                        //Valida contra Mongo
                        
                         $code_error = "C.3";
                        //Valida contra Mongo
                    }
                }

                /* RECUPERO
                 * Nro D.1
                 * Detail:
                 * Formato de número. Debe ser un valor numérico y aceptar hasta 2 decimales.
                 * Nro D.2
                 * Detail:
                 * Debe validar que el número de garantía registre previamente en el sistema (o en el mismo archivo que se está importando) una caída.
                 * Nro D.3
                 * Detail:
                 * Debe validar que la suma de todos los RECUPEROS e INCOBRABLES registrados en el Sistema (incluidos los informados  en el archivo que se está importando) para una misma garantía no supere la suma de todas las caídas de esa misma garantía registradas en el Sistema (incluidos los informados  en el archivo que se está importando).
                 */
                if ($parameterArr[$i]['col'] == 4) {
                    //empty field Validation
                    $code_error = "D.1";
                    //empty field Validation
                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result["error_code"] = $code_error;
                        $result["error_row"] = $parameterArr[$i]['row'];
                        $result["error_input_value"] = "empty";
                        array_push($stack, $result);
                    }
                    //Check Date Validation
                    if (isset($parameterArr[$i]['fieldValue'])) {
                        $return = check_decimal($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                            array_push($stack, $result);
                        }

                        $code_error = "D.2";
                        //Valida contra Mongo
                        
                         $code_error = "D.3";
                        //Valida contra Mongo
                    }
                }

                /* INCOBRABLES_PERIODO
                 * Nro E.1
                 * Detail:
                 * Formato de número. Debe ser un valor numérico y aceptar hasta 2 decimales.
                 * Nro E.2
                 * Detail:
                 * Debe validar que el número de garantía registre previamente en el sistema (o en el mismo archivo que se está importando) una caída.
                 * Nro E.3
                 * Detail:
                 * Debe validar que la suma de todos los RECUPEROS e INCOBRABLES registrados en el Sistema (incluidos los informados  en el archivo que se está importando) para una misma garantía no supere la suma de todas las caídas de esa misma garantía registradas en el Sistema (incluidos los informados  en el archivo que se está importando).
                 */
                if ($parameterArr[$i]['col'] == 5) {
                    //empty field Validation
                    $code_error = "E.1";
                    //empty field Validation
                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result["error_code"] = $code_error;
                        $result["error_row"] = $parameterArr[$i]['row'];
                        $result["error_input_value"] = "empty";
                        array_push($stack, $result);
                    }
                    //Check Date Validation
                    if (isset($parameterArr[$i]['fieldValue'])) {
                        $return = check_decimal($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                            array_push($stack, $result);
                        }

                        $code_error = "E.2";
                        //Valida contra Mongo
                        
                         $code_error = "E.3";
                        //Valida contra Mongo
                    }
                }

                /* INCOBRABLES_PERIODO
                 * Nro F.1
                 * Detail:
                 * Formato de número. Debe ser un valor numérico y aceptar hasta 2 decimales.
                 * Nro F.2
                 * Detail:
                 * Debe validar que el número de garantía registre previamente en el sistema (o en el mismo archivo que se está importando) una caída.
                 */
                if ($parameterArr[$i]['col'] == 6) {
                    //empty field Validation
                    $code_error = "F.1";
                    //empty field Validation
                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result["error_code"] = $code_error;
                        $result["error_row"] = $parameterArr[$i]['row'];
                        $result["error_input_value"] = "empty";
                        array_push($stack, $result);
                    }
                    //Check Date Validation
                    if (isset($parameterArr[$i]['fieldValue'])) {
                        $return = check_decimal($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                            array_push($stack, $result);
                        }

                        $code_error = "F.2";
                        //Valida contra Mongo
                        
                        
                    }
                }
            } // END FOR LOOP->
        }
        exit();
        $this->data = $stack;
    }

}
