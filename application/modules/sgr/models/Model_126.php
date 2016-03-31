<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_126 extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('sgr/tools');

        $this->anexo = '126';
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
         * @example .... OTORGAMIENTO_PERIODO	OTORGAMIENTO_PERIODO_PREVIO  ADM_FDR	ASESORAMIENTO
         * */
        $defdna = array(
            1 => 'OTORGAMIENTO_PERIODO',
            2 => 'OTORGAMIENTO_PERIODO_PREVIO',
            3 => 'ADM_FDR',
            4 => 'ASESORAMIENTO'
        );


        $insertarr = array();
        foreach ($defdna as $key => $value) {
            $insertarr[$value] = $parameter[$key];
            /* FLOAT */
            $insertarr['OTORGAMIENTO_PERIODO'] = (float) $insertarr['OTORGAMIENTO_PERIODO'];
            $insertarr['OTORGAMIENTO_PERIODO_PREVIO'] = (float) $insertarr['OTORGAMIENTO_PERIODO_PREVIO'];
            $insertarr['ADM_FDR'] = (float) $insertarr['ADM_FDR'];
            $insertarr['ASESORAMIENTO'] = (float) $insertarr['ASESORAMIENTO'];
        }
        return $insertarr;
    }

    function save($parameter) {
        $period = $this->session->userdata['period'];
        $container = 'container.sgr_anexo_' . $this->anexo;

        $parameter['period'] = $period;
        $parameter['origen'] = "2013";

        $id = $this->app->genid_sgr($container);

        //$result = $this->app->put_array_sgr($id, $container, $parameter);
        $criteria = array('id' => $id);
        $update = array('$set' => $parameter);
        $options = array('upsert' => true, 'w' => 1);
        $result = $this->mongowrapper->sgr->selectCollection($container)->update($criteria, $update, $options);

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

        //$result = $this->app->put_array_sgr($id, $container, $parameter);
        $criteria = array('id' => $id);
        $update = array('$set' => $parameter);
        $options = array('upsert' => true, 'w' => 1);
        $result = $this->mongowrapper->sgr->selectCollection($container)->update($criteria, $update, $options);

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

    function get_anexo_info($anexo, $parameter, $xls = false) {

        /* HEADER TEMPLATE */
        $header_data = array();

        $header = $this->parser->parse('prints/anexo_' . $anexo . '_header', TRUE);
        $tmpl = array('data' => $header);

        $data = array($tmpl);

        $anexoValues = $this->get_anexo_data($anexo, $parameter, $xls);
        $anexoValues2 = $this->get_anexo_data_clean($anexo, $parameter, $xls);
        $anexoValues = array_merge($anexoValues, $anexoValues2);
        foreach ($anexoValues as $values) {
            $data[] = array_values($values);
        }

        $this->load->library('table_custom');
        $newTable = $this->table_custom->generate($data);
        return $newTable;
    }

    function get_anexo_data($anexo, $parameter) {
        header('Content-type: text/html; charset=UTF-8');
        $rtn = array();
        $container = 'container.sgr_anexo_' . $anexo;
        $query = array("filename" => $parameter);
        $result = $this->mongowrapper->sgr->$container->find($query);

        foreach ($result as $list) {

            /* Vars 	  	ASESORAMIENTO*/          
            $new_list = array();
            $new_list['col1'] = money_format_custom($list['OTORGAMIENTO_PERIODO']);
            $new_list['col2'] = money_format_custom($list['OTORGAMIENTO_PERIODO_PREVIO']);
            $new_list['col3'] = money_format_custom($list['ADM_FDR']);
            $new_list['col4'] = money_format_custom($list['ASESORAMIENTO']);
            $rtn[] = $new_list;
        }
        return $rtn;
    }

    function get_anexo_data_clean($anexo, $parameter, $xls = false) {

        $rtn = array();

        return $rtn;
    }
    
    
    /* REPORT */

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
            unset($values['_id']);
            unset($values['id']);
            $data[] = array_values($values);
        }



        $this->load->library('table_custom');
        $newTable = $this->table_custom->generate($data);

        return $newTable;
    }
    
    function header_arr() {
        $headerArr = array('SGR'
            , 'ID'
            , 'PERIODO'
            , 'COMISIONES DEVENGADAS POR OTORGAMIENTO DE GARANTIAS EN EL PERIODO'
            , 'COMISIONES DEVENGADAS POR GARANTÍAS OTORGADAS EN PERIODO PREVIO'
             , 'PREVIOS COMISIONES DEVENGADAS POR ADMINISTRACION DE FONDO DE RIESGO'
             , 'COMISIONES DEVENGADAS POR ASESORAMIENTO'
            , 'FILENAME'
        );
        return $headerArr;
    }
    
    function get_link_report($anexo, $parameter) {

        $headerArr = $this->header_arr();

        $data[] = array($headerArr);
        $anexoValues = $this->sgr_model->last_report_general();

        if (!$anexoValues) {
            return false;
        } else {
            foreach ($anexoValues as $values) {

                $header = '<h2>Reporte  COMISIONES  </h2><h3>PER&Iacute;ODO/S: ' . $values['query']['input_period_from'] . ' a ' . $values['query']['input_period_to'] . '</h3>';

                unset($values['_id']);
                unset($values['id']);
                $data[] = array_values($values);
            }
            $this->load->library('table');
            return $header . $this->table->generate($data);
        }
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
            
            
       // var_dump(json_encode($query));

        $period_result = $this->mongowrapper->sgr->$period_container->find($query);
        $container = 'container.sgr_anexo_' . $anexo;
        

        $new_query = array();
        $new_query_2 = array();
        foreach ($period_result as $results) {
            
            //var_dump($results);
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

            /* Vars */

            $get_period_filename = $this->sgr_model->get_period_filename($list['filename']);

            $filename = trim($list['filename']);
            list($g_anexo, $g_denomination, $g_date) = explode("-", $filename);

            

            $new_list = array();
            $new_list['a'] = trim($g_denomination);
            $new_list['b'] = $list['id'];
            $new_list['c'] = period_print_format($get_period_filename['period']);
            $new_list['d'] = dot_by_coma($list['OTORGAMIENTO_PERIODO']);
            $new_list['e'] = dot_by_coma($list['OTORGAMIENTO_PERIODO_PREVIO']);
            $new_list['f'] = dot_by_coma($list['ADM_FDR']);
            $new_list['g'] = dot_by_coma($list['ASESORAMIENTO']);
            $new_list['h'] = $list['filename'];
            $new_list['query'] = $parameter;

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
