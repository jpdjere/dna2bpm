<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_061 extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('sgr/tools');

        $this->anexo = '061';
        /* Additional SGR users */
        $this->load->model('sgr/sgr_model');
        $additional_users = $this->sgr_model->additional_users($this->session->userdata('iduser'));
        $this->idu = (isset($additional_users)) ? $additional_users['sgr_idu'] : $this->session->userdata('iduser');
        /* SWITCH TO SGR DB */        
        $this->sgr_db=new $this->cimongo;
        #DB
        $this->sgr_db->switch_db('sgr');


        /* MODELS */

        $this->load->model('app');
        $this->load->model('padfyj_model');
        $this->load->model('model_06');
        $this->load->Model('model_12');

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
         * @example .... 
         * */
        $defdna = array(
            1 => 'CUIT_SOCIO_INCORPORADO', //CUIT_SOCIO_INCORPORADO
            2 => 'TIENE_VINCULACION', //TIENE_VINCULACION
            3 => 'CUIT_VINCULADO', //CUIT_VINCULADO
            4 => 'RAZON_SOCIAL_VINCULADO', //RAZON_SOCIAL_VINCULADO
            5 => 'TIPO_RELACION_VINCULACION', //TIPO_RELACION_VINCULACION
            6 => 'PORCENTAJE_ACCIONES', //"PORCENTAJE_ACCIONES"
        );


        $insertarr = array();
        foreach ($defdna as $key => $value) {
            $insertarr[$value] = $parameter[$key];
            $insertarr['CUIT_SOCIO_INCORPORADO'] = (string) $insertarr['CUIT_SOCIO_INCORPORADO'];
            $insertarr['CUIT_VINCULADO'] = (string) $insertarr['CUIT_VINCULADO'];

            /* FLOAT */
            $insertarr['PORCENTAJE_ACCIONES'] = (float) $insertarr['PORCENTAJE_ACCIONES'];
        }
        return $insertarr;
    }

    function save($parameter) {
        $period = $this->session->userdata['period'];
        $container = 'container.sgr_anexo_' . $this->anexo;

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
        $this->update_period($get_period['id'], $get_period['status']);

        $result = $this->app->put_array_sgr($id, $container, $parameter);

        if ($result) {
            /* ACTUALIZO PENDIND DEL ANEXO 06 */
            $get_pending = $this->sgr_model->get_current_period_info("06", $period);
            $this->update_pending($get_pending['id']);


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

    function save_period_pending($parameter) {
        /* ADD PERIOD */
        $container = 'container.sgr_periodos';
        $period = $this->session->userdata['period'];

        $id = $this->app->genid_sgr($container);
        $parameter['period'] = $period;
        $parameter['period_date'] = translate_period_date($period);
        $parameter['status'] = 'activo';
        $parameter['idu'] = (float) $this->idu;
        $parameter['activated_on'] = date('Y-m-d h:i:s');


        /*
         * VERIFICO PENDIENTE           
         */
        $get_period = $this->sgr_model->get_current_period_info($this->anexo, $period);

        $this->update_period($get_period['id'], $get_period['status']);
        $result = $this->app->put_array_sgr($id, $container, $parameter);
        if ($result) {
            /* ACTUALIZO PENDIND DEL ANEXO 06 */
            $get_pending = $this->sgr_model->get_current_period_info("06", $period);
            /* UPDATE */
            if (isset($get_period['status']))
                $this->update_period($get_period['id'], $get_period['status']);

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

    /* UPDATE ANEXO 06 */

    function update_pending($id) {
        $options = array('upsert' => true, 'w' => 1);
        $container = 'container.sgr_periodos';
        $query = array('id' => $id);
        $parameter = array(
            'status' => 'activo',
            'activated_on' => date('Y-m-d h:i:s')
        );

        $rs = $this->mongowrapper->sgr->$container->update($query, array('$set' => $parameter), $options);
        return $rs['err'];
    }

    function get_anexo_info($anexo, $parameter) {

        $headerArr = array("Tipo<br/>de<br/>Socio",
            "C.U.I.T Socio<br/>Incorporado",
            "Socio<br/>Incorporado",
            "Tiene<br/>Vinculacion",
            "C.U.I.T<br/>Vinculado",
            "Razón<br/>Social<br/>Vinculado",
            "Tipo<br/>Relación<br/>Vinculación",
            "Porcentaje<br/>Acciones",
            "Es<br/>Participe",
            "Es<br/>Protector"
        );
        $data = array($headerArr);
        $anexoValues = $this->get_anexo_data($anexo, $parameter);
        foreach ($anexoValues as $values) {
            $data[] = array_values($values);
        }
        $this->load->library('table');
        return $this->table->generate($data);
    }

    function get_anexo_data($anexo, $parameter) {
        header('Content-type: text/html; charset=UTF-8');
        $rtn = array();
        $container = 'container.sgr_anexo_' . $anexo;
        $query = array("filename" => $parameter);
        $result = $this->mongowrapper->sgr->$container->find($query);

        foreach ($result as $list) {

            /* Vars */
            if (isset($list['1695']))
                $cuit = str_replace("-", "", $list['1695']);

            $brand_name = $list['1693'];

            $partner_type = $this->app->get_ops(532);

            $parner_inc = $this->padfyj_model->search_name($list['CUIT_SOCIO_INCORPORADO']);
            $parner_linked = $this->padfyj_model->search_name((string) $list['CUIT_VINCULADO']);

            $type_partner_inc_value = false;


            $type_partner = $this->model_06->partner_type($list['CUIT_SOCIO_INCORPORADO']);

            /* SHARER */
            $type_partner_inc_sharer = $this->model_06->partner_type_linked_sharer((string) $list['CUIT_VINCULADO']);
            if (isset($type_partner_inc_sharer)) {
                foreach ($type_partner_inc_sharer as $partner_inc_sharer)
                    $type_partner_inc_value_sharer = $partner_inc_sharer[5272];
            }

            /* PROTECTOR */
            $type_partner_inc = $this->model_06->partner_type_linked((string) $list['CUIT_VINCULADO']);
            if (isset($type_partner_inc)) {
                foreach ($type_partner_inc as $partner_inc)
                    $type_partner_inc_value = $partner_inc[5272];
            }

            $parner_linked = ($parner_linked) ? $parner_linked : $list['RAZON_SOCIAL_VINCULADO'];

            $es_participe = "-";
            $es_protector = "-";

            if (!empty($list['CUIT_VINCULADO'])) {
                $es_participe = ($type_partner_inc_value_sharer[0] == "A") ? "SI" : "NO";
                $es_protector = ($type_partner_inc_value[0] == "B") ? "SI" : "NO";
            }

            $new_list = array();
            $new_list['TIPO_SOCIO'] = $type_partner;
            $new_list['CUIT_SOCIO_INCORPORADO'] = $list['CUIT_SOCIO_INCORPORADO'];
            $new_list['SOCIO_INCORPORADO'] = $parner_inc;
            $new_list['"TIENE_VINCULACION"'] = $list['TIENE_VINCULACION'];
            $new_list['"CUIT_VINCULADO"'] = $list['CUIT_VINCULADO'];
            $new_list['"RAZON_SOCIAL_VINCULADO"'] = $parner_linked;
            $new_list['"TIPO_RELACION_VINCULACION"'] = $list['TIPO_RELACION_VINCULACION'];
            $new_list['"PORCENTAJE_ACCIONES"'] = percent_format_custom($list['PORCENTAJE_ACCIONES'] * 100);
            $new_list['"PARTICIPE"'] = $es_participe;
            $new_list['"PROTECTOR"'] = $es_protector;



            $rtn[] = $new_list;
        }
        return $rtn;
    }

    /* REPORT */

    function header_arr() {
        $headerArr = array('SGR'
            , 'ID'
            , 'EJERCICIO'
            , 'PERIODO'
            , 'TIPO DE SOCIO'
            , 'CUIT SOCIO INCORPORADO'
            , 'SOCIO INCORPORADO'
            , 'TIENE VINCULACION'
            , 'CUIT VINCULADO'
            , 'RAZON SOCIAL VINCULADO'
            , 'TIPO RELACION VINCULACION'
            , 'PORCENTAJE ACCIONES'
            , 'PARTICIPE'
            , 'PROTECTOR'
            , 'FILENAME');

        return $headerArr;
    }

    function get_link_report($anexo) {



        $headerArr = $this->header_arr();

        $data[] = array($headerArr);
        $anexoValues = $this->sgr_model->last_report_general();



        if (!$anexoValues) {
            return false;
        } else {
            foreach ($anexoValues as $values) {

                $header = '<h2>Reporte  RELACIONES DE VINCULACION </h2><h3>PER&Iacute;ODO/S: ' . $values['query']['input_period_from'] . ' a ' . $values['query']['input_period_to'] . '</h3>';

                unset($values['_id']);
                unset($values['id']);
                $data[] = array_values($values);
            }
            $this->load->library('table');
            return $header . $this->table->generate($data);
        }
    }

    function get_anexo_report($anexo, $parameter) {

        $input_period_from = ($parameter['input_period_from']) ? : '01_1990';
        $input_period_to = ($parameter['input_period_to']) ? : '12_' . date("Y");

        /* HEADER TEMPLATE */
        $header_data = array();
        $header_data['input_period_to'] = $input_period_to;
        $header_data['input_period_from'] = $input_period_from;
        $header = $this->parser->parse('reports/form_' . $anexo . '_header', $header_data, TRUE);
        $tmpl = array('data' => $header);

        $data = array($tmpl);

        $anexoValues = $this->get_anexo_data_report($anexo, $parameter);
        foreach ($anexoValues as $values) {
            $data[] = array_values($values);
        }
        $this->load->library('table_custom');
        $newTable = $this->table_custom->generate($data);

        return $newTable;
    }

    function get_anexo_data_report($anexo, $parameter) {

        if (!isset($parameter)) {
            return false;
            exit();
        }

        header('Content-type: text/html; charset=UTF-8');
        $rtn = array();

        $input_period_from = ($parameter['input_period_from']) ? : '01_1990';
        $input_period_to = ($parameter['input_period_to']) ? : '12_' . date("Y");



        $start_date = first_month_date($input_period_from);
        $end_date = last_month_date($input_period_to);

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
        $container = 'container.sgr_anexo_' . $anexo;

        $new_query = array();
        $new_query_2 = array();
        foreach ($period_result as $results) {
            $period = $results['period'];
            $new_query[] = array("filename" => $results['filename']);
        }



        $or1 = array('$or' => $new_query);
        $or2 = array('$or' => $new_query_2);

        $query = array('$and' => array($or1, $or2));


        if (empty($new_query_2))
            $query = $or1;



        if (!empty($new_query))
            $result_arr = $this->mongowrapper->sgr->$container->find($query);

        /* TABLE DATA */
        return $this->ui_table_xls($result_arr, $anexo, $parameter);
    }

    function ui_table_xls($result, $anexo = null, $parameter) {




        /* CSS 4 REPORT */
        css_reports_fn();

        $i = 1;

        $list = null;
        $this->sgr_model->del_tmp_general();

        foreach ($result as $list) {


            $brand_name = $list['1693'];


            $partner_type = $this->app->get_ops(532);

            $parner_inc = $this->padfyj_model->search_name($list['CUIT_SOCIO_INCORPORADO']);
            $parner_linked = $this->padfyj_model->search_name((string) $list['CUIT_VINCULADO']);

            $type_partner_inc_value = false;

            $type_partner = $this->model_06->partner_type($list['CUIT_SOCIO_INCORPORADO'], $list['sgr_id']);

            /* SHARER */
            $type_partner_inc_sharer = $this->model_06->partner_type_linked_sharer((string) $list['CUIT_VINCULADO']);
            if (isset($type_partner_inc_sharer)) {
                foreach ($type_partner_inc_sharer as $partner_inc_sharer)
                    $type_partner_inc_value_sharer = $partner_inc_sharer[5272];
            }

            /* PROTECTOR */
            $type_partner_inc = $this->model_06->partner_type_linked((string) $list['CUIT_VINCULADO']);
            if (isset($type_partner_inc)) {
                foreach ($type_partner_inc as $partner_inc)
                    $type_partner_inc_value = $partner_inc[5272];
            }

            $parner_linked = ($parner_linked) ? $parner_linked : $list['RAZON_SOCIAL_VINCULADO'];

            $es_participe = "-";
            $es_protector = "-";

            if (!empty($list['CUIT_VINCULADO'])) {
                $es_participe = ($type_partner_inc_value_sharer[0] == "A") ? "SI" : "NO";
                $es_protector = ($type_partner_inc_value[0] == "B") ? "SI" : "NO";
            }


            /* SGR DATA */
            $filename = trim($list['filename']);
            list($g_anexo, $g_denomination, $g_date) = explode("-", $filename);

            $get_period_filename = $this->sgr_model->get_period_filename($list['filename']);
            $period = $get_period_filename['period'];
            list($period_month, $period_year) = explode("-", $period);

            $new_list = array();
            $new_list['a'] = $g_denomination;
            $new_list['b'] = $list['id'];
            $new_list['c'] = $period_year;
            $new_list['d'] = $period_year . "-" . $period_month;
            $new_list['e'] = $type_partner;
            $new_list['f'] = $list['CUIT_SOCIO_INCORPORADO'];
            $new_list['g'] = $parner_inc;
            $new_list['h'] = $list['TIENE_VINCULACION'];
            $new_list['i'] = $list['CUIT_VINCULADO'];
            $new_list['j'] = $parner_linked;
            $new_list['k'] = $list['TIPO_RELACION_VINCULACION'];
            $new_list['l'] = percent_format_custom($list['PORCENTAJE_ACCIONES'] * 100);
            $new_list['m'] = $es_participe;
            $new_list['n'] = $es_protector;
            $new_list['o'] = $list['filename'];
            $new_list['query'] = $parameter;
            $rtn[] = $new_list;
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
        exit;

        /* REFRESH AND SHOW LINK */
        header("Location: $this->module_url_report");
        exit();
    }

}
