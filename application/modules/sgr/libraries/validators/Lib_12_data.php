<?php

class Lib_12_data extends MX_Controller {
    /* VALIDADOR ANEXO 12 */

    public function __construct($parameter) {
        parent::__construct();


        $this->load->library('session');
        $this->load->helper('sgr/tools');
        $this->load->model('sgr/sgr_model');
        $this->period = $this->session->userdata['period'];


        $this->load->Model('model_06');
        $this->load->Model('model_12');
        $this->load->Model('model_062');


        /* Vars 
         * 
         * $parameters =  
         * $parameterArr[0]['fieldValue'] 
         * $parameterArr[0]['row'] 
         * $parameterArr[0]['col']
         * $parameterArr[0]['count']
         * 
         */


        /* ARRAYS */
        $result = array();
        $col_A_arr = array();
        $parameterArr = (array) $parameter;



        /**
         * BASIC VALIDATION
         * 
         * @param 
         * @type PHP
         * @name ...
         * @author Diego
         *
         * @example 
         * */
        for ($i = 0; $i <= count($parameterArr); $i++) {
            $param_col = (isset($parameterArr[$i]['col'])) ? $parameterArr[$i]['col'] : 0;


            /* NRO
             * Nro A.1
             * Detail:
             * El Número no puede estar cargado previamente en el Sistema en la misma SGR, así como tampoco puede estar repetido en el archivo que se está importando.               
             */

            if ($param_col == 1) {

                $col_A_arr[] = $parameterArr[$i]['fieldValue'];

                $code_error = "A.1";

                if (empty($parameterArr[$i]['fieldValue'])) {
                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                } else {
                    $is_order_num = $this->model_12->get_order_number($parameterArr[$i]['fieldValue']);
                    if ($is_order_num) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "El nro de Orden " . $parameterArr[$i]['fieldValue'] . " ya está en el sistema.");
                    }


                    /* A.2 
                     * El campo sólo puede contener números y un guión (-), ningún otro caracter.
                     */

                    $code_error = "A.2";
                    $return = warranty_number_checker($parameterArr[$i]['fieldValue']);
                    if ($return)
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                }
            }


            /* CUIT_PARTICIPE
             * Nro B.1
             * Detail:
             * Debe tener 11 caracteres numéricos sin guiones.
              Debe verificarse que el CUIT esté registrado en el sistema como Socio Partícipe (Clase A) y que tengas saldo positivo de tenencia accionaria.

             */

            if ($param_col == 2) {

                $code_error = "B.1";

                if (isset($parameterArr[$i]['fieldValue'])) {
                    $return = cuit_checker($parameterArr[$i]['fieldValue']);
                    if (!$return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                } else {

                    /* FRE CONTROL */
                    if (isset($this->session->userdata['fre_session']))
                        $partner_data = $this->model_06->get_partner_left_fre($parameterArr[$i]['fieldValue']);
                    else
                        $partner_data = $this->model_06->get_partner_left($parameterArr[$i]['fieldValue']);

                    if (empty($parameterArr[$i]['fieldValue'])) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                    } else {

                        if (empty($partner_data))
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }


                    /* CUIT Partícipe
                     * Nro B.2
                     * Detail:
                     * Si en la Columna C la fecha del período es del año 2014 en adelante, debe verificar que para cada CUIT informado se cuente con información de Facturación informada mediante ANEXOS 6  o 6.2 correspondiente al año anterior al período que se está informando. Ej. Si se están informando las garantías otorgadas en Enero de 2013, deben haber informado previamente la información de Facturación del año 2012. Debe validar sólo el año, ya que ambos datos se piden al cierre de cada ejercicio, y los cierres de ejercicios pueden realizarse en cualquier mes del año. 
                     * Nro B.2
                     * Detail:
                     * Si en la Columna C la fecha del período es del año 2014 en adelante, debe verificar que para cada CUIT informado se cuente con información de Cantidad de Empleados informados mediante ANEXOS 6 o 6.2  correspondiente al año anterior al período que se está informando. Ej. Si se están informando las garantías otorgadas en Enero de 2013, deben haber informado previamente la información de  Cantidad de Empleados del año 2012. Debe validar sólo el año, ya que ambos datos se piden al cierre de cada ejercicio, y los cierres de ejercicios pueden realizarse en cualquier mes del año. 
                     */
                    $amount_employees = 0;
                    $transaction_date = null;


                    /* FRE CONTROL */
                    if (isset($this->session->userdata['fre_session']))
                        $partner_data = $this->model_06->get_partner_left_fre($parameterArr[$i]['fieldValue']);
                    else
                        $partner_data = $this->model_06->get_partner_left($parameterArr[$i]['fieldValue']);

                    foreach ($partner_data as $partner) {
                        $amount_employees += (int) $partner['CANTIDAD_DE_EMPLEADOS'];
                        $transaction_date = $partner['FECHA_DE_TRANSACCION'];
                    }
                    $amount_employees2 = 0;


                    /* FRE CONTROL */
                    if (isset($this->session->userdata['fre_session']))
                        $amount_employees2 = $this->model_062->get_count_partner_left_fre($parameterArr[$i]['fieldValue']);
                    else
                        $amount_employees2 = $this->model_062->get_count_partner_left($parameterArr[$i]['fieldValue']);




                    $sum_amount_employees = array_sum(array($amount_employees, $amount_employees2));



                    if ($sum_amount_employees == 0) {
                        $code_error = "B.2.A";
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue'] . "(Empleados 0)");
                    } else {
                        list($month_period, $year_period) = explode("-", $this->session->userdata['period']);
                        $transaction_year = explode("/", $partner['25']);
                        $result_dates = (int) $year_period - (int) $transaction_year[0];
                        if ($result_dates < 0) {
                            $code_error = "B.2";
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                        }
                    }

                    /* B.3 */
                    $subscribed = $this->model_06->shares_active_left($parameterArr[$i]['fieldValue'], "A");
                    $integrated = $this->model_06->shares_active_left($parameterArr[$i]['fieldValue'], "A", 5598);

                    if ($integrated != $subscribed) {
                        $code_error = "B.3";
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue'] . " - Saldo Integrado: " . $integrated . " - Saldo Suscripto: " . $subscribed);
                    }
                }
            }

            /* ORIGEN
             * Nro C.1
             * Detail:
             * Debe contener cinco dígitos numéricos. La fecha debe estar dentro del período informado.
             */
            if ($param_col == 3) {

                $code_error = "C.1";
                if (empty($parameterArr[$i]['fieldValue'])) {
                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                }

                if (isset($parameterArr[$i]['fieldValue'])) {
                    //Check Date Validation
                    $return = check_date_format($parameterArr[$i]['fieldValue']);
                    if ($return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                    /* PERIOD */
                    $return = check_period($parameterArr[$i]['fieldValue'], $this->session->userdata['period']);
                    if ($return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                }
            }

            /* TIPO
             * Nro D.1
             * Detail:
             * Debe contener uno de los parámetros establecidos en el Anexo adjunto donde se lista el Tipo de Garantías. Debe validar que el Tipo de Garantía informado se corresponda con el menú habilitado en la fecha en que se está informando
             */
            if ($param_col == 4) {

                $this->load->model('app');
                $warranty_type = $this->app->get_ops(525);

                $D_cell_value = strtoupper($parameterArr[$i]['fieldValue']);

                $code_error = "D.1";

                if (empty($parameterArr[$i]['fieldValue'])) {
                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                }


                if (isset($parameterArr[$i]['fieldValue'])) {
                    $return = $this->sgr_model->get_warranty_type($D_cell_value, $this->period);
                    if (!$return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                }
            }
            /* IMPORTE
             * Nro E.1
             * Detail:
             * El monto ingresado debe contener números enteros o hasta dos decimales. Solo puede tomar valores positivos (mayores a cero).
             */
            if ($param_col == 5) {

                $code_error = "E.1";
                if (empty($parameterArr[$i]['fieldValue'])) {
                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                } else {
                    #$return = validate_two_decimals($parameterArr[$i]['fieldValue']);
                    $return = check_decimal($parameterArr[$i]['fieldValue'], 2, true);
                    if ($return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                }
            }


            /* MONEDA
             * Nro F.1
             * Detail:
             * Debe contener uno de los siguientes parámetros:
              Pesos Argentinos
              Dolares Americanos
              Si la Columna D se completó con la opción GFCPD, la moneda de origen sólo podrá ser PESOS ARGENTINOS
             */
            if ($param_col == 6) {
                $code_error = "F.1";

                if (empty($parameterArr[$i]['fieldValue'])) {
                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                } else {

                    if ($D_cell_value == "GFCPD") {
                        $code_error = "F.2";
                        $allow_words = array("PESOS ARGENTINOS");
                    } else {
                        $allow_words = array("PESOS ARGENTINOS", "DOLARES AMERICANOS");
                    }

                    $return = check_word($parameterArr[$i]['fieldValue'], $allow_words);
                    if ($return)
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                }
            }


            /* LIBRADOR_NOMBRE
             * Nro G.1
             * Detail:
             * Sólo deberá estar completo en caso de que en la Columna D – Tipo de Garantía Otorgada, se haya completado alguna de las siguientes opciones:
              GFFF1
              GFFF2
              GFFF3
             */
            if ($param_col == 7) {
                $codes_arr = array("GFFF0", "GFFF1", "GFFF2", "GFFF3", "GFCPD", "GFPB0", "GFPB1", "GFPB2");
                $code_error = "G.1";
                if (in_array($D_cell_value, $codes_arr)) {
                    if (empty($parameterArr[$i]['fieldValue'])) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                    }
                } else {
                    if (!empty($parameterArr[$i]['fieldValue'])) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
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
              GFCPD
             */
            if ($param_col == 8) {
                $codes_arr = array("GFFF0", "GFFF1", "GFFF2", "GFFF3", "GFCPD", "GFPB0", "GFPB1", "GFPB2");

                if (in_array($D_cell_value, $codes_arr)) {
                    if (empty($parameterArr[$i]['fieldValue'])) {
                        $code_error = "H.1";
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                    }
                    //cuit checker
                    $return = cuit_checker($parameterArr[$i]['fieldValue']);
                    $code_error = "H.2";
                    if (!$return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                } else {
                    $code_error = "H.1";
                    if (!empty($parameterArr[$i]['fieldValue'])) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                }
            }

            /* NRO_OPERACION_BOLSA
             * Nro I.1
             * Detail:
             * Sólo deberá estar completo en caso de que en la Columna D – Tipo de Garantía Otorgada, se haya completado alguna de las siguientes opciones:
              GFCPD
              GFON0
              GFON1
              GFON2
              GFON3
              GFVCP
              GFPB
             * Nro I.2
             * Detail:
             * Si la Columna D se completó con la opción GFCPD, deberá tener el siguiente formato: 4 LETRAS Y 9 NÚMEROS. Ej. CUAV250200005 Las cuatro letras deben coincidir con el Código asignado a cada SGR por la CNV. Se adjunta Anexo con Códigos.
             * Nro I.3
             * Detail: Si la Columna D se completó con alguna de las siguientes Opciones:
              GFON1
              GFON2
              GFON3
              GFVCP
              deberá tener el siguiente formato: 3 Letras, un Numero, una letra.
              Eje. OAH1P
             */
            if ($param_col == 9) {
                $codes_arr = array("GFCPD", "GFON0", "GFON1", "GFON2", "GFON3", "GFPB0", "GFPB1", "GFPB2", "GFVCP");
                $code_error = "I.1";
                if (in_array($D_cell_value, $codes_arr)) {
                    if (empty($parameterArr[$i]['fieldValue'])) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                    }

                    // I.2
                    $I2_validate_arr = array("GFCPD");
                    $code_error = "I.2";
                    if (in_array($D_cell_value, $I2_validate_arr)) {
                        $check_cnv_syntax = check_cnv_syntax($parameterArr[$i]['fieldValue']);
                        if (!$check_cnv_syntax) {
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                        } else {
                            $return = $this->sgr_model->get_cnv_code($check_cnv_syntax);
                            if (!$return) {
                                $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                            }
                        }
                    }
                    // I.3
                    $I3_validate_arr = array("GFON0", "GFON1", "GFON2", "GFON3", "GFVCP");
                    $code_error = "I.3";
                    if (in_array($D_cell_value, $I3_validate_arr)) {
                        $check_cnv_syntax_alt = check_cnv_syntax_alt($parameterArr[$i]['fieldValue']);
                        if (!$check_cnv_syntax_alt) {
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                        }
                    }
                    // I.4
                    $I4_validate_arr = array("GFPB0", "GFPB1", "GFPB2");
                    $code_error = "I.4";
                    if (in_array($D_cell_value, $I4_validate_arr)) {
                        $check_cnv_syntax_i4 = check_cnv_syntax_i4($parameterArr[$i]['fieldValue']);
                        $check_cnv_code = (!empty($check_cnv_syntax_i4)) ? ($this->sgr_model->get_cnv_code('$' . $check_cnv_syntax_i4)) : (null);

                        if (!$check_cnv_syntax_i4 || is_null($check_cnv_code)) {
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                        }
                    }
                } else {
                    if (!empty($parameterArr[$i]['fieldValue'])) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
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
              GFCPD, GFVCP, GFPB, GFFF1, GFFF2, GFFF3, GFON1, GFON2, GFON3, GFMFO
              debe validar que hayan informado alguno de los CUIT detallados en el Anexo adjunto, donde se listan los Mercados de Valores donde se realizan las operaciones.
             * Nro K.3
             * Detail:
             * Si el Tipo de Garantía informado en la Columna D es alguno de los siguientes:
              GFEF0, GFEF1, GFEF2, GFEF3
              Debe validar que hayan informado alguno de los CUIT detallados en el Anexo adjunto, donde se listan los BANCOS COMERCIALES que son los únicos pueden aceptar dichos tipos de garantías.
             * Nro K.4
             * Detail : Si el Tipo de Garantía informado en la Columna D es GFL0, GFL1, GFL2, GFL3, NO debe aceptar las CUIT del listado Mercado de Valores.
             * Nro K.5
             * Detail: Si el Tipo de Garantía informado en la Columna D es diferente a los indicados en los validadores K.2, K.3 y K.4, no se deben admitir las CUIT detalladas en los anexos allí indicados (No debe aceptar los CUIT de Bancos Comerciales y de Mercados de Valores).
             */
            if ($param_col == 11) {


                $code_error = "K.1";

                if (empty($parameterArr[$i]['fieldValue'])) {
                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                } else {
                    $k_cell_value = $parameterArr[$i]['fieldValue'];
                    //cuit checker
                    $return = cuit_checker($parameterArr[$i]['fieldValue']);
                    if (!$return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }


                    $cuit_type = $this->model_12->get_mv_and_comercial_cuits($parameterArr[$i]['fieldValue']);


                    $code_error = "K.2";
                    $k2_check_arr = array("GFCPD", "GFVCP", "GFPB0", "GFPB1", "GFPB2", "GFFF0", "GFFF1", "GFFF2", "GFFF3", "GFON1", "GFON2", "GFON3", "GFMFO");
                    if (in_array($D_cell_value, $k2_check_arr)) {
                        if ($cuit_type != "MV") {
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                        }
                    }

                    $code_error = "K.3";
                    $k3_check_arr = array("GFEF0", "GFEF1", "GFEF2", "GFEF3");
                    if (in_array($D_cell_value, $k3_check_arr)) {
                        if ($cuit_type != "COMERCIAL") {
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                        }
                    }

                    $code_error = "K.4";
                    $k4_check_arr = array("GFL0", "GFL1", "GFL2", "GFL3");
                    if (in_array($D_cell_value, $k4_check_arr)) {
                        if ($cuit_type == "MV") {
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue'] . "(" . $D_cell_value . ")");
                        }
                    }


                    $code_error = "K.5";

                   
                    
                    $k5_check_arr = array_merge($k2_check_arr, $k3_check_arr, $k4_check_arr);
                    if (!in_array($D_cell_value, $k5_check_arr)) {
                        if ($cuit_type == "MV" || $cuit_type == "COMERCIAL") {
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                        }
                    }
                }
            }

            /* IMPORTE_CRED_GARANT
             * Nro L.1
             * Detail: 
             * Debe tener formato numérico. El monto ingresado debe contener números enteros o hasta dos decimales. Solo puede tomar valores positivos (mayores a cero).
             */

            if ($param_col == 12) {
                $code_error = "L.1";

                if (empty($parameterArr[$i]['fieldValue'])) {
                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                } else {
                    $return = check_decimal($parameterArr[$i]['fieldValue'], 2, true);
                    if ($return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
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
            if ($param_col == 13) {
                $code_error = "M.1";

                if (empty($parameterArr[$i]['fieldValue'])) {
                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                } else {
                    $code_error = "M.2";



                    if ($D_cell_value == "GFCPD") {
                        $code_error = "M.2";
                        $allow_words = array("PESOS ARGENTINOS");
                    } else {
                        $allow_words = array("PESOS ARGENTINOS", "DOLARES AMERICANOS");
                    }

                    $return = check_word($parameterArr[$i]['fieldValue'], $allow_words);

                    if ($return)
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                }
            }

            /* TASA
             * Nro N.1
             * Detail: 
             * Debe contener uno de los siguientes parámetros:
              FIJA
              LIBOR
              BADLARPU (Badlar Bancos Públicos)
              BADLARPR (Badlar Bancos Privados)
              TEC
              TEBP
             */
            if ($param_col == 14) {
                $N_cell_value = null;
                $code_error = "N.1";

                if (empty($parameterArr[$i]['fieldValue'])) {
                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                } else {
                    $N_cell_value = strtoupper($parameterArr[$i]['fieldValue']);
                    $allow_words = array("FIJA", "LIBOR", "BADLARPU", "BADLARPR", "TEC", "TEBP", "50%BNA", "BNA");
                    $return = check_word($parameterArr[$i]['fieldValue'], $allow_words);
                    if ($return) {


                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                }
            }

            /* PUNTOS_ADIC_CRED_GARANT
             * Nro 0.1
             * Detail:
             * Debe tomar valores entre -50 y -1 o entre 0 y 50 (es decir, no puede tomar valores entre -1 y 0).
             * Nro 0.2
             * Detail:
             * Si en la Columna N se indicó que la tasa es “FIJA”, debe tomar un valor mayor a 0 (cero), con hasta dos decimales.
             */
            if ($param_col == 15) {
                $in_value = (int) $parameterArr[$i]['fieldValue'];
                $range1 = range(-50, -1);
                $range2 = range(0, 50);
                $range3 = range(-25, 50);

                if ($N_cell_value == "FIJA") {
                    $return = check_decimal($parameterArr[$i]['fieldValue'], 2, true);
                    if ($return) {
                        $code_error = "O.2";
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                } else {

                    /* Support #12131: Anexo 12 - validador O.1 
                     * Modificamos los valores topes de este validador que dice:Debe tomar valores 
                     * entre -20 y -1 o entre 1 y 20 (es decir, no puede tomar valores entre -1 y 1). 
                     * Hay q pasarlo de -20 a -50 y 20 a 50. */
                    if (!in_array($in_value, $range1) && !in_array($in_value, $range2)) {
                        $code_error = "O.1";
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                }
            }

            /* PLAZO
             * Nro P.1
             * Detail:
             * Debe ser un campo numérico, sin decimales, y mayor a cero.
             * Nro P.2
             * Detail:
             * Si en la Columna “D” el Tipo de Garantía seleccionado fue GFCPD, el plazo debe ser mayor a cero y meno a 365 (366 si implica un año bisiesto).
             * Nro P.3
             * Detail:
             * Si en la Columna “D” el Tipo de Garantía seleccionado fue GFVCP, el plazo debe ser mayor a cero y meno a 730 (731 si implica un año bisiesto).
             * Nro P.4
             * Detail:
             * Para los demás tipos de garantías el plazo informado debe encontrarse dentro de los límites.
             * Nro P.5
             * Si en la Columna “J” el nombre del Acreedor es FONAPYME, y en la columna “K” el CUIT ingresado es 30708258691, el plazo, en ningún caso, puede ser mayor a 2557 días)
             */
            if ($param_col == 16) {
                $P_cell_value = (int) $parameterArr[$i]['fieldValue'];
                $code_error = "P.1";

                if (empty($parameterArr[$i]['fieldValue'])) {
                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                } else {
                    $return = check_is_numeric_no_decimal($parameterArr[$i]['fieldValue'], true);
                    if (!$return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }

                    if ($k_cell_value == '30708258691' && $P_cell_value > 2557) {
                        $code_error = "P.5";
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                }
            }

            /* GRACIA
             * Nro Q.1
             * Detail:
             * Debe ser un campo numérico, sin decimales, y mayor a cero.
             * Nro Q.2
             * Detail:
             * Si en la Columna R se indicó PAGO ÚNICO, el valor aquí indicado debe ser igual al valor indicado en la Columna P.
             * Nro Q.3
             * Detail:
             *  Si en la columna R NO se indicó PAGO ÚNICO, entonces el valor indicado aquí debe ser menor al indicado en la columna P (Plazo).
             */
            if ($param_col == 17) {
                $Q_cell_value = (int) $parameterArr[$i]['fieldValue'];
                $code_error = "Q.1";

                if (empty($parameterArr[$i]['fieldValue'])) {
                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                } else {
                    //Check Numeric Validation
                    $return = check_is_numeric_no_decimal($parameterArr[$i]['fieldValue'], true);
                    if (!$return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                }


                /* PLAZO + GRACIA */

                if ($D_cell_value == "GFCPD") {
                    $code_error = "P.2";
                    $ctyDays = 0;
                    $yearCtyDays = 366;

                    if ($P_cell_value >= $yearCtyDays) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                }


                if ($D_cell_value == "GFVCP") {
                    $code_error = "P.3";
                    $ctyDays = 0;
                    $yearCtyDays = 366;
                    if ($P_cell_value >= $yearCtyDays) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                }


                $return = $this->sgr_model->get_warranty_type($D_cell_value, $this->period);

                $yearCtyDays = 365;

                $ctyMayor = $return->mayor * $yearCtyDays;
                $ctyMinor = $return->minor * $yearCtyDays;
                $ctyDays = $P_cell_value;

                $range = range($ctyMinor, $ctyMayor);

                if (!in_array($ctyDays, $range)) {

                    //  var_dump($ctyDays, $range);

                    $code_error = "P.4";
                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $P_cell_value);
                }
            }

            /* PERIODICIDAD
             * Nro R.1
             * Detail:
             * Debe contener uno de los siguientes parámetros:
              PAGO UNICO
              MENSUAL
              BIMESTRAL
              TRIMESTRAL
              CUATRIMESTRAL
              SEMESTRAL
              ANUAL
              OTRO
             * Nro R.2
             * Detail:
             * Si en la Columna “D” el Tipo de Garantía seleccionado fue GFCPD o GFVCP, este campo sólo puede indicar PAGO UNICO.
             */
            if ($param_col == 18) {

                $R_cell_value = strtoupper($parameterArr[$i]['fieldValue']);

                $code_error = "R.1";

                if (empty($parameterArr[$i]['fieldValue'])) {
                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                } else {
                    $allow_words = array("PAGO UNICO", "MENSUAL", "BIMESTRAL", "TRIMESTRAL", "CUATRIMESTRAL", "SEMESTRAL", "ANUAL", "OTRO");
                    $return = check_word($parameterArr[$i]['fieldValue'], $allow_words);
                    if ($return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }


                    $code_error = "R.2";
                    $types_arr = array("GFCPD", "GFVCP");
                    if (in_array($D_cell_value, $types_arr)) {
                        if (strtoupper($parameterArr[$i]['fieldValue']) != "PAGO UNICO") {
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                        }
                    }

                    $code_error = "Q.2";
                    if (strtoupper($parameterArr[$i]['fieldValue']) == "PAGO UNICO") {
                        if ($P_cell_value != $Q_cell_value) {
                            $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                        }
                    }


                    /* Q.3 */
                    $show_Q3 = null;

                    if ($R_cell_value != "PAGO UNICO") {
                        if ($Q_cell_value > $P_cell_value)
                            $show_Q3 = true;
                    }

                    if (isset($show_Q3)) {
                        $code_error = "Q.3";
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $Q_cell_value . " es mayor a " . $P_cell_value);
                    }
                }
            }

            /* SISTEMA
             * Nro S.1
             * Detail:
             * Debe contener uno de los siguientes parámetros:
              PAGO UNICO
              FRANCES
              ALEMAN
              AMERICANO
              OTRO
             * Nro S.2
             * Detail:
             * Si en la Columna “D” el Tipo de Garantía seleccionado fue GFCPD o GFVCP, este campo sólo puede indicar PAGO UNICO.
             * Nro S.3
             * Detail:
             * Si en la Columna “T” se indicó que la Periodicidad de los pagos es PAGO UNICO, este campo sólo puede indicar PAGO UNICO.
             */
            if ($param_col == 19) {
                $code_error = "S.1";

                if (empty($parameterArr[$i]['fieldValue'])) {
                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                } else {
                    $allow_words = array("PAGO UNICO", "FRANCES", "ALEMAN", "AMERICANO", "OTRO");
                    $return = check_word($parameterArr[$i]['fieldValue'], $allow_words);
                    if ($return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                }

                $code_error = "S.2";
                $types_arr = array("GFCPD", "GFVCP");
                if (in_array($D_cell_value, $types_arr)) {
                    if (strtoupper($parameterArr[$i]['fieldValue']) != "PAGO UNICO") {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                }

                $code_error = "S.3";
                if ($R_cell_value == "PAGO UNICO") {
                    $code_error = "S.3";
                    if (strtoupper($parameterArr[$i]['fieldValue']) != "PAGO UNICO") {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    } else {
                        $code_error = "S.4";
                        if (strtoupper($parameterArr[$i]['fieldValue']) == "PAGO UNICO") {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                }
            }
            /* DESTINO_CREDITO
             * Nro T.1
             * Detail:
             * Debe contener uno de los siguientes parámetros:
              OBRA CIVIL
              BIENES DE CAPITAL
              INMUEBLES
              CAPITAL DE TRABAJO
              PROYECTO DE INVERSION
             */
            if ($param_col == 20) {

                $pyear = get_reference_year($this->session->userdata['period']);

                if (isset($pyear)) {
                    $code_error = "T.2";

                    if (!empty($parameterArr[$i]['fieldValue']))
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                }



                $code_error = "T.1";
                if (empty($parameterArr[$i]['fieldValue'])) {
                    $result[] = return_error_array($code_error, $parameterArr[$i]['row'], "empty");
                } else {
                    $allow_words = array("OBRA CIVIL", "BIENES DE CAPITAL", "INMUEBLES", "CAPITAL DE TRABAJO", "PROYECTO DE INVERSION");
                    $return = check_word($parameterArr[$i]['fieldValue'], $allow_words);
                    if ($return) {
                        $result[] = return_error_array($code_error, $parameterArr[$i]['row'], $parameterArr[$i]['fieldValue']);
                    }
                }
            }
        } // END FOR LOOP->



        /* NROS DE ORDEN REPETIDOS */
        if (count(array_unique($col_A_arr)) < count($col_A_arr)) {
            $code_error = "A.1";
            $result[] = return_error_array($code_error, "Todas", "Nros. de Orden repetidos dentro del mismo Anexo");
        }


        /* VALIDATION pre 2014 */
        $redo_result = exclude_validation_12($result, $this->session->userdata['period']);

        /* RETURN ARRAY WITH ERRORS */
        /* var_dump($redo_result);
          exit; */
        $this->data = $redo_result;
    }

}
