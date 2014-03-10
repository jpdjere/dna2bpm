<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_16 extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('sgr/tools');

        $this->anexo = '16';
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
         * PROMEDIO_SALDO_MENSUAL	
         * SALDO_PROMEDIO_GARANTIAS_VIGENTES	
         * SALDO_PROMEDIO_PONDERADO_GARANTIAS_VIGENTES_80_HASTA_FEB_2010	
         * SALDO_PROMEDIO_PONDERADO_GARANTIAS_VIGENTES_120_HASTA_FEB_2010	
         * SALDO_PROMEDIO_PONDERADO_GARANTIAS_VIGENTES_80_DESDE_FEB_2010	
         * SALDO_PROMEDIO_PONDERADO_GARANTIAS_VIGENTES_120_DESDE_FEB_2010	
         * SALDO_PROMEDIO_PONDERADO_GARANTIAS_VIGENTES_80_DESDE_ENE_2011	
         * SALDO_PROMEDIO_PONDERADO_GARANTIAS_VIGENTES_120_DESDE_ENE_2011	
         * SALDO_PROMEDIO_FDR_TOTAL_COMPUTABLE	
         * SALDO_PROMEDIO_FDR_CONTINGENTE
         * */
        $defdna = array(
            1 => 'PROMEDIO_SALDO_MENSUAL',
            2 => 'GARANTIAS_VIGENTES',
            3 => '80_HASTA_FEB_2010',
            4 => '120_HASTA_FEB_2010',
            5 => '80_DESDE_FEB_2010',
            6 => '120_DESDE_FEB_2010',
            7 => '80_DESDE_ENE_2011',
            8 => '120_DESDE_ENE_2011',
            9 => 'FDR_TOTAL_COMPUTABLE',
            10 => 'FDR_CONTINGENTE'
        );


        $insertarr = array();
        foreach ($defdna as $key => $value) {
            $insertarr[$value] = $parameter[$key];

            if (strtoupper(trim($insertarr["MONEDA"])) == "PESOS ARGENTINOS")
                $insertarr["MONEDA"] = "1";
            if (strtoupper(trim($insertarr["MONEDA"])) == "DOLARES AMERICANOS")
                $insertarr["MONEDA"] = "2";
        }
        return $insertarr;
    }
    
    function save($parameter) {
        $period = $this->session->userdata['period'];
        $container = 'container.sgr_anexo_' . $this->anexo;        

        $parameter['period'] = $period;
        $parameter['origin'] = 2013;
        //$parameter['PROMEDIO_SALDO_MENSUAL'] = $period;
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

    function get_anexo_info($anexo, $parameter) {


        $tmpl = array(
            'data' => '<tr>
        <td align="center" rowspan="2">Promedio Saldo <br>mensual correspondiente al mes</td>
        <td align="center" rowspan="2">Saldo Promedio <br />Garantias Vigentes</td>
        <td align="center" rowspan="2">Saldo Promedio <br />Ponderado Garantias Vigentes <br />80 hasta feb 2010</td>
        <td align="center" rowspan="2">Saldo Promedio <br />Ponderado Garantias Vigentes <br />120 hasta feb 2010</td>
        
        <td colspan="2">Emitidas entre el 25 de Febrero y el 31 de Diciembre de 2010</td>
        <td colspan="2">Emitidas desde el 1° de Febrero de 2011</td>
        
                                <td rowspan="2" align="center">Saldo Total de Garantias Vigentes que Computan para el 80%</td>
                                <td rowspan="2" align="center">Saldo Total de Garantias Vigentes que Computan para el 120%</td>
                                <td rowspan="2" align="center">Saldo Promedio <br />Fonde de Riesgo<br /> Total computable</td>                                
                                <td rowspan="2" align="center">Saldo Promedio <br />Fonde de Riesgo<br /> contingente</td>
                                <td rowspan="2" align="center">Saldo Promedio <br />Fonde de Riesgo<br /> Total disponible</td>
                                <td rowspan="2" align="center">Solvencia (Apalancamiento)</td>
                                <td rowspan="2" align="center">Grado de Utilización para el 80%</td>
                                <td rowspan="2" align="center">Grado de Utilización para el 120%</td>
    </tr>
    <tr>
         <td align="center">Saldo Promedio <br />Ponderado Garantias Vigentes <br />80 desde feb 2010</td>
                                <td  align="center">Saldo Promedio <br />Ponderado Garantias Vigentes <br />120 desde feb 2010</td>
                                <td  align="center">Saldo Promedio <br />Ponderado Garantias Vigentes <br />80 desde ene 2011</td>
                                <td align="center">Saldo Promedio <br />Ponderado Garantias Vigentes <br />120 desde ene 2011</td>
                                
    </tr>
                            <tr>
                                <td align="center">1</td>
                                <td align="center">2</td>
                                <td align="center">3</td>
                                <td align="center">4</td>
                                <td align="center">5</td>
                                <td align="center">6</td>
                                <td align="center">7</td>
                                <td align="center">8</td>
                                <td align="center">9 (3+5+7)</td>
                                <td align="center">10 (4+6+8)</td>
                                <td align="center">11</td>
                                <td align="center">12</td>
                                <td align="center">13 (11-12)</td>
                                <td align="center">14 (2/11)</td>
                                <td align="center">15 (9/11)</td>
                                <td align="center">16 (10/11)</td>
                            </tr>',
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

    function get_anexo_data($anexo, $parameter) {
        header('Content-type: text/html; charset=UTF-8');
        $rtn = array();
        $container = 'container.sgr_anexo_' . $anexo;
        $query = array("filename" => $parameter);
        $result = $this->mongo->sgr->$container->find($query);

        foreach ($result as $list) {
            /* Vars 								
             */

            $this->load->model('padfyj_model');
            $transmitter_name = $this->padfyj_model->search_name($list['CUIT_EMISOR']);
            $transmitter_name = ($transmitter_name) ? $transmitter_name : strtoupper($list['EMISOR']);

            $depositories_name = $this->sgr_model->get_depositories($list['CUIT_DEPOSITARIO']);
            $depositories_name = ($depositories_name) ? $depositories_name['nombre'] : strtoupper($list['ENTIDAD_DESPOSITARIA']);

            $this->load->model('app');
            
            
            


            $col9 = array_sum(array($list['80_HASTA_FEB_2010'], $list['80_DESDE_FEB_2010'], $list['80_DESDE_ENE_2011']));
            $col10 = array_sum(array($list['120_HASTA_FEB_2010'], $list['120_DESDE_FEB_2010'], $list['120_DESDE_ENE_2011']));
            $col13 = $list['FDR_TOTAL_COMPUTABLE'] - $list['FDR_CONTINGENTE'];
            $col14 = $list['GARANTIAS_VIGENTES'] / $list['FDR_TOTAL_COMPUTABLE'];
            $col15 = $col9 / $list['FDR_TOTAL_COMPUTABLE'];
            $col15 = $col10 / $list['FDR_TOTAL_COMPUTABLE'];

            $new_list = array();
            $new_list['col1'] = $list['PROMEDIO_SALDO_MENSUAL'];
            $new_list['col2'] = money_format_custom($list['GARANTIAS_VIGENTES']);
            $new_list['col3'] = money_format_custom($list['80_HASTA_FEB_2010']);
            $new_list['col4'] = money_format_custom($list['120_HASTA_FEB_2010']);
            $new_list['col5'] = money_format_custom($list['80_DESDE_FEB_2010']);
            $new_list['col6'] = money_format_custom($list['120_DESDE_FEB_2010']);
            $new_list['col7'] = money_format_custom($list['80_DESDE_ENE_2011']);
            $new_list['col8'] = money_format_custom($list['120_DESDE_ENE_2011']);
            $new_list['col9'] = money_format_custom($col9, true);
            $new_list['col10'] = money_format_custom($col10, true);
            $new_list['col11'] = money_format_custom($list['FDR_TOTAL_COMPUTABLE']);
            $new_list['col12'] = money_format_custom($list['FDR_CONTINGENTE']);
            $new_list['col13'] = money_format_custom($col13, true);
            $new_list['col14'] = percent_format_custom($col14);
            $new_list['col15'] = percent_format_custom($col15);
            $new_list['col16'] = percent_format_custom($col16);
            $rtn[] = $new_list;
        }
        return $rtn;
    }

}
