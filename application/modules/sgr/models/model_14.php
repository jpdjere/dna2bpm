<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_14 extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('sgr/tools');

        $this->anexo = '14';
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

    function clear_tmp($parameter) {
        $token = $this->idu;
        $container = 'container.sgr_anexo_' . $token . '_tmp';
        $delete = $this->mongo->sgr->$container->remove();
    }

    function save_tmp($parameter) {
        $token = $this->idu;
        $period = $this->session->userdata['period'];
        $container = 'container.sgr_anexo_' . $token . '_tmp';

        $parameter['TOKEN'] = $token;
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
        
         if (!isset($this->session->userdata['rectify']))
            exit();
        
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

    function get_anexo_infox($anexo, $parameter) {


        $headerArr = array("Fecha",
            "N° de Orden de la Garantía Otorgada",
            "CAIDA", "RECUPERO", "INCOBRABLES_PERIODO", "GASTOS_EFECTUADOS_PERIODO", "RECUPERO_GASTOS_PERIODO", "GASTOS_INCOBRABLES_PERIODO");
        $data = array($headerArr);
        $anexoValues = $this->get_anexo_data($anexo, $parameter);
        foreach ($anexoValues as $values) {
            $data[] = array_values($values);
        }
        $this->load->library('table');
        return $this->table->generate($data);
    }

    function get_anexo_info($anexo, $parameter, $xls = false) {
        $tmpl = array(
            'data' => '<tr><td align="center" rowspan="2">Fecha</td>
                                <td align="center" rowspan="2">N° de Orden de la Garantía Otorgada</td>
                                <td align="center" rowspan="2">Socio Participe</td>
                                <td align="center" rowspan="2">C.U.I.T</td>                                
                                <td align="center" colspan="3">GARANTIAS AFRONTADAS</td>
                                <td align="center" colspan="3">Gastos por Gestión de Recuperos</td>
    <tr>
        <td>Deuda Originada en el Período</td>
        <td>Cobranza o Recupero del Período</td>
        <td>Incobrables declarados en el Período</td>
        <td>Gastos efectuados en el Período</td>
        <td>Recuperos del Período</td>
        <td>Incobrables declarados en el Período</td>       
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
        <td>10</td>       
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


            /* "12585/10" */
            $get_movement_data = $this->$model_12->get_order_number_print($list['NRO_GARANTIA'], $list['period']);


            if (!empty($get_movement_data)) {
                foreach ($get_movement_data as $warrant) {
                    $cuit = $warrant[5349];
                    $brand_name = $this->padfyj_model->search_name($warrant[5349]);
                }
            }

            $new_list = array();
            $new_list['col1'] = mongodate_to_print($list['FECHA_MOVIMIENTO']);
            $new_list['col2'] = $list['NRO_GARANTIA'];
            $new_list['col3'] = $brand_name;
            $new_list['col4'] = $cuit;
            $new_list['col5'] = money_format_custom($list['CAIDA']);
            $new_list['col6'] = money_format_custom($list['RECUPERO']);
            $new_list['col7'] = money_format_custom($list['INCOBRABLES_PERIODO']);
            $new_list['col8'] = money_format_custom($list['GASTOS_EFECTUADOS_PERIODO']);
            $new_list['col9'] = money_format_custom($list['RECUPERO_GASTOS_PERIODO']);
            $new_list['col10'] = money_format_custom($list['GASTOS_INCOBRABLES_PERIODO']);
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
        $new_list['col5'] = money_format_custom(array_sum($col5));
        $new_list['col6'] = money_format_custom(array_sum($col6));
        $new_list['col7'] = money_format_custom(array_sum($col7));
        $new_list['col8'] = money_format_custom(array_sum($col8));
        $new_list['col9'] = money_format_custom(array_sum($col9));
        $new_list['col10'] = money_format_custom(array_sum($col10));
        $rtn[] = $new_list;

        return $rtn;
    }

    function get_anexo_data_tmp($anexo, $parameter) {

        $rtn = array();
        $container = 'container.sgr_anexo_' . $anexo;
        $fields = array('FECHA_MOVIMIENTO',
            'NRO_GARANTIA',
            'RECUPERO',
            'INCOBRABLES_PERIODO',
            'GASTOS_EFECTUADOS_PERIODO',
            'RECUPERO_GASTOS_PERIODO',
            'GASTOS_INCOBRABLES_PERIODO', 'filename', 'period', 'sgr_id', 'origin');
        $query = array("filename" => $parameter);
        $result = $this->mongo->sgr->$container->find($query, $fields);

        foreach ($result as $list) {
            $rtn[] = $list;
        }

        return $rtn;
    }

    function get_movement_data($nro) {

        $anexo = $this->anexo;
        $token = $this->idu;
        $container = 'container.sgr_anexo_' . $anexo;
        $period_value = $this->session->userdata['period'];

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

    function get_movement_data_print($nro, $period) {


        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;

        $caida_result_arr = array();
        $recupero_result_arr = array();
        $inc_periodo_arr = array();
        $gasto_efectuado_periodo_arr = array();
        $recupero_gasto_periodo_arr = array();
        $gasto_incobrable_periodo_arr = array();

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active_print($anexo, $period);


        /* FIND ANEXO */
        foreach ($result as $list) {
            $new_query = array(
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
        $container = 'container.sgr_anexo_' . $this->idu . '_tmp';

        $caida_result_arr = array();
        $recupero_result_arr = array();
        $inc_periodo_arr = array();
        $gasto_efectuado_periodo_arr = array();
        $recupero_gasto_periodo_arr = array();
        $gasto_incobrable_periodo_arr = array();

        $token = $this->idu;
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
        $container = 'container.sgr_anexo_' . $this->idu . '_tmp';
        $token = $this->idu;
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

    function get_gastos_tmp($nro, $date) {

        $gasto_efectuado_periodo_arr = array();
        $recupero_gasto_periodo_arr = array();
        $gasto_incobrable_periodo_arr = array();


        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $this->idu . '_tmp';
        $token = $this->idu;
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
            $gasto_efectuado_periodo_arr[] = $movement['GASTOS_EFECTUADOS_PERIODO'];
            $recupero_gasto_periodo_arr[] = $movement['RECUPERO_GASTOS_PERIODO'];
            $gasto_incobrable_periodo_arr[] = $movement['GASTOS_INCOBRABLES_PERIODO'];
        }

        $gasto_efectuado_periodo_sum = array_sum($gasto_efectuado_periodo_arr);
        $recupero_gasto_periodo_sum = array_sum($recupero_gasto_periodo_arr);
        $gasto_incobrable_periodo_sum = array_sum($gasto_incobrable_periodo_arr);

        $return_arr = array(
            'GASTOS_EFECTUADOS_PERIODO' => $gasto_efectuado_periodo_sum,
            'RECUPERO_GASTOS_PERIODO' => $recupero_gasto_periodo_sum,
            'GASTOS_INCOBRABLES_PERIODO' => $gasto_incobrable_periodo_sum
        );
        return $return_arr;
    }

    function get_caida_tmp($nro, $date) {

        $caida_result_arr = array();
        $recupero_result_arr = array();
        $inc_periodo_arr = array();


        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $this->idu . '_tmp';
        $token = $this->idu;
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

    function nums_guarantees_faced($period, $col) {
        $anexo = $this->anexo;
        /* GET ACTIVE ANEXOS */
        $container_period = 'container.sgr_periodos';
        $container = 'container.sgr_anexo_' . $anexo;


        $result = $this->sgr_model->get_active_one($anexo, $period); //exclude actual


        $rtn = array();
        foreach ($result as $each) {

            $new_query = array(
                'filename' => $each['filename']
            );


            $warrants = $this->mongo->sgr->$container->find($new_query);

            foreach ($warrants as $warrant) {
                if ($warrant[$col])
                    $rtn[] = $warrant['NRO_GARANTIA'];
            }
        }
        return (count(array_unique($rtn)));
    }

    function amount_guarantees_faced($period, $col) {
        $anexo = $this->anexo;
        /* GET ACTIVE ANEXOS */
        $container_period = 'container.sgr_periodos';
        $container = 'container.sgr_anexo_' . $anexo;


        $result = $this->sgr_model->get_active_one($anexo, $period); //exclude actual


        $rtn = array();
        foreach ($result as $each) {

            $new_query = array(
                'filename' => $each['filename']
            );


            $warrants = $this->mongo->sgr->$container->find($new_query);

            foreach ($warrants as $warrant) {
                if ($warrant[$col])
                    $rtn[] = $warrant[$col];
            }
        }

        $sum = array_sum($rtn);
        return $sum;
    }

    function get_anexo_report($anexo, $parameter) {

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
		<td>MOVIMIENTOS DE CAPITAL SOCIAL</td>
		
	</tr>
	<tr>
		<td></td>
		
	</tr>
	<tr>
		<td>PER&Iacute;ODO/S: ' . $input_period_from . ' a ' . $input_period_to . '</td>
		
	</tr><tr>
            <td align="center" rowspan="2">SGR</td>
            <td align="center" rowspan="2">CUIT SGR</td>
            <td align="center" rowspan="2">ID</td>
            <td align="center" rowspan="2">Per&iacute;odo</td>
            <td align="center" rowspan="2">Fecha</td>
                                <td align="center" rowspan="2">N° de Orden de la Garantía Otorgada</td>
                                <td align="center" rowspan="2">Socio Participe</td>
                                <td align="center" rowspan="2">C.U.I.T</td>                                
                                <td align="center" colspan="3">GARANTIAS AFRONTADAS</td>
                                <td align="center" colspan="3">Gastos por Gestión de Recuperos</td>
    <tr>
        <td>Deuda Originada en el Período</td>
        <td>Cobranza o Recupero del Período</td>
        <td>Incobrables declarados en el Período</td>
        <td>Gastos efectuados en el Período</td>
        <td>Recuperos del Período</td>
        <td>Incobrables declarados en el Período</td>       
    </tr>
	
',
        );
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

        if (!$parameter) {
            return false;
            exit();
        }

        header('Content-type: text/html; charset=UTF-8');
        $rtn = array();



        $input_period_from = ($parameter['input_period_from']) ? $parameter['input_period_from'] : '01_1990';
        $input_period_to = ($parameter['input_period_to']) ? $parameter['input_period_to'] : '12_' . date("Y");


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

        $period_result = $this->mongo->sgr->$period_container->find($query);


       

        $files_arr = array();
        $container = 'container.sgr_anexo_' . $anexo;


        $new_query = array();
        foreach ($period_result as $results) {
            $period = $results['period'];
            $new_query['$or'][] = array("filename" => $results['filename']);
        }


        $result_arr = $this->mongo->sgr->$container->find($new_query);
        /* TABLE DATA */
        return $this->ui_table_xls($result_arr, $anexo);
    }

    function ui_table_xls($result, $anexo = null) {

        foreach ($result as $list) {

            /* Vars */
            $cuit = str_replace("-", "", $list['CUIT']);
            $this->load->model('padfyj_model');
            $model_12 = 'model_12';
            $this->load->Model($model_12);


            /* "12585/10" */
            $get_movement_data = $this->$model_12->get_order_number_print($list['NRO_GARANTIA'], $this->session->userdata['period']);


            if (!empty($get_movement_data)) {
                foreach ($get_movement_data as $warrant) {
                    $cuit = $warrant[5349];
                    $brand_name = $this->padfyj_model->search_name($warrant[5349]);
                }
            }


            $get_period_filename = $this->sgr_model->get_period_filename($list['filename']);

            $new_list = array();
            $new_list['col1'] = $this->sgr_nombre;
            $new_list['col2'] = $cuit_sgr;
            $new_list['col3'] = $list['id'];
            $new_list['col4'] = $get_period_filename['period'];
            $new_list['col5'] = mongodate_to_print($list['FECHA_MOVIMIENTO']);
            $new_list['col6'] = $list['NRO_GARANTIA'];
            $new_list['col7'] = $brand_name;
            $new_list['col8'] = $cuit;
            $new_list['col9'] = money_format_custom($list['CAIDA']);
            $new_list['col10'] = money_format_custom($list['RECUPERO']);
            $new_list['col11'] = money_format_custom($list['INCOBRABLES_PERIODO']);
            $new_list['col12'] = money_format_custom($list['GASTOS_EFECTUADOS_PERIODO']);
            $new_list['col13'] = money_format_custom($list['RECUPERO_GASTOS_PERIODO']);
            $new_list['col14'] = money_format_custom($list['GASTOS_INCOBRABLES_PERIODO']);
            $rtn[] = $new_list;
        }

        return $rtn;
    }

}
