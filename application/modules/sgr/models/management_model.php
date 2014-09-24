<?php

/**
 * @class genia
 *
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Management_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('sgr/tools');


        $original_user = (float) $this->session->userdata['iduser'];

        if (isset($this->session->userdata['sgr_impersonate']))
            $taken_user = (float) $this->session->userdata['sgr_impersonate'];

        $this->idu = (isset($taken_user))? $taken_user : $original_user;

        /* SWITCH TO SGR DB */
        $this->load->library('cimongo/cimongo', '', 'sgr_db');
        $this->sgr_db->switch_db('sgr');

        if (!$this->idu)
            header("$this->module_url/user/logout");


        /* DATOS SGR */
        $sgrArr = $this->get_sgr();
        foreach ($sgrArr as $sgr) {
            $this->sgr_id = $sgr['id'];
            $this->sgr_nombre = $sgr['1693'];
        }
    }

    /* RETURN ANEXOS */

    function get_anexos() {
        $container = 'container.sgr_anexos';
        $result = $this->mongo->sgr->$container->find();
        $result->sort(array('id' => 1));
        return $result;
    }

    function get_anexo($anexo) {
        $container = 'container.sgr_anexos';
        $query['number'] = $anexo;
        $result = $this->mongo->sgr->$container->findOne($query);
        return $result;
    }

    function get_processed_info() {

        $container = 'container.sgr_periodos';
        $fields = array('anexo', 'period', 'status', 'filename', 'id');
        $query = array(
            "status" => array('$ne' => 'rectificado'), "origen" => "2013", "status" => "activo"
        );

        $sort = array('sgr_id' => -1, 'anexo' => -1);
        $result = $this->mongo->sgr->$container->find($query, $fields)->sort($sort);
        return $result;
    }

    function get_current_period_info($anexo, $period) {

        $container = 'container.sgr_periodos';
        $fields = array('anexo', 'period', 'status', 'filename', 'id');
        $query = array(
            'anexo' => $anexo,
            'sgr_id' => (float) $this->sgr_id,
            'period' => $period,
            "status" => array('$ne' => 'rectificado'),
        );



        $result = $this->mongo->sgr->$container->findOne($query, $fields);
        return $result;
    }

    function get_if_is_rectified($filename) {

        $container = 'container.sgr_periodos';
        $fields = array('id');
        $query = array(
            'filename' => $filename,
            "status" => array('$ne' => 'rectificado'),
        );
        $result = $this->mongo->sgr->$container->findOne($query, $fields);
        return $result;
    }

    function get_period_count($anexo, $period) {
        $container = 'container.sgr_periodos';
        $fields = array('anexo', 'period', 'status', 'filename', 'id');
        $query = array('status' => 'rectificado', 'anexo' => $anexo, 'sgr_id' => (float) $this->sgr_id, 'period' => $period);
        $result = $this->mongo->sgr->$container->find($query, $fields);
        return $result->count();
    }

    function get_period_filename($filename) {
        $container = 'container.sgr_periodos';
        $fields = array('anexo', 'period', 'status', 'filename', 'id', 'idu');
        $query = array("filename" => $filename);
        $result = $this->mongo->sgr->$container->findOne($query, $fields);
        return $result;
    }

    //dd.jj
    //processes
    function get_ready($sgr_id, $year = null) {
        $rtn = array();
        $rtn_anexo = array();
        $regex = new MongoRegex('/' . $year . '/');
        $container = 'container.sgr_periodos';
        $fields = array('anexo', 'period', 'status', 'filename', 'origen');
        $sort = array('period_date' => -1);
        $query = array("status" => 'activo', "sgr_id" => $sgr_id, 'period' => $regex);
        $result = $this->mongo->sgr->$container->find($query, $fields)->sort($sort);

        foreach ($result as $list) {
            $rtn_anexo[] = $list['anexo'];
            $rtn[] = $list['period'];
        }

        if (count($rtn_anexo) > 1)
            return array_unique($rtn);
    }

    function get_ready_anexo($sgr_id, $period) {
        $rtn = array();
        $rtn_anexo = array();

        $container = 'container.sgr_periodos';
        $fields = array('anexo', 'period', 'status', 'filename', 'origen');
        $sort = array('period_date' => -1);
        $query = array("status" => 'activo', "sgr_id" => $sgr_id, 'period' => $period);
        $result = $this->mongo->sgr->$container->find($query, $fields)->sort($sort);

        foreach ($result as $list) {
            $rtn_anexo[] = $list['anexo'];
            $rtn[] = true;
        }

        /* 1. Una vez que el usuario finalizó la carga de los Anexos 6, 6.1, 12, 12.1, 12.2, 12.3, 12.4, 12.5, 13, 14, 14.1, 15, 16, 20.1 y 20.2, debe acceder a este Anexo y pedir la GENERCIÓN del Anexo 17 para el período que seleccione. */
        if (count($rtn_anexo) > 15)
            return array_unique($rtn);
    }

    function get_ready_($sgr_id, $year = null) {

        $container = 'container.sgr_periodos';

        $anexos_arr = array("06", "061", "12", "121", "122", "123", "124", "125", "13", "14", "141", "15", "16", "201", "202");
        $rtn_period = array();
        $rtn = array();

        $regex = new MongoRegex('/' . $year . '/');
        $fields = array('period');
        $sort = array('period_date' => -1);
        $query = array("status" => 'activo', "sgr_id" => $sgr_id, 'period' => $regex);
        $result = $this->mongo->sgr->$container->find($query, $fields)->sort($sort);

        foreach ($result as $list)
            $rtn_period[] = $list['period'];


        $arr_periods = array_unique($rtn_period);

        foreach ($arr_periods as $period) {
            $success = array();

            foreach ($anexos_arr as $anexo) {
                $query = array("period" => $period, 'anexo' => $anexo);
                $new_result = $this->mongo->sgr->$container->findOne($query);
                if ($new_result)
                    $success[] = $period;
            }

            if (count($success) == 4) //count($anexos_arr)
                $rtn[] = $success;
        }

        return $rtn;
    }

    //processes
    function get_processed($anexo, $sgr_id, $year = null) {
        $rtn = array();
        $regex = new MongoRegex('/' . $year . '/');
        $container = 'container.sgr_periodos';
        $fields = array('anexo', 'period', 'status', 'filename', 'origen');
        $sort = array('period_date' => -1);
        $query = array("status" => 'activo', "anexo" => $anexo, "sgr_id" => $sgr_id, 'period' => $regex);
        $result = $this->mongo->sgr->$container->find($query, $fields)->sort($sort);

        foreach ($result as $list) {
            $rtn[] = $list;
        }
        return $rtn;
    }

    //rectify
    //processes
    function get_rectified($anexo, $sgr_id, $year = null) {
        $rtn = array();
        $regex = new MongoRegex('/' . $year . '/');
        $container = 'container.sgr_periodos';
        $sort = array('period_date' => -1);
        $query = array("status" => 'rectificado', "anexo" => $anexo, "sgr_id" => $sgr_id, 'period' => $regex);
        $result = $this->mongo->sgr->$container->find($query)->sort($sort);

        foreach ($result as $list) {
            $rtn[] = $list;
        }
        return $rtn;
    }

    function get_pending($anexo, $sgr_id) {
        $rtn = array();
        $regex = new MongoRegex('/' . $year . '/');
        $container = 'container.sgr_periodos';
        $query = array("status" => 'pendiente', "anexo" => $anexo, "sgr_id" => $sgr_id);
        $result = $this->mongo->sgr->$container->find($query);

        foreach ($result as $list) {
            $rtn[] = $list;
        }
        return $rtn;
    }

    function get_sgr() {
        $rtn = array();
        $idu = (float) $this->idu;
        $data = array();
        // Listado de empresas
        $container = 'container.empresas';
        $fields = array('id', '1695', '4651', '1693', '1703');
        $query = array("owner" => $idu, 6026 => '30', "status" => 'activa', 5281 => 'C');
        $result = $this->mongo->db->$container->find($query, $fields);

        foreach ($result as $empresa) {
            unset($empresa['_id']);
            $rtn[] = $empresa;
        }
        return $rtn;
    }

    function get_sgrs_users() {
        $rtn = array();

        $container = 'users';
        $query = array("group" => 58);
        $result = $this->mongo->db->$container->find($query);
        foreach ($result as $users) {

            $rtn[] = $users;
        }
        return $rtn;
    }

    function get_sgrs() {
        $rtn = array();

        // Listado de empresas
        $sort = array(1693 => 1);
        $container = 'container.empresas';
        $query = array(6026 => '30', "status" => 'activa', 5281 => 'C');
        $result = $this->mongo->db->$container->find($query);
        $result->sort($sort);

        foreach ($result as $sgrs) {
            $rtn[] = $sgrs;
        }

        return $rtn;
    }

    /* RETURN EMPRESAS */

    function get_empresas($query) {
        $rtn = array();
        //$query['status'] = 'activa';
        $fields = array('id',
            'status'
            , '1693'  //     Nombre de la empresa
            , '1695'  //     CUIT
            , '7819' // 	Longitud
            , '7820' // 	Latitud
            , '4651' // 	Provincia
            , '4653' //     Calle Ruta
            , '4654' //     Nro /km
            , '4655' //     Piso
            , '4656' //     Dto Oficina
            , '1699' // 	Partido
            , '1694'    // Tipo de empresa
            , '1698'    //cod postal
            , '1701'    //telefonos
            , '1703'    //email
            , '1704'    //web
            , '1711'    //Cantidad de Empleados actual  
            /* contacto */
            , '7876'    // Apellido y Nombre del Contacto
            , '7877'    // E-mail del Contacto
            , '7878'    // Rubro de la Empresa                
            /* PLANTA */
            , '7879'    // Superficie Cubierta
            , '7880'    // Posesi�n (idopcion = 729)
            , '1715'    // Productos o servicios que Ofrece
            /* PRODUCCION */
            , '7881'    // Tiene componentes importados (idopcion = 15)
            , '7882'    // Pueden ser reemplazados? (idopcion = 15)
            , '7883'    // Tiene capacidad para exportar? (idopcion = 15)
            , '1716'    // Mercado destino (idopcion = 88)
            , '7884'    // Proveedores
            , 'C7663'    // La empresa ha realizado o realiza acciones vinculadas a la Responsabilidad Social (idopcion = 716)
            , '7665'    // Registro �nico de Organizaciones de Responsabilidad Social (idopcion = 715)
            , 'origenGenia'    // usuario que tocó la empresa
        );
        $container = 'container.empresas';
        $sort = array('origenGenia' => -1);
        $result = $this->mongo->db->$container->find($query, $fields);
        $result->limit(2500)->sort($sort);
        foreach ($result as $empresa) {
            unset($empresa['_id']);
            $rtn[] = $empresa;
        }
        return $rtn;
    }

    /* RETURN VISITAS */

    function get_empresas_raw($query) {
        $rtn = array();
        //$query['status'] = 'activa';
        $fields = array('id',
            'status'
            , '1693'  //     Nombre de la empresa
            , '1695'  //     CUIT
            , '7819' // 	Longitud
            , '7820' // 	Latitud
            , '4651' // 	Provincia
            , '4653' //     Calle Ruta
            , '4654' //     Nro /km
            , '4655' //     Piso
            , '4656' //     Dto Oficina
            , '1699' // 	Partido
            , '1694'    // Tipo de empresa
            , '1698'    //cod postal
            , '1701'    //telefonos
            , '1703'    //email
            , '1704'    //web
            , '1711'    //Cantidad de Empleados actual  
            /* contacto */
            , '7876'    // Apellido y Nombre del Contacto
            , '7877'    // E-mail del Contacto
            , '7878'    // Rubro de la Empresa                
            /* PLANTA */
            , '7879'    // Superficie Cubierta
            , '7880'    // Posesi�n (idopcion = 729)
            , '1715'    // Productos o servicios que Ofrece
            /* PRODUCCION */
            , '7881'    // Tiene componentes importados (idopcion = 15)
            , '7882'    // Pueden ser reemplazados? (idopcion = 15)
            , '7883'    // Tiene capacidad para exportar? (idopcion = 15)
            , '1716'    // Mercado destino (idopcion = 88)
            , '7884'    // Proveedores
            , 'C7663'    // La empresa ha realizado o realiza acciones vinculadas a la Responsabilidad Social (idopcion = 716)
            , '7665'    // Registro �nico de Organizaciones de Responsabilidad Social (idopcion = 715)
            , 'origenGenia'    // usuario que tocó la empresa
        );
        $container = 'container.empresas';
        $sort = array('origenGenia' => -1);
        $result = $this->mongo->db->$container->find($query, $fields);
        $result->sort($sort);
        foreach ($result as $empresa) {
            unset($empresa['_id']);
            $rtn[] = $empresa;
        }
        return $rtn;
    }

    function get_empresas_id($query) {
        $rtn = array();
        //$query['status'] = 'activa';


        $container = 'container.empresas';
        $sort = array('id' => 1);
        $result = $this->mongo->db->$container->find($query);
        $result->sort($sort);
        foreach ($result as $empresa) {
            $empresaRtn['id'] = $empresa['id'];
            $empresaRtn['checksum'] = md5(json_encode($empresa));
            $rtn[] = $empresaRtn;
        }
        return $rtn;
    }

    function array_delete($value, $array) {
        $array = array_diff($array, array($value));
        return $array;
    }

    function clae2013($code) {
        $sector = "";

        $code = (string) $code;

        //$code = (strlen($code) == 5) ? "0" . $code : $code;        
        //$regex = new MongoRegex('/' . $code . '/i');
        $container = 'container.sgr_clae2013';
        $query = array("code" => $code);
        $fields = array("sector", "code");
        $result = $this->mongo->sgr->$container->findOne($query, $fields);

        if ($result) {
            $sector = $result['sector'];
        } else {
            $fields = array("sector", "code");
            $query = array("code" => "0" . $code);
            $result = $this->mongo->sgr->$container->findOne($query, $fields);

            $sector = $result['sector'];
        }

        return $sector;
    }

    function clae2013_forbidden($code) {
        $container = 'container.sgr_clae2013_forbidden';
        $query = array("code" => $code);
        $fields = array("code");
        $result = $this->mongo->sgr->$container->findOne($query, $fields);
        if ($result) {
            return $result['code'];
        } else {
            $query = array("code" => "0" . $code);
            $result = $this->mongo->sgr->$container->findOne($query, $fields);
            return $result['code'];
        }
    }

    function get_company_size($sector, $average) {
        $sector = (string) $sector;
        $container = 'container.sgr_size_empresa';
        $query = array("sector" => $sector);
        $fields = array("average");
        $result = $this->mongo->sgr->$container->findOne($query);
        $resultSize = ($average <= $result["average"]) ? true : false;
        return $resultSize;
    }

    function get_cnv_code($code) {
        /* DATOS SGR */
        $sgrArr = $this->sgr_model->get_sgr();
        foreach ($sgrArr as $sgr) {
            $this->sgr_cuit = $sgr['1695'];
        }
        $container = 'container.sgr_code_CNV';
        $query = array("codigo" => $code, "cuit_sgr" => $this->sgr_cuit);
        $result = $this->mongo->sgr->$container->findOne($query);
        return $result;
    }

    /* CÓDIGOS EMPRESAS EXTRANJERAS */

    function get_cuit_ext_company($cuit) {
        $container = 'container.sgr_code_empresa_ext';
        $query = array("cuit" => $cuit);
        $result = $this->mongo->sgr->$container->findOne($query);
        return $result;
    }

    /* GARANTIAS */

    function get_warranty_type($code) {
        $container = 'container.sgr_tipo_garantias';
        $query = array("code" => utf8_decode($code));
        $result = $this->mongo->sgr->$container->findOne($query);
        return $result;
    }

    function get_warranty_data($order_number, $options = null) {
        $container = 'container.sgr_anexo_12';
        $period = 'container.sgr_periodos';
        $query = array('status' => 'activo', 'anexo' => '12', 'sgr_id' => $this->sgr_id);
        if ($options) {
            $optionArr = array("period" => $options);
        }
        $result = $this->mongo->sgr->$period->find($query);
        foreach ($result as $list) {
            $new_query = array(5214 => $order_number, 'filename' => $list['filename']);
            $new_result = $this->mongo->sgr->$container->findOne($new_query);
        }
        return $new_result;
    }

    function get_investment_options($code) {
        $container = 'container.sgr_opciones_inversion';
        $query = array("inciso_art_25" => utf8_decode($code));
        $result = $this->mongo->sgr->$container->findOne($query);
        return $result;
    }

    function get_depositories($code) {
        $container = 'container.sgr_entidades_depositarias';
        $query = array("codigo" => utf8_decode($code));
        $result = $this->mongo->sgr->$container->findOne($query);
        return $result;
    }

    /* COTIZACION */

    function get_dollar_quotation($period, $currency = "dolar americano") {
        $quotation_date = date("Y-m-d", mktime(0, 0, 0, 1, -1 + ($period - 1), 1900));

        $container = 'container.sgr_cotizacion_dolar';
        $quotation_date = new MongoDate(strtotime($quotation_date));
        $field = array("amount");
        $query = array('date' => array(
                '$lte' => $quotation_date
        ));
        $result = $this->mongo->sgr->$container->find($query)->sort(array('date' => -1))->limit(1);

        foreach ($result as $each) {
            return $each[amount];
        }
    }

    function get_dollar_quotation_period($currency = "dolar americano") {

        $endDate = last_month_date($this->session->userdata['period']);

        $container = 'container.sgr_cotizacion_dolar';
        $quotation_date = new MongoDate(strtotime($quotation_date));
        $field = array("amount");
        $query = array('date' => array(
                '$lte' => $endDate
        ));
        $result = $this->mongo->sgr->$container->find($query)->sort(array('date' => -1))->limit(1);

        foreach ($result as $each) {
            return $each[amount];
        }
    }

    /* GET ACTIVE ANEXOS */

    function get_just_active($anexo, $period = false) {
        $rtn = array();
        $container = 'container.sgr_periodos';

        $query = array(
            'anexo' => $anexo,
            'sgr_id' => (float) $this->sgr_id,
            'status' => 'activo',
        );

        if ($period) {
            $query["period"] = $period;
        }

        $result = $this->mongo->sgr->$container->find($query);

        foreach ($result as $each) {

            $rtn[] = $each;
        }
        return $rtn;
    }

    function get_active_exclude_this($anexo, $period) {
        $rtn = array();
        $container = 'container.sgr_periodos';

        $query = array(
            'anexo' => $anexo,
            'sgr_id' => (float) $this->sgr_id,
            'status' => 'activo',
            "period" => array('$ne' => $period),
        );



        $result = $this->mongo->sgr->$container->find($query);

        foreach ($result as $each) {

            $rtn[] = $each;
        }
        return $rtn;
    }

    function get_active_tmp($anexo, $exclude_this = false) {
        $rtn = array();
        $token = $this->idu;
        $period = 'container.periodos_' . $token . '_tmp';
        $container = 'container.sgr_anexo_' . $anexo . '_tmp';

        $endDate = last_month_date($this->session->userdata['period']);

        $query = array(
            'anexo' => $anexo,
            "filename" => array('$ne' => 'SIN MOVIMIENTOS'),
            'sgr_id' => (float) $this->sgr_id,
            'status' => 'activo',
            'period_date' => array(
                '$lte' => $endDate
            ),
        );

        if ($exclude_this) {
            $query['period'] = array('$ne' => $exclude_this);
        }


        $result = $this->mongo->sgr->$period->find($query);

        foreach ($result as $each) {

            $rtn[] = $each;
        }
        return $rtn;
    }

    function get_active($anexo, $exclude_this = false) {
        $rtn = array();
        $period = 'container.sgr_periodos';
        $container = 'container.sgr_anexo_' . $anexo;

        $endDate = last_month_date($this->session->userdata['period']);

        $query = array(
            'sgr_id' => (float) $this->sgr_id,
            'anexo' => $anexo,
            "filename" => array('$ne' => 'SIN MOVIMIENTOS'),
            'status' => 'activo',
            'period_date' => array(
                '$lte' => $endDate
            ),
        );


        if ($exclude_this) {
            $query['period'] = array('$ne' => $exclude_this);
        }


        $result = $this->mongo->sgr->$period->find($query);

        foreach ($result as $each) {
            $rtn[] = $each;
        }
        return $rtn;
    }

    /* GET ACTIVE for PRINT ANEXOS */

    function get_active_one($anexo, $get_period) {



        $rtn = array();
        $period = 'container.sgr_periodos';
        $container = 'container.sgr_anexo_' . $anexo;


        $query = array(
            'anexo' => $anexo,
            "filename" => array('$ne' => 'SIN MOVIMIENTOS'),
            'sgr_id' => (float) $this->sgr_id,
            'status' => 'activo',
            'period' => $get_period
        );

        $result = $this->mongo->sgr->$period->find($query);
        foreach ($result as $each) {


            $rtn[] = $each;
        }

        return $rtn;
    }

    function get_active_print($anexo, $period_date) {

        $rtn = array();
        $period = 'container.sgr_periodos';
        $container = 'container.sgr_anexo_' . $anexo;

        $endDate = last_month_date($period_date);

        $query = array(
            'anexo' => $anexo,
            "filename" => array('$ne' => 'SIN MOVIMIENTOS'),
            'sgr_id' => (float) $this->sgr_id,
            'status' => 'activo',
            'period_date' => array(
                '$lte' => $endDate
            ),
        );


        $result = $this->mongo->sgr->$period->find($query);
        foreach ($result as $each) {


            $rtn[] = $each;
        }

        return $rtn;
    }

    function get_period_data($anexo, $period_date, $exclude_sm = false) {

        $rtn = array();
        $period = 'container.sgr_periodos';
        $container = 'container.sgr_anexo_' . $anexo;
        $query = array(
            'anexo' => $anexo,
            'sgr_id' => (float) $this->sgr_id,
            'status' => 'activo',
            'period' => $period_date,
        );

        if (!$exclude_sm) {
            $query['filename'] = array('$ne' => 'SIN MOVIMIENTOS');
        }

        $result = $this->mongo->sgr->$period->find($query);
        foreach ($result as $each) {
            $rtn[] = $each;
        }
        return $rtn;
    }

    function get_active_last_rec($anexo, $exclude_this = false) {
        $rtn = array();
        $period = 'container.sgr_periodos';
        $container = 'container.sgr_anexo_' . $anexo;

        $endDate = last_month_date($this->session->userdata['period']);

        $query = array(
            'anexo' => $anexo,
            "filename" => array('$ne' => 'SIN MOVIMIENTOS'),
            'sgr_id' => (float) $this->sgr_id,
            'status' => 'activo',
            'period_date' => array(
                '$lte' => $endDate
            ),
        );

        if ($exclude_this) {
            $query['period'] = array('$ne' => $exclude_this);
        }


        $result = $this->mongo->sgr->$period->find($query)->sort(array('period_date' => -1))->limit(1);

        foreach ($result as $each) {
            $rtn[] = $each;
        }
        return $rtn;
    }

    function get_active_other_sgrs($anexo, $exclude_this = false) {
        $rtn = array();
        $period = 'container.sgr_periodos';
        $container = 'container.sgr_anexo_' . $anexo;

        $query = array(
            'anexo' => $anexo,
            "filename" => array('$ne' => 'SIN MOVIMIENTOS'),
            'sgr_id' => array('$ne' => $this->sgr_id),
            'status' => 'activo',
            'period' => array('$ne' => $this->session->userdata['period'])
        );

        $result = $this->mongo->sgr->$period->find($query);
        foreach ($result as $each) {
            $rtn[] = $each;
        }
        return $rtn;
    }

//        function fojo_update() {
//        $this->load->helper('file');
//        $myfile=read_file('./application/modules/sgr/assets/sgr_garantias.csv');
//        $lista=explode("\n",$myfile);
//
//         $container = 'container.sgr_anexo_12';
//         $options = array('safe' => true);
//         $data=array('5219'=>array("2"));
//         $i=0;
//         foreach($lista as $id){
//             $i++;
//             $myid=trim($id,'"');
//             echo $myid."/";
//             $query=array("id"=>$myid);
//             $rs = $this->mongo->sgr->$container->update($query, array('$set'=>$data), $options);
//             //if($i==1)break;
//         }
//             
//
//    }
}