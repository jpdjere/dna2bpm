<?php

// if (!defined('BASEPATH'))
//     exit('No direct script access allowed');
/**
 * Actualiza los archivos segun la rama configurada
 * 
 * @autor Fojo Gabriel 
 * 
 * @version 	1.0 
 * 
 * 
 */
 

 
class Formularios extends MX_Controller {

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
       $data['title']='Formulario de Preinscripción';
       $data['logobar']= $this->ui->render_logobar();
        
      echo $this->parser->parse('form_preinscripcion',$data,true,true);
    

    }
    
    
    function process(){
       $data=$this->input->post();

        $body="Programa Clubes de Emprendedores : formulario de preinscripción\n\n";
        foreach($data as $k=>$v){
           $clean[$k] = $this->security->xss_clean($k);
           $clean[$v] = $this->security->xss_clean($v);
           $body.="{$clean[$k]}: {$clean[$v]}<br>\n" ;
        }
        
        $mailer['body']=$body;
        $mailer['subject']='Programa Clubes de Emprendedores : formulario de preinscripción';
        $mailer['reply_email']='clubemprendedor@produccion.gob.ar';
        $mailer['reply_nicename']='Club de emprendedores';
        $mailer['to']=array('clubemprendedor@produccion.gob.ar'=>'clubemprendedor@produccion.gob.ar');
        $status=$this->msg->sendmail($mailer);
        
        
        echo json_encode(array('status'=>$status));

        
    }
    
    

    
}//class
