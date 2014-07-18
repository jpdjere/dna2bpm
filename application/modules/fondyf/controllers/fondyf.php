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
    
    function Proyecto(){
        Modules::run('dashboard/dashboard','fondyf_empresas');
    }
    
    function Evaluador(){
        
    }
    
    function Admin(){
        
    }
}

/* End of file fondyf */
/* Location: ./system/application/controllers/welcome.php */