<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_cnv_1 extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('sgr/tools');

        $this->anexo = '202';
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

        $options = array('upsert' => true, 'safe' => true);
        $container = 'container.sgr_periodos';
        $query = array('id' => (float) $id);
        $parameter = array(
            'status' => 'rectificado',
            'rectified_on' => date('Y-m-d h:i:s'),
            'others' => $this->session->userdata['others'],
            'reason' => $this->session->userdata['rectify']
        );
        $rs = $this->mongo->sgr->$container->update($query, array('$set' => $parameter), $options);
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

    function ui_table_xls($result, $anexo = null) {      

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
                $new_list['SALDO_APORTE'] = (float) $list['SALDO'];
                $new_list['CONTINGENTE_PROPORCIONAL_ASIGNADO'] = (float) $list['CONTINGENTE_PROPORCIONAL_ASIGNADO'];
                $new_list['DEUDA_PROPORCIONAL_ASIGNADA'] = (float) $list['DEUDA_PROPORCIONAL_ASIGNADA'];
                $new_list['SALDO_APORTE_DISPONIBLE'] = (float) $list['DISPONIBLE'];
                $new_list['RENDIMIENTO_ASIGNADO'] = (float) $list['RENDIMIENTO_ASIGNADO'];
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

    function get_anexo_report($anexo, $parameter) {
        
       

        $input_period_from = ($parameter['input_period_from']) ? : '01_2014';

        /* HEADER TEMPLATE */
        $header_data = array();
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



        $start_date = first_month_date($input_period_from);


        /* GET PERIOD */
        $period_container = 'container.sgr_periodos';
        $query = array(
            'anexo' => $anexo,
            'status' => "activo",
            'period_date' => $start_date
        );


        if ($parameter['sgr_id'] != 666)
            $query["sgr_id"] = (float) $parameter['sgr_id'];


        $period_result = $this->mongo->sgr->$period_container->find($query);
        
        
        

        $files_arr = array();
        $container = 'container.sgr_anexo_' . $anexo;


        $new_query = array();
        foreach ($period_result as $results) {
            $period = $results['period'];
            $new_query['$or'][] = array("filename" => $results['filename']);
        }


        $result_arr = $this->mongo->sgr->$container->find($new_query);
        /* TABLE DATA */
        return $this->ui_table_xls($result_arr, $anexo);
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
        $result = $this->mongo->sgr->$container->find($query)->sort(array('NUMERO_DE_APORTE' => 1));
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
            $new_list['CONTINGENTE_PROPORCIONAL_ASIGNADO'] = (array_sum($col5));
            $new_list['DEUDA_PROPORCIONAL_ASIGNADA'] = (array_sum($col6));
            $new_list['SALDO_APORTE_DISPONIBLE'] = (array_sum($col7));
            $new_list['RENDIMIENTO_ASIGNADO'] = (array_sum($col8));
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

}
