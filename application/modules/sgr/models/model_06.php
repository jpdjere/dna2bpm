<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_06 extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('sgr/tools');

        $this->anexo = '06';
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
            $this->sgr_id = (float) $sgr['id'];
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
         * @example ....
         * */
        $defdna = array(
            1 => 5779, //TIPO_OPERACION
            2 => 5272, //TIPO_SOCIO
            3 => 1695, //CUIT
            4 => 1693, //NOMBRE
            5 => 4651, //PROVINCIA
            6 => 1699, //"PARTIDO_MUNICIPIO_COMUNA",
            7 => 1700, //LOCALIDAD
            8 => 1698, //CODIGO_POSTAL
            9 => 4653, //CALLE
            10 => 4654, //NRO
            11 => 4655, //PISO
            12 => 4656, //DTO_OFICINA
            13 => "CODIGO_AREA", //CODIGO_AREA
            14 => 1701, //TELEFONO
            15 => 1703, //EMAIL
            16 => 1704, //WEB
            17 => 5208, //CODIGO_ACTIVIDAD_AFIP
            18 => 19, //"ANIO_MES1",
            19 => 20, //"MONTO",
            20 => 21, //"TIPO_ORIGEN",
            21 => 22, //"ANIO2",
            22 => 23, //"MONTO2",
            23 => 24, // "TIPO_ORIGEN2",
            24 => 25, //"ANIO3",
            25 => 26, //"MONTO3",
            26 => 27, //"TIPO ORIGEN3",
            27 => 5596, //CONDICION_INSCRIPCION_AFIP
            28 => "CANTIDAD_DE_EMPLEADOS", //CANTIDAD_DE_EMPLEADOS
            29 => 5253, //TIPO_ACTA
            30 => 5255, //FECHA_ACTA
            31 => 5254, //ACTA_NRO. 
            32 => "FECHA_DE_TRANSACCION", //FECHA_DE_TRANSACCION
            33 => 5252, //MODALIDAD
            34 => 5597, //CAPITAL_SUSCRIPTO            
            35 => 5598, //CAPITAL_INTEGRADO            
            36 => 5248, //CEDENTE_CUIT
        );


        $insertarr = array();
        foreach ($defdna as $key => $value) {
            $insertarr[$value] = $parameter[$key];

            $insertarr[1695] = (string) $insertarr[1695];

            /* INTEGERS */
            $insertarr[4654] = (int) $insertarr[4654];
            $insertarr[5208] = (int) $insertarr[5208];
            $insertarr[28] = (int) $insertarr[28];
            $insertarr['CANTIDAD_DE_EMPLEADOS'] = (int) $insertarr['CANTIDAD_DE_EMPLEADOS'];



            /* FLOAT */
            $insertarr[20] = (float) $insertarr[20];
            $insertarr[23] = (float) $insertarr[23];
            $insertarr[26] = (float) $insertarr[26];

            //--- Tipo de Operacion           
            if ($insertarr["5779"] == "INCORPORACION")
                $insertarr["5779"] = "1";
            if ($insertarr["5779"] == "INCREMENTO DE TENENCIA ACCIONARIA")
                $insertarr["5779"] = "2";
            if ($insertarr["5779"] == "DISMINUCION DE CAPITAL SOCIAL")
                $insertarr["5779"] = "3";



            //---Parseamos el tipo (hay que sacarlo del nombre)
            $explodenombre = explode(' ', $insertarr[1693]);
            //  echo strtoupper($explodenombre[count($explodenombre)-1]).'<br/>';
            $mistr = strtoupper($explodenombre[count($explodenombre) - 1]);

            if (stristr($mistr, "S.A"))
                $insertarr[1694] = "1";
            if (stristr($mistr, "S.C.A"))
                $insertarr[1694] = "1";
            if (stristr($mistr, "S.R.L"))
                $insertarr[1694] = "2";
            if (stristr($mistr, "SRL"))
                $insertarr[1694] = "2";
            if (stristr($mistr, "S.H"))
                $insertarr[1694] = "7";

            //--- Metemo & metemo la Provincia
            if ($insertarr[4651] == "CAPITAL FEDERAL")
                $insertarr[4651] = "CABA";
            if ($insertarr[4651] == "BUENOS AIRES")
                $insertarr[4651] = "BA";

            if ($insertarr[4651] == "BUENOS AIRES INTERIOR")
                $insertarr[4651] = "BA";
            if ($insertarr[4651] == "BUENOS AIRES CONOURBANO")
                $insertarr[4651] = "BA";

            if ($insertarr[4651] == "CATAMARCA")
                $insertarr[4651] = "CAT";
            if ($insertarr[4651] == "CORDOBA")
                $insertarr[4651] = "CBA";
            if ($insertarr[4651] == "CHUBUT")
                $insertarr[4651] = "CH";
            if ($insertarr[4651] == "CHACO")
                $insertarr[4651] = "CHA";
            if ($insertarr[4651] == "CORRIENTES")
                $insertarr[4651] = "CTES";
            if ($insertarr[4651] == "ENTRE RIOS")
                $insertarr[4651] = "ER";
            if ($insertarr[4651] == "FORMOSA")
                $insertarr[4651] = "FOR";
            if ($insertarr[4651] == "JUJUY")
                $insertarr[4651] = "JUJ";
            if ($insertarr[4651] == "LA PAMPA")
                $insertarr[4651] = "LP";
            if ($insertarr[4651] == "LA RIOJA")
                $insertarr[4651] = "LR";
            if ($insertarr[4651] == "MISIONES")
                $insertarr[4651] = "MIS";
            if ($insertarr[4651] == "MENDOZA")
                $insertarr[4651] = "MZA";
            if ($insertarr[4651] == "NEUQUEN")
                $insertarr[4651] = "NEU";
            if ($insertarr[4651] == "RIO NEGRO")
                $insertarr[4651] = "RN";
            if ($insertarr[4651] == "SALTA")
                $insertarr[4651] = "SAL";
            if ($insertarr[4651] == "SANTA CRUZ")
                $insertarr[4651] = "SC";
            if ($insertarr[4651] == "SANTIAGO DEL ESTERO")
                $insertarr[4651] = "SDE";
            if ($insertarr[4651] == "SANTA FE")
                $insertarr[4651] = "SF";
            if ($insertarr[4651] == "SAN JUAN")
                $insertarr[4651] = "SJ";
            if ($insertarr[4651] == "SAN LUIS")
                $insertarr[4651] = "SL";
            if ($insertarr[4651] == "TIERRA DEL FUEGO")
                $insertarr[4651] = "TDF";
            if ($insertarr[4651] == "TUCUMAN")
                $insertarr[4651] = "TUC";

            //Regimen ante AFIP
            if ($insertarr[5596] == "EXENTO")
                $insertarr[5596] = "3";
            if ($insertarr[5596] == "INSCRIPTO")
                $insertarr[5596] = "1";
            if ($insertarr[5596] == "MONOTRIBUTISTA")
                $insertarr[5596] = "2";
            if ($insertarr[5596] == "NO CATEGORIZADO")
                $insertarr[5596] = "";

            //Tipo de Acta
            if ($insertarr[5253] == "")
                $insertarr[5253] = "";
            if ($insertarr[5253] == "ACTA DEL CONSEJO DE ADMINISTRACION")
                $insertarr[5253] = "1";
            if ($insertarr[5253] == "ASAMBLEA ORDINARIA")
                $insertarr[5253] = "2";
            if ($insertarr[5253] == "ASAMBLEA CONSTITUTIVA")
                $insertarr[5253] = "3";

            //Modalidad            
            if ($insertarr[5252] == "TRANSFERENCIA")
                $insertarr[5252] = "1";
            if ($insertarr[5252] == "SUSCRIPCION")
                $insertarr[5252] = "2";

            //Formatos numricos para importes
            $insertarr[5597] = (int) str_replace(",", ".", $insertarr[5597]);
            $insertarr[5598] = (int) str_replace(",", ".", $insertarr[5598]);

            //  Arreglamo la caracteristica
            if ($insertarr[5292] == "DISMINUCION DE TENENCIA ACCIONARIA")
                $insertarr[5292] = "1";
            if ($insertarr[5292] == "DESVINCULACION")
                $insertarr[5292] = "2";
        }
        return $insertarr;
    }

    function save($parameter) {
        $period = $this->session->userdata['period'];
        $container = 'container.sgr_anexo_' . $this->anexo;

        list($arr['Y'], $arr['m'], $arr['d']) = explode("-", strftime("%Y-%m-%d", mktime(0, 0, 0, 1, -1 + $parameter[5255], 1900)));
        $parameter[5255] = $arr;
        $parameter['FECHA_DE_TRANSACCION'] = new MongoDate(strtotime(translate_for_mongo($parameter['FECHA_DE_TRANSACCION'])));

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
        $parameter['period_date'] = translate_period_date($period);
        $parameter['status'] = 'activo';
        $parameter['idu'] = $this->idu;


        /*
         * VERIFICO INCORPORACIONES
         */

        $anexoValues = $this->get_insert_data($this->anexo, $parameter['filename']);
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

    /* REVISAR */

    function save_period_check($parameter) {
        /* ADD PERIOD */
        $container = 'container.sgr_periodos';
        $period = $this->session->userdata['period'];

        $parameter['period_date'] = new MongoDate(strtotime(translate_period_date($period)));
        $id = $this->app->genid_sgr($container);
        $parameter['period'] = $period;
        $parameter['idu'] = $this->idu;
        /* TEMPORAL */
        $parameter['activated_on'] = date('Y-m-d h:i:s');
        $parameter['status'] = 'activo';
        $parameter['status'] = 'activo';

        /*
         * VERIFICO INCORPORACIONES
         */

        $anexoValues = $this->get_insert_data($this->anexo, $parameter['filename']);

        var_dump($this->anexo, $parameter['filename']);

        foreach ($anexoValues as $values) {



            /* Si es una incorporacion solo se activa al aprobar el Anexo 6.1 */
            if (in_array('1', $values["5779"])) {
                $parameter['status'] = 'activo';
                $parameter['pending_on'] = date('Y-m-d h:i:s');
            } else {
                $parameter['activated_on'] = date('Y-m-d h:i:s');
                $parameter['status'] = 'activo';
                /*
                 * ACTUALIZO EL ANEXO 61 "SIN MOVIMIENTOS" POR NO TENER INCORPORACIONES
                 */
                $parameter061 = array();
                $id061 = $this->app->genid_sgr($container);
                $parameter061['anexo'] = "061";
                $parameter061['filename'] = "SIN MOVIMIENTOS";
                $parameter061['period'] = $period;
                $parameter061['status'] = 'activo';
                $parameter061['idu'] = (float) $this->idu;
                $parameter061['sgr_id'] = $this->sgr_id;


                $get_period = $this->sgr_model->get_period_info('061', $this->sgr_id, $period);
                if ($get_period['id']) {
                    $this->update_period($get_period['id'], $get_period['status']);
                }
                $result = $this->app->put_array_sgr($id061, $container, $parameter061);
            }
        }

        /*
         * VERIFICO PENDIENTE           
         */
        $get_period = $this->sgr_model->get_period_info($this->anexo, $this->sgr_id, $period);
        if ($get_period['id']) {
            $this->update_period($get_period['id'], $get_period['status']);
        }
        $result = $this->app->put_array_sgr($id, $container, $parameter);
        $out = array('status' => 'error');
        if ($result) {
            /* BORRO SESSION RECTIFY */
            $this->session->unset_userdata('rectify');
            $this->session->unset_userdata('others');
            $this->session->unset_userdata('period');
            $out = array('status' => 'ok');
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

    function get_anexo_data_tmp($anexo, $parameter) {

        $rtn = array();
        $container = 'container.sgr_anexo_' . $anexo;
        $fields = array('5779', '1695', '5272', '5779', '5248', '5208', '5779', '20', '23', '26', '5597', '5598', 'FECHA_DE_TRANSACCION', 'filename', 'period', 'sgr_id', 'origin', 'CANTIDAD_DE_EMPLEADOS');
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

        foreach ($result as $list) {
            /* Vars */
            $cuit = str_replace("-", "", $list['1695']);
            $this->load->model('padfyj_model');
            $brand_name = $this->padfyj_model->search_name($cuit);
            $brand_name = ($brand_name) ? $brand_name : $list['1693'];
            $grantor_brand_name = $this->padfyj_model->search_name($list['5248']);

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


            /* CARACTER CEDENTE */
            
            if ($list['5248']) {
                $subscribed = $this->shares_print($list['5248'], $list['5272'][0], 5597, $list['period']);
                $integrated = $this->shares_print($list['5248'], $list['5272'][0], 5598, $list['period']);
                $grantor_balance = $subscribed . "." . $integrated;
            }

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
            $new_list['ACTA'] = "Tipo: " . $acta_type[$list['5253'][0]] . "<br/>Acta: " . $list['5255'] . "<br/>Nro." . $list['5254'] . "<br/>Efectiva:" . mongodate_to_print($list['FECHA_DE_TRANSACCION']);
            $new_list['MODALIDAD'] = "Modalidad " . $transaction_type[$list['5252'][0]] . "<br/>Capital Suscripto:" . $list['5597'] . "<br/>Acciones Suscriptas: " . $list['5250'] . "<br/>Capital Integrado: " . $list['5598'] . "<br/>Acciones Integradas:" . $list['5251'];
            $new_list['CEDENTE_CUIT'] = $list['5248'] . "<br/>" . $grantor_brand_name . "<br/>" . $transfer_characteristic[$list['5292'][0]] . "" . $grantor_balance;

            $rtn[] = $new_list;
        }
        return $rtn;
    }

    /*
     * CLEAN ANEXO DATA
     */

    function get_insert_data($anexo, $parameter) {

        $rtn = array();
        $container = 'container.sgr_anexo_' . $anexo;
        $query = array("filename" => $parameter);
        $result = $this->mongo->sgr->$container->find($query);

        foreach ($result as $list) {
            $rtn[] = $list;
        }
        return $rtn;
    }

    function get_partner_period($cuit, $get_period) {

        $anexo = $this->anexo;
        $period = 'container.sgr_periodos';
        $container = 'container.sgr_anexo_' . $anexo;

        $query = array(
            'anexo' => $anexo,
            'sgr_id' => $this->sgr_id,
            'period' => $get_period,
            'status' => 'activo'
        );

        $result_period = $this->mongo->sgr->$period->findOne($query);
        $query_partner = array(
            'filename' => $result_period['filename'],
            1695 => $cuit
        );

        $result_partner = $this->mongo->sgr->$container->findOne($query_partner);

        return $result_partner;
    }

    /* FROM OUTSIDE (ANOTHER ANEXO) */

    function get_partner_left($cuit) {
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;

        $period_value = $this->session->userdata['period'];

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active($anexo);

        $return_result = array();
        foreach ($result as $list) {
            $new_query = array(
                'filename' => $list['filename'],
                1695 => $cuit
            );

            $new_result = $this->mongo->sgr->$container->findOne($new_query);

            if ($new_result)
                $return_result[] = $new_result;
        }
        return $return_result;
    }

    function get_partner_print($cuit) {
        $anexo = $this->anexo;
        $period = 'container.sgr_periodos';
        $container = 'container.sgr_anexo_' . $anexo;
        $period_value = $this->session->userdata['period'];

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_just_active($anexo);

        $return_result = array();
        foreach ($result as $list) {
            $new_query = array(
                'filename' => $list['filename'],
                1695 => $cuit
            );

            $new_result = $this->mongo->sgr->$container->findOne($new_query);
            if ($new_result)
                $return_result[] = $new_result;
        }

        return $return_result;
    }

    /* PARTNERS INFO ONLY CURRENT ANEXO */

    function new_count_partners($partners_arr, $get_period = null) {
        $get_error = false;
        $anexo = $this->anexo;
        $container_period = 'container.sgr_periodos';
        $container_anexo = 'container.sgr_anexo_' . $anexo;

        $query = array(
            'anexo' => $anexo,
            'sgr_id' => $this->sgr_id,
            'status' => 'activo',
            'period' => $get_period
        );


        /* PARTNERS ARRAY */
        $add = array();
        foreach ($partners_arr as $each_partner) {
            $add[] = $each_partner;
        }


        $period_arr = $this->mongo->sgr->$container_period->findOne($query);
        $filename = $period_arr['filename'];
        foreach ($period_arr as $list) {

            $anexo_query = array(
                'filename' => $filename,
                "5779" => "1",
                1695 => array('$in' => $add),
            );

            $get_error = array();
            $new_result = $this->mongo->sgr->$container_anexo->find($anexo_query);
            foreach ($new_result as $new_list) {
                $get_error[] = $new_list[1695];
            }

            $anexo_query_total = array(
                'filename' => $filename,
                "5779" => "1"
            );

            $get_error_total = array();
            $new_result_total = $this->mongo->sgr->$container_anexo->find($anexo_query_total);
            foreach ($new_result_total as $new_list_total) {
                $get_error_total[] = $new_list_total[1695];
            }
        }

        if ($get_error || $get_error_total) {


            $count_xls = count($partners_arr);
            $register = count($get_error);
            $register_total = count($get_error_total);

            $num = array($count_xls, $register, $register_total);

            if (max($num) == min($num)) {
                return false;
            }


            $key = array_search(max($num), $num);


            switch ($key) {
                case 0:
                    $error_value = "VG.3";
                    break;
                case 1:
                    $error_value = "VG.4";
                    break;
                case 2:
                    $error_value = "VG.4";
                    break;
            }

            //var_dump($key, $count_xls, $register, $register_total);

            return $error_value;
        }
    }

    /* ACCIONES COMPRA/VENTA X SGR
     * Compra/venta por socio
     */

    function shares($cuit, $partner_type = null, $field = 5597) {
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;
        $period_value = $this->session->userdata['period'];

        $buy_result_arr = array();
        $sell_result_arr = array();

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active($anexo, $period_value);

        /* FIND ANEXO */
        foreach ($result as $list) {

            /* BUY */
            $new_query = array(
                1695 => $cuit,
                'filename' => $list['filename']
            );
            if ($partner_type)
                $new_query[5272] = $partner_type;

            $buy_result = $this->mongo->sgr->$container->find($new_query);
            foreach ($buy_result as $buy) {
                $buy_result_arr[] = $buy[$field];
            }

            /* SELL */
            $new_query = array(
                5248 => $cuit,
                'filename' => $list['filename']
            );
            if ($partner_type)
                $new_query[5272] = $partner_type;

            $sell_result = $this->mongo->sgr->$container->find($new_query);
            foreach ($sell_result as $sell) {
                $sell_result_arr[] = $sell[$field];
            }
        }

        $buy_sum = array_sum($buy_result_arr);
        $sell_sum = array_sum($sell_result_arr);
        $balance = $buy_sum - $sell_sum;
        return $balance;
    }

    /* ACCIONES COMPRA/VENTA X SGR de socios que estan activos en el sistema
     * Compra/venta por socio
     */

    function shares_active_left($cuit, $partner_type = null, $field = 5597) {

        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;
        $period_value = $this->session->userdata['period'];

        $buy_result_arr = array();
        $sell_result_arr = array();

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active($anexo);

        /* FIND ANEXO */
        foreach ($result as $list) {

            /* BUY */
            $new_query = array(
                1695 => $cuit,
                'filename' => $list['filename']
            );
            if ($partner_type)
                $new_query[5272] = $partner_type;

            $buy_result = $this->mongo->sgr->$container->find($new_query);
            foreach ($buy_result as $buy) {
                $buy_result_arr[] = $buy[$field];
            }

            /* SELL */
            $new_query = array(
                5248 => $cuit,
                'filename' => $list['filename']
            );
            if ($partner_type)
                $new_query[5272] = $partner_type;

            $sell_result = $this->mongo->sgr->$container->find($new_query);
            foreach ($sell_result as $sell) {
                $sell_result_arr[] = $sell[$field];
            }
        }

        $buy_sum = array_sum($buy_result_arr);
        $sell_sum = array_sum($sell_result_arr);
        $balance = $buy_sum - $sell_sum;
        return $balance;
    }

    function shares_print($cuit, $partner_type = null, $field = 5597, $period_value) {
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;
        
        $buy_result_arr = array();
        $sell_result_arr = array();

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active_print($anexo, $period_value);
       // return true;
        /* FIND ANEXO */
        foreach ($result as $list) {
            /* BUY */
            $new_query = array(
                1695 => $cuit,
                'filename' => $list['filename']
            );
            if ($partner_type)
                $new_query[5272] = $partner_type;

            $buy_result = $this->mongo->sgr->$container->find($new_query);
            foreach ($buy_result as $buy) {
                $buy_result_arr[] = $buy[$field];
            }

            /* SELL */
            $new_query = array(
                5248 => $cuit,
                'filename' => $list['filename']
            );
            if ($partner_type)
                $new_query[5272] = $partner_type;

            $sell_result = $this->mongo->sgr->$container->find($new_query);
            foreach ($sell_result as $sell) {
                $sell_result_arr[] = $sell[$field];
            }
        }

        $buy_sum = array_sum($buy_result_arr);
        $sell_sum = array_sum($sell_result_arr);
        $balance = $buy_sum - $sell_sum;
        return $balance;
    }

    function shares_active_left_until_date($cuit, $date) {

        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;

        $buy_result_arr = array();
        $sell_result_arr = array();

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active($anexo);

        /* FIND ANEXO */
        foreach ($result as $list) {

            /* BUY */
            $new_query = array(
                1695 => $cuit,
                'filename' => $list['filename'],
                5272 => 'B'
                , 'FECHA_DE_TRANSACCION' => array(
                    '$lte' => $date
                )
            );

            $buy_result = $this->mongo->sgr->$container->find($new_query);
            foreach ($buy_result as $buy) {
                $buy_result_arr[] = $buy[5597];
            }

            /* SELL */
            $new_query = array(
                5248 => $cuit,
                'filename' => $list['filename'],
                5272 => 'B'
                , 'FECHA_DE_TRANSACCION' => array(
                    '$lte' => $date
                )
            );

            $sell_result = $this->mongo->sgr->$container->find($new_query);
            foreach ($sell_result as $sell) {
                $sell_result_arr[] = $sell[5597];
            }
        }

        $buy_sum = array_sum($buy_result_arr);
        $sell_sum = array_sum($sell_result_arr);
        $balance = $buy_sum - $sell_sum;
        return $balance;
    }

    /* ACCIONES COMPRA/VENTA todas las otras SGR
     * Compra/venta por socio
     */

    function shares_others_sgrs($cuit, $partner_type, $field = 5597) {

        $anexo = $this->anexo;
        $period = 'container.sgr_periodos';
        $container = 'container.sgr_anexo_' . $anexo;
        $partner_type = ($partner_type == "A") ? "B" : "A";

        $buy_result_arr = array();
        $sell_result_arr = array();

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active_other_sgrs($anexo);

        $return_result = array();
        /* FIND ANEXO */
        foreach ($result as $list) {
            /* BUY */
            $new_query = array(
                1695 => $cuit,
                'filename' => $list['filename'],
                5272 => $partner_type
            );

            $buy_result = $this->mongo->sgr->$container->findOne($new_query);
            if ($buy_result) {
                $buy_result_arr[] = $buy_result[$field];
            }

            /* SELL */
            $new_query = array(
                5248 => $cuit,
                'filename' => $list['filename'],
                5272 => $partner_type
            );


            $sell_result = $this->mongo->sgr->$container->findOne($new_query);
            if ($sell_result) {
                $sell_result_arr[] = $sell_result[$field];
            }
        }

        $buy_sum = array_sum($buy_result_arr);
        $sell_sum = array_sum($sell_result_arr);
        $balance = $buy_sum - $sell_sum;
        return $balance;
    }

    /* TIPO DE SOCIO */

    function partner_type($cuit) {
        $anexo = $this->anexo;
        $info_06 = $this->get_partner_print($cuit);

        foreach ($info_06 as $data) {
            return $data[5272][0];
        }
    }

}
