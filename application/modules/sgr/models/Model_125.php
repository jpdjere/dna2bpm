<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_125 extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('sgr/tools');

        $this->anexo = '125';
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
         * @example .... CUIT_PART	CUIT_ACREEDOR	SLDO_FINANC	SLDO_COMER	SLDO_TEC
         * */
        $defdna = array(
            1 => 'CUIT_PART',
            2 => 'CUIT_ACREEDOR',
            3 => 'SLDO_FINANC',
            4 => 'SLDO_COMER',
            5 => 'SLDO_TEC'
        );


        $insertarr = array();
        foreach ($defdna as $key => $value) {
            $insertarr[$value] = $parameter[$key];
            /* STRING */
            $insertarr['CUIT_PART'] = (string) $insertarr['CUIT_PART'];
            $insertarr['CUIT_ACREEDOR'] = (string) $insertarr['CUIT_ACREEDOR'];
            /* FLOAT */
            $insertarr['SLDO_FINANC'] = (float) $insertarr['SLDO_FINANC'];
            $insertarr['SLDO_COMER'] = (float) $insertarr['SLDO_COMER'];
            $insertarr['SLDO_TEC'] = (float) $insertarr['SLDO_TEC'];
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
            /* Vars */

            $this->load->model('padfyj_model');
            $brand_name_participate = $this->padfyj_model->search_name($list['CUIT_PART']);
            $brand_name_creditor = $this->padfyj_model->search_name($list['CUIT_ACREEDOR']);

            $total = array_sum(array($list['SLDO_FINANC'], $list['SLDO_COMER'], $list['SLDO_TEC']));

            $new_list = array();
            $new_list['col1'] = $list['CUIT_PART'];
            $new_list['col2'] = $brand_name_participate;
            $new_list['col3'] = $list['CUIT_ACREEDOR'];
            $new_list['col4'] = $brand_name_creditor;
            $new_list['col5'] = money_format_custom($list['SLDO_FINANC']);
            $new_list['col6'] = money_format_custom($list['SLDO_COMER']);
            $new_list['col7'] = money_format_custom($list['SLDO_TEC']);
            $new_list['col8'] = money_format_custom($total);
            $rtn[] = $new_list;
        }
        return $rtn;
    }

    function get_anexo_data_clean($anexo, $parameter, $xls = false) {

        $rtn = array();

        $col5 = array();
        $col6 = array();
        $col7 = array();
        $col8 = array();




        $container = 'container.sgr_anexo_' . $anexo;
        $query = array("filename" => $parameter);
        $result = $this->mongowrapper->sgr->$container->find($query);

        $new_list = array();
        foreach ($result as $list) {
            $total = array_sum(array($list['SLDO_FINANC'], $list['SLDO_COMER'], $list['SLDO_TEC']));
            $col5[] = (float) ($list['SLDO_FINANC']);
            $col6[] = (float) ($list['SLDO_COMER']);
            $col7[] = (float) ($list['SLDO_TEC']);
            $col8[] = (float) ($total);
        }


        $new_list = array();

        $new_list['col1'] = "<strong>TOTALES</strong>";
        $new_list['col2'] = "-";
        $new_list['col3'] = "-";
        $new_list['col4'] = "-";
        $new_list['col5'] = money_format_custom(array_sum($col5));
        $new_list['col6'] = money_format_custom(array_sum($col6));
        $new_list['col7'] = money_format_custom(array_sum($col7));
        $new_list['col8'] = money_format_custom(array_sum($col8));

        $rtn[] = $new_list;


        return $rtn;
    }

    function get_balance_by_partner($cuit, $period) {

        $this->load->Model('sgr_model');
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;

        /* PERIOD FILE */

        $result = $this->sgr_model->get_current_period_info($anexo, $period);
        $rtn = array();
        //var_dump($result);
        $query = array("filename" => $result['filename'], "CUIT_PART" => $cuit);
        //var_dump($query);
        $new_result = $this->mongowrapper->sgr->$container->find($query);
        $new_arr = array();

        foreach ($new_result as $each) {

            $balance = array($each['SLDO_FINANC'], $each['SLDO_COMER'], $each['SLDO_TEC']);
            $new_arr[] = array_sum($balance);
        }

        $rtn['count'] = $new_result->count();
        $rtn['balance'] = array_sum($new_arr);

        return $rtn;
    }

    function get_balance_by_partner_chek($cuit, $period, $sgr_id) {

        $this->load->Model('sgr_model');
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;

        /* PERIOD FILE */

        $result = $this->sgr_model->get_current_period_info_check($anexo, $period, $sgr_id);
        $rtn = array();
        //var_dump($result);
        $query = array("filename" => $result['filename'], "CUIT_PART" => $cuit);
        //var_dump(json_encode($query));
        $new_result = $this->mongowrapper->sgr->$container->find($query);
        $new_arr = array();

        foreach ($new_result as $each) {

            $balance = array($each['SLDO_FINANC'], $each['SLDO_COMER'], $each['SLDO_TEC']);
            $new_arr[] = array_sum($balance);
        }

        $rtn['count'] = $new_result->count();
        $rtn['balance'] = array_sum($new_arr);

        return $rtn;
    }    

    function cuits_by_period($period) {
        $rtn = false;

        $period_container = 'container.sgr_periodos';
        $field = array('filename');
        $query_period = array("period" => $period, "status" => "activo", "anexo" => $this->anexo, 'sgr_id' => $this->sgr_id);
        $period_result = $this->mongowrapper->sgr->$period_container->findOne($query_period, $field);
        $period_filename = $period_result['filename'];

        $container = 'container.sgr_anexo_' . $this->anexo;
        $query = array("filename" => $period_filename);
        
        $new_result = $this->mongowrapper->sgr->$container->find($query);
        foreach ($new_result as $each) {
            $rtn[] = $each['CUIT_PART'];
        }
        
        $rtn_unique = array_unique($rtn);
        
        return $rtn_unique;
    }
/**
     * Nuevo Reporte Anexo 125
     *
     * @name generate_report
     *
     * @see SGR()
     *
     * @author Diego Otero <daotero@industria.gob.ar>
     *
     * @date Apr 19, 2016
     *
     * @param type $query
     */

     

     function get_link_report($anexo) {

        $headerArr = header_arr($anexo);
        $title_report = $this->sgr_model->get_anexo($anexo);
        
        $data[] = array($headerArr);
        $anexoValues = $this->sgr_model->last_report_general();

        if (!$anexoValues) {
            return false;
        } else {
            foreach ($anexoValues as $values) {
                $header = '<h2>Reporte '.$anexo.' - '.strtoupper($title_report['title']).' </h2><h3>PERIODO/S: ' . $values['uquery']['input_period_from'] . ' a ' . $values['uquery']['input_period_to'] . '</h3>';

                unset($values['_id']);
                unset($values['id']);
                $data[] = array_values($values);
            }
            $this->load->library('table');
            return $header . $this->table->generate($data);
        }
    }

    function generate_report($parameter=array()) {
        

        /*REPORT POST VALUES*/        


        # STANDARD 
        $report_name = $this->input->post('report_name');
        $start_date = first_month_date($this->input->post('input_period_from'));       
        $end_date = last_month_date($this->input->post('input_period_to'));

        switch ($this->input->post('sgr')) {
            case '666':
                $sgr_id = array('$exists'  => true);
            break;

            case '777':
                $sgr_id = array('$in'=>$sgr_id_array);
            break;

            default:
                $sgr_id = (float)$this->input->post('sgr');
            break;
        }

        

        $query = reports_default_query($this->anexo, $start_date, $end_date, $sgr_id);
        $get=$this->sgr_db->command($query);        
        $this->ui_table_xls($get['result'], $this->anexo, $parameter, $end_date);       
    }


    function ui_table_xls($result, $anexo = null, $parameter) { 

        #custom
        $rtn_msg = array('no_record');
        
        $list = null;
        
        $this->sgr_model->del_tmp_general();
        
        foreach ($result as $period_info) {
        
            foreach ($period_info['anexo_data'] as $list) {
                
                /* Vars */
                $new_list = array();

                $this->load->model('padfyj_model');
                $this->load->Model('model_06');

                $cuit = null;
                $cuit_creditor = null;
                $brand_name = null;
                $brand_name_get_creditor = null;
                $get_period_filename = null;
                $filename = null;
                $total = null;

                $cuit = $list['CUIT_PART'];
                $cuit_creditor = $list['CUIT_ACREEDOR'];

                $brand_name = $this->padfyj_model->search_name($cuit);
                if (!isset($brand_name)) {
                    $brand_name_get = $this->model_06->get_partner_name($cuit);
                    $brand_name = $brand_name_get;
                }

                $brand_name_get_creditor = $this->padfyj_model->search_name($cuit_creditor);
                if (!isset($brand_name_get_creditor)) {
                    $brand_name_get = $this->model_06->get_partner_name($cuit_creditor);
                    $brand_name_get_creditor = $brand_name_get;
                }  

                $total = array_sum(array($list['SLDO_FINANC'], $list['SLDO_COMER'], $list['SLDO_TEC']));
                
                /* FILENAME */
                $sgr_info = array();
                $period_month = null;
                $period_year = null;

                if(isset($period_info['filename'])){
                    $filename = trim($list['filename']);   
                    
                    $sgr_info = $this->sgr_model->get_sgr_by_id_new($period_info['sgr_id']);
                    list($period_month, $period_year) = explode("-", $period_info['period']);
                }

                $new_list = array();
                $new_list['col0'] = $sgr_info[1693];
                $new_list['col1'] = $sgr_info[1695];            
                $new_list['col2'] = $list['id'];
                $new_list['col3'] = period_print_format($period_info['period']);
                $new_list['col4'] = $brand_name;
                $new_list['col5'] = $cuit;
                $new_list['col6'] = $brand_name_get_creditor;
                $new_list['col7'] = $cuit_creditor;
                $new_list['col8'] = dot_by_coma($list['SLDO_COMER']);
                $new_list['col9'] = dot_by_coma($list['SLDO_FINANC']);
                $new_list['col10'] = dot_by_coma($list['SLDO_TEC']);
                $new_list['col11'] = dot_by_coma($total);
                $new_list['col12'] = $filename;
                $new_list['uquery'] = $parameter;
              
                /* SAVE RESULT IN TMP DB COLLECTION */
                $this->sgr_model->save_tmp_general($new_list, $list['id']);
                $rtn_msg = array('ok');
            } 
        }
       echo json_encode($rtn_msg);
       exit;
    }
   
}