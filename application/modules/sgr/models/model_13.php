<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_13 extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('sgr/tools');

        $this->anexo = '13';
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

        /* FILTER NUMBERS/STRINGS */
        $int_values = array_filter($parameter, 'is_int');
        $float_values = array_filter($parameter, 'is_float');
        $numbers_values = array_merge($int_values, $float_values);

        /* FIX INFORMATION */
        $parameter = array_map('trim', $parameter);
        $parameter = array_map('addSlashes', $parameter);

        /* FIX DATE */
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

    function get_anexo_info($anexo, $parameter, $xls = false) {
        $tmpl = array('data' => ' <tr><td rowspan="2" align="center">Tipo de Garantía</td>
        <td colspan="5" align="center">Saldo según antigüedad</td>
        <td rowspan="2" align="center">Valor de las contragarantías</td>
    </tr>
    <tr>
        <td align="center">Menor de 90<br>días</td>
        <td align="center">Menor de 180<br>días</td>
        <td align="center">Menor de 365<br>días</td>
        <td align="center">Mayor de 365<br>días</td>
        <td align="center">Totales</td>
    </tr>
    <tr>
        <td>1</td>
        <td>2</td>
        <td>3</td>
        <td>4</td>
        <td>5</td>
        <td>6</td>
        <td>7</td>
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
            
            $new_list = array();
            $sum_totales = array_sum(array($list['MENOR_90_DIAS'], $list['MENOR_180_DIAS'], $list['MENOR_365_DIAS'], $list['MAYOR_365_DIAS']));
            $new_list['col1'] = $list['TIPO_DE_GARANTIA'];
            $new_list['col2'] = money_format_custom($list['MENOR_90_DIAS']);
            $new_list['col3'] = money_format_custom($list['MENOR_180_DIAS']);
            $new_list['col4'] = money_format_custom($list['MENOR_365_DIAS']);
            $new_list['col5'] = money_format_custom($list['MAYOR_365_DIAS']);
            $new_list['col6'] = money_format_custom($sum_totales);
            $new_list['col7'] = money_format_custom($list['VALOR_CONTRAGARANTIAS']);
            $rtn[] = $new_list;
        }
        return $rtn;
    }

    function get_anexo_data_clean($anexo, $parameter, $xls = false) {

        $rtn = array();
        $col2 = array();
        $col3 = array();
        $col4 = array();
        $col5 = array();
        $col6 = array();
        $col7 = array();


        $container = 'container.sgr_anexo_' . $anexo;
        $query = array("filename" => $parameter);
        $result = $this->mongo->sgr->$container->find($query);
        $new_list = array();
        foreach ($result as $list) {
            $sum_totales = array_sum(array($list['MENOR_90_DIAS'], $list['MENOR_180_DIAS'], $list['MENOR_365_DIAS'], $list['MAYOR_365_DIAS']));
            $col2[] = (float) ($list['MENOR_90_DIAS']);
            $col3[] = (float) ($list['MENOR_180_DIAS']);
            $col4[] = (float) ($list['MENOR_365_DIAS']);
            $col5[] = (float) ($list['MAYOR_365_DIAS']);
            $col6[] = (float) ($sum_totales);
            $col5[] = (float) ($list['VALOR_CONTRAGARANTIAS']);
        }


        $new_list = array();

        $new_list['col1'] = "<strong>TOTALES</strong>";       
        $new_list['col2'] = money_format_custom(array_sum($col2));
        $new_list['col3'] = money_format_custom(array_sum($col3));
        $new_list['col4'] = money_format_custom(array_sum($col4));
        $new_list['col5'] = money_format_custom(array_sum($col5));
        $new_list['col6'] = money_format_custom(array_sum($col6));
        $new_list['col7'] = money_format_custom(array_sum($col7));

        $rtn[] = $new_list;


        return $rtn;
    }

}
