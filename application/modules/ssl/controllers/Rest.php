<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * Actualiza los archivos segun la rama configurada
 * 
 * @autor Fojo Gabriel 
 * 
 * @version 	1.0 
 * 
 * 
 */
 

 
class Rest extends MX_Controller {

    function __construct() {
        parent::__construct();

        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
        $this->load->library('parser');
        $this->load->library('dashboard/ui');
        
        $this->idu = (int) $this->session->userdata('iduser');
        $this->load->model('ssl/ssl_model');

        //d$this->user->authorize();
        //---Output Profiler
        //$this->output->enable_profiler(TRUE);
        //error_reporting(E_ALL);

    }
    
    function Index(){
        

    }
    


    function init(){

        $mypost=$this->input->post('fingerprint');
        $fingerprint=trim($mypost);
        $res=$this->ssl_model->get_key($fingerprint);
         
         if(empty($res)){
             //== Error
             $response['status']=false;
             $response['msg']='Bad fingerprint';
         }else{
             $response['status']=true;
             $response['simetric_key']='---';
         }
         
         $key="123123";
         
         $resp=Modules::run('ssl/encrypt_simetric', 'texto',$key);
        // echo json_encode($resp);
         echo base64_encode($resp);
         //$res->public_key
         

    }
    
}//class
