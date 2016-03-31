<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_062 extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('sgr/tools');

        $this->anexo = '062';
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
         * @example .... 

         * */
        $defdna = array(
            1 => 'CUIT', //CUIT
            2 => 'ANIO_MES', //ANIO_MES
            3 => 'FACTURACION', //FACTURACION
            4 => 'EMPLEADOS', //EMPLEADOS
            5 => 'TIPO_ORIGEN', //TIPO_ORIGEN
        );


        $insertarr = array();
        foreach ($defdna as $key => $value) {
            $insertarr[$value] = $parameter[$key];
            $insertarr["CUIT"] = (string) $insertarr["CUIT"];
            /* FLOAT */
            $insertarr["FACTURACION"] = (float) $insertarr["FACTURACION"];
            /* INT */
            $insertarr['EMPLEADOS'] = (int) $insertarr['EMPLEADOS'];
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

    function get_anexo_data_tmp($anexo, $parameter) {

        $rtn = array();
        $container = 'container.sgr_anexo_' . $anexo;
        $fields = array('CUIT',
            'EMPLEADOS', 'filename', 'period', 'sgr_id', 'origin');
        $query = array("filename" => $parameter);
        $result = $this->mongowrapper->sgr_tmp->$container->find($query, $fields);

        foreach ($result as $list) {
            $rtn[] = $list;
        }

        return $rtn;
    }

    function get_anexo_info($anexo, $parameter) {


        $headerArr = array("C.U.I.T",
            "Apellido y Nombre o Razón Social",
            "Fecha de Cierre del ejercicio<br> (Mes/Año)",
            "Cantidad de Empleados al último día del ejercicio",
            "Ingresos",
            "Origen de los Datos"
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
        $result = $this->mongowrapper->sgr->$container->find($query);

        foreach ($result as $list) {

            /* Vars */
            $this->load->model('app');
            $this->load->model('padfyj_model');

            $parner = $this->padfyj_model->search_name($list['CUIT']);
            list($year, $month) = explode("/", $list['ANIO_MES']);

            $new_list = array();
            $new_list['col1'] = $list['CUIT'];
            $new_list['col2'] = $parner;
            $new_list['col3'] = $month . "/" . $year;
            $new_list['col4'] = $list['EMPLEADOS'];
            $new_list['col5'] = $list['FACTURACION'];
            $new_list['col6'] = $list['TIPO_ORIGEN'];
            $rtn[] = $new_list;
        }
        return $rtn;
    }
    
    /*COUNT PARTNERS*/
    function get_count_partner_left($cuit) {
        $anexo = $this->anexo;
        $token = $this->idu;
        $container = 'container.sgr_anexo_' . $anexo;
        $period_value = $this->session->userdata['period'];

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active($anexo);

        $return_result = array();
        foreach ($result as $list) {
            $new_query = array(
                'filename' => $list['filename'],
                'CUIT' => $cuit
            );            
            
            $new_result = $this->mongowrapper->sgr->$container->find($new_query);
            foreach ($new_result as $each)
                $return_result[] = $each['EMPLEADOS'];
        }

        return array_sum($return_result);
    }

    function get_count_partner_left_fre($cuit) {
        $anexo = $this->anexo;
        $token = $this->idu;
        $container = 'container.sgr_anexo_' . $anexo;
        $period_value = $this->session->userdata['period'];

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active_fre($anexo);

        $return_result = array();
        foreach ($result as $list) {
            $new_query = array(
                'filename' => $list['filename'],
                'CUIT' => $cuit
            );
            $new_result = $this->mongowrapper->sgr->$container->find($new_query);
            foreach ($new_result as $each)
                $return_result[] = $each['EMPLEADOS'];
        }

        return array_sum($return_result);
    }

    function get_partner_left($cuit) {
        $anexo = $this->anexo;
        $token = $this->idu;
        $container = 'container.sgr_anexo_' . $anexo;
        $period_value = $this->session->userdata['period'];

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active($anexo);

        $return_result = array();
        foreach ($result as $list) {
            $new_query = array(
                'filename' => $list['filename'],
                'CUIT' => $cuit
            );            
            
            $new_result = $this->mongowrapper->sgr->$container->find($new_query);
            foreach ($new_result as $each)
                $return_result[] = $each;
        }

        return array_sum($return_result);
    }

    function get_partner_left_fre($cuit) {
        $anexo = $this->anexo;
        $token = $this->idu;
        $container = 'container.sgr_anexo_' . $anexo;
        $period_value = $this->session->userdata['period'];

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active_fre($anexo);

        $return_result = array();
        foreach ($result as $list) {
            $new_query = array(
                'filename' => $list['filename'],
                'CUIT' => $cuit
            );
            $new_result = $this->mongowrapper->sgr->$container->find($new_query);
            foreach ($new_result as $each)
                $return_result[] = $each;
        }

        return array_sum($return_result);
    }

    /* REPORT */

    function get_anexo_report($anexo, $parameter) {

        $input_period_from = ($parameter['input_period_from']) ? : '01_1990';
        $input_period_to = ($parameter['input_period_to']) ? : '12_' . date("Y");

        /* HEADER TEMPLATE */
        $header_data = array();
        $header_data['input_period_to'] = $input_period_to;
        $header_data['input_period_from'] = $input_period_from;
        $header = $this->parser->parse('reports/form_' . $anexo . '_header', $header_data, TRUE);
        $tmpl = array('data' => $header);

        $data = array($tmpl);
        $anexoValues = $this->get_anexo_data_report($anexo, $parameter);
        foreach ($anexoValues as $values) {
            $data[] = array_values($values);
        }
        $this->load->library('table_custom');
        $newTable = $this->table_custom->generate($data);

        return $newTable;
    }

    function get_anexo_data_report($anexo, $parameter) {

        if (!isset($parameter)) {
            return false;
            exit();
        }

        header('Content-type: text/html; charset=UTF-8');
        $rtn = array();



        $input_period_from = ($parameter['input_period_from']) ? : '01_1990';
        $input_period_to = ($parameter['input_period_to']) ? : '12_' . date("Y");
        $cuit_socio = (isset($parameter['cuit_socio'])) ? $parameter['cuit_socio'] : null;


        $start_date = first_month_date($input_period_from);
        $end_date = last_month_date($input_period_to);

        /* GET PERIOD */
        $period_container = 'container.sgr_periodos';
        $query = array(
            'anexo' => $anexo,
            'status' => "activo",
            'period_date' => array(
                '$gte' => $start_date, '$lte' => $end_date
            )
        );


        if ($parameter['sgr_id'] != 666)
            $query["sgr_id"] = (float) $parameter['sgr_id'];

        $period_result = $this->mongowrapper->sgr->$period_container->find($query);


        $files_arr = array();
        $container = 'container.sgr_anexo_' . $anexo;


        $new_query = array();
        foreach ($period_result as $results) {
            $period = $results['period'];
            $new_query['$or'][] = array("filename" => $results['filename']);
        }

        if (isset($cuit_socio))
            $new_query['CUIT'] = $cuit_socio;


        $result_arr = $this->mongowrapper->sgr->$container->find($new_query);
        /* TABLE DATA */
        return $this->ui_table_xls($result_arr, $anexo);
    }

    function ui_table_xls($result, $anexo = null) {

        $this->load->model('app');
        $currency = $this->app->get_ops(549);


        foreach ($result as $list) {
            $this->load->model('padfyj_model');

            $get_period_filename = $this->sgr_model->get_period_filename($list['filename']);
            $sgr_info = $this->sgr_model->get_sgr_by_id_new($list['sgr_id']);

            /*
              "CUIT" : "23233265519",
              "EMPLEADOS" : "1",
              "FACTURACION" : "",
              "TIPO_ORIGEN" : "",
              "filename" : "06.2 - LA SOCIEDAD SGR - 2014-01-17 11:50:57.xls",
              "id" : 185081059,
              "origin" : 2013,
              "period" : "07-2012",
             */

            $sgr_info = $this->sgr_model->get_sgr_by_id_new($get_period_filename['sgr_id']);           
            $cuit_name = $this->padfyj_model->search_name($list['CUIT']);

            
              /* SGR DATA */
            $filename = trim($list['filename']);
            list($g_anexo, $g_denomination, $g_date) = explode("-", $filename);
            
            $new_list = array();
            $new_list['col1'] = $g_denomination;
            $new_list['col2'] = $list['id'];
            $new_list['col3'] = period_print_format($get_period_filename['period']);
            $new_list['col4'] = $list['CUIT'];
            $new_list['col5'] = $cuit_name;
            $new_list['col6'] = $list['EMPLEADOS'];            
            $new_list['col7'] = dot_by_coma($list['FACTURACION']);
            $new_list['col8'] = $list['TIPO_ORIGEN'];
            $new_list['col9'] = $list['ANIO_MES'];
            $new_list['col10'] = $list['filename'];
            $rtn[] = $new_list;
        }

        return $rtn;
    }

}
