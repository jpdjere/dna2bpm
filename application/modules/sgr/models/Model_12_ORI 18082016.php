<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_12 extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('sgr/tools');

        $this->anexo = '12';
        /* Additional SGR users */
        $this->load->model('sgr/sgr_model');
        $additional_users = $this->sgr_model->additional_users($this->session->userdata('iduser'));
        $this->idu = (isset($additional_users)) ? $additional_users['sgr_idu'] : $this->session->userdata('iduser');
        /* SWITCH TO SGR DB */
        $this->load->library('cimongo/Cimongo.php', '', 'sgr_db');
        $this->sgr_db->switch_db('sgr');



        /* LOAD MODELS */
        $this->load->model('padfyj_model');
        $this->load->model('model_06');
         $this->load->model('model_062');
        $this->load->model('sgr_model');
        $this->load->model('app');

        /* base variables */
        $this->base_url = base_url();
        $this->module_url_report = base_url() . 'sgr/reports';

        if (!$this->idu) {
            header("$this->module_url/user/logout");
        }

        /* DATOS SGR */
        $sgrArr = $this->sgr_model->get_sgr();
        foreach ($sgrArr as $sgr) {
            $this->sgr_id = $sgr['id'];
            $this->sgr_nombre = $sgr['1693'];
        }
    }

    function sanitize($parameter) {
        /* FIX INFORMATION */
        $parameter = (array) $parameter;
        $parameter = array_map('trim', $parameter);
        $parameter = array_map('addSlashes', $parameter);

        return $parameter;
    }

    function check($parameter) {
        /**
         *   Funcion ...
         * 
         * @param 
         * @type PHP
         * @name ...
         * @author Diego
         *
         * @example NRO	CUIT_PARTICIPE	ORIGEN	TIPO	IMPORTE	MONEDA	LIBRADOR_NOMBRE	LIBRADOR_CUIT	NRO_OPERACION_BOLSA	ACREEDOR	CUIT_ACREEDOR	IMPORTE_CRED_GARANT	MONEDA_CRED_GARANT	TASA	PUNTOS_ADIC_CRED_GARANT	PLAZO	GRACIA	PERIODICIDAD	SISTEMA	DESTINO_CREDITO
         * */
        $defdna = array(
            1 => 5214, //"Nro",
            2 => 5349, //"Cuit_participe",              
            3 => 5215, //"Origen",                      
            4 => 5216, //"Tipo",
            5 => 5218, //"Importe",
            6 => 5219, //"Moneda",
            7 => 5725, //"Librador_nombre",
            8 => 5726, //"Librador_cuit",
            9 => 5727, //"Nro_operacion_bolsa",
            10 => 5350, //"Acreedor",
            11 => 5351, //"Cuit_acreedor",            
            12 => 5221, //"Importe_Cred_Garant",
            13 => 5758, //"Moneda_Cred_Garant",
            14 => 5222, //"Tasa",
            15 => 5223, //"Puntos_adic_Cred_Garantizado",
            16 => 5224, //"Plazo",  
            17 => 5225, //"Gracia",
            18 => 5226, //"Period.",
            19 => 5227, //"Sistema",
            20 => 'DESTINO_CREDITO' //"Tipo_Contragarantia"
        );


        $insertarr = array();
        foreach ($defdna as $key => $value) {
            $insertarr[$value] = $parameter[$key];

            /* STRING */
            $insertarr[5214] = (string) $insertarr[5214]; //Nro orden


            $insertarr[5349] = (string) $insertarr[5349]; //Cuit_participe
            $insertarr[5726] = (string) $insertarr[5726]; //Librador_cuit           
            $insertarr[5351] = (string) $insertarr[5351]; //Acreedir

            $insertarr[5216] = (string) trim(strtoupper($insertarr[5216])); //Tipo           


            /* FLOAT */
            $insertarr[5218] = (float) $insertarr[5218];
            $insertarr[5221] = (float) $insertarr[5221];
            $insertarr[5223] = (float) $insertarr[5223];

            /* INTEGER */
            $insertarr[5224] = (int) $insertarr[5224];
            $insertarr[5225] = (int) $insertarr[5225];

            /* MONEDA */
            if (strtoupper(trim($insertarr[5219])) == "PESOS ARGENTINOS")
                $insertarr[5219] = "1";
            if (strtoupper(trim($insertarr[5219])) == "DOLARES AMERICANOS")
                $insertarr[5219] = "2";
            if (strtoupper(trim($insertarr[5219])) == "EUROS")
                $insertarr[5219] = "3";
            if (strtoupper(trim($insertarr[5219])) == "REALES")
                $insertarr[5219] = "4";

            if (strtoupper($insertarr[5758]) == "PESOS ARGENTINOS")
                $insertarr[5758] = "1";
            if (strtoupper($insertarr[5758]) == "DOLARES AMERICANOS")
                $insertarr[5758] = "2";
            if (strtoupper($insertarr[5758]) == "EUROS")
                $insertarr[5758] = "3";
            if (strtoupper($insertarr[5758]) == "REALES")
                $insertarr[5758] = "4";



            /* PERIODICIDAD */
            if (strtoupper(trim($insertarr[5226])) == "PAGO UNICO")
                $insertarr[5226] = "1";
            if (strtoupper(trim($insertarr[5226])) == "MENSUAL")
                $insertarr[5226] = "30";
            if (strtoupper(trim($insertarr[5226])) == "BIMESTRAL")
                $insertarr[5226] = "60";
            if (strtoupper(trim($insertarr[5226])) == "TRIMESTRAL")
                $insertarr[5226] = "90";
            if (strtoupper(trim($insertarr[5226])) == "CUATRIMESTRAL")
                $insertarr[5226] = "120";
            if (strtoupper(trim($insertarr[5226])) == "SEMESTRAL")
                $insertarr[5226] = "180";
            if (strtoupper(trim($insertarr[5226])) == "ANUAL")
                $insertarr[5226] = "360";
            if (strtoupper(trim($insertarr[5226])) == "OTRO")
                $insertarr[5226] = "04";

            /* SISTEMA */
            if (strtoupper(trim($insertarr[5227])) == "PAGO UNICO")
                $insertarr[5227] = "01";
            if (strtoupper(trim($insertarr[5227])) == "FRANCES")
                $insertarr[5227] = "02";
            if (strtoupper(trim($insertarr[5227])) == "ALEMAN")
                $insertarr[5227] = "03";
            if (strtoupper(trim($insertarr[5227])) == "OTRO")
                $insertarr[5227] = "04";



            /* TASA */
            if (strtoupper($insertarr[5222]) == "LIBOR")
                $insertarr[5222] = "01";
            if (strtoupper($insertarr[5222]) == "BADLARPU")
                $insertarr[5222] = "02";
            if (strtoupper($insertarr[5222]) == "BADLARPR")
                $insertarr[5222] = "03";
            if (strtoupper($insertarr[5222]) == "FIJA")
                $insertarr[5222] = "04";
            if (strtoupper($insertarr[5222]) == "TEBP")
                $insertarr[5222] = "05";
            if (strtoupper($insertarr[5222]) == "TEC")
                $insertarr[5222] = "06";
        }
        return $insertarr;
    }

    function save($parameter) {
        $period = $this->session->userdata['period'];
        $container = 'container.sgr_anexo_' . $this->anexo;

        /* FIX DATE */
        list($arr['Y'], $arr['m'], $arr['d']) = explode("-", strftime("%Y-%m-%d", mktime(0, 0, 0, 1, -1 + $parameter[5215], 1900)));
        $parameter[5215] = $arr;

        $parameter['period'] = $period;
        $parameter['origen'] = "2013";

        $id = $this->app->genid_sgr($container);

        $result = $this->app->put_array_sgr($id, $container, $parameter);

        if ($result) {
            $out = array('status' => 'ok');
        } else {
            $out = array('status' => 'error');
        }
        return $out;
    }

    function save_period($parameter) {
        /* ADD PERIOD */
        $container = 'container.sgr_periodos';
        $period = $this->session->userdata['period'];
        $id = $this->app->genid_sgr($container);
        $parameter['period'] = $period;
        $parameter['period_date'] = translate_period_date($period);
        $parameter['status'] = 'activo';
        $parameter['idu'] = (float) $this->idu;
        $parameter['origen'] = "2013";

        /*
         * VERIFICO PENDIENTE           
         */
        $get_period = $this->sgr_model->get_current_period_info($this->anexo, $period);
        /* UPDATE */
        if (isset($get_period['status']))
            $this->update_period($get_period['id'], $get_period['status']);

        $result = $this->app->put_array_sgr($id, $container, $parameter);
        if (isset($result)) {
            /* BORRO SESSION RECTIFY */
            $this->session->unset_userdata('rectify');
            $this->session->unset_userdata('others');
            $this->session->unset_userdata('period');

            $out = array('status' => 'ok');
        } else {
            $out = array('status' => 'error');
        }
        return $out;
    }

    function update_period($id, $status) {

        $options = array('upsert' => true, 'w' => 1);
        $container = 'container.sgr_periodos';
        $query = array('id' => (float) $id);
        $parameter = array(
            'status' => 'rectificado',
            'rectified_on' => date('Y-m-d h:i:s'),
            'others' => $this->session->userdata['others'],
            'reason' => $this->session->userdata['rectify']
        );



        $rs = $this->mongowrapper->sgr->$container->update($query, array('$set' => $parameter), $options);
        return $rs['err'];
    }

    function get_anexo_info($anexo, $parameter) {
        /* HEADER TEMPLATE */
        $header_data = array();

        $header = $this->parser->parse('prints/anexo_' . $anexo . '_header', TRUE);
        $tmpl = array('data' => $header);

        $data = array($tmpl);

        $anexoValues = $this->get_anexo_data($anexo, $parameter);
        foreach ($anexoValues as $values) {
            $data[] = array_values($values);
        }
        $this->load->library('table_custom');
        $newTable = $this->table_custom->generate($data);

        return $newTable;
    }

    function get_anexo_data_tmp($anexo, $parameter) {

        $rtn = array();
        $container = 'container.sgr_anexo_' . $anexo;
        $fields = array('5214',
            '5349',
            '5215',
            '5216',
            '5218',
            '5219',
            '5726',
            '5727',
            '5351',
            '5221',
            '5758',
            '5222',
            '5223',
            '5224',
            '5225',
            '5226',
            '5227', 'filename', 'period', 'sgr_id', 'origin');
        $query = array("filename" => $parameter);
        $result = $this->mongowrapper->sgr->$container->find($query, $fields);

        foreach ($result as $list) {
            $rtn[] = $list;
        }

        return $rtn;
    }

    function get_anexo_data($anexo, $parameter) {
        header('Content-type: text/html; charset=UTF-8');
        $rtn = array();
        $container = 'container.sgr_anexo_' . $anexo;
        $query = array("filename" => $parameter);
        $result = $this->mongowrapper->sgr->$container->find($query);



        foreach ($result as $list) {


            /* Vars */
            $new_list = array();

            $participate = $this->padfyj_model->search_name($list[5349]);
            $drawer = $this->padfyj_model->search_name((string) $list[5726]);



            /*  CREDITOR NAME */
            $creditor_mv = $this->get_mv_and_comercial_name($list[5351]);
            $creditor_padfyj = $this->padfyj_model->search_name($list[5351]);
            $creditor = ($creditor_mv) ? $creditor_mv : $creditor_padfyj;



            $warranty_type = $this->app->get_ops(525);
            $currency = $this->app->get_ops(549);
            $repayment_system = $this->app->get_ops(527);
            $rate = $this->app->get_ops(526);
            $periodicity = $this->app->get_ops(548);

            /* PONDERACION */
            $get_weighting = $this->sgr_model->get_warranty_type($list[5216][0], $list['period']);
            $warranty_type_value = ($warranty_type[$list[5216][0]]) ? $warranty_type[$list[5216][0]] : $list[5216][0];

            $new_list['NRO'] = $list[5214];
            $new_list['PARTICIPE'] = $participate;
            $new_list['CUIT_PARTICIPE'] = $list[5349];
            $new_list['ORIGEN'] = $list[5215];
            $new_list['TIPO'] = $warranty_type_value;
            $new_list['PONDERACION'] = $get_weighting['weighted'] * 100;
            $new_list['IMPORTE'] = money_format_custom($list[5218]);
            $new_list['MONEDA'] = $currency[$list[5219][0]];
            $new_list['LIBRADOR_NOMBRE'] = $drawer;
            $new_list['LIBRADOR_CUIT'] = $list[5726];
            $new_list['NRO_OPERACION_BOLSA'] = $list[5727];
            $new_list['ACREEDOR'] = $creditor;
            $new_list['CUIT_ACREEDOR'] = $list[5351];
            $new_list['IMPORTE_CRED_GARANT'] = $list[5221];
            $new_list['MONEDA_CRED_GARANT'] = $currency[$list[5758][0]];
            $new_list['TASA'] = $rate[$list[5222][0]];
            $new_list['PUNTOS_ADIC_CRED_GARANT'] = $list[5223];
            $new_list['PLAZO'] = $list[5224];
            $new_list['GRACIA'] = $list[5225];
            $new_list['PERIODICIDAD'] = $periodicity[$list[5226][0]];
            $new_list['SISTEMA'] = $repayment_system[$list[5227][0]];
            $new_list['DESTINO_CREDITO'] = $list['DESTINO_CREDITO'];
            $rtn[] = $new_list;
        }
        return $rtn;
    }

    //container.sgr_cuits_comerciales_y_mv
    function get_mv_and_comercial_name($cuit) {

        $container = 'container.sgr_cuits_comerciales_y_mv';
        $query = array("cuit" => $cuit);
        $result = $this->mongowrapper->sgr->$container->findOne($query);
        if ($result)
            return $result['name'];
    }

    function get_mv_and_comercial_cuits($cuit, $type) {

        $container = 'container.sgr_cuits_comerciales_y_mv';
        $query = array("cuit" => $cuit);
        $result = $this->mongowrapper->sgr->$container->findOne($query);

        return $result['type'];
    }

    /* GET DATA */

    function get_order_number($nro) {
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;
        $period_value = $this->session->userdata['period'];

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active($anexo, $period_value);

        $return_result = array();
        foreach ($result as $list) {
            $new_query = array(
                'filename' => $list['filename'],
                5214 => $nro
            );

            $new_result = $this->mongowrapper->sgr->$container->findOne($new_query);
            if ($new_result) {
                $return_result[] = $new_result;
            }
        }
        return $return_result;
    }

    function get_period_amount($period_value) {

        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_12';

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_period_data($anexo, $period_value);

        $return_result = array();
        foreach ($result as $list) {
            $new_query = array(
                'filename' => $list['filename']
            );
            $new_result = $this->mongowrapper->sgr->$container->find($new_query);
            foreach ($new_result as $each) {
                $return_result[] = $each[5218];
            }
        }
        $average = array_sum($return_result) / count($return_result);
        return $average;
    }

    /* GET DATA */

    function get_order_number_left($nro) {
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;
        $period_value = $this->session->userdata['period'];



        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active($anexo);

        $return_result = array();
        foreach ($result as $list) {
            $new_query = array(
                'filename' => $list['filename'],
                5214 => $nro
            );
            $new_result = $this->mongowrapper->sgr->$container->findOne($new_query);
            if ($new_result) {
                $return_result[] = $new_result;
            }
        }
        return $return_result;
    }

    function get_order_number_print($nro, $period_date) {
        $anexo = $this->anexo;

        $period = 'container.sgr_periodos';
        $container = 'container.sgr_anexo_' . $anexo;

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active_print($anexo, $period_date);

        /* FIND ANEXO */
        foreach ($result as $list) {
            $new_query = array(
                'filename' => $list['filename'],
                5214 => $nro
            );
            $new_result = $this->mongowrapper->sgr->$container->findOne($new_query);
            if ($new_result) {
                $return_result[] = $new_result;
            }
        }
        return $return_result;
    }

    function get_order_number_period($nro, $period_date) {
        $anexo = $this->anexo;

        $period = 'container.sgr_periodos';
        $container = 'container.sgr_anexo_' . $anexo;

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active_one($anexo, $period_date);

        /* FIND ANEXO */
        foreach ($result as $list) {

            $new_query = array(
                'filename' => $list['filename'],
                5214 => $nro
            );
            $new_result = $this->mongowrapper->sgr->$container->findOne($new_query);
            if ($new_result) {
                $return_result[] = $new_result;
            }
        }
        return $return_result;
    }

    function get_order_number_by_sgrid($nro, $sgr_id) {


        $anexo = '12';

        $period = 'container.sgr_periodos';
        $container = 'container.sgr_anexo_' . $anexo;

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active_each_sgrid($anexo, $sgr_id);

        /* FIND ANEXO */
        foreach ($result as $list) {



            $new_query = array(
                'filename' => $list['filename'],
                5214 => $nro
            );
            $new_result = $this->mongowrapper->sgr->$container->findOne($new_query);
            if ($new_result) {
                $return_result[] = $new_result;
            }
        }
        return $return_result;
    }

    /* GET DATA */

    function get_warranty_partner_left($cuit) {
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;
        $period_value = $this->session->userdata['period'];

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active($anexo);
        $return_result = array();
        foreach ($result as $list) {
            $new_query = array(
                'filename' => $list['filename'],
                5349 => $cuit
            );
            $new_result = $this->mongowrapper->sgr->$container->findOne($new_query);
            if ($new_result) {
                $return_result[] = $new_result;
            }
        }
        return $return_result;
    }

    function get_warranty_partner_print($cuit, $period) {

        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;
        $period_value = $period;

        /* GET ACTIVE ANEXOS */
        // $result = $this->sgr_model->get_current_period_info($anexo, $period);
        $result = $this->sgr_model->get_active_print($anexo, $period);


        $return_result = array();
        foreach ($result as $list) {

            $new_query = array(
                'filename' => $list['filename'],
                5349 => $cuit
            );

            $new_results = $this->mongowrapper->sgr->$container->find($new_query);
            foreach ($new_results as $new_result) {
                $return_result[] = $new_result;
            }
        }



        return $return_result;
    }

    function get_warranty_partner_print_check($cuit, $period, $sgr_id) {

        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;
        $period_value = $period;

        /* GET ACTIVE ANEXOS */
        // $result = $this->sgr_model->get_current_period_info($anexo, $period);
        $result = $this->sgr_model->get_active_print_check($anexo, $period, $sgr_id);


        $return_result = array();
        foreach ($result as $list) {

            $new_query = array(
                'filename' => $list['filename'],
                5349 => $cuit
            );
            //var_dump($container);
            //var_dump(json_encode($new_query));
            $new_results = $this->mongowrapper->sgr->$container->find($new_query);
            foreach ($new_results as $new_result) {
                $return_result[] = $new_result;
            }
        }



        return $return_result;
    }

    /* GET SHARER PARTNER WARRANTIES */

    function get_sharer($cuit) {
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;
        $period_value = $this->session->userdata['period'];

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active($anexo, $period_value);

        $return_result = array();
        foreach ($result as $list) {
            $new_query = array(
                'filename' => $list['filename'],
                5349 => $cuit
            );

            $new_result = $this->mongowrapper->sgr->$container->find($new_query);
            foreach ($new_result as $list2) {
                $return_result[] = $list2;
            }
        }

        return $return_result;
    }

    function get_sharer_left($cuit) {
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;
        $period_value = $this->session->userdata['period'];

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active($anexo);

        $return_result = array();
        foreach ($result as $list) {
            $new_query = array(
                'filename' => $list['filename'],
                5349 => $cuit
            );

            $new_result = $this->mongowrapper->sgr->$container->find($new_query);
            foreach ($new_result as $list2) {
                $return_result[] = $list2;
            }
        }

        return $return_result;
    }

    /* SHARED SGR LIST */

    function get_sharer_all($period, $sgr_id) {
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active_print_check($anexo, $period, $sgr_id);

        $return_result = array();
        foreach ($result as $list) {
            $new_query = array(
                'filename' => $list['filename']
            );
            $field = array('5349');
            $new_result = $this->mongowrapper->sgr->$container->find($new_query, $field);
            foreach ($new_result as $all) {

                $return_result[] = $all[5349];
            }
        }
        $new_list = array_unique($return_result);
        return $new_list;
    }

    /* GET CREDITOR */

    function get_creditor($sharer, $cuit) {
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;
        $period_value = $this->session->userdata['period'];

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active($anexo);

        $return_result = array();
        foreach ($result as $list) {
            $new_query = array(
                'filename' => $list['filename'],
                5351 => $cuit,
                5349 => $sharer
            );
            $get_new_result = $this->mongowrapper->sgr->$container->find($new_query);
            foreach ($get_new_result as $new_result) {
                $return_result[] = $new_result['5214'];
            }
        }
        return $return_result;
    }

    /* REPORTS */

    function header_arr() {
        $headerArr = array('SGR'
            , 'ID'
            , 'NRO_ORDEN'
            , 'PARTICIPE'
            , 'CUIT_PARTICIPE'
            , 'ORIGEN'
            , 'TIPO'
            , 'PONDERACION'
            , 'IMPORTE'
            , 'MONEDA'
            , 'LIBRADOR_NOMBRE'
            , 'LIBRADOR_CUIT'
            , 'NRO_OPERACION_BOLSA'
            , 'ACREEDOR'
            , 'CUIT_ACREEDOR'
            , 'IMPORTE_CRED_GARANT'
            , 'MONEDA_CRED_GARANT'
            , 'TASA'
            , 'PUNTOS_ADIC_CRED_GARANT'
            , 'PLAZO'
            , 'GRACIA'
            , 'PERIODICIDAD'
            , 'SISTEMA'
            , 'DESTINO_CREDITO'
            , 'FILENAME');

        return $headerArr;
    }

    function header_arr_custom() {

        $headerArr = array('SGR'
            , 'ID'
            , 'NRO_ORDEN'
            , 'PARTICIPE'
            , 'CUIT_PARTICIPE'
            , 'PROVINCIA'
            , 'PARTIDO_MUNICIPIO_COMUNA'
            , 'LOCALIDAD'
            , 'CODIGO_POSTAL'
            , 'CANTIDAD_DE_EMPLEADOS'
            , 'CODIGO_ACTIVIDAD_AFIP'
            , 'ORIGEN'
            , 'TIPO'
            , 'PONDERACION'
            , 'IMPORTE'
            , 'MONEDA'
            , 'LIBRADOR_NOMBRE'
            , 'LIBRADOR_CUIT'
            , 'NRO_OPERACION_BOLSA'
            , 'ACREEDOR'
            , 'CUIT_ACREEDOR'
            , 'IMPORTE_CRED_GARANT'
            , 'MONEDA_CRED_GARANT'
            , 'TASA'
            , 'PUNTOS_ADIC_CRED_GARANT'
            , 'PLAZO'
            , 'GRACIA'
            , 'PERIODICIDAD'
            , 'SISTEMA'
            , 'DESTINO_CREDITO'
            , 'AÑO'
            , 'TRIMESTRE'
            , 'AÑO-MES'
            , 'AÑO-TRI'
            , 'FILENAME');

        return $headerArr;
    }

    function get_anexo_report($anexo, $parameter) {

        if (isset($parameter['custom_report']))
            $headerArr = $this->header_arr_custom();
        else
            $headerArr = $this->header_arr();


        $data = array($headerArr);
        $anexoValues = $this->get_anexo_data_report($anexo, $parameter);

        if (!$anexoValues) {
            return false;
        } else {
            foreach ($anexoValues as $values) {
                unset($values['_id']);
                unset($values['id']);
                $data[] = array_values($values);
            }

            $this->load->library('table');
            return $this->table->generate($data);
        }
    }

    function get_link_report() {

        $custom_report = $this->sgr_model->last_report_is_custom();


        if (isset($custom_report['zjquery']['custom_report']))
            $headerArr = $this->header_arr_custom();
        else
            $headerArr = $this->header_arr();

        $data[] = array($headerArr);
        $anexoValues = $this->sgr_model->last_report_general();

        $anexoValues = $this->sgr_model->last_report_general();


        if (!$anexoValues) {
            return false;
        } else {
            foreach ($anexoValues as $values) {

                $header = '<h2>Reporte GARANTIAS OTORGADAS</h2><h3>PER&Iacute;ODO/S: ' . $values['zjquery']['input_period_from'] . ' a ' . $values['zjquery']['input_period_to'] . '</h3>';

                if (isset($values['zjquery']['warranty_type']))
                    $header .= '<h3> Tipo de Garantia:' . $values['zjquery']['warranty_type'] . '</h3>';


                unset($values['_id']);
                unset($values['id']);
                $data[] = array_values($values);
            }
            $this->load->library('table');
            return $header . $this->table->generate($data);
        }
    }

    function get_assisted_pymes($period) {

        $rtn = array();
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;


        $model_anexo = "model_12";
        $this->load->Model($model_anexo);

        $get_result = $this->sgr_model->get_current_period_info($anexo, $period);

        $query = array("filename" => $get_result['filename']);
        $result = $this->mongowrapper->sgr->$container->find($query);
        $new_list = array();
        foreach ($result as $list) {
            $rtn[] = $list[5349];
        }


        return count(array_unique($rtn));
    }

    function get_amount_granted_qty($period) {

        $rtn = array();
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;

        $get_result = $this->sgr_model->get_current_period_info($anexo, $period);

        $query = array("filename" => $get_result['filename']);
        $result = $this->mongowrapper->sgr->$container->find($query);
        $new_list = array();
        foreach ($result as $list) {
            $rtn[] = $list[5214];
        }


        return count(array_unique($rtn));
    }

    function get_amount_granted($period) {

        $rtn = array();
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;

        $get_result = $this->sgr_model->get_current_period_info($anexo, $period);

        $query = array("filename" => $get_result['filename']);
        $result = $this->mongowrapper->sgr->$container->find($query);
        $new_list = array();
        foreach ($result as $list) {
            $rtn[] = $list[5218];
        }
        return array_sum($rtn);
    }

    function get_anexo_data_report($anexo, $parameter) {

        if (!isset($parameter)) {
            return false;
            exit();
        }


        header('Content-type: text/html; charset=UTF-8');

        $rtn = array();

        $order_number = (isset($parameter['order_number'])) ? $parameter['order_number'] : null;
        $cuit_sharer = (isset($parameter['cuit_sharer'])) ? $parameter['cuit_sharer'] : null;
        $cuit_creditor = (isset($parameter['cuit_creditor'])) ? $parameter['cuit_creditor'] : null;
        $warranty_type = (isset($parameter['warranty_type'])) ? $parameter['warranty_type'] : null;
        $custom_report = (isset($parameter['custom_report'])) ? $parameter['custom_report'] : null;



        $start_date = (isset($parameter['input_period_from'])) ? first_month_date($parameter['input_period_from']) : null;
        $end_date = (isset($parameter['input_period_to'])) ? last_month_date($parameter['input_period_to']) : null;

        /* GET PERIOD */
        $period_container = 'container.sgr_periodos';
        $query = array(
            'anexo' => $anexo,
            'status' => "activo",
            'period_date' => array(
                '$gte' => $start_date, '$lte' => $end_date
            )
        );

        if ($parameter['sgr_id'] != 666)
            $query["sgr_id"] = (float) $parameter['sgr_id'];
        $period_result = $this->mongowrapper->sgr->$period_container->find($query);

        $files_arr = array();
        $container = 'container.sgr_anexo_' . $anexo;

        $new_query = array();
        foreach ($period_result as $results) {
            $period = $results['period'];
            $new_query['$or'][] = array("filename" => $results['filename']);
        }
        if (isset($cuit_sharer))
            $new_query["5349"] = $cuit_sharer;

        if (isset($order_number))
            $new_query["5214"] = $order_number;


        if (isset($cuit_creditor))
            $new_query["5351"] = $cuit_creditor;

        if (isset($warranty_type)) {
            $regex = new MongoRegex('/^' . $warranty_type . '/i');
            $new_query["5216"] = $regex;
        }




        $result_arr = $this->mongowrapper->sgr->$container->find($new_query);
        /* TABLE DATA */
        if (isset($custom_report)) {
            return $this->ui_table_xls_custom($result_arr, $parameter);
        } else {
            return $this->ui_table_xls($result_arr, $parameter);
        }
    }

    function ui_table_xls($result, $parameter) {

        /* CSS 4 REPORT */
        css_reports_fn();

        $i = 1;

        $list = null;
        $this->sgr_model->del_tmp_general();

        foreach ($result as $list) {
            /* Vars */
            $new_list = array();

            $this->load->model('padfyj_model');
            $participate = $this->padfyj_model->search_name($list[5349]);
            $drawer = $this->padfyj_model->search_name((string) $list[5726]);



            /*  CREDITOR NAME */
            $creditor_mv = $this->get_mv_and_comercial_name($list[5351]);
            $creditor_padfyj = $this->padfyj_model->search_name($list[5351]);
            $creditor = ($creditor_mv) ? $creditor_mv : $creditor_padfyj;


            $this->load->model('app');
            $currency = $this->app->get_ops(549);
            $repayment_system = $this->app->get_ops(527);
            $rate = $this->app->get_ops(526);
            $periodicity = $this->app->get_ops(548);

            /* PONDERACION */
            $get_weighting = $this->sgr_model->get_warranty_type($list[5216][0], $list['period']);

            /* SGR DATA */
            $filename = trim($list['filename']);
            list($g_anexo, $g_denomination, $g_date) = explode("-", $filename);

            $destino_credito = (isset($list['DESTINO_CREDITO'])) ? $list['DESTINO_CREDITO'] : null;

            /* CURRENCY */
            $moneda = $list[5219][0];
            if (isset($moneda))
                $moneda = $currency[$moneda];


            $moneda_2 = $list[5758][0];
            if (isset($moneda_2))
                $moneda_2 = $currency[$moneda_2];


            /* RATE */
            $tasa = $list[5222][0];
            if (isset($tasa))
                $tasa = $rate[$tasa];

            /* PERDIODICITY */
            $periodicidad = $list[5226][0];
            if (isset($periodicidad))
                $periodicidad = $periodicity[$periodicidad];


            /* SYSTEM */
            $sistema = $list[5227][0];
            if (isset($sistema))
                $sistema = $repayment_system[$sistema];

            $new_list['a'] = trim($g_denomination);
            $new_list['b'] = $list['id'];
            $new_list['c'] = $list[5214];
            $new_list['d'] = $participate;
            $new_list['e'] = $list[5349];
            $new_list['f'] = $list[5215];
            $new_list['g'] = $list[5216][0];
            $new_list['h'] = dot_by_coma($get_weighting['weighted']);
            $new_list['i'] = dot_by_coma($list[5218]);
            $new_list['j'] = $moneda;
            $new_list['k'] = $drawer;
            $new_list['l'] = $list[5726];
            $new_list['m'] = $list[5727];
            $new_list['n'] = $creditor;
            $new_list['o'] = $list[5351];
            $new_list['p'] = dot_by_coma($list[5221]);
            $new_list['q'] = $moneda_2;
            $new_list['r'] = $tasa;
            $new_list['s'] = dot_by_coma($list[5223] / 100);
            $new_list['t'] = $list[5224];
            $new_list['u'] = $list[5225];
            $new_list['v'] = $periodicidad;
            $new_list['w'] = $sistema;
            $new_list['x'] = $destino_credito;
            $new_list['y'] = $list['filename'];
            $new_list['zjquery'] = $parameter;

            /* COUNT */
            $increment = $i++;
            report_account_records_fn($increment);

            /* ARRAY FOR RENDER */
            $rtn[] = $new_list;

            /* SAVE RESULT IN TMP DB COLLECTION */
            $this->sgr_model->save_tmp_general($new_list, $list['id']);
        }

        /* PRINT XLS LINK */
        link_report_and_back_fn();
        exit();

        /* REFRESH AND SHOW LINK */
        header("Location: $this->module_url_report");
        exit();
    }

    function ui_table_xls_custom($result, $parameter) {

        /* CSS 4 REPORT */
        css_reports_fn();

        $i = 1;

        $list = null;
        $this->sgr_model->del_tmp_general();

        foreach ($result as $list) {

            /* Vars */
            $new_list = array();

            $participate = $this->padfyj_model->search_name($list[5349]);


            /* PARTICIPATE DATA */
            $participate_data = $this->model_06->get_partner_stand_alone($list[5349]);

            $drawer = $this->padfyj_model->search_name((string) $list[5726]);



            /*  CREDITOR NAME */
            $creditor_mv = $this->get_mv_and_comercial_name($list[5351]);
            $creditor_padfyj = $this->padfyj_model->search_name($list[5351]);
            $creditor = ($creditor_mv) ? $creditor_mv : $creditor_padfyj;


            /* (AÑO) - (TRIMESTRE) - (AÑO-MES) - (AÑO-TRI) */
            list($year_data, $month_data, $day_data) = explode('-', $list[5215]);
            //$trim=floor(($mes-1) / 3)+1;
            $quarter = floor(($month_data - 1) / 3) + 1;


            $currency = $this->app->get_ops(549);
            $repayment_system = $this->app->get_ops(527);
            $rate = $this->app->get_ops(526);
            $periodicity = $this->app->get_ops(548);

            /* PONDERACION */
            $get_weighting = $this->sgr_model->get_warranty_type($list[5216][0], $list['period']);

            /* SGR DATA */
            $get_period_filename = $this->sgr_model->get_period_filename($list['filename']);            
            $sgr_info = $this->sgr_model->get_sgr_by_id_new($get_period_filename['sgr_id']); 

            $destino_credito = (isset($list['DESTINO_CREDITO'])) ? $list['DESTINO_CREDITO'] : null;

            /* CURRENCY */
            $moneda = $list[5219][0];
            if (isset($moneda))
                $moneda = $currency[$moneda];


            $moneda_2 = $list[5758][0];
            if (isset($moneda_2))
                $moneda_2 = $currency[$moneda_2];


            /* RATE */
            $tasa = $list[5222][0];
            if (isset($tasa))
                $tasa = $rate[$tasa];

            /* PERDIODICITY */
            $periodicidad = $list[5226][0];
            if (isset($periodicidad))
                $periodicidad = $periodicity[$periodicidad];


            /* SYSTEM */
            $sistema = $list[5227][0];
            if (isset($sistema))
                $sistema = $repayment_system[$sistema];

            /* EMPLOYEES QTY */
            $employees_qty = $participate_data[0]['CANTIDAD_DE_EMPLEADOS'];
            
            if($employees_qty==0){ //search 062
               $employees_qty = $this->model_062->get_count_partner_left($list[5349]);
            }

            $new_list['a'] = $sgr_info[1693];
            $new_list['b'] = $list['id'];
            $new_list['c'] = $list[5214];
            $new_list['d'] = $participate;
            $new_list['e'] = $list[5349];
            $new_list['f'] = $participate_data[0]['4651'][0];
            $new_list['g'] = $participate_data[0]['1699'][0];
            $new_list['h'] = htmlentities($participate_data[0]['1700'], null, "UTF-8");
            $new_list['i'] = $participate_data[0]['1698'];
            $new_list['j'] = $employees_qty;
            $new_list['k'] = $participate_data[0]['5208'];
            $new_list['l'] = $list[5215];
            $new_list['m'] = $list[5216][0];
            $new_list['n'] = dot_by_coma($get_weighting['weighted']);
            $new_list['o'] = dot_by_coma($list[5218]);
            $new_list['p'] = $moneda;
            $new_list['q'] = $drawer;
            $new_list['r'] = $list[5726];
            $new_list['s'] = $list[5727];
            $new_list['t'] = $creditor;
            $new_list['u'] = $list[5351];
            $new_list['v'] = dot_by_coma($list[5221]);
            $new_list['w'] = $moneda_2;
            $new_list['x'] = $tasa;
            $new_list['y'] = dot_by_coma($list[5223] / 100);
            $new_list['z'] = $list[5224];
            $new_list['za'] = $list[5225];
            $new_list['zb'] = $periodicidad;
            $new_list['zc'] = $sistema;
            $new_list['zd'] = $destino_credito;
            $new_list['ze'] = $year_data;
            $new_list['zf'] = $quarter;
            $new_list['zg'] = $year_data . "-" . $month_data;
            $new_list['zh'] = $year_data . "-" . $quarter;
            $new_list['zi'] = $list['filename'];
            $new_list['zjquery'] = $parameter;

            /* COUNT */
            $increment = $i++;
            report_account_records_fn($increment);

            /* ARRAY FOR RENDER */
            $rtn[] = $new_list;

            /* SAVE RESULT IN TMP DB COLLECTION */
            $this->sgr_model->save_tmp_general($new_list, $list['id']);
        }

        /* PRINT XLS LINK */
        link_report_and_back_fn();
        exit();

        /* REFRESH AND SHOW LINK */
        header("Location: $this->module_url_report");
        exit();
    }

}
