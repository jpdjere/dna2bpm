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
        
         /* LOAD MODEL */
        $this->load->model('forms_model');
        
        //d$this->user->authorize();
        //---Output Profiler
        //$this->output->enable_profiler(TRUE);
        //error_reporting(E_ALL);

    }
    
    function Index(){
       $data['base_url']=$this->base_url;
       $data['title']='SOLICITUD DE ANTICIPO DE VIATICOS Y ORDENES DE PASAJE';
       $data['logobar']= $this->ui->render_logobar();
        
        
       /*Agentes*/
        $data_select = NULL;
        $agents_data = $this->forms_model->buscar_agentes_registrados();
        
        foreach ($agents_data as $each) {
           $data_select .= '<option value='.$each['dni'].'>'.$each['apellido'].' '.$each['nombre'].' </option>';
            
        }
        
        $data['groupagents'] = $data_select;
        
        
      echo $this->parser->parse('form_viaticos',$data,true,true);
    

    }
    
    
    
    //=== Create  buttons groups on ajax call
    
    function get_option_button(){
     $sel=$this->input->post('sel');
     
     $ret = NULL;
     $groups = $this->forms_model->buscar_agentes_registrados();
              
     if($sel=='all'){
         foreach($groups as $g){
              $ret.= "<button type='button' data-groupid='{$g['dni']}' class='btn btn-default btn-xs'><i class='fa fa-times-circle'></i> {$g['nombre']}</button>";
         }
     }else{
         // just one
          foreach($groups as $g){
              if($g['dni']==$sel){
              $ret.= "<button type='button' data-groupid='{$g['dni']}' class='btn btn-default btn-xs'><i class='fa fa-times-circle'></i> {$g['nombre'] } {$g['apellido'] }</button>";
              break;
              }
          }
     }
     echo $ret;
    }
    
    
    
    function process(){
        
         var_dump($this->input->post());
         echo json_encode(array('status'=>$status));
         exit();
         $data=$this->input->post();
        
        

        
    }
    
    

    
}//class
