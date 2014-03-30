<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_202 extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('sgr/tools');

        $this->anexo = '202';
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
         * @example
         * NUMERO_DE_APORTE	
         * CONTINGENTE_PROPORCIONAL_ASIGNADO	
         * DEUDA_PROPORCIONAL_ASIGNADA	
         * RENDIMIENTO_ASIGNADO
         * */
        $defdna = array(
            1 => 'NUMERO_DE_APORTE',
            2 => 'CONTINGENTE_PROPORCIONAL_ASIGNADO',
            3 => 'DEUDA_PROPORCIONAL_ASIGNADA',
            4 => 'RENDIMIENTO_ASIGNADO'
        );

        $insertarr = array();
        foreach ($defdna as $key => $value) {
            $insertarr[$value] = $parameter[$key];

            /* INT & FLOAT */
            $insertarr["NUMERO_DE_APORTE"] = (int) $insertarr["NUMERO_DE_APORTE"];
            $insertarr["CONTINGENTE_PROPORCIONAL_ASIGNADO"] = (float) $insertarr["CONTINGENTE_PROPORCIONAL_ASIGNADO"];
            $insertarr["DEUDA_PROPORCIONAL_ASIGNADA"] = (float) $insertarr["DEUDA_PROPORCIONAL_ASIGNADA"];
            $insertarr["RENDIMIENTO_ASIGNADO"] = (float) $insertarr["RENDIMIENTO_ASIGNADO"];
        }
        return $insertarr;
    }

    function save($parameter) {
        $period = $this->session->userdata['period'];
        $container = 'container.sgr_anexo_' . $this->anexo;
        $id = $this->app->genid_sgr($container);

        $parameter['period'] = $period;
        $parameter['origin'] = 2013;

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
            'data' => '<tr><td align="center">Número de Aporte</td>
                                <td align="center">Nombre o Razón Social del Socio Protector</td>
                                <td align="center">C.U.I.T.</td>
                                <td align="center">Saldo del Aporte</td>
                                <td align="center">Contingente Proporcional Asignado</td>
                                <td align="center">Deuda Proporcional Asignada</td>
                                <td align="center">Saldo del Aporte Disponible</td>
                                <td align="center">Rendimiento Asignado</td>
                            </tr>
                            <tr>
                                <th>1</th>
                                <th>2</th>
                                <th>3</th>
                                <th>4</th>
                                <th>5</th>
                                <th>6</th>
                                <th>7</th>
                                <th>8</th>                                                
                            </tr> ',
        );
        
        $tmpl_xls = array(
            'data' => '<tr><td>Numero de Aporte</td>
                                <td align="center">Nombre o Razon Social del Socio Protector</td>
                                <td>C.U.I.T.</td>
                                <td>Saldo del Aporte</td>
                                <td>Contingente Proporcional Asignado</td>
                                <td>Deuda Proporcional Asignada</td>
                                <td>Saldo del Aporte Disponible</td>
                                <td>Rendimiento Asignado</td>
                            </tr>',
        );

        /* DRAW TABLE */
        $fix_table = '<thead>
<tr>
<th>';
        
        
        $template = ($xls)? $tmpl_xls : $tmpl;
        $data = array($template);
        $anexoValues = $this->get_anexo_data($anexo, $parameter, $xls);
        $anexoValues2 = $this->get_anexo_data_clean($anexo, $parameter, $xls);
        $anexoValues = array_merge($anexoValues, $anexoValues2);
        foreach ($anexoValues as $values) {
            $data[] = array_values($values);
        }

        $this->load->library('table_custom');
        $newTable = str_replace($fix_table, '<thead>', $this->table_custom->generate($data));
        return $newTable;
    }

    function get_anexo_data($anexo, $parameter, $xls = false) {


        header('Content-type: text/html; charset=UTF-8');
        $rtn = array();
        $container = 'container.sgr_anexo_' . $anexo;
        $query = array("filename" => $parameter);
        $result = $this->mongo->sgr->$container->find($query)->sort(array('NUMERO_DE_APORTE' => -1));

        foreach ($result as $list) {
            /*
             * Vars 								
             */
            $this->load->model('padfyj_model');


            $model_201 = 'model_201';
            $this->load->Model($model_201);

            
            $get_movement_data = $this->$model_201->get_movement_data_print($list['NUMERO_DE_APORTE'], $list['period']);
            $partener_info = $this->$model_201->get_input_number_print($list['NUMERO_DE_APORTE'], $list['period']);
            foreach ($partener_info as $partner) {
                $cuit = $partner["CUIT_PROTECTOR"];
                $brand_name = $this->padfyj_model->search_name($partner["CUIT_PROTECTOR"]);
            }
            $retiros = array_sum(array($get_movement_data['RETIRO'], $get_movement_data['RETIRO_DE_RENDIMIENTOS']));
            $saldo = $get_movement_data['APORTE'] - $retiros;
            $disponible = $saldo - (float) $list['CONTINGENTE_PROPORCIONAL_ASIGNADO'];

            $new_list = array();
            $new_list['NUMERO_DE_APORTE'] = $list['NUMERO_DE_APORTE']; //$list['NUMERO_DE_APORTE'];
            $new_list['RAZON_SOCIAL'] = $brand_name;
            $new_list['CUIT'] = $cuit;
            if ($xls) {
                $new_list['SALDO_APORTE'] = $saldo;
                $new_list['CONTINGENTE_PROPORCIONAL_ASIGNADO'] = (float) $list['CONTINGENTE_PROPORCIONAL_ASIGNADO'];
                $new_list['DEUDA_PROPORCIONAL_ASIGNADA'] = (float) $list['DEUDA_PROPORCIONAL_ASIGNADA'];
                $new_list['SALDO_APORTE_DISPONIBLE'] = $disponible;
                $new_list['RENDIMIENTO_ASIGNADO'] = (float) $list['RENDIMIENTO_ASIGNADO'];
            } else {
                $new_list['SALDO_APORTE'] = money_format_custom($saldo);
                $new_list['CONTINGENTE_PROPORCIONAL_ASIGNADO'] = money_format_custom((float) $list['CONTINGENTE_PROPORCIONAL_ASIGNADO']);
                $new_list['DEUDA_PROPORCIONAL_ASIGNADA'] = money_format_custom((float) $list['DEUDA_PROPORCIONAL_ASIGNADA']);
                $new_list['SALDO_APORTE_DISPONIBLE'] = money_format_custom($disponible);
                $new_list['RENDIMIENTO_ASIGNADO'] = money_format_custom((float) $list['RENDIMIENTO_ASIGNADO']);
            }
            $rtn[] = $new_list;
        }
        return $rtn;
    }


    function get_anexo_data_clean($anexo, $parameter, $xls=false) {

        $rtn = array();
        $col4 = array();
        $col5 = array();
        $col6 = array();
        $col7 = array();
        $col8 = array();

        $container = 'container.sgr_anexo_' . $anexo;
        $query = array("filename" => $parameter);
        $result = $this->mongo->sgr->$container->find($query);
        $new_list = array();
        foreach ($result as $list) {

            $model_201 = 'model_201';
            $this->load->Model($model_201);
            $get_movement_data = $this->$model_201->get_movement_data_print($list['NUMERO_DE_APORTE'], $list['period']);
            $retiros = array_sum(array($get_movement_data['RETIRO'], $get_movement_data['RETIRO_DE_RENDIMIENTOS']));
            $saldo = $get_movement_data['APORTE'] - $retiros;
            $disponible = $saldo - (float) $list['CONTINGENTE_PROPORCIONAL_ASIGNADO'];

            $col4[] = $saldo;
            $col5[] = (float) $list['CONTINGENTE_PROPORCIONAL_ASIGNADO'];
            $col6[] = (float) $list['DEUDA_PROPORCIONAL_ASIGNADA'];
            $col7[] = $disponible;
            $col8[] = (float) $list['RENDIMIENTO_ASIGNADO'];
        }


        $new_list = array();
        $new_list['NUMERO_DE_APORTE'] = "<strong>TOTALES</strong>"; //$list['NUMERO_DE_APORTE'];
        $new_list['RAZON_SOCIAL'] = "-";
        $new_list['CUIT'] = "-";
        if ($xls) {          
            $new_list['SALDO_APORTE'] = (array_sum($col4));
            $new_list['CONTINGENTE_PROPORCIONAL_ASIGNADO'] = (array_sum($col5));
            $new_list['DEUDA_PROPORCIONAL_ASIGNADA'] = (array_sum($col6));
            $new_list['SALDO_APORTE_DISPONIBLE'] = (array_sum($col7));
            $new_list['RENDIMIENTO_ASIGNADO'] = (array_sum($col8));
        } else {
            $new_list['SALDO_APORTE'] = money_format_custom(array_sum($col4));
            $new_list['CONTINGENTE_PROPORCIONAL_ASIGNADO'] = money_format_custom(array_sum($col5));
            $new_list['DEUDA_PROPORCIONAL_ASIGNADA'] = money_format_custom(array_sum($col6));
            $new_list['SALDO_APORTE_DISPONIBLE'] = money_format_custom(array_sum($col7));
            $new_list['RENDIMIENTO_ASIGNADO'] = money_format_custom(array_sum($col8));
        }
        $rtn[] = $new_list;


        return $rtn;
    }

}
