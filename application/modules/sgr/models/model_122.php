<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_122 extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('sgr/tools');

        $this->anexo = '122';
        $this->idu = (int) $this->session->userdata('iduser');
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
         * @example .... NRO_GARANTIA	NUMERO_CUOTA_CUYO_VENC_MODIFICA	FECHA_VENC_CUOTA	FECHA_VENC_CUOTA_NUEVA	MONTO_CUOTA	SALDO_AL_VENCIMIENTO


         * */
        $defdna = array(
            1 => 'NRO_GARANTIA', //NRO_GARANTIA
            2 => 'NUMERO_CUOTA_CUYO_VENC_MODIFICA', //NUMERO_CUOTA_CUYO_VENC_MODIFICA
            3 => 'FECHA_VENC_CUOTA', //FECHA_VENC_CUOTA
            4 => 'FECHA_VENC_CUOTA_NUEVA', //FECHA_VENC_CUOTA_NUEVA
            5 => 'MONTO_CUOTA', //MONTO_CUOTA
            6 => 'SALDO_AL_VENCIMIENTO', //SALDO_AL_VENCIMIENTO
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
        $parameter['FECHA_VENC_CUOTA'] = strftime("%Y-%m-%d", mktime(0, 0, 0, 1, -1 + $parameter['FECHA_VENC_CUOTA'], 1900));
        $parameter['FECHA_VENC_CUOTA_NUEVA'] = strftime("%Y-%m-%d", mktime(0, 0, 0, 1, -1 + $parameter['FECHA_VENC_CUOTA_NUEVA'], 1900));

        $parameter['period'] = $period;

        $parameter['origin'] = 2013;
        $id = $this->app->genid($container);

        $result = $this->app->put_array($id, $container, $parameter);

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
        $id = $this->app->genid($container);
        $parameter['period'] = $period;
        $parameter['status'] = 'activo';

        /*
         * VERIFICO PENDIENTE           
         */
        $get_period = $this->sgr_model->get_period_info($this->anexo, $this->sgr_id, $period);
        $this->update_period($get_period['id'], $get_period['status']);

        $result = $this->app->put_array($id, $container, $parameter);

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
        $status = 'rectificado';
        $parameter = array('status' => $status);
        $rs = $this->mongo->db->$container->update($query, array('$set' => $parameter), $options);
        return $rs['err'];
    }

    function get_anexo_info($anexo, $parameter) {

        $headerArr = array("NRO ORDEN", "NRO CUOTA", "VENCIMIENTO", "CUOTA GTA PESOS", "CUOTA MENOR PESOS");
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
        $result = $this->mongo->db->$container->find($query);
        
        foreach ($result as $list) {            
            /* Vars */
            $new_list = array();
            $new_list['NRO_GARANTIA'] = $list['NRO_GARANTIA'];
            $new_list['NUMERO_CUOTA_CUYO_VENC_MODIFICA'] = $list['NUMERO_CUOTA_CUYO_VENC_MODIFICA'];
            $new_list['FECHA_VENC_CUOTA'] = $list['FECHA_VENC_CUOTA'];
            $new_list['FECHA_VENC_CUOTA_NUEVA'] = $list['FECHA_VENC_CUOTA_NUEVA'];
            $new_list['MONTO_CUOTA'] = money_format_custom($list['MONTO_CUOTA']);
            $new_list['SALDO_AL_VENCIMIENTO'] = money_format_custom($list['SALDO_AL_VENCIMIENTO']);
            $rtn[] = $new_list;
        }
        return $rtn;
    }

}
