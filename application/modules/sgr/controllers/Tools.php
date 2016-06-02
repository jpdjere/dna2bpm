<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * sgr
 *
 */
class Tools extends MX_Controller {

    function __construct() {
        parent::__construct();
        /* habilita acceso a todo los metodos de este controlador */
        $this->user->authorize('modules/sgr/controllers/tools');
        $this->load->config('config');
        $this->load->library('parser');
        $this->load->library('ui');
        $this->load->model('app');
        $this->load->model('user/user');
        $this->load->model('bpm/bpm');
        $this->load->model('user/rbac');
        $this->load->model('sgr/sgr_model');
        $this->load->model('sgr/model_141');
        $this->load->model('sgr/model_organos_sociales');
        $this->load->helper('sgr/tools');
        $this->load->library('session');

        /* update db with mysql forms2 */
        $this->load->Model("mysql_model_periods");
        $this->mysql_model_periods->active_periods_dna2();

        /* base variables */
        $this->base_url = base_url();
        $this->module_url = base_url() . 'sgr/';

        /* LOAD LANGUAGE */
        $this->lang->load('library', $this->config->item('language'));

        /* IDU : USER CHECK / Additional SGR users */
        $additional_users = $this->sgr_model->additional_users($this->session->userdata('iduser'));
        $this->idu = (isset($additional_users)) ? $additional_users['sgr_idu'] : $this->session->userdata('iduser');

        /* bypass session */
        session_start();

        /* to interact with /sgr */
        $_SESSION['idu'] = $this->idu;

        /* IF NOT A SGR'S USER -> Exit */
        if (!$this->idu) {
            header("$this->module_url/user/logout");
            exit();
        }

        /* SGR'S DATA */
        $sgrArr = $this->sgr_model->get_sgr();
        foreach ($sgrArr as $sgr) {
            $this->sgr_id = (float) $sgr['id'];
            $this->sgr_nombre = $sgr['1693'];
            $this->sgr_cuit = $sgr['1695'];
        }
        $this->anexo = (isset($this->session->userdata['anexo_code'])) ? $this->session->userdata['anexo_code'] : "06";

        if (isset($this->session->userdata['period']))
            $this->period = $this->session->userdata['period'];

        /* TIME LIMIT */
        set_time_limit(28800);
    }



    /*FIX ISODATE*/
    function fix_isodate(){
        
        /*ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);*/

         $this->load->Model('model_06');
         $rs = $this->model_06->update_idodate();
         echo "result " . $rs;
    }
    
    
    function fix_fieldname($var1, $var2){
         $this->load->Model('sgr_model');
         $rs = $this->sgr_model->update_filename_field($var1, $var2);
         echo "result " . $rs;
    }

    function hand_over_users() {
        $result = $this->sgr_model->update_sgrs_users();
        debug($result);
    }

    function hand_over_sgrs() {
        $result = $this->sgr_model->update_sgrs();
        debug($result);
    }

    function Index() {

        $customData = array();
        $default_dashboard = 'tools';


        /* HEADERS */
        $header_merge = array_merge($customData, $this->headers());
        foreach ($header_merge as $key => $each) {
            $customData[$key] = $each;
        }

        /* FORM TEMPLATE */


        $customData['form_template'] = $this->parser->parse('tools/form_control_panel' . $anexo, $customData, true);


        $custom_sgr = ' <option value="' . $this->sgr_id . '">' . $this->sgr_nombre . '</option>';
        $customData['sgr_options'] = $this->get_sgrs();


        /* RENDER */
        $this->render($default_dashboard, $customData);
    }

    function action_form() {

        $customData = array();
        $default_dashboard = 'reports_result';
        /* HEADERS */
        $header_merge = array_merge($customData, $this->headers());
        foreach ($header_merge as $key => $each) {
            $customData[$key] = $each;
        }

        $rtn_report = $this->process(); //$this->$model->get_anexo_report($anexo, $this->process_06());
        $fileName = "reporte_al_" . date("j-n-Y"); //Get today

        $customData['form_template'] = $this->parser->parse('reports/form_result', $customData, true);
        $customData['show_table'] = ($rtn_report) ? $rtn_report : "";

        $fileName = "_al_" . date("j-n-Y"); //Get today
        //Generate  file
        header("Content-Description: File Transfer");
        header("Content-type: application/x-msexcel");
        header("Content-Type: application/force-download");
        header("Content-Disposition: attachment; filename=SGR_Tools" . $fileName . ".xls");
        header("Content-Description: PHP Generated XLS Data");
        /* RENDER */
        $this->render($default_dashboard, $customData);
    }

    /* ASSETS HEADERS */

    function headers() {
        $rtn = array();

        $rtn['sgr_nombre'] = $this->sgr_nombre;
        $rtn['sgr_id'] = $this->sgr_id;
        $rtn['sgr_id_encode'] = base64_encode($this->sgr_id);
        $rtn['base_url'] = base_url();
        $rtn['module_url'] = base_url() . 'sgr/';
        $rtn['titulo'] = "";
        $rtn['js'] = array($this->module_url . "assets/jscript/dashboard.js" => 'Dashboard JS', $this->module_url . "assets/jscript/jquery-validate/jquery.validate.min_1.js" => 'Validate');
        $rtn['css'] = array($this->module_url . "assets/css/dashboard.css" => 'Dashboard CSS');

        $rtn['anexo'] = $this->anexo;
        $rtn['anexo_title'] = $this->oneAnexoDB($this->anexo);
        $rtn['anexo_title_cap'] = strtoupper($this->oneAnexoDB($this->anexo));
        $rtn['anexo_list'] = $this->AnexosDB();
        $rtn['anexo_short'] = $this->oneAnexoDB_short($this->anexo);

        return $rtn;
    }

    /* PROCESS */

    function process() {
        return $this->process_control_panel();
    }

    function process_control_panel() {

        $rtn = array();
        $report_name = $this->input->post('report_name');

        $rtn['input_period_from'] = ($this->input->post('input_period_from')) ? $this->input->post('input_period_from') : '01-1990';
        $rtn['input_period_to'] = ($this->input->post('input_period_to')) ? $this->input->post('input_period_to') : '01-2020';
        $rtn['sgr_id'] = $this->input->post('sgr');
        if ($this->input->post('sgr')) {
            $model = "model_control_panel";
            $this->load->model($model);
            $result = $this->$model->get_anexo_report($rtn);
            return $result;
        }
    }

    /* SGRS */

    function get_processed() {
        $sgrArr = $this->sgr_model->get_processed_info();
        foreach ($sgrArr as $data)
            echo "<p>Anexo " . $data['anexo'] . " | Periodo " . $data['period'] . " Archivo: " . $data['filename'] . "</p>";
    }

    function get_sgrs() {
        $sgrArr = $this->sgr_model->get_sgrs();
        $rtn = "<option value=666>TODAS</option>";

        foreach ($sgrArr as $sgr) {
            $this->sgr_id = (float) $sgr['id'];
            $this->sgr_nombre = $sgr['1693'];
            $this->sgr_cuit = $sgr['1695'];

            $rtn .= "<option value=" . $sgr['id'] . ">" . $sgr['1693'] . "</option>";
        }
        return $rtn;
    }

    /* ANEXOS FNs */

    function Anexo_code($parameter) {
        /* BORRO SESSION RECTIFY */

        $this->session->unset_userdata('rectify');
        $this->session->unset_userdata('others');


        $newdata = array('anexo_code' => $parameter);
        $this->session->set_userdata($newdata);
        redirect('sgr/reports/');
    }

    function AnexosDB($target = '_self') {
        $module_url = base_url() . 'sgr/';
        $anexosArr = $this->sgr_model->get_anexos();
        $result = "";
        foreach ($anexosArr as $anexo) {
            /*
             * FILTER 4 FRE
             * FONDOS DE AFECTACIÓN ESPECÍFICOS, no deben tener la opcion de subir los Anexo 6, ni 6.1 ni 6.2.
             */
            $chunk_id = (int) $anexo['id'];
            $limit_chunk_id = (isset($this->session->userdata['fre_session'])) ? 3 : 0;

            if ($chunk_id > $limit_chunk_id)
                $result .= '<li><a target="' . $target . '" href=  "' . $module_url . 'anexo_code/' . $anexo['number'] . '"> ' . $anexo['title'] . ' <strong>[' . $anexo['short'] . ']</strong></a></li>';
        }
        return $result;
    }

    function oneAnexoDB() {
        $anexoValues = $this->sgr_model->get_anexo($this->anexo);
        return $anexoValues['title'];
    }

    function oneAnexoDB_short() {
        $anexoValues = $this->sgr_model->get_anexo($this->anexo);
        return $anexoValues['short'];
    }

    function render($file, $customData) {
        $this->load->model('user/user');
        $this->load->model('msg');
        $this->load->language('inbox/inbox');
        $cpData['lang'] = $this->lang->language;
        $segments = $this->uri->segment_array();
        $cpData['nolayout'] = (in_array('nolayout', $segments)) ? '1' : '0';
        $cpData['theme'] = $this->config->item('theme');
        $cpData['base_url'] = $this->base_url;
        $cpData['module_url'] = $this->module_url;
        $cpData['global_js'] = array(
            'base_url' => $this->base_url,
            'module_url' => $this->module_url,
            'idu' => $this->idu
        );
        $user = $this->user->get_user($this->idu);

        $cpData['user'] = (array) $user;
        // $cpData['isAdmin'] = $this->user->isAdmin($user);
        $cpData['username'] = strtoupper($user->lastname . ", " . $user->name);
        $cpData['usermail'] = $user->email;
// Profile 
//$cpData['profile_img'] = get_gravatar($user->email);

        $cpData['gravatar'] = (isset($user->avatar)) ? $this->base_url . $user->avatar : get_gravatar($user->email);
        $cpData['rol'] = "Usuarios";
        $cpData['rol_icono'] = ($cpData['rol'] == 'coordinador') ? ('icon-group') : ('icon-user');

        $cpData = array_replace_recursive($customData, $cpData);



// offline mark
        $cpData['is_offline'] = ($this->uri->segment(3) == 'offline') ? ('offline') : ('');

        $this->ui->compose($file, 'layout.php', $cpData);
    }

    function get_period_url_value() {
        return "01-2014";
    }

    function check_rectifications() {
        $this->load->Model("mysql_model_periods");
        $this->mysql_model_periods->active_periods_dna2();
    }

    function fix_anexo141_balance_form() {

        $customData = array();
        $default_dashboard = 'tools';



        /* HEADERS */
        $header_merge = array_merge($customData, $this->headers());
        foreach ($header_merge as $key => $each) {
            $customData[$key] = $each;
        }

        /* FORM TEMPLATE */


        $customData['form_template'] = $this->parser->parse('tools/fix_141_balance_form', $customData, true);


        $custom_sgr = ' <option value="' . $this->sgr_id . '">' . $this->sgr_nombre . '</option>';
        $customData['sgr_options'] = $this->get_sgrs();


        /* RENDER */
        $this->render($default_dashboard, $customData);
    }

    function fix_anexo141_balance() {

        ini_set("error_reporting", E_ALL);
        $period = $this->input->post('period');
        $sgr_id = $this->input->post('sgr');

        if (empty($period)) {
            var_dump($period);
            echo 'Falta cargar el periodo.';
            return;
        }

        $this->model_141->fix_anexo141_balance_model($period, $sgr_id, $debug = true);
    }

    function fix_anexo141_balance_tool() {

        //ini_set("error_reporting", E_ALL);
        $anexo = "14";
        $result = $this->sgr_model->get_active_other_sgrs($anexo);
        //var_dump($result, $result['sgr_id'], $result['period']);

        foreach ($result as $each) {
            list($mperiod, $yperiod) = explode("-", $each['period']);


                /*SOLO ACINDAR #26955*/
             //$arr = array("09-2015", "10-2015", "11-2015", "12-2015");            
            //if (in_array($each['period'], $arr) && $each['sgr_id'] == 2478671474) {
            
            if (($yperiod == "2015") && $each['sgr_id'] == 3303455306) {
                $period = $each['period'];
                $sgr_id = $each['sgr_id'];

                var_dump($period, $sgr_id);

                $this->model_141->fix_anexo141_balance_model($period, $sgr_id, $debug = true);
            }
        }
    }

    function fix_anexo141_balance_original() {


        // ini_set("error_reporting", 0);


        $period = $this->input->post('period');
        $sgr_id = $this->input->post('sgr');


        /* DYNAMIC INFO */
        $this->load->Model('model_125');
        $this->load->Model('model_12');
        $this->load->Model('model_14');
        $this->load->Model('model_141');


        if (empty($period)) {
            var_dump($period);
            echo 'Falta cargar el periodo.';
            return;
        }


        $anexo = '141';

        $container_periodos = 'container.sgr_periodos';
        $status = 'activo';

        $fields = array('anexo', 'period', 'status', 'filename', 'id', 'sgr_id');
        $query = array(
            'anexo' => $anexo,
            'period' => $period,
            'status' => $status,
            'origen' => '2013'
        );


        if ($sgr_id != '666')
            $query['sgr_id'] = (float) $sgr_id;

        //Busco los archivos del período:
        $result = $this->mongowrapper->sgr->$container_periodos->find($query, $fields);

        foreach ($result as $arch) {



            $container_141 = 'container.sgr_anexo_141';
            $fields_141 = array('CUIT_PARTICIPE', 'MONTO_ADEUDADO', 'filename', 'id');
            $query_141 = array('filename' => $arch['filename'],
            );



            $result_141 = $this->mongowrapper->sgr->$container_141->find($query_141, $fields_141);
            foreach ($result_141 as $res_141) {
                $QTY = null;
                $col12_arr = array();

                $cuit = $res_141["CUIT_PARTICIPE"];


                /* GET ALL WARRANTIES BY PARTNER */

                $get_warranty_partner = $this->model_12->get_warranty_partner_print_check($cuit, $period, $arch['sgr_id']);
                /* FIX DEUDA */
                $files_arr = array();
                foreach ($get_warranty_partner as $each) {
                    $files_arr[] = $each[5214];
                }

                $files_arr = array_unique($files_arr);



                foreach ($files_arr as $each) {



                    $caida_result_arr = array();
                    $recupero_result_arr = array();
                    $inc_periodo_arr = array();
                    $gasto_efectuado_periodo_arr = array();
                    $recupero_gasto_periodo_arr = array();
                    $gasto_incobrable_periodo_arr = array();


                    $get_movement_data = $this->model_14->get_movement_data_print_check($each, $period, $arch['sgr_id']);


                    /* 30707861203 */
                    $caida_result_arr[] = $get_movement_data['CAIDA'];
                    $recupero_result_arr[] = $get_movement_data['RECUPERO'];
                    $inc_periodo_arr[] = $get_movement_data['INCOBRABLES_PERIODO'];
                    $gasto_efectuado_periodo_arr[] = $get_movement_data['GASTOS_EFECTUADOS_PERIODO'];
                    $recupero_gasto_periodo_arr[] = $get_movement_data['RECUPERO_GASTOS_PERIODO'];
                    $gasto_incobrable_periodo_arr[] = $get_movement_data['GASTOS_INCOBRABLES_PERIODO'];

                    $all_arr = array(
                        $get_movement_data['CAIDA'],
                        $get_movement_data['RECUPERO'],
                        $get_movement_data['INCOBRABLES_PERIODO']
                    );


                    if (array_sum($all_arr) != 0) {
                        /* CALC COL12 */

                        $caida_sum_tmp = array_sum($caida_result_arr);
                        $recupero_sum_tmp = array_sum($recupero_result_arr);
                        $inc_periodo_sum_tmp = array_sum($inc_periodo_arr);
                        $sum_tmp = ($caida_sum_tmp - $recupero_sum_tmp) - $inc_periodo_sum_tmp;

                        /* IF POSITIVE BALANCE */
                        if ($sum_tmp) {
                            $col12_arr[] = $cuit;
                        }
                    }
                }

                $caida_sum = array_sum($caida_result_arr);
                $recupero_sum = array_sum($recupero_result_arr);
                $inc_periodo_sum = array_sum($inc_periodo_arr);
                $gasto_efectuado_periodo_sum = array_sum($gasto_efectuado_periodo_arr);
                $recupero_gasto_periodo_sum = array_sum($recupero_gasto_periodo_arr);
                $gasto_incobrable_periodo_sum = array_sum($gasto_incobrable_periodo_arr);


                $sum_1 = ($caida_sum - $recupero_sum) - $inc_periodo_sum;
                $sum_2 = ($gasto_efectuado_periodo_sum - $recupero_gasto_periodo_sum) - $gasto_incobrable_periodo_sum;
                $sum_total = array_sum(array($sum_1, $sum_2));

                /* UPDATE QTY GARANTIAS */

                $QTY = count($col12_arr);

                /* UPDATE */
                $container_141_balance = 'container.sgr_anexo_141.balance';
                $res = $this->model_141->update_141_balance($sgr_id, $cuit, $QTY, $res_141['MONTO_ADEUDADO']);

                if ($QTY == 0) {

                    $options = array('upsert' => true, 'w' => 1);
                    $container_141 = 'container.sgr_anexo_141';
                    $query_141 = array(
                        'filename' => $arch['filename'],
                        'CUIT_PARTICIPE' => $res_141['CUIT_PARTICIPE'],
                        'sgr_id' => $arch['sgr_id'],
                        'id' => $res_141['id']
                    );

                    $parameter = array(
                        'MONTO_ADEUDADO' => $QTY
                    );

                    $parameter = array(
                        'CANTIDAD_GARANTIAS_AFRONTADAS' => $QTY
                    );

                    //var_dump($cuit . " Cantidad: " . $QTY);

                    $this->mongowrapper->sgr->$container_141->update($query_141, array('$set' => $parameter), $options);
                } else {



                    //var_dump($res);

                    if ($QTY != $res_141['CANTIDAD_GARANTIAS_AFRONTADAS']) {



                        $options = array('upsert' => true, 'w' => 1);
                        $container_141 = 'container.sgr_anexo_141';
                        $query_141qty = array(
                            'filename' => $arch['filename'],
                            'CUIT_PARTICIPE' => $res_141['CUIT_PARTICIPE'],
                            'sgr_id' => $arch['sgr_id'],
                            'id' => $res_141['id']
                        );
                        $parameter = array(
                            'CANTIDAD_GARANTIAS_AFRONTADAS' => $QTY
                        );

                        var_dump($cuit . " Cantidad: " . $QTY);

                        $this->mongowrapper->sgr->$container_141->update($query_141qty, array('$set' => $parameter), $options);
                    }

                    //  var_dump($QTY, $cuit, $res_141['MONTO_ADEUDADO']);


                    /* UPDATE MONTO */
                    if ($sum_total != $res_141['MONTO_ADEUDADO']) {
                        $options = array('upsert' => true, 'w' => 1);
                        $container_141 = 'container.sgr_anexo_141';
                        $query_141 = array(
                            'filename' => $arch['filename'],
                            'CUIT_PARTICIPE' => $res_141['CUIT_PARTICIPE'],
                            'sgr_id' => $arch['sgr_id'],
                            'id' => $res_141['id']
                        );

                        $parameter = array(
                            'MONTO_ADEUDADO' => $sum_total
                        );

                        // var_dump($parameter);

                        $this->mongowrapper->sgr->$container_141->update($query_141, array('$set' => $parameter), $options);
                    }
                }
            }
        }

        echo "fin..";
    }

}
