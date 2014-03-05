<?php

class Lib_14_data extends MX_Controller {
    /* VALIDADOR ANEXO 14 */

    public function __construct($parameter) {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('sgr/tools');
        $this->load->model('sgr/sgr_model');

        $model_anexo = "model_14";
        $this->load->Model($model_anexo);

        $model_12 = 'model_12';
        $this->load->Model($model_12);


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

        $input_array = array();
        $b2_array = array();
        $b3_array = array();
        $b4_array = array();
        $b5_array = array();
        $b6_array = array();


        $spending_recovery = array();
        //$recovered_uncollectible = array();
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
                 */

                if ($parameterArr[$i]['col'] == 2) {
                    $B_cell_value = $parameterArr[$i]['fieldValue'];

                    $B_warranty_info = $this->$model_12->get_order_number_others($parameterArr[$i]['fieldValue']);


                    $get_movement_data = $this->$model_anexo->get_movement_data($B_cell_value);
                    //var_dump($B_cell_value,$get_movement_data['CAIDA']);

                    $CAIDAS = array($get_movement_data['CAIDA']);
                    $c3_values_array = array($get_movement_data['RECUPERO']);
                    $c3_values = $get_movement_data['RECUPERO'];
                    $b3_values_array = array($get_movement_data['INCOBRABLES_PERIODO']);
                    $b4_values_array = array($get_movement_data['GASTOS_EFECTUADOS_PERIODO']);
                    $b5_values_array = array($get_movement_data['RECUPERO_GASTOS_PERIODO']);
                    $b6_values_array = array($get_movement_data['GASTOS_INCOBRABLES_PERIODO']);
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

                        /* NRO_GARANTIA TO COMPARE */
                        $input_array[] = $B_cell_value;
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

                        if (!$get_movement_data['CAIDA']) {
                            $b2_array[] = $B_cell_value;
                        }

                        /* Nro D.3
                         * Detail:
                         * Debe validar que la suma de todos los RECUPEROS e INCOBRABLES registrados en el Sistema (incluidos los informados  en el archivo que se está importando) para una misma garantía no supere la suma de todas las caídas de esa misma garantía registradas en el Sistema (incluidos los informados  en el archivo que se está importando).
                         */
                        //$recovered_uncollectible[] = $parameterArr[$i]['fieldValue'];

                        array_push($c3_values_array, $parameterArr[$i]['fieldValue']);
                        $c3_values += $parameterArr[$i]['fieldValue'];
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
                        if (!$get_movement_data['CAIDA']) {
                            $b3_array[] = $B_cell_value;
                        }

                        /* Nro E.3
                         * Detail:
                         * Debe validar que la suma de todos los RECUPEROS e INCOBRABLES registrados en el Sistema (incluidos los informados  en el archivo que se está importando) para una misma garantía no supere la suma de todas las caídas de esa misma garantía registradas en el Sistema (incluidos los informados  en el archivo que se está importando).
                         */
                        //$recovered_uncollectible[] = $parameterArr[$i]['fieldValue'];
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
                        if (!$get_movement_data['CAIDA']) {
                            $b4_array[] = $B_cell_value;
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
                        if (!$get_movement_data['RECUPERO_GASTOS_PERIODO']) {
                            $b5_array[] = $B_cell_value;
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
                        if (!$get_movement_data['GASTOS_INCOBRABLES_PERIODO']) {
                            $b6_array[] = $B_cell_value;
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

        /*
         * $spending_recovery = array();
         * $recovered_uncollectible = array();
         * $spending_management = array();
         */

//        var_dump("--->1", $spending_recovery);
//        var_dump("--->2", $recovered_uncollectible);
//        var_dump("--->3", $spending_management);
//


        /* Nro B.2
         * Detail:
         * Si se está informando un RECUPERO (Columna D del importador), debe validar que el número de garantía registre 
         * previamente en el sistema (o en el mismo archivo que se está importando) una caída. 
         */
        foreach ($b2_array as $b2) {
            if (!in_array($b2, $input_array)) {
                $code_error = "B.2";
                $result = return_error_array($code_error, $parameterArr[$i]['row'], $b2);
                array_push($stack, $result);
            }
        }

        /* Nro B.3
         * Detail:
         * Si se está informando un INCOBRABLE (Columna E del importador), debe validar que el número de garantía registre 
         * previamente en el sistema (o en el mismo archivo que se está importando) una caída. 
         */
        foreach ($b3_array as $b3) {
            if (!in_array($b3, $input_array)) {
                $code_error = "B.3";
                $result = return_error_array($code_error, $parameterArr[$i]['row'], $b3);
                array_push($stack, $result);
            }
        }

        /* Nro B.4
         * Detail: 
         * Si se está informando un GASTOS POR GESTIÓN DE RECUPERO (Columna F), 
         * debe validar que el número de garantía registre previamente en el sistema (o en el mismo archivo que se está importando) una caída.
         */
        foreach ($b4_array as $b4) {
            if (!in_array($b4, $input_array)) {
                $code_error = "B.4";
                $result = return_error_array($code_error, $parameterArr[$i]['row'], $b4);
                array_push($stack, $result);
            }
        }

        /* Nro B.5
         * Detail: 
         * Si se está informando un RECUPERO DE GASTOS POR GESTIÓN DE RECUPERO (Columna G), 
         * debe validar que el número de garantía registre previamente en el sistema (o en el mismo archivo que se está importando) un 
         * GASTO POR GESTIÓN DE RECUPERO. 
         */
        foreach ($b5_array as $b5) {
            if (!in_array($b5, $input_array)) {
                $code_error = "B.5";
                $result = return_error_array($code_error, $parameterArr[$i]['row'], $b5);
                array_push($stack, $result);
            }
        }

        /* Nro B.6
         * Detail: 
         * Si se está informando un INCOBRABLE DE GASTOS POR GESTIÓN DE RECUPERO (Columna G), 
         * debe validar que el número de garantía registre previamente en el sistema (o en el mismo archivo que se está importando) un 
         * GASTO POR GESTIÓN DE RECUPERO. 
         */
        foreach ($b6_array as $b6) {
            if (!in_array($b6, $input_array)) {
                $code_error = "B.6";
                $result = return_error_array($code_error, $parameterArr[$i]['row'], $b6);
                array_push($stack, $result);
            }
        }
      exit();
        $this->data = $stack;
    }

}
