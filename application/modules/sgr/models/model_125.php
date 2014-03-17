<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_125 extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('sgr/tools');

        $this->anexo = '125';
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
         * @example .... CUIT_PART	CUIT_ACREEDOR	SLDO_FINANC	SLDO_COMER	SLDO_TEC
         * */
        $defdna = array(
            1 => 'CUIT_PART',
            2 => 'CUIT_ACREEDOR',
            3 => 'SLDO_FINANC',
            4 => 'SLDO_COMER',
            5 => 'SLDO_TEC'
        );


        $insertarr = array();
        foreach ($defdna as $key => $value) {
            $insertarr[$value] = $parameter[$key];
            /* STRING */
            $insertarr['CUIT_PART'] = (string) $insertarr['CUIT_PART'];
            $insertarr['CUIT_ACREEDOR'] = (string) $insertarr['CUIT_ACREEDOR'];
            /* FLOAT */
            $insertarr['SLDO_FINANC'] = (float) $insertarr['SLDO_FINANC'];
            $insertarr['SLDO_COMER'] = (float) $insertarr['SLDO_COMER'];
            $insertarr['SLDO_TEC'] = (float) $insertarr['SLDO_TEC'];
        }
        return $insertarr;
    }

    function save($parameter) {
        $period = $this->session->userdata['period'];
        $container = 'container.sgr_anexo_' . $this->anexo;

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

    function get_anexo_info($anexo, $parameter, $xls = false) {
        $tmpl = array(
            'data' => ' <tr>
        <td colspan="2" align="center">Socio Participe</td>
        <td colspan="2" align="center">Acreedor <br></td>
        <td colspan="4" align="center">Saldo de Garantías Vigentes<br></td>
    </tr>
    <tr>
        <td align="center">C.U.I.T.</td>
        <td align="center">Razón Social</td>
        <td align="center">C.U.I.T.</td>
        <td align="center">Razón Social</td>
        <td align="center">Financiera</td>
        <td align="center">Comerciales <br></td>
        <td align="center">Técnicas</td>
        <td align="center">Total</td>
    </tr>
    <tr>
        <td>1</td>
        <td>2</td>
        <td>3</td>
        <td>4</td>
        <td>5</td>
        <td>6</td>
        <td>7</td>
        <td>8</td>    
    </tr> ',
        );


        $data = array($tmpl);
        $anexoValues = $this->get_anexo_data($anexo, $parameter, $xls);
        $anexoValues2 = $this->get_anexo_data_clean($anexo, $parameter, $xls);
        $anexoValues = array_merge($anexoValues, $anexoValues2);
        foreach ($anexoValues as $values) {
            $data[] = array_values($values);
        }

        $this->load->library('table_custom');
        $newTable = $this->table_custom->generate($data);
        return $newTable;
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
            $brand_name_participate = $this->padfyj_model->search_name($list['CUIT_PART']);
            $brand_name_creditor = $this->padfyj_model->search_name($list['CUIT_ACREEDOR']);

            $total = array_sum(array($list['SLDO_FINANC'], $list['SLDO_COMER'], $list['SLDO_TEC']));

            $new_list = array();
            $new_list['col1'] = $list['CUIT_PART'];
            $new_list['col2'] = $brand_name_participate;
            $new_list['col3'] = $list['CUIT_ACREEDOR'];
            $new_list['col4'] = $brand_name_creditor;
            $new_list['col5'] = money_format_custom($list['SLDO_FINANC']);
            $new_list['col6'] = money_format_custom($list['SLDO_COMER']);
            $new_list['col7'] = money_format_custom($list['SLDO_TEC']);
            $new_list['col8'] = money_format_custom($total);
            $rtn[] = $new_list;
        }
        return $rtn;
    }

    function get_anexo_data_clean($anexo, $parameter, $xls = false) {

        $rtn = array();

        $col5 = array();
        $col6 = array();
        $col7 = array();
        $col8 = array();




        $container = 'container.sgr_anexo_' . $anexo;
        $query = array("filename" => $parameter);
        $result = $this->mongo->sgr->$container->find($query);
        $new_list = array();
        foreach ($result as $list) {
            $total = array_sum(array($list['SLDO_FINANC'], $list['SLDO_COMER'], $list['SLDO_TEC']));
            $col5[] = (float) ($list['SLDO_FINANC']);
            $col6[] = (float) ($list['SLDO_COMER']);
            $col7[] = (float) ($list['SLDO_TEC']);
            $col8[] = (float) ($total);
        }


        $new_list = array();

        $new_list['col1'] = "<strong>TOTALES</strong>";
        $new_list['col2'] = "-";
        $new_list['col3'] = "-";
        $new_list['col4'] = "-";
        $new_list['col5'] = money_format_custom(array_sum($col5));
        $new_list['col6'] = money_format_custom(array_sum($col6));
        $new_list['col7'] = money_format_custom(array_sum($col7));
        $new_list['col8'] = money_format_custom(array_sum($col8));

        $rtn[] = $new_list;


        return $rtn;
    }

}
