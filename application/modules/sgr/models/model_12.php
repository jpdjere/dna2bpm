<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_12 extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('sgr/tools');

        $this->anexo = '12';
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
         * @example NRO	CUIT_PARTICIPE	ORIGEN	TIPO	IMPORTE	MONEDA	LIBRADOR_NOMBRE	LIBRADOR_CUIT	NRO_OPERACION_BOLSA	ACREEDOR	CUIT_ACREEDOR	IMPORTE_CRED_GARANT	MONEDA_CRED_GARANT	TASA	PUNTOS_ADIC_CRED_GARANT	PLAZO	GRACIA	PERIODICIDAD	SISTEMA	DESTINO_CREDITO
         * */
        $defdna = array(
            1 => 5214, //"Nro",
            2 => 5349, //"Cuit_participe",              
            3 => 5215, //"Origen",                      
            4 => 5216, //"Tipo",
            5 => 5218, //"Importe",
            6 => 5219, //"Moneda",
            7 => 5725, //"Librador_nombre",
            8 => 5726, //"Librador_cuit",
            9 => 5727, //"Nro_operacion_bolsa",
            10 => 5350, //"Acreedor",
            11 => 5351, //"Cuit_acreedor",            
            12 => 5221, //"Importe_Cred_Garant",
            13 => 5758, //"Moneda_Cred_Garant",
            14 => 5222, //"Tasa",
            15 => 5223, //"Puntos_adic_Cred_Garantizado",
            16 => 5224, //"Plazo",  
            17 => 5225, //"Gracia",
            18 => 5226, //"Period.",
            19 => 5227, //"Sistema",
            20 => 'DESTINO_CREDITO' //"Tipo_Contragarantia"
        );


        $insertarr = array();
        foreach ($defdna as $key => $value) {
            $insertarr[$value] = $parameter[$key];

            /* STRING */
            $insertarr[5214] = (string) $insertarr[5214]; //Nro orden


            $insertarr[5349] = (string) $insertarr[5349]; //Cuit_participe
            $insertarr[5726] = (string) $insertarr[5726]; //Librador_cuit           
            $insertarr[5351] = (string) $insertarr[5351]; //Acreedir

            /* FLOAT */
            $insertarr[5218] = (float) $insertarr[5218];
            $insertarr[5221] = (float) $insertarr[5221];
            $insertarr[5223] = (float) $insertarr[5223];

            /* INTEGER */
            $insertarr[5224] = (int) $insertarr[5224];
            $insertarr[5225] = (int) $insertarr[5225];

            /* MONEDA */
            if (strtoupper(trim($insertarr[5219])) == "PESOS ARGENTINOS")
                $insertarr[5219] = "1";
            if (strtoupper(trim($insertarr[5219])) == "DOLARES AMERICANOS")
                $insertarr[5219] = "2";
            if (strtoupper(trim($insertarr[5219])) == "EUROS")
                $insertarr[5219] = "3";
            if (strtoupper(trim($insertarr[5219])) == "REALES")
                $insertarr[5219] = "4";

            if (strtoupper($insertarr[5758]) == "PESOS ARGENTINOS")
                $insertarr[5758] = "1";
            if (strtoupper($insertarr[5758]) == "DOLARES AMERICANOS")
                $insertarr[5758] = "2";
            if (strtoupper($insertarr[5758]) == "EUROS")
                $insertarr[5758] = "3";
            if (strtoupper($insertarr[5758]) == "REALES")
                $insertarr[5758] = "4";



            /* PERIODICIDAD */
            if (strtoupper(trim($insertarr[5226])) == "PAGO UNICO")
                $insertarr[5226] = "1";
            if (strtoupper(trim($insertarr[5226])) == "MENSUAL")
                $insertarr[5226] = "30";
            if (strtoupper(trim($insertarr[5226])) == "BIMESTRAL")
                $insertarr[5226] = "60";
            if (strtoupper(trim($insertarr[5226])) == "TRIMESTRAL")
                $insertarr[5226] = "90";
            if (strtoupper(trim($insertarr[5226])) == "CUATRIMESTRAL")
                $insertarr[5226] = "120";
            if (strtoupper(trim($insertarr[5226])) == "SEMESTRAL")
                $insertarr[5226] = "180";
            if (strtoupper(trim($insertarr[5226])) == "ANUAL")
                $insertarr[5226] = "360";
            if (strtoupper(trim($insertarr[5226])) == "OTRO")
                $insertarr[5226] = "04";

            /* SISTEMA */
            if (strtoupper(trim($insertarr[5227])) == "PAGO UNICO")
                $insertarr[5227] = "01";
            if (strtoupper(trim($insertarr[5227])) == "FRANCES")
                $insertarr[5227] = "02";
            if (strtoupper(trim($insertarr[5227])) == "ALEMAN")
                $insertarr[5227] = "03";
            if (strtoupper(trim($insertarr[5227])) == "OTRO")
                $insertarr[5227] = "04";



            /* TASA */
            if (strtoupper($insertarr[5222]) == "LIBOR")
                $insertarr[5222] = "01";
            if (strtoupper($insertarr[5222]) == "BADLARPU")
                $insertarr[5222] = "02";
            if (strtoupper($insertarr[5222]) == "BADLARPR")
                $insertarr[5222] = "03";
            if (strtoupper($insertarr[5222]) == "FIJA")
                $insertarr[5222] = "04";
            if (strtoupper($insertarr[5222]) == "TEBP")
                $insertarr[5222] = "05";
            if (strtoupper($insertarr[5222]) == "TEC")
                $insertarr[5222] = "06";
        }
        return $insertarr;
    }

    function save($parameter) {
        $period = $this->session->userdata['period'];
        $container = 'container.sgr_anexo_' . $this->anexo;

        /* FIX DATE */
        list($arr['Y'], $arr['m'], $arr['d']) = explode("-", strftime("%Y-%m-%d", mktime(0, 0, 0, 1, -1 + $parameter[5215], 1900)));
        $parameter[5215] = $arr;

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

    function get_anexo_info($anexo, $parameter) {
        $tmpl = array(
            'data' => '<tr><td rowspan="5" align="center">N&ordm; de Orden de la Garant&iacute;a Otorgada</td>
                                <td colspan="2" rowspan="3" align="center">Del Part&iacute;cipe / Beneficiario</td>
                                <td colspan="5" rowspan="3" align="center">De la Garant&iacute;a</td>
                                <td colspan="3" rowspan="3" align="center">Operaciones con Cheques de Pago    Diferido</td>
                                <td colspan="2" rowspan="3" align="center">Del Acreedor</td>
                                <td colspan="9" rowspan="3" align="center">Del Cr&eacute;dito Garantizado</td>                                
                            </tr>
                            <tr></tr>
                            <tr></tr>
                            <tr>
                                <td rowspan="2" align="center">Nombre o raz&oacute;n social</td>
                                <td rowspan="2" align="center">C.U.I.T.</td>
                                <td rowspan="2" align="center">Fecha de    origen</td>
                                <td rowspan="2" align="center">Tipo</td>
                                <td rowspan="2" align="center">Ponderaci&oacute;n</td>
                                <td rowspan="2" align="center">Importe en $</td>
                                <td rowspan="2" align="center">Moneda    de Origen</td>
                                <td colspan="2" align="center">Librador</td>
                                <td rowspan="2" align="center">N&ordm; de    Operaci&oacute;n en la Bolsa</td>
                                <td rowspan="2" align="center">Nombre o raz&oacute;n social</td>
                                <td rowspan="2" align="center">C.U.I.T</td>
                                <td rowspan="2" align="center">Importe Total en <br />Pesos Argentinos</td>
                                <td rowspan="2" align="center">Moneda de Origen</td>
                                <td colspan="2" align="center">Tasa de inter&eacute;s pactada</td>
                                <td rowspan="2" align="center">Plazo<br>(d&iacute;as)</td>
                                <td rowspan="2" align="center">Per&iacute;odo de gracia (d&iacute;as)</td>
                                <td rowspan="2" align="center">Periodicidad de los pagos<br>
                                        (d&iacute;as)</td>
                                <td rowspan="2" align="center">Sistema de amortizaci&oacute;n</td>
                                <td rowspan="2" align="center">Destino del Credito</td>                                
                            </tr>
                            <tr>
                                <td align="center">Nombre</td>
                                <td align="center">C.U.I.T.</td>
                                <td align="center">Tasa    de Referencia</td>
                                <td align="center">Puntos    Porcentuales adicionales Fijos (%)</td>
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
                                <th>9</th>
                                <th>10</th>
                                <th>11</th>
                                <th>12</th>
                                <th>13</th>
                                <th>14</th>
                                <th>15</th>
                                <th>16</th>
                                <th>17</th>
                                <th>18</th>
                                <th>19</th>                
                                <th>20</th>
                                <th>21</th>
                                <th>22</th>                
                            </tr> ',
        );
        $data = array($tmpl);
        $anexoValues = $this->get_anexo_data($anexo, $parameter);
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
        $fields = array('5214',
            '5349',
            '5215',
            '5216',
            '5218',
            '5219',
            '5726',
            '5727',
            '5351',
            '5221',
            '5758',
            '5222',
            '5223',
            '5224',
            '5225',
            '5226',
            '5227', 'filename', 'period', 'sgr_id', 'origin');
        $query = array("filename" => $parameter);
        $result = $this->mongo->sgr->$container->find($query, $fields);

        foreach ($result as $list) {
            $rtn[] = $list;
        }

        return $rtn;
    }

    function get_anexo_data($anexo, $parameter) {
        header('Content-type: text/html; charset=UTF-8');
        $rtn = array();
        $container = 'container.sgr_anexo_' . $anexo;
        $query = array("filename" => $parameter);
        $result = $this->mongo->sgr->$container->find($query);

        $model_anexo = "model_12";
        $this->load->Model($model_anexo);


        foreach ($result as $list) {
            /* Vars */
            $new_list = array();

            $this->load->model('padfyj_model');
            $participate = $this->padfyj_model->search_name($list[5349]);
            $drawer = $this->padfyj_model->search_name((string) $list[5726]);



            /*  CREDITOR NAME */
            $creditor_mv = $this->get_mv_and_comercial_name($list[5351]);
            $creditor_padfyj = $this->padfyj_model->search_name($list[5351]);
            $creditor = ($creditor_mv) ? $creditor_mv : $creditor_padfyj;


            $this->load->model('app');
            $warranty_type = $this->app->get_ops(525);
            $currency = $this->app->get_ops(549);
            $repayment_system = $this->app->get_ops(527);
            $rate = $this->app->get_ops(526);
            $periodicity = $this->app->get_ops(548);

            /* PONDERACION */
            $get_weighting = $this->sgr_model->get_warranty_type($list[5216][0]);
            $warranty_type_value = ($warranty_type[$list[5216][0]]) ? $warranty_type[$list[5216][0]] : $list[5216][0];


            $new_list['NRO'] = $list[5214];
            $new_list['PARTICIPE'] = $participate;
            $new_list['CUIT_PARTICIPE'] = $list[5349];
            $new_list['ORIGEN'] = $list[5215];
            $new_list['TIPO'] = $warranty_type_value;
            $new_list['PONDERACION'] = $get_weighting['weighted'] * 100;
            $new_list['IMPORTE'] = money_format_custom($list[5218]);
            $new_list['MONEDA'] = $currency[$list[5219][0]];
            $new_list['LIBRADOR_NOMBRE'] = $drawer;
            $new_list['LIBRADOR_CUIT'] = $list[5726];
            $new_list['NRO_OPERACION_BOLSA'] = $list[5727];
            $new_list['ACREEDOR'] = $creditor;
            $new_list['CUIT_ACREEDOR'] = $list[5351];
            $new_list['IMPORTE_CRED_GARANT'] = $list[5221];
            $new_list['MONEDA_CRED_GARANT'] = $currency[$list[5758][0]];
            $new_list['TASA'] = $rate[$list[5222][0]];
            $new_list['PUNTOS_ADIC_CRED_GARANT'] = $list[5223];
            $new_list['PLAZO'] = $list[5224];
            $new_list['GRACIA'] = $list[5225];
            $new_list['PERIODICIDAD'] = $periodicity[$list[5226][0]];
            $new_list['SISTEMA'] = $repayment_system[$list[5227][0]];
            $new_list['DESTINO_CREDITO'] = $list['DESTINO_CREDITO'];
            $rtn[] = $new_list;
        }
        return $rtn;
    }

    //container.sgr_cuits_comerciales_y_mv
    function get_mv_and_comercial_name($cuit) {

        $container = 'container.sgr_cuits_comerciales_y_mv';
        $query = array("cuit" => $cuit);
        $result = $this->mongo->sgr->$container->findOne($query);
        if ($result)
            return $result['name'];
    }

    function get_mv_and_comercial_cuits($cuit, $type) {

        $container = 'container.sgr_cuits_comerciales_y_mv';
        $query = array("cuit" => $cuit, "type" => $type);
        $result = $this->mongo->sgr->$container->findOne($query);
        if ($result)
            return true;
    }

    /* GET DATA */

    function get_order_number($nro) {
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;
        $period_value = $this->session->userdata['period'];

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active($anexo, $period_value);

        $return_result = array();
        foreach ($result as $list) {
            $new_query = array(
                'filename' => $list['filename'],
                5214 => $nro
            );

            $new_result = $this->mongo->sgr->$container->findOne($new_query);
            if ($new_result) {
                $return_result[] = $new_result;
            }
        }
        return $return_result;
    }

    function get_period_amount($period_value) {

        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_12';

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_period_data($anexo, $period_value);

        $return_result = array();
        foreach ($result as $list) {
            $new_query = array(
                'filename' => $list['filename']
            );
            $new_result = $this->mongo->sgr->$container->find($new_query);
            foreach ($new_result as $each) {
                $return_result[] = $each[5218];
            }
        }
        $average = array_sum($return_result) / count($return_result);
        return $average;
    }

    /* GET DATA */

    function get_order_number_left($nro) {
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;
        $period_value = $this->session->userdata['period'];

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active($anexo);
        $return_result = array();
        foreach ($result as $list) {
            $new_query = array(
                'filename' => $list['filename'],
                5214 => $nro
            );
            $new_result = $this->mongo->sgr->$container->findOne($new_query);
            if ($new_result) {
                $return_result[] = $new_result;
            }
        }
        return $return_result;
    }

    function get_order_number_print($nro, $period_date) {
        $anexo = $this->anexo;

        $period = 'container.sgr_periodos';
        $container = 'container.sgr_anexo_' . $anexo;

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active_print($anexo, $period_date);

        /* FIND ANEXO */
        foreach ($result as $list) {
            $new_query = array(
                'filename' => $list['filename'],
                5214 => $nro
            );
            $new_result = $this->mongo->sgr->$container->findOne($new_query);
            if ($new_result) {
                $return_result[] = $new_result;
            }
        }
        return $return_result;
    }

    /* GET DATA */

    function get_warranty_partner_left($cuit) {
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;
        $period_value = $this->session->userdata['period'];

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active($anexo);
        $return_result = array();
        foreach ($result as $list) {
            $new_query = array(
                'filename' => $list['filename'],
                5349 => $cuit
            );
            $new_result = $this->mongo->sgr->$container->findOne($new_query);
            if ($new_result) {
                $return_result[] = $new_result;
            }
        }
        return $return_result;
    }

    /* GET SHARER PARTNER WARRANTIES */

    function get_sharer($cuit) {
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;
        $period_value = $this->session->userdata['period'];

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active($anexo, $period_value);

        $return_result = array();
        foreach ($result as $list) {
            $new_query = array(
                'filename' => $list['filename'],
                5349 => $cuit
            );

            $new_result = $this->mongo->sgr->$container->find($new_query);
            foreach ($new_result as $list2) {
                $return_result[] = $list2;
            }
        }

        return $return_result;
    }

    function get_sharer_left($cuit) {
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;
        $period_value = $this->session->userdata['period'];

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active($anexo);

        $return_result = array();
        foreach ($result as $list) {
            $new_query = array(
                'filename' => $list['filename'],
                5349 => $cuit
            );

            $new_result = $this->mongo->sgr->$container->find($new_query);
            foreach ($new_result as $list2) {
                $return_result[] = $list2;
            }
        }

        return $return_result;
    }

    /* GET CREDITOR */

    function get_creditor($sharer, $cuit) {
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;
        $period_value = $this->session->userdata['period'];

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active($anexo);

        $return_result = array();
        foreach ($result as $list) {
            $new_query = array(
                'filename' => $list['filename'],
                5349 => $sharer,
                5351 => $cuit
            );

            $new_result = $this->mongo->sgr->$container->findOne($new_query);
            if ($new_result) {
                $return_result[] = $new_result;
            }
        }

        return $return_result;
    }

    /* REPORTS */

    function get_anexo_report($anexo, $parameter) {

        $headerArr = array('NRO'
            , 'PARTICIPE'
            , 'CUIT_PARTICIPE'
            , 'ORIGEN'
            , 'TIPO'
            , 'PONDERACION'
            , 'IMPORTE'
            , 'MONEDA'
            , 'LIBRADOR_NOMBRE'
            , 'LIBRADOR_CUIT'
            , 'NRO_OPERACION_BOLSA'
            , 'ACREEDOR'
            , 'CUIT_ACREEDOR'
            , 'IMPORTE_CRED_GARANT'
            , 'MONEDA_CRED_GARANT'
            , 'TASA'
            , 'PUNTOS_ADIC_CRED_GARANT'
            , 'PLAZO'
            , 'GRACIA'
            , 'PERIODICIDAD'
            , 'SISTEMA'
            , 'DESTINO_CREDITO');
        $data = array($headerArr);
        $anexoValues = $this->get_anexo_data_report($anexo, $parameter);

        if (!$anexoValues) {
            return false;
        } else {
            foreach ($anexoValues as $values) {
                $data[] = array_values($values);
            }
            $this->load->library('table');
            return $this->table->generate($data);
        }
    }

    function get_assisted_pymes($period) {

        $rtn = array();
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;


        $model_anexo = "model_12";
        $this->load->Model($model_anexo);

        $get_result = $this->sgr_model->get_current_period_info($anexo, $period);

        $query = array("filename" => $get_result['filename']);
        $result = $this->mongo->sgr->$container->find($query);
        $new_list = array();
        foreach ($result as $list) {
            $rtn[] = $list[5349];
        }


        return count(array_unique($rtn));
    }

    function get_amount_granted_qty($period) {

        $rtn = array();
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;

        $get_result = $this->sgr_model->get_current_period_info($anexo, $period);

        $query = array("filename" => $get_result['filename']);
        $result = $this->mongo->sgr->$container->find($query);
        $new_list = array();
        foreach ($result as $list) {
            $rtn[] = $list[5214];
        }


        return count(array_unique($rtn));
    }

    function get_amount_granted($period) {

        $rtn = array();
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;

        $get_result = $this->sgr_model->get_current_period_info($anexo, $period);

        $query = array("filename" => $get_result['filename']);
        $result = $this->mongo->sgr->$container->find($query);
        $new_list = array();
        foreach ($result as $list) {
            $rtn[] = $list[5218];
        }
        return array_sum($rtn);
    }

    function get_anexo_data_report($anexo, $parameter) {

        if (!$parameter) {
            return false;
            exit();
        }

        header('Content-type: text/html; charset=UTF-8');
        $rtn = array();

        $order_number = $parameter['order_number'];
        $cuit_sharer = $parameter['cuit_sharer'];
        $start_date = first_month_date($parameter['input_period_from']);
        $end_date = last_month_date($parameter['input_period_to']);

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
        if ($cuit_sharer)
            $new_query["5349"] = $cuit_sharer;

        if ($order_number)
            $new_query["5214"] = $order_number;



        $result_arr = $this->mongo->sgr->$container->find($new_query);
        /* TABLE DATA */
        return $this->ui_table_xls($result_arr);
    }

    function ui_table_xls($result) {
        foreach ($result as $list) {
            /* Vars */
            $new_list = array();

            $this->load->model('padfyj_model');
            $participate = $this->padfyj_model->search_name($list[5349]);
            $drawer = $this->padfyj_model->search_name((string) $list[5726]);



            /*  CREDITOR NAME */
            $creditor_mv = $this->get_mv_and_comercial_name($list[5351]);
            $creditor_padfyj = $this->padfyj_model->search_name($list[5351]);
            $creditor = ($creditor_mv) ? $creditor_mv : $creditor_padfyj;


            $this->load->model('app');
            $warranty_type = $this->app->get_ops(525);
            $currency = $this->app->get_ops(549);
            $repayment_system = $this->app->get_ops(527);
            $rate = $this->app->get_ops(526);
            $periodicity = $this->app->get_ops(548);

            /* PONDERACION */
            $get_weighting = $this->sgr_model->get_warranty_type($list[5216][0]);
            $warranty_type_value = ($warranty_type[$list[5216][0]]) ? $warranty_type[$list[5216][0]] : $list[5216][0];


            $new_list['NRO'] = $list[5214];
            $new_list['PARTICIPE'] = $participate;
            $new_list['CUIT_PARTICIPE'] = $list[5349];
            $new_list['ORIGEN'] = $list[5215];
            $new_list['TIPO'] = $warranty_type_value;
            $new_list['PONDERACION'] = $get_weighting['weighted'] * 100;
            $new_list['IMPORTE'] = $list[5218];
            $new_list['MONEDA'] = $currency[$list[5219][0]];
            $new_list['LIBRADOR_NOMBRE'] = $drawer;
            $new_list['LIBRADOR_CUIT'] = $list[5726];
            $new_list['NRO_OPERACION_BOLSA'] = $list[5727];
            $new_list['ACREEDOR'] = $creditor;
            $new_list['CUIT_ACREEDOR'] = $list[5351];
            $new_list['IMPORTE_CRED_GARANT'] = $list[5221];
            $new_list['MONEDA_CRED_GARANT'] = $list[5758][0];
            $new_list['TASA'] = $rate[$list[5222][0]];
            $new_list['PUNTOS_ADIC_CRED_GARANT'] = $list[5223];
            $new_list['PLAZO'] = $list[5224];
            $new_list['GRACIA'] = $list[5225];
            $new_list['PERIODICIDAD'] = $periodicity[$list[5226][0]];
            $new_list['SISTEMA'] = $repayment_system[$list[5227][0]];
            $new_list['DESTINO_CREDITO'] = $list['DESTINO_CREDITO'];
            $rtn[] = $new_list;
        }

        return $rtn;
    }

}
