<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_14 extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('sgr/tools');

        $this->anexo = '14';
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
         * @example .... FECHA_MOVIMIENTO	NRO_GARANTIA	CAIDA	RECUPERO	INCOBRABLES_PERIODO	GASTOS_EFECTUADOS_PERIODO	
         * RECUPERO_GASTOS_PERIODO	GASTOS_INCOBRABLES_PERIODO
         * */
        $defdna = array(
            1 => 'FECHA_MOVIMIENTO',
            2 => 'NRO_GARANTIA',
            3 => 'CAIDA',
            4 => 'RECUPERO',
            5 => 'INCOBRABLES_PERIODO',
            6 => 'GASTOS_EFECTUADOS_PERIODO',
            7 => 'RECUPERO_GASTOS_PERIODO',
            8 => 'GASTOS_INCOBRABLES_PERIODO'
        );


        $insertarr = array();
        foreach ($defdna as $key => $value) {
            $insertarr[$value] = $parameter[$key];

            /* STRING */
            $insertarr["NRO_GARANTIA"] = (string) $insertarr["NRO_GARANTIA"]; //Nro orden
            /* INTEGERS & FLOAT */
            $insertarr["CAIDA"] = (float) $insertarr["CAIDA"];
            $insertarr["RECUPERO"] = (float) $insertarr["RECUPERO"];
            $insertarr["INCOBRABLES_PERIODO"] = (float) $insertarr["INCOBRABLES_PERIODO"];
            $insertarr["GASTOS_EFECTUADOS_PERIODO"] = (float) $insertarr["GASTOS_EFECTUADOS_PERIODO"];
            $insertarr["RECUPERO_GASTOS_PERIODO"] = (float) $insertarr["RECUPERO_GASTOS_PERIODO"];
            $insertarr["GASTOS_INCOBRABLES_PERIODO"] = (float) $insertarr["GASTOS_INCOBRABLES_PERIODO"];
        }
        return $insertarr;
    }

    function save($parameter) {
        $period = $this->session->userdata['period'];
        $container = 'container.sgr_anexo_' . $this->anexo;

        $parameter['FECHA_MOVIMIENTO'] = new MongoDate(strtotime(translate_for_mongo($parameter['FECHA_MOVIMIENTO'])));

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

    function clear_tmp($parameter) {
        $container = 'container.sgr_anexo_' . $this->anexo . '_tmp';
        $result = $this->mongo->sgr->$container->remove();
        //
    }

    function save_tmp($parameter) {

        $period = $this->session->userdata['period'];
        $container = 'container.sgr_anexo_' . $this->anexo . '_tmp';

        $parameter['TOKEN'] = $this->idu . $this->session->userdata['period'];
        $parameter['FECHA_MOVIMIENTO'] = new MongoDate(strtotime(translate_for_mongo($parameter['FECHA_MOVIMIENTO'])));

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

    function get_anexo_info($anexo, $parameter) {


        $headerArr = array("FECHA_MOVIMIENTO", "NRO_GARANTIA", "CAIDA", "RECUPERO", "INCOBRABLES_PERIODO", "GASTOS_EFECTUADOS_PERIODO", "RECUPERO_GASTOS_PERIODO", "GASTOS_INCOBRABLES_PERIODO");
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
            $cuit = str_replace("-", "", $list['CUIT']);
            $this->load->model('padfyj_model');
            $brand_name = $this->padfyj_model->search_name($cuit);
            $brand_name = ($brand_name) ? $brand_name : strtoupper($list['RAZON_SOCIAL']);

            $new_list = array();
            $new_list['FECHA_MOVIMIENTO'] = $list['FECHA_MOVIMIENTO'];
            $new_list['NRO_GARANTIA'] = $list['NRO_GARANTIA'];
            $new_list['CAIDA'] = money_format_custom($list['CAIDA']);
            $new_list['RECUPERO'] = money_format_custom($list['RECUPERO']);
            $new_list['INCOBRABLES_PERIODO'] = money_format_custom($list['INCOBRABLES_PERIODO']);
            $new_list['GASTOS_EFECTUADOS_PERIODO'] = money_format_custom($list['GASTOS_EFECTUADOS_PERIODO']);
            $new_list['RECUPERO_GASTOS_PERIODO'] = money_format_custom($list['RECUPERO_GASTOS_PERIODO']);
            $new_list['GASTOS_INCOBRABLES_PERIODO'] = money_format_custom($list['GASTOS_INCOBRABLES_PERIODO']);
            $rtn[] = $new_list;
        }
        return $rtn;
    }

    function get_movement_data($nro) {
        $anexo = $this->anexo;
        $period_value = $this->session->userdata['period'];
        $period = 'container.sgr_periodos';
        $container = 'container.sgr_anexo_' . $anexo;

        $caida_result_arr = array();
        $recupero_result_arr = array();
        $inc_periodo_arr = array();
        $gasto_efectuado_periodo_arr = array();
        $recupero_gasto_periodo_arr = array();
        $gasto_incobrable_periodo_arr = array();

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active($anexo, $period_value);

        /* FIND ANEXO */
        foreach ($result as $list) {
            $new_query = array(
                'sgr_id' => $list['sgr_id'],
                'filename' => $list['filename'],
                'NRO_GARANTIA' => $nro
            );

            $movement_result = $this->mongo->sgr->$container->find($new_query);
            foreach ($movement_result as $movement) {
                $caida_result_arr[] = $movement['CAIDA'];
                $recupero_result_arr[] = $movement['RECUPERO'];
                $inc_periodo_arr[] = $movement['INCOBRABLES_PERIODO'];
                $gasto_efectuado_periodo_arr[] = $movement['GASTOS_EFECTUADOS_PERIODO'];
                $recupero_gasto_periodo_arr[] = $movement['RECUPERO_GASTOS_PERIODO'];
                $gasto_incobrable_periodo_arr[] = $movement['GASTOS_INCOBRABLES_PERIODO'];
            }
        }


        $caida_sum = array_sum($caida_result_arr);
        $recupero_sum = array_sum($recupero_result_arr);
        $inc_periodo_sum = array_sum($inc_periodo_arr);
        $gasto_efectuado_periodo_sum = array_sum($gasto_efectuado_periodo_arr);
        $recupero_gasto_periodo_sum = array_sum($recupero_gasto_periodo_arr);
        $gasto_incobrable_periodo_sum = array_sum($gasto_incobrable_periodo_arr);



        $return_arr = array(
            'CAIDA' => $caida_sum,
            'RECUPERO' => $recupero_sum,
            'INCOBRABLES_PERIODO' => $inc_periodo_sum,
            'GASTOS_EFECTUADOS_PERIODO' => $gasto_efectuado_periodo_sum,
            'RECUPERO_GASTOS_PERIODO' => $recupero_gasto_periodo_sum,
            'GASTOS_INCOBRABLES_PERIODO' => $gasto_incobrable_periodo_sum
        );
        return $return_arr;
    }

    function get_tmp_movement_data($nro) {
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $this->anexo . '_tmp';

        $caida_result_arr = array();
        $recupero_result_arr = array();
        $inc_periodo_arr = array();
        $gasto_efectuado_periodo_arr = array();
        $recupero_gasto_periodo_arr = array();
        $gasto_incobrable_periodo_arr = array();

        $token = $this->idu . $this->session->userdata['period'];
        $new_query = array(
            'NRO_GARANTIA' => $nro,
            'TOKEN' => $token,
        );

        $movement_result = $this->mongo->sgr->$container->find($new_query);
        foreach ($movement_result as $movement) {
            $caida_result_arr[] = $movement['CAIDA'];
            $recupero_result_arr[] = $movement['RECUPERO'];
            $inc_periodo_arr[] = $movement['INCOBRABLES_PERIODO'];
            $gasto_efectuado_periodo_arr[] = $movement['GASTOS_EFECTUADOS_PERIODO'];
            $recupero_gasto_periodo_arr[] = $movement['RECUPERO_GASTOS_PERIODO'];
            $gasto_incobrable_periodo_arr[] = $movement['GASTOS_INCOBRABLES_PERIODO'];
        }


        $caida_sum = array_sum($caida_result_arr);
        $recupero_sum = array_sum($recupero_result_arr);
        $inc_periodo_sum = array_sum($inc_periodo_arr);
        $gasto_efectuado_periodo_sum = array_sum($gasto_efectuado_periodo_arr);
        $recupero_gasto_periodo_sum = array_sum($recupero_gasto_periodo_arr);
        $gasto_incobrable_periodo_sum = array_sum($gasto_incobrable_periodo_arr);



        $return_arr = array(
            'CAIDA' => $caida_sum,
            'RECUPERO' => $recupero_sum,
            'INCOBRABLES_PERIODO' => $inc_periodo_sum,
            'GASTOS_EFECTUADOS_PERIODO' => $gasto_efectuado_periodo_sum,
            'RECUPERO_GASTOS_PERIODO' => $recupero_gasto_periodo_sum,
            'GASTOS_INCOBRABLES_PERIODO' => $gasto_incobrable_periodo_sum
        );
        return $return_arr;
    }

    function get_recuperos_tmp($nro, $type) {
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $this->anexo . '_tmp';
        $token = $this->idu . $this->session->userdata['period'];
        $new_query = array(
            'NRO_GARANTIA' => $nro,
            'TOKEN' => $token
        );

        $date_movement_arr = array();

        $movement_result = $this->mongo->sgr->$container->find($new_query);

        foreach ($movement_result as $movement) {
            if ($movement[$type])
                $date_movement_arr[] = $movement['FECHA_MOVIMIENTO'];
        }
        return $date_movement_arr;
    }

    function get_test_tmp($nro, $date) {

        $caida_result_arr = array();
        $recupero_result_arr = array();
        $inc_periodo_arr = array();


        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $this->anexo . '_tmp';
        $token = $this->idu . $this->session->userdata['period'];
        $new_query = array(
            'NRO_GARANTIA' => $nro,
            'TOKEN' => $token,
            'FECHA_MOVIMIENTO' => array(
                '$lte' => $date
            )
        );

        $date_movement_arr = array();

        $movement_result = $this->mongo->sgr->$container->find($new_query);

        foreach ($movement_result as $movement) {
            var_dump($movement['RECUPERO']);

            $caida_result_arr[] = $movement['CAIDA'];
            $recupero_result_arr[] = $movement['RECUPERO'];
            $inc_periodo_arr[] = $movement['INCOBRABLES_PERIODO'];
        }

        $caida_sum = array_sum($caida_result_arr);
        $recupero_sum = array_sum($recupero_result_arr);
        $inc_periodo_sum = array_sum($inc_periodo_arr);

        $return_arr = array(
            'CAIDA' => $caida_sum,
            'RECUPERO' => $recupero_sum,
            'INCOBRABLES_PERIODO' => $inc_periodo_sum
        );
        return $return_arr;
    }

}
