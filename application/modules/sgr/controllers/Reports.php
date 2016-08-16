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

        /* habilita acceso a todo los metodos de este controlador */
        $this->user->authorize('modules/sgr/controllers/reports');
        $this->load->config('config');
        $this->load->library('parser');
        $this->load->library('ui');
        $this->load->model('app');
        $this->load->model('user/user');
        $this->load->model('bpm/bpm');
        $this->load->model('user/rbac');
        $this->load->model('sgr/sgr_model');
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
        $this->sgr_id  = null;
        $this->sgr_nombre = null;
        $this->sgr_cuit = null;

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
        set_time_limit(230400);
        ini_set("error_reporting", 0);

        if ($this->session->userdata('iduser') == 10){        
           # ini_set('display_errors', 1);
           # ini_set('display_startup_errors', 1);
           # error_reporting(E_ALL);
            ini_set("error_reporting", E_ALL);
        }
    }

    function Index() {
        /* load render info */
        $this->render_config();
    }

    /**
     * Config for index page
     * 
     * @name render_config
     * 
     * @author Diego Otero <daotero@industria.gob.ar>     
     * 
     * @see Index
     * 
     * @date Jun 28, 2015  
     * 
     * @return render   
     */
    function render_config() {

        $customData = array();
        if(isset($this->session->userdata['anexo_code'])){
            $anexo = $this->session->userdata['anexo_code'];
        } else {
            $anexo = '06';
        }

        $model = "model_" . $anexo;
        $this->load->model($model);


        /* CNV case */
        $cnv_model_arr = array("model_cnv_1", "model_cnv_2", "model_cnv_3", "model_cnv_4");
        if (in_array($model, $cnv_model_arr)) {
            $form_title = "N/D";
            $default_dashboard = 'reports_cnv';

            switch ($model) {
                case 'model_cnv_1':
                    $form_title = "INFORMACIÓN POR SGR – VARIABLES PRINCIPALES";
                    break;

                case 'model_cnv_2':
                    $form_title = "EVOLUCIÓN DE LAS PRINCIPALES VARIABLES";
                    break;

                case 'model_cnv_3':
                    $form_title = "DETALLE DE LAS INVERSIONES DEL FONDO DE RIESGO";
                    break;

                case 'model_cnv_4':
                    $form_title = "EVOLUCIÓN DE LAS PRINCIPALES VARIABLES – SALDOS PROMEDIOS MENSUALES";
                    break;
            }
            $customData['form_title'] = $form_title;
        } else {
            $default_dashboard = 'reports';
        }


        /* HEADERS */
        $header_merge = array_merge($customData, $this->headers());
        foreach ($header_merge as $key => $each) {
            $customData[$key] = $each;
        }


        if (!isset($this->sgr_id)) {
            $customData['is_admin'] = "1";
        }

        /* FORM TEMPLATE */
        $enables = array('06', '061', '062', '12', '125', '126', '13', '14', '141', '15', '16', '201', '202', 'cnv_1', 'cnv_2', 'cnv_3', 'cnv_4');

        if (in_array($this->anexo, $enables))
            $customData['form_template'] = $this->parser->parse('reports/form_' . $anexo, $customData, true);
        else
            $customData['form_template'] = "";


        $custom_sgr = ' <option value="' . $this->sgr_id . '">' . $this->sgr_nombre . '</option>';
        $customData['sgr_options'] = $this->get_sgrs_select();
        $customData['sgr_options_checklist'] = $this->get_sgrs_checkbox();        


        $customData['link_report'] = "";

        if ($this->sgr_model->last_report_general())
            $customData['link_report'] = link_report_fn();



        /* RENDER */
        $this->render($default_dashboard, $customData);
    }

    function is_report(){
         if ($this->sgr_model->last_report_general())
            
            echo json_encode(array('mensaje'=>'ok'));
    }

    function show_last_report() {

        $anexo = ($this->session->userdata['anexo_code']) ? : '06';
        $model = "model_" . $anexo;

        $this->load->model($model);
        header('Content-type: text/html; charset=UTF-8');

        $customData = $this->$model->get_link_report($anexo);



        $fileName = $anexo . "_reporte_al_" . date("j-n-Y"); //Get today
        //Generate  file
        $filename = "informe_" . $anexo . "_" . date("j-n-Y") . ".xls";
        header("Content-Description: File Transfer");
        header("Content-Type: application/x-msexcel");
        header("Content-Type: application/force-download");
        header("Content-Disposition: attachment; filename=" . $filename);
        header("Content-Description: PHP Generated XLSx Data");

        print_r(utf8_decode($customData));
    }

    function action_form_cnv() {

        $customData = array();


        $default_dashboard = 'form_result_cnv';

        /* HEADERS */
        header('Content-type: text/html; charset=UTF-8');
        $header_merge = array_merge($customData, $this->headers());
        foreach ($header_merge as $key => $each) {
            $customData[$key] = $each;
        }
        $customData['form_template'] = $this->parser->parse('reports/form_result_cnv', $customData, true);

        foreach ($this->process() as $key => $value) {
            $customData[$key] = ($value)? : "";
        }

        $this->load->model('sgr/sgr_model');
        //var_dump($this->model_sgr->get_sgr_by_id();
        $sgr_id = (float) $this->input->post('sgr');
        $sgr_data = $this->sgr_model->get_sgr_by_id_new($sgr_id);

        $customData['sgr_nombre'] = $sgr_data[1693];
        switch ($customData['anexo']) {
            case 'cnv_1':
                if ($this->input->post('pdf') == 1) {
                    $this->load->library('pdf/pdf');

                    $this->pdf->set_paper('a4', 'portrait');
                    $this->pdf->parse($default_dashboard, $customData);
                    $this->pdf->render();
                    $this->pdf->stream("informe_cnv_" . date("j-n-Y") . ".pdf");
                }


                $this->render($default_dashboard, $customData);

                break;

            default:
                $fileName = $customData['anexo'] . "_reporte_al_" . date("j-n-Y"); //Get today
                //Generate  file
                header("Content-Description: File Transfer");
                header("Content-Type: application/x-msexcel");
                header("Content-Type: application/force-download");
                header("Content-Disposition: attachment; filename=informe_" . $customData['anexo'] . "_" . date("j-n-Y") . ".xls");
                header("Content-Description: PHP Generated XLSx Data");
                /* RENDER */
                $this->render($default_dashboard, $customData);
                break;
        }
    }

    function action_form() {

        $customData = array();
        $default_dashboard = 'reports_result';
        $anexo = ($this->session->userdata['anexo_code']) ? : '06';

        $model = "model_" . $anexo;
        $this->load->model($model);

        /* HEADERS */
        $header_merge = array_merge($customData, $this->headers());
        foreach ($header_merge as $key => $each) {
            $customData[$key] = $each;
        }


        $rtn_report = $this->process();

        $fileName = "reporte_al_" . date("j-n-Y"); //Get today

        $customData['form_template'] = $this->parser->parse('reports/form_result', $customData, true);       
        $customData['show_table'] = ($rtn_report) ? html_entity_decode($rtn_report) : "";

        header('Content-type: text/html; charset=UTF-8');
        if ($this->idu == 11) {
            //var_dump($customData);           exit;
            $this->render($default_dashboard, $customData);
        } else {
            $fileName = $anexo . "_al_" . date("j-n-Y"); //Get today
            //Generate  file
            header("Content-Description: File Transfer");
            header("Content-Type: application/x-msexcel");
            header("Content-Type: application/force-download");
            header("Content-Disposition: attachment; filename=SGR_reporteAnexo" . $fileName . ".xls");
            header("Content-Description: PHP Generated XLSx Data");
        }

        /* RENDER */
        $this->render($default_dashboard, $customData);
    }


    /* ASSETS HEADERS */

    function headers() {
        $rtn = array();

        $rtn['sgr_nombre'] = (isset($this->sgr_nombre)) ? $this->sgr_nombre : null;
        $rtn['sgr_id'] = $this->sgr_id;
        $rtn['sgr_id_encode'] = base64_encode($this->sgr_id);
        $rtn['base_url'] = base_url();
        $rtn['module_url'] = base_url() . 'sgr/';
        $rtn['titulo'] = "";
        $rtn['js'] = array($this->module_url . "assets/jscript/form_reports.js" => 'Report JS', $this->module_url . "assets/jscript/jquery-validate/jquery.validate.min_1.js" => 'Validate');
        $rtn['css'] = array($this->module_url . "assets/css/dashboard.css" => 'Report CSS');

        $rtn['anexo'] = $this->anexo;
        $rtn['anexo_title'] = $this->oneAnexoDB($this->anexo);
        $rtn['anexo_title_cap'] = strtoupper($this->oneAnexoDB($this->anexo));
        $rtn['anexo_list'] = $this->AnexosDB();
        $rtn['anexo_short'] = $this->oneAnexoDB_short($this->anexo);

        return $rtn;
    }

    /* PROCESS */

    function process() {

        $anexo = $this->input->post('anexo');
        switch ($anexo) {           

            case '061':
                return $this->process_061($anexo);
                break;

            case '062':
                return $this->process_062($anexo);
                break;

            case '12':
                return $this->process_12($anexo);
                break;

            case '13':
                return $this->process_13($anexo);
                break;

            case '125':
                return $this->process_125($anexo);
                break;
                
             case '126':
                return $this->process_125($anexo);
                break;    

            case '14':
                return $this->process_14($anexo);
                break;           

            case '15':
                return $this->process_15($anexo);
                break;

            case '16':
                return $this->process_15($anexo);
                break;

            case '201':
                return $this->process_201($anexo);
                break;

            case '202':
                return $this->process_202($anexo);
                break;

            /* CNV cases */
            case 'cnv_1':

                $rtn_array = array();
                $rtn_array['show_table'] = $this->process_cnv_1_1($anexo);
                $rtn_array['show_table_1'] = $this->process_cnv_1($anexo);
                $rtn_array['show_table_2'] = $this->process_cnv_1_2($anexo);
                $rtn_array['show_table_3'] = $this->process_cnv_1_3($anexo);
                return $rtn_array;
                break;

            case 'cnv_2':

                $rtn_array = array();
                $rtn_array['show_table'] = $this->process_cnv_2($anexo);
                $rtn_array['show_table_1'] = null;
                $rtn_array['show_table_2'] = null;
                $rtn_array['show_table_3'] = null;
                return $rtn_array;
                break;



            case 'cnv_3':

                $rtn_array = array();
                $rtn_array['show_table'] = $this->process_cnv_3($anexo);
                $rtn_array['show_table_1'] = null;
                $rtn_array['show_table_2'] = null;
                $rtn_array['show_table_3'] = null;
                return $rtn_array;

                break;
        }
    }

    

    function process_061($anexo) {

        $rtn = array();
        $report_name = $this->input->post('report_name');


        $rtn['input_period_from'] = ($this->input->post('input_period_from')) ? : '01-1990';
        $rtn['input_period_to'] = ($this->input->post('input_period_to')) ? : '01-2020';


        $rtn['sgr_id'] = $this->input->post('sgr');
        if ($this->input->post('sgr')) {
            $model = "model_" . $anexo;
            $this->load->model($model);

            $result = $this->$model->get_anexo_report($anexo, $rtn);

            return $result;
        }
    }

    function process_062($anexo) {

        $rtn = array();
        $report_name = $this->input->post('report_name');


        $rtn['input_period_from'] = ($this->input->post('input_period_from')) ? : '01-1990';
        $rtn['input_period_to'] = ($this->input->post('input_period_to')) ? : '01-2020';

        if ($this->input->post('cuit_socio'))
            $rtn['cuit_socio'] = $this->input->post('cuit_socio');

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

        if ($this->input->post('warranty_type'))
            $rtn['warranty_type'] = $this->input->post('warranty_type');

        if ($this->input->post('custom_report'))
            $rtn['custom_report'] = $this->input->post('custom_report');

        $rtn['input_period_from'] = ($this->input->post('input_period_from')) ? : '01-1990';
        $rtn['input_period_to'] = ($this->input->post('input_period_to')) ? : '01-2020';
        $rtn['sgr_id'] = $this->input->post('sgr');
        if ($this->input->post('sgr')) {
            $model = "model_" . $anexo;
            $this->load->model($model);

            $result = $this->$model->get_anexo_report($anexo, $rtn);

            return $result;
        }
    }

    function process_125($anexo) {

        $rtn = array();
        $report_name = $this->input->post('report_name');
        $rtn['input_period_from'] = ($this->input->post('input_period_from')) ? : '01-1990';
        $rtn['input_period_to'] = ($this->input->post('input_period_to')) ? : '01-2020';

        if ($this->input->post('cuit_socio'))
            $rtn['cuit_socio'] = $this->input->post('cuit_socio');

        $rtn['sgr_id'] = $this->input->post('sgr');
        if ($this->input->post('sgr')) {
            $model = "model_" . $anexo;
            $this->load->model($model);
            $result = $this->$model->get_anexo_report($anexo, $rtn);
            return $result;
        }
    }
    
    
    function process_126($anexo) {

        $rtn = array();
        $report_name = $this->input->post('report_name');
        $rtn['input_period_from'] = ($this->input->post('input_period_from')) ? : '01-1990';
        $rtn['input_period_to'] = ($this->input->post('input_period_to')) ? : '01-2020';

      

        $rtn['sgr_id'] = $this->input->post('sgr');
        if ($this->input->post('sgr')) {
            $model = "model_" . $anexo;
            $this->load->model($model);
            $result = $this->$model->get_anexo_report($anexo, $rtn);
            return $result;
        }
    }

    function process_13($anexo) {

        $rtn = array();
        $report_name = $this->input->post('report_name');


        $rtn['input_period_from'] = ($this->input->post('input_period_from')) ? : '01-1990';
        $rtn['input_period_to'] = ($this->input->post('input_period_to')) ? : '01-2020';


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


        $rtn['input_period_from'] = ($this->input->post('input_period_from')) ? : '01-1990';
        $rtn['input_period_to'] = ($this->input->post('input_period_to')) ? : '01-2020';

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
    

    function process_15($anexo) {

        $rtn = array();
        $report_name = $this->input->post('report_name');


        $rtn['input_period_from'] = ($this->input->post('input_period_from')) ? : '01-1990';
        $rtn['input_period_to'] = ($this->input->post('input_period_to')) ? : '01-2020';
        $rtn['sgr_id'] = $this->input->post('sgr');
        if ($this->input->post('sgr')) {
            $model = "model_" . $anexo;
            $this->load->model($model);

            $result = $this->$model->get_anexo_report($anexo, $rtn);

            return $result;
        }
    }

    function process_16($anexo) {

        $rtn = array();
        $report_name = $this->input->post('report_name');


        $rtn['input_period_from'] = ($this->input->post('input_period_from')) ? : '01-1990';
        $rtn['input_period_to'] = ($this->input->post('input_period_to')) ? : '01-2020';


        $rtn['sgr_id'] = $this->input->post('sgr');
        if ($this->input->post('sgr')) {
            $model = "model_" . $anexo;
            $this->load->model($model);

            $result = $this->$model->get_anexo_report($anexo, $rtn);

            return $result;
        }
    }

    function process_201($anexo) {

        $rtn = array();
        $report_name = $this->input->post('report_name');


        $rtn['input_period_from'] = ($this->input->post('input_period_from')) ? : '01-1990';
        $rtn['input_period_to'] = ($this->input->post('input_period_to')) ? : '01-2020';
        $rtn['sgr_id'] = $this->input->post('sgr');
        if ($this->input->post('sgr')) {
            $model = "model_" . $anexo;
            $this->load->model($model);


            $result = $this->$model->get_anexo_report($anexo, $rtn);



            return $result;
        }
    }

    function process_202($anexo) {

        $rtn = array();
        $report_name = $this->input->post('report_name');


        $rtn['input_period_from'] = ($this->input->post('input_period_from')) ? : '01-1990';
        $rtn['input_period_to'] = ($this->input->post('input_period_to')) ? : '01-2020';

        if ($this->input->post('cuit_socio'))
            $rtn['cuit_socio'] = $this->input->post('cuit_socio');




        $rtn['sgr_id'] = $this->input->post('sgr');
        if ($this->input->post('sgr')) {
            $model = "model_" . $anexo;
            $this->load->model($model);

            $result = $this->$model->get_anexo_report($anexo, $rtn);

            return $result;
        }
    }

    /* CNV */

    function process_cnv_1($anexo) {

        $rtn = array();
        $report_name = $this->input->post('report_name');


        $rtn['input_period_from'] = ($this->input->post('input_period_from')) ? : '01-1990';
        $rtn['sgr_id'] = $this->input->post('sgr');
        if ($this->input->post('sgr')) {
            $model = "model_" . $anexo;
            $this->load->model($model);

            $result = $this->$model->get_anexo_report($anexo, $rtn, "default");
            return $result;
        }
    }

    function process_cnv_1_1($anexo) {

        $rtn = array();
        $report_name = $this->input->post('report_name');


        $rtn['input_period_from'] = ($this->input->post('input_period_from')) ? : '01-1990';
        $rtn['sgr_id'] = $this->input->post('sgr');
        if ($this->input->post('sgr')) {
            $model = "model_" . $anexo;
            $this->load->model($model);

            $result = $this->$model->get_anexo_report($anexo, $rtn, '1');
            return $result;
        }
    }

    function process_cnv_1_2($anexo) {

        $rtn = array();
        $report_name = $this->input->post('report_name');


        $rtn['input_period_from'] = ($this->input->post('input_period_from')) ? : '01-1990';
        $rtn['sgr_id'] = $this->input->post('sgr');
        if ($this->input->post('sgr')) {
            $model = "model_" . $anexo;
            $this->load->model($model);

            $result = $this->$model->get_anexo_report($anexo, $rtn, '2');
            return $result;
        }
    }

    function process_cnv_1_3($anexo) {

        $rtn = array();
        $report_name = $this->input->post('report_name');


        $rtn['input_period_from'] = ($this->input->post('input_period_from')) ? : '01-1990';
        $rtn['sgr_id'] = $this->input->post('sgr');
        if ($this->input->post('sgr')) {
            $model = "model_" . $anexo;
            $this->load->model($model);

            $result = $this->$model->get_anexo_report($anexo, $rtn, '3');
            return $result;
        }
    }

    function process_cnv_2($anexo) {

        $rtn = array();
        $report_name = $this->input->post('report_name');


        $rtn['input_period_from'] = ($this->input->post('input_period_from')) ? : '01-1990';
        $rtn['input_period_to'] = ($this->input->post('input_period_to')) ? : '01-2020';
        $rtn['sgr_id'] = $this->input->post('sgr');
        if ($this->input->post('sgr')) {
            $model = "model_" . $anexo;
            $this->load->model($model);

            $result = $this->$model->get_anexo_report($anexo, $rtn);
            return $result;
        }
    }

    function process_cnv_3($anexo) {

        $rtn = array();
        $report_name = $this->input->post('report_name');


        $rtn['input_period_from'] = ($this->input->post('input_period_from')) ? : '01-1990';
        $rtn['input_period_to'] = ($this->input->post('input_period_to')) ? : '01-2020';
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

    function get_sgrs_select() {

        if (isset($this->sgr_id)) {
            $rtn = "<option value=" . $this->sgr_id . ">" . $this->sgr_nombre . "</option>";
        } else {
            $sgrArr = $this->sgr_model->get_sgrs();
            $rtn = "<option value=666>TODAS LAS SGRs</option>";

            /*ONLY ADMINS*/
            if (!isset($this->sgr_id)) 
                $rtn .= "<option value=777>SELECCIONAR DE LA LISTA</option>";

            foreach ($sgrArr as $sgr) {
                
                $this->sgr_id = (float) $sgr['id'];
                $this->sgr_nombre = $sgr['1693'];
                $this->sgr_cuit = $sgr['1695'];
                
                $rtn .= "<option value=" . $sgr['id'] . ">" . $sgr['1693'] . "</option>";
              
            }
        }
        return $rtn;
    }

    function get_sgrs_checkbox() {
        $sgrArr = $this->sgr_model->get_sgrs();
            $rtn = null;
            foreach ($sgrArr as $sgr) {

                $sgr_names = str_replace('FONDO ESPECIFICO DE RIESGO', 'FRE', $sgr['1693']);

               if (strpos($sgr_names, 'FRE') === false) #SACO LOS FDRE
                    $rtn .= '<div id="checklist_sgr">' . $sgr_names. ' <input type="checkbox" value="'.(float)$sgr['id'].'" checked="checked" name="sgr_checkbox[]"></div>';
            }
        
        return $rtn;
    }

    /* ANEXOS FNs */

    function Anexo_code($parameter) {
        /* BORRO SESSION RECTIFY */


        $this->sgr_model->del_tmp_general();

        $this->session->unset_userdata('rectify');
        $this->session->unset_userdata('others');

        $newdata = array('anexo_code' => $parameter);
        $this->session->set_userdata($newdata);
        redirect('sgr/reports/');
    }

    function Cnv_code($parameter) {
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
        $user_lastname = isset($user->lastname) ? $user->lastname : null;
        $user_name = isset($user->name) ? $user->name : null;
        $user_email = isset($user->email) ? $user->email : null;

        $cpData['username'] = strtoupper($user_lastname . ", " . $user_name);
        $cpData['usermail'] = $user_email;
        $cpData['rol'] = "Usuarios";
        $cpData['rol_icono'] = ($cpData['rol'] == 'coordinador') ? ('icon-group') : ('icon-user');

        $cpData = array_replace_recursive($customData, $cpData);



// offline mark
        $cpData['is_offline'] = ($this->uri->segment(3) == 'offline') ? : ('');

        $this->ui->compose($file, 'reports.php', $cpData);
    }

    /*NEW REPORT*/
    function new_report($anexo='06'){

        
        /*LOAD MODEL*/
        $model = "model_" . $this->anexo;
        $this->load->model($model);

        /*VIEW*/
        $default_dashboard = 'reports_result'; 


        $data = array();
        $customData = array();

        $report_name = $this->input->post('report_name');
        $data['input_period_from'] = ($this->input->post('input_period_from')) ? : '01-1990';
        $data['input_period_to'] = ($this->input->post('input_period_to')) ? : '01-2020';

        if ($this->input->post('cuit_socio'))
            $data['cuit_socio'] = $this->input->post('cuit_socio');        

        if($this->input->post('sgr_checkbox'))
            $data['sgr_id_array'] = array_map('intval', $this->input->post('sgr_checkbox'));
        
        $data['sgr_id'] = $this->input->post('sgr');

        /*CALL MODEL*/
        if ($this->input->post('sgr')) {
           $this->$model->generate_report($data);  
        }


    }

}
