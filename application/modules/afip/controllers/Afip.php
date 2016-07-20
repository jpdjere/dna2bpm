<?php

// if (!defined('BASEPATH'))
//     exit('No direct script access allowed');
/**
 * "ventanilla electrónica" de la AFIP
 * 
 * @autor Diego Otero
 * 
 * @version 	1.0 
 * 
 * 
 */
 
class Afip extends MX_Controller {    
    function __construct() {
        parent::__construct();

        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';

        
        #LIBRARIES          
        $this->load->library('parser');
        $this->load->library('dashboard/ui');        
        
        
        #CREDENTIALS
        $this->idu = (int) $this->session->userdata('iduser');
        $this->user->authorize();
        
     

    }
    
    function Index() {
        $data['base_url'] = $this->base_url;
        $data['title'] = 'DASHBOARD AFIP';
        $data['logobar'] = $this->ui->render_logobar();
        echo $this->parser->parse('index', $data, true, true);
    }
    /**
     * Dashboard para Admins
     */
    function dashboard($debug=false){
        Modules::run('dashboard/dashboard', 'afip/dashboards/dashboard.json',$debug);

    }
    function status(){
        /**
         * moved to /api/status
         */
    }
    
    

    
}//class
