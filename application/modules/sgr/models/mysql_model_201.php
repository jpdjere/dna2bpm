<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class mysql_model_201 extends CI_Model {

    function mysql_model_201() {
        parent::__construct();
        // IDU : Chequeo de sesion
        $this->idu = (float) switch_users($this->session->userdata('iduser'));
        if (!$this->idu) {
            header("$this->module_url/user/logout");
            exit();
        }

        /* DATOS SGR */
        $sgrArr = $this->sgr_model->get_sgr();
        foreach ($sgrArr as $sgr) {
            $this->sgr_id = $sgr['id'];
            $this->sgr_nombre = $sgr['1693'];
            $this->sgr_cuit = $sgr['1695'];
        }

        $dbconnect = $this->load->database('dna2');
    }

    /* ACTIVE PERIODS DNA2 */

    function active_periods_dna2($anexo, $period) {

        /* TRANSLATE ANEXO NAME */
        $anexo_dna2 = translate_anexos_dna2($anexo);
        $this->db->where('estado', 'activo');
        $this->db->where('archivo !=', 'Sin Movimiento');
        $this->db->where('periodo NOT LIKE', '%2014');
        $this->db->where('anexo', $anexo_dna2);
        $query = $this->db->get('forms2.sgr_control_periodos');

        foreach ($query->result() as $row) {
            $already_period = $this->already_period($row->archivo);
            if (!$already_period) {

                $parameter = array();

                $parameter['anexo'] = translate_anexos_dna2($row->anexo);
                $parameter['filename'] = $row->archivo;
                $parameter['period_date'] = translate_dna2_period_date($row->periodo);
                $parameter['sgr_id'] = (float) $row->sgr_id;
                $parameter['status'] = 'activo';
                $parameter['origen'] = 'forms2';
                $parameter['period'] = str_replace("_", "-", $row->periodo);


                /* UPDATE CTRL PERIOD */
                $this->save_tmp($parameter);

                /* UPDATE ANEXO */
                if ($row->archivo) {
                    $already_update = $this->already_updated($row->anexo, $nro_orden, $filename);
                    if (!$already_update)
                        $this->anexo_data_tmp($anexo_dna2, $row->archivo);
                }
            }
        }
    }

    function active_periods_dna2_ori($anexo, $period) {



        /* TRANSLATE ANEXO NAME */
        $anexo_dna2 = translate_anexos_dna2($anexo);
        $this->db->where('estado', 'activo');
        $this->db->where('archivo !=', 'Sin Movimiento');
        $this->db->where('anexo', $anexo_dna2);
        $query = $this->db->get('forms2.sgr_control_periodos');

        foreach ($query->result() as $row) {



            $already_period = $this->already_period($row->archivo);
            if (!$already_period) {

                $parameter = array();

                $parameter['anexo'] = translate_anexos_dna2($row->anexo);
                $parameter['filename'] = $row->archivo;
                $parameter['period_date'] = translate_dna2_period_date($row->periodo);
                $parameter['sgr_id'] = (float) $row->sgr_id;
                $parameter['status'] = 'activo';
                $parameter['origen'] = 'forms2';
                $parameter['period'] = str_replace("_", "-", $row->periodo);


                $is_2014 = explode("_", $row->periodo);
                if ($is_2014[1] != "2014") {

                    /* UPDATE CTRL PERIOD */
                    $this->save_tmp($parameter);

                    /* UPDATE ANEXO */
                    if ($row->archivo) {
                        $already_update = $this->already_updated($row->anexo, $nro_orden, $filename);
                        if (!$already_update)
                            $this->anexo_data_tmp($anexo_dna2, $row->archivo);
                    }
                }
            }
        }
    }

    /* UPDATE SIN MOVIMIENTO */

    function active_periods_sm_dna2($anexo, $period) {
        /* TRANSLATE ANEXO NAME */
        $anexo_dna2 = translate_anexos_dna2($anexo);
        $this->db->where('estado', 'activo');
        $this->db->where('archivo', 'Sin Movimiento');
        $this->db->where('anexo', $anexo_dna2);
        $query = $this->db->get('forms2.sgr_control_periodos');

        foreach ($query->result() as $row) {
            $already_period = $this->already_period($row->archivo);
            $parameter = array();

            $parameter['anexo'] = translate_anexos_dna2($row->anexo);
            $parameter['filename'] = $row->archivo;
            $parameter['period_date'] = translate_dna2_period_date($row->periodo);
            $parameter['sgr_id'] = (float) $row->sgr_id;
            $parameter['status'] = 'activo';
            $parameter['origen'] = 'forms2';
            $parameter['period'] = str_replace("_", "-", $row->periodo);


            $is_2014 = explode("_", $row->periodo);
            if ($is_2014[1] != "2014") {
                /* UPDATE CTRL PERIOD */
                $this->save_tmp($parameter);
            }
        }
    }

    function save_tmp($parameter) {


        $parameter = (array) $parameter;
        $container = 'container.sgr_periodos';

        $id = $this->app->genid_sgr($container);
        $result = $this->app->put_array_sgr($id, $container, $parameter);
        if ($result) {
            $out = array('status' => 'ok');
        } else {
            $out = array('status' => 'error');
        }
        return $out;
    }

    /* SAVE FETCHS ANEXO  DATA */

    function anexo_data_tmp($anexo, $filename) {




        $this->db->select(
                'sgr_fdr_integrado.ID as get_ID,
                nro_control,
                cuit_protector,
                fecha_movimiento,
                aporte,
                retiro,
                retiro_de_rendimientos,
                retencion_por_contingente,               
                filename,
                idu'
        );

        if ($filename != 'Sin Movimiento')
            $this->db->where('filename', $filename);


        $this->db->join('sgr_fdr_integrado_numeracion', 'sgr_fdr_integrado_numeracion.ID = sgr_fdr_integrado.ID');
        $query = $this->db->get($anexo);
        $parameter = array();
        foreach ($query->result() as $row) {

            $parameter = array();


            /* STRING */

            $parameter["CUIT_PROTECTOR"] = (string) str_replace("-", "", $row->cuit_protector);

            /* INTEGERS  & FLOATS */
            $parameter["APORTE"] = (float) $row->aporte;
            $parameter["RETIRO"] = (float) $row->retiro;
            $parameter["RETENCION_POR_CONTINGENTE"] = (float) $row->retencion_por_contingente;
            $parameter["RETIRO_DE_RENDIMIENTOS"] = (float) $row->retiro_de_rendimientos;

            $parameter["NRO_ACTA"] = (int) $row->NRO_ACTA;
            $parameter["NUMERO_DE_APORTE"] = (int) $row->nro_control;


            $parameter['FECHA_MOVIMIENTO'] = translate_mysql_date($row->fecha_movimiento);

            $parameter['idu'] = (float) $row->idu;
            $parameter['filename'] = (string) $row->filename;
            $parameter['id'] = (float) $row->get_ID;
            $parameter['origen'] = 'forms2';

            debug($parameter);

            $this->save_anexo_201_tmp($parameter, $anexo);
        }
    }

    /* SAVE FETCHS ANEXO 201 DATA */

    function already_period($filename) {

        $container = 'container.sgr_periodos';
        $query = array("filename" => $filename);
        $result = $this->mongo->sgr->$container->findOne($query);
        if ($result)
            return true;
    }

    function already_updated($anexo, $nro_orden, $filename) {

        $container = 'container.sgr_anexo_' . $anexo;
        $query = array("filename" => $filename);
        $result = $this->mongo->sgr->$container->findOne($query);

        if ($result)
            return true;
    }

    function already_id($anexo, $idvalue) {
        $idvalue = (float) $idvalue;

        $container = 'container.sgr_anexo_' . $anexo;
        $query = array("id" => $idvalue);
        $result = $this->mongo->sgr->$container->findOne($query);
        if ($result)
            return true;
    }

    function save_anexo_201_tmp($parameter, $anexo) {
        $parameter = (array) $parameter;
        $token = $this->idu;
        $period = $this->session->userdata['period'];
        $container = 'container.sgr_anexo_201';
        /* TRANSLATE ANEXO NAME */
        $already_id = $this->already_id($anexo, $parameter['id']);

        if ($already_id) {
            //echo "duplicado" . $parameter['id'];
        } else {

            $id = $this->app->genid_sgr($container);
            $result = $this->app->put_array_sgr($id, $container, $parameter);
            if ($result) {
                $out = array('status' => 'ok');
            } else {
                $out = array('status' => 'error');
            }
        }
        return $out;
    }

    function get_anexo_report($anexo, $parameter) {

        $input_period_from = ($parameter['input_period_from']) ? : '01_1990';
        $input_period_to = ($parameter['input_period_to']) ? : '12_' . date("Y");

        $tmpl = array(
            'data' => '<tr>
		<td>' . $this->sgr_nombre . '</td>
	</tr>
	<tr>
		<td></td>
		
	</tr>
	<tr>
		<td>Movimientos del F.D.R. contingente</td>
		
	</tr>
	<tr>
		<td></td>
		
	</tr>
	<tr>
		<td>PER&Iacute;ODO/S: ' . $input_period_from . ' a ' . $input_period_to . '</td>
		
    </tr>
    <tr>
        <td align="center" rowspan="2">SGR</td>            
        <td align="center" rowspan="2">ID</td>        
        <td align="center" rowspan="2">Per&iacute;odo</td>
        <td align="center" rowspan="2">Fecha</td>
        <td align="center" rowspan="2">N° de Orden de la Garantía Otorgada</td>
        <td align="center" rowspan="2">Socio Participe</td>
        <td align="center" rowspan="2">C.U.I.T</td>                                
        <td align="center" colspan="3">GARANTIAS AFRONTADAS</td>
        <td align="center" colspan="3">Gastos por Gestión de Recuperos</td>
        <td align="center" rowspan="2">Filename</td>
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

        if (!isset($parameter)) {
            return false;
            exit();
        }

        header('Content-type: text/html; charset=UTF-8');
        $rtn = array();



        $input_period_from = ($parameter['input_period_from']) ? : '01_1990';
        $input_period_to = ($parameter['input_period_to']) ? : '12_' . date("Y");


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
            $this->load->Model(model_12);


            /* "12585/10" */
            //$get_movement_data = $this->$model_12->get_order_number_print($list['NRO_GARANTIA'], $this->session->userdata['period']);
            $each_sgr_id = $this->sgr_model->get_sgr_by_filename($list['filename']);

            $get_movement_data = $this->model_12->get_order_number_by_sgrid($list['NRO_GARANTIA'], $each_sgr_id);


            //debug($get_movement_data);

            if (!empty($get_movement_data)) {
                foreach ($get_movement_data as $warrant) {
                    $cuit = $warrant[5349];
                    $brand_name = $this->padfyj_model->search_name($warrant[5349]);
                }
            }


            $get_period_filename = $this->sgr_model->get_period_filename($list['filename']);

            $filename = trim($list['filename']);
            list($g_anexo, $g_denomination, $g_date) = explode("-", $filename);


            $new_list = array();
            $new_list['col1'] = $g_denomination;
            $new_list['col2'] = $list['id'];
            $new_list['col3'] = $get_period_filename['period'];
            $new_list['col4'] = mongodate_to_print($list['FECHA_MOVIMIENTO']);
            $new_list['col5'] = $list['NRO_GARANTIA'];
            $new_list['col6'] = $brand_name;
            $new_list['col7'] = $cuit;
            $new_list['col8'] = money_format_custom($list['CAIDA']);
            $new_list['col9'] = money_format_custom($list['RECUPERO']);
            $new_list['col10'] = money_format_custom($list['INCOBRABLES_PERIODO']);
            $new_list['col11'] = money_format_custom($list['GASTOS_EFECTUADOS_PERIODO']);
            $new_list['col12'] = money_format_custom($list['RECUPERO_GASTOS_PERIODO']);
            $new_list['col13'] = money_format_custom($list['GASTOS_INCOBRABLES_PERIODO']);
            $new_list['col14'] = $list['filename'];
            $rtn[] = $new_list;
        }

        return $rtn;
    }

}
