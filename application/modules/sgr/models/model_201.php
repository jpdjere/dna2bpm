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
            /* STRING */
            $insertarr["CUIT_PROTECTOR"] = (string) $insertarr["CUIT_PROTECTOR"];

            /* INTEGERS  & FLOATS */
            $insertarr["APORTE"] = (float) $insertarr["APORTE"];
            $insertarr["RETIRO"] = (float) $insertarr["RETIRO"];
            $insertarr["RETENCION_POR_CONTINGENTE"] = (float) $insertarr["RETENCION_POR_CONTINGENTE"];
            $insertarr["RETIRO_DE_RENDIMIENTOS"] = (float) $insertarr["RETIRO_DE_RENDIMIENTOS"];

            $insertarr["NRO_ACTA"] = (int) $insertarr["NRO_ACTA"];
            $insertarr["NUMERO_DE_APORTE"] = (int) $insertarr["NUMERO_DE_APORTE"];

           
        }
        return $insertarr;
    }

    function save($parameter) {
        $period = $this->session->userdata['period'];
        $container = 'container.sgr_anexo_' . $this->anexo;
        $id = $this->app->genid_sgr($container);

        /* FIX DATE */
        $parameter['FECHA_MOVIMIENTO'] = new MongoDate(strtotime(translate_for_mongo($parameter['FECHA_MOVIMIENTO'])));
        $parameter['FECHA_ACTA'] = new MongoDate(strtotime(translate_for_mongo($parameter['FECHA_ACTA'])));

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

    function get_anexo_info($anexo, $parameter, $xls = false) {
        $tmpl = array(
            'data' => '<tr><td align="center" rowspan="2">Número de Aporte</td>
                                <td align="center" rowspan="2">Fecha de Movimiento</td>
                                <td align="center" rowspan="2">Nombre o Razón Social del Socio Protector</td>
                                <td align="center" rowspan="2">C.U.I.T. Socio Protector</td>
                                <td align="center" rowspan="2">Monto del Aporte</td>
                                <td align="center" rowspan="2">Monto del Retiro</td>
                                <td align="center" rowspan="2">Fecha del Aporte Original</td>
                                <td align="center" rowspan="2">Monto del Aporte Original</td>                                
                                <td align="center" rowspan="2">Retencion por Contingente</td>
                                <td align="center" rowspan="2">Retiro de Rendimientos</td>                                
                                <td align="center" rowspan="2">Especie</td>
                                <td align="center" colspan="4">Cuenta Origen</td>
                                <td align="center" colspan="4">Cuenta Destino</td>
                                <td align="center" colspan="2">Acta de Autorización</td></tr>
    <tr>
        <td>Titular</td>
        <td>N°</td>
        <td>Entidad</td>
        <td>Entidad Depositaria</td>
        <td>Titular</td>
        <td>N°</td>
        <td>Entidad</td>
        <td>Entidad Depositaria</td>
        <td>Fecha</td>
        <td>Número</td>
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
        <td>15</td>
        <td>16</td>
        <td>17</td>
        <td>18</td>
        <td>19</td>
        <td>20</td>
        <td>21</td>
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



        $template = ($xls) ? $tmpl_xls : $tmpl;
        $data = array($template);
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

    function get_anexo_data_tmp($anexo, $parameter) {

        $rtn = array();
        $container = 'container.sgr_anexo_' . $anexo;
        $fields = array('NUMERO_DE_APORTE',
            'FECHA_MOVIMIENTO',
            'CUIT_PROTECTOR',
            'APORTE',
            'RETIRO',
            'RETENCION_POR_CONTINGENTE',
            'RETIRO_DE_RENDIMIENTOS', 'filename', 'period', 'sgr_id', 'origin');
        $query = array("filename" => $parameter);
        $result = $this->mongo->sgr->$container->find($query, $fields);

        foreach ($result as $list) {
            $rtn[] = $list;
        }

        return $rtn;
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


            $get_movement_data = $this->$model_201->get_original_aporte_print($list['NUMERO_DE_APORTE'], $list['period']);
            $partener_info = $this->$model_201->get_input_number_print($list['NUMERO_DE_APORTE'], $list['period']);
            foreach ($partener_info as $partner) {
                $cuit = $partner["CUIT_PROTECTOR"];
                $brand_name = $this->padfyj_model->search_name($partner["CUIT_PROTECTOR"]);
            }

            $new_list = array();
            $new_list['NUMERO_DE_APORTE'] = $list['NUMERO_DE_APORTE'];
            $new_list['FECHA_MOVIMIENTO'] = mongodate_to_print($list['FECHA_MOVIMIENTO']);
            $new_list['RAZON_SOCIAL'] = $brand_name;
            $new_list['CUIT_PROTECTOR'] = $cuit;
            $new_list['APORTE'] = money_format_custom($list['APORTE']);
            $new_list['RETIRO'] = money_format_custom($list['RETIRO']);
            $new_list['FECHA_APORTE_ORIGINAL'] = mongodate_to_print($get_movement_data['FECHA_MOVIMIENTO']);
            $new_list['APORTE_ORIGINAL'] = money_format_custom($get_movement_data['APORTE']);

            $new_list['RETENCION_POR_CONTINGENTE'] = $get_movement_data['RETENCION_POR_CONTINGENTE'];
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
            $new_list['FECHA_ACTA'] = mongodate_to_print($list['FECHA_ACTA']);
            $new_list['NRO_ACTA'] = $list['NRO_ACTA'];
            $rtn[] = $new_list;
        }
        return $rtn;
    }

    function get_anexo_data_clean($anexo, $parameter, $xls = false) {

        $rtn = array();

        $col6 = array();
        $col8 = array();
        $col10 = array();

        $container = 'container.sgr_anexo_' . $anexo;
        $query = array("filename" => $parameter);
        $result = $this->mongo->sgr->$container->find($query);
        $new_list = array();
        foreach ($result as $list) {

            $model_201 = 'model_201';
            $this->load->Model($model_201);
            $get_movement_data = $this->$model_201->get_original_aporte_print($list['NUMERO_DE_APORTE'], $list['period']);

            $col6[] = (float) $list['RETIRO'];
            $col8[] = (float) $get_movement_data['APORTE'];
            $col10[] = (float) $list['RETIRO_DE_RENDIMIENTOS'];
        }


        $new_list = array();
        $new_list['NUMERO_DE_APORTE'] = "<strong>TOTALES</strong>";
        $new_list['FECHA_MOVIMIENTO'] = "-";
        $new_list['RAZON_SOCIAL'] = "-";
        $new_list['CUIT_PROTECTOR'] = "-";
        $new_list['APORTE'] = "-";
        $new_list['RETIRO'] = money_format_custom(array_sum($col6));
        $new_list['APORTE_ORIGINAL'] = "-";
        $new_list['FECHA_APORTE_ORIGINAL'] = money_format_custom(array_sum($col8));
        $new_list['RETENCION_POR_CONTINGENTE'] = "-";
        $new_list['RETIRO_DE_RENDIMIENTOS'] = money_format_custom(array_sum($col10));
        $new_list['ESPECIE'] = "-";
        $new_list['TITULAR_ORIG'] = "-";
        $new_list['NRO_CTA_OR'] = "-";
        $new_list['ENTIDAD_OR'] = "-";
        $new_list['ENT_DEP_OR'] = "-";
        $new_list['TITULAR_DEST'] = "-";
        $new_list['NRO_DEST'] = "-";
        $new_list['ENTIDAD_DEST'] = "-";
        $new_list['ENT_DEP_DEST'] = "-";
        $new_list['FECHA_ACTA'] = "-";
        $new_list['NRO_ACTA'] = "-";

        $rtn[] = $new_list;


        return $rtn;
    }

    function get_anexo_data_left($period) {

        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;

        $rtn = array();

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active($anexo);

        /* FIND ANEXO */
        foreach ($result as $list) {
            /* APORTE */
            $new_query = array(
                'sgr_id' => $list['sgr_id'],
                'filename' => $list['filename']
            );


            $io_result = $this->mongo->sgr->$container->find($new_query);
            foreach ($io_result as $data) {
                $rtn[] = $data;
            }
        }


        return $rtn;
    }

    function exist_input_number($code) {

        $anexo = $this->anexo;
        $period_value = $this->session->userdata['period'];
        $period = 'container.sgr_periodos';
        $container = 'container.sgr_anexo_' . $anexo;

        $input_result_arr = array();
        $output_result_arr = array();

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active($anexo, $period_value);

        /* FIND ANEXO */
        foreach ($result as $list) {
            /* APORTE */
            $new_query = array(
                'NUMERO_DE_APORTE' => (int) $code,
                'sgr_id' => $list['sgr_id'],
                'filename' => $list['filename']
            );


            $io_result = $this->mongo->sgr->$container->find($new_query);
            foreach ($io_result as $data) {                
                if ($data) {
                    return true;
                }
            }
        }
    }

    function get_input_number($code) {

        $anexo = $this->anexo;
        $period_value = $this->session->userdata['period'];
        $period = 'container.sgr_periodos';
        $container = 'container.sgr_anexo_' . $anexo;

        $input_result_arr = array();
        $output_result_arr = array();

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active($anexo, $period_value);

        /* FIND ANEXO */
        foreach ($result as $list) {
            /* APORTE */
            $new_query = array(
                'NUMERO_DE_APORTE' => (int) $code,
                'sgr_id' => $list['sgr_id'],
                'filename' => $list['filename']
            );


            $io_result = $this->mongo->sgr->$container->find($new_query);
            foreach ($io_result as $data) {
                if ($data['APORTE']) {
                    $input_result_arr[] = (int) $data['APORTE'];
                }
                if ($data['RETIRO']) {
                    $output_result_arr[] = (int) $data['RETIRO'];
                }
            }
        }

        $input_sum = array_sum($input_result_arr);
        $output_sum = array_sum($output_result_arr);
        $balance = $input_sum - $output_sum;
        return $balance;
    }

    function get_input_number_left($code) {

        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;

        $input_result_arr = array();
        $output_result_arr = array();

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active($anexo);

        /* FIND ANEXO */
        foreach ($result as $list) {
            /* APORTE */
            $new_query = array(
                'NUMERO_DE_APORTE' => (int) $code,
                'sgr_id' => $list['sgr_id'],
                'filename' => $list['filename']
            );


            $io_result = $this->mongo->sgr->$container->find($new_query);
            foreach ($io_result as $data) {

                if ($data['APORTE']) {
                    // var_dump($input_result['APORTE']);
                    $input_result_arr[] = (int) $data['APORTE'];
                }
                if ($data['RETIRO']) {
                    // var_dump($input_result['RETIRO']);
                    $output_result_arr[] = (int) $data['RETIRO'];
                }
            }
        }

        $input_sum = array_sum($input_result_arr);
        $output_sum = array_sum($output_result_arr);
        $balance = $input_sum - $output_sum;
        return $balance;
    }

    function get_input_number_print($code, $period_date) {
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;

        $rtn = array();

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active_print($anexo, $period_date);
        /* FIND ANEXO */
        foreach ($result as $list) {
            $new_query = array(
                'NUMERO_DE_APORTE' => (int) $code,
                'sgr_id' => $list['sgr_id'],
                'filename' => $list['filename']
            );
            $io_result = $this->mongo->sgr->$container->find($new_query);
            foreach ($io_result as $data) {
                $rtn[] = $data;
            }
        }
        return $rtn;
    }

    function get_last_input_number($code) {
        $anexo = $this->anexo;
        $period = 'container.sgr_periodos';
        $container = 'container.sgr_anexo_' . $anexo;
        $period_value = $this->session->userdata['period'];

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active_last_rec($anexo, $period_value);

        /* FIND ANEXO */
        foreach ($result as $list) {
            $new_query = array(
                'sgr_id' => $list['sgr_id'],
                'filename' => $list['filename']
            );

            $new_result = $this->mongo->sgr->$container->find($new_query)->sort(array('NUMERO_DE_APORTE' => -1))->limit(1);
            foreach ($new_result as $new_list) {
                return $new_list['NUMERO_DE_APORTE'];
            }
        }

        $result = array_sum($nresult_arr);
        return $result;
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

    function get_last_input() {
        $anexo = $this->anexo;
        $period_value = $this->session->userdata['period'];
        $period = 'container.sgr_periodos';
        $container = 'container.sgr_anexo_' . $anexo;

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active($anexo, $period_value);

        /* FIND ANEXO */
        foreach ($result as $list) {
            $new_query = array(
                'sgr_id' => $list['sgr_id'],
                'filename' => $list['filename']
            );
            $new_result = $this->mongo->sgr->$container->find($new_query)->sort(array('NUMERO_DE_APORTE' => -1))->limit(1);
            foreach ($new_result as $new_list) {
                return $new_list['NUMERO_DE_APORTE'];
            }
        }
    }

    function get_movement_data($nro) {
        $anexo = $this->anexo;
        $token = $this->idu;
        $container = 'container.sgr_anexo_' . $anexo . '_' . $token . '_tmp';
        $period_value = $this->session->userdata['period'];


        $aporte_result_arr = array();
        $retiro_result_arr = array();
        $rendimientos_result_arr = array();

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active_tmp($anexo, $period_value);

        /* FIND ANEXO */
        foreach ($result as $list) {
            $new_query = array(
                'sgr_id' => $list['sgr_id'],
                'filename' => $list['filename'],
                'NUMERO_DE_APORTE' => $nro
            );

            $movement_result = $this->mongo->sgr->$container->find($new_query);
            foreach ($movement_result as $movement) {
                $aporte_result_arr[] = $movement['APORTE'];
                $retiro_result_arr[] = $movement['RETIRO'];
                $rendimientos_result_arr[] = $movement['RETIRO_DE_RENDIMIENTOS'];
            }
        }


        $aporte_sum = array_sum($aporte_result_arr);
        $retiro_sum = array_sum($retiro_result_arr);
        $rendimientos_sum = array_sum($rendimientos_result_arr);

        $return_arr = array(
            'APORTE' => $aporte_sum,
            'RETIRO' => $retiro_sum,
            'RETIRO_DE_RENDIMIENTOS' => $rendimientos_sum
        );
        return $return_arr;
    }

    function get_movement_recursive($nro) {
        $anexo = $this->anexo;
        $period_value = $this->session->userdata['period'];
        $period = 'container.sgr_periodos';
        $container = 'container.sgr_anexo_' . $anexo;


        $aporte_result_arr = array();
        $retiro_result_arr = array();
        $rendimientos_result_arr = array();

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active_exclude_this($anexo, $period_value);

        /* FIND ANEXO */
        foreach ($result as $list) {
            $new_query = array(
                'sgr_id' => $list['sgr_id'],
                'filename' => $list['filename'],
                'NUMERO_DE_APORTE' => $nro
            );

            $movement_result = $this->mongo->sgr->$container->find($new_query);
            foreach ($movement_result as $movement) {
                $aporte_result_arr[] = $movement['APORTE'];
                $retiro_result_arr[] = $movement['RETIRO'];
                $rendimientos_result_arr[] = $movement['RETIRO_DE_RENDIMIENTOS'];
            }
        }


        $aporte_sum = array_sum($aporte_result_arr);
        $retiro_sum = array_sum($retiro_result_arr);
        $rendimientos_sum = array_sum($rendimientos_result_arr);

        $return_arr = array(
            'APORTE' => $aporte_sum,
            'RETIRO' => $retiro_sum,
            'RETIRO_DE_RENDIMIENTOS' => $rendimientos_sum
        );
        return $return_arr;
    }

    function get_movement_data_print($nro, $period_date) {
        $anexo = $this->anexo;

        $period = 'container.sgr_periodos';
        $container = 'container.sgr_anexo_' . $anexo;
        $nro = (int) $nro;

        $aporte_result_arr = array();
        $retiro_result_arr = array();
        $rendimientos_result_arr = array();

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active_print($anexo, $period_date);

        /* FIND ANEXO */
        foreach ($result as $list) {
            $new_query = array(
                'sgr_id' => $list['sgr_id'],
                'filename' => $list['filename'],
                'NUMERO_DE_APORTE' => $nro
            );

            $movement_result = $this->mongo->sgr->$container->find($new_query);
            foreach ($movement_result as $movement) {
                $aporte_result_arr[] = $movement['APORTE'];
                $retiro_result_arr[] = $movement['RETIRO'];
                $rendimientos_result_arr[] = $movement['RETIRO_DE_RENDIMIENTOS'];
            }
        }


        $aporte_sum = array_sum($aporte_result_arr);
        $retiro_sum = array_sum($retiro_result_arr);
        $rendimientos_sum = array_sum($rendimientos_result_arr);

        $return_arr = array(
            'APORTE' => $aporte_sum,
            'RETIRO' => $retiro_sum,
            'RETIRO_DE_RENDIMIENTOS' => $rendimientos_sum
        );
        return $return_arr;
    }

    function get_original_aporte_print($nro, $period_date) {
        $anexo = $this->anexo;

        $period = 'container.sgr_periodos';
        $container = 'container.sgr_anexo_' . $anexo;
        $nro = (int) $nro;



        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active_print($anexo, $period_date);

        /* FIND ANEXO */
        foreach ($result as $list) {
            $new_query = array(
                'sgr_id' => $list['sgr_id'],
                'filename' => $list['filename'],
                'NUMERO_DE_APORTE' => $nro
            );

            $movement_result = $this->mongo->sgr->$container->findOne($new_query);
            if ($movement_result['APORTE'])
                return $movement_result;
        }
    }

    function get_tmp_movement_data($nro) {
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $this->idu . '_tmp';

        $number_result_arr = array();
        $aporte_result_arr = array();
        $retiro_result_arr = array();
        $rendimientos_result_arr = array();

        $token = $this->idu;
        $new_query = array(
            'NUMERO_DE_APORTE' => $nro,
            'TOKEN' => $token
        );

        $movement_result = $this->mongo->sgr->$container->find($new_query);
        foreach ($movement_result as $movement) {

            if ($movement['APORTE']) {
                $number_result_arr[] = 1;
            }
            $aporte_result_arr[] = $movement['APORTE'];
            $retiro_result_arr[] = $movement['RETIRO'];
            $rendimientos_result_arr[] = $movement['RETIRO_DE_RENDIMIENTOS'];
        }

        $number_sum = array_sum($number_result_arr);
        $aporte_sum = array_sum($aporte_result_arr);
        $retiro_sum = array_sum($retiro_result_arr);
        $rendimientos_sum = array_sum($rendimientos_result_arr);

        $return_arr = array(
            'TOTAL' => $number_sum,
            'APORTE' => $aporte_sum,
            'RETIRO' => $retiro_sum,
            'RETIRO_DE_RENDIMIENTOS' => $rendimientos_sum
        );
        return $return_arr;
    }

    function get_retiros_tmp($nro, $type) {
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $this->idu . '_tmp';
        $token = $this->idu;
        $new_query = array(
            'NUMERO_DE_APORTE' => $nro,
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

    function get_aporte_tmp($nro, $date) {

        $aporte_result_arr = array();
        $retiro_result_arr = array();
        $rendimientos_result_arr = array();


        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $this->idu . '_tmp';
        $token = $this->idu;
        $new_query = array(
            'NUMERO_DE_APORTE' => $nro,
            'TOKEN' => $token,
            'FECHA_MOVIMIENTO' => array(
                '$lte' => $date
            )
        );

        $date_movement_arr = array();

        $movement_result = $this->mongo->sgr->$container->find($new_query);

        foreach ($movement_result as $movement) {
            $aporte_result_arr[] = $movement['APORTE'];
            $retiro_result_arr[] = $movement['RETIRO'];
            $rendimientos_result_arr[] = $movement['RETIRO_DE_RENDIMIENTOS'];
        }

        $aporte_sum = array_sum($aporte_result_arr);
        $retiro_sum = array_sum($retiro_result_arr);
        $rendimientos_sum = array_sum($rendimientos_result_arr);

        $return_arr = array(
            'APORTE' => $aporte_sum,
            'RETIRO' => $retiro_sum,
            'RETIRO_DE_RENDIMIENTOS' => $rendimientos_sum
        );
        return $return_arr;
    }

}
