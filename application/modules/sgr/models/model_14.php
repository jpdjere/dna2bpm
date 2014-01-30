<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_14 extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('sgr/tools');

        $this->anexo = '14';
        $this->idu = (int) $this->session->userdata('iduser');
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
        }
        return $insertarr;
    }

    function save($parameter) {
        $period = $this->session->userdata['period'];
        $container = 'container.sgr_anexo_' . $this->anexo;

        $parameter = array_map('trim', $parameter);
        $parameter = array_map('addSlashes', $parameter);

        /* FIX DATE */        
        $parameter['period'] = $period;
        $parameter['origin'] = 2013;
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
        $parameter['status'] = 'activo';
        $parameter['idu'] = $this->idu;

        /*
         * VERIFICO PENDIENTE           
         */
        $get_period = $this->sgr_model->get_period_info($this->anexo, $this->sgr_id, $period);
        $this->update_period($get_period['id'], $get_period['status']);

        $result = $this->app->put_array_sgr($id, $container, $parameter);

        if ($result) {
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
        $query = array('id' => (integer) $id);
        $rectified_on = date('Y-m-d h:i:s');
        $parameter = array('status' => 'rectificado', 'rectified_on'=>$rectified_on);
        $rs = $this->mongo->sgr->$container->update($query, array('$set' => $parameter), $options);
        return $rs['err'];
    }

    function get_anexo_info($anexo, $parameter) {       
        

        $headerArr = array("TIPO_DE_GARANTIA","MENOR_90_DIAS","MENOR_180_DIAS","MENOR_365_DIAS","MAYOR_365_DIAS","VALOR_CONTRAGARANTIAS");
        $data = array($headerArr);
        $anexoValues = $this->get_anexo_data($anexo, $parameter);
        foreach ($anexoValues as $values) {
            $data[] = array_values($values);
        }
        $this->load->library('table');
        return $this->table->generate($data);
    }

    function get_anexo_data($anexo, $parameter) {
        header('Content-type: text/html; charset=UTF-8');
        $rtn = array();
        $container = 'container.sgr_anexo_' . $anexo;
        $query = array("filename" => $parameter);
        $result = $this->mongo->sgr->$container->find($query);

        foreach ($result as $list) {
            /* Vars */
            $cuit = str_replace("-", "", $list['CUIT']);
            $this->load->model('padfyj_model');
            $brand_name = $this->padfyj_model->search_name($cuit);
            $brand_name = ($brand_name) ? $brand_name : strtoupper($list['RAZON_SOCIAL']);
            
            $new_list = array();
            $new_list['TIPO_DE_GARANTIA'] = $list['TIPO_DE_GARANTIA'];            
            $new_list['MENOR_90_DIAS'] = money_format_custom($list['MENOR_90_DIAS']);
            $new_list['MENOR_180_DIAS'] = money_format_custom($list['MENOR_180_DIAS']);
            $new_list['MENOR_365_DIAS'] = money_format_custom($list['MENOR_365_DIAS']);
            $new_list['MAYOR_365_DIAS'] = money_format_custom($list['MAYOR_365_DIAS']);
            $new_list['VALOR_CONTRAGARANTIAS'] = money_format_custom($list['VALOR_CONTRAGARANTIAS']);
            $rtn[] = $new_list;
        }
        return $rtn;
    }

}
