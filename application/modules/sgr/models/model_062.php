<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_062 extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('sgr/tools');

        $this->anexo = '062';
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
         * @example .... 

         * */
        $defdna = array(
            1 => 'CUIT', //CUIT
            2 => 'ANIO_MES', //ANIO_MES
            3 => 'FACTURACION', //FACTURACION
            4 => 'EMPLEADOS', //EMPLEADOS
            5 => 'TIPO_ORIGEN', //TIPO_ORIGEN
        );


        $insertarr = array();
        foreach ($defdna as $key => $value) {
            $insertarr[$value] = $parameter[$key];
            $insertarr["CUIT"] = (string) $insertarr["CUIT"];
            /* FLOAT */
            $insertarr["FACTURACION"] = (float) $insertarr["FACTURACION"];
            /* INT */
            $insertarr['EMPLEADOS'] = (int) $insertarr['EMPLEADOS'];
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
        $get_period = $this->sgr_model->get_current_period_info($this->anexo,$period);
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
         if (!isset($this->session->userdata['rectify']))
            exit();
        
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

    function get_anexo_data_tmp($anexo, $parameter) {

        $rtn = array();
        $container = 'container.sgr_anexo_' . $anexo;
        $fields = array('CUIT',
            'EMPLEADOS', 'filename', 'period', 'sgr_id', 'origin');
        $query = array("filename" => $parameter);
        $result = $this->mongo->sgr->$container->find($query, $fields);

        foreach ($result as $list) {
            $rtn[] = $list;
        }

        return $rtn;
    }

    function get_anexo_info($anexo, $parameter) {


        $headerArr = array("C.U.I.T",
            "Apellido y Nombre o Razón Social",
            "Fecha de Cierre del ejercicio<br> (Mes/Año)",
            "Cantidad de Empleados al último día del ejercicio",
            "Ingresos",
            "Origen de los Datos"
        );
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
            $this->load->model('app');
            $this->load->model('padfyj_model');

            $parner = $this->padfyj_model->search_name($list['CUIT']);
            list($year, $month) = explode("/", $list['ANIO_MES']);

            $new_list = array();
            $new_list['col1'] = $list['CUIT'];
            $new_list['col2'] = $parner;
            $new_list['col3'] = $month . "/" . $year;
            $new_list['col4'] = $list['EMPLEADOS'];
            $new_list['col5'] = $list['FACTURACION'];
            $new_list['col6'] = $list['TIPO_ORIGEN'];
            $rtn[] = $new_list;
        }
        return $rtn;
    }

    function get_partner_left($cuit) {
        $anexo = $this->anexo;
        $token = $this->idu;
        $container = 'container.sgr_anexo_' . $anexo;
        $period_value = $this->session->userdata['period'];

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active($anexo);
        
        $return_result = array();
        foreach ($result as $list) {
            $new_query = array(
                'filename' => $list['filename'],
                'CUIT' => $cuit
            );
            $new_result = $this->mongo->sgr->$container->find($new_query);                        
            foreach ($new_result as $each)                 
                $return_result[] = $each['EMPLEADOS'];
        }        
        
        return array_sum($return_result);
        
    }

}
