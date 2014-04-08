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


        $default_sgrs = '<option value="666">Todos</option>
                            <option value="2478671474">ACINDAR PYMES S.G.R.</option>
                            <option value="2175135318">AFFIDAVIT S.G.R.</option>
                            <option value="1106520165">AFIANZAR S.G.R.</option>
                            <option value="1476431157">AGROAVAL S.G.R.</option>
                            <option value="1607152997">AMERICANA DE AVALES S.G.R.</option>
                            <option value="3121601518">AVAL RURAL S.G.R.</option>
                            <option value="3653247007">AVALUAR S.G.R.</option>
                            <option value="3703508095">AZUL PYME S.G.R.</option>
                            <option value="2010246721">C.A.E.S. S.G.R.</option>
                            <option value="2257679366">CAMPO AVAL S.G.R.</option>
                            <option value="3790377123">CARDINAL S.G.R.</option>
                            <option value="4284790099">CONFIABLES S.G.R.</option>
                            <option value="2129915769">CUYO AVAL S.G.R.</option>
                            <option value="3303455306">DON MARIO S.G.R.</option>
                            <option value="2840662334">FIDUS S.G.R.</option>
                            <option value="1462524917">GARANTIA DE VALORES S.G.R.</option>
                            <option value="3826154295">GARANTIZAR S.G.R.</option>
                            <option value="3528267758">INTERGARANTIAS S.G.R.</option>
                            <option value="1285076677">LOS GROBO S.G.R.</option>
                            <option value="2519972722"> COMPANIA GENERAL DE AVALES S.G.R.</option>
                            <option value="3885670783">NORTE AVAL S.G.R.</option>
                            <option value="687239304">PROPyME S.G.R.</option>
                            <option value="2267515782">AVAL FEDERAL S.G.R.</option>
                            <option value="1270405713">SOL GARANTIAS S.G.R.</option>
                            <option value="702780368">SOLIDUM S.G.R.</option>
                            <option value="2207746538">VINCULOS S.G.R.</option>
                            <option value="1676213769">LA SOCIEDAD SGR</option>
                            <option value="3768366151">FONDO ESPECIFICO DE RIESGO FIDUCIARIO GARANTAXI I </option>
                            <option value="1045524969">FONDO ESPECIFICO DE RIESGO FIDUCIARIO CORPORACION BUENOS AIRES SUR </option>
                            <option value="1383403561">FRE FIDUCIARIO PARA GARANTIZAR PYMES NO SUJETAS DE CREDITO</option>
                            <option value="1186345001">FONDO ESPECIFICO DE RIESGO FIDUCIARIO PMSA I</option>
                            <option value="128688736">FONDO ESPECIFICO DE RIESGO FIDUCIARIO PROVINCIA DE CATAMARCA</option>
                            <option value="462574988">FONDO ESPECIFICO DE RIESGO FIDUCIARIO PROVINCIA DE SANTA CRUZ</option>
                            <option value="4061642435">FONDO ESPECIFICO DE RIESGO FIDUCIARIO PROVINCIA DE SANTA FE</option>
                            <option value="3755096283">FONDO ESPECIFICO DE RIESGO FIDUCIARIO SOCO RIL</option>
                            <option value="3945918291">FONDO ESPECIFICO DE RIESGO FIDUCIARIO YAGUAR</option>
                            <option value="2957316498">FOGABA - FONDO DE GARANTIAS DE BUENOS AIRES S.G.R.</option>
                            <option value="3624559275">PRODUCTOS HARMONY S.G.R. </option>
                            <option value="627335384">CONFEDERAR NEA S.G.R.</option>
                            <option value="2111570369">ALIANZA S.G.R. En Formacion</option>';


        $custom_sgr = ' <option value="' . $this->sgr_id . '">' . $this->sgr_nombre . '</option>';
        $customData['sgr_options'] = ($this->sgr_cuit) ? $custom_sgr : $default_sgrs; //$this->get_sgrs();
       

        /* RENDER */
        $this->render($default_dashboard, $customData);
    }

    function action_form() {
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

        $customData['form_template'] = $this->parser->parse('reports/form_' . $anexo . '_result', $customData, true);
        $customData['show_table'] = ($rtn_report) ? $rtn_report : "";
//
//        $fileName = "reporte_al_" . date("j-n-Y"); //Get today
//        //Generate  file
//        header("Content-Description: File Transfer");
//        header("Content-type: application/x-msexcel");
//        header("Content-Type: application/force-download");
//        header("Content-Disposition: attachment; filename=SGR_reporteAnexo06_" . $fileName . ".xls");
//        header("Content-Description: PHP Generated XLS Data");


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
         switch($anexo){
             case '06':
                 return $this->process_06($anexo);
                 break;
         }
     }

    function process_06($anexo) {

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

    /* ANEXOS FNs */

    function Anexo_code($parameter) {
        /* BORRO SESSION RECTIFY */

        $this->session->unset_userdata('rectify');
        $this->session->unset_userdata('others');


        $newdata = array('anexo_code' => $parameter);
        $this->session->set_userdata($newdata);
        redirect('sgr/reports/');
    }

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
                    $get_period = $this->sgr_model->get_period_info($this->anexo, $this->sgr_id, $period);
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
