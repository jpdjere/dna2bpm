<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_12 extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('sgr/tools');

        $this->anexo = '12';
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
         * @example NRO	CUIT_PARTICIPE	ORIGEN	TIPO	IMPORTE	MONEDA	LIBRADOR_NOMBRE	LIBRADOR_CUIT	NRO_OPERACION_BOLSA	ACREEDOR	CUIT_ACREEDOR	IMPORTE_CRED_GARANT	MONEDA_CRED_GARANT	TASA	PUNTOS_ADIC_CRED_GARANT	PLAZO	GRACIA	PERIODICIDAD	SISTEMA	DESTINO_CREDITO
         * */
        $defdna = array(
            1 => 5214, //"Nro",
            2 => 5348, //"Participe",
            3 => 5349, //"Cuit_participe",
            4 => 5215, //"Origen",
            5 => 5216, //"Tipo",
            6 => 5217, //"Ponderacion",
            7 => 5218, //"Importe",
            8 => 5219, //"Moneda",
            9 => 5725, //"Librador_nombre",
            10 => 5726, //"Librador_cuit",
            11 => 5727, //"Nro_operacion_bolsa",
            12 => 5350, //"Acreedor",
            13 => 5351, //"Cuit_acreedor",
            14 => 5221, //"Importe_Cred_Garant",
            15 => 5758, //"Moneda_Cred_Garant",
            16 => 5222, //"Tasa",
            17 => 5223, //"Puntos_adic_Cred_Garantizado",
            18 => 5224, //"Plazo",  
            19 => 5225, //"Gracia",
            20 => 5226, //"Period.",
            21 => 5227, //"if(strtoupper($insertarr[5227])",
            22 => 5228, //"Tipo_Contragarantia",
            23 => 5229   //"Valor_Contragarantia"
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
        list($arr['Y'], $arr['m'], $arr['d']) = explode("-", strftime("%Y-%m-%d", mktime(0, 0, 0, 1, -1 + $parameter[5215], 1900)));
        $parameter[5215] = $arr;        


        if (strtoupper(trim($insertarr[5219])) == "PESOS ARGENTINOS")
            $insertarr[5219] = "1";
        if (strtoupper(trim($insertarr[5219])) == "DOLARES AMERICANOS")
            $insertarr[5219] = "2";

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
        $headerArr = array("NRO","CUIT_PARTICIPE","ORIGEN","TIPO","IMPORTE","MONEDA","LIBRADOR_NOMBRE","LIBRADOR_CUIT","NRO_OPERACION_BOLSA","ACREEDOR","CUIT_ACREEDOR","IMPORTE_CRED_GARANT","MONEDA_CRED_GARANT","TASA","PUNTOS_ADIC_CRED_GARANT","PLAZO","GRACIA","PERIODICIDAD","SISTEMA","DESTINO_CREDITO");
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
            var_dump($list);
            /* Vars */
            $this->load->model('padfyj_model');
            $brand_name = $this->padfyj_model->search_name($list['5248']);
            $brand_name = ($brand_name) ? $brand_name : $list['1693'];

            $this->load->model('app');
            
            $new_list = array(); 				
            $new_list['NRO'] = $list[5214];
            $new_list['CUIT_PARTICIPE'] = $list[5349];
            $new_list['ORIGEN'] = $list[5215];
            $new_list['TIPO'] = $list[5216];
            $new_list['IMPORTE'] = $list[5218];
            $new_list['MONEDA'] = $list[5219];
            $new_list['LIBRADOR_NOMBRE'] = $list['LIBRADOR_NOMBRE'];
            $new_list['LIBRADOR_CUIT'] = $list[5726];
            $new_list['NRO_OPERACION_BOLSA'] = $list[5727];
            $new_list['ACREEDOR'] = $list['ACREEDOR'];
            $new_list['CUIT_ACREEDOR'] = $list[5351];
            $new_list['IMPORTE_CRED_GARANT'] = $list[5221];
            $new_list['MONEDA_CRED_GARANT'] = $list[5758];
            $new_list['TASA'] = $list[5222];
            $new_list['PUNTOS_ADIC_CRED_GARANT'] = $list[5223];
            $new_list['PLAZO'] = $list[5224];
            $new_list['GRACIA'] = $list[5225];
            $new_list['PERIODICIDAD'] = $list[5226];
            $new_list['SISTEMA'] = $list['SISTEMA'];
            $new_list['DESTINO_CREDITO'] = $list['DESTINO_CREDITO'];
            $rtn[] = $new_list;
        }
        return $rtn;
    }

}
