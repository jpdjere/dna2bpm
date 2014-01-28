<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_121 extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('sgr/tools');

        $this->anexo = '121';
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
        }
        return $insertarr;
    }

    function save($parameter) {
        $period = $this->session->userdata['period'];
        $container = 'container.sgr_anexo_' . $this->anexo;

        $parameter = array_map('trim', $parameter);
        $parameter = array_map('addSlashes', $parameter);

        /* FIX DATE */
        $parameter['VENCIMIENTO'] = strftime("%Y-%m-%d", mktime(0, 0, 0, 1, -1 + $parameter['VENCIMIENTO'], 1900));

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

        $headerArr = array("NRO ORDEN", "NRO CUOTA", "VENCIMIENTO", "CUOTA GTA PESOS", "CUOTA MENOR PESOS");

        $tmpl = array(
            'data' => '<tr>
                                <td colspan="2" align="center">Garantía</td>
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

        /* DRAW TABLE */
        $fix_table = '<thead>
<tr>
<th>';

        $data = array($tmpl);
        $anexoValues = $this->get_anexo_data($anexo, $parameter);
        foreach ($anexoValues as $values) {
            $data[] = array_values($values);
        }
        $this->load->library('table');
        $newTable = str_replace($fix_table, '<thead>', $this->table->generate($data));

        return $newTable;
    }

    

    function get_anexo_data($anexo, $parameter) {
        header('Content-type: text/html; charset=UTF-8');
        $rtn = array();
        $container = 'container.sgr_anexo_' . $anexo;
        $query = array("filename" => $parameter);
        $result = $this->mongo->sgr->$container->find($query);

        
        
        
        
        foreach ($result as $list) { /* Vars */
            $new_list = array();            
            
            $warranty_info = $this->sgr_model->get_warranty_data($list['NRO_ORDEN'], $list['period']);
            $this->load->model('padfyj_model');
            $participate = $this->padfyj_model->search_name($warranty_info[5349]);
            
            $new_list['NRO_ORDEN'] = $list['NRO_ORDEN'];
            $new_list['NRO_CUOTA'] = $list['NRO_CUOTA'];
            $new_list['CUIT'] = $warranty_info[5349];
            $new_list['RAZON_SOCIAL'] = $participate;
            $new_list['VENCIMIENTO'] = $list['VENCIMIENTO'];
            $new_list['CUOTA_GTA_PESOS'] = money_format_custom($list['CUOTA_GTA_PESOS']);
            $new_list['CUOTA_MENOR_PESOS'] = money_format_custom($list['CUOTA_MENOR_PESOS']);
            $rtn[] = $new_list;
        }
        return $rtn;
    }

}
