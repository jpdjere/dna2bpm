<?php

class Lib_061_data extends MX_Controller {
    /* VALIDADOR ANEXO 061 */

    public function __construct($parameter) {
        parent::__construct();
        $this->load->library('session');

        $this->load->helper('sgr/tools');
        $model_anexo = "model_061";
        $this->load->Model($model_anexo);

        /* PARTNER INFO */
        $model_06 = 'model_06';
        $this->load->Model($model_06);

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
        $count_inc = array();
        $partner_shares_arr = array();
        $A_cell_array = array();
        $A_cell_array_no = array();

        for ($i = 1; $i <= $parameterArr[0]['count']; $i++) {
            /* Validacion Basica */
            for ($i = 0; $i <= count($parameterArr); $i++) {


                /* CUIT_SOCIO_INCORPORADO
                 * Nro A.1
                 * Detail:
                 * Si alguna de las columnas B a F está completa, este campo no puede estar vacío y  debe tener 11 caracteres sin guiones.
                 */

                if ($parameterArr[$i]['col'] == 1) {
                    if ($parameterArr[$i]['fieldValue'] != "") {
                        $A_cell_value = $parameterArr[$i]['fieldValue'];
                        $A_cell_array[] = $A_cell_value;
                    }
                    /*
                     * Nro A.2
                     * Detail:
                     * El CUIT debe estar en el ANEXO 6 – MOVIMIENTOS DE CAPITAL SOCIAL, informado en el período correspondiente como incorporado. 
                     */
//                    $code_error = "A.2";
//                    $partner_data = $this->$model_06->get_partner_period($parameterArr[$i]['fieldValue'], $this->session->userdata['period']);
//                    if ($partner_data[5779][0] != '1') {
//                        $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
//                        array_push($stack, $result);
//                    }
                }


                /* TIENE_VINCULACION
                 * Nro B.1
                 * Detail:
                 * El campo no puede estar vacío y debe contener uno de los siguientes parámetros:
                  SI
                  NO
                 */

                if ($parameterArr[$i]['col'] == 2) {

                    $code_error = "B.1";
                    //empty field Validation
                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                        array_push($stack, $result);
                    } else {
                        $B_cell_value = $parameterArr[$i]['fieldValue'];
                        $allow_words = array("SI", "NO");
                        $return = check_word($parameterArr[$i]['fieldValue'], $allow_words);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }


                        /*
                         * Nro B.2
                         * Detail:
                         * Si el CUIT informado en la Columna A comienza con 30 o 33 (Correspondiente a Personas Jurídicas) la opción debe ser “SI”. 
                         */
                        $b2_value = substr($A_cell_value, 0, 2);
                        $array = array("30", "33");
                        if (in_array($b2_value, $array) && $parameterArr[$i]['fieldValue'] == "NO") {
                            $code_error = "B.2";
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }

                        /*
                         * Nro B.3/2
                         * Detail:
                         * Si se indica la opción “NO” el CUIT no puede estar más de una vez en la Columna A de este Anexo.
                         */
                        $A_cell_array[] = $A_cell_value;
                        if ($parameterArr[$i]['fieldValue'] == "NO") {
                            $A_cell_array_no[] = $A_cell_value;
                        }
                    }
                }

                /* CUIT_VINCULADO
                 * Nro C.1
                 * Detail:
                 * Si en la Columna B se completó la opción “SI”, el campo no puede estar vacío y  debe tener 11 caracteres sin guiones. El CUIT debe cumplir el “ALGORITMO VERIFICADOR”.
                 */
                if ($parameterArr[$i]['col'] == 3) {

                    $code_error = "C.1";
                    /*CHECK EMPTY*/
                    if ($B_cell_value == "SI") {
                        $return = check_empty($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                            array_push($stack, $result);
                        }

                        /*CUIT CHECKER*/
                        if ($parameterArr[$i]['fieldValue'] != "") {
                            $return = cuit_checker($parameterArr[$i]['fieldValue']);
                            if (!$return) {
                                $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                                array_push($stack, $result);
                            }
                        }
                    } else {
                        /*CHECK FOR IS NOT EMPTY ????? */
//                        $return = check_for_empty($B_cell_value);
//                        if ($return) {
//                            $code_error = "B.3";
//                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $B_cell_value);
//                            array_push($stack, $result);
//                        }
                   }
                }

                /* RAZON_SOCIAL_VINCULADO
                 * Nro D.1
                 * Detail:
                 * Si en la Columna B se completó la opción “SI”, el campo no puede estar vacío. Si el CUIT se encuentra registrado en la Base de Datos del Sistema, debe tomar el nombre allí escrito, de lo contrario, debe tomar transitoriamente el nombre informado por la SGR.
                 */
                if ($parameterArr[$i]['col'] == 4) {
                    $code_error = "D.1";
                    //Check Empry
                    if ($B_cell_value == "SI") {
                        $return = check_empty($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                            array_push($stack, $result);
                        }
                    } else {
                        $return = check_for_empty($B_cell_value);
                        if ($return) {
                            $code_error = "B.3";
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $B_cell_value);
                            //array_push($stack, $result);
                        }
                    }
                }

                /* TIPO_RELACION_VINCULACION
                 * Nro E.1
                 * Detail:
                 * Si en la Columna B se completó la opción “SI”, el campo no puede estar vacío y debe contener uno de los siguientes parámetros:
                  ASCENDENTE
                  DESCENDENTE
                 */
                if ($parameterArr[$i]['col'] == 5) {

                    $code_error = "D.1";
                    //Check Empry
                    if ($B_cell_value == "SI") {
                        $code_error = "E.1";

                        $return = check_empty($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                            array_push($stack, $result);
                        }

                        if (isset($parameterArr[$i]['fieldValue'])) {
                            $B_cell_value = "";
                            $allow_words = array("ASCENDENTE", "DESCENDENTE");
                            $return = check_word($parameterArr[$i]['fieldValue'], $allow_words);
                            if ($return) {
                                $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                                array_push($stack, $result);
                            }
                        }


                        /* TIPO_RELACION_VINCULACION
                         * Nro E.2
                         * Detail:
                         * Si el número de CUIT informado en la Columna A empieza con 20, 23 o 27 
                         * (los tres correspondientes a personas físicas), y se indicó que el Socio SI tiene Relaciones de Vinculación (Columna B), 
                         * la opción elegida sólo puede ser DESCENDENTE.
                         */
                        $code_error = "E.2";
                        $E_cell_value = $parameterArr[$i]['fieldValue'];
                        $check_cuit = substr($A_cell_value, 0, 2);
                        $opt_arr = array('20', '23', '27');
                        $pos = strpos($check_cuit, $findme);

                        if (in_array($check_cuit, $opt_arr)) {
                            if ($parameterArr[$i]['fieldValue'] != "DESCENDENTE") {
                                $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                                array_push($stack, $result);
                            }
                        }
                    } else {
                        $return = check_for_empty($B_cell_value);
                        if ($return) {
                            $code_error = "B.3";
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $B_cell_value);
                            //array_push($stack, $result);
                        }
                    }
                }

                /* TIPO_RELACION_VINCULACION
                 * Nro F.1
                 * Detail:
                  Si en la Columna B se completó la opción “SI”, el campo no puede estar vacío.
                 * Nro F.2
                 * Detail:
                  De completarse, debe tener formato numérico y sólo debe tomar valores entre 0 y 1 y aceptar hasta 2 decimales.
                 * Nro F.3
                 * Detail:
                  Para un mismo CUIT informado en la Columna A, los campos que en la Columna E indiquen ASCENDENTE, deben sumar 1, de forma de cerciorarse que estén informando el total de los Accionistas de la empresa.
                 */
                if ($parameterArr[$i]['col'] == 6) {

                    if ($parameterArr[$i]['fieldValue'] != "" || $B_cell_value) {
                        /* A.1 */
                        if ($A_cell_value) {
                            $code_error = "A.1";
                            $count_inc[] = $A_cell_value;
                            $return = cuit_checker($A_cell_value);
                            if (!$return) {
                                $result = return_error_array($code_error, $parameterArr[$i]['row'], $A_cell_value);
                                array_push($stack, $result);
                            }
                        }
                    }


                    $code_error = "F.1";

                    if ($B_cell_value == "SI") {
                        //empty field Validation
                        $return = check_empty($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                            array_push($stack, $result);
                        }
                    } else {
                        $return = check_for_empty($B_cell_value);
                        if ($return) {
                            $code_error = "B.3";
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $B_cell_value);
                            // array_push($stack, $result);
                        }
                    }

                    /* Multiplico para usar INT */
                    $range = range(0, 100);
                    $float_var = (float) $parameterArr[$i]['fieldValue'];
                    $float_to_int =(int) $float_var * 100;
                    $code_error = "F.2";
                    if ($parameterArr[$i]['fieldValue'] != "") {
                        $return = check_decimal($parameterArr[$i]['fieldValue'],2,true);
                        if ($return) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        } else if (!in_array($float_to_int, $range)) {
                            $result = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            array_push($stack, $result);
                        }
                    }

                    /* F.3 */
                    $shares_result = array($E_cell_value . '.' . $A_cell_value . '.', $float_var);
                    if ($E_cell_value == "ASCENDENTE") {                        
                        array_push($partner_shares_arr, $shares_result);
                    }
                }
            }

            /* CUIT_SOCIO_INCORPORADO
             * Nro A.3
             * Detail:
             * Todos los Socios que fueron informados como Incorporados en el Anexo 6 – Movimientos de Capital Social, deben figurar en esta columna.
             */
            $partners_error_data = $this->$model_06->new_count_partners($count_inc, $this->session->userdata['period']);


            $register_on_06 = count($partners_error_data);
            $count_on_061 = count(array_unique($A_cell_array));

            if ($register_on_06 != $count_on_061) {
                $stack = array();
                $code_error = "A.3";
                $result["error_row"] = 1;
                $result = return_error_array($code_error, " - ", "No figuran todos los socios incorporados");
                array_push($stack, $result);
            }


            /* F.3 */
            var_dump($partner_shares_arr);
            $AF3_result = count_shares($partner_shares_arr);
            foreach ($AF3_result as $cell) {
                $count_shares = $cell['acumulados']['shares'];
                if ($count_shares != 1) {
                    $code_error = "F.3";
                    $result = return_error_array($code_error, $parameterArr[$i]['row'], $cell[0]["gridGroupName"] . " Total Acciones: " . $count_shares);
                    array_push($stack, $result);
                }
            }
        }

        /*
         * Nro B.3/1
         * Detail:
         * Si se indica la opción “NO” el CUIT no puede estar más de una vez en la Columna A de este Anexo,  y las Columnas C, D, E, y F deben estar vacías.
         */
        
        foreach ($A_cell_array_no as $cuit) {
            if (in_array($cuit, $A_cell_array)) {
                $search_cuit = (array_keys($A_cell_array, $cuit));
                $counter = count($search_cuit);
                if ($counter > 1) {
                    $code_error = "B.3";
                    $result = return_error_array($code_error, "-", $cuit . " Total de Veces.: " . $counter);
                    array_push($stack, $result);
                }
            }
        }


          var_dump($stack);  exit();
        $this->data = $stack;
    }

}
