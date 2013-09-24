<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Update_metas extends MX_Controller {

    function __construct() {
        parent::__construct();
        //----habilita acceso a todo los metodos de este controlador        
        @$this->load->config('config');
        @$this->load->library('parser');
        @$this->load->library('ui');
        @$this->load->model('app');
        @$this->load->model('user/user');
        @$this->load->model('bpm/bpm');
        @$this->load->model('user/rbac');
        @$this->load->model('genias/genias_model');
        @$this->load->helper('genias/tools');

        //---base variables
        @$this->base_url = base_url();
        @$this->module_url = base_url() . 'genias/';
        //----LOAD LANGUAGE
        @$this->lang->load('library', $this->config->item('language'));
        @$this->idu = (int) $this->session->userdata('iduser');
        @$this->containerGenias = 'container.genias_visitas';
    }

    /*
     * Update GOALS
     */

    public function index() {

        $container = $this->containerGenias;
        $query = array('idu' => (int) ($this->idu));
        $result = $this->genias_model->get_visitas_all();
        foreach ($result as $returnData) {
            $id = $returnData['id'];
            $idu = $returnData['idu'];
            $fecha = $returnData['fecha'];
            $newResult = $this->genias_model->goal_update_all('2', $id, $idu, $fecha);
            if (!empty($newResult)) {
                echo "<pre>";
                var_dump($newResult);
                echo "</pre>";
            }
        }
        exit();
    }

}