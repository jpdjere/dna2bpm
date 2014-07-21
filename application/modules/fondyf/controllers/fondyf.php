<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * fondyf
 * 
 * Description of the class fondyf
 * 
 * @author Juan Ignacio Borda <juanignacioborda@gmail.com>
 * @date   Jul 18, 2014
 */
class Fondyf extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('menu/menu_model');
        $this->user->authorize();
        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
        //----LOAD LANGUAGE
        $this->lang->load('library', $this->config->item('language'));
        $this->idu = (int) $this->session->userdata('iduser');
    }

    function Index() {
        $this->proyecto();
    }

    function Proyecto() {
        Modules::run('dashboard/dashboard', 'fondyf/json/fondyf_proyectos.json');
    }

    function Evaluador() {
        Modules::run('dashboard/dashboard', 'fondyf/json/fondyf_evaluador.json');
    }

    function Admin() {
        Modules::run('dashboard/dashboard', 'fondyf/json/fondyf_admin.json');
    }

    function tile_proyectos() {
        //----portable indicators are stored as json files
        $kpi = json_decode($this->load->view("fondyf/kpi/kpi_proyectos.json", '', true), true);
        echo Modules::run('bpm/kpi/tile_kpi', $kpi);
    }

}

/* End of file fondyf */
/* Location: ./system/application/controllers/welcome.php */