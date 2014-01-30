<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_202 extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('sgr/tools');

        $this->anexo = '202';
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
         * NUMERO_DE_APORTE	
         * CONTINGENTE_PROPORCIONAL_ASIGNADO	
         * DEUDA_PROPORCIONAL_ASIGNADA	
         * RENDIMIENTO_ASIGNADO
         * */
        $defdna = array(
            1 => 'NUMERO_DE_APORTE',
            2 => 'CONTINGENTE_PROPORCIONAL_ASIGNADO',
            3 => 'DEUDA_PROPORCIONAL_ASIGNADA',
            4 => 'RENDIMIENTO_ASIGNADO'
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


        $headerArr = array(
            "N° DE APORTE",
            "APELLIDO Y NOMBRE<BR/>O RAZÓN SOCIAL",
            "CUIT",
            "SALDO DEL APORTE",
            "CONTINGENTE<BR/>PROPOCIONAL<BR/>ASIGNADO",
            "DEUDA<BR/>PROPOCIONAL<BR/>ASIGNADA",
            "SALDO DEL APORTE<BR/>DISPONIBLE",
            "RENDIMIENTO<BR/>ASIGNADO"
        );
        $numberArr = array(1, 2, 3, 4, 5, 6, 7, 8);

        $data = array($headerArr, $numberArr);
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
            /*
             * Vars 								
             */
            $this->load->model('padfyj_model');

            $this->load->model('app');

            $new_list = array();
            $new_list['NUMERO_DE_APORTE'] = 1; //$list['NUMERO_DE_APORTE'];
            $new_list['RAZON_SOCIAL'] = "2";
            $new_list['CUIT'] = "3";
            $new_list['SALDO_APORTE'] = "4";
            $new_list['CONTINGENTE_PROPORCIONAL_ASIGNADO'] = 5; //money_format_custom($list['CONTINGENTE_PROPORCIONAL_ASIGNADO']);
            $new_list['DEUDA_PROPORCIONAL_ASIGNADA'] = 6; //money_format_custom($list['DEUDA_PROPORCIONAL_ASIGNADA']);
            $new_list['SALDO_APORTE_DISPONIBLE'] = "7";
            $new_list['RENDIMIENTO_ASIGNADO'] = 8; //money_format_custom($list['RENDIMIENTO_ASIGNADO']);
            $rtn[] = $new_list;
        }
        return $rtn;
    }

}
