<?php

class Lib_12_data extends MX_Controller {
    /* VALIDADOR ANEXO 061 */

    public function __construct($parameter) {
        parent::__construct();
        $this->load->library('session');

        $this->load->helper('sgr/tools');

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
             * @example NRO  CUIT_PARTICIPE	ORIGEN	TIPO	IMPORTE	MONEDA	LIBRADOR_NOMBRE	LIBRADOR_CUIT	NRO_OPERACION_BOLSA	ACREEDOR	CUIT_ACREEDOR	IMPORTE_CRED_GARANT	MONEDA_CRED_GARANT	TASA	PUNTOS_ADIC_CRED_GARANT	PLAZO	GRACIA	PERIODICIDAD	SISTEMA	DESTINO_CREDITO
             * */
            for ($i = 0; $i <= count($parameterArr); $i++) {

                /* NRO
                 * Nro A.1
                 * Detail:
                 * El Número no puede estar cargado previamente en el Sistema en la misma SGR, así como tampoco puede estar repetido en el archivo que se está importando.               
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
                    //Valida contra Mongo

                }
                
                /* CUIT_PARTICIPE
                 * Nro B.1
                 * Detail:
                 * Debe tener 11 caracteres numéricos sin guiones.
                   Debe verificarse que el CUIT esté registrado en el sistema como Socio Partícipe (Clase A) y que tengas saldo positivo de tenencia accionaria.
                 * Nro B.2
                 * Detail:
                 * Si en la Columna B se completó la opción “SI”, el campo no puede estar vacío y  debe tener 11 caracteres sin guiones. El CUIT debe cumplir el “ALGORITMO VERIFICADOR”.
                 */
                if ($parameterArr[$i]['col'] == 3) {

                    $code_error = "C.1";

                    //empty field Validation
                    if ($B1_field_value == "SI") {
                        $return = check_empty($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = "empty";
                            array_push($stack, $result);
                        }
                        
                        //Valida contra Mongo
                    }
                }

                /* TIENE_VINCULACION
                 * Nro B.1
                 * Detail:
                 * El campo no puede estar vacío y debe contener uno de los siguientes parámetros:
                  SI
                  NO
                 * Nro B.2
                 * Detail:
                 * Si el CUIT informado en la Columna A comienza con 30 o 33 (Correspondiente a Personas Jurídicas) la opción debe ser “SI”. 
                 * Nro B.3
                 * Detail:
                 * Si se indica la opción “NO” el CUIT no puede estar más de una vez en la Columna A de este Anexo,  y las Columnas C, D, E, y F deben estar vacías.
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
                    }
                    //Value Validation
                    if ($parameterArr[$i]['fieldValue'] != "") {
                        $B1_field_value = "";
                        $allow_words = array("SI", "NO");
                        $return = check_word($parameterArr[$i]['fieldValue'], $allow_words);
                        if ($return) {
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                            array_push($stack, $result);
                        } else {
                            $B1_field_value = $parameterArr[$i]['fieldValue'];
                        }
                    }

                    $code_error = "B.2";
                }

                /* CUIT_VINCULADO
                 * Nro C.1
                 * Detail:
                 * Si en la Columna B se completó la opción “SI”, el campo no puede estar vacío y  debe tener 11 caracteres sin guiones. El CUIT debe cumplir el “ALGORITMO VERIFICADOR”.
                 */
                if ($parameterArr[$i]['col'] == 3) {

                    $code_error = "C.1";

                    //empty field Validation
                    if ($B1_field_value == "SI") {
                        $return = check_empty($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = "empty";
                            array_push($stack, $result);
                        }
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
                    if ($B1_field_value == "SI") {
                        $return = check_empty($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = "empty";
                            array_push($stack, $result);
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
                    if ($B1_field_value == "SI") {
                        $code_error = "E.1";

                        //empty field Validation
                        $return = check_empty($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = "empty";
                            array_push($stack, $result);
                        }
                        //Value Validation
                        if ($parameterArr[$i]['fieldValue'] != "") {
                            $B1_field_value = "";
                            $allow_words = array("ASCENDENTE", "DESCENDENTE");
                            $return = check_word($parameterArr[$i]['fieldValue'], $allow_words);
                            if ($return) {
                                $result["error_code"] = $code_error;
                                $result["error_row"] = $parameterArr[$i]['row'];
                                $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
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
                        $check_cuit = substr($A1_field_value, 0, 2);
                        $opt_arr = array('20', '23', '27');
                        $pos = strpos($check_cuit, $findme);

                        if (in_array($check_cuit, $opt_arr)) {
                            if ($parameterArr[$i]['fieldValue'] != "DESCENDENTE") {
                                $result["error_code"] = $code_error;
                                $result["error_row"] = $parameterArr[$i]['row'];
                                $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                                array_push($stack, $result);
                            }
                        }
                    }
                }

                /* TIPO_RELACION_VINCULACION
                 * Nro F.1
                 * Detail:
                 * Si en la Columna B se completó la opción “SI”, el campo no puede estar vacío.
                 */
                if ($parameterArr[$i]['col'] == 6) {
                    $code_error = "F.1";

                    if ($B1_field_value == "SI") {
                        //empty field Validation
                        $return = check_empty($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = "empty";
                            array_push($stack, $result);
                        }
                    }
                    $code_error = "F.2";
                    $return = check_is_numeric($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result["error_code"] = $code_error;
                        $result["error_row"] = $parameterArr[$i]['row'];
                        $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                        array_push($stack, $result);
                    }
                }
            }
        }
        $this->data = $stack;
    }

}
