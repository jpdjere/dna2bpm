<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_06 extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('sgr/tools');
        $this->load->model('padfyj_model');

        $this->anexo = '06';


        /* Additional SGR users */
        $this->load->model('sgr/sgr_model');
        $additional_users = $this->sgr_model->additional_users($this->session->userdata('iduser'));
        $this->idu = (isset($additional_users)) ? $additional_users['sgr_idu'] : $this->session->userdata('iduser');


        /* SWITCH TO SGR DB */
        $this->load->library('cimongo/Cimongo.php', '', 'sgr_db');
        $this->sgr_db->switch_db('sgr');

        if (!$this->idu) {
            header("$this->module_url/user/logout");
        }

        /* DATOS SGR */
        $sgrArr = $this->sgr_model->get_sgr();
        foreach ($sgrArr as $sgr) {
            $this->sgr_id = (float) $sgr['id'];
            $this->sgr_nombre = $sgr['1693'];
            $this->sgr_cuit = $sgr['1695'];
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

         
        /* FIX INFORMATION */
        $parameter = array_map('trim', $parameter); 
        $parameter = array_map('addSlashes', $parameter);
          
        
        $insertarr = array();
        foreach ($defdna as $key => $value) {
            $insertarr[$value] = $parameter[$key];

            $insertarr[1695] = (string) $insertarr[1695];
            $insertarr[5248] = (string) $insertarr[5248];
            $insertarr[5272] = strtoupper($insertarr[5272]);

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
            if (strtoupper($insertarr["5779"]) == "INCORPORACION")
                $insertarr["5779"] = "1";
            if (strtoupper($insertarr["5779"]) == "INCREMENTO TENENCIA ACCIONARIA")
                $insertarr["5779"] = "2";
            if (strtoupper($insertarr["5779"]) == "DISMINUCION DE CAPITAL SOCIAL")
                $insertarr["5779"] = "3";
            if (strtoupper($insertarr["5779"]) == "INTEGRACION PENDIENTE")
                $insertarr["5779"] = "4";
            if (strtoupper($insertarr["5779"]) == "FAE CORP")
                $insertarr["5779"] = "5";
            if (strtoupper($insertarr["5779"]) == "FAE GARANTAXI")
                $insertarr["5779"] = "6";
            if (strtoupper($insertarr["5779"]) == "FAE CUYO PYMES")
                $insertarr["5779"] = "7";
            if (strtoupper($insertarr["5779"]) == "FAE INCAA")
                $insertarr["5779"] = "8";
            if (strtoupper($insertarr["5779"]) == "FAE PMSA")
                $insertarr["5779"] = "9";
            if (strtoupper($insertarr["5779"]) == "FAE PRODAF")
                $insertarr["5779"] = "10";
            if (strtoupper($insertarr["5779"]) == "FAE CATAMARCA")
                $insertarr["5779"] = "11";
            if (strtoupper($insertarr["5779"]) == "FAE SANTA CRUZ")
                $insertarr["5779"] = "12";
            if (strtoupper($insertarr["5779"]) == "FAE SANTA FE")
                $insertarr["5779"] = "13";
            if (strtoupper($insertarr["5779"]) == "FAE SOCO RIL")
                $insertarr["5779"] = "14";
            if (strtoupper($insertarr["5779"]) == "FAE YAGUAR")
                $insertarr["5779"] = "15";
            if (strtoupper($insertarr["5779"]) == "FUSION")
                $insertarr["5779"] = "16";



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
            if (strtoupper($insertarr[4651]) == "CAPITAL FEDERAL")
                $insertarr[4651] = "CABA";
            if (strtoupper($insertarr[4651]) == "BUENOS AIRES")
                $insertarr[4651] = "BA";

            if (strtoupper($insertarr[4651]) == "BUENOS AIRES INTERIOR")
                $insertarr[4651] = "BA";
            if (strtoupper($insertarr[4651]) == "BUENOS AIRES CONOURBANO")
                $insertarr[4651] = "BA";

            if (strtoupper($insertarr[4651]) == "CATAMARCA")
                $insertarr[4651] = "CAT";
            if (strtoupper($insertarr[4651]) == "CORDOBA")
                $insertarr[4651] = "CBA";
            if (strtoupper($insertarr[4651]) == "CHUBUT")
                $insertarr[4651] = "CH";
            if (strtoupper($insertarr[4651]) == "CHACO")
                $insertarr[4651] = "CHA";
            if (strtoupper($insertarr[4651]) == "CORRIENTES")
                $insertarr[4651] = "CTES";
            if (strtoupper($insertarr[4651]) == "ENTRE RIOS")
                $insertarr[4651] = "ER";
            if (strtoupper($insertarr[4651]) == "FORMOSA")
                $insertarr[4651] = "FOR";
            if (strtoupper($insertarr[4651]) == "JUJUY")
                $insertarr[4651] = "JUJ";
            if (strtoupper($insertarr[4651])  == "LA PAMPA")
                $insertarr[4651] = "LP";
            if (strtoupper($insertarr[4651]) ==  "LA RIOJA")
                $insertarr[4651] = "LR";
            if (strtoupper($insertarr[4651]) == "MISIONES")
                $insertarr[4651] = "MIS";
            if (strtoupper($insertarr[4651]) == "MENDOZA")
                $insertarr[4651] = "MZA";
            if (strtoupper($insertarr[4651]) == "NEUQUEN")
                $insertarr[4651] = "NEU";
            if (strtoupper($insertarr[4651]) == "RIO NEGRO")
                $insertarr[4651] = "RN";
            if (strtoupper($insertarr[4651]) == "SALTA")
                $insertarr[4651] = "SAL";
            if (strtoupper($insertarr[4651]) == "SANTA CRUZ")
                $insertarr[4651] = "SC";
            if (strtoupper($insertarr[4651]) == "SANTIAGO DEL ESTERO")
                $insertarr[4651] = "SDE";
            if (strtoupper($insertarr[4651]) == "SANTA FE")
                $insertarr[4651] = "SF";
            if (strtoupper($insertarr[4651]) == "SAN JUAN")
                $insertarr[4651] = "SJ";
            if (strtoupper($insertarr[4651]) == "SAN LUIS")
                $insertarr[4651] = "SL";
            if (strtoupper($insertarr[4651]) == "TIERRA DEL FUEGO")
                $insertarr[4651] = "TDF";
            if (strtoupper($insertarr[4651]) == "TUCUMAN")
                $insertarr[4651] = "TUC";

            //Regimen ante AFIP
            if (strtoupper($insertarr[5596]) == "EXENTO")
                $insertarr[5596] = "3";
            if (strtoupper($insertarr[5596]) == "INSCRIPTO")
                $insertarr[5596] = "1";
            if (strtoupper($insertarr[5596]) == "MONOTRIBUTISTA")
                $insertarr[5596] = "2";
            if (strtoupper($insertarr[5596]) == "NO CATEGORIZADO")
                $insertarr[5596] = "";

            //Tipo de Acta
            if (strtoupper($insertarr[5253]) == "")
                $insertarr[5253] = "";
            if (strtoupper($insertarr[5253]) == "ACTA DEL CONSEJO DE ADMINISTRACION")
                $insertarr[5253] = "1";
            if (strtoupper($insertarr[5253]) == "ASAMBLEA ORDINARIA")
                $insertarr[5253] = "2";
            if (strtoupper($insertarr[5253]) == "ASAMBLEA CONSTITUTIVA")
                $insertarr[5253] = "3";

            //Modalidad            
            if (strtoupper($insertarr[5252]) == "TRANSFERENCIA")
                $insertarr[5252] = "1";
            if (strtoupper($insertarr[5252]) == "SUSCRIPCION")
                $insertarr[5252] = "2";
                
                
           

            //Formatos numricos para importes
            $insertarr[5597] = (int) $insertarr[5597];
            $insertarr[5598] = (int) $insertarr[5598];
        }



        if ($insertarr[5248]) {
            /*
              1     Disminución de tenencia accionaria  null
              2     Desvinculación  null
             */
            $ctrl_date = (isset($insertarr['FECHA_DE_TRANSACCION'])) ? $insertarr['FECHA_DE_TRANSACCION'] : $insertarr[5255];
            $query_period = period_before($this->session->userdata['period']); //period -1
            $transaction_date = strftime("%Y-%m-%d", mktime(0, 0, 0, 1, -1 + $ctrl_date, 1900));
            $integrated_calc = $this->shares_print($insertarr[5248], $insertarr[5272], 5598, $query_period, $transaction_date);

            $integrated_total = abs((int) $integrated_calc - $insertarr[5598]);

            $grantor_type = ($integrated_total == 0) ? "2" : "1";
        }


        $insertarr[5292] = $grantor_type;

        return $insertarr;
    }

    function save($parameter) {

        $period = $this->session->userdata['period'];
        $container = 'container.sgr_anexo_' . $this->anexo;

        list($arr['Y'], $arr['m'], $arr['d']) = explode("-", strftime("%Y-%m-%d", mktime(0, 0, 0, 1, -1 + $parameter[5255], 1900)));
        $parameter[5255] = $arr;
        $parameter['FECHA_DE_TRANSACCION'] = new MongoDate(strtotime(translate_for_mongo($parameter['FECHA_DE_TRANSACCION'])));

        $parameter['period'] = $period;
        $parameter['origen'] = "2013";

        $id = (float) $this->app->genid_sgr($container);

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
         * VERIFICO INCORPORACIONES
         */


        /* INCORPORATION VALIDATIONS */
        $is_pending = false;

        $anexoValues = $this->get_insert_data($this->anexo, $parameter['filename']);
        foreach ($anexoValues as $values) {
            /* Si es una incorporacion solo se activa al aprobar el Anexo 6.1 */
            if (in_array('1', $values["5779"])) {
                $is_pending = true; //               
            }
        }

        /*
         * VERIFICO PENDIENTE           
         */
        $get_period = $this->sgr_model->get_current_period_info($this->anexo, $period);
        $get_period_061 = $this->sgr_model->get_current_period_info('061', $period);

        /* UPDATE */
        if (isset($get_period['status']))
            $this->update_period($get_period['id'], $get_period['status']);

        /* INCORPORATION STATUS */
        if ($is_pending) {
            $parameter['status'] = 'pendiente';
            $parameter['pending_on'] = date('Y-m-d h:i:s');

            if ($get_period_061['id'])
                $this->update_period($get_period_061['id'], $get_period['status']);
        } else {


            $parameter['activated_on'] = date('Y-m-d h:i:s');
            $parameter['status'] = 'activo';

            /*
             * RECTIFICO SI EXISTE Y ACTUALIZO EL ANEXO 6.1 "SIN MOVIMIENTOS" POR NO TENER INCORPORACIONES
             */

            if ($get_period_061['id'])
                $this->update_period($get_period_061['id'], $get_period['status']);

            $parameter061 = array();
            $id061 = $this->app->genid_sgr($container);
            $parameter061['anexo'] = "061";
            $parameter061['filename'] = "SIN MOVIMIENTOS";
            $parameter061['period'] = $period;
            $parameter061['period_date'] = translate_period_date($period);
            $parameter061['status'] = 'activo';
            $parameter061['idu'] = (float) $this->idu;
            $parameter061['sgr_id'] = $this->sgr_id;
            $parameter061['origen'] = "2013";
            $result06 = $this->app->put_array_sgr($id061, $container, $parameter061);
        }

        /* INSERT MAIN ANEXO */
        $result = $this->app->put_array_sgr($id, $container, $parameter);

        if (isset($result)) {
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

        $options = array('upsert' => true, 'w' => 1);
        $container = 'container.sgr_periodos';
        $query = array('id' => (float) $id);
        $parameter = array(
            'status' => 'rectificado',
            'rectified_on' => date('Y-m-d h:i:s'),
            'others' => $this->session->userdata['others'],
            'reason' => $this->session->userdata['rectify']
        );
        $rs = $this->mongowrapper->sgr->$container->update($query, array('$set' => $parameter), $options);
        return $rs['err'];
    }

    function get_anexo_footer($anexo, $parameter) {
        
        /*Support #23476*/
       
//        $rtn = array();
//        $container = 'container.sgr_anexo_' . $anexo;
//        $field = array('5208');
//        $query = array("filename" => $parameter);
//        $result = $this->mongowrapper->sgr->$container->find($query, $field);
//
//        
//        foreach ($result as $list) {
//            $sector_value = $this->sgr_model->clae2013_forbidden($list[5208], $list[5272][0]);
//            if ($sector_value) {
//                return "Se declara bajo juramento que los Socios Partícipes cuyas actividades son prohibidas por el Artículo 12 del Anexo de la Resolución SEPyMEyDR Nº 212/2013, cumplen con las excepciones allí establecidas";
//            }
//        }
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
        $result = $this->mongowrapper->sgr_tmp->$container->find($query, $fields);

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
        $result = $this->mongowrapper->sgr->$container->find($query);
        /* TABLE DATA */
        return $this->ui_table($result);
    }

    

    function ui_table($result) {
        foreach ($result as $list) {
            /* Vars */
            $cuit = null;
            $partner_data = null;
            $partner_add_data = null;
            $partner_phone = null;
            $partner_web = null;

            $this->load->model('padfyj_model');

            if ($list['1695'] != "") {
                $cuit = str_replace("-", "", $list['1695']);
                $brand_name = $this->padfyj_model->search_name($cuit);
                $brand_name = ($brand_name) ? : $list['1693'];
            }

            if (isset($list['5248']))
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
            $sector_opt = $this->app->get_ops(494);

            $transaction_date = ($list['FECHA_DE_TRANSACCION']) ? mongodate_to_print($list['FECHA_DE_TRANSACCION']) : "N/A";

            $calc_average = "";
            $promedio = "";
            $sector_value = "";
            $company_type = "";

            $calc_average = ($list[20] != "") ? 1 : 0;
            $calc_average += ($list[23] != "") ? 1 : 0;
            $calc_average += ($list[26] != "") ? 1 : 0;
            if ($calc_average != 0) {

                $montosArr = array($list[20], $list[23], $list[26]);
                $sumaMontos = array_sum($montosArr);

                $promedio = money_format_custom($sumaMontos / $calc_average);
            }


            $sector_value = $this->sgr_model->clae2013($list['5208'], $list['5272'][0], $resolution);


            $isPyme = $this->sgr_model->get_company_size($sector_value, $promedio);
            $company_type = ($isPyme) ? "PyME" : "";


            /* CARACTER CEDENTE */

            if ($list['5248']) {
                $grantor_type_text = "Caracter del Cedente:</br>";
                $grantor_type = $grantor_type_text . $transfer_characteristic[$list['5292'][0]];
            }


            $print_table = null;
            $inner_table = null;

            if ($list['19']) {
                $inner_table .= '<tr><td>' . $list['19'] . '</td><td align="right">' . money_format_custom($list['20']) . '</td><td>' . $list['21'] . '</td></tr>';
            }
            if ($list['22']) {
                $inner_table .= '<tr><td>' . $list['22'] . '</td><td align="right">' . money_format_custom($list['23']) . '</td><td>' . $list['24'] . '</td></tr>';
            }
            if ($list['25']) {
                $inner_table .= '<tr><td>' . $list['25'] . '</td><td align="right">' . money_format_custom($list['26']) . '</td><td>' . $list['27'] . '</td></tr>';
            }


            $cuit_grantor = "";

            if (isset($inner_table)) {
                $print_table = '<table width="100%">';
                $print_table .= $inner_table;
                $print_table .= '</table>';
            }

            $codigo_actividad = ($list['5208'] == "0") ? "-" : $list['5208'] . "<br>[SECTOR]<br>" . $sector_opt[$sector_value];


            $zip_address = null;
            $cuit_grantor = null;
            $area_code = null;
            $address = null;

            if ($list['1698'] != "")
                $zip_address = "</br>[" . $list['1698'] . "]";



            if (isset($list['5248']) && $list['5248'] != "")
                $cuit_grantor = $list['5248'] . "<br/>" . $grantor_brand_name . "<br/>" . $grantor_type;

            if ($list['CODIGO_AREA'] != "")
                $area_code = "(" . $list['CODIGO_AREA'] . ") ";

            if ($list['4653'] != "")
                $address = $list['4653'] . "</br>" . "Nro." . $list['4654'] . "</br>Piso/Dto/Of." . $list['4655'] . " " . $list['4656'];


            @$partido_value = (isset($list['1699'][0])) ? $partido[$list['1699'][0]] : null;
            $provincia_value = (isset($list['4651'][0])) ? $provincia[$list['4651'][0]] : null;
            $afip_condition_value = (isset($list['5596'][0])) ? $afip_condition[$list['5596'][0]] : null;


            if (isset($cuit)) {
                $partner_data = $list['5272'][0] . "</br>" . $cuit . "</br>" . $brand_name;
                $partner_add_data = $list['1700'] . "</br>" . $partido_value . "</br>" . $provincia_value . $zip_address;

                $partner_phone = $area_code . $list['1701'];
                $partner_web = $list['1703'] . "</br>" . $list['1704'];
            } else {
                $partner_data = $list['5272'][0];
            }



            $new_list = array();
            $new_list['TIPO_OPERACION'] = $operation_type[$list['5779'][0]];
            $new_list['SOCIO'] = $partner_data;
            $new_list['LOCALIDAD'] = $partner_add_data;
            $new_list['DIRECCION'] = $address;
            $new_list['TELEFONO'] = $partner_phone;
            $new_list['EMAIL'] = $partner_web;
            $new_list['CODIGO_ACTIVIDAD'] = $codigo_actividad;
            $new_list['"ANIO"'] = $print_table;
            $new_list['CONDICION_INSCRIPCION_AFIP'] = $promedio . "<br/>" . $company_type . "<br/>" . $afip_condition_value;
            $new_list['EMPLEADOS'] = $list['CANTIDAD_DE_EMPLEADOS'];
            $new_list['ACTA'] = "Tipo: " . $acta_type[$list['5253'][0]] . "<br/>Acta: " . $list['5255'] . "<br/>Nro." . $list['5254'] . "<br/>Efectiva:" . $transaction_date;
            $new_list['MODALIDAD'] = "Modalidad " . $transaction_type[$list['5252'][0]] . "<br/>Capital Suscripto:" . $list['5597'] . "<br/>Capital Integrado: " . $list['5598'];
            $new_list['CEDENTE_CUIT'] = $cuit_grantor;

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
        $result = $this->mongowrapper->sgr->$container->find($query);

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

        $result_period = $this->mongowrapper->sgr->$period->findOne($query);
        $query_partner = array(
            'filename' => $result_period['filename'],
            1695 => $cuit
        );

        $result_partner = $this->mongowrapper->sgr->$container->findOne($query_partner);

        return $result_partner;
    }

    /* FROM OUTSIDE (ANOTHER ANEXO) */

    function get_partners_ws($date_from, $date_to) {
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;


        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active_ws($anexo, $date_from, $date_to);

        $return_result = array();

        foreach ($result as $list) {
            $new_query = array(
                'filename' => $list['filename']
            );

            $new_result = $this->mongowrapper->sgr->$container->find($new_query);

            foreach ($new_result as $nr) {
                $return_result[] = $nr;
            }
        }
        return $return_result;
    }

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

            $new_result = $this->mongowrapper->sgr->$container->findOne($new_query);

            if ($new_result)
                $return_result[] = $new_result;
        }
        return $return_result;
    }

    function get_partner_stand_alone($cuit) {
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;

        $new_query = array(
            1695 => $cuit
        );

        $new_result = $this->mongowrapper->sgr->$container->findOne($new_query);

        if ($new_result)
            $return_result[] = $new_result;

        return $return_result;
    }

    /* FRE */

    function get_partner_left_fre($cuit) {
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;

        $period_value = $this->session->userdata['period'];

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active_fre($anexo);

        $return_result = array();
        foreach ($result as $list) {
            $new_query = array(
                'filename' => $list['filename'],
                1695 => $cuit
            );

            $new_result = $this->mongowrapper->sgr->$container->findOne($new_query);

            if ($new_result)
                $return_result[] = $new_result;
        }
        return $return_result;
    }

    function shares_active_left_until_date_fre($cuit, $date) {

        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;

        $buy_result_arr = array();
        $sell_result_arr = array();

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active_fre($anexo);

        /* FIND ANEXO */
        foreach ($result as $list) {

            /* BUY */
            $new_query = array(
                1695 => (string) $cuit,
                'filename' => $list['filename'],
                5272 => 'B'
                , 'FECHA_DE_TRANSACCION' => array(
                    '$lte' => $date
                )
            );

            $buy_result = $this->mongowrapper->sgr->$container->find($new_query);
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

            $sell_result = $this->mongowrapper->sgr->$container->find($new_query);
            foreach ($sell_result as $sell) {
                $sell_result_arr[] = $sell[5597];
            }
        }


        $buy_sum = array_sum($buy_result_arr);
        $sell_sum = array_sum($sell_result_arr);
        $balance = $buy_sum - $sell_sum;
        return $balance;
    }

    function get_partner_print($cuit, $sgr_id = null) {
        $anexo = $this->anexo;
        $period = 'container.sgr_periodos';
        $container = 'container.sgr_anexo_' . $anexo;
        $period_value = $this->session->userdata['period'];



        /* GET ACTIVE ANEXOS */
        //$result = $this->sgr_model->get_just_active($anexo);
        $result = $this->sgr_model->get_active_each_sgrid($anexo, $sgr_id);

        $return_result = array();
        foreach ($result as $list) {
            $new_query = array(
                'filename' => $list['filename'],
                1695 => $cuit
            );

            $new_result = $this->mongowrapper->sgr->$container->findOne($new_query);
            if ($new_result)
                $return_result[] = $new_result;
        }

        return $return_result;
    }

    /* get_partner_name */

    function get_partner_name($cuit) {
        $anexo = $this->anexo;
        $field = array('1693');
        $container = 'container.sgr_anexo_' . $anexo;
        $new_query = array(
            1695 => $cuit
        );

        $new_result = $this->mongowrapper->sgr->$container->findOne($new_query, $field);

        if ($new_result)
            return $return_result = $new_result[1693];
    }

    /* PROTECTOR */

    function partner_type_linked($cuit) {
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;
        $new_query = array(
            1695 => $cuit
        );

        $new_result = $this->mongowrapper->sgr->$container->findOne($new_query);
        if (isset($new_result))
            $return_result[] = $new_result;

        return $return_result;
    }

    /* SHARER */

    function partner_type_linked_sharer($cuit) {
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;
        $new_query = array(
            1695 => $cuit, 'sgr_id' => $this->sgr_id,
        );

        $new_result = $this->mongowrapper->sgr->$container->findOne($new_query);
        if (isset($new_result))
            $return_result[] = $new_result;

        return $return_result;
    }

    /* PARTNERS INFO ONLY CURRENT ANEXO #23556 */

    function new_count_partners($partners_arr, $get_period = null) {
        $get_error = false;
        $anexo = $this->anexo;
        $container_period = 'container.sgr_periodos';
        $container_anexo = 'container.sgr_anexo_' . $anexo;

        $query = array(
            'anexo' => $anexo,
            'sgr_id' => $this->sgr_id,
            'status' => array('$ne' => 'rectificado'),
            'period' => $get_period
        );

        $get_period_data = $this->mongowrapper->sgr->$container_period->findOne($query);
        

        $anexo_query = array(
            'filename' => $get_period_data['filename']
        );
        
        /* 5779 only for Incorporations / Faes & Fusion */
        $diff_incorp_fae = array("2", "3", "4", "16");
        $anexo_query['5779'] = array('$nin' => $diff_incorp_fae);
        $anexo_query['1695'] = array('$ne' => '');
        
        $count_all = $this->mongowrapper->sgr->$container_anexo->find($anexo_query);   
        $count_all_total = $count_all->count();
    
       

        /* CUITS 6.1 ARRAY */
        $arr_count = array();
        foreach ($partners_arr as $each_partner) {
            $anexo_query = array(
                'filename' => $get_period_data['filename'],
                1695 => $each_partner, 5779 => array('$ne' => "16")
            );
            $parter_data = $this->mongowrapper->sgr->$container_anexo->findOne($anexo_query);
            if(isset($parter_data['1695']))
            $arr_match_partners[] = $parter_data['1695'];
        }

        $arr_match_partners_total = count($arr_match_partners);
        $result = $count_all_total - $arr_match_partners_total;  
        
    
        if ($result > 0)
            $return = "VG.4";

        else if ($result < 0)
            $return = "VG.3";
        

        return $return;
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

            $buy_result = $this->mongowrapper->sgr->$container->find($new_query);
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

            $sell_result = $this->mongowrapper->sgr->$container->find($new_query);
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

            $buy_result = $this->mongowrapper->sgr->$container->find($new_query);
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

            $sell_result = $this->mongowrapper->sgr->$container->find($new_query);
            foreach ($sell_result as $sell) {
                $sell_result_arr[] = $sell[$field];
            }
        }

        $buy_sum = array_sum($buy_result_arr);
        $sell_sum = array_sum($sell_result_arr);
        $balance = $buy_sum - $sell_sum;
        return $balance;
    }

    function shares_print($cuit, $partner_type = null, $field = 5597, $period_value, $transaction_date) {
        $anexo = $this->anexo;
        $container = 'container.sgr_anexo_' . $anexo;
        $endDate = new MongoDate(strtotime($transaction_date));

        $buy_result_arr = array();
        $sell_result_arr = array();

        /* GET ACTIVE ANEXOS */
        $result = $this->sgr_model->get_active_print($anexo, $period_value);



        /* FIND ANEXO */
        foreach ($result as $list) {
            /* BUY */
            $new_query = array(
                1695 => (string) $cuit,
                'filename' => $list['filename'],
                'FECHA_DE_TRANSACCION' => array(
                    '$lte' => $endDate
                ),
            );
            if ($partner_type)
                $new_query[5272] = $partner_type;


            $buy_result = $this->mongowrapper->sgr->$container->find($new_query);
            foreach ($buy_result as $buy) {
                $buy_result_arr[] = $buy[$field];
            }

            /* SELL */
            $new_query = array(
                5248 => (string) $cuit,
                'filename' => $list['filename'],
                'FECHA_DE_TRANSACCION' => array(
                    '$lte' => $endDate
                ),
            );
            if ($partner_type)
                $new_query[5272] = $partner_type;

            $sell_result = $this->mongowrapper->sgr->$container->find($new_query);
            foreach ($sell_result as $sell) {

                $sell_result_arr[] = $sell[$field];
            }
        }

        $buy_sum = array_sum($buy_result_arr);
        $sell_sum = array_sum($sell_result_arr);
        $balance = abs($buy_sum - $sell_sum);

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
                1695 => (string) $cuit,
                'filename' => $list['filename'],
                5272 => 'B'
                , 'FECHA_DE_TRANSACCION' => array(
                    '$lte' => $date
                )
            );

            $buy_result = $this->mongowrapper->sgr->$container->find($new_query);
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

            $sell_result = $this->mongowrapper->sgr->$container->find($new_query);
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
    function shares_others_sgrs_beta($cuit_field=1695, $cuit, $partner_type, $inc_date, $capital_suscripto = 5597) {

        $anexo = $this->anexo;
        $period = 'container.sgr_periodos';
        $container = 'container.sgr_anexo_' . $anexo;
        $partner_type = ($partner_type == "A") ? "B" : "A";
       

        $buy_result_arr = array();
        $sell_result_arr = array();
        
 

         $suma_query=array(
                'aggregate'=>'container.sgr_periodos',
                'pipeline'=>
                  array(
                      array (
                        '$lookup' => array (
                            'from' => 'container.sgr_anexo_06',
                            'localField' => 'filename',
                            'foreignField' => 'filename',
                            'as' => 'anexo')                        
                      ),
                      array ('$unwind' => '$anexo'),  
                      array ('$match' => array (
                        'anexo.' . $cuit_field => $cuit, 
                        'status'=>'activo' ,
                        5272 => $partner_type,
                        'FECHA_DE_TRANSACCION' => array('$lte' => $inc_date))),  
                      array(
                        '$group' => array(
                        '_id' => null,                    
                        'suma' => array('$sum' => '$anexo.' . $capital_suscripto)
                        ),
                    ), 

                ));
    


         $rs=$this->sgr_db->command($suma_query);   
         #foreach($rs['result'] as $x)
            #var_dump($x['filename'], $x['anexo']['5597'])  ;    
       
         #var_dump($rs);
         return $rs['result'][0]['suma'];
     }

    function shares_others_sgrs($cuit, $partner_type, $inc_date, $field = 5597) {

        $anexo = $this->anexo;
        $period = 'container.sgr_periodos';
        $container = 'container.sgr_anexo_' . $anexo;
        $partner_type = ($partner_type == "A") ? "B" : "A";
       

        $buy_result_arr = array();
        $sell_result_arr = array();
        

        /* GET ACTIVE ANEXOS */
        $exclude_this = TRUE;
        $result = $this->sgr_model->get_active_other_sgrs($anexo, $exclude_this);

        $return_result = array();
        /* FIND ANEXO */
        foreach ($result as $list) {
            /* BUY */
            $new_query = array(
                1695 => (string) $cuit,
                'filename' => $list['filename'],
                5272 => $partner_type,
                'FECHA_DE_TRANSACCION' => array('$lte' => $inc_date)
            );
            
            $buy_result = $this->mongowrapper->sgr->$container->findOne($new_query);
            
            if ($buy_result)
                $buy_result_arr[] = $buy_result[$field];
            

            /* SELL */
            $new_query = array(
                5248 => $cuit,
                'filename' => $list['filename'],
                5272 => $partner_type, 
                'FECHA_DE_TRANSACCION' => array('$lte' => $inc_date)
            );

            $sell_result = $this->mongowrapper->sgr->$container->findOne($new_query);
            
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

    function partner_type($cuit, $sgr_id = null) {


        $anexo = $this->anexo;



        $info_06 = $this->get_partner_print($cuit, $sgr_id);

        foreach ($info_06 as $data) {
            return $data[5272][0];
        }
    }

    function balance_count_before($period, $partner_type) {
        $anexo = $this->anexo;
        /* GET ACTIVE ANEXOS */
        $container_period = 'container.sgr_periodos';
        $container = 'container.sgr_anexo_' . $anexo;
        $result = $this->sgr_model->get_active_print($anexo, period_before($period)); //exclude actual

        $input_arr = array();
        $datached_arr = array();

        foreach ($result as $each) {
            /* INPUT */
            $input_query = array(
                'filename' => $each['filename'], 5272 => $partner_type, 5779 => '1'
            );

            $input_partners = $this->mongowrapper->sgr->$container->find($input_query);
            foreach ($input_partners as $inputs)
                $input_arr[] = $inputs[1695];

            /* DATACHED */
            $datached_query = array(
                'filename' => $each['filename'], 5272 => $partner_type, 5292 => '2'
            );

            $datached_partners = $this->mongowrapper->sgr->$container->find($datached_query);
            foreach ($datached_partners as $datacheds)
                $datached_arr[] = $datacheds[5248];
        }


        $total_inputs = count(array_unique($input_arr));
        $total_datacheds = count(array_unique($datached_arr));



        $diff = $total_inputs - $total_datacheds;

        return $diff;
    }

    function balance_amount_count_before($period, $partner_type) {
        $anexo = $this->anexo;
        /* GET ACTIVE ANEXOS */
        $container_period = 'container.sgr_periodos';
        $container = 'container.sgr_anexo_' . $anexo;
        $result = $this->sgr_model->get_active_print($anexo, period_before($period)); //exclude actual

        $input_arr = array();
        $datached_arr = array();
        foreach ($result as $each) {
            /* INPUT */
            $input_query = array(
                'filename' => $each['filename'], 5272 => $partner_type, 5779 => array('$ne' => '3')
            );

            $input_partners = $this->mongowrapper->sgr->$container->find($input_query);
            foreach ($input_partners as $inputs)
                $input_arr[] = $inputs[5598];

            /* DATACHED */

            $datached_query = array(
                'filename' => $each['filename'], 5272 => $partner_type, 5292 => array('$ne' => null)
            );

            $datached_partners = $this->mongowrapper->sgr->$container->find($datached_query);
            foreach ($datached_partners as $datacheds)
                $datached_arr[] = $datacheds[5598];
        }


        $total_inputs = array_sum($input_arr);
        $total_datacheds = array_sum($datached_arr);

        $diff = $total_inputs - $total_datacheds;

        return $diff;
    }

    /* INCORPORACION */

    function incorporated_count($period, $partner_type) {

        $anexo = $this->anexo;

        /* GET ACTIVE ANEXOS */
        $container_period = 'container.sgr_periodos';
        $container = 'container.sgr_anexo_' . $anexo;


        $result = $this->sgr_model->get_current_period_info($anexo, $period);
        $new_query = array(
            'filename' => $result['filename'], 5272 => $partner_type, 5779 => '1'
        );
        $partners = $this->mongowrapper->sgr->$container->find($new_query);
        return $partners->count();
    }

    function detached_count($period, $partner_type) {
        $anexo = $this->anexo;

        /* GET ACTIVE ANEXOS */
        $container_period = 'container.sgr_periodos';
        $container = 'container.sgr_anexo_' . $anexo;


        $result = $this->sgr_model->get_current_period_info($anexo, $period);
        $new_query = array(
            'filename' => $each['filename'], 5272 => $partner_type, 5292 => '2'
        );
        $partners = $this->mongowrapper->sgr->$container->find($new_query);
        return $partners->count();
    }

    /* ACCIONES COMPRA */

    function buys_shares($period, $partner_type) {
        $anexo = $this->anexo;

        /* GET ACTIVE ANEXOS */
        $container_period = 'container.sgr_periodos';
        $container = 'container.sgr_anexo_' . $anexo;

        $result = $this->sgr_model->get_current_period_info($anexo, $period);
        $new_query = array(
            'filename' => $result['filename'],
            5272 => $partner_type,
            5779 => array('$ne' => '3')
        );
        $count = array();
        $partners = $this->mongowrapper->sgr->$container->find($new_query);
        foreach ($partners as $each)
            $count[] = $each['5598'];
        return array_sum($count);
    }

    function buys_shares_before($period, $partner_type) {
        $anexo = $this->anexo;

        /* GET ACTIVE ANEXOS */
        $container_period = 'container.sgr_periodos';
        $container = 'container.sgr_anexo_' . $anexo;
        $count = array();

        $get_result = $this->sgr_model->get_active_print('06', period_before($period)); //exclude actual
        foreach ($get_result as $result) {
            $new_query = array(
                'filename' => $result['filename'],
                5272 => $partner_type,
                5779 => array('$ne' => '3')
            );

            $partners = $this->mongowrapper->sgr->$container->find($new_query);
            foreach ($partners as $each)
                $count[] = $each['5598'];
        }
        return array_sum($count);
    }

    /* ACCIONES VENTA */

    function sells_shares($period, $partner_type) {

        $anexo = $this->anexo;

        /* GET ACTIVE ANEXOS */
        $container_period = 'container.sgr_periodos';
        $container = 'container.sgr_anexo_' . $anexo;

        $result = $this->sgr_model->get_current_period_info($anexo, $period);
        $new_query = array(
            'filename' => $result['filename'],
            5272 => $partner_type,
            5779 => '3',
            5252 => '1'
        );
        $count = array();
        $partners = $this->mongowrapper->sgr->$container->find($new_query);
        foreach ($partners as $each)
            $count[] = $each['5598'];
        return array_sum($count);
    }

    function sells_shares_before($period, $partner_type) {
        $anexo = $this->anexo;

        /* GET ACTIVE ANEXOS */
        $container_period = 'container.sgr_periodos';
        $container = 'container.sgr_anexo_' . $anexo;
        $count = array();

        $get_result = $this->sgr_model->get_active_print('06', period_before($period)); //exclude actual
        foreach ($get_result as $result) {

            $new_query = array(
                'filename' => $result['filename'],
                5272 => $partner_type,
                5779 => '3',
                5252 => '1'
            );

            $partners = $this->mongowrapper->sgr->$container->find($new_query);
            foreach ($partners as $each)
                $count[] = $each['5598'];
        }
        return array_sum($count);
    }
    
    
    /*FIX ISODATE*/
    function update_idodate() {

        $rtn = array();
        $container = 'container.sgr_anexo_06';
        $result = $this->mongowrapper->sgr->$container->find();

        
        foreach ($result as $list) {
            
            if(is_string($list['FECHA_DE_TRANSACCION'])){
            
             echo $list['filename'] . "<br>";
             
            
            $options = array('upsert' => true, 'w' => 1);
            $container = 'container.sgr_anexo_06';
            $query = array('_id' => $list['_id']);
            $parameter = array('FECHA_DE_TRANSACCION' =>new MongoDate(strtotime($list['FECHA_DE_TRANSACCION'])));
            $rs = $this->mongowrapper->sgr->$container->update($query, array('$set' => $parameter), $options);
            
            if (isset($rs)) {
            $out = array('status' => 'ok');
            } else {
                $out = array('status' => 'error');
            }
          var_dump($out, $list['FECHA_DE_TRANSACCION']);
           
        }
    }



    }


     /**
     * Nuevo Reporte Anexo 06
     *
     * @name generate_report
     *
     * @see SGR()
     *
     * @author Diego Otero <daotero@industria.gob.ar>
     *
     * @date Apr 19, 2016
     *
     * @param type $query
     */

     

     function get_link_report($anexo) {

        $headerArr = header_arr($anexo);
        $title_report = $this->sgr_model->get_anexo($anexo);
        
        $data[] = array($headerArr);
        $anexoValues = $this->sgr_model->last_report_general();

        if (!$anexoValues) {
            return false;
        } else {
            foreach ($anexoValues as $values) {
                $header = '<h2>Reporte '.$anexo.' - '.strtoupper($title_report['title']).' </h2><h3>PERIODO/S: ' . $values['uquery']['input_period_from'] . ' a ' . $values['uquery']['input_period_to'] . '</h3>';

                unset($values['_id']);
                unset($values['id']);
                $data[] = array_values($values);
            }
            $this->load->library('table');
            return $header . $this->table->generate($data);
        }
    }

    function generate_report($parameter=array()) {
       

       $start_date = first_month_date($parameter['input_period_from']);
       $end_date = last_month_date($parameter['input_period_to']);


       $socio = isset($parameter['cuit_socio']) ? $parameter['cuit_socio'] : array('$exists'  => true);
       $sgr_id = ($parameter['sgr_id']=='666') ? array('$in'=>$parameter['sgr_id_array']) : (float)$parameter['sgr_id'] ;


        $query=array(
                'aggregate'=>'container.sgr_periodos',
                'pipeline'=>
                  array(
                       array (
                        '$match' => array (
                            'sgr_id' =>$sgr_id, 
                            'status'=>'activo' ,
                            'period_date' => array(
                            '$gte' => $start_date, '$lte' => $end_date
                        ))                        
                      ), 
                      array (
                        '$lookup' => array (
                            'from' => 'container.sgr_anexo_' . $this->anexo,
                            'localField' => 'filename',
                            'foreignField' => 'filename',
                            'as' => 'anexo')                        
                      ),                      
                      array ('$match' => array (
                        'anexo.1695' => $socio                    
                    ))                   

                ));          
        $get=$this->sgr_db->command($query);
        
        $this->ui_table_xls($get['result'], $this->anexo, $parameter);
         
   }

   function ui_table_xls($result, $anexo = null, $parameter) {        


        /* CSS 4 REPORT */
        css_reports_fn();

        $i = 1;

        $list = null;
        $this->sgr_model->del_tmp_general();

        foreach ($result as $get_each) {
            
            $list = $get_each['anexo'];

           
            /* Vars */
            $cuit_sgr = str_replace("-", "", $this->sgr_cuit);
            $cuit = str_replace("-", "", $list['1695']);
            
            $brand_name = $this->padfyj_model->search_name($cuit);
            $brand_name = ($brand_name) ? : $list['1693'];
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
            $sector_opt = $this->app->get_ops(494);
            
           

            $calc_average = "";
            $promedio = "";
            $sector_value = "";
            $company_type = "";

            $calc_average = ($list[20] != "") ? 1 : 0;
            $calc_average += ($list[23] != "") ? 1 : 0;
            $calc_average += ($list[26] != "") ? 1 : 0;
            if ($calc_average != 0) {

                $montosArr = array($list[20], $list[23], $list[26]);
                $sumaMontos = array_sum($montosArr);

                $promedio = ($sumaMontos / $calc_average);
            }

            $sector_value = $this->sgr_model->clae2013($list['5208'],$list['5272'][0], $resolution);
            $isPyme = $this->sgr_model->get_company_size($sector_value, $promedio);
            $company_type = ($isPyme) ? "PyME" : "";
            $transaction_date = mongodate_to_print($list['FECHA_DE_TRANSACCION']);



            /* CARACTER CEDENTE */

            if ($list['5248']) {
                $integrated = $this->shares_print($list['5248'], $list['5272'][0], 5598, $list['period'], $transaction_date);
                $grantor_type = ($integrated == 0) ? "DESVINCULACION" : "DISMINUCION DE TENENCIA ACCIONARIA";
                $grantor_type = $grantor_type;
            }

            $get_period_filename = $this->sgr_model->get_period_filename($list['filename']);

            $afip_var = ($afip_condition[$list['5596'][0]])?$afip_condition[$list['5596'][0]]:$list['5596'][0];
            $afip_var = strtoupper($afip_var);
            
            /* SGR DATA */ 
            $sgr_info = $this->sgr_model->get_sgr_by_id_new($get_period_filename['sgr_id']);
            
            $new_list = array();
            $new_list['col0'] = $sgr_info[1693];
            $new_list['col1'] = $sgr_info[1695];            
            $new_list['col3'] = $list['id'];
            $new_list['col4'] = $get_period_filename['period'];
            $new_list['col5'] = $operation_type[$list['5779'][0]];
            $new_list['col6'] = $list['5272'][0];
            $new_list['col7'] = $cuit;
            $new_list['col8'] = $brand_name;
            $new_list['col9'] = $provincia[$list['4651'][0]];
            $new_list['col10'] = $list['1699'][0]; //$partido[$list['1699'][0]];
            $new_list['col11'] = $list['1700'];
            $new_list['col12'] = $list['1698'];
            $new_list['col13'] = $list['4653'];
            $new_list['col14'] = $list['4654'];
            $new_list['col15'] = $list['4655'];
            $new_list['col16'] = $list['4656'];
            $new_list['col17'] = $list['CODIGO_AREA'] . $list['1701'];
            $new_list['col18'] = "";
            $new_list['col19'] = $list['1703'];
            $new_list['col20'] = $list['1704'];
            $new_list['col21'] = $list['5208'];
            $new_list['col22'] = $sector_opt[$sector_value];
            $new_list['col23'] = $list['19'];
            $new_list['col24'] = dot_by_coma($list['20']);
            $new_list['col25'] = $list['21'];
            $new_list['col26'] = $list['22'];
            $new_list['col27'] = dot_by_coma($list['23']);
            $new_list['col28'] = $list['24'];
            $new_list['col29'] = $list['25'];
            $new_list['col30'] = dot_by_coma($list['26']);
            $new_list['col31'] = $list['27'];
            $new_list['col32'] = dot_by_coma($promedio);
            $new_list['col33'] = $company_type;
            $new_list['col34'] = $afip_var;
            $new_list['col35'] = $list['CANTIDAD_DE_EMPLEADOS'];
            $new_list['col36'] = $acta_type[$list['5253'][0]];
            $new_list['col37'] = $list['5255'];
            $new_list['col38'] = $list['5254'];
            $new_list['col39'] = $transaction_date;
            $new_list['col40'] = $transaction_type[$list['5252'][0]];
            $new_list['col41'] = $list['5597'];
            $new_list['col42'] = $list['5598'];
            $new_list['col43'] = $list['5248'];
            $new_list['col44'] = $grantor_brand_name;
            $new_list['col45'] = $transfer_characteristic[$list['5292'][0]];
            $new_list['col46'] = $list['filename'];  
            $new_list['uquery'] = $parameter;

            /* COUNT */
            $increment = $i++;
            report_account_records_fn($increment);

            /* ARRAY FOR RENDER */
            $rtn[] = $new_list;

            /* SAVE RESULT IN TMP DB COLLECTION */
            $this->sgr_model->save_tmp_general($new_list, $list['id']);
        }

        /* PRINT XLS LINK */
        link_report_and_back_fn();
        exit;

        /* REFRESH AND SHOW LINK */
        header("Location: $this->module_url_report");
        exit();
    }
}
