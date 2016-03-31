<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_cnv_1 extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('sgr/tools');

        $this->anexo = 'cnv_1';
        /* Additional SGR users */
        $this->load->model('sgr/sgr_model');
        $additional_users = $this->sgr_model->additional_users($this->session->userdata('iduser'));
        $this->idu = (isset($additional_users)) ? $additional_users['sgr_idu'] : $this->session->userdata('iduser');
        /* SWITCH TO SGR DB */
        $this->load->library('cimongo/cimongo', '', 'sgr_db');
        $this->sgr_db->switch_db('sgr');

        if (!$this->idu) {
            header("$this->module_url/user/logout");
        }
    }

    function ui_table_xls($result, $result125) {

        $col1 = array();
        $col2 = array();
        $col3 = array();
        $col4 = array();
        $col5 = array();
        $col6 = array();

        /* 202 */
        foreach ($result as $list) {
            $new_list = array();
            $col1[] = (float) $list['SALDO'];
            $col2[] = (float) $list['CONTINGENTE_PROPORCIONAL_ASIGNADO'];
            $col3[] = (float) $list['DISPONIBLE'];
            $col4[] = (float) $list['RENDIMIENTO_ASIGNADO'];

            $col6[] = ($list['SALDO'] != 0) ? (float) $list['DISPONIBLE'] / (float) $list['SALDO'] : 0;
        }
        /* 125 */
        foreach ($result125 as $list125) {
            $total = array_sum(array($list125['SLDO_FINANC'], $list125['SLDO_COMER'], $list125['SLDO_TEC']));
            $col5[] = (float) ($total);
        }

        $new_list['1'] = money_format_integer(array_sum($col1));
        $new_list['2'] = money_format_integer(array_sum($col2));
        $new_list['3'] = money_format_integer(array_sum($col3));
        $new_list['4'] = money_format_integer(array_sum($col4));
        $new_list['5'] = money_format_integer(array_sum($col5));
        $new_list['6'] = percent_format_integer(array_sum($col6));

        $rtn[] = $new_list;
        return $rtn;
    }

    function ui_table_xls_1($result16) {

        $col1 = array();
        $col2 = array();
        $col3 = array();
        $col4 = array();


        /* 16 */
        foreach ($result16 as $list) {

            $col1[] = $list['120_DESDE_ENE_2011'];

            $sum_col_I = array_sum(array($list['80_HASTA_FEB_2010'], $list['80_DESDE_FEB_2010'], $list['80_DESDE_ENE_2011']));
            $col2[] = array_sum(array($list['120_DESDE_ENE_2011'], $sum_col_I));
            $col3[] = $sum_col_I;
            $col4[] = $list['GARANTIAS_VIGENTES'];
        }
        $precent = array_sum($col1) / array_sum($col4);

        $new_list['1'] = money_format_integer(array_sum($col1));
        $new_list['2'] = money_format_integer(array_sum($col2));
        $new_list['3'] = money_format_integer(array_sum($col3));
        $new_list['4'] = money_format_integer(array_sum($col4));
        $new_list['5'] = percent_format_integer($precent);


        $rtn[] = $new_list;
        return $rtn;
    }

    function ui_table_xls_2($result) {
        $container = 'container.sgr_anexo_15';


        foreach ($result as $results) {
            /* TOTALES */
            $query_sum = array(
                array(
                    '$match' => array('filename' => $results['filename']),
                ),
                array(
                    '$group' => array(
                        '_id' => null,
                        'total' => array('$sum' => '$MONTO'),
                    ),
                )
            );
            $result_total_aggregate = $this->mongowrapper->sgr->$container->aggregate($query_sum);


            $total[] = $result_total_aggregate['result'][0]['total'];
        }
        
        $new_query15 = array();
        foreach (range('A', 'L') as $key) {
            foreach ($result as $results) {
                $new_query15['INCISO_ART_25'] = $key;
                $period = $results['period'];
                $new_query15['$or'][] = array("filename" => $results['filename']);
                $result_arr15 = $this->mongowrapper->sgr->$container->find($new_query15);
            }


            $col2 = array();
            $col3 = array();
            $col4 = array();
            $col5 = array();
            $col6 = array();

            foreach ($result_arr15 as $list) {


                if ($list['MONEDA'] == "1") {
                    $col2[] = (float) $list['MONTO'];
                } else {
                    $col3[] = (float) $list['MONTO'];
                }

                $col4[] = (float) $list['MONTO'];
            }


            $precent = array_sum($col4) * 100 / array_sum($total);
            $sum_percent[] = $precent;

            $new_list = array();
            $new_list[$key]['0'] = $key;
            $new_list[$key]['1'] = money_format_integer(array_sum($col2));
            $new_list[$key]['2'] = money_format_integer(array_sum($col3));
            $new_list[$key]['3'] = money_format_integer(array_sum($col4));
            $new_list[$key]['4'] = percent_format_integer($precent);
            $rtn[] = $new_list;
        }

        /* TOTALES */
        $new_total = array();
        $new_total['total']['0'] = "<strong>TOTAL</strong>";
        $new_total['total']['1'] = "-";
        $new_total['total']['2'] = "-";
        $new_total['total']['3'] = "<strong>" . money_format_integer(array_sum($total)) . "</strong>";
        $new_total['total']['4'] = "<strong>" . percent_format_integer(array_sum($sum_percent)) . "</strong>";

        $rtn = array_merge($rtn, $new_total);
        return $rtn;
    }

    function ui_table_xls_3($result125) {

        $col1 = array();
        $col2 = array();
        $col3 = array();
        $col4 = array();


        /* 125 */
        foreach ($result125 as $list125) {
            $total = array_sum(array($list125['SLDO_FINANC'], $list125['SLDO_COMER'], $list125['SLDO_TEC']));
            $col4[] = (float) ($total);
            $col1[] = (float) ($list125['SLDO_FINANC']);
            $col2[] = (float) ($list125['SLDO_COMER']);
            $col3[] = (float) ($list125['SLDO_TEC']);
        }

        $new_list['1'] = money_format_integer(array_sum($col1));
        $new_list['2'] = money_format_integer(array_sum($col2));
        $new_list['3'] = money_format_integer(array_sum($col3));
        $new_list['4'] = money_format_integer(array_sum($col4));


        $rtn[] = $new_list;
        return $rtn;
    }

    function get_anexo_report($anexo, $parameter, $cnv) {

        $header_data = array();
        if ($parameter['sgr_id'] == '666') {
            $header_data['sgr'] = "Todas las SGR";
        } else {
            $sgrid = (float) $parameter['sgr_id'];
            $sgrname = $this->sgr_model->get_sgr_by_id_new($sgrid);
            $header_data['sgr'] = $sgrname['razon_social'];
        }
        $input_period_from = ($parameter['input_period_from'] == '01-1990') ? '01-2014' : $parameter['input_period_from'];

        /* HEADER TEMPLATE */

        $header_data['input_period_from'] = $input_period_from;


        $add_template = "";
        switch ($cnv) {
            case'1':
                $add_template = "_1";
                break;

            case'2':
                $add_template = "_2";
                break;

            case'3':
                $add_template = "_3";
                break;
        }


        $header = $this->parser->parse('reports/form_' . $anexo . $add_template . '_header', $header_data, TRUE);



        $tmpl = array('data' => $header);

        $data = array($tmpl);
        $anexoValues = $this->get_anexo_data_report($anexo, $parameter, $cnv);

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
        $input_period_from = ($parameter['input_period_from'] == '01-1990') ? '01-2014' : $parameter['input_period_from'];

        /* GET PERIOD ANEXO 202 */
        $period_container = 'container.sgr_periodos';
        $query = array(
            'anexo' => '202',
            'status' => "activo",
            'period' => $input_period_from
        );

        if ($parameter['sgr_id'] != 666)
            $query["sgr_id"] = (float) $parameter['sgr_id'];

        $period_result = $this->mongowrapper->sgr->$period_container->find($query);
        $container = 'container.sgr_anexo_202';

        $new_query = array();
        foreach ($period_result as $results) {
            $period = $results['period'];
            $new_query['$or'][] = array("filename" => $results['filename']);
        }


        $result_arr = $this->mongowrapper->sgr->$container->find($new_query);



        /* GET ANEXO 125 */
        $query125 = array(
            'anexo' => '125',
            'status' => "activo",
            'period' => $input_period_from
        );


        if ($parameter['sgr_id'] != 666)
            $query125["sgr_id"] = (float) $parameter['sgr_id'];

        $period_result_125 = $this->mongowrapper->sgr->$period_container->find($query125);
        $container = 'container.sgr_anexo_125';

        $new_query125 = array();
        foreach ($period_result_125 as $results) {
            $period = $results['period'];
            $new_query125['$or'][] = array("filename" => $results['filename']);
        }
        $result_arr125 = $this->mongowrapper->sgr->$container->find($new_query125);


        /* GET ANEXO 15 */
        $query15 = array(
            'anexo' => '15',
            'status' => "activo",
            'period' => $input_period_from
        );


        if ($parameter['sgr_id'] != 666)
            $query15["sgr_id"] = (float) $parameter['sgr_id'];

        $period_result_15 = $this->mongowrapper->sgr->$period_container->find($query15);


        /* GET ANEXO 16 */
        $query16 = array(
            'anexo' => '16',
            'status' => "activo",
            'period' => $input_period_from
        );


        if ($parameter['sgr_id'] != 666)
            $query16["sgr_id"] = (float) $parameter['sgr_id'];

        $period_result_16 = $this->mongowrapper->sgr->$period_container->find($query16);
        $container = 'container.sgr_anexo_16';

        $new_query16 = array();
        foreach ($period_result_16 as $results) {
            $period = $results['period'];
            $new_query16['$or'][] = array("filename" => $results['filename']);
        }
        $result_arr16 = $this->mongowrapper->sgr->$container->find($new_query16);


        switch ($cnv) {
            case '1':
                return $this->ui_table_xls_1($result_arr16);
                break;

            case '2':
                return $this->ui_table_xls_2($period_result_15);
                break;

            case '3':
                return $this->ui_table_xls_3($result_arr125);
                break;

            case 'default':
                return $this->ui_table_xls($result_arr, $result_arr125);
                break;
        }
    }

}
