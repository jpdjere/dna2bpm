<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_15 extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('sgr/tools');

        $this->anexo = '15';
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
         * @example
         * INCISO_ART_25	
         * DESCRIPCION	
         * IDENTIFICACION	
         * EMISOR	
         * CUIT_EMISOR	
         * ENTIDAD_DESPOSITARIA	
         * CUIT_DEPOSITARIO	
         * MONEDA	
         * MONTO
         * */
        $defdna = array(
            1 => 'INCISO_ART_25',
            2 => 'DESCRIPCION',
            3 => 'IDENTIFICACION',
            4 => 'EMISOR',
            5 => 'CUIT_EMISOR',
            6 => 'ENTIDAD_DESPOSITARIA',
            7 => 'CUIT_DEPOSITARIO',
            8 => 'MONEDA',
            9 => 'MONTO'
        );


        $insertarr = array();
        foreach ($defdna as $key => $value) {
            $insertarr[$value] = $parameter[$key];
            /* STRING */
            $insertarr['INCISO_ART_25'] = (string) $insertarr['INCISO_ART_25'];
            $insertarr['CUIT_EMISOR'] = (string) $insertarr['CUIT_EMISOR'];
            $insertarr['CUIT_DEPOSITARIO'] = (string) $insertarr['CUIT_DEPOSITARIO'];

            /* FLOAT */
            $insertarr['MONTO'] = (float) $insertarr['MONTO'];


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
        $get_period = $this->sgr_model->get_current_period_info($this->anexo, $period);
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
            'data' => '<tr>
                    <td align="center" colspan="3">Fondo de riesgo disponible</td>
                    <td align="center" rowspan="3">Entidad Emisora</td>
                    <td align="center" rowspan="3">CUIT Entidad Emisora</td>
                    <td align="center" rowspan="3">Entidad Depositaria</td>
                    <td align="center" rowspan="3">CUIT Entidad Depositaria</td>
                    <td align="center" rowspan="3">Moneda nominativa del Activo</td>
                    <td align="center" rowspan="3">Monto (En Pesos)</td>
                    <td align="center" rowspan="3">Proporción en el Fondo de Riesgo (%)</td>
                </tr>
                <tr>
                    <td colspan="3">1. Activos Artículo 25</td></tr>
                    <tr>
                        <td>Inciso del Art. 25</td>
                        <td>Descripción</td>
                        <td>Identificación</td>
                    </tr>
                            <tr>
                                <th>1</th>
                                <th>2</th>
                                <th>3</th>
                                <th>4</th>
                                <th>5</th>
                                <th>6</th>
                                <th>7</th>
                                <th>8</th>
                                <th>9</th>
                                <th>10</th>                                              
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

    function get_total($anexo, $parameter) {

        $rtn = array();
        $col9 = array();

        $container = 'container.sgr_anexo_' . $anexo;
        $query = array("filename" => $parameter);
        $result = $this->mongo->sgr->$container->find($query);
        foreach ($result as $list) {

            $col9[] = (float) ($list['MONTO']);
        }

        return array_sum($col9);
    }

    function get_anexo_data($anexo, $parameter) {
        header('Content-type: text/html; charset=UTF-8');
        $rtn = array();
        $container = 'container.sgr_anexo_' . $anexo;
        $query = array("filename" => $parameter);
        $result = $this->mongo->sgr->$container->find($query)->sort(array('INCISO_ART_25' => 1));

        foreach ($result as $list) {
            /* Vars 								
             */
            $this->load->model('padfyj_model');
            $transmitter_name = $this->padfyj_model->search_name($list['CUIT_EMISOR']);
            $transmitter_name = ($transmitter_name) ? $transmitter_name : strtoupper($list['EMISOR']);

            $depositories_name = $this->sgr_model->get_depositories($list['CUIT_DEPOSITARIO']);
            $depositories_name = ($depositories_name) ? $depositories_name['nombre'] : strtoupper($list['ENTIDAD_DESPOSITARIA']);

            $this->load->model('app');
            $currency = $this->app->get_ops(549);

            $total = $this->get_total($anexo, $parameter);
            $percent = ($list['MONTO'] * 100) / $total;

            $new_list = array();
            $new_list['INCISO_ART_25'] = $list['INCISO_ART_25'];
            $new_list['DESCRIPCION'] = $list['DESCRIPCION'];
            $new_list['IDENTIFICACION'] = $list['IDENTIFICACION'];
            $new_list['EMISOR'] = $transmitter_name;
            $new_list['CUIT_EMISOR'] = $list['CUIT_EMISOR'];
            $new_list['ENTIDAD_DESPOSITARIA'] = $depositories_name;
            $new_list['CUIT_DEPOSITARIO'] = $list['CUIT_DEPOSITARIO'];
            $new_list['MONEDA'] = $currency[$list['MONEDA']];
            $new_list['MONTO'] = money_format_custom($list['MONTO']);
            $new_list['col10'] = percent_format_custom($percent);
            $rtn[] = $new_list;
        }
        return $rtn;
    }

    function get_anexo_ddjj($period, $subsection) {

        $rtn = array();
        $new_list = array();
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;

        $get_result = $this->sgr_model->get_current_period_info($anexo, $period);

        $query = array("filename" => $get_result['filename'], 'INCISO_ART_25' => $subsection);
        $result = $this->mongo->sgr->$container->find($query)->sort(array('INCISO_ART_25' => 1));
        $new_list = array();

        $pesos_arr = array();
        $dolar_arr = array();

        foreach ($result as $list) {


            if ($list['MONEDA'] == 1)
                $pesos_arr[] = $list['MONTO'];
            else
                $dolar_arr[] = $list['MONTO'];
        }



        $sum_pesos = array_sum($pesos_arr);
        $sum_dolar = array_sum($dolar_arr);
        $sum_total = array_sum(array($sum_pesos, $sum_dolar));
        $total = $this->get_total($anexo, $get_result['filename']);
        if ($total)
            $percent = ($sum_total * 100) / $total;

        $new_list['col1'] = $sum_pesos;
        $new_list['col2'] = $sum_dolar;
        $new_list['col3'] = $sum_total;
        $new_list['col4'] = $percent;

        $rtn[] = $new_list;



        return $rtn;
    }

    function get_anexo_data_clean_ddjj($period) {

        $col9 = array();

        $rtn = array();
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;

        $get_result = $this->sgr_model->get_current_period_info($anexo, $period);

        $query = array("filename" => $get_result['filename']);
        $result = $this->mongo->sgr->$container->find($query);
        $new_list = array();
        foreach ($result as $list) {
            $col9[] = (float) ($list['MONTO']);
        }

        $new_list = array();
        $new_list['col1'] = "<strong>TOTAL</strong>";
        $new_list['col2'] = "-";
        $new_list['col3'] = "-";
        $new_list['col4'] = money_format_custom(array_sum($col9));
        $new_list['col5'] = percent_format_custom(100);
        $rtn[] = $new_list;

        return $rtn;
    }

    function get_anexo_data_clean($anexo, $parameter, $xls = false) {

        $rtn = array();
        $col9 = array();

        $container = 'container.sgr_anexo_' . $anexo;
        $query = array("filename" => $parameter);
        $result = $this->mongo->sgr->$container->find($query);
        foreach ($result as $list) {
            $col9[] = (float) ($list['MONTO']);
        }

        $new_list = array();
        $new_list['col1'] = "<strong>TOTAL</strong>";
        $new_list['col2'] = "-";
        $new_list['col3'] = "-";
        $new_list['col4'] = "-";
        $new_list['col5'] = "-";
        $new_list['col6'] = "-";
        $new_list['col7'] = "-";
        $new_list['col8'] = "-";
        $new_list['col9'] = money_format_custom(array_sum($col9));
        $new_list['col10'] = percent_format_custom(100);
        $rtn[] = $new_list;

        return $rtn;
    }

}
