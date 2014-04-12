<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_141 extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('sgr/tools');

        $this->anexo = '141';
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
         * @example .... "CUIT_PARTICIPE","CANT_GTIAS_VIGENTES","HIPOTECARIAS","PRENDARIAS","FIANZA"
         * ,"OTRAS","REAFIANZA","MORA_EN_DIAS","CLASIFICACION_DEUDOR"
         * */
        $defdna = array(
            1 => 'CUIT_PARTICIPE',
            2 => 'CANT_GTIAS_VIGENTES',
            3 => 'HIPOTECARIAS',
            4 => 'PRENDARIAS',
            5 => 'FIANZA',
            6 => 'OTRAS',
            7 => 'REAFIANZA',
            8 => 'MORA_EN_DIAS',
            9 => 'CLASIFICACION_DEUDOR'
        );


        $insertarr = array();
        foreach ($defdna as $key => $value) {
            $insertarr[$value] = $parameter[$key];

            /* STRING */
            $insertarr["CUIT_PARTICIPE"] = (string) $insertarr["CUIT_PARTICIPE"];
            /* INTEGERS & FLOAT */
            $insertarr["CANT_GTIAS_VIGENTES"] = (int) $insertarr["CANT_GTIAS_VIGENTES"];
            $insertarr["HIPOTECARIAS"] = (float) $insertarr["HIPOTECARIAS"];
            $insertarr["PRENDARIAS"] = (float) $insertarr["PRENDARIAS"];
            $insertarr["FIANZA"] = (float) $insertarr["FIANZA"];
            $insertarr["OTRAS"] = (float) $insertarr["OTRAS"];
            $insertarr["REAFIANZA"] = (float) $insertarr["REAFIANZA"];
            $insertarr["MORA_EN_DIAS"] = (int) $insertarr["MORA_EN_DIAS"];
            $insertarr["CLASIFICACION_DEUDOR"] = (int) $insertarr["CLASIFICACION_DEUDOR"];
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
            'data' => '<tr>
        <td align="center" rowspan="2">C.U.I.T</td>
        <td align="center" rowspan="2">Socio Participe</td>
        <td colspan="2" align="center">Saldo de Garantias Vigentes<br></td>
        <td colspan="5" align="center">ContragarantÃ­as</td>
        <td align="center" rowspan="2">Saldos Reafianzados <br></td>
        <td colspan="4" align="center">Deudores por GarantÃ­as Abonadas<br></td>
    </tr>
    <tr>
        <td align="center">Cantidad de Garantias<br></td>
        <td align="center">Monto</td>
        <td align="center">Hipotecarias</td>
        <td align="center">Prendarias</td>
        <td align="center">Fianzas</td>
        <td align="center">Otras</td>
        <td align="center">Total</td>
        <td align="center">Monto adeudado a la fecha</td>
        <td align="center">Cantidad de garantÃ­as afrontadas <br></td>
        <td align="center">DÃ­as de mora</td>
        <td align="center">ClasificaciÃ³n del deudor <br></td>
    </tr>
    <tr>
        <td>C.U.I.T</td>
        <td>Socio Participe</td>
        <td>Cantidad de Garantias</td>
        <td>Monto</td>
        <td>Hipotecarias</td>
        <td>Prendarias</td>
        <td>Fianzas</td>
        <td>Otras</td>
        <td>Total</td>
        <td>Saldos Reafianzados </td>
        <td>Monto adeudado a la fecha</td>
        <td>Cantidad de garantÃ­as afrontadas</td>
        <td>DÃ­as de mora</td>
        <td>ClasificaciÃ³n del deudor</td>        
    </tr> ',
        );

        $tmpl_xls = array(
            'data' => '<tr><td>1</td>
        <td>2</td>
        <td>3</td>
        <td>4</td>
        <td>5</td>
        <td>6</td>
        <td>7</td>
        <td>8</td>
        <td>9</td>
        <td>10</td>
        <td>11</td>
        <td>12</td>
        <td>13</td>
        <td>14</td>
                            </tr>',
        );

        /* DRAW TABLE */
        $fix_table = '<thead>
<tr>
<th>';


        $template = ($xls) ? $tmpl_xls : $tmpl;
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
        $result = $this->mongo->sgr->$container->find($query)->sort(array('NUMERO_DE_APORTE' => 1));

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
            $new_list['col1'] = $list['CUIT_PARTICIPE'];
            $new_list['col2'] = $brand_name;
            if ($xls) {
                $new_list['col3'] = (float) (0);
                $new_list['col4'] = (float) (0);
                $new_list['col5'] = (float) ($list['HIPOTECARIAS']);
                $new_list['col6'] = (float) ($list['PRENDARIAS']);
                $new_list['col7'] = (float) ($list['FIANZA']);
                $new_list['col8'] = (float) ($list['OTRAS']);
                $new_list['col9'] = (float) (0);
                $new_list['col10'] = (float) ($list['REAFIANZA']);
                $new_list['col11'] = (float) (0);
                $new_list['col12'] = (float) (0);
            } else {
                $new_list['col3'] = money_format_custom(0);
                $new_list['col4'] = money_format_custom(0);
                $new_list['col5'] = money_format_custom($list['HIPOTECARIAS']);
                $new_list['col6'] = money_format_custom($list['PRENDARIAS']);
                $new_list['col7'] = money_format_custom($list['FIANZA']);
                $new_list['col8'] = money_format_custom($list['OTRAS']);
                $new_list['col9'] = money_format_custom(0);
                $new_list['col10'] = money_format_custom($list['REAFIANZA']);
                $new_list['col11'] = money_format_custom(0);
                $new_list['col12'] = money_format_custom(0);
            }
            $new_list['col13'] = $list['MORA_EN_DIAS'];
            $new_list['col14'] = $list['CLASIFICACION_DEUDOR'];
            $rtn[] = $new_list;
        }
        return $rtn;
    }

    function get_anexo_data_clean($anexo, $parameter, $xls = false) {

        $rtn = array();
        $col3 = array();
        $col4 = array();
        $col5 = array();
        $col6 = array();
        $col7 = array();
        $col8 = array();
        $col10 = array();
        $col11 = array();
        $col12 = array();

        $container = 'container.sgr_anexo_' . $anexo;
        $query = array("filename" => $parameter);
        $result = $this->mongo->sgr->$container->find($query);
        $new_list = array();
        foreach ($result as $list) {
            $col3[] = (float) 0;
            $col4[] = (float) 0;
            $col5[] = (float) ($list['HIPOTECARIAS']);
            $col6[] = (float) ($list['PRENDARIAS']);
            $col7[] = (float) ($list['FIANZA']);
            $col8[] = (float) ($list['OTRAS']);
            $col9[] = (float) 0;
            $col10[] = (float) 0;
            $col11[] = (float) 0;
            $col12[] = (float) 0;
            $col13[] = (float) 0;
            $col14[] = (float) 0;
        }


        $new_list = array();

        $new_list['col1'] = "<strong>TOTALES</strong>";
        $new_list['col2'] = "-";
        $new_list['col3'] = "-";
        $new_list['col4'] = "-";
        if ($xls) {
            $new_list['col5'] = (float)(array_sum($col5));
            $new_list['col6'] = (float)(array_sum($col6));
            $new_list['col7'] = (float)(array_sum($col7));
            $new_list['col8'] = (float)(array_sum($col8));
            $new_list['col9'] = (float)(array_sum($col9));
            $new_list['col10'] = (float)(array_sum($col10));
            $new_list['col11'] = (float)(array_sum($col11));
            $new_list['col12'] = (float)(array_sum($col12));
        } else {
            $new_list['col5'] = money_format_custom(array_sum($col5));
            $new_list['col6'] = money_format_custom(array_sum($col6));
            $new_list['col7'] = money_format_custom(array_sum($col7));
            $new_list['col8'] = money_format_custom(array_sum($col8));
            $new_list['col9'] = money_format_custom(array_sum($col9));
            $new_list['col10'] = money_format_custom(array_sum($col10));
            $new_list['col11'] = money_format_custom(array_sum($col11));
            $new_list['col12'] = money_format_custom(array_sum($col12));
        }


        $new_list['col13'] = "-";
        $new_list['col14'] = "-";
        $rtn[] = $new_list;


        return $rtn;
    }

}
