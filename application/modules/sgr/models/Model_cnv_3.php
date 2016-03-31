<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_cnv_3 extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('sgr/tools');

        $this->anexo = '201';
        $this->idu = (float) switch_users($this->session->userdata('iduser'));
        /* SWITCH TO SGR DB */
        $this->load->library('cimongo/cimongo', '', 'sgr_db');
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

    function get_anexo_report($anexo, $parameter) {

        $input_period_from = ($parameter['input_period_from']) ? : '01_1990';
        $input_period_to = ($parameter['input_period_to']) ? : '12_' . date("Y");


        $start_date = first_month_date($input_period_from);
        $end_date = last_month_date($input_period_to);

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

    function get_anexo_data_report($anexo, $parameter, $cnv = null) {


        if (!isset($parameter)) {
            return false;
            exit();
        }

        header('Content-type: text/html; charset=UTF-8');

        $rtn = array();
        $input_period_from = ($parameter['input_period_from']) ? : '01_2014';
        $input_period_to = ($parameter['input_period_to']) ? : '12_' . date("Y");


        $start_date = first_month_date($input_period_from);
        $end_date = last_month_date($input_period_to);


        /* GET PERIOD ANEXO 202 */
        $period_container = 'container.sgr_periodos';
        $query = array(
            'anexo' => '15',
            'status' => "activo",
            'period_date' => array(
                '$gte' => $start_date, '$lte' => $end_date
            )
        );

        if ($parameter['sgr_id'] != 666)
            $query["sgr_id"] = (float) $parameter['sgr_id'];

        $period_result = $this->mongowrapper->sgr->$period_container->find($query);
        $period_result->sort(array('period' => 1, 'sgr_id' => -1));

        return $this->ui_table_xls($period_result);
    }

    function ui_table_xls($result) {
        $rtn = array();
        $container = 'container.sgr_anexo_15';


        foreach ($result as $each) {
            $new_query15 = array("filename" => $each['filename']);
            $list_result = $this->mongowrapper->sgr->$container->find($new_query15);
            $list_result->sort(array('INCISO_ART_25' => 1));


            foreach ($list_result as $list) {

                $sgr_info = $this->sgr_model->get_sgr_by_id_new($list['sgr_id']);
                list($g_anexo, $g_sgr, $g_year, $g_month, $g_day, $g_time) = explode("-", $list['filename']);

                /* SGR DATA */
                $get_period_filename = $this->sgr_model->get_period_filename($list['filename']);
                $this->load->model('padfyj_model');
                $sgr_name = $this->padfyj_model->search_name(str_replace("-","", $sgr_info[1695]));


                $curMonth = date($g_month, time());
                $curQuarter = ceil($curMonth / 3);
                $new_list = array();
                $new_list['col1'] = period_print_format($list['period']);
                $new_list['col2'] = $sgr_name;
                $new_list['col3'] = $sgr_info[1695];
                $new_list['col4'] = $list['INCISO_ART_25'];
                $new_list['col6'] = $list['DESCRIPCION'];
                $new_list['col7'] = $list['IDENTIFICACION'];
                $new_list['col8'] = $list['ENTIDAD_DESPOSITARIA'];
                $new_list['col9'] = dot_by_coma($list['MONTO']);
                $new_list['col10'] = $g_year;
                $new_list['col11'] = $g_month;
                $new_list['col12'] = $curQuarter;
                $new_list['col13'] = substr($g_day, 0, 2) . "/" . $g_month . "/" . trim($g_year);
                $rtn[] = $new_list;
            }
        }

        return $rtn;
    }

}
