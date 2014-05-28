<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_control_panel extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('sgr/tools');


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
            $this->sgr_id = (float) $sgr['id'];
            $this->sgr_nombre = $sgr['1693'];
            $this->sgr_cuit = $sgr['1695'];
        }
    }

    function get_anexo_info($anexo, $parameter) {


        $headerArr = array("TIPO<br/>OPERACION", "SOCIO", "LOCALIDAD<br/>PARTIDO", "DIRECCION", "TELEFONO", "EMAIL WEB"
            , "CODIGO ACTIVIDAD/SECTOR", "A&Ntilde;O/MONTO/TIPO ORIGEN", "PROMEDIO<br/>TIPO EMPRESA", "EMPLEADOS"
            , "ACTA", "MODALIDAD/CAPITAL/ACCIONES", "CEDENTE");
        $data = array($headerArr);
        $anexoValues = $this->get_anexo_data($anexo, $parameter);

        foreach ($anexoValues as $values) {
            $data[] = array_values($values);
        }
        $this->load->library('table');
        return $this->table->generate($data);
    }

    function get_anexo_report($parameter) {

        $sgr_nombre_to_print = ($this->sgr_nombre) ? $this->sgr_nombre : 'TODAS';

        $input_period_from = ($parameter['input_period_from']) ? $parameter['input_period_from'] : '01_1990';
        $input_period_to = ($parameter['input_period_to']) ? $parameter['input_period_to'] : '12_' . date("Y");

        $tmpl = array(
            'data' => '<tr>
		<td>' . $this->sgr_nombre . '</td>
	</tr>
	<tr>
		<td></td>
		
	</tr>
	<tr>
		<td>CONTROL PANEL</td>
		
	</tr>
	<tr>
		<td></td>
		
	</tr>
	<tr>
		<td>PER&Iacute;ODO/S: ' . $input_period_from . ' a ' . $input_period_to . '</td>
	</tr>
	<tr>
		<td rowspan=4>SGR</td>
		<td rowspan=4>ANEXO</td>
		<td rowspan=4>PERIODO</td>
		<td rowspan=4>ARCHIVO</td>
		<td rowspan=4>ESTADO</td>
	</tr>
	',
        );
        $data = array($tmpl);
        $anexoValues = $this->get_anexo_data_report($parameter);

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
        /* TABLE DATA */
        return $this->ui_table($result);
    }

    function get_anexo_data_report($parameter) {



        if (!$parameter) {
            return false;
            exit();
        }

        header('Content-type: text/html; charset=UTF-8');
        $rtn = array();
   
        $input_origin = false;
        
        if($parameter['input_origin']!="0")
        $input_origin = ($parameter['input_origin']=="1")?"forms2":"2013";
        
        $input_period_from = ($parameter['input_period_from']) ? $parameter['input_period_from'] : '01_1990';
        $input_period_to = ($parameter['input_period_to']) ? $parameter['input_period_to'] : '12_' . date("Y");

        $start_date = first_month_date($input_period_from);
        $end_date = last_month_date($input_period_to);

        /* GET PERIOD */
        $period_container = 'container.sgr_periodos';
        $query = array(
            'status' => "activo",
            'period_date' => array(
                '$gte' => $start_date, '$lte' => $end_date
            )
        );

        if ($parameter['sgr_id'] != 666)
            $query["sgr_id"] = (float) $parameter['sgr_id'];
        
        //if ($input_origin)
           // $query["origen"] = $input_origin;

        $period_result = $this->mongo->sgr->$period_container->find($query);
        $period_result->sort(array('sgr_id' => 1, 'anexo' => 1));

     
        foreach ($period_result as $each)
            $rtn[] = $each;



        /* TABLE DATA */
        return $this->ui_table_xls($rtn);
    }

    function ui_table_xls($result) {
        foreach ($result as $list) {


            $sgrArr_data = $this->sgr_model->get_sgr_by_id($list['sgr_id']);
            foreach ($sgrArr_data as $sgr) {               
                $sgr_nombre = $sgr['1693'];
                $sgr_cuit = $sgr['1695'];
            }


            $new_list = array();
            $new_list['col1'] = $sgr_nombre;
            $new_list['col2'] = "Anexo " . $list['anexo'];
            $new_list['col3'] = $list['period'];
            $new_list['col4'] = $list['filename'];
            $new_list['col5'] = $list['status'];

            $rtn[] = $new_list;
        }

        return $rtn;
    }

    

    /*
     * CLEAN ANEXO DATA
     */

    
    function get_partner_period($cuit, $get_period) {

        $anexo = $this->anexo;
        $period = 'container.sgr_periodos';
        $container = 'container.sgr_anexo_' . $anexo;

        $query = array(
            'anexo' => $anexo,
            'sgr_id' => $this->sgr_id,
            'period' => $get_period,
            'status' => 'activo'
        );

        $result_period = $this->mongo->sgr->$period->findOne($query);
        $query_partner = array(
            'filename' => $result_period['filename'],
            1695 => $cuit
        );

        $result_partner = $this->mongo->sgr->$container->findOne($query_partner);

        return $result_partner;
    }

    /* FROM OUTSIDE (ANOTHER ANEXO) */

    function get_partner_left($cuit) {
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;

        $period_value = $this->session->userdata['period'];

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active($anexo);

        $return_result = array();
        foreach ($result as $list) {
            $new_query = array(
                'filename' => $list['filename'],
                1695 => $cuit
            );

            $new_result = $this->mongo->sgr->$container->findOne($new_query);

            if ($new_result)
                $return_result[] = $new_result;
        }
        return $return_result;
    }

    function get_partner_print($cuit) {
        $anexo = $this->anexo;
        $period = 'container.sgr_periodos';
        $container = 'container.sgr_anexo_' . $anexo;
        $period_value = $this->session->userdata['period'];

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_just_active($anexo);

        $return_result = array();
        foreach ($result as $list) {
            $new_query = array(
                'filename' => $list['filename'],
                1695 => $cuit
            );

            $new_result = $this->mongo->sgr->$container->findOne($new_query);
            if ($new_result)
                $return_result[] = $new_result;
        }

        return $return_result;
    }

    function partner_type_linked($cuit) {
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;
        $new_query = array(
            1695 => $cuit
        );

        $new_result = $this->mongo->sgr->$container->findOne($new_query);
        if ($new_result)
            $return_result[] = $new_result;

        return $return_result;
    }


}
