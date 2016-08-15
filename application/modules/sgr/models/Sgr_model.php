<?php

/**
 * @class genia
 *
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sgr_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('sgr/tools');

        /* Additional SGR users */

        $additional_users = $this->additional_users($this->session->userdata('iduser'));
        $this->idu = (isset($additional_users)) ? $additional_users['sgr_idu'] : $this->session->userdata('iduser');

        /* SWITCH TO SGR DB */
        $this->load->library('cimongo/Cimongo.php', '', 'sgr_db');
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

    /* ADMINS MAILS DATA */

    function get_admin_emails() {
        $container = 'container.sgr_rectification_mail';
        $result = $this->mongowrapper->sgr->$container->find();
        return $result;
    }

    function get_rectification_pending_mails() {
        $container = 'container.sgr_periodos';
        $regex = new MongoRegex('/' . date('Y') . '-' . date('m') . '/');

        $query = array( 'rectified_on' => $regex, 
            #'sended' => null, 
            "status" => "rectificado");
        $result = $this->mongowrapper->sgr->$container->find($query);
        
        return $result;
    }

    function set_admin_email_ok($id) {

        $options = array('upsert' => true, 'w' => 1);
        $container = 'container.sgr_periodos';
        $query = array('id' => (float) $id, "status" => "rectificado");

        $parameter = array(
            'sended' => 'true',
            'sended_on' => date('Y-m-d h:i:s')
        );
        $rs = $this->mongowrapper->sgr->$container->update($query, array('$set' => $parameter), $options);
    }

    /* RETURN ANEXOS */

    function get_anexos() {
        $container = 'container.sgr_anexos';
        $result = $this->mongowrapper->sgr->$container->find();
        $result->sort(array('id' => 1));
        return $result;
    }

    function get_fre($idu) {
        $container = 'container.sgr_fre';
        $query = array('sgr_idu' => (int) $idu);
        $result = $this->mongowrapper->sgr->$container->find($query);

        $result->sort(array('title' => 1));
        return $result;
    }

    function get_anexo($anexo) {
        $container = 'container.sgr_anexos';
        $query['number'] = $anexo;
        $result = $this->mongowrapper->sgr->$container->findOne($query);
        return $result;
    }

    function get_processed_info() {

        $container = 'container.sgr_periodos';
        $fields = array('anexo', 'period', 'status', 'filename', 'id');
        $query = array(
            "status" => array('$ne' => 'rectificado'), "origen" => "2013", "status" => "activo"
        );

        $sort = array('sgr_id' => -1, 'anexo' => -1);
        $result = $this->mongowrapper->sgr->$container->find($query, $fields)->sort($sort);
        return $result;
    }

    function get_current_period_info($anexo, $period) {

        $container = 'container.sgr_periodos';
        $fields = array('anexo', 'period', 'status', 'filename', 'id', 'sgr_id');
        $query = array(
            'anexo' => $anexo,
            'sgr_id' => (float) $this->sgr_id,
            'period' => $period,
            "status" => array('$ne' => 'rectificado'),
        );

        //var_dump(json_encode($query));
        $result = $this->mongowrapper->sgr->$container->findOne($query, $fields);
        return $result;
    }

    function get_current_period_info_check($anexo, $period, $sgr_id) {

        $container = 'container.sgr_periodos';
        $fields = array('anexo', 'period', 'status', 'filename', 'id', 'sgr_id');
        $query = array(
            'anexo' => $anexo,
            'sgr_id' => (float) $sgr_id,
            'period' => $period,
            "status" => array('$ne' => 'rectificado'),
        );

        $result = $this->mongowrapper->sgr->$container->findOne($query, $fields);
        return $result;
    }

    function get_current_period_info_17($anexo, $period) {

        $container = 'container.sgr_periodos';
        $fields = array('anexo', 'period', 'status', 'filename', 'id');
        $query = array(
            'anexo' => $anexo,
            'sgr_id' => (float) $this->sgr_id,
            'period' => $period,
            "status" => array('$ne' => 'rectificado'),
        );



        $result = $this->mongowrapper->sgr->$container->findOne($query, $fields);
        return $result;
    }

    function get_current_period_info_141($period) {
        echo 'Funcion!</br>';
        $anexo = '141';
        $container = 'container.sgr_periodos';
        $status = 'activo';

        $fields = array('anexo', 'period', 'status', 'filename', 'id');
        $query = array(
            'anexo' => $anexo,
            'sgr_id' => (float) $this->sgr_id,
            'period' => $period,
            'origen' => '2013',
            "status" => 'activo',
        );

        //$sort = array('filename' => -1);
        //$result = $this->mongowrapper->sgr->$container->find($query, $fields)->sort($sort);    

        $result = $this->mongowrapper->sgr->$container->findOne($query, $fields);
        return $result;
    }

    function get_if_is_rectified($filename) {

        $container = 'container.sgr_periodos';
        $fields = array('id');
        $query = array(
            'filename' => $filename,
            "status" => array('$ne' => 'rectificado'),
        );
        $result = $this->mongowrapper->sgr->$container->findOne($query, $fields);
        return $result;
    }

    function get_period_count($anexo, $period) {
        $container = 'container.sgr_periodos';
        $fields = array('anexo', 'period', 'status', 'filename', 'id');
        $query = array('status' => 'rectificado', 'anexo' => $anexo, 'sgr_id' => (float) $this->sgr_id, 'period' => $period);
        $result = $this->mongowrapper->sgr->$container->find($query, $fields);
        return $result->count();
    }

    function get_period_filename($filename) {
        $container = 'container.sgr_periodos';
        $fields = array('anexo', 'period', 'status', 'filename', 'id', 'idu', 'sgr_id');
        $query = array("filename" => $filename);
        $result = $this->mongowrapper->sgr->$container->findOne($query, $fields);
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
        $result = $this->mongowrapper->sgr->$container->find($query, $fields)->sort($sort);

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
        $result = $this->mongowrapper->sgr->$container->find($query, $fields)->sort($sort);

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
        $result = $this->mongowrapper->sgr->$container->find($query, $fields)->sort($sort);

        foreach ($result as $list)
            $rtn_period[] = $list['period'];


        $arr_periods = array_unique($rtn_period);

        foreach ($arr_periods as $period) {
            $success = array();

            foreach ($anexos_arr as $anexo) {
                $query = array("period" => $period, 'anexo' => $anexo);
                $new_result = $this->mongowrapper->sgr->$container->findOne($query);

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
        $result = $this->mongowrapper->sgr->$container->find($query, $fields)->sort($sort);

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
        $result = $this->mongowrapper->sgr->$container->find($query)->sort($sort);

        foreach ($result as $list) {
            $rtn[] = $list;
        }
        return $rtn;
    }

    function get_pending($anexo, $sgr_id) {

        $rtn = array();

        $container = 'container.sgr_periodos';
        $query = array("status" => 'pendiente', "anexo" => $anexo, "sgr_id" => $sgr_id);
        $result = $this->mongowrapper->sgr->$container->find($query);

        foreach ($result as $list) {
            $rtn[] = $list;
        }
        return $rtn;
    }

    function update_sgrs() {

        $sgrs_data = $this->sgr_model->get_sgrs();

        $sgr_data_arr = array();
        foreach ($sgrs_data as $each_sgr) {

            $sgr_data['id'] = $each_sgr['id'];
            $sgr_data['cuit'] = str_replace("-", "", $each_sgr[1695]);
            $sgr_data['razon_social'] = $each_sgr[1693];
            $sgr_data['owner'] = $each_sgr['owner'];
            $sgr_data_arr[] = $sgr_data;
        }

        foreach ($sgr_data_arr as $eachdata)
            $this->mongowrapper->sgr->sgrs_data->save($eachdata);
    }

    /* SGRS COMPANY INFO */

    function get_sgrs() {
        $rtn = array();

        /* Listado de empresas */
        $sort = array(1693 => 1);
        $container = 'container.empresas_custom';
        //$query = array(6026 => '30', "status" => 'activa', 5281 => 'C');
        $result = $this->mongowrapper->sgr->$container->find();

        $result->sort($sort);

        foreach ($result as $sgrs) {
            $rtn[] = $sgrs;
        }

        return $rtn;
    }

    function get_sgr($sent_idu = null) {

        $rtn = array();

        if (isset($this->session->userdata['sgr_impersonate'])) {
            $idu = (float) $this->session->userdata['sgr_impersonate'];
        } else {

            $idu = (isset($sent_idu)) ? (float) $sent_idu : (float) $this->idu;
        }

        // Listado de empresas
        $container = 'container.empresas_custom';
        $fields = array('id', '1695', '4651', '1693', '1703');
        $query = array("owner" => $idu);
        $result = $this->mongowrapper->sgr->$container->find($query, $fields);


        foreach ($result as $empresa) {
            unset($empresa['_id']);
            $rtn[] = $empresa;
        }
        return $rtn;
    }

    function get_sgr_by_id_new($sgr_id) {
        $container = 'container.empresas_custom';
        $query = array("id" => $sgr_id);
        $result = $this->mongowrapper->sgr->$container->findOne($query);


        if (isset($result))
            return $result;
    }

    function get_sgr_by_id($sgr_id) {


        $rtn = array();
        // Listado de empresas
        $container = 'container.empresas_custom';
        $query = array("id" => $sgr_id);
        $result = $this->mongowrapper->db->$container->find($query, $fields);

        foreach ($result as $empresa) {

            $rtn[] = $empresa;
        }

        return $rtn;
    }

    function get_sgr_by_filename($filename) {

        $container = 'container.sgr_periodos';
        $query = array("filename" => $filename);
        $result = $this->mongowrapper->sgr->$container->findOne($query);

        if (isset($result))
            return $result['sgr_id'];
    }

    function get_sgrs_users() {
        $rtn = array();

        $container = 'users';
        $query = array("group" => 58);
        $result = $this->mongowrapper->db->$container->find($query);
        foreach ($result as $users) {

            $rtn[] = $users;
        }
        return $rtn;
    }

    function array_delete($value, $array) {
        $array = array_diff($array, array($value));
        return $array;
    }

    function clae2013($code, $type, $resolution = null) {
        
        if($resolution =='11/2016' && $type == "A")
            $type = $type. "_reso_11_2016";
            
        $sector = false;

        $code = (string) ((int) $code);

        $regex = new MongoRegex('/' . $code . '/');
        $container = 'container.sgr_clae_F883_2013_socios_' . $type;
        
        $query = array("code" => $regex);
        $fields = array("sector", "code");
        $result = $this->mongowrapper->sgr->$container->findOne($query, $fields);

        if ($result)
            $sector = $result['sector'];

        return $sector;
    }

    function clanae1999($code, $type) {


        $sector = false;

        $code = (string) ((int) $code);

        $regex = new MongoRegex('/' . $code . '/');
        $container = 'container.sgr_clanae_F150_1999_socios_' . $type;
        $query = array("code" => $regex);
        $fields = array("sector", "code");
        $result = $this->mongowrapper->sgr->$container->findOne($query, $fields);

        if ($result)
            $sector = $result['sector'];

        return $sector;
    }
    
     function get_resolution($inc_date) {
         $check_resolution = check_date_for_resolution($inc_date);
         return $check_resolution;
     }
   
    function get_company_size($sector, $average, $inc_date) {

        list($month_period, $year_period) = explode("-", $this->session->userdata['period']);

        $startDate = $year_period . "-" . $month_period . "-03";
        $lastDate = '2015-07-02'; //Desde el 02/07/2015 los límites Pyme son los siguientes (Resolución 357/2015)

        $check_resolution = check_date_for_resolution($inc_date);

        $query = array("sector" => $sector,);

        if ($check_resolution)
            $query["resolution"] = $check_resolution;


        $sector = (string) $sector;
        $container = 'container.sgr_size_empresa';

        $fields = array("average");

        $result = $this->mongowrapper->sgr->$container->findOne($query);


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
        $result = $this->mongowrapper->sgr->$container->findOne($query);
        return $result;
    }

    /* CÓDIGOS EMPRESAS EXTRANJERAS */

    function get_cuit_ext_company($cuit) {

        $cuit = (int) $cuit;

        $container = 'container.sgr_code_empresa_ext';
        $query = array("cuit" => trim($cuit));
        $result = $this->mongowrapper->sgr->$container->findOne($query);
        return $result;
    }
    
    function get_cuit_paises_inciso_g($cuit) {

        $cuit = (int) $cuit;

        $container = 'container.sgr_cuit_paises_inciso_g';
        $query = array("cuit" => trim($cuit));
        $result = $this->mongowrapper->sgr->$container->findOne($query);
        return $result;
    }

    /* GARANTIAS */

    function get_warranty_type($code, $period) {

        $add_period_query = null;
        $query = array("code" => utf8_decode($code));

        $pyear = get_reference_year($period);

        if (isset($pyear))
            $query = array("code" => utf8_decode($code), "parent" => "forms2");

        $container = 'container.sgr_tipo_garantias';
        $result = $this->mongowrapper->sgr->$container->findOne($query);

        return $result;
    }

    function get_warranty_data($order_number, $options = null) {
        $container = 'container.sgr_anexo_12';
        $period = 'container.sgr_periodos';
        $query = array('status' => 'activo', 'anexo' => '12', 'sgr_id' => $this->sgr_id);
        if ($options) {
            $optionArr = array("period" => $options);
        }
        $result = $this->mongowrapper->sgr->$period->find($query);
        foreach ($result as $list) {
            $new_query = array(5214 => $order_number, 'filename' => $list['filename']);
            $new_result = $this->mongowrapper->sgr->$container->findOne($new_query);
        }
        return $new_result;
    }

    function get_investment_options($code) {
        $container = 'container.sgr_opciones_inversion';
        $query = array("inciso_art_25" => utf8_decode($code));
        $result = $this->mongowrapper->sgr->$container->findOne($query);
        return $result;
    }
    
    function get_investment_options_parametrization($code, $identification) {
        $container = 'container.sgr_opciones_inversion_parametrizacion';
        $query = array("inciso_art_25" =>  $code,  "identificacion" =>$identification);
        $result = $this->mongowrapper->sgr->$container->findOne($query);
        return $result;
    }

    function get_depositories($code) {
        $container = 'container.sgr_entidades_depositarias';
        $query = array("codigo" => utf8_decode($code));
        
        $result = $this->mongowrapper->sgr->$container->findOne($query);

        return $result;
    }

    /* COTIZACION */

    function get_dollar_quotation($period, $currency = "dolar americano") {

        $quotation_date = date("Y-m-d", mktime(0, 0, 0, 1, -1 + ($period - 1), 1900));

        $container = 'container.sgr_cotizacion_dolar';
        $quotation_date = new MongoDate(strtotime($quotation_date));

        $query = array('date' => array(
                '$lte' => $quotation_date
        ));
        $result = $this->mongowrapper->sgr->$container->find($query)->sort(array('date' => -1))->limit(1);

        foreach ($result as $each)
            return $each['amount'];
    }

    function get_dollar_quotation_period($currency = "dolar americano") {

        $endDate = last_month_date($this->session->userdata['period']);
        $container = 'container.sgr_cotizacion_dolar';

        $query = array('date' => array(
                '$lte' => $endDate
        ));
        $result = $this->mongowrapper->sgr->$container->find($query)->sort(array('date' => -1))->limit(1);

        foreach ($result as $each) {
            return $each['amount'];
        }
    }

    /* GET ACTIVE ANEXOS */

    function get_just_active($anexo, $period = null, $no_movement = null) {

        $rtn = array();
        $container = 'container.sgr_periodos';

        $query = array(
            'anexo' => $anexo,
            'sgr_id' => $this->sgr_id,
            'status' => 'activo',
        );


        if (isset($period)) {
            $query["period"] = $period;
        }

        if (isset($no_movement)) {
            $query["filename"] = array('$ne' => 'SIN MOVIMIENTOS');
        }


        $result = $this->mongowrapper->sgr->$container->find($query);

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



        $result = $this->mongowrapper->sgr->$container->find($query);

        foreach ($result as $each) {

            $rtn[] = $each;
        }
        return $rtn;
    }

    function check_if_active($filename, $sgr_id) {
        $rtn = false;
        $container = 'container.sgr_periodos';

        $query = array(
            'sgr_id' => $sgr_id,
            'anexo' => '141',
            'status' => 'activo'
        );

        $result = $this->mongowrapper->sgr->$container->find($query)->sort(array('period_date' => -1))->limit(1);


        foreach ($result as $rs)
            $get_filename = $rs['filename'];

        if ($get_filename == $filename)
            $rtn = true;

        return $rtn;
    }

    function get_active_tmp($anexo, $exclude_this = null) {
        $rtn = array();
        $token = $this->idu;
        $period = 'container.periodos_' . $token . '_tmp';

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


        $result = $this->mongowrapper->sgr->$period->find($query);

        foreach ($result as $each) {

            $rtn[] = $each;
        }
        return $rtn;
    }

    /* WEB SERVICE */

    function get_active_ws($anexo, $date_from, $date_to) {

        $rtn = array();
        $period = 'container.sgr_periodos';

        /* FOR TEST */
        $fecha_desde = isset($date_from) ? $date_from : '02-2015';
        $fecha_hasta = isset($date_to) ? $date_to : '02-2015';

        $query = array(
            'anexo' => $anexo,
            "filename" => array('$ne' => 'SIN MOVIMIENTOS'),
            'status' => 'activo'
        );

        $query['period_date'] = array('$gte' => first_month_date($fecha_desde, true), '$lte' => last_month_date($fecha_hasta, true));
        $result = $this->mongowrapper->sgr->$period->find($query);

        foreach ($result as $each)
            $rtn[] = $each;

        return $rtn;
    }

    function get_active($anexo, $exclude_this = false) {
        $rtn = array();
        $period = 'container.sgr_periodos';

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
        
        
        $result = $this->mongowrapper->sgr->$period->find($query);

        foreach ($result as $each)
            $rtn[] = $each;
            

        return $rtn;
    }

    /* JUST FOR FRE ONLY */

    function get_active_fre($anexo, $exclude_this = false) {
        $rtn = array();
        $period = 'container.sgr_periodos';

        $endDate = last_month_date($this->session->userdata['period']);


        /* GET SGR DATA */
        $sgr_params_arr = $this->get_fre($this->session->userdata['fre_session']);

        foreach ($sgr_params_arr as $sgr_params) {
            $sgr_id = $sgr_params['sgr_id'];
        }


        $query = array(
            'sgr_id' => (float) $sgr_id,
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


        $result = $this->mongowrapper->sgr->$period->find($query);

        foreach ($result as $each)
            $rtn[] = $each;

        return $rtn;
    }

    function get_active_each_sgrid($anexo, $sgr_id) {
        $rtn = array();
        $period = 'container.sgr_periodos';


        $query = array(
            'sgr_id' => (float) $sgr_id,
            'anexo' => $anexo,
            'status' => 'activo'
        );

        $result = $this->mongowrapper->sgr->$period->find($query);

        foreach ($result as $each) {

            $rtn[] = $each;
        }

        return $rtn;
    }

    function get_active_each_sgrid_with_limit($anexo, $sgr_id, $end_date) {
        $rtn = array();
        $period = 'container.sgr_periodos';

        $end_date = last_month_date($end_date);

        $query = array(
            'sgr_id' => (float) $sgr_id,
            'anexo' => $anexo,
            'status' => 'activo',
            'period_date' => array(
                '$lte' => $end_date
            ),
        );

        $result = $this->mongowrapper->sgr->$period->find($query);
        foreach ($result as $each)
            $rtn[] = $each;

        return $rtn;
    }

    /* GET ACTIVE for PRINT ANEXOS */

    function get_active_one($anexo, $get_period) {

        $rtn = array();
        $period = 'container.sgr_periodos';

        $query = array(
            'anexo' => $anexo,
            "filename" => array('$ne' => 'SIN MOVIMIENTOS'),
            'sgr_id' => (float) $this->sgr_id,
            'status' => 'activo',
            'period' => $get_period
        );

        $result = $this->mongowrapper->sgr->$period->find($query);
        foreach ($result as $each)
            $rtn[] = $each;

        return $rtn;
    }

    function get_active_print($anexo, $period_date) {


        $rtn = array();
        $period = 'container.sgr_periodos';

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

        $result = $this->mongowrapper->sgr->$period->find($query);
        foreach ($result as $each)
            $rtn[] = $each;



        return $rtn;
    }

    function get_active_print_check($anexo, $period_date, $sgr_id) {


        $rtn = array();
        $period = 'container.sgr_periodos';

        $endDate = last_month_date($period_date);

        $query = array(
            'anexo' => $anexo,
            "filename" => array('$ne' => 'SIN MOVIMIENTOS'),
            'sgr_id' => (float) $sgr_id,
            'status' => 'activo',
            'period_date' => array(
                '$lte' => $endDate
            ),
        );

        $result = $this->mongowrapper->sgr->$period->find($query);
        foreach ($result as $each)
            $rtn[] = $each;



        return $rtn;
    }

    function get_period_data($anexo, $period_date, $exclude_sm = false) {

        $rtn = array();
        $period = 'container.sgr_periodos';
        $query = array(
            'anexo' => $anexo,
            'sgr_id' => (float) $this->sgr_id,
            'status' => 'activo',
            'period' => $period_date,
        );

        if (!$exclude_sm) {
            $query['filename'] = array('$ne' => 'SIN MOVIMIENTOS');
        }

        $result = $this->mongowrapper->sgr->$period->find($query);
        foreach ($result as $each) {
            $rtn[] = $each;
        }
        return $rtn;
    }

    function get_active_last_rec($anexo, $exclude_this = false) {
        $rtn = array();
        $period = 'container.sgr_periodos';

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


        $result = $this->mongowrapper->sgr->$period->find($query)->sort(array('period_date' => -1))->limit(1);

        foreach ($result as $each) {
            $rtn[] = $each;
        }
        return $rtn;
    }

    function get_active_other_sgrs($anexo, $exclude_this = false) {
        $rtn = array();
        $period = 'container.sgr_periodos';

        $query = array(
            'anexo' => $anexo,
            "filename" => array('$ne' => 'SIN MOVIMIENTOS'),
            'sgr_id' => array('$ne' => $this->sgr_id),
            'status' => 'activo'
        );
        
         if (!$exclude_this) {
            $query['period'] = array('$ne' =>  $this->session->userdata['period']);
        }


        $result = $this->mongowrapper->sgr->$period->find($query);
        foreach ($result as $each) {
            $rtn[] = $each;
        }
        return $rtn;
    }

    function update_sgrs_users() {
        var_dump($_SERVER["SERVER_NAME"]);
        if ($_SERVER["SERVER_NAME"] != "dna2-tests.industria.gob.ar")
            exit;

        $rtn = array();
        $collection = 'users';
        $query = array("idgroup" => 58);
        $action = array('$set' => array('passw' => md5("1234")));
        $options = array('upsert' => true);

        $qry = $this->mongowrapper->db->$collection->find($query);
        foreach ($qry as $each) {
            $rtn[] = $each['nick'];
            $query = array("idu" => $each['idu']);
            $rf = $this->mongowrapper->db->$collection->update($query, $action, $options);
        }
        return $rtn;
    }

    function additional_users($idu) {
        $container = 'container.sgr_additional_users';
        $query = array(
            'idu' => (int) $idu,
        );
        $result = $this->mongowrapper->sgr->$container->findOne($query);
        return $result;
    }

    /* REPORTS */

    function del_tmp_general() {

        $container = 'container.sgr_anexo_report_' . $this->idu . '_tmp';
        $delete = $this->mongowrapper->sgr_tmp->$container->remove();
    }

    function last_report_general() {
        $container = 'container.sgr_anexo_report_' . $this->idu . '_tmp';

        $rtnRslt = $this->mongowrapper->sgr_tmp->$container->find();

        if ($rtnRslt->count() > 0) {
            return $rtnRslt;
        }
    }

    function last_report_is_custom() {
        $container = 'container.sgr_anexo_report_' . $this->idu . '_tmp';

        $rtnRslt = $this->mongowrapper->sgr_tmp->$container->findOne();

        return $rtnRslt;
    }

    function save_tmp_general($parameter, $id) {

        $container = 'container.sgr_anexo_report_' . $this->idu . '_tmp';

        $criteria = array('id' => $id);
        $update = array('$set' => $parameter);
        $options = array('upsert' => true, 'w' => 1);
        $result = $this->mongowrapper->sgr_tmp->selectCollection($container)->update($criteria, $update, $options);

        if ($result) {
            $out = array('status' => 'ok');
        } else {
            $out = array('status' => 'error');
        }
        return $out;
    }
    
    /*UPDATE FILENAME NAME*/
    function update_filename_field($old_name, $new_name){
        
        var_dump($anexo, $old_name, $new_name);
        
        $rtn = array();
        $container = 'container.sgr_periodos';
        $query = array('FILENAME' => $old_name);
        $result = $this->mongowrapper->sgr->$container->find($query);

        
        foreach ($result as $list) {
            
            
             echo $list['filename'] . "<br>";
             
            
            $options = array('upsert' => true, 'w' => 1);
            $parameter = array('FILENAME' =>$new_name);
            $rs = $this->mongowrapper->sgr->$container->update($query, array('$set' => $parameter), $options);
            
            if (isset($rs)) {
            $out = array('status' => 'ok');
            } else {
                $out = array('status' => 'error');
            }
          var_dump($out, $list['FILENAME']);
           
        
	}
        
        
        
    }

}
