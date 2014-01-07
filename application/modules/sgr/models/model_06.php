<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_06 extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->idu = (int) $this->session->userdata('iduser');
        if (!$this->idu)
            header("$this->module_url/user/logout");
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
            35 => 5250, //ACCIONES_SUSCRIPTAS
            36 => 5598, //CAPITAL_INTEGRADO
            37 => 5251, //ACCIONES_INTEGRADAS
            38 => 5248, //CEDENTE_CUIT
            39 => 5292 //CEDENTE_CARACTERISTICA
        );


        $insertarr = array();
        foreach ($defdna as $key => $value) {
            $insertarr[$value] = $parameter[$key];            
            //--- Tipo de Operacion           
            if ($insertarr[5779] == "INCORPORACION")
                $insertarr[5779] = "1";
            if ($insertarr[5779] == "INCREMENTO DE TENENCIA ACCIONARIA")
                $insertarr[5779] = "2";
            if ($insertarr[5779] == "DISMINUCION DE CAPITAL SOCIAL")
                $insertarr[5779] = "3";


           
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
            $insertarr[5597] = str_replace(",", ".", $insertarr[5597]);
            $insertarr[5598] = str_replace(",", ".", $insertarr[5598]);

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
        $container = 'container.sgr_anexo_06';
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
        $id = $this->app->genid($container);
        $parameter['period'] = $this->session->userdata['period'];
        $parameter['status'] = 'activo';
        $result = $this->app->put_array($id, $container, $parameter);
        if ($result) {
            $out = array('status' => 'ok');
        } else {
            $out = array('status' => 'error');
        }
        return $out;
    }

    function update_period($id) {
        $options = array('upsert' => true, 'safe' => true);
        $container = 'container.sgr_periodos';
        $query = array('id' => (integer) $id);
        $parameter = array('status' => "rectificado");
        $rs = $this->mongo->db->$container->update($query, array('$set' => $parameter), $options);
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
        $rtn = array();
        $container = 'container.sgr_anexo_' . $anexo;
        $query = array("filename" => $parameter);
        $result = $this->mongo->db->$container->find($query);

        foreach ($result as $list) {
            
            /*Vars*/
            $cuit = str_replace("-","", $list['1695']);
            $brand_name = strtoupper($list['1693']);
            
            $this->translate_options(589);
            
            
            $new_list = array();
            $new_list['TIPO_OPERACION'] = $list['5779'][0];
            $new_list['SOCIO'] = $list['5272'][0] . "</br>" . $cuit . "</br>" . $brand_name;
            $new_list['LOCALIDAD'] = $list['1700'] . "</br>" . $list['1699'][0] . "</br>" . $list['4651'][0] . "</br>[" . $list['1698'] . "]";
            $new_list['DIRECCION'] = $list['4653'] . "</br>" . "Nro." . $list['4654'] . "</br>Piso/Dto/Of." . $list['4655'] . " " . $list['4656'];
            $new_list['TELEFONO'] = "(" . $list['CODIGO_AREA'] . ") " . $list['1701'];
            $new_list['EMAIL'] = $list['1703'] . "</br>" . $list['1704'];
            $new_list['CODIGO_ACTIVIDAD'] = $list['5208'] . "<br>[SECTOR]";
            $new_list['"ANIO",'] = $list['19'] . " " . $list['20'] . " " . $list['21'] . "<br/>" . $list['22'] . " " . $list['23'] . " " . $list['24'] . "<br/>" . $list['25'] . " " . $list['26'] . " " . $list['27'];
            $new_list['CONDICION_INSCRIPCION_AFIP'] = "[PROMEDIO] " . $list['5596'][0];
            $new_list['EMPLEADOS'] = $list['CANTIDAD_DE_EMPLEADOS'];
            $new_list['ACTA'] = "Tipo: " . $list['5253'][0] . "<br/>Acta: " . $this->translate_date($list['5255']) . "<br/>Nro." . $list['5254'] . "<br/>Efectiva:" . $this->translate_date($list['FECHA_DE_TRANSACCION']);
            $new_list['MODALIDAD'] = "Modalidad " . $list['5252'][0] . "<br/>Capital Suscripto:" . $list['5597'] . "<br/>Acciones Suscriptas: " . $list['5250'] . "<br/>Capital Integrado: " . $list['5598'] . "<br/>Acciones Integradas:" . $list['5251'];
            $new_list['CEDENTE_CUIT'] = $list['5248'] . "<br/>" . $list['5292'][0];

            $rtn[] = $new_list;
        }
        return $rtn;
    }

    function translate_date($parameter) {
    if($parameter==""){exit();}
        $parameter = mktime(0, 0, 0, 1, -1 + $parameter, 1900);
        return strftime("%Y/%m/%d", $parameter);
    }
    
    function translate_options($parameter){
        $container = 'options';
        $fields = array("data");
        $query = array("idop" => $parameter);
        $result = $this->mongo->db->$container->find($query, $fields);
        
        var_dump($result);
    }

    function debug($parameter) {
        return "<pre>" . var_dump($parameter) . "</pre><hr>";
    }

}
