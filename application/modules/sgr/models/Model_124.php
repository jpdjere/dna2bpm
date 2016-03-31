<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_124 extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('sgr/tools');

        $this->anexo = '124';
        /* Additional SGR users */
        $this->load->model('sgr/sgr_model');
        $additional_users = $this->sgr_model->additional_users($this->session->userdata('iduser'));
        $this->idu = (isset($additional_users)) ? $additional_users['sgr_idu'] : $this->session->userdata('iduser');
        /* SWITCH TO SGR DB */
        $this->load->library('cimongo/Cimongo.php', '', 'sgr_db');
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
         * @example .... NRO_GARANTIA	FECHA_REAFIANZA	SALDO_VIGENTE	REAFIANZADO	RAZON_SOCIAL	CUIT
         * */
        $defdna = array(
            1 => 'NRO_GARANTIA', //NRO_GARANTIA
            2 => 'FECHA_REAFIANZA', //FECHA_REAFIANZA
            3 => 'SALDO_VIGENTE', //SALDO_VIGENTE
            4 => 'REAFIANZADO', //REAFIANZADO
            5 => 'RAZON_SOCIAL', //RAZON_SOCIAL
            6 => 'CUIT', //CUIT
        );


        $insertarr = array();
        foreach ($defdna as $key => $value) {
            $insertarr[$value] = $parameter[$key];
            /* STRING */
            $insertarr['NRO_GARANTIA'] = (string) $insertarr['NRO_GARANTIA']; //Nro orden
            $insertarr['CUIT'] = (string) $insertarr['CUIT'];

            /* FLOAT */
            $insertarr['SALDO_VIGENTE'] = (float) $insertarr['SALDO_VIGENTE'];
            $insertarr['REAFIANZADO'] = (float) $insertarr['REAFIANZADO'];
        }
        return $insertarr;
    }

    function save($parameter) {
        $period = $this->session->userdata['period'];
        $container = 'container.sgr_anexo_' . $this->anexo;

        /* FIX DATE */
        $parameter['FECHA_REAFIANZA'] = new MongoDate(strtotime(translate_for_mongo($parameter['FECHA_REAFIANZA'])));

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
        /* UPDATE */
        if (isset($get_period['status']))
            $this->update_period($get_period['id'], $get_period['status']);


        $result = $this->app->put_array_sgr($id, $container, $parameter);

        if (isset($result)) {
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

        $options = array('upsert' => true, 'w' => 1);
        $container = 'container.sgr_periodos';
        $query = array('id' => (float) $id);
        $parameter = array(
            'status' => 'rectificado',
            'rectified_on' => date('Y-m-d h:i:s'),
            'others' => $this->session->userdata['others'],
            'reason' => $this->session->userdata['rectify']
        );
        $rs = $this->mongowrapper->sgr->$container->update($query, array('$set' => $parameter), $options);
        return $rs['err'];
    }

    function get_anexo_info($anexo, $parameter) {
        /* HEADER TEMPLATE */
        $header_data = array();

        $header = $this->parser->parse('prints/anexo_' . $anexo . '_header', TRUE);
        $tmpl = array('data' => $header);

        $data = array($tmpl);

        $anexoValues = $this->get_anexo_data($anexo, $parameter);
        foreach ($anexoValues as $values) {
            $data[] = array_values($values);
        }
        $this->load->library('table_custom');
        $newTable = $this->table_custom->generate($data);

        return $newTable;
    }

    function get_warranty_qty($period) {

        $rtn = array();
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;

        $get_result = $this->sgr_model->get_current_period_info($anexo, $period);

        $query = array("filename" => $get_result['filename']);
        $result = $this->mongowrapper->sgr->$container->find($query);
        $new_list = array();
        foreach ($result as $list) {
            $rtn[] = $list["NRO_GARANTIA"];
        }


        return count(array_unique($rtn));
    }

    function get_warranty_amount($period) {

        $rtn = array();
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;

        $get_result = $this->sgr_model->get_current_period_info($anexo, $period);

        $query = array("filename" => $get_result['filename']);
        $result = $this->mongowrapper->sgr->$container->find($query);
        $new_list = array();
        foreach ($result as $list) {
            $rtn[] = $list['SALDO_VIGENTE'];
        }
        return array_sum($rtn);
    }

    function get_anexo_data($anexo, $parameter) {
        header('Content-type: text/html; charset=UTF-8');
        $rtn = array();
        $container = 'container.sgr_anexo_' . $anexo;
        $query = array("filename" => $parameter);
        $result = $this->mongowrapper->sgr->$container->find($query);

        foreach ($result as $list) {
            /* Vars */
            $cuit = str_replace("-", "", $list['CUIT']);
            $this->load->model('padfyj_model');
            $model_12 = 'model_12';
            $this->load->Model($model_12);
            $this->load->model('app');
            $warranty_type = $this->app->get_ops(525);

            $get_movement_data = $this->$model_12->get_order_number_print($list['NRO_GARANTIA'], $list['period']);
            if ($get_movement_data) {
                foreach ($get_movement_data as $warranty) {
                    $participate_cuit = $warranty[5349];
                    $participate = $this->padfyj_model->search_name($participate_cuit);

                    $creditor_cuit = $warranty[5351];
                    $creditor = $this->padfyj_model->search_name($creditor_cuit);

                    $reafianzadora_cuit = (string) $list['CUIT'];
                    $reafianzadora = $this->padfyj_model->search_name($reafianzadora_cuit);

                    $origen = $warranty[5215];
                    $warranty_type = $warranty[5216][0];
                    $amount = $warranty[5218];
                }
            }

            $brand_name_creditor = $this->padfyj_model->search_name($warranty[5349]);


            $new_list = array();
            $new_list['col1'] = $list['NRO_GARANTIA'];
            $new_list['col2'] = $participate;
            $new_list['col3'] = $participate_cuit;
            $new_list['col4'] = $origen;
            $new_list['col5'] = @$warranty_type[$warranty_type];
            $new_list['col6'] = money_format_custom($amount);
            $new_list['col7'] = $creditor;
            $new_list['col8'] = $creditor_cuit;
            $new_list['col9'] = mongodate_to_print($list['FECHA_REAFIANZA']);
            $new_list['col10'] = money_format_custom($list['SALDO_VIGENTE']);
            $new_list['col11'] = percent_format_custom($list['REAFIANZADO'] * 100);
            $new_list['RAZON_SOCIAL'] = $reafianzadora;
            $new_list['CUIT'] = $reafianzadora_cuit;
            $rtn[] = $new_list;
        }
        return $rtn;
    }

}
