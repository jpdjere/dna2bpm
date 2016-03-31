<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_16 extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('sgr/tools');
        $this->load->Model('sgr/model_12');

        $this->anexo = '16';
        /* Additional SGR users */
        $this->load->model('sgr/sgr_model');
        $additional_users = $this->sgr_model->additional_users($this->session->userdata('iduser'));
        $this->idu = (isset($additional_users)) ? $additional_users['sgr_idu'] : $this->session->userdata('iduser');
        /* SWITCH TO SGR DB */
        $this->load->library('cimongo/Cimongo.php', '', 'sgr_db');
        $this->sgr_db->switch_db('sgr');

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
         * @example         
         * SALDO_PROMEDIO_PONDERADO_GARANTIAS_VIGENTES_80_HASTA_FEB_2010	
         * SALDO_PROMEDIO_PONDERADO_GARANTIAS_VIGENTES_120_HASTA_FEB_2010	
         * SALDO_PROMEDIO_PONDERADO_GARANTIAS_VIGENTES_80_DESDE_FEB_2010	
         * SALDO_PROMEDIO_PONDERADO_GARANTIAS_VIGENTES_120_DESDE_FEB_2010	
         * SALDO_PROMEDIO_PONDERADO_GARANTIAS_VIGENTES_80_DESDE_ENE_2011	
         * SALDO_PROMEDIO_PONDERADO_GARANTIAS_VIGENTES_120_DESDE_ENE_2011	
         * SALDO_PROMEDIO_FDR_TOTAL_COMPUTABLE	
         * SALDO_PROMEDIO_FDR_CONTINGENTE
         * */
        $defdna = array(
            1 => 'GARANTIAS_VIGENTES',
            2 => '80_HASTA_FEB_2010',
            3 => '120_HASTA_FEB_2010',
            4 => '80_DESDE_FEB_2010',
            5 => '120_DESDE_FEB_2010',
            6 => '80_DESDE_ENE_2011',
            7 => '120_DESDE_ENE_2011',
            8 => 'FDR_TOTAL_COMPUTABLE',
            9 => 'FDR_CONTINGENTE'
        );


        $insertarr = array();
        foreach ($defdna as $key => $value) {
            $insertarr[$value] = $parameter[$key];
        }
        return $insertarr;
    }

    function save($parameter) {
        $period = $this->session->userdata['period'];
        $container = 'container.sgr_anexo_' . $this->anexo;

        $parameter['period'] = $period;
        $parameter['origen'] = "2013";
        //$parameter['PROMEDIO_SALDO_MENSUAL'] = $period;
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

    function get_anexo_ddjj($period) {

        $rtn = array();
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;

        $get_result = $this->sgr_model->get_current_period_info($anexo, $period);

        $query = array("filename" => $get_result['filename']);
        $result = $this->mongowrapper->sgr->$container->find($query);
        $new_list = array();
        foreach ($result as $list) {
            $col9 = array_sum(array($list['80_HASTA_FEB_2010'], $list['80_DESDE_FEB_2010'], $list['80_DESDE_ENE_2011']));
            $col10 = array_sum(array($list['120_HASTA_FEB_2010'], $list['120_DESDE_FEB_2010'], $list['120_DESDE_ENE_2011']));
            $col13 = $list['FDR_TOTAL_COMPUTABLE'] - $list['FDR_CONTINGENTE'];
            $col14 = ($list['GARANTIAS_VIGENTES'] / $list['FDR_TOTAL_COMPUTABLE']) * 100;
            $col15 = ($col9 / $list['FDR_TOTAL_COMPUTABLE']) * 100;
            $col16 = ($col10 / $list['FDR_TOTAL_COMPUTABLE']) * 100;

            $new_list = array();
            $new_list['col1'] = $month_value;
            $new_list['col2'] = money_format_custom($list['GARANTIAS_VIGENTES']);
            $new_list['col3'] = money_format_custom($list['80_HASTA_FEB_2010']);
            $new_list['col4'] = money_format_custom($list['120_HASTA_FEB_2010']);
            $new_list['col5'] = money_format_custom($list['80_DESDE_FEB_2010']);
            $new_list['col6'] = money_format_custom($list['120_DESDE_FEB_2010']);
            $new_list['col7'] = money_format_custom($list['80_DESDE_ENE_2011']);
            $new_list['col8'] = money_format_custom($list['120_DESDE_ENE_2011']);
            $new_list['col9'] = money_format_custom($col9, true);
            $new_list['col10'] = money_format_custom($col10, true);
            $new_list['col11'] = money_format_custom($list['FDR_TOTAL_COMPUTABLE']);
            $new_list['col12'] = money_format_custom($list['FDR_CONTINGENTE']);
            $new_list['col13'] = money_format_custom($col13, true);
            $new_list['col14'] = percent_format_custom($col14);
            $new_list['col15'] = percent_format_custom($col15);
            $new_list['col16'] = percent_format_custom($col16);
            $rtn[] = $new_list;
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
            /* Vars 								
             */

            $this->load->model('padfyj_model');
            $transmitter_name = $this->padfyj_model->search_name($list['CUIT_EMISOR']);
            $transmitter_name = ($transmitter_name) ? $transmitter_name : strtoupper($list['EMISOR']);

            $depositories_name = $this->sgr_model->get_depositories($list['CUIT_DEPOSITARIO']);
            $depositories_name = ($depositories_name) ? $depositories_name['nombre'] : strtoupper($list['ENTIDAD_DESPOSITARIA']);

            $this->load->model('app');
            $get_month = explode("-", $list['period']);
            $month_value = translate_month_spanish($get_month[0]);

            $col9 = array_sum(array($list['80_HASTA_FEB_2010'], $list['80_DESDE_FEB_2010'], $list['80_DESDE_ENE_2011']));
            $col10 = array_sum(array($list['120_HASTA_FEB_2010'], $list['120_DESDE_FEB_2010'], $list['120_DESDE_ENE_2011']));
            $col13 = $list['FDR_TOTAL_COMPUTABLE'] - $list['FDR_CONTINGENTE'];
            $col14 = ($list['GARANTIAS_VIGENTES'] / $list['FDR_TOTAL_COMPUTABLE']) * 100;
            $col15 = ($col9 / $list['FDR_TOTAL_COMPUTABLE']) * 100;
            $col16 = ($col10 / $list['FDR_TOTAL_COMPUTABLE']) * 100;

            $new_list = array();
            $new_list['col1'] = $month_value;
            $new_list['col2'] = money_format_custom($list['GARANTIAS_VIGENTES']);
            $new_list['col3'] = money_format_custom($list['80_HASTA_FEB_2010']);
            $new_list['col4'] = money_format_custom($list['120_HASTA_FEB_2010']);
            $new_list['col5'] = money_format_custom($list['80_DESDE_FEB_2010']);
            $new_list['col6'] = money_format_custom($list['120_DESDE_FEB_2010']);
            $new_list['col7'] = money_format_custom($list['80_DESDE_ENE_2011']);
            $new_list['col8'] = money_format_custom($list['120_DESDE_ENE_2011']);
            $new_list['col9'] = money_format_custom($col9, true);
            $new_list['col10'] = money_format_custom($col10, true);
            $new_list['col11'] = money_format_custom($list['FDR_TOTAL_COMPUTABLE']);
            $new_list['col12'] = money_format_custom($list['FDR_CONTINGENTE']);
            $new_list['col13'] = money_format_custom($col13, true);
            $new_list['col14'] = percent_format_custom($col14);
            $new_list['col15'] = percent_format_custom($col15);
            $new_list['col16'] = percent_format_custom($col16);
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
            , 'PROMEDIO SALDO MENSUAL CORRESPONDIENTE AL MES'
            , 'SALDO PROMEDIO GARANTIAS VIGENTES'
            , 'SALDO PROMEDIO PONDERADO GARANTIAS VIGENTES 80 HASTA FEB 2010'
            , 'SALDO PROMEDIO PONDERADO GARANTIAS VIGENTES 120 HASTA FEB 2010'
            , 'SALDO PROMEDIO PONDERADO GARANTIAS VIGENTES 80 DESDE FEB 2010'
            , 'SALDO PROMEDIO PONDERADO GARANTIAS VIGENTES 120 DESDE FEB 2010'
            , 'SALDO PROMEDIO PONDERADO GARANTIAS VIGENTES 80 DESDE ENE 2011'
            , 'SALDO PROMEDIO PONDERADO GARANTIAS VIGENTES 120 DESDE ENE 2011'
            , 'SALDO TOTAL DE GARANTIAS VIGENTES QUE COMPUTAN PARA EL 80%'
            , 'SALDO TOTAL DE GARANTIAS VIGENTES QUE COMPUTAN PARA EL 120%'
            , 'SALDO PROMEDIO FONDE DE RIESGO TOTAL COMPUTABLE'
            , 'SALDO PROMEDIO FONDE DE RIESGO CONTINGENTE'
            , 'SALDO PROMEDIO FONDE DE RIESGO TOTAL DISPONIBLE'
            , 'SOLVENCIA'
            , 'GRADO DE UTILIZACIÓN PARA EL 80%'
            , 'GRADO DE UTILIZACIÓN PARA EL 120%'
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
                $header = '<h2>Reporte   GRADOS DE UTILIZACION G.D.U. </h2><h3>PER&Iacute;ODO/S: ' . $values['uquery']['input_period_from'] . ' a ' . $values['uquery']['input_period_to'] . '</h3>';

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

            $this->load->model('padfyj_model');
            $transmitter_name = $this->padfyj_model->search_name($list['CUIT_EMISOR']);
            $transmitter_name = ($transmitter_name) ? $transmitter_name : strtoupper($list['EMISOR']);

            $depositories_name = $this->sgr_model->get_depositories($list['CUIT_DEPOSITARIO']);
            $depositories_name = ($depositories_name) ? $depositories_name['nombre'] : strtoupper($list['ENTIDAD_DESPOSITARIA']);

            $this->load->model('app');
            $get_month = explode("-", $list['period']);
            $month_value = translate_month_spanish($get_month[0]);

            $col9 = array_sum(array($list['80_HASTA_FEB_2010'], $list['80_DESDE_FEB_2010'], $list['80_DESDE_ENE_2011']));
            $col10 = array_sum(array($list['120_HASTA_FEB_2010'], $list['120_DESDE_FEB_2010'], $list['120_DESDE_ENE_2011']));
            $col13 = $list['FDR_TOTAL_COMPUTABLE'] - $list['FDR_CONTINGENTE'];
            $col14 = ($list['GARANTIAS_VIGENTES'] / $list['FDR_TOTAL_COMPUTABLE']) * 100;
            $col15 = ($col9 / $list['FDR_TOTAL_COMPUTABLE']) * 100;
            $col16 = ($col10 / $list['FDR_TOTAL_COMPUTABLE']) * 100;


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
            $new_list['e'] = $month_value;
            $new_list['f'] = dot_by_coma($list['GARANTIAS_VIGENTES']);
            $new_list['g'] = dot_by_coma($list['80_HASTA_FEB_2010']);
            $new_list['h'] = dot_by_coma($list['120_HASTA_FEB_2010']);
            $new_list['i'] = dot_by_coma($list['80_DESDE_FEB_2010']);
            $new_list['j'] = dot_by_coma($list['120_DESDE_FEB_2010']);
            $new_list['k'] = dot_by_coma($list['80_DESDE_ENE_2011']);
            $new_list['l'] = dot_by_coma($list['120_DESDE_ENE_2011']);
            $new_list['m'] = dot_by_coma($col9, true);
            $new_list['n'] = dot_by_coma($col10, true);
            $new_list['o'] = dot_by_coma($list['FDR_TOTAL_COMPUTABLE']);
            $new_list['p'] = dot_by_coma($list['FDR_CONTINGENTE']);
            $new_list['q'] = dot_by_coma($col13, true);
            $new_list['r'] = percent_format_custom($col14);
            $new_list['s'] = percent_format_custom($col15);
            $new_list['t'] = percent_format_custom($col16);
            $new_list['u'] = $list['filename'];
            $new_list['uquery'] = $parameter;

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
