<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_12 extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('sgr/tools');

        $this->anexo = '12';
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
        }
        return $insertarr;
    }

    function save($parameter) {
        $period = $this->session->userdata['period'];
        $container = 'container.sgr_anexo_' . $this->anexo;

        $parameter = array_map('trim', $parameter);
        $parameter = array_map('addSlashes', $parameter);

        /* FIX DATE */
        list($arr['Y'], $arr['m'], $arr['d']) = explode("-", strftime("%Y-%m-%d", mktime(0, 0, 0, 1, -1 + $parameter[5215], 1900)));
        $parameter[5215] = $arr;
        
        
        if (strtoupper(trim($insertarr[5219])) == "PESOS ARGENTINOS")
            $insertarr[5219] = "1";
        if (strtoupper(trim($insertarr[5219])) == "DOLARES AMERICANOS")
            $insertarr[5219] = "2";

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
        $status = 'rectificado';
        $parameter = array('status' => $status);
        $rs = $this->mongo->sgr->$container->update($query, array('$set' => $parameter), $options);
        return $rs['err'];
    }

    function get_anexo_info($anexo, $parameter) {


        $tmpl = array(
            'data' => '<tr>
                                <td rowspan="5" align="center">N&ordm; de Orden de la Garant&iacute;a Otorgada</td>
                                <td colspan="2" rowspan="3" align="center">Del Part&iacute;cipe / Beneficiario</td>
                                <td colspan="5" rowspan="3" align="center">De la Garant&iacute;a</td>
                                <td colspan="3" rowspan="3" align="center">Operaciones con Cheques de Pago    Diferido</td>
                                <td colspan="2" rowspan="3" align="center">Del Acreedor</td>
                                <td colspan="9" rowspan="3" align="center">Del Cr&eacute;dito Garantizado</td>                                
                            </tr>
                            <tr> </tr>
                            <tr> </tr>
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

       /*DRAW TABLE*/
        $fix_table = '<thead>
<tr>
<th>';
        
        $data = array($tmpl);
        $anexoValues = $this->get_anexo_data($anexo, $parameter);
        foreach ($anexoValues as $values) {
            $data[] = array_values($values);
        }
        $this->load->library('table');
        $newTable = str_replace($fix_table, '<thead>', $this->table->generate($data));
        return $newTable;
        
    }

    function get_anexo_data($anexo, $parameter) {
        header('Content-type: text/html; charset=UTF-8');
        $rtn = array();
        $container = 'container.sgr_anexo_' . $anexo;
        $query = array("filename" => $parameter);
        $result = $this->mongo->sgr->$container->find($query);

        foreach ($result as $list) {
            /* Vars */
            $new_list = array();
            
            
            $this->load->model('padfyj_model');
            $participate = $this->padfyj_model->search_name($list[5349]);
            $drawer = $this->padfyj_model->search_name($list[5726]);
            $creditor = $this->padfyj_model->search_name($list[5351]);


            $this->load->model('app');
            $warranty_type = $this->app->get_ops(525);
            
            /*PONDERACION*/
            $get_weighting = $this->sgr_model->get_warranty_type($list[5216][0]);  
            //$currency = ($list[5219][0]=="")
            

            
            $new_list['NRO'] = $list[5214];
            $new_list['PARTICIPE'] = $participate;
            $new_list['CUIT_PARTICIPE'] = $list[5349];
            $new_list['ORIGEN'] = $list[5215];
            $new_list['TIPO'] = $list[5216][0];
            $new_list['PONDERACION'] = $get_weighting['ponderacion']*100;
            $new_list['IMPORTE'] = money_format_custom($list[5218]);
            $new_list['MONEDA'] = $list[5219][0];
            $new_list['LIBRADOR_NOMBRE'] = $drawer;
            $new_list['LIBRADOR_CUIT'] = $list[5726];
            $new_list['NRO_OPERACION_BOLSA'] = $list[5727];
            $new_list['ACREEDOR'] = $creditor;
            $new_list['CUIT_ACREEDOR'] = $list[5351];
            $new_list['IMPORTE_CRED_GARANT'] = $list[5221];
            $new_list['MONEDA_CRED_GARANT'] = $list[5758];
            $new_list['TASA'] = $list[5222][0];
            $new_list['PUNTOS_ADIC_CRED_GARANT'] = $list[5223];
            $new_list['PLAZO'] = $list[5224];
            $new_list['GRACIA'] = $list[5225];
            $new_list['PERIODICIDAD'] = $list[5226][0];
            $new_list['SISTEMA'] = $list[5227][0];
            $new_list['DESTINO_CREDITO'] = $list['DESTINO_CREDITO'];
            $rtn[] = $new_list;
        }
        return $rtn;
    }

}
