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
    
    
     /**
     * Nuevo Reporte Anexo 126
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
       

       $start_date = first_month_date($parameter['input_period_from']);
       $end_date = last_month_date($parameter['input_period_to']);


       $socio = isset($parameter['cuit_socio']) ? $parameter['cuit_socio'] : array('$exists'  => true);
       switch($parameter['sgr_id']){
            case '666':
                $sgr_id = array('$exists'  => true);
            break;

            case '777':
                $sgr_id = array('$in'=>$parameter['sgr_id_array']);
            break;

            default:
                $sgr_id = (float)$parameter['sgr_id'];
            break;
       }


        $query=array(
                'aggregate'=>'container.sgr_anexo_' . $this->anexo,
                'pipeline'=>
                  array(                       
                      array (
                        '$lookup' => array (
                            'from' => 'container.sgr_periodos' ,
                            'localField' => 'filename',
                            'foreignField' => 'filename',
                            'as' => 'periodo')                        
                      ),
                      array (
                        '$match' => array (
                            'periodo.sgr_id' =>$sgr_id, 
                            'periodo.status'=>'activo' ,
                            'periodo.period_date' => array(
                                '$gte' => $start_date, '$lte' => $end_date
                        ))                        
                      )                 

                ));  

              
        $get=$this->sgr_db->command($query);
        $this->ui_table_xls($get['result'], $this->anexo, $parameter, $end_date);  
   }

   function ui_table_xls($result, $anexo = null, $parameter) {   
  
        $rtn_msg = array('no_record');
        
        $list = null;
        
        $this->sgr_model->del_tmp_general();

        foreach ($result as $list) {

            $get_period_filename = $this->sgr_model->get_period_filename($list['filename']);

            /* SGR DATA */ 
            $sgr_info = $this->sgr_model->get_sgr_by_id_new($get_period_filename['sgr_id']);
            
            $new_list = array();
            $new_list['col0'] = $sgr_info[1693];
            $new_list['col1'] = $sgr_info[1695];            
            $new_list['col2'] = $list['id'];
            $new_list['col3'] = $get_period_filename['period'];
            $new_list['col4'] = dot_by_coma($list['OTORGAMIENTO_PERIODO']);
            $new_list['col5'] = dot_by_coma($list['OTORGAMIENTO_PERIODO_PREVIO']);
            $new_list['col6'] = dot_by_coma($list['ADM_FDR']);
            $new_list['col7'] = dot_by_coma($list['ASESORAMIENTO']);
            $new_list['uquery'] = $parameter;

           /* ARRAY FOR RENDER */
            $rtn[] = $new_list;

            /* SAVE RESULT IN TMP DB COLLECTION */
            $this->sgr_model->save_tmp_general($new_list, $list['id']);
            $rtn_msg = array('ok');
        }
        echo json_encode($rtn_msg);
        exit;
    }
}