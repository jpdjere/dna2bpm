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
        $parameter['FECHA_MOVIMIENTO'] = strftime("%Y-%m-%d", mktime(0, 0, 0, 1, -1 + $parameter['FECHA_MOVIMIENTO'], 1900));

        $parameter['period'] = $period;
        $parameter['origin'] = 2013;

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

    function get_movement_data($warranty_num) {
       
        $anexo = $this->anexo;
        $period = 'container.sgr_periodos';
        $container = 'container.sgr_anexo_' . $anexo;

        list($getPeriodMonth, $getPeriodYear) = explode("-", $this->session->userdata['period']);
        $getPeriodMonth = (int) $getPeriodMonth - 1;

        $query = array(
            'anexo' => $anexo,
            "filename" => array('$ne' => 'SIN MOVIMIENTOS'),
            'sgr_id' => $this->sgr_id,
            'status' => 'activo',
            'period_date' => array('$lte' => date($getPeriodYear . '-' . $getPeriodMonth . '-01'))
        );
        $result = $this->mongo->sgr->$period->find($query);
        $caida = 0;
        foreach ($result as $list) {

            $new_query = array(
                'sgr_id' => $list['sgr_id'],
                'filename' => $list['filename'],
                'NRO_GARANTIA' => $warranty_num
            );
            $new_result = $this->mongo->sgr->$container->find($new_query);
            
            $CAIDA = NULL;
            $RECUPERO = NULL;
            $INCOBRABLES_PERIODO = NULL;
            $GASTOS_EFECTUADOS_PERIODO = NULL;
            $RECUPERO_GASTOS_PERIODO = NULL;
            $GASTOS_INCOBRABLES_PERIODO = NULL;

            foreach ($new_result as $new_list) {
                $CAIDA += $new_list['CAIDA'];
                $RECUPERO += $new_list['RECUPERO'];
                $INCOBRABLES_PERIODO += $new_list['INCOBRABLES_PERIODO'];
                $GASTOS_EFECTUADOS_PERIODO += $new_list['GASTOS_EFECTUADOS_PERIODO'];
                $RECUPERO_GASTOS_PERIODO += $new_list['RECUPERO_GASTOS_PERIODO'];
                $GASTOS_INCOBRABLES_PERIODO += $new_list['GASTOS_INCOBRABLES_PERIODO'];
            }
        }
        
        $return_arr = array(
            'CAIDA'=> $CAIDA,
            'RECUPERO'=> $RECUPERO,
            'INCOBRABLES_PERIODO'=>$INCOBRABLES_PERIODO,
            'GASTOS_EFECTUADOS_PERIODO'=>$GASTOS_EFECTUADOS_PERIODO,
            'RECUPERO_GASTOS_PERIODO'=>$GASTOS_EFECTUADOS_PERIODO,
            'GASTOS_INCOBRABLES_PERIODO'=>$GASTOS_EFECTUADOS_PERIODO
        );
        return $return_arr;
    }

}
