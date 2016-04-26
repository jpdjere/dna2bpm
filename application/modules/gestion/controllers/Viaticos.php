<?php

// if (!defined('BASEPATH'))
//     exit('No direct script access allowed');
/**
 * Actualiza los archivos segun la rama configurada
 * 
 * @autor Diego Otero
 * 
 * @version 	1.0 
 * 
 * 
 */
 

 
class Viaticos extends MX_Controller {

    function __construct() {
        parent::__construct();

        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
        $this->load->library('parser');
        $this->load->library('dashboard/ui');
        
        $this->idu = (int) $this->session->userdata('iduser');
        $this->load->model('ssl/ssl_model');
        $this->load->model('msg');
        $this->load->library('phpmailer/phpmailer');
        //d$this->user->authorize();
        //---Output Profiler
        //$this->output->enable_profiler(TRUE);
        //error_reporting(E_ALL);

    }
    
    function Index(){
       $data['base_url']=$this->base_url;
       $data['title']='SOLICITUD DE ANTICIPO DE VIATICOS Y ORDENES DE PASAJE';
       $data['logobar']= $this->ui->render_logobar();
        
      echo $this->parser->parse('form_viaticos',$data,true,true);
    

    }
    
    
    function process(){
        
        var_dump($this->input->post());
        exit();
       $data=$this->input->post();

        
        
        echo json_encode(array('status'=>$status));

        
    }
    
    

    
}//class
