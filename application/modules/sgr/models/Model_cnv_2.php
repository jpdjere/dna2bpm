<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_cnv_2 extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('sgr/tools');

        $this->anexo = 'cnv_2';
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
            'anexo' => '202',
            'status' => "activo",
            'period_date' => array(
                '$gte' => $start_date, '$lte' => $end_date
            )
        );



        if ($parameter['sgr_id'] != 666)
            $query["sgr_id"] = (float) $parameter['sgr_id'];

        $period_result = $this->mongowrapper->sgr->$period_container->find($query);

        /* GET ANEXO 125 */
        $query125 = array(
            'anexo' => '125',
            'status' => "activo",
            'period_date' => array(
                '$gte' => $start_date, '$lte' => $end_date
            )
        );


        if ($parameter['sgr_id'] != 666)
            $query125["sgr_id"] = (float) $parameter['sgr_id'];

        $period_result_125 = $this->mongowrapper->sgr->$period_container->find($query125);

        return $this->ui_table_xls($period_result, $period_result_125);
    }

    function ui_table_xls($result202, $result125) {




        $new_list = array();
        foreach ($result202 as $list) {



            $sgr_info = $this->sgr_model->get_sgr_by_id_new($list['sgr_id']);
            $new_list_id = null;
            $new_list_id = $sgr_info['id'] . $sgr_id['period'];

            $this->load->model('padfyj_model');
            $sgr_name = $this->padfyj_model->search_name(str_replace("-", "", $sgr_info[1695]));


            $new_list[$new_list_id]['1'] = $sgr_name;
            $new_list[$new_list_id]['2'] = $sgr_info[1695];
            $new_list[$new_list_id]['3'] = period_print_format($sgr_id['period']);


            /* TOTALES 202 */
            $container = 'container.sgr_anexo_202';
            $query_sum = array(
                array(
                    '$match' => array('filename' => $list['filename']),
                ),
                array(
                    '$group' => array(
                        '_id' => null,
                        '4' => array('$sum' => '$SALDO'),
                        '5' => array('$sum' => '$DISPONIBLE'),
                        '6' => array('$sum' => '$CONTINGENTE_PROPORCIONAL_ASIGNADO'),
                        '7' => array('$sum' => '$RENDIMIENTO_ASIGNADO')
                    ),
                )
            );
            $totales = $this->mongowrapper->sgr->$container->aggregate($query_sum);

            $new_list[$new_list_id]['4'] = $totales['result'][0]['4'];
            $new_list[$new_list_id]['5'] = $totales['result'][0]['5'];
            $new_list[$new_list_id]['6'] = $totales['result'][0]['6'];
            $new_list[$new_list_id]['7'] = $totales['result'][0]['7'];
        }
        /* 125 */
        foreach ($result125 as $list125) {
            /* TOTALES 202 */
            $container = 'container.sgr_anexo_125';
            $query_sum = array(
                array(
                    '$match' => array('filename' => $list125['filename']),
                ),
                array(
                    '$group' => array(
                        '_id' => null,
                        '8' => array('$sum' => '$SLDO_FINANC'),
                        '9' => array('$sum' => '$SLDO_COMER'),
                        '10' => array('$sum' => '$SLDO_TEC')
                    ),
                )
            );



            $totales = $this->mongowrapper->sgr->$container->aggregate($query_sum);



            $sgr_id = $this->sgr_model->get_period_filename(trim($list125['filename']));

            $new_list_id = $sgr_id['sgr_id'] . $sgr_id['period'];

            $col8 = $totales['result'][0]['8'];
            $col9 = $totales['result'][0]['9'];
            $col10 = $totales['result'][0]['10'];

            $col11 = array($col8, $col9, $col10);

            $new_list[$new_list_id]['8'] = $col8;
            $new_list[$new_list_id]['9'] = $col9;
            $new_list[$new_list_id]['10'] = $col10;
            $new_list[$new_list_id]['11'] = array_sum($col11);
        }




        $x_list = array();
        $col_total = array();
        foreach ($new_list as $each) {

            if (isset($each['1'])) {

                $col12 = ($each['11'] / $each['4']) * 100;
                $x_col_12[] = $col12;
                $each['12'] = $col12;

                /* SIN MOVIMIENTO */
                for ($i = 4; $i <= 11; $i++) {
                    $each[$i] = (isset($each[$i])) ? $each[$i] : 0;
                    $col_total[$i] = $each[$i];
                    $each[$i] = dot_by_coma($each[$i]);
                }

                $x_list[] = $each;
                $x_col[] = $col_total;
            }
        }




        /* TOTALES */
        $new_total = array();
        $new_total['total']['0'] = "<strong>TOTAL</strong>";
        $new_total['total']['1'] = "-";
        $new_total['total']['2'] = "-";

        /* SUMO */
        $new_parcial = array();
        foreach ($x_col as $parcial) {
            for ($x = 4; $x <= 11; $x++) {
                $new_parcial['total'][$x] += $parcial[$x];
            }
        }
        /* FORMATO */
        foreach ($new_parcial as $totales) {
            for ($x = 4; $x <= 11; $x++) {
                $new_total['total'][$x] = "<strong>" . dot_by_coma($totales[$x]) . "<strong>";
            }
        }

        $new_total['total']['12'] = "<strong>" . array_sum($x_col_12) . "</strong>";
        $x_list = array_merge($x_list, $new_total);




        return $x_list;
    }

}
