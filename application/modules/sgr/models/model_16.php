<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_16 extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('sgr/tools');

        $this->anexo = '16';
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
         * @example
         * PROMEDIO_SALDO_MENSUAL	
         * SALDO_PROMEDIO_GARANTIAS_VIGENTES	
         * SALDO_PROMEDIO_PONDERADO_GARANTIAS_VIGENTES_80_HASTA_FEB_2010	
         * SALDO_PROMEDIO_PONDERADO_GARANTIAS_VIGENTES_120_HASTA_FEB_2010	
         * SALDO_PROMEDIO_PONDERADO_GARANTIAS_VIGENTES_80_DESDE_FEB_2010	
         * SALDO_PROMEDIO_PONDERADO_GARANTIAS_VIGENTES_120_DESDE_FEB_2010	
         * SALDO_PROMEDIO_PONDERADO_GARANTIAS_VIGENTES_80_DESDE_ENE_2011	
         * SALDO_PROMEDIO_PONDERADO_GARANTIAS_VIGENTES_120_DESDE_ENE_2011	
         * SALDO_PROMEDIO_FDR_TOTAL_COMPUTABLE	
         * SALDO_PROMEDIO_FDR_CONTINGENTE
         * */
        $defdna = array(
            1 => 'PROMEDIO_SALDO_MENSUAL',
            2 => 'GARANTIAS_VIGENTES',
            3 => '80_HASTA_FEB_2010',
            4 => '120_HASTA_FEB_2010',
            5 => '80_DESDE_FEB_2010',
            6 => '120_DESDE_FEB_2010',
            7 => '80_DESDE_ENE_2011',
            8 => '120_DESDE_ENE_2011',
            9 => 'FDR_TOTAL_COMPUTABLE',
            10 => 'FDR_CONTINGENTE'
        );


        $insertarr = array();
        foreach ($defdna as $key => $value) {
            $insertarr[$value] = $parameter[$key];

            if (strtoupper(trim($insertarr["MONEDA"])) == "PESOS ARGENTINOS")
                $insertarr["MONEDA"] = "1";
            if (strtoupper(trim($insertarr["MONEDA"])) == "DOLARES AMERICANOS")
                $insertarr["MONEDA"] = "2";
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
        $status = 'rectificado';
        $parameter = array('status' => $status);
        $rs = $this->mongo->sgr->$container->update($query, array('$set' => $parameter), $options);
        return $rs['err'];
    }

    function get_anexo_info($anexo, $parameter) {


        $headerArr = array("PROMEDIO_SALDO_MENSUAL"
            ,"GARANTIAS_VIGENTES"
            ,"80_HASTA_FEB_2010"
            ,"120_HASTA_FEB_2010"
            ,"80_DESDE_FEB_2010"
            ,"120_DESDE_FEB_2010"
            ,"80_DESDE_ENE_2011"
            ,"120_DESDE_ENE_2011"
            ,"FDR_TOTAL_COMPUTABLE"
            ,"FDR_CONTINGENTE");
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
            /* Vars 								
             */

            $this->load->model('padfyj_model');
            $transmitter_name = $this->padfyj_model->search_name($list['CUIT_EMISOR']);
            $transmitter_name = ($transmitter_name) ? $transmitter_name : strtoupper($list['EMISOR']);

            $depositories_name = $this->sgr_model->get_depositories($list['CUIT_DEPOSITARIO']);
            $depositories_name = ($depositories_name) ? $depositories_name['nombre'] : strtoupper($list['ENTIDAD_DESPOSITARIA']);

            $this->load->model('app');
            
            
            $new_list = array();
            $new_list['PROMEDIO_SALDO_MENSUAL'] = $list['PROMEDIO_SALDO_MENSUAL'];            
            $new_list['GARANTIAS_VIGENTES'] = money_format_custom($list['GARANTIAS_VIGENTES']);
            $new_list['80_HASTA_FEB_2010'] = money_format_custom($list['80_HASTA_FEB_2010']);
            $new_list['120_HASTA_FEB_2010'] = money_format_custom($list['120_HASTA_FEB_2010']);
            $new_list['80_DESDE_FEB_2010'] = money_format_custom($list['80_DESDE_FEB_2010']);
            $new_list['120_DESDE_FEB_2010'] = money_format_custom($list['120_DESDE_FEB_2010']);
            $new_list['80_DESDE_ENE_2011'] = money_format_custom($list['80_DESDE_ENE_2011']);
            $new_list['120_DESDE_ENE_2011'] = money_format_custom($list['120_DESDE_ENE_2011']);
            $new_list['FDR_TOTAL_COMPUTABLE'] = money_format_custom($list['FDR_TOTAL_COMPUTABLE']);
            $new_list['FDR_CONTINGENTE'] = money_format_custom($list['FDR_CONTINGENTE']);
            $rtn[] = $new_list;
        }
        return $rtn;
    }

}
