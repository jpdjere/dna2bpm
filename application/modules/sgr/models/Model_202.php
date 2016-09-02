<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_202 extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('sgr/tools');

        $this->anexo = '202';
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
         * NUMERO_DE_APORTE	
         * CONTINGENTE_PROPORCIONAL_ASIGNADO	
         * DEUDA_PROPORCIONAL_ASIGNADA	
         * RENDIMIENTO_ASIGNADO
         * */
        $defdna = array(
            1 => 'NUMERO_DE_APORTE',
            2 => 'CONTINGENTE_PROPORCIONAL_ASIGNADO',
            3 => 'DEUDA_PROPORCIONAL_ASIGNADA',
            4 => 'RENDIMIENTO_ASIGNADO',
            5 => 'CUIT_PROTECTOR',
            6 => 'SALDO',
            7 => 'DISPONIBLE'
        );

        $insertarr = array();
        foreach ($defdna as $key => $value) {
            $insertarr[$value] = $parameter[$key];

            /* INT & FLOAT */
            $insertarr["NUMERO_DE_APORTE"] = (int) $insertarr["NUMERO_DE_APORTE"];
            $insertarr["CONTINGENTE_PROPORCIONAL_ASIGNADO"] = (float) $insertarr["CONTINGENTE_PROPORCIONAL_ASIGNADO"];
            $insertarr["DEUDA_PROPORCIONAL_ASIGNADA"] = (float) $insertarr["DEUDA_PROPORCIONAL_ASIGNADA"];
            $insertarr["RENDIMIENTO_ASIGNADO"] = (float) $insertarr["RENDIMIENTO_ASIGNADO"];


            /* DYNAMIC INFO */
            $model_201 = 'model_201';
            $this->load->Model($model_201);


            $get_movement_data = $this->$model_201->get_movement_data_print($insertarr['NUMERO_DE_APORTE'], $this->session->userdata['period']);
            $partener_info = $this->$model_201->get_input_number_print($insertarr['NUMERO_DE_APORTE'], $this->session->userdata['period']);
            foreach ($partener_info as $partner) {
                $cuit = $partner["CUIT_PROTECTOR"];
            }


            $retiros = array_sum(array($get_movement_data['RETIRO']));
            $saldo = $get_movement_data['APORTE'] - $retiros;
            $disponible = $saldo - (float) $insertarr['CONTINGENTE_PROPORCIONAL_ASIGNADO'];
            /* */

            $insertarr["CUIT_PROTECTOR"] = $cuit;
            $insertarr["SALDO"] = $saldo;
            $insertarr["DISPONIBLE"] = $disponible;


//            if ($this->sgr_id == 2207746538) {
//                echo "update";
//                debug($this->sgr_id);
//                debug($get_movement_data);
//                exit();
//            }
        }
        return $insertarr;
    }

    function save($parameter) {
        $period = $this->session->userdata['period'];
        $container = 'container.sgr_anexo_' . $this->anexo;
        $id = $this->app->genid_sgr($container);

        $parameter['period'] = $period;
        $parameter['origen'] = "2013";

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
        $template = array();

        if ($xls)
            $template['xls'] = true;

        $header = $this->parser->parse('prints/anexo_' . $anexo . '_header', $template, TRUE);
        $tmpl = array('data' => $header);

        $data = array($tmpl);

        $anexoValues = $this->get_anexo_data($anexo, $parameter, $xls);
        $anexoValues2 = $this->get_anexo_data_clean($anexo, $parameter, $xls);
        $anexoValues = array_merge($anexoValues, $anexoValues2);
        foreach ($anexoValues as $values) {
            $data[] = array_values($values);
        }

        $this->load->library('table_custom');
        $newTable = str_replace($fix_table, '<thead>', $this->table_custom->generate($data));
        return $newTable;
    }

    function get_anexo_data($anexo, $parameter, $xls = false) {
        
        


        header('Content-type: text/html; charset=UTF-8');
        $rtn = array();
        $container = 'container.sgr_anexo_' . $anexo;
        $query = array("filename" => $parameter);
        $result = $this->mongowrapper->sgr->$container->find($query)->sort(array('NUMERO_DE_APORTE' => 1));

        foreach ($result as $list) {
            /*
             * Vars 								
             */
            $this->load->model('padfyj_model');


            $model_201 = 'model_201';
            $this->load->Model($model_201);


            $get_movement_data = $this->$model_201->get_movement_data_print($list['NUMERO_DE_APORTE'], $list['period']);
            $partener_info = $this->$model_201->get_input_number_print($list['NUMERO_DE_APORTE'], $list['period']);
            foreach ($partener_info as $partner) {
                $cuit = $partner["CUIT_PROTECTOR"];
            }
            $brand_name = $this->padfyj_model->search_name($list["CUIT_PROTECTOR"]);


            $new_list = array();
            $new_list['NUMERO_DE_APORTE'] = $list['NUMERO_DE_APORTE']; //$list['NUMERO_DE_APORTE'];
            $new_list['RAZON_SOCIAL'] = $brand_name;
            $new_list['CUIT'] = $list["CUIT_PROTECTOR"];
            if ($xls) {
                $new_list['SALDO_APORTE'] = dot_by_coma(round((float) $list['SALDO'], 2));
                $new_list['CONTINGENTE_PROPORCIONAL_ASIGNADO'] = dot_by_coma((float) $list['CONTINGENTE_PROPORCIONAL_ASIGNADO']);
                $new_list['DEUDA_PROPORCIONAL_ASIGNADA'] = dot_by_coma((float) $list['DEUDA_PROPORCIONAL_ASIGNADA']);
                $new_list['SALDO_APORTE_DISPONIBLE'] = dot_by_coma(round((float) $list['DISPONIBLE'], 2));
                $new_list['RENDIMIENTO_ASIGNADO'] = dot_by_coma((float) $list['RENDIMIENTO_ASIGNADO']);
            } else {
                $new_list['SALDO_APORTE'] = money_format_custom($list['SALDO']);
                $new_list['CONTINGENTE_PROPORCIONAL_ASIGNADO'] = money_format_custom((float) $list['CONTINGENTE_PROPORCIONAL_ASIGNADO']);
                $new_list['DEUDA_PROPORCIONAL_ASIGNADA'] = money_format_custom((float) $list['DEUDA_PROPORCIONAL_ASIGNADA']);
                $new_list['SALDO_APORTE_DISPONIBLE'] = money_format_custom($list['DISPONIBLE']);
                $new_list['RENDIMIENTO_ASIGNADO'] = money_format_custom((float) $list['RENDIMIENTO_ASIGNADO']);
            }
            $rtn[] = $new_list;
        }       
        return $rtn;
    }

    function get_anexo_data_clean($anexo, $parameter, $xls = false) {

        $rtn = array();
        $col4 = array();
        $col5 = array();
        $col6 = array();
        $col7 = array();
        $col8 = array();

        $container = 'container.sgr_anexo_' . $anexo;
        $query = array("filename" => $parameter);
        $result = $this->mongowrapper->sgr->$container->find($query)->sort(array('NUMERO_DE_APORTE' => 1));
        $new_list = array();
        foreach ($result as $list) {


            $col4[] = (float) $list['SALDO'];
            $col5[] = (float) $list['CONTINGENTE_PROPORCIONAL_ASIGNADO'];
            $col6[] = (float) $list['DEUDA_PROPORCIONAL_ASIGNADA'];
            $col7[] = (float) $list['DISPONIBLE'];
            $col8[] = (float) $list['RENDIMIENTO_ASIGNADO'];
        }


        $new_list = array();
        $new_list['NUMERO_DE_APORTE'] = "<strong>TOTALES</strong>"; //$list['NUMERO_DE_APORTE'];
        $new_list['RAZON_SOCIAL'] = "-";
        $new_list['CUIT'] = "-";
        if ($xls) {
            $new_list['SALDO_APORTE'] = (array_sum($col4));
            $new_list['CONTINGENTE_PROPORCIONAL_ASIGNADO'] = dot_by_coma(array_sum($col5));
            $new_list['DEUDA_PROPORCIONAL_ASIGNADA'] = dot_by_coma(array_sum($col6));
            $new_list['SALDO_APORTE_DISPONIBLE'] = dot_by_coma(array_sum($col7));
            $new_list['RENDIMIENTO_ASIGNADO'] = dot_by_coma(array_sum($col8));
        } else {
            $new_list['SALDO_APORTE'] = money_format_custom(array_sum($col4));
            $new_list['CONTINGENTE_PROPORCIONAL_ASIGNADO'] = money_format_custom(array_sum($col5));
            $new_list['DEUDA_PROPORCIONAL_ASIGNADA'] = money_format_custom(array_sum($col6));
            $new_list['SALDO_APORTE_DISPONIBLE'] = money_format_custom(array_sum($col7));
            $new_list['RENDIMIENTO_ASIGNADO'] = money_format_custom(array_sum($col8));
        }
        $rtn[] = $new_list;


        return $rtn;
    }

     /**
     * Nuevo Reporte Anexo 061
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
                /* Vars */
                $this->load->model('padfyj_model');
                $this->load->Model('model_06');
                $this->load->Model('model_12');


                $cuit = $list['CUIT_PROTECTOR'];
                $brand_name = $this->padfyj_model->search_name($cuit);
                if (!isset($brand_name)) {
                    $brand_name_get = $this->model_06->get_partner_name($cuit);
                    $brand_name = $brand_name_get;
                }

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
                $new_list['col7'] = $list['NUMERO_DE_APORTE'];
                $new_list['col5'] = $brand_name;
                $new_list['col6'] = $cuit;           
                $new_list['col8'] = dot_by_coma($list['SALDO']);
                $new_list['col9'] = dot_by_coma($list['CONTINGENTE_PROPORCIONAL_ASIGNADO']);
                $new_list['col10'] = dot_by_coma($list['DEUDA_PROPORCIONAL_ASIGNADA']);
                $new_list['col11'] = dot_by_coma($list['DISPONIBLE']);
                $new_list['col12'] = dot_by_coma($list['RENDIMIENTO_ASIGNADO']);
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

    function ui_table_xls_ORI($result, $anexo = null) {

        foreach ($result as $list) {

            /* Vars */
            $this->load->model('padfyj_model');
            $this->load->Model('model_06');
            $this->load->Model('model_12');


            $cuit = $list['CUIT_PROTECTOR'];
            $brand_name = $this->padfyj_model->search_name($cuit);
            if (!isset($brand_name)) {
                $brand_name_get = $this->model_06->get_partner_name($cuit);
                $brand_name = $brand_name_get;
            }

            $get_period_filename = $this->sgr_model->get_period_filename($list['filename']);

            $sgr_info = $this->sgr_model->get_sgr_by_id_new($get_period_filename['sgr_id']);

            $new_list = array();
            $new_list['col1'] = $sgr_info[1693];
            $new_list['col2'] = $list['id'];
            $new_list['col3'] = period_print_format($get_period_filename['period']);
            $new_list['col7'] = $list['NUMERO_DE_APORTE'];
            $new_list['col5'] = $brand_name;
            $new_list['col6'] = $cuit;           
            $new_list['col8'] = dot_by_coma($list['SALDO']);
            $new_list['col9'] = dot_by_coma($list['CONTINGENTE_PROPORCIONAL_ASIGNADO']);
            $new_list['col10'] = dot_by_coma($list['DEUDA_PROPORCIONAL_ASIGNADA']);
            $new_list['col11'] = dot_by_coma($list['DISPONIBLE']);
            $new_list['col12'] = dot_by_coma($list['RENDIMIENTO_ASIGNADO']);
            $new_list['col13'] = $list['filename'];
            $rtn[] = $new_list;
        }

        return $rtn;
    }

}
