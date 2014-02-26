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
        $fall_array = array();
        $spending_recovery = array();
        $recovered_uncollectible = array();
        $spending_management = array();

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
                    $A_cell_value = "";
                    $code_error = "A.1";

                    //empty field Validation
                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {

                        $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                        array_push($stack, $result);
                    } else {
                        $A_cell_value = $parameterArr[$i]['fieldValue'];
                        $return = check_date_format($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }


                        /* PERIOD */
                        $return = check_period($parameterArr[$i]['fieldValue'], $this->session->userdata['period']);
                        if ($return) {
                            $code_error = "A.2";
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }
                    }
                }

                /* NRO_GARANTIA
                 * Nro B.1
                 * Detail:
                 * Si se está informando la CAÍDA de una Garantía (Columna C del importador), 
                 * debe validar que el número de garantía se encuentre registrado en el Sistema como que fue otorgada (Anexo 12).
                 * Nro B.2
                 * Detail:
                 * Si se está informando un RECUPERO (Columna D del importador), debe validar que el número de garantía registre 
                 * previamente en el sistema (o en el mismo archivo que se está importando) una caída.
                 * Nro B.3
                 * Detail:
                 * Si se está informando un INCOBRABLE (Columna E del importador), debe validar que el número de garantía registre 
                 * previamente en el sistema (o en el mismo archivo que se está importando) una caída.
                 * Nro B.4
                 * Detail: 
                 * Si se está informando un GASTOS POR GESTIÓN DE RECUPERO (Columna F), 
                 * debe validar que el número de garantía registre previamente en el sistema (o en el mismo archivo que se está importando) una caída.
                 * Nro B.5
                 * Detail: 
                 * Si se está informando un RECUPERO DE GASTOS POR GESTIÓN DE RECUPERO (Columna G), 
                 * debe validar que el número de garantía registre previamente en el sistema (o en el mismo archivo que se está importando) un 
                 * GASTO POR GESTIÓN DE RECUPERO.
                 * Nro B.6
                 * Detail: 
                 * Si se está informando un INCOBRABLE DE GASTOS POR GESTIÓN DE RECUPERO (Columna G), 
                 * debe validar que el número de garantía registre previamente en el sistema (o en el mismo archivo que se está importando) un 
                 * GASTO POR GESTIÓN DE RECUPERO.
                 */

                if ($parameterArr[$i]['col'] == 2) {
                    $B_cell_value = $parameterArr[$i]['fieldValue'];
                    $B_warranty_info = $this->sgr_model->get_warranty_data($parameterArr[$i]['fieldValue']);
                }

                /* CAIDA
                 * Nro C.1
                 * Detail:
                 * Formato de número. Debe ser un valor numérico y aceptar hasta 2 decimales.
                 */
                if ($parameterArr[$i]['col'] == 3) {                    
                    $code_error = "C.1";
                    if ($parameterArr[$i]['fieldValue'] != "") {
                        $return = check_decimal($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }


                        /* Nro C.2
                         * Detail:
                         * En caso de que la garantía haya sido otorgada en PESOS, debe validar que el importe sea menor o igual al 
                         * Monto de la Garantía Otorgada informada mediante Anexo 12 registrado en el Sistema. 
                         */

                        /* MONEDA 5219 | IMPORTE 5218 */
                        $warranty_info = $this->sgr_model->get_warranty_data($B_cell_value);
                        if ($warranty_info['5219'][0] == 1) {
                            if ($parameterArr[$i]['fieldValue'] > $warranty_info[5218]) {
                                $code_error = "C.2";
                                $result = return_error_array($code_error, $parameterArr[$i]['row'], '($' . $parameterArr[$i]['fieldValue'] . '). Monto disponible para el Nro. Orden ' . $B_cell_value . ' = $' . $warranty_info[5218]);
                                array_push($stack, $result);
                            }
                        }


                        /* Nro C.3
                         * Detail:
                         * En  caso de que la garantía haya sido otorgada en DÓLARES debe validar que el importe aquí informado sea menor o igual 
                         * al Monto de la Garantía Otorgada informado mediante Anexo 12 registrado en el Sistema, dividido por el 
                         * TIPO DE CAMBIO DEL día anterior al que fue otorgada la garantía y multiplicado por el TIPO DE CAMBIO del día 
                         * anterior al que se está informando que se cayó la garantía. 
                         */
                        if ($warranty_info['5219'][0] == 2) {
                            $code_error = "C.3";
                            //get_dollar_quotation
                            $dollar_quotation = $this->sgr_model->get_dollar_quotation($A_cell_value);
                            $dollar_value = $parameterArr[$i]['fieldValue'] / $dollar_quotation;
                            if ($dollar_value > $warranty_info[5218]) {
                                $code_error = "C.3";
                                $result = return_error_array($code_error, $parameterArr[$i]['row'], '(u$s' . $dollar_value . ') a la fecha ' . $dollar_quotation . '. Monto disponible para el Nro. Orden ' . $B_cell_value . ' = $' . $warranty_info[5218]);
                                array_push($stack, $result);
                            }
                        }


                        /* Nro B.1
                         * Detail:
                         * Si se está informando la CAÍDA de una Garantía (Columna C del importador), 
                         * debe validar que el número de garantía se encuentre registrado en el Sistema como que fue otorgada (Anexo 12). 
                         */
                        if (!$B_warranty_info) {
                            $code_error = "B.1";
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $B_cell_value);
                            array_push($stack, $result);
                        }
                    }
                }

                /* RECUPERO
                 * Nro D.1
                 * Detail:
                 * Formato de número. Debe ser un valor numérico y aceptar hasta 2 decimales.
                 * Nro D.2
                 * Detail:
                 * Debe validar que el número de garantía registre previamente en el sistema (o en el mismo archivo que se está importando) una caída.                 
                 */
                if ($parameterArr[$i]['col'] == 4) {

                    $code_error = "D.1";
                    if ($parameterArr[$i]['fieldValue'] != "") {
                        $return = check_decimal($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }

                        /* Nro D.2 = B.2
                         * Detail:
                         * Si se está informando un RECUPERO (Columna D del importador), debe validar que el número de garantía registre 
                         * previamente en el sistema (o en el mismo archivo que se está importando) una caída.
                         */
                        if (!$B_warranty_info) {
                            $fall_array[] = $B_warranty_info;
                        }

                        /* Nro D.3
                         * Detail:
                         * Debe validar que la suma de todos los RECUPEROS e INCOBRABLES registrados en el Sistema (incluidos los informados  en el archivo que se está importando) para una misma garantía no supere la suma de todas las caídas de esa misma garantía registradas en el Sistema (incluidos los informados  en el archivo que se está importando).
                         */
                        $recovered_uncollectible[] = $parameterArr[$i]['fieldValue'];
                    }
                }

                /* INCOBRABLES_PERIODO
                 * Nro E.1
                 * Detail:
                 * Formato de número. Debe ser un valor numérico y aceptar hasta 2 decimales.
                 * Nro E.2
                 * Detail:
                 * Debe validar que el número de garantía registre previamente en el sistema (o en el mismo archivo que se está importando) una caída.                
                 */
                if ($parameterArr[$i]['col'] == 5) {

                    $code_error = "E.1";
                    if ($parameterArr[$i]['fieldValue'] != "") {
                        $return = check_decimal($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }

                        /* Nro E.2 = B.3
                         * Detail:
                         * Si se está informando un INCOBRABLE (Columna E del importador), debe validar que el número de garantía registre 
                         * previamente en el sistema (o en el mismo archivo que se está importando) una caída. 
                         */
                        if (!$B_warranty_info) {
                            $fall_array[] = $B_warranty_info;
                        }

                        /* Nro E.3
                         * Detail:
                         * Debe validar que la suma de todos los RECUPEROS e INCOBRABLES registrados en el Sistema (incluidos los informados  en el archivo que se está importando) para una misma garantía no supere la suma de todas las caídas de esa misma garantía registradas en el Sistema (incluidos los informados  en el archivo que se está importando).
                         */
                        $recovered_uncollectible[] = $parameterArr[$i]['fieldValue'];
                    }
                }

                /* GASTOS_EFECTUADOS_PERIODO
                 * Nro F.1
                 * Detail:
                 * Formato de número. Debe ser un valor numérico y aceptar hasta 2 decimales.
                 * Nro F.2
                 * Detail:
                 * Debe validar que el número de garantía registre previamente en el sistema (o en el mismo archivo que se está importando) una caída.
                 */
                if ($parameterArr[$i]['col'] == 6) {                    
                    $code_error = "F.1";
                    if ($parameterArr[$i]['fieldValue'] != "") {
                        $return = check_decimal($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }

                        /* Nro F.2 = B.4
                         * Detail: 
                         * Si se está informando un GASTOS POR GESTIÓN DE RECUPERO (Columna F), 
                         * debe validar que el número de garantía registre previamente en el sistema (o en el mismo archivo que se está importando) una caída.
                         */
                        if (!$B_warranty_info) {
                            $fall_array[] = $B_warranty_info;
                        }
                    }
                }

                /* RECUPERO_GASTOS_PERIODO
                 * G.1
                  Debe ser un valor numérico y aceptar hasta 2 decimales.
                  G.2 = B.5
                  Debe validar que el número de garantía registre previamente en el sistema (o en el mismo archivo que se está importando) un GASTO POR GESTIÓN DE RECUPERO.
                  G.3
                  Debe validar que la suma de todos los RECUPEROS POR GASTOS DE GESTIÓN DE RECUPEROS e INCOBRABLES POR GASTOS DE GESTIÓN DE RECUPEROS registrados en el Sistema (incluidos los informados  en el archivo que se está importando) para una misma garantía no supere la suma de todos los GASTOS POR GESTIÓN DE RECUPEROS de esa misma garantía registrados en el Sistema (incluidos los informados  en el archivo que se está importando).
                 */
                if ($parameterArr[$i]['col'] == 7) {
                    $code_error = "G.1";
                    if ($parameterArr[$i]['fieldValue'] != "") {
                        $return = check_decimal($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }

                        /* Nro G.2 = B.5
                         * Detail: 
                         * Si se está informando un RECUPERO DE GASTOS POR GESTIÓN DE RECUPERO (Columna G), 
                         * debe validar que el número de garantía registre previamente en el sistema (o en el mismo archivo que se está importando) un 
                         * GASTO POR GESTIÓN DE RECUPERO. 
                         */
                        if (!$B_warranty_info) {
                            $spending_recovery[] = $B_warranty_info;
                        }

                        /* G.3 */
                        if (!$B_warranty_info) {
                            $spending_management[] = $B_warranty_info;
                        }
                    }
                }

                /* GASTOS_INCOBRABLES_PERIODO 
                  H.1
                  Debe ser un valor numérico y aceptar hasta 2 decimales.
                  H.2 = B.6
                  Debe validar que el número de garantía registre previamente en el sistema (o en el mismo archivo que se está importando) un GASTO POR GESTIÓN DE RECUPERO.
                  H.3 = G.3
                  Debe validar que la suma de todos los RECUPEROS POR GASTOS DE GESTIÓN DE RECUPEROS e INCOBRABLES POR GASTOS DE GESTIÓN DE RECUPEROS registrados en el Sistema (incluidos los informados  en el archivo que se está importando) para una misma garantía no supere la suma de todos los GASTOS POR GESTIÓN DE RECUPEROS de esa misma garantía registrados en el Sistema (incluidos los informados  en el archivo que se está importando).
                 */
                if ($parameterArr[$i]['col'] == 8) {
                    $code_error = "H.1";
                    if ($parameterArr[$i]['fieldValue'] != "") {
                        $return = check_decimal($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }

                        /* Nro B.6
                         * Detail: 
                         * Si se está informando un INCOBRABLE DE GASTOS POR GESTIÓN DE RECUPERO (Columna G), 
                         * debe validar que el número de garantía registre previamente en el sistema (o en el mismo archivo que se está importando) un 
                         * GASTO POR GESTIÓN DE RECUPERO. 
                         */
                        if (!$B_warranty_info) {
                            $spending_recovery[] = $B_warranty_info;
                        }

                        /* H.3 */
                        if (!$B_warranty_info) {
                            $spending_management[] = $B_warranty_info;
                        }
                    }
                }
            } // END FOR LOOP->
        }
        //var_dump($stack);
        //exit();
        $this->data = $stack;
    }

}
