<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_121 extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('sgr/tools');

        $this->anexo = '121';
        $this->idu = (float) $this->session->userdata('iduser');
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
         * @example .... "NRO_ORDEN","NRO_CUOTA","VENCIMIENTO","CUOTA_GTA_PESOS","CUOTA_MENOR_PESOS"

         * */
        $defdna = array(
            1 => 'NRO_ORDEN', //NRO_ORDEN
            2 => 'NRO_CUOTA', //NRO_CUOTA
            3 => 'VENCIMIENTO', //VENCIMIENTO
            4 => 'CUOTA_GTA_PESOS', //CUOTA_GTA_PESOS
            5 => 'CUOTA_MENOR_PESOS', //CUOTA_MENOR_PESOS
        );


        $insertarr = array();
        foreach ($defdna as $key => $value) {
            $insertarr[$value] = $parameter[$key];
            /* STRING */
            $insertarr['NRO_ORDEN'] = (string) $insertarr['NRO_ORDEN']; //Nro orden

            /* INT & FLOAT */
            $insertarr['NRO_CUOTA'] = (int) $insertarr['NRO_CUOTA'];

            $insertarr['CUOTA_GTA_PESOS'] = (float) $insertarr['CUOTA_GTA_PESOS'];
            $insertarr['CUOTA_MENOR_PESOS'] = (float) $insertarr['CUOTA_MENOR_PESOS'];
        }
        return $insertarr;
    }

    function save($parameter) {
        $period = $this->session->userdata['period'];
        $container = 'container.sgr_anexo_' . $this->anexo;

        $parameter['VENCIMIENTO'] = new MongoDate(strtotime(translate_for_mongo($parameter['VENCIMIENTO'])));

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

    function save_($parameter) {
        $period = $this->session->userdata['period'];
        $container = 'container.sgr_anexo_' . $this->anexo;

        /* FILTER NUMBERS/STRINGS */
        $int_values = array_filter($parameter, 'is_int');
        $float_values = array_filter($parameter, 'is_float');
        $numbers_values = array_merge($int_values, $float_values);

        /* FIX INFORMATION */
        $parameter = array_map('trim', $parameter);
        $parameter = array_map('addSlashes', $parameter);



        $parameter['period'] = $period;
        $parameter['origen'] = "2013";
        $id = $this->app->genid_sgr($container);

        /* MERGE CAST */
        $parameter = array_merge($parameter, $numbers_values);
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

    function get_anexo_info($anexo, $parameter) {
        $tmpl = array(
            'data' => '<tr><td colspan="2" align="center">Garantía.</td>
                                <td colspan="2" align="center">Del Part&iacute;cipe / Beneficiario</td>
                                <td colspan="3" align="center">Información sobre la Amortización</td>                                
                            </tr>
                            <tr> </tr>
                            <tr> </tr>
                            <tr>
                            <td>N° de Orden de<br/>la Garantía<br/>Otorgada</td>
                            <td>N° de<br/>Cuota</td>
                            <td>C.U.I.T.</td>
                            <td>Apellido y Nombre o Razón<br/>Social</td>
                            <td>Fecha de<br/>Vencimiento<br/>de la Cuota</td>
                            <td>Monto de la<br/>Cuota de la<br/>Garantía</td>
                            <td>Monto de la<br/>Cuota del<br/>Importe Menor</td>
                            </tr>
                            <tr>
                                <th>1</th>
                                <th>2</th>
                                <th>3</th>
                                <th>4</th>
                                <th>5</th>
                                <th>6</th>
                                <th>7</th>                                               
                            </tr>',
        );


        $data = array($tmpl);
        $anexoValues = $this->get_anexo_data($anexo, $parameter);
        foreach ($anexoValues as $values) {
            $data[] = array_values($values);
        }
        $this->load->library('table_custom');
        $newTable = $this->table_custom->generate($data);

        return $newTable;
    }

    function get_anexo_data($anexo, $parameter) {


        $this->load->model('padfyj_model');
        $model_12 = 'model_12';
        $this->load->Model($model_12);
        
        
        header('Content-type: text/html; charset=UTF-8');
        $rtn = array();
        $container = 'container.sgr_anexo_' . $anexo;
        $query = array("filename" => $parameter);
        $result = $this->mongo->sgr->$container->find($query);
        foreach ($result as $list) { /* Vars */
            $new_list = array();
            
            $get_movement_data = $this->$model_12->get_order_number_print($list['NRO_ORDEN'], $list['period']);

            foreach ($get_movement_data as $warranty) {
                $cuit = $warranty[5349];
                $brand_name = $this->padfyj_model->search_name($warranty[5349]);
            }
            
            $new_list['NRO_ORDEN'] = $list['NRO_ORDEN'];
            $new_list['NRO_CUOTA'] = $list['NRO_CUOTA'];
            $new_list['CUIT'] = $cuit;
            $new_list['RAZON_SOCIAL'] = $brand_name;
            $new_list['VENCIMIENTO'] = mongodate_to_print($list['VENCIMIENTO']);
            $new_list['CUOTA_GTA_PESOS'] = money_format_custom($list['CUOTA_GTA_PESOS']);
            $new_list['CUOTA_MENOR_PESOS'] = money_format_custom($list['CUOTA_MENOR_PESOS']);
            $rtn[] = $new_list;
        }
        return $rtn;
    }

}
