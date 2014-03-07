<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_141 extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('sgr/tools');

        $this->anexo = '141';
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
         * @example .... "CUIT_PARTICIPE","CANT_GTIAS_VIGENTES","HIPOTECARIAS","PRENDARIAS","FIANZA"
         * ,"OTRAS","REAFIANZA","MORA_EN_DIAS","CLASIFICACION_DEUDOR"
         * */
        $defdna = array(
            1 => 'CUIT_PARTICIPE',
            2 => 'CANT_GTIAS_VIGENTES',
            3 => 'HIPOTECARIAS',
            4 => 'PRENDARIAS',
            5 => 'FIANZA',
            6 => 'OTRAS',
            7 => 'REAFIANZA',
            8 => 'MORA_EN_DIAS',
            9 => 'CLASIFICACION_DEUDOR'
        );


        $insertarr = array();
        foreach ($defdna as $key => $value) {
            $insertarr[$value] = $parameter[$key];

            /* STRING */
            $insertarr["CUIT_PARTICIPE"] = (string) $insertarr["CUIT_PARTICIPE"]; 
            /* INTEGERS & FLOAT */
            $insertarr["CANT_GTIAS_VIGENTES"] = (int) $insertarr["CANT_GTIAS_VIGENTES"];
            $insertarr["HIPOTECARIAS"] = (float) $insertarr["HIPOTECARIAS"];
            $insertarr["PRENDARIAS"] = (float) $insertarr["PRENDARIAS"];
            $insertarr["FIANZA"] = (float) $insertarr["FIANZA"];
            $insertarr["OTRAS"] = (float) $insertarr["OTRAS"];
            $insertarr["REAFIANZA"] = (float) $insertarr["REAFIANZA"];
            $insertarr["MORA_EN_DIAS"] = (int) $insertarr["MORA_EN_DIAS"];
            $insertarr["CLASIFICACION_DEUDOR"] = (int) $insertarr["CLASIFICACION_DEUDOR"];
        }
        return $insertarr;
    }

    function save($parameter) {
        $period = $this->session->userdata['period'];
        $container = 'container.sgr_anexo_' . $this->anexo;

        $parameter['FECHA_MOVIMIENTO'] = new MongoDate(strtotime(translate_for_mongo($parameter['FECHA_MOVIMIENTO'])));

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
        $parameter['period_date'] = translate_period_date($period);
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
        $parameter = array(
            'status' => 'rectificado',
            'rectified_on' => date('Y-m-d h:i:s'),
            'others' => $this->session->userdata['others'],
            'reason' => $this->session->userdata['rectify']
        );
        $rs = $this->mongo->sgr->$container->update($query, array('$set' => $parameter), $options);
        return $rs['err'];
    }

    function get_anexo_info($anexo, $parameter) {


        $headerArr = array("CUIT_PARTICIPE","CANT_GTIAS_VIGENTES","HIPOTECARIAS","PRENDARIAS","FIANZA","OTRAS","REAFIANZA","MORA_EN_DIAS","CLASIFICACION_DEUDOR");
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
            $cuit = str_replace("-", "", $list['CUIT_PARTICIPE']);
            $this->load->model('padfyj_model');
            $brand_name = $this->padfyj_model->search_name($cuit);
            $brand_name = ($brand_name) ? $brand_name : strtoupper($list['RAZON_SOCIAL']);

            $new_list = array();
            $new_list['CANT_GTIAS_VIGENTES'] = $list['CANT_GTIAS_VIGENTES'];
            $new_list['HIPOTECARIAS'] = money_format_custom($list['HIPOTECARIAS']);
            $new_list['PRENDARIAS'] = money_format_custom($list['PRENDARIAS']);
            $new_list['FIANZA'] = money_format_custom($list['FIANZA']);
            $new_list['OTRAS'] = money_format_custom($list['OTRAS']);
            $new_list['REAFIANZA'] = money_format_custom($list['REAFIANZA']);
            $new_list['MORA_EN_DIAS'] = $list['MORA_EN_DIAS'];
            $new_list['CLASIFICACION_DEUDOR'] = $list['CLASIFICACION_DEUDOR'];
            $rtn[] = $new_list;
        }
        return $rtn;
    }

}
