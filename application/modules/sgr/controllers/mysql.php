<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * sgr
 *
 */
class Mysql extends MX_Controller {

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
        $this->load->model('sgr/mysql_model');
        $this->load->helper('sgr/tools');
        $this->load->library('session');

        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . 'sgr/';
        //----LOAD LANGUAGE
        $this->lang->load('library', $this->config->item('language'));


        // IDU : Chequeo de sesion
        $this->idu = (int) $this->session->userdata('iduser');
        if (!$this->idu) {
            header("$this->module_url/user/logout");
            exit();
        }

        /* DATOS SGR */
        $sgrArr = $this->sgr_model->get_sgr();
        foreach ($sgrArr as $sgr) {
            $this->sgr_id = $sgr['id'];
            $this->sgr_nombre = $sgr['1693'];
            $this->sgr_cuit = $sgr['1695'];
        }


        $this->anexo = ($this->session->userdata['anexo_code']) ? $this->session->userdata['anexo_code'] : "06";
        $this->period = $this->session->userdata['period'];
    }

    function Index() {
        //
    }

    function update_periods() {

        $anexo = '061';
        $mysql_model = "mysql_model_periods";
        $this->load->Model($mysql_model);

        $result = $this->$mysql_model->active_periods_dna2($anexo, $this->period);
        debug($result);



        /*
          INSERT INTO export_sgr_fdr_contingente (filename)
          SELECT D2.filename
          FROM sgr_fdr_contingente D2 LEFT JOIN sgr_control_periodos D1 ON D2.filename = D1.archivo
          WHERE D1.id IS NULL
         */

        //$result = $this->mysql_model->active_periods_dna2();
        debug($result);
    }

    function Dollar() {

        $mysql_model = "mysql_model_dollar";
        $this->load->Model($mysql_model);
        $result = $this->$mysql_model->active_periods_dna2();
        debug($result);
    }

    function Anexo06() {

        $anexo = '06';
        $mysql_model = "mysql_model_" . $anexo;
        $this->load->Model($mysql_model);

        $result = $this->$mysql_model->active_periods_dna2($anexo, $this->period);
        debug($result);
    }

    function Anexo062() {

        $anexo = '062';
        $mysql_model = "mysql_model_" . $anexo;
        $this->load->Model($mysql_model);

        $result = $this->$mysql_model->active_periods_dna2($anexo, $this->period);
        debug($result);
    }

    function Anexo09() {

        $anexo = '09';
        $mysql_model = "mysql_model_" . $anexo;
        $this->load->Model($mysql_model);

        $result = $this->$mysql_model->active_periods_dna2($anexo, $this->period);
        debug($result);
    }

    function Anexo12() {

        $anexo = '12';
        $mysql_model = "mysql_model_" . $anexo;
        $this->load->Model($mysql_model);

        $result = $this->$mysql_model->active_periods_dna2($anexo, $this->period);
        //$result = $this->$mysql_model->update();
        debug($result);
    }

    function Anexo14() {

        $anexo = '14';
        $mysql_model = "mysql_model_" . $anexo;
        $this->load->Model($mysql_model);

        $result = $this->$mysql_model->active_periods_dna2($anexo, $this->period);
        debug($result);
    }

    function Anexo201() {

        $anexo = '201';
        $mysql_model = "mysql_model_" . $anexo;
        $this->load->Model($mysql_model);

        $result = $this->$mysql_model->active_periods_dna2($anexo, $this->period);
        debug($result);
    }

    function Periodos() {
        /* UPDATE DB FORMS2 PERIODOS */
        //$result = $this->mysql_model->active_periods_dna2($this->anexo, $this->period);
        //debug($result);
    }

}
