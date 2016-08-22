<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_13 extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('sgr/tools');
        $this->load->Model('sgr/model_12');

        $this->anexo = '13';
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
         * @example .... TIPO_DE_GARANTIA	MENOR_90_DIAS	MENOR_180_DIAS	MENOR_365_DIAS	MAYOR_365_DIAS	VALOR_CONTRAGARANTIAS
         * */
        $defdna = array(
            1 => 'TIPO_DE_GARANTIA',
            2 => 'MENOR_90_DIAS',
            3 => 'MENOR_180_DIAS',
            4 => 'MENOR_365_DIAS',
            5 => 'MAYOR_365_DIAS',
            6 => 'VALOR_CONTRAGARANTIAS'
        );


        $insertarr = array();
        foreach ($defdna as $key => $value) {
            $insertarr[$value] = $parameter[$key];

            /* STRING */
            $insertarr['TIPO_DE_GARANTIA'] = (string) $insertarr['TIPO_DE_GARANTIA'];

            /* FLOAT */
            $insertarr['MENOR_90_DIAS'] = (float) $insertarr['MENOR_90_DIAS'];
            $insertarr['MENOR_180_DIAS'] = (float) $insertarr['MENOR_180_DIAS'];
            $insertarr['MENOR_365_DIAS'] = (float) $insertarr['MENOR_365_DIAS'];
            $insertarr['MAYOR_365_DIAS'] = (float) $insertarr['MAYOR_365_DIAS'];
            $insertarr['VALOR_CONTRAGARANTIAS'] = (float) $insertarr['VALOR_CONTRAGARANTIAS'];
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

            $new_list = array();
            $sum_totales = array_sum(array($list['MENOR_90_DIAS'], $list['MENOR_180_DIAS'], $list['MENOR_365_DIAS'], $list['MAYOR_365_DIAS']));
            $new_list['col1'] = $list['TIPO_DE_GARANTIA'];
            $new_list['col2'] = money_format_custom($list['MENOR_90_DIAS']);
            $new_list['col3'] = money_format_custom($list['MENOR_180_DIAS']);
            $new_list['col4'] = money_format_custom($list['MENOR_365_DIAS']);
            $new_list['col5'] = money_format_custom($list['MAYOR_365_DIAS']);
            $new_list['col6'] = money_format_custom($sum_totales);
            $new_list['col7'] = money_format_custom($list['VALOR_CONTRAGARANTIAS']);
            $rtn[] = $new_list;
        }
        return $rtn;
    }

    function get_anexo_data_clean($anexo, $parameter, $xls = false) {

        $rtn = array();
        $col2 = array();
        $col3 = array();
        $col4 = array();
        $col5 = array();
        $col6 = array();
        $col7 = array();


        $container = 'container.sgr_anexo_' . $anexo;
        $query = array("filename" => $parameter);
        $result = $this->mongowrapper->sgr->$container->find($query);
        $new_list = array();
        foreach ($result as $list) {
            $sum_totales = array_sum(array($list['MENOR_90_DIAS'], $list['MENOR_180_DIAS'], $list['MENOR_365_DIAS'], $list['MAYOR_365_DIAS']));
            $col2[] = (float) ($list['MENOR_90_DIAS']);
            $col3[] = (float) ($list['MENOR_180_DIAS']);
            $col4[] = (float) ($list['MENOR_365_DIAS']);
            $col5[] = (float) ($list['MAYOR_365_DIAS']);
            $col6[] = (float) ($sum_totales);
            $col7[] = (float) ($list['VALOR_CONTRAGARANTIAS']);
        }


        $new_list = array();

        $new_list['col1'] = "<strong>TOTALES</strong>";
        $new_list['col2'] = money_format_custom(array_sum($col2));
        $new_list['col3'] = money_format_custom(array_sum($col3));
        $new_list['col4'] = money_format_custom(array_sum($col4));
        $new_list['col5'] = money_format_custom(array_sum($col5));
        $new_list['col6'] = money_format_custom(array_sum($col6));
        $new_list['col7'] = money_format_custom(array_sum($col7));

        $rtn[] = $new_list;


        return $rtn;
    }

    function get_amount_total($period, $col) {

        $rtn = array();
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;

        $get_result = $this->sgr_model->get_current_period_info($anexo, $period);

        $query = array("filename" => $get_result['filename']);
        $result = $this->mongowrapper->sgr->$container->find($query);
        $new_list = array();
        foreach ($result as $list) {
            $rtn[] = $list[$col];
        }
        return array_sum($rtn);
    }

    /**
     * Nuevo Reporte Anexo 13
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
        if(!empty($this->input->post('sgr_checkbox')))
            $sgr_id_array = array_map('intval', $this->input->post('sgr_checkbox'));
      
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

        /*QUERY*/       
        $query =array(
            'aggregate'=>'container.sgr_periodos',
            'pipeline'=>
             array(
                array (
                        '$match' => array (
                            'anexo' => (string)$this->anexo,
                            'sgr_id' =>$sgr_id, 
                            'status'=>'activo',                            
                            'period_date' => array(
                                '$gte' => $start_date, '$lte' => $end_date
                            )
                        )                        
                    ),                         
                    array (
                        '$lookup' => array (
                            'from' => 'container.sgr_anexo_' . $this->anexo,
                            'localField' => 'filename',
                            'foreignField' => 'filename',
                            'as' => 'anexo_data'
                    )                            
                )      
            )     
        );    

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
                #$this->load->Model('model_12');

                if(isset($list['CUIT_PART']))
                    $cuit = $list['CUIT_PART'];
                
                if(isset($list['CUIT_ACREEDOR']))
                    $cuit_creditor = $list['CUIT_ACREEDOR'];

               

                $sum_totales = array_sum(array($list['MENOR_90_DIAS'], $list['MENOR_180_DIAS'], $list['MENOR_365_DIAS'], $list['MAYOR_365_DIAS']));

              
                list($period_month, $period_year) = explode("-", $period_info['period']);

                
                /* FILENAME */
                $sgr_info = array();
                if(isset($period_info['filename'])){
                    $filename = trim($list['filename']);                       
                    $sgr_info = $this->sgr_model->get_sgr_by_id_new($period_info['sgr_id']);
                }
        

                $new_list = array();
                $new_list['col0'] = $sgr_info[1693];
                $new_list['col1'] = $sgr_info[1695];    
                $new_list['col2'] = $list['id'];
                $new_list['col3'] = $period_year;
                $new_list['col4'] = $period_year . "-" . $period_month;
                $new_list['col5'] = $list['TIPO_DE_GARANTIA'];
                $new_list['col6'] = dot_by_coma($list['MENOR_90_DIAS']);
                $new_list['col7'] = dot_by_coma($list['MENOR_180_DIAS']);
                $new_list['col8'] = dot_by_coma($list['MENOR_365_DIAS']);
                $new_list['col9'] = dot_by_coma($list['MAYOR_365_DIAS']);
                $new_list['col10'] = dot_by_coma($sum_totales);
                $new_list['col11'] = dot_by_coma($list['VALOR_CONTRAGARANTIAS']);
                $new_list['col15'] = $filename;
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