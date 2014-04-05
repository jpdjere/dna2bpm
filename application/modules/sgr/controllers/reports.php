<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * sgr
 *
 */
class Reports extends MX_Controller {

    function __construct() {
        parent::__construct();
//----habilita acceso a todo los metodos de este controlador
        $this->user->authorize('modules/sgr/controllers/sgr');
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

//---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . 'sgr/';
//----LOAD LANGUAGE
        $this->lang->load('library', $this->config->item('language'));

// IDU : Chequeo de sesion
        $this->idu = (float) $this->session->userdata('iduser');

        /* bypass session */
        session_start();
        $_SESSION['idu'] = $this->idu;


        if (!$this->idu) {
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


        $this->anexo = ($this->session->userdata['anexo_code']) ? $this->session->userdata['anexo_code'] : "06";
        $this->period = $this->session->userdata['period'];
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
        $enables = array('06');

        if (in_array($this->anexo, $enables))
            $customData['form_template'] = $this->parser->parse('reports/form_' . $anexo, $customData, true);
        else
            $customData['form_template'] = "";
         

        //$customData['sgr_options'] = $this->get_sgrs();
        /* POST */
        
        $process = $this->process_06();
        /*PRINT RESULT*/
        $customData['show_table'] = $this->$model->get_anexo_report($anexo, $process);


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

    /* ANEXOS */

    function AnexosDB() {
        $module_url = base_url() . 'sgr/reports/';
        $anexosArr = $this->sgr_model->get_anexos();
        $result = "";
        foreach ($anexosArr as $anexo) {
            $result .= '<li><a href=  "' . $module_url . 'anexo_code/' . $anexo['number'] . '"> ' . $anexo['title'] . ' <strong>[' . $anexo['short'] . ']</strong></a></li>';
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

    function Anexo_code($parameter) {
        /* BORRO SESSION RECTIFY */

        $this->session->unset_userdata('rectify');
        $this->session->unset_userdata('others');


        $newdata = array('anexo_code' => $parameter);
        $this->session->set_userdata($newdata);
        redirect('/sgr/reports');
    }

    /* SGRS */

    function get_sgrs() {
        $sgrArr = $this->sgr_model->get_sgrs();
        $rtn;

        foreach ($sgrArr as $sgr) {
            $this->sgr_id = (float) $sgr['id'];
            $this->sgr_nombre = $sgr['1693'];
            $this->sgr_cuit = $sgr['1695'];

            $rtn .= "<option value=" . $sgr['id'] . ">" . $sgr['1693'] . "</option>";
        }
        return $rtn;
    }

    /* PROCESS */

    function process_06() {
        $input_period_from = $this->translate_post('input_period_from');
        $input_period_to = $this->translate_post('input_period_to');
        $sgr = $this->translate_post('sgr');
        
        
        
        
        
    }

    function translate_post($input) {
        $return_input = ($this->input->post($input)) ? $this->input->post($input) : $_POST[$input];
        return $return_input;
    }

    /* RENDER */

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
        $cpData['isAdmin'] = $this->user->isAdmin($user);
        $cpData['username'] = strtoupper($user->lastname . ", " . $user->name);
        $cpData['usermail'] = $user->email;
// Profile 
//$cpData['profile_img'] = get_gravatar($user->email);

        $cpData['gravatar'] = (isset($user->avatar)) ? $this->base_url . $user->avatar : get_gravatar($user->email);
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
