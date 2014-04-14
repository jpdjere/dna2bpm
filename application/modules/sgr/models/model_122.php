<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_122 extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('sgr/tools');

        $this->anexo = '122';
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
         * @example .... NRO_GARANTIA	NUMERO_CUOTA_CUYO_VENC_MODIFICA	FECHA_VENC_CUOTA	FECHA_VENC_CUOTA_NUEVA	MONTO_CUOTA	SALDO_AL_VENCIMIENTO


         * */
        $defdna = array(
            1 => 'NRO_GARANTIA', //NRO_GARANTIA
            2 => 'NUMERO_CUOTA_CUYO_VENC_MODIFICA', //NUMERO_CUOTA_CUYO_VENC_MODIFICA
            3 => 'FECHA_VENC_CUOTA', //FECHA_VENC_CUOTA
            4 => 'FECHA_VENC_CUOTA_NUEVA', //FECHA_VENC_CUOTA_NUEVA
            5 => 'MONTO_CUOTA', //MONTO_CUOTA
            6 => 'SALDO_AL_VENCIMIENTO', //SALDO_AL_VENCIMIENTO
        );


        $insertarr = array();
        foreach ($defdna as $key => $value) {
            $insertarr[$value] = $parameter[$key];
            /* STRING */
            $insertarr['NRO_GARANTIA'] = (string) $insertarr['NRO_GARANTIA'];

            /* INT & FLOAT */
            $insertarr['NUMERO_CUOTA_CUYO_VENC_MODIFICA'] = (int) $insertarr['NUMERO_CUOTA_CUYO_VENC_MODIFICA'];

            $insertarr['MONTO_CUOTA'] = (float) $insertarr['MONTO_CUOTA'];
            $insertarr['SALDO_AL_VENCIMIENTO'] = (float) $insertarr['SALDO_AL_VENCIMIENTO'];
        }
        return $insertarr;
    }

    function save($parameter) {
        $period = $this->session->userdata['period'];
        $container = 'container.sgr_anexo_' . $this->anexo;

        $parameter['FECHA_VENC_CUOTA'] = new MongoDate(strtotime(translate_for_mongo($parameter['FECHA_VENC_CUOTA'])));
        $parameter['FECHA_VENC_CUOTA_NUEVA'] = new MongoDate(strtotime(translate_for_mongo($parameter['FECHA_VENC_CUOTA_NUEVA'])));

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
        $tmpl = array(
            'data' => '<tr><td align="center" rowspan="2">N° de<br>Garntía</td>
                                <td align="center" rowspan="2">N° de Cuota<br>cuyo vencimiento se<br>modifica</td>
                                <td align="center" colspan="2">Del Participe/Beneficiario </td>
                                <td align="center" rowspan="2">Fecha de Origen de la Garantía</td>                                
                                <td align="center" colspan="3">De la Cuota cuyo vencimiento se modifica</td>
                                <td align="center" rowspan="2">Saldo al Vencimiento</td> 
    <tr>
        <td>Nombre o Razón Social</td>
        <td>C.U.I.T</td>
        <td>Fecha de Vencimiento Original</td>
        <td>Fecha de Efectiva Cancelación</td>
        <td>Monto</td>      
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
        <td>9</td>
    </tr> ',
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

    function get_anexo_data($anexo, $parameter, $xls = false) {
        header('Content-type: text/html; charset=UTF-8');
        $rtn = array();
        $container = 'container.sgr_anexo_' . $anexo;
        $query = array("filename" => $parameter);
        $result = $this->mongo->sgr->$container->find($query);

        foreach ($result as $list) {
            /* Vars */
            $cuit = str_replace("-", "", $list['CUIT']);
            $this->load->model('padfyj_model');
            $model_12 = 'model_12';
            $this->load->Model($model_12);



            $get_movement_data = $this->$model_12->get_order_number_print($list['NRO_GARANTIA'], $list['period']);

            foreach ($get_movement_data as $warranty) {
                $cuit = $warranty[5349];
                $brand_name = $this->padfyj_model->search_name($warranty[5349]);
                $origen = $warranty[5215];
            }



            $new_list = array();
            $new_list['col1'] = $list['NRO_GARANTIA'];
            $new_list['col2'] = $list['NUMERO_CUOTA_CUYO_VENC_MODIFICA'];
            $new_list['col3'] = $brand_name;
            $new_list['col4'] = $cuit;
            $new_list['col5'] = $origen;
            $new_list['col6'] = mongodate_to_print($list['FECHA_VENC_CUOTA']);
            $new_list['col7'] = mongodate_to_print($list['FECHA_VENC_CUOTA_NUEVA']);
            $new_list['col8'] = money_format_custom($list['MONTO_CUOTA']);
            $new_list['col9'] = money_format_custom($list['SALDO_AL_VENCIMIENTO']);

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
        $col10 = array();

        $container = 'container.sgr_anexo_' . $anexo;
        $query = array("filename" => $parameter);
        $result = $this->mongo->sgr->$container->find($query);
        $new_list = array();
        foreach ($result as $list) {

            $col5[] = (float) ($list['CAIDA']);
            $col6[] = (float) ($list['RECUPERO']);
            $col7[] = (float) ($list['INCOBRABLES_PERIODO']);
            $col8[] = (float) ($list['GASTOS_EFECTUADOS_PERIODO']);
            $col9[] = (float) ($list['RECUPERO_GASTOS_PERIODO']);
            $col10[] = (float) ($list['GASTOS_INCOBRABLES_PERIODO']);
        }


        $new_list = array();

        $new_list['col1'] = "<strong>TOTALES</strong>";
        $new_list['col2'] = "-";
        $new_list['col3'] = "-";
        $new_list['col4'] = "-";
        $new_list['col5'] = money_format_custom($list['CAIDA']);
        $new_list['col6'] = money_format_custom(array_sum($col6));
        $new_list['col7'] = money_format_custom(array_sum($col7));
        $new_list['col8'] = money_format_custom(array_sum($col8));
        $new_list['col9'] = money_format_custom(array_sum($col9));
        $new_list['col10'] = money_format_custom(array_sum($col10));
        $rtn[] = $new_list;


        return $rtn;
    }

}
