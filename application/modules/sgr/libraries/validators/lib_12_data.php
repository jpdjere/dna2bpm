<?php

class Lib_12_data extends MX_Controller {
    /* VALIDADOR ANEXO 12 */

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
                 * Debe verificar que para cada CUIT informado se cuente con información de Facturación y Cantidad de Empleados informados mediante ANEXOS 6 o 6.2 correspondiente al año anterior al período que se está informando. Ej. Si se están informando las garantías otorgadas en Enero de 2013, deben haber informado previamente la información de Facturación y Cantidad de Empleados del año 2012. Debe validar sólo el año, ya que ambos datos se piden al cierre de cada ejercicio, y los cierres de ejercicios pueden realizarse en cualquier mes del año.                  
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
                    //cuit checker
                    if ($parameterArr[$i]['fieldValue'] != "") {
                        $return = cuit_checker($parameterArr[$i]['fieldValue']);
                        if (!$return) {
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                            array_push($stack, $result);
                        }
                    }

                    $code_error = "B.2";
                    //Valida contra Mongo
                }

                /* ORIGEN
                 * Nro C.1
                 * Detail:
                 * Debe contener cinco dígitos numéricos. La fecha debe estar dentro del período informado.
                 */
                if ($parameterArr[$i]['col'] == 3) {

                    $code_error = "C.1";
                    //empty field Validation
                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result["error_code"] = $code_error;
                        $result["error_row"] = $parameterArr[$i]['row'];
                        $result["error_input_value"] = "empty";
                        array_push($stack, $result);
                    }

                    if ($parameterArr[$i]['fieldValue'] != "") {
                        //Check Date Validation
                        $return = check_date_format($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                            array_push($stack, $result);
                        }
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

                /* TIPO
                 * Nro D.1
                 * Detail:
                 * Debe contener uno de los parámetros establecidos en el Anexo adjunto donde se lista el Tipo de Garantías. Debe validar que el Tipo de Garantía informado se corresponda con el menú habilitado en la fecha en que se está informando
                 */
                if ($parameterArr[$i]['col'] == 4) {

                    $this->load->model('app');
                    $warranty_type = $this->app->get_ops(525);



                    $D1_field_value = $parameterArr[$i]['fieldValue'];

                    $code_error = "D.1";

                    //empty field Validation
                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result["error_code"] = $code_error;
                        $result["error_row"] = $parameterArr[$i]['row'];
                        $result["error_input_value"] = "empty";
                        array_push($stack, $result);
                    }


                    if ($parameterArr[$i]['fieldValue'] != "") {
                        $return = $this->sgr_model->get_warranty_type($D1_field_value);
                        if (!$return) {
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                            array_push($stack, $result);


//                            //PENDIENTE
//                            if ($parameterArr[$i]['fieldValue'] == "GFCPD") {
//
//                                $ctyDays = 0;
//                                $yearCtyDays = (Bisiesto(2012)) ? 366 : 365;
//
//                                /* Sumo el plazo + la gracia */
//                                $ctyDays = $insertarr[5224] + $insertarr[5225];
//                                if ($ctyDays > $yearCtyDays) {
//                                    $proc = false;
//                                    $showError[] = '<li>El Tipo de Garant&iacute;a ( ' . $insertarr[5216] . ' ) de la linea (' . $i . ') no puede superar los 365/366 d&iacute;as.</li>';
//                                }
//                            }
                        }
                    }
                }
                /* IMPORTE
                 * Nro E.1
                 * Detail:
                 * Debe ser formato numérico y aceptar hasta dos decimales.
                 */
                if ($parameterArr[$i]['col'] == 5) {
                    //empty field Validation
                    $code_error = "E.1";

                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result["error_code"] = $code_error;
                        $result["error_row"] = $parameterArr[$i]['row'];
                        $result["error_input_value"] = "empty";
                        array_push($stack, $result);
                    }

                    if ($parameterArr[$i]['fieldValue'] != "") {

                        $return = check_decimal($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                            array_push($stack, $result);
                        }
                    }
                }


                /* MONEDA
                 * Nro F.1
                 * Detail:
                 * Numero entero mayor a cero.
                 */
                if ($parameterArr[$i]['col'] == 6) {

                    $code_error = "F.1";

                    //empty field Validation
                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result["error_code"] = $code_error;
                        $result["error_row"] = $parameterArr[$i]['row'];
                        $result["error_input_value"] = "empty";
                        array_push($stack, $result);
                    }

                    if ($parameterArr[$i]['fieldValue'] != "") {
                        $allow_words = array("PESOS ARGENTINOS", "DOLARES AMERICANOS");
                        $return = check_word($parameterArr[$i]['fieldValue'], $allow_words);
                        if ($return) {
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                            array_push($stack, $result);
                        }
                    }
                }


                /* LIBRADOR_NOMBRE
                 * Nro G.1
                 * Detail:
                 * Sólo deberá estar completo en caso de que en la Columna D – Tipo de Garantía Otorgada, se haya completado alguna de las siguientes opciones:
                  GFFF1
                  GFFF2
                  GFFF3
                  CFCPD
                 */
                if ($parameterArr[$i]['col'] == 7) {
                    $codes_arr = array("GFFF1", "GFFF2", "GFFF3", "CFCPD");
                    $code_error = "G.1";

                    if (in_array($D1_field_value, $codes_arr)) {
                        $return = check_empty($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = "empty";
                            array_push($stack, $result);
                        }
                    }
                }

                /* LIBRADOR_CUIT
                 * Nro H.1
                 * Detail:
                 * Sólo deberá estar completo en caso de que en la Columna D – Tipo de Garantía Otorgada, se haya completado alguna de las siguientes opciones:
                  GFFF1
                  GFFF2
                  GFFF3
                  CFCPD
                 */
                if ($parameterArr[$i]['col'] == 8) {
                    $codes_arr = array("GFFF1", "GFFF2", "GFFF3", "CFCPD");

                    if (in_array($D1_field_value, $codes_arr)) {
                        $return = check_empty($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $code_error = "H.1";
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = "empty";
                            array_push($stack, $result);
                        }
                        //cuit checker
                        $return = cuit_checker($parameterArr[$i]['fieldValue']);
                        $code_error = "H.2";
                        if (!$return) {
                            var_dump($parameterArr[$i]['fieldValue'], $return);
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                            array_push($stack, $result);
                        }
                    }
                }

                /* NRO_OPERACION_BOLSA
                 * Nro I.1
                 * Detail:
                 * Sólo deberá estar completo en caso de que en la Columna D – Tipo de Garantía Otorgada, se haya completado alguna de las siguientes opciones:
                  GFCPD
                  GFON1
                  GFON2
                  GFON3
                 * Nro I.2
                 * Detail:
                 * Si la Columna D se completó con la opción CFCPD, deberá tener el siguiente formato: 4 LETRAS Y 9 NÚMEROS. Ej. CUAV250200005 Las cuatro letras deben coincidir con el Código asignado a cada SGR por la CNV. Se adjunta Anexo con Códigos.
                 * Nro I.3
                 * Detail: Si la Columna D se completó con alguna de las siguientes Opciones:
                  GFON1
                  GFON2
                  GFON3
                  deberá tener el siguiente formato: 3 Letras, un Numero, una letra.
                  Eje. OAH1P
                 */
                if ($parameterArr[$i]['col'] == 9) {

                    var_dump($D1_field_value);
                    $codes_arr = array("GFCPD", "GFON1", "GFON2", "GFON3");
                    if (in_array($D1_field_value, $codes_arr)) {
                        $code_error = "I.1";
                        $return = check_empty($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = "empty";
                            array_push($stack, $result);
                        }


                        if ($D1_field_value == "GFCPD") {
                            $check_cnv_syntax = check_cnv_syntax($parameterArr[$i]['fieldValue']);
                            if (!$check_cnv_syntax) {
                                $code_error = "I.2";
                                $result["error_code"] = $code_error;
                                $result["error_row"] = $parameterArr[$i]['row'];
                                $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                                array_push($stack, $result);
                            } else {
                                $return = $this->sgr_model->get_cnv_code($check_cnv_syntax);
                                if (!$return) {
                                    $result["error_code"] = $code_error;
                                    $result["error_row"] = $parameterArr[$i]['row'];
                                    $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                                    array_push($stack, $result);
                                }
                            }
                        } else {
                            $check_cnv_syntax_alt = check_cnv_syntax_alt($parameterArr[$i]['fieldValue']);
                            if (!$check_cnv_syntax_alt) {
                                $code_error = "I.3";
                                $result["error_code"] = $code_error;
                                $result["error_row"] = $parameterArr[$i]['row'];
                                $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                                array_push($stack, $result);
                            }
                        }
                    }
                }


                /* CUIT_ACREEDOR
                 * Nro K.1
                 * Detail:
                 * Debe tener 11 caracteres sin guiones.
                 * Nro K.2
                 * Detail:
                 * Si el Tipo de Garantía informado en la Columna D es alguno de los siguientes:
                  GFCPD, GFFF1, GFFF2, GFFF3, GFON1, GFON2, GFON3, GFMFO
                  debe validar que hayan informado alguno de los CUIT detallados en el Anexo adjunto, donde se listan los Mercados de Valores donde se realizan las operaciones.
                 * Nro K.3
                 * Detail:
                 * Si el Tipo de Garantía informado en la Columna D es alguno de los siguientes:
                  GFEF1, GFEF2, GFEF3
                  Debe validar que hayan informado alguno de los CUIT detallados en el Anexo adjunto, donde se listan los BANCOS COMERCIALES que son los únicos pueden aceptar dichos tipos de garantías.
                 */
                if ($parameterArr[$i]['col'] == 11) {
                    $code_error = "K.1";
                    //empty field Validation
                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result["error_code"] = $code_error;
                        $result["error_row"] = $parameterArr[$i]['row'];
                        $result["error_input_value"] = "empty";
                        array_push($stack, $result);
                    }
                    //cuit checker
                    if ($parameterArr[$i]['fieldValue'] != "") {
                        $return = cuit_checker($parameterArr[$i]['fieldValue']);
                        if (!$return) {
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                            array_push($stack, $result);
                        }
                    }
                }

                /* IMPORTE_CRED_GARANT
                 * Nro L.1
                 * Detail: 
                 * Aceptar hasta dos decimales.
                 */

                if ($parameterArr[$i]['col'] == 12) {
                    $code_error = "L.1";

                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result["error_code"] = $code_error;
                        $result["error_row"] = $parameterArr[$i]['row'];
                        $result["error_input_value"] = "empty";
                        array_push($stack, $result);
                    }

                    if ($parameterArr[$i]['fieldValue'] != "") {

                        $return = check_decimal($parameterArr[$i]['fieldValue']);
                        if ($return) {
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                            array_push($stack, $result);
                        }
                    }
                }


                /* MONEDA_CRED_GARANT
                 * Nro M.1
                 * Detail: 
                 * Debe contener uno de los siguientes parámetros:
                  Pesos Argentinos
                  Dolares Americanos
                 */
                if ($parameterArr[$i]['col'] == 13) {
                    $code_error = "M.1";

                    //empty field Validation
                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result["error_code"] = $code_error;
                        $result["error_row"] = $parameterArr[$i]['row'];
                        $result["error_input_value"] = "empty";
                        array_push($stack, $result);
                    }

                    if ($parameterArr[$i]['fieldValue'] != "") {
                        $allow_words = array("PESOS ARGENTINOS", "DOLARES AMERICANOS");
                        $return = check_word($parameterArr[$i]['fieldValue'], $allow_words);
                        if ($return) {
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                            array_push($stack, $result);
                        }
                    }
                }
                /* TASA
                 * Nro N.1
                 * Detail: 
                 * Debe contener uno de los siguientes parámetros:
                  FIJA
                  LIBOR
                  BADLAR PU (Badlar Bancos Públicos)
                  BADLAR PR (Badlar Bancos Privados)
                  TEC
                  TEBP
                 */
                if ($parameterArr[$i]['col'] == 14) {
                    $code_error = "N.1";

                    //empty field Validation
                    $return = check_empty($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result["error_code"] = $code_error;
                        $result["error_row"] = $parameterArr[$i]['row'];
                        $result["error_input_value"] = "empty";
                        array_push($stack, $result);
                    }

                    if ($parameterArr[$i]['fieldValue'] != "") {
                        $allow_words = array("FIJA", "LIBOR", "BADLAR PU","BADLAR PR","TEC","TEBP");
                        $return = check_word($parameterArr[$i]['fieldValue'], $allow_words);
                        if ($return) {
                            $result["error_code"] = $code_error;
                            $result["error_row"] = $parameterArr[$i]['row'];
                            $result["error_input_value"] = $parameterArr[$i]['fieldValue'];
                            array_push($stack, $result);
                        }
                    }
                }


                //PUNTOS_ADIC_CRED_GARANT	PLAZO	GRACIA	PERIODICIDAD	SISTEMA	DESTINO_CREDITO
            } // END FOR LOOP->
        }
        $this->data = $stack;
    }

}
