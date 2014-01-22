<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_12 extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('sgr/tools');

        $this->anexo = '06';
        $this->idu = (int) $this->session->userdata('iduser');
        /*SWITCH TO SGR DB*/
        $this->load->library('cimongo/cimongo','','sgr_db');
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
            2 => 5348, //"Participe",
            3 => 5349, //"Cuit_participe",
            4 => 5215, //"Origen",
            5 => 5216, //"Tipo",
            6 => 5217, //"Ponderacion",
            7 => 5218, //"Importe",
            8 => 5219, //"Moneda",
            9 => 5725, //"Librador_nombre",
            10 => 5726, //"Librador_cuit",
            11 => 5727, //"Nro_operacion_bolsa",
            12 => 5350, //"Acreedor",
            13 => 5351, //"Cuit_acreedor",
            14 => 5221, //"Importe_Cred_Garant",
            15 => 5758, //"Moneda_Cred_Garant",
            16 => 5222, //"Tasa",
            17 => 5223, //"Puntos_adic_Cred_Garantizado",
            18 => 5224, //"Plazo",  
            19 => 5225, //"Gracia",
            20 => 5226, //"Period.",
            21 => 5227, //"if(strtoupper($insertarr[5227])",
            22 => 5228, //"Tipo_Contragarantia",
            23 => 5229   //"Valor_Contragarantia"
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
        list($arr['Y'], $arr['m'], $arr['d']) = explode("-", strftime("%Y-%m-%d", mktime(0, 0, 0, 1, -1 + $parameter[5255], 1900)));
        $parameter[5255] = $arr;

        $parameter['FECHA_DE_TRANSACCION'] = strftime("%Y-%m-%d", mktime(0, 0, 0, 1, -1 + $parameter['FECHA_DE_TRANSACCION'], 1900));

        $parameter['period'] = $period;

        $parameter['origin'] = 2013;
        $id = $this->app->genid($container);

        $result = $this->app->put_array($id, $container, $parameter);

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
        $id = $this->app->genid($container);
        $parameter['period'] = $period;
        $parameter['status'] = 'activo';
        $parameter['idu'] = $this->idu;

        /*
         * VERIFICO PENDIENTE           
         */
        $get_period = $this->sgr_model->get_period_info($this->anexo, $this->sgr_id, $period);
        $this->update_period($get_period['id'], $get_period['status']);

        $result = $this->app->put_array($id, $container, $parameter);

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

    function get_anexo_data($anexo, $parameter) {
        header('Content-type: text/html; charset=UTF-8');
        $rtn = array();
        $container = 'container.sgr_anexo_' . $anexo;
        $query = array("filename" => $parameter);
        $result = $this->mongo->sgr->$container->find($query);

        foreach ($result as $list) {

            /* Vars */           
            $this->load->model('padfyj_model');
            $brand_name = $this->padfyj_model->search_name($list['5248']);            
            $brand_name = ($brand_name) ? $brand_name:$list['1693'];
            
            $this->load->model('app');
            $operation_type = $this->app->get_ops(589);
            $inscripcion_iva = $this->app->get_ops(571);
            $acta_type = $this->app->get_ops(531);
            $partner_type = $this->app->get_ops(532);
            $transaction_type = $this->app->get_ops(530);
            $partido = $this->app->get_ops(58);
            $provincia = $this->app->get_ops(39);
            $transfer_characteristic = $this->app->get_ops(571);
            $afip_condition = $this->app->get_ops(570);




            $calc_average = "";
            $promedio = "";
            $sector = "";
            $company_type = "";

            $calc_average = ($list[20] != "") ? 1 : 0;
            $calc_average += ($list[23] != "") ? 1 : 0;
            $calc_average += ($list[26] != "") ? 1 : 0;
            if ($calc_average != 0) {

                $montosArr = array($list[20], $list[23], $list[26]);
                $sumaMontos = array_sum($montosArr);

                $promedio = money_format_custom($sumaMontos / $calc_average);
            }

            $sector_value = $this->sgr_model->clae2013($list['5208']);
            $isPyme = $this->sgr_model->get_company_size($sector, $average_amount);
            $company_type = ($isPyme) ? "PyME" : "";


            $inner_table = '<table width="100%">';
            if ($list['19']) {
                $inner_table .= '<tr><td>' . $list['19'] . '</td><td align="right">' . money_format_custom($list['20']) . '</td><td>' . $list['21'] . '</td><tr>';
            }
            if ($list['22']) {
                $inner_table .= '<tr><td>' . $list['22'] . '</td><td align="right">' . money_format_custom($list['23']) . '</td><td>' . $list['24'] . '</td><tr>';
            }
            if ($list['25']) {
                $inner_table .= '<tr><td>' . $list['25'] . '</td><td align="right">' . money_format_custom($list['26']) . '</td><td>' . $list['27'] . '</td><tr>';
            }
            $inner_table .= '</table>';

            $new_list = array();
            $new_list['TIPO_OPERACION'] = $operation_type[$list['5779'][0]];
            $new_list['SOCIO'] = "(" . $list['5272'][0] . ") " . $partner_type[$list['5272'][0]] . "</br>" . $cuit . "</br>" . $brand_name;
            $new_list['LOCALIDAD'] = $list['1700'] . "</br>" . $partido[$list['1699'][0]] . "</br>" . $provincia[$list['4651'][0]] . "</br>[" . $list['1698'] . "]";
            $new_list['DIRECCION'] = $list['4653'] . "</br>" . "Nro." . $list['4654'] . "</br>Piso/Dto/Of." . $list['4655'] . " " . $list['4656'];
            $new_list['TELEFONO'] = "(" . $list['CODIGO_AREA'] . ") " . $list['1701'];
            $new_list['EMAIL'] = $list['1703'] . "</br>" . $list['1704'];
            $new_list['CODIGO_ACTIVIDAD'] = $list['5208'] . "<br>[SECTOR]<br>" . $sector_opt[$sector_value];
            $new_list['"ANIO"'] = $inner_table;
            $new_list['CONDICION_INSCRIPCION_AFIP'] = $promedio . "<br/>" . $company_type . "<br/>" . $afip_condition[$list['5596'][0]];
            $new_list['EMPLEADOS'] = $list['CANTIDAD_DE_EMPLEADOS'];
            $new_list['ACTA'] = "Tipo: " . $acta_type[$list['5253'][0]] . "<br/>Acta: " . $list['5255'] . "<br/>Nro." . $list['5254'] . "<br/>Efectiva:" . $list['FECHA_DE_TRANSACCION'];
            $new_list['MODALIDAD'] = "Modalidad " . $transaction_type[$list['5252'][0]] . "<br/>Capital Suscripto:" . $list['5597'] . "<br/>Acciones Suscriptas: " . $list['5250'] . "<br/>Capital Integrado: " . $list['5598'] . "<br/>Acciones Integradas:" . $list['5251'];
            $new_list['CEDENTE_CUIT'] = $list['5248'] . "<br/>" . $grantor_brand_name . "<br/>" . $transfer_characteristic[$list['5292'][0]];

            $rtn[] = $new_list;
        }
        return $rtn;
    }

}
