<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_201 extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('sgr/tools');

        $this->anexo = '201';
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
         * @example
         * NUMERO_DE_APORTE	
         * FECHA_MOVIMIENTO	
         * CUIT_PROTECTOR	
         * APORTE	
         * RETIRO	
         * RETENCION_POR_CONTINGENTE	
         * RETIRO_DE_RENDIMIENTOS	
         * ESPECIE	
         * TITULAR_ORIG	
         * NRO_CTA_OR	
         * ENTIDAD_OR	
         * ENT_DEP_OR	
         * TITULAR_DEST	
         * NRO_DEST	
         * ENTIDAD_DEST	
         * ENT_DEP_DEST	
         * FECHA_ACTA	
         * NRO_ACTA
         * */
        $defdna = array(
            1 => 'NUMERO_DE_APORTE',
            2 => 'FECHA_MOVIMIENTO',
            3 => 'CUIT_PROTECTOR',
            4 => 'APORTE',
            5 => 'RETIRO',
            6 => 'RETENCION_POR_CONTINGENTE',
            7 => 'RETIRO_DE_RENDIMIENTOS',
            8 => 'ESPECIE',
            9 => 'TITULAR_ORIG',
            10 => 'NRO_CTA_OR',
            11 => 'ENTIDAD_OR',
            12 => 'ENT_DEP_OR',
            13 => 'TITULAR_DEST',
            14 => 'NRO_DEST',
            15 => 'ENTIDAD_DEST',
            16 => 'ENT_DEP_DEST',
            17 => 'FECHA_ACTA',
            18 => 'NRO_ACTA'
        );

        $insertarr = array();
        foreach ($defdna as $key => $value) {
            $insertarr[$value] = $parameter[$key];

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

        $parameter = array_map('trim', $parameter);
        $parameter = array_map('addSlashes', $parameter);


        /* FIX DATE */
        $parameter['FECHA_MOVIMIENTO'] = strftime("%Y-%m-%d", mktime(0, 0, 0, 1, -1 + $parameter['FECHA_MOVIMIENTO'], 1900));
        $parameter['FECHA_ACTA'] = strftime("%Y-%m-%d", mktime(0, 0, 0, 1, -1 + $parameter['FECHA_ACTA'], 1900));


        $parameter['period'] = $period;
        $parameter['origin'] = 2013;
        $id = $this->app->genid_sgr($container);
        $parameter['sgr_id'] = $this->sgr_id;
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


        $headerArr = array("NUMERO_DE_APORTE",
            "FECHA_MOVIMIENTO",
            "CUIT_PROTECTOR",
            "APORTE",
            "RETIRO",
            "RETENCION_POR_CONTINGENTE",
            "RETIRO_DE_RENDIMIENTOS",
            "ESPECIE",
            "TITULAR_ORIG",
            "NRO_CTA_OR",
            "ENTIDAD_OR",
            "ENT_DEP_OR",
            "TITULAR_DEST",
            "NRO_DEST",
            "ENTIDAD_DEST",
            "ENT_DEP_DEST",
            "FECHA_ACTA",
            "NRO_ACTA");
        $data = array($headerArr);
        $anexoValues = $this->get_anexo_data($anexo, $parameter);
        foreach ($anexoValues as $values) {
            $data[] = array_values($values);
        }
        $this->load->library('table');
        return $this->table->generate($data);
    }

    function get_anexo_data($anexo, $parameter) {
        header('Content-type: text / html;
        charset = UTF-8');
        $rtn = array();
        $container = 'container.sgr_anexo_' . $anexo;
        $query = array("filename" => $parameter);
        $result = $this->mongo->sgr->$container->find($query);

        foreach ($result as $list) {
            /* Vars 								
             */

            $this->load->model('padfyj_model');
            $this->load->model('app');

            //				


            $new_list = array();
            $new_list['NUMERO_DE_APORTE'] = $list['NUMERO_DE_APORTE'];
            $new_list['FECHA_MOVIMIENTO'] = $list['FECHA_MOVIMIENTO'];
            $new_list['CUIT_PROTECTOR'] = $list['CUIT_PROTECTOR'];
            $new_list['APORTE'] = money_format_custom($list['APORTE']);
            $new_list['RETIRO'] = money_format_custom($list['RETIRO']);
            $new_list['RETENCION_POR_CONTINGENTE'] = money_format_custom($list['RETENCION_POR_CONTINGENTE']);
            $new_list['RETIRO_DE_RENDIMIENTOS'] = money_format_custom($list['RETIRO_DE_RENDIMIENTOS']);
            $new_list['ESPECIE'] = $list['ESPECIE'];
            $new_list['TITULAR_ORIG'] = $list['TITULAR_ORIG'];
            $new_list['NRO_CTA_OR'] = $list['NRO_CTA_OR'];
            $new_list['ENTIDAD_OR'] = $list['ENTIDAD_OR'];
            $new_list['ENT_DEP_OR'] = $list['ENT_DEP_OR'];
            $new_list['TITULAR_DEST'] = $list['TITULAR_DEST'];
            $new_list['NRO_DEST'] = $list['NRO_DEST'];
            $new_list['ENTIDAD_DEST'] = $list['ENTIDAD_DEST'];
            $new_list['ENT_DEP_DEST'] = $list['ENT_DEP_DEST'];
            $new_list['FECHA_ACTA'] = $list['FECHA_ACTA'];
            $new_list['NRO_ACTA'] = $list['NRO_ACTA'];
            $rtn[] = $new_list;
        }
        return $rtn;
    }

    function get_order_number_($code) {
        $container = 'container.sgr_anexo_' . $anexo;
        $query = array("NUMERO_DE_APORTE" => $code, 'sgr_id' => $this->sgr_id);
        $result = $this->mongo->sgr->$container->findOne($query);
        var_dump($query);
        if ($result) {
            return $result;
        } else {
            /* GET MAX */
            $result = $this->mongo->sgr->$container->find(array(), array('NUMERO_DE_APORTE' => 1))->sort(array('NUMERO_DE_APORTE' => -1))->limit(1);
            return $result;
        }

        var_dump($result);
    }

    function get_order_number($code) {
        $period = 'container.sgr_periodos';
        list($getPeriodMonth, $getPeriodYear) = explode("-", $this->session->userdata['period']);
        $getPeriodMonth = (int) $getPeriodMonth - 1;
        $endDate = new MongoDate(strtotime($getPeriodYear . "-" . $getPeriodMonth . "-01 00:00:00"));

        $nresult_arr = array();
        $anexo = $this->anexo;

        $container = 'container.sgr_anexo_' . $anexo;
        $query = array(
            "period_date" => array(
                '$lte' => $endDate
            ),
            'status' => 'activo',
            'anexo' => $anexo,
            'sgr_id' => $this->sgr_id);
        $result = $this->mongo->sgr->$period->find($query);
        /* FIND ANEXO */
        foreach ($result as $list) {
            $new_query = array(
                'NUMERO_DE_APORTE' => $code,
                'sgr_id' => $list['sgr_id'],
                'filename' => $list['filename']
            );

            $new_result = $this->mongo->sgr->$container->findOne($new_query);
            if ($new_result) {
                $nresult_arr[] = $new_result[$field];
            }
        }

        $result = array_sum($nresult_arr);
        return $result;
    }

    function get_last_input_number($code) {
        $period = 'container.sgr_periodos';
        list($getPeriodMonth, $getPeriodYear) = explode("-", $this->session->userdata['period']);
        $getPeriodMonth = (int) $getPeriodMonth - 1;
        $endDate = new MongoDate(strtotime($getPeriodYear . "-" . $getPeriodMonth . "-01 00:00:00"));

        $nresult_arr = array();
        $anexo = $this->anexo;

        $container = 'container.sgr_anexo_' . $anexo;
        $query = array(
            "period_date" => array(
                '$lte' => $endDate
            ),
            'status' => 'activo',
            'anexo' => $anexo,
            'sgr_id' => $this->sgr_id);
        $result = $this->mongo->sgr->$period->find($query)->sort(array('period_date' => -1))->limit(1);
        ;


        /* FIND ANEXO */
        foreach ($result as $list) {
            var_dump($list['sgr_id']);
            $new_query = array(
                'sgr_id' => $list['sgr_id'],
                'filename' => $list['filename']
            );

        $new_result = $this->mongo->sgr->$container->find($new_query)->sort(array('NUMERO_DE_APORTE' => -1))->limit(1);            
        
            foreach ($new_result as $new_list) {
                var_dump($list['filename'],$new_list['filename']);
            }
//            if ($new_result) {
//                $nresult_arr[] = $new_result[$field];
//            } 
        }

        $result = array_sum($nresult_arr);
        return $result;
    }

}
