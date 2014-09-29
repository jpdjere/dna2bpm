<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * sgr
 *
 */
class reports extends MX_Controller {

    function __construct() {
        parent::__construct();
//----habilita acceso a todo los metodos de este controlador
        $this->user->authorize();
        $this->load->config('config');
        $this->load->library('parser');
        $this->load->library('ui');
        $this->load->model('app');
        $this->load->model('user/user');
        $this->load->model('bpm/bpm');
        $this->load->model('user/rbac');
        $this->load->model('sgr/sgr_model');
        $this->load->helper('sgr/tools');
        $this->load->library('session');


        /* update db */
        $this->load->Model("mysql_model_periods");
        //$this->mysql_model_periods->call_every_one();
//---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . 'sgr/';
//----LOAD LANGUAGE
        $this->lang->load('library', $this->config->item('language'));

// IDU : Chequeo de sesion
        $this->idu = (float) switch_users($this->session->userdata('iduser'));

        /* bypass session */
        session_start();
        $_SESSION['idu'] = $this->idu;


        if (!isset($this->idu)) {
            header("$this->module_url/user/logout");
            exit();
        }

        /* DATOS SGR */
        $sgrArr = $this->sgr_model->get_sgr();
        foreach ($sgrArr as $sgr) {
            $this->sgr_id = (float) $sgr['id'];
            $this->sgr_nombre = $sgr['1693'];
            $this->sgr_cuit = $sgr['1695'];
        }

        /* ANEXO */
        $this->anexo = ($this->session->userdata['anexo_code']) ? $this->session->userdata['anexo_code'] : "06";

        /* PERIOD */
        if (isset($this->session->userdata['period']))
            $this->period = $this->session->userdata['period'];


        /* TIME LIMIT */
        set_time_limit(230400);
        //ini_set("error_reporting", 0);
    }

    function Index() {



        $customData = array();
        $default_dashboard = 'reports';
        $anexo = ($this->session->userdata['anexo_code']) ? $this->session->userdata['anexo_code'] : '06';
        $model = "model_" . $anexo;
        $this->load->model($model);

        /* HEADERS */
        $header_merge = array_merge($customData, $this->headers());
        foreach ($header_merge as $key => $each) {
            $customData[$key] = $each;
        }

        /* FORM TEMPLATE */
        $enables = array('06', '12', '14', '15', '201');

        if (in_array($this->anexo, $enables))
            $customData['form_template'] = $this->parser->parse('reports/form_' . $anexo, $customData, true);
        else
            $customData['form_template'] = "";

        $custom_sgr = ' <option value="' . $this->sgr_id . '">' . $this->sgr_nombre . '</option>';
        $customData['sgr_options'] = $this->get_sgrs();


        /* RENDER */
        $this->render($default_dashboard, $customData);
    }

    function action_form() {

       // ini_set("error_reporting", E_ALL);

        $customData = array();
        $default_dashboard = 'reports_result';
        $anexo = ($this->session->userdata['anexo_code']) ? $this->session->userdata['anexo_code'] : '06';
        $model = "model_" . $anexo;
        $this->load->model($model);




        /* HEADERS */
        $header_merge = array_merge($customData, $this->headers());
        foreach ($header_merge as $key => $each) {
            $customData[$key] = $each;
        }

        $rtn_report = $this->process(); //$this->$model->get_anexo_report($anexo, $this->process_06());
        $fileName = "reporte_al_" . date("j-n-Y"); //Get today

        $customData['form_template'] = $this->parser->parse('reports/form_result', $customData, true);
        $customData['show_table'] = ($rtn_report) ? $rtn_report : "";

        $fileName = $anexo . "_al_" . date("j-n-Y"); //Get today
        //Generate  file
        header("Content-Description: File Transfer");
        //header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Type: application/x-msexcel");
        header("Content-Type: application/force-download");
        header("Content-Disposition: attachment; filename=SGR_reporteAnexo" . $fileName . ".xls");
        header("Content-Description: PHP Generated XLSx Data");
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


    /* PROCESS */

    function process() {

        $anexo = $this->input->post('anexo');
        switch ($anexo) {
            case '06':
                return $this->process_06($anexo);
                break;

            case '12':
                return $this->process_12($anexo);
                break;

            case '14':
                return $this->process_14($anexo);
                break;

            case '15':
                return $this->process_15($anexo);
                break;

            case '201':
                return $this->process_201($anexo);
                break;
        }
    }

    function process_06($anexo) {

        $rtn = array();
        $report_name = $this->input->post('report_name');


        $rtn['input_period_from'] = ($this->input->post('input_period_from')) ? $this->input->post('input_period_from') : '01-1990';
        $rtn['input_period_to'] = ($this->input->post('input_period_to')) ? $this->input->post('input_period_to') : '01-2020';
        
         if ($this->input->post('cuit_socio'))
            $rtn['cuit_socio'] = $this->input->post('cuit_socio');        
        
        $rtn['sgr_id'] = $this->input->post('sgr');        
        
        if ($this->input->post('sgr')) {
            $model = "model_" . $anexo;
            $this->load->model($model);

            switch ($report_name) {
                case "A":
                    $result = $this->$model->get_anexo_report($anexo, $rtn);
                    break;
            }

            return $result;
        }
    }

    function process_20($anexo) {

        $rtn = array();
        $report_name = $this->input->post('report_name');


        $rtn['input_period_from'] = ($this->input->post('input_period_from')) ? $this->input->post('input_period_from') : '01-1990';
        $rtn['input_period_to'] = ($this->input->post('input_period_to')) ? $this->input->post('input_period_to') : '01-2020';
        $rtn['sgr_id'] = $this->input->post('sgr');
        if ($this->input->post('sgr')) {
            $model = "model_" . $anexo;
            $this->load->model($model);

            switch ($report_name) {
                case "A":
                    $result = $this->$model->get_anexo_report($anexo, $rtn);
                    break;
            }

            return $result;
        }
    }

    function process_201($anexo) {

        $rtn = array();
        $report_name = $this->input->post('report_name');


        $rtn['input_period_from'] = ($this->input->post('input_period_from')) ? $this->input->post('input_period_from') : '01-1990';
        $rtn['input_period_to'] = ($this->input->post('input_period_to')) ? $this->input->post('input_period_to') : '01-2020';
        $rtn['sgr_id'] = $this->input->post('sgr');
        if ($this->input->post('sgr')) {
            $model = "model_" . $anexo;
            $this->load->model($model);


            $result = $this->$model->get_anexo_report($anexo, $rtn);



            return $result;
        }
    }

    function process_15($anexo) {

        $rtn = array();
        $report_name = $this->input->post('report_name');


        $rtn['input_period_from'] = ($this->input->post('input_period_from')) ? $this->input->post('input_period_from') : '01-1990';
        $rtn['input_period_to'] = ($this->input->post('input_period_to')) ? $this->input->post('input_period_to') : '01-2020';
        $rtn['sgr_id'] = $this->input->post('sgr');
        if ($this->input->post('sgr')) {
            $model = "model_" . $anexo;
            $this->load->model($model);

            $result = $this->$model->get_anexo_report($anexo, $rtn);

            return $result;
        }
    }

    function process_14($anexo) {

        $rtn = array();
        $report_name = $this->input->post('report_name');


        $rtn['input_period_from'] = ($this->input->post('input_period_from')) ? $this->input->post('input_period_from') : '01-1990';
        $rtn['input_period_to'] = ($this->input->post('input_period_to')) ? $this->input->post('input_period_to') : '01-2020';
        
        if ($this->input->post('cuit_socio'))
            $rtn['cuit_socio'] = $this->input->post('cuit_socio');
        
         if ($this->input->post('nro_orden'))
            $rtn['nro_orden'] = $this->input->post('nro_orden');
        
        
        $rtn['sgr_id'] = $this->input->post('sgr');
        if ($this->input->post('sgr')) {
            $model = "model_" . $anexo;
            $this->load->model($model);

            $result = $this->$model->get_anexo_report($anexo, $rtn);

            return $result;
        }
    }

    function process_12($anexo) {

        $rtn = array();

        if ($this->input->post('cuit_sharer'))
            $rtn['cuit_sharer'] = $this->input->post('cuit_sharer');

        if ($this->input->post('cuit_creditor'))
            $rtn['cuit_creditor'] = $this->input->post('cuit_creditor');
        
        if ($this->input->post('order_number'))
            $rtn['order_number'] = $this->input->post('order_number');

        $rtn['input_period_from'] = ($this->input->post('input_period_from')) ? $this->input->post('input_period_from') : '01-1990';
        $rtn['input_period_to'] = ($this->input->post('input_period_to')) ? $this->input->post('input_period_to') : '01-2020';
        $rtn['sgr_id'] = $this->input->post('sgr');
        if ($this->input->post('sgr')) {
            $model = "model_" . $anexo;
            $this->load->model($model);

            $result = $this->$model->get_anexo_report($anexo, $rtn);

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
        $module_url = base_url() . 'sgr/reports/';
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

    function set_period() {
        $rectify = $this->input->post("rectify");
        $period = $this->input->post("input_period");
        $others = $this->input->post("others");
        $anexo = $this->input->post("anexo");

        if ($period) {
            $this->session->unset_userdata('period');
            $this->session->unset_userdata('rectify');
            $this->session->unset_userdata('others');

            $date_string = date('Y-m', strtotime('-1 month', strtotime(date('Y-m-01'))));

            list($month, $year) = explode("-", $period);
            $set_month = strtotime(date($year . '-' . $month . '-01'));

            $limit_month = strtotime('-1 month', strtotime(date('Y-m-01')));
            $set_start_month = strtotime(date('2013-12-30'));

            if ($this->idu == -342725103)
                $set_start_month = strtotime(date('2010-12-30'));

            if ($rectify) {
                $newdata = array('period' => $period, 'rectify' => $rectify, 'others' => $others);
                /* PERIOD SESSION */
                $this->session->set_userdata($newdata);
                redirect('/sgr');
            } else {
                if ($limit_month < $set_month) {
                    return "1"; // Posterior al mes actual
                } else if ($set_start_month > $set_month) {
                    return "2"; // Anterior al mes Inicial
                } else {
                    $get_period = $this->sgr_model->get_current_period_info($this->anexo, $period);
                    if ($get_period) {
                        return $this->input->post("input_period"); //Ya fue informado                    
                    } else {
                        $newdata = array('period' => $period);
                        $this->session->set_userdata($newdata);
                        redirect('/sgr');
                    }
                }
            }
        }
    }

    function unset_period() {

        $this->session->unset_userdata('rectify');
        $this->session->unset_userdata('others');
        $this->session->unset_userdata('period');
        redirect('/sgr');
    }

    function unset_period_active() {
        $this->session->unset_userdata('rectify');
        $this->session->unset_userdata('others');
        $this->session->unset_userdata('period');
    }

    function check_session_period() {
        if ($this->session->userdata['period']) {
            echo $this->session->userdata['period'];
        }
    }

// OFFLINE FALLBACK
    function offline() {
// testeo reemplazo appcache
        $customData = array();
        $customData['base_url'] = base_url();
        $customData['module_url'] = base_url() . 'sgr/';
        $this->render('offline', $customData);
    }

    function render($file, $customData) {
        $this->load->model('user/user');
        $this->load->model('msg');
        $this->load->language('inbox');
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
        $user_lastname = isset($user->lastname) ? $user->lastname : "";
        $user_name = isset($user->name) ? $user->name : "";
        $user_email = isset($user->email) ? $user->email : "";

        $cpData['username'] = strtoupper($user_lastname . ", " . $user_name);
        $cpData['usermail'] = $user_email;
        $cpData['rol'] = "Usuarios";
        $cpData['rol_icono'] = ($cpData['rol'] == 'coordinador') ? ('icon-group') : ('icon-user');

        $cpData = array_replace_recursive($customData, $cpData);

        /* Inbox Count MSgs */
        $mymgs = $this->msg->get_msgs($this->idu);
        $cpData['inbox_count'] = $mymgs->count();

// offline mark
        $cpData['is_offline'] = ($this->uri->segment(3) == 'offline') ? ('offline') : ('');

        $this->ui->compose($file, 'layout.php', $cpData);
    }

}
