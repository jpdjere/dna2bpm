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
 

 
class Eventanilla extends MX_Controller {

    function __construct() {
        parent::__construct();

        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
        
        #LIBRARIES
        $this->load->library('parser');
        $this->load->library('dashboard/ui');
        
        
        #MODELS
        $this->load->model('afip/eventanilla_model');
        
        
        #CREDENTIALS
        $this->idu = (int) $this->session->userdata('iduser');
        $this->user->authorize();
        
        //---Output Profiler
        //$this->output->enable_profiler(TRUE);
        //error_reporting(E_ALL);
        
       

    }
    
    function Index(){
        $process = $this->process();
        echo $process;
    }
    
    
    function process(){
        
        $test = $this->eventanilla_model->buscar_registros();
        var_dump($test);
    }
    
    

    
}//class
