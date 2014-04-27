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

            /* DYNAMIC INFO */
            $this->load->model('padfyj_model');

            $model_125 = 'model_125';
            $this->load->Model($model_125);

            $model_12 = 'model_12';
            $this->load->Model($model_12);

            $model_14 = 'model_14';
            $this->load->Model($model_14);

            $model_201 = 'model_201';
            $this->load->Model($model_201);



            /* PARTNER DATA */
            $cuit = $list["CUIT_PARTICIPE"];
            $brand_name = $this->padfyj_model->search_name($list["CUIT_PARTICIPE"]);


            $partner_balance = $this->$model_125->get_balance_by_partner($cuit, $this->session->userdata['period']);

            $partner_balance_qty = ($partner_balance['count']) ? $partner_balance['count'] : 0;
            $partner_balance_amount = ($partner_balance['balance']) ? $partner_balance['balance'] : 0;

            /* GET ALL WARRANTIES BY PARTNER */
            $get_warranty_partner = $this->$model_12->get_warranty_partner_print($cuit, $this->session->userdata['period']);


            $col12_arr = array();

            $caida_result_arr = array();
            $recupero_result_arr = array();
            $inc_periodo_arr = array();
            $gasto_efectuado_periodo_arr = array();
            $recupero_gasto_periodo_arr = array();
            $gasto_incobrable_periodo_arr = array();

            foreach ($get_warranty_partner as $each) {
                $get_movement_data = $this->$model_14->get_movement_data_print($each[5214], $list['period']);

                $caida_result_arr[] = $get_movement_data['CAIDA'];
                $recupero_result_arr[] = $get_movement_data['RECUPERO'];
                $inc_periodo_arr[] = $get_movement_data['INCOBRABLES_PERIODO'];
                $gasto_efectuado_periodo_arr[] = $get_movement_data['GASTOS_EFECTUADOS_PERIODO'];
                $recupero_gasto_periodo_arr[] = $get_movement_data['RECUPERO_GASTOS_PERIODO'];
                $gasto_incobrable_periodo_arr[] = $get_movement_data['GASTOS_INCOBRABLES_PERIODO'];

                /* CALC COL12 */
                $caida_sum_tmp = array_sum($caida_result_arr);
                $recupero_sum_tmp = array_sum($recupero_result_arr);
                $inc_periodo_sum_tmp = array_sum($inc_periodo_arr);
                $sum_tmp = ($caida_sum_tmp - $recupero_sum_tmp) - $inc_periodo_sum_tmp;
                if ($sum_tmp != 0)
                    $col12_arr[] = $each[5214];
            }

            $caida_sum = array_sum($caida_result_arr);
            $recupero_sum = array_sum($recupero_result_arr);
            $inc_periodo_sum = array_sum($inc_periodo_arr);
            $gasto_efectuado_periodo_sum = array_sum($gasto_efectuado_periodo_arr);
            $recupero_gasto_periodo_sum = array_sum($recupero_gasto_periodo_arr);
            $gasto_incobrable_periodo_sum = array_sum($gasto_incobrable_periodo_arr);


            $sum_1 = ($caida_sum - $recupero_sum) - $inc_periodo_sum;
            $sum_2 = ($gasto_efectuado_periodo_sum - $recupero_gasto_periodo_sum) - $gasto_incobrable_periodo_sum;
            $sum_total = array_sum(array($sum_1, $sum_2));


            $col5 = (float) $insertarr['HIPOTECARIAS'];
            $col6 = (float) $insertarr['PRENDARIAS'];
            $col7 = (float) $insertarr['FIANZA'];
            $col8 = (float) $insertarr['OTRAS'];
            $col9 = array_sum(array($col5, $col6, $col7, $col8));

            $total = array_sum(array($col5, $col6, $col7, $col8));

            $insertarr["MONTO_ADEUDADO"] = $sum_total;
            $insertarr["CANTIDAD_GARANTIAS_AFRONTADAS"] = count($col12_arr);

            $insertarr["CANTIDAD_GARANTIAS"] = (int) $partner_balance_qty;
            $insertarr["MONTO_GARANTIAS"] = (float) $partner_balance_amount;
            $insertarr["TOTAL"] = (float) $total;
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
        <td colspan="2" align="center">Saldo de Garantías Vigentes<br></td>
        <td colspan="5" align="center">Contragarantías</td>
        <td align="center" rowspan="2">Saldos Reafianzados <br></td>
        <td colspan="4" align="center">Deudores por Garantías Abonadas<br></td>
    </tr>
    <tr>
        <td align="center">Cantidad de Garantías<br></td>
        <td align="center">Monto</td>
        <td align="center">Hipotecarias</td>
        <td align="center">Prendarias</td>
        <td align="center">Fianzas</td>
        <td align="center">Otras</td>
        <td align="center">Total</td>
        <td align="center">Monto adeudado a la fecha</td>
        <td align="center">Cantidad de garantías afrontadas <br></td>
        <td align="center">Días de mora</td>
        <td align="center">Clasificación del deudor <br></td>
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
        <td>11</td>
        <td>12</td>
        <td>13</td>
        <td>14</td>              
    </tr> ',
        );

        $tmpl_xls = array(
            'data' => '<tr><td>C.U.I.T</td>
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
        <td>Cantidad de garantias afrontadas</td>
        <td>Dias de mora</td>
        <td>Clasificacion del deudor</td>  
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

            $model_125 = 'model_125';
            $this->load->Model($model_125);

            $model_12 = 'model_12';
            $this->load->Model($model_12);

            $model_14 = 'model_14';
            $this->load->Model($model_14);

            $model_201 = 'model_201';
            $this->load->Model($model_201);



            /* PARTNER DATA */
            $cuit = $list["CUIT_PARTICIPE"];
            $brand_name = $this->padfyj_model->search_name($list["CUIT_PARTICIPE"]);


            $partner_balance = $this->$model_125->get_balance_by_partner($cuit, $list['period']);

            $col3 = ($partner_balance['count']) ? $partner_balance['count'] : 0;
            $col4 = ($partner_balance['balance']) ? $partner_balance['balance'] : 0;

            /* GET ALL WARRANTIES BY PARTNER */
            $get_warranty_partner = $this->$model_12->get_warranty_partner_print($cuit, $list['period']);


            $col12_arr = array();

            $caida_result_arr = array();
            $recupero_result_arr = array();
            $inc_periodo_arr = array();
            $gasto_efectuado_periodo_arr = array();
            $recupero_gasto_periodo_arr = array();
            $gasto_incobrable_periodo_arr = array();

            foreach ($get_warranty_partner as $each) {
                $get_movement_data = $this->$model_14->get_movement_data_print($each[5214], $list['period']);

                $caida_result_arr[] = $get_movement_data['CAIDA'];
                $recupero_result_arr[] = $get_movement_data['RECUPERO'];
                $inc_periodo_arr[] = $get_movement_data['INCOBRABLES_PERIODO'];
                $gasto_efectuado_periodo_arr[] = $get_movement_data['GASTOS_EFECTUADOS_PERIODO'];
                $recupero_gasto_periodo_arr[] = $get_movement_data['RECUPERO_GASTOS_PERIODO'];
                $gasto_incobrable_periodo_arr[] = $get_movement_data['GASTOS_INCOBRABLES_PERIODO'];

                /* CALC COL12 */
                $caida_sum_tmp = array_sum($caida_result_arr);
                $recupero_sum_tmp = array_sum($recupero_result_arr);
                $inc_periodo_sum_tmp = array_sum($inc_periodo_arr);
                $sum_tmp = ($caida_sum_tmp - $recupero_sum_tmp) - $inc_periodo_sum_tmp;
                if ($sum_tmp != 0)
                    $col12_arr[] = $each[5214];
            }

            $caida_sum = array_sum($caida_result_arr);
            $recupero_sum = array_sum($recupero_result_arr);
            $inc_periodo_sum = array_sum($inc_periodo_arr);
            $gasto_efectuado_periodo_sum = array_sum($gasto_efectuado_periodo_arr);
            $recupero_gasto_periodo_sum = array_sum($recupero_gasto_periodo_arr);
            $gasto_incobrable_periodo_sum = array_sum($gasto_incobrable_periodo_arr);


            $sum_1 = ($caida_sum - $recupero_sum) - $inc_periodo_sum;
            $sum_2 = ($gasto_efectuado_periodo_sum - $recupero_gasto_periodo_sum) - $gasto_incobrable_periodo_sum;
            $sum_total = array_sum(array($sum_1, $sum_2));


            $col5 = $list['HIPOTECARIAS'];
            $col6 = $list['PRENDARIAS'];
            $col7 = $list['FIANZA'];
            $col8 = $list['OTRAS'];
            $col9 = array_sum(array($col5, $col6, $col7, $col8));
            $col10 = $list['REAFIANZA'];
            $col11 = $sum_total;
            $col12 = count($col12_arr);

            $new_list = array();
            $new_list['col1'] = $cuit;
            $new_list['col2'] = $brand_name;
            if ($xls) {
                $new_list['col3'] = $col3;
                $new_list['col4'] = $col4;
                $new_list['col5'] = (float) $col5;
                $new_list['col6'] = (float) $col6;
                $new_list['col7'] = (float) $col7;
                $new_list['col8'] = (float) $col8;
                $new_list['col9'] = (float) $col9;
                $new_list['col10'] = (float) $col10;
                $new_list['col11'] = (float) $col11;
                $new_list['col12'] = $col12;
            } else {
                $new_list['col3'] = $col3;
                $new_list['col4'] = money_format_custom($col4);
                $new_list['col5'] = money_format_custom($col5);
                $new_list['col6'] = money_format_custom($col6);
                $new_list['col7'] = money_format_custom($col7);
                $new_list['col8'] = money_format_custom($col8);
                $new_list['col9'] = money_format_custom($col9);
                $new_list['col10'] = money_format_custom($col10);
                $new_list['col11'] = money_format_custom($col11);
                $new_list['col12'] = $col12;
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

            /*
             * Vars 								
             */
            $this->load->model('padfyj_model');

            $model_125 = 'model_125';
            $this->load->Model($model_125);

            $model_12 = 'model_12';
            $this->load->Model($model_12);

            $model_14 = 'model_14';
            $this->load->Model($model_14);

            $model_201 = 'model_201';
            $this->load->Model($model_201);


            $get_movement_data = $this->$model_201->get_movement_data_print($list['NUMERO_DE_APORTE'], $list['period']);
            $partener_info = $this->$model_201->get_input_number_print($list['NUMERO_DE_APORTE'], $list['period']);
            foreach ($partener_info as $partner) {
                var_dump($partner);
            }



            /* PARTNER DATA */
            $cuit = $list["CUIT_PARTICIPE"];
            $brand_name = $this->padfyj_model->search_name($list["CUIT_PARTICIPE"]);

            $retiros = array_sum(array($get_movement_data['RETIRO'], $get_movement_data['RETIRO_DE_RENDIMIENTOS']));
            $saldo = $get_movement_data['APORTE'] - $retiros;
            $disponible = $saldo - (float) $list['CONTINGENTE_PROPORCIONAL_ASIGNADO'];


            $partner_balance = $this->$model_125->get_balance_by_partner($cuit, $list['period']);
            $col3_val = ($partner_balance['count']) ? $partner_balance['count'] : 0;
            $col4_val = ($partner_balance['balance']) ? $partner_balance['balance'] : 0;

            /* GET ALL WARRANTIES BY PARTNER */
            $get_warranty_partner = $this->$model_12->get_warranty_partner_print($cuit, $list['period']);


            $col12_arr = array();

            $caida_result_arr = array();
            $recupero_result_arr = array();
            $inc_periodo_arr = array();
            $gasto_efectuado_periodo_arr = array();
            $recupero_gasto_periodo_arr = array();
            $gasto_incobrable_periodo_arr = array();

            foreach ($get_warranty_partner as $each) {
                $get_movement_data = $this->$model_14->get_movement_data_print($each[5214], $list['period']);

                $caida_result_arr[] = $get_movement_data['CAIDA'];
                $recupero_result_arr[] = $get_movement_data['RECUPERO'];
                $inc_periodo_arr[] = $get_movement_data['INCOBRABLES_PERIODO'];
                $gasto_efectuado_periodo_arr[] = $get_movement_data['GASTOS_EFECTUADOS_PERIODO'];
                $recupero_gasto_periodo_arr[] = $get_movement_data['RECUPERO_GASTOS_PERIODO'];
                $gasto_incobrable_periodo_arr[] = $get_movement_data['GASTOS_INCOBRABLES_PERIODO'];

                /* CALC COL12 */
                $caida_sum_tmp = array_sum($caida_result_arr);
                $recupero_sum_tmp = array_sum($recupero_result_arr);
                $inc_periodo_sum_tmp = array_sum($inc_periodo_arr);
                $sum_tmp = ($caida_sum_tmp - $recupero_sum_tmp) - $inc_periodo_sum_tmp;
                if ($sum_tmp != 0)
                    $col12_arr[] = $each[5214];
            }

            $caida_sum = array_sum($caida_result_arr);
            $recupero_sum = array_sum($recupero_result_arr);
            $inc_periodo_sum = array_sum($inc_periodo_arr);
            $gasto_efectuado_periodo_sum = array_sum($gasto_efectuado_periodo_arr);
            $recupero_gasto_periodo_sum = array_sum($recupero_gasto_periodo_arr);
            $gasto_incobrable_periodo_sum = array_sum($gasto_incobrable_periodo_arr);


            $sum_1 = ($caida_sum - $recupero_sum) - $inc_periodo_sum;
            $sum_2 = ($gasto_efectuado_periodo_sum - $recupero_gasto_periodo_sum) - $gasto_incobrable_periodo_sum;
            $sum_total = array_sum(array($sum_1, $sum_2));


            $col5_val = $list['HIPOTECARIAS'];
            $col6_val = $list['PRENDARIAS'];
            $col7_val = $list['FIANZA'];
            $col8_val = $list['OTRAS'];

            $col9_val = array_sum(array($col5, $col6, $col7, $col8));
            $col11_val = $sum_total;

            $col12_val = count($col12_arr);

            $col3[] = (float) $col3_val;
            $col4[] = (float) $col4_val;
            $col5[] = (float) $col5_val;
            $col6[] = (float) $col6_val;
            $col7[] = (float) $col7_val;
            $col8[] = (float) $col8_val;
            $col9[] = (float) $col9_val;
            $col10[] = (float) $col10_val;
            $col11[] = (float) $col11_val;
            $col12[] = (float) $col12_val;
        }


        $new_list = array();

        $new_list['col1'] = "<strong>TOTALES</strong>";
        $new_list['col2'] = "-";
        if ($xls) {
            $new_list['col3'] = array_sum($col3);
            $new_list['col4'] = (float) (array_sum($col4));
            $new_list['col5'] = (float) (array_sum($col5));
            $new_list['col6'] = (float) (array_sum($col6));
            $new_list['col7'] = (float) (array_sum($col7));
            $new_list['col8'] = (float) (array_sum($col8));
            $new_list['col9'] = (float) (array_sum($col9));
            $new_list['col10'] = (float) (array_sum($col10));
            $new_list['col11'] = (float) (array_sum($col11));
            $new_list['col12'] = array_sum($col12);
        } else {
            $new_list['col3'] = array_sum($col3);
            $new_list['col4'] = money_format_custom(array_sum($col4));
            $new_list['col5'] = money_format_custom(array_sum($col5));
            $new_list['col6'] = money_format_custom(array_sum($col6));
            $new_list['col7'] = money_format_custom(array_sum($col7));
            $new_list['col8'] = money_format_custom(array_sum($col8));
            $new_list['col9'] = money_format_custom(array_sum($col9));
            $new_list['col10'] = money_format_custom(array_sum($col10));
            $new_list['col11'] = money_format_custom(array_sum($col11));
            $new_list['col12'] = array_sum($col12);
        }


        $new_list['col13'] = "-";
        $new_list['col14'] = "-";
        $rtn[] = $new_list;


        return $rtn;
    }

    function partners_debtors_to_top($period) {
        $anexo = $this->anexo;
        /* GET ACTIVE ANEXOS */
        $container_period = 'container.sgr_periodos';
        $container = 'container.sgr_anexo_' . $anexo;


        $result = $this->sgr_model->get_active_one($anexo, period_before($period)); //exclude actual


        $rtn = array();
        foreach ($result as $each) {

            $new_query = array(
                'filename' => $each['filename']
            );


            $partners = $this->mongo->sgr->$container->find($new_query);

            foreach ($partners as $partner) {
                if ($partner['MORA_EN_DIAS'])
                    $rtn[] = $partner['CUIT_PARTICIPE'];
            }
        }
        return (count(array_unique($rtn)));
    }

}
