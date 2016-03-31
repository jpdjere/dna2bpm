<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * sgr
 *
 */
class Central extends MX_Controller {

    function __construct() {
        parent::__construct();
        /* habilita acceso a todo los metodos de este controlador */
        //$this->user->authorize('modules/sgr/controllers/central');
        $this->load->config('config');
        $this->load->library('parser');
        $this->load->library('ui');
        $this->load->model('app');
        $this->load->model('user/user');
        $this->load->model('bpm/bpm');
        $this->load->model('user/rbac');
        $this->load->model('sgr/sgr_model');
        $this->load->model('sgr/model_141');
        $this->load->model('sgr/padfyj_model');
        $this->load->model('sgr/model_organos_sociales');
        $this->load->helper('sgr/tools');
        $this->load->library('session');


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
        // ini_set("error_reporting", E_ALL);
    }

    function Index() {

        $customData = array();
        $default_dashboard = 'central_deudores_view';


        /* HEADERS */
        $header_merge = array_merge($customData, $this->headers());
        foreach ($header_merge as $key => $each) {
            $customData[$key] = $each;
        }

        /* FORM TEMPLATE */

        $anexo = null;
        $customData['form_template'] = $this->parser->parse('central/form_control_panel' . $anexo, $customData, true);


        $custom_sgr = ' <option value="' . $this->sgr_id . '">' . $this->sgr_nombre . '</option>';
        $customData['sgr_options'] = $this->get_sgrs();


        /* RENDER */
        $this->render($default_dashboard, $customData);
    }

    function action_form() {

        $customData = array();
        $default_dashboard = 'central_deudores_view';
        /* HEADERS */
        $header_merge = array_merge($customData, $this->headers());
        foreach ($header_merge as $key => $each) {
            $customData[$key] = $each;
        }

        $rtn_report = $this->process();


        $customData['nombre_participe'] = $this->padfyj_model->search_name($this->input->post('cuit_sharer'));

        $cuit_sharer = $this->input->post('cuit_sharer');
        $cuit_sharer_a = substr($cuit_sharer, 0, -9);  // returns los 2 1eros nums
        $cuit_sharer_b = substr($cuit_sharer, 2, -1);  // returns el string del medio
        $cuit_sharer_c = substr($cuit_sharer, -1);

        $customData['cuit_participe'] = $cuit_sharer_a."-" .$cuit_sharer_b . "-". $cuit_sharer_c;

        $customData['logo'] = "http://" . $_SERVER['HTTP_HOST'] . "/dna2bpm/sgr/assets/images/orgullo.jpg"; //$this->module_url."/assets/images/orgullo.jpg";
        $customData['form_template'] = $this->parser->parse('reports/form_result', $customData, true);
        $customData['show_table'] = ($rtn_report) ? $rtn_report : "";

        /* RENDER CUSTOM */
        $this->render_custom($default_dashboard, $customData);
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
        return $this->process_debtors();
    }

    function process_debtors() {

        $rtn = array();
        $rtn['cuit_sharer'] = $this->input->post('cuit_sharer');

        $this->load->model('model_141');
        $result = $this->model_141->get_anexo_debtors($rtn);
        return $result;
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

    function render_custom($file, $customData) {



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

        $this->ui->compose($file, 'layout_central.php', $cpData);
    }

    function get_period_url_value() {
        return "01-2014";
    }

    function check_rectifications() {
        $this->load->Model("mysql_model_periods");
        $this->mysql_model_periods->active_periods_dna2();
    }

}
