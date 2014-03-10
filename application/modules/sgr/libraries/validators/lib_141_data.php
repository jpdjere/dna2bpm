<?php

class Lib_141_data extends MX_Controller {
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
        $this->$model_anexo->clear_tmp($insert_tmp);
        $A_cell_array = array();

        $order_num = array();

        for ($i = 1; $i <= $parameterArr[0]['count']; $i++) {
            /**
             * BASIC VALIDATION
             * 
             * @param 
             * @type PHP
             * @name ...
             * @author Diego             
             * @example 
             * CUIT_PARTICIPE	CANT_GTIAS_VIGENTES	HIPOTECARIAS	PRENDARIAS	FIANZA	OTRAS	REAFIANZA	MORA_EN_DIAS	CLASIFICACION_DEUDOR
             * */
            for ($i = 0; $i <= count($parameterArr); $i++) {

                /* CUIT_PARTICIPE
                 * Nro A.1
                 * Detail:
                 * Debe tener 11 caracteres sin guiones.
                 * Nro A.2
                 * Detail:
                 * Debe figura en el Sistema con Garantías Otorgadas (Anexo 12)
                 */

                if ($parameterArr[$i]['col'] == 1) {
                    $A_cell_value = "";

                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $code_error = "A.1";
                        $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                        array_push($stack, $result);
                    } else {
                        $A_cell_array = $parameterArr[$i]['fieldValue'];

                        $return = cuit_checker($parameterArr[$i]['fieldValue']);
                        if (!$return) {
                            $code_error = "A.1";
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }

                        $B_warranty_info = $this->$model_12->get_warranty_partner_left($parameterArr[$i]['fieldValue']);
                        if (!$B_warranty_info) {
                            $code_error = "A.2";
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }


                        /* A.3 */
                        //valida mongo
                    }
                }

                /* CANT_GTIAS_VIGENTES
                 * Nro B.1
                 * Detail:
                 * OPCIONAL. Si se detecta que el CUIT está informando en el ANEXO 12.5, debe tener formato número y aceptar números enteros mayores a Cero. De lo contrario, debe estar vacío.                
                 */

                if ($parameterArr[$i]['col'] == 2) {
                    
                }

                /* HIPOTECARIAS
                 * Nro C.1
                 * Detail:
                 * OPCIONAL. De completarse, debe tomar valor numérico mayor a cero y aceptar hasta dos decimales.
                 */
                if ($parameterArr[$i]['col'] == 3) {

                    $code_error = "C.1";
                    if ($parameterArr[$i]['fieldValue'] != "") {
                        $return = check_decimal($parameterArr[$i]['fieldValue'], 2, true);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }
                    }
                }

                /* PRENDARIAS
                 * Nro D.1
                 * Detail:OPCIONAL. De completarse, debe tomar valor numérico mayor a cero y aceptar hasta dos decimales.                
                 */
                if ($parameterArr[$i]['col'] == 4) {

                    $code_error = "D.1";
                    if ($parameterArr[$i]['fieldValue'] != "") {
                        $return = check_decimal($parameterArr[$i]['fieldValue'], 2, true);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }
                    }
                }

                /* FIANZA
                 * Nro E.1
                 * Detail:
                 * OPCIONAL. De completarse, debe tomar valor numérico mayor a cero y aceptar hasta dos decimales.
                 */
                if ($parameterArr[$i]['col'] == 5) {

                    $code_error = "E.1";
                    if ($parameterArr[$i]['fieldValue'] != "") {
                        $return = check_decimal($parameterArr[$i]['fieldValue'], 2, true);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }
                    }
                }

                /* OTRAS
                 * Nro F.1
                 * Detail:
                 * OPCIONAL. De completarse, debe tomar valor numérico mayor a cero y aceptar hasta dos decimales.
                 */
                if ($parameterArr[$i]['col'] == 6) {
                    $code_error = "F.1";
                    if ($parameterArr[$i]['fieldValue'] != "") {
                        $return = check_decimal($parameterArr[$i]['fieldValue'], 2, true);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }
                    }
                }

                /* REAFIANZA
                 * G.1
                 * Detail: 
                 * Debe completarse sólo en caso de que el CUIT informado en la Columna A del importador se encuentre previamente informado en el Sistema mediante ANEXO 12.4. 
                 * De lo contrario, debe estar vacío.De completarse, debe tomar valor numérico mayor a cero y aceptar hasta dos decimales.
                 */
                if ($parameterArr[$i]['col'] == 7) {
                    $code_error = "G.1";

                    /* CHECK ANEXO 12/4 */

                    if ($parameterArr[$i]['fieldValue'] != "") {
                        $return = check_decimal($parameterArr[$i]['fieldValue'], 2, true);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }
                    }
                }

                /* MORA_EN_DIAS 
                 * H.1
                 * Detail: 
                 * OPCIONAL. De estar completo debe tener formato numérico, positivo y entero, sin decimales. Debe validar que si en el proceso de importación detecta que el CUIT informado en la Columna A tiene saldos de deuda positivos (Saldo Calculado por el Sistema sobre la información histórica de los movimientos del FDR Contingente informados mediante ANEXO 14), esta columna deberá estar completa. Esto está relacionado con el proceso indicado en las Validaciones de Impresión de las PARTICULARIDADES DE IMPRESIÓN de este Anexo.
                 */
                if ($parameterArr[$i]['col'] == 8) {
                    $code_error = "H.1";
                    if ($parameterArr[$i]['fieldValue'] != "") {
                        $return = check_is_numeric_no_decimal($parameterArr[$i]['fieldValue'], true);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }
                    }
                }

                /* MORA_EN_DIAS 
                 * I.1
                 * Detail: 
                 * OPCIONAL. Debe validar que si en el proceso de importación detecta que el CUIT informado en la Columna A tiene saldos de deuda positivos (Saldo Calculado por el Sistema sobre la información histórica de los movimientos del FDR Contingente informados mediante ANEXO 14), esta columna deberá estar completa.
                 * I.2
                 * Detail:
                 * De estar completo, debe tomar alguno de los siguientes parámetros:1,2,3,4
                 */
                if ($parameterArr[$i]['col'] == 8) {
                    $code_error = "H.1";
                    if ($parameterArr[$i]['fieldValue'] != "") {
                        
                        $nums = array("1", "2", "3", "4");
                        if (!in_array($parameterArr[$i]['fieldValue'], $nums)) {
                             $code_error = "H.2";
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }
                    }
                }
            } // END FOR LOOP->
        }



        /* EXTRA VALIDATION A.4 */
        foreach (repeatedElements($A_cell_array) as $arr) {
            $code_error = "A.4";
            $result = return_error_array($code_error, $parameterArr[$i]['row'], "CUIT Repetido " . $arr['value']);
            array_push($stack, $result);
        }





//        var_dump($stack);
//        exit();
        $this->data = $stack;
    }

}
