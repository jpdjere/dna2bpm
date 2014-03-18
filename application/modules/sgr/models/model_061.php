<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_061 extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('sgr/tools');

        $this->anexo = '061';
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
         * @example .... 
         * */
        $defdna = array(
            1 => 'CUIT_SOCIO_INCORPORADO', //CUIT_SOCIO_INCORPORADO
            2 => 'TIENE_VINCULACION', //TIENE_VINCULACION
            3 => 'CUIT_VINCULADO', //CUIT_VINCULADO
            4 => 'RAZON_SOCIAL_VINCULADO', //RAZON_SOCIAL_VINCULADO
            5 => 'TIPO_RELACION_VINCULACION', //TIPO_RELACION_VINCULACION
            6 => 'PORCENTAJE_ACCIONES', //"PORCENTAJE_ACCIONES"
        );


        $insertarr = array();
        foreach ($defdna as $key => $value) {
            $insertarr[$value] = $parameter[$key];
            $insertarr['CUIT_SOCIO_INCORPORADO'] = (string) $insertarr['CUIT_SOCIO_INCORPORADO'];
            $insertarr['CUIT_VINCULADO'] = (string) $insertarr['CUIT_VINCULADO'];

            /* FLOAT */
            $insertarr['PORCENTAJE_ACCIONES'] = (float) $insertarr['PORCENTAJE_ACCIONES'];
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

    function save_period_pending($parameter) {
        /* ADD PERIOD */
        $container = 'container.sgr_periodos';
        $period = $this->session->userdata['period'];

        $id = $this->app->genid_sgr($container);
        $parameter['period'] = $period;
        $parameter['period_date'] = translate_period_date($period);
        $parameter['status'] = 'activo';
        $parameter['idu'] = $this->idu;
        $parameter['activated_on'] = date('Y-m-d h:i:s');

        /*
         * VERIFICO PENDIENTE           
         */
        $get_period = $this->sgr_model->get_period_info($this->anexo, $this->sgr_id, $period);
        
        var_dump($get_period);
        
        
        $this->update_period($get_period['id'], $get_period['status']);
        $result = $this->app->put_array_sgr($id, $container, $parameter);
        if ($result) {
            /* ACTUALIZO PENDIND DEL ANEXO 06 */
            $get_pending = $this->sgr_model->get_period_info("06", $this->sgr_id, $period);
            $this->update_pending($get_period['id'], $get_period['status']);
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

    /* UPDATE ANEXO 06 */

    function update_pending($id, $status) {
        $options = array('upsert' => true, 'safe' => true);
        $container = 'container.sgr_periodos';
        $query = array('id' => (integer) $id);
        $parameter = array(
            'status' => 'activo',
            'activated_on' => date('Y-m-d h:i:s')
        );
        $rs = $this->mongo->sgr->$container->update($query, array('$set' => $parameter), $options);
        return $rs['err'];
    }

    function get_anexo_info($anexo, $parameter) {

        $headerArr = array("Tipo<br/>de<br/>Socio",
            "C.U.I.T Socio<br/>Incorporado",
            "Socio<br/>Incorporado",
            "Tiene<br/>Vinculacion",
            "C.U.I.T<br/>Vinculado",
            "Razón<br/>Social<br/>Vinculado",
            "Tipo<br/>Relación<br/>Vinculación",
            "Porcentaje<br/>Acciones",
            "Es<br/>Participe",
            "Es<br/>Protector"
        );
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

            $cuit = str_replace("-", "", $list['1695']);
            $brand_name = $list['1693'];
            $this->load->model('app');
            $this->load->model('padfyj_model');
            $model_anexo = "model_06";
            $this->load->Model($model_anexo);

            $parner_inc = $this->padfyj_model->search_name($list['CUIT_SOCIO_INCORPORADO']);
            $parner_linked = $this->padfyj_model->search_name((string)$list['CUIT_VINCULADO']);
          
            $type_partner = $this->$model_anexo->partner_type($list['CUIT_SOCIO_INCORPORADO']);
      
            $type_partner_inc = $this->$model_anexo->partner_type((string)$list['CUIT_VINCULADO']);
            var_dump($type_partner_inc);

            $type_partner_inc = ($type_partner_inc)?$type_partner_inc : $list['RAZON_SOCIAL_VINCULADO'];

            $es_participe = ($type_partner_inc == "A") ? "SI" : "NO";
            $es_protector = ($type_partner_inc == "B") ? "SI" : "NO";
            


            // 					

            $new_list = array();
            $new_list['TIPO_SOCIO'] = $type_partner;
            $new_list['CUIT_SOCIO_INCORPORADO'] = $list['CUIT_SOCIO_INCORPORADO'];
            $new_list['SOCIO_INCORPORADO'] = $parner_inc;
            $new_list['"TIENE_VINCULACION"'] = $list['TIENE_VINCULACION'];
            $new_list['"CUIT_VINCULADO"'] = $list['CUIT_VINCULADO'];
            $new_list['"RAZON_SOCIAL_VINCULADO"'] = $parner_linked;
            $new_list['"TIPO_RELACION_VINCULACION"'] = $list['TIPO_RELACION_VINCULACION'];
            $new_list['"PORCENTAJE_ACCIONES"'] = percent_format_custom($list['PORCENTAJE_ACCIONES'] * 100);
            $new_list['"PARTICIPE"'] = $es_participe;
            $new_list['"PROTECTOR"'] = $es_protector;



            $rtn[] = $new_list;
        }
        return $rtn;
    }
}
