<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * Actualiza los archivos segun la rama configurada
 * 
 * @autor Borda Juan Ignacio
 * 
 * @version 	1.13 (2012-06-14)
 * 
 * @file-salida   update-git.log
 * 
 */
class cd extends MX_Controller {

    function __construct() {
        parent::__construct();

        $this->user->authorize();
        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
        $this->load->library('parser');
        $this->idu = (int) $this->session->userdata('iduser');
        //---Output Profiler
        //$this->output->enable_profiler(TRUE);
    }
    
//    
    function Index(){
        $this->cd_dashboard();
    }

    function cd_dashboard(){
        Modules::run('dashboard/dashboard', 'cd/json/dashboard.json');
    }
    
    

    function form($template='edit001'){  
        $data=array();
        $data['module_url']=$this->module_url;
        echo $this->parser->parse("cd/$template",$data,true,true);

        
        
    }
    
    
    function create($template='view001'){
  
        $data['module_url']=$this->module_url;
        $data['remitente_nombre'] = $this->input->post('remitente_nombre');
        $data['remitente_domicilio'] = $this->input->post('remitente_domicilio');
        $data['remitente_cpa'] = $this->input->post('remitente_cpa');
        $data['remitente_localidad'] = $this->input->post('remitente_localidad');
        $data['remitente_provincia'] = $this->input->post('remitente_provincia');
        
        $data['destinatario_nombre'] = $this->input->post('destinatario_nombre');
        $data['destinatario_domicilio'] = $this->input->post('destinatario_domicilio');
        $data['destinatario_cpa'] = $this->input->post('destinatario_cpa');
        $data['destinatario_localidad'] = $this->input->post('destinatario_localidad');
        $data['destinatario_provincia'] = $this->input->post('destinatario_provincia');
        
        $data['cuerpo'] = $this->input->post('cuerpo');
        
        $this->load->library('pdf/pdf');
        $customData=array();
        $this->pdf->set_paper('legal', 'portrait');
        $this->pdf->parse("cd/$template", $data);
        $this->pdf->render();
        $this->pdf->stream("parameter.pdf");
        

       // echo $this->parser->parse("cd/$template",$data,true,true);

    }
    
    
    /**
     * Generar CD con datos pasados, si tiene path lo grabo y si no ->stream
     * @param string $template
     * @param array $data
     * @param string $path
     * 
     */ 

    function create_from_array($myparams=array(),$data=array()){
        $this->load->helper('file');
        //if(empty($data))die('No data');
        $default=array(
            'template'=>'view001',
            'filename'=>'',
            'path'=>'images'
        );

        $params=array_merge($default,$myparams);

        $data['module_url']=$this->base_url."cd/";
        $this->load->library('pdf/pdf');
        $this->pdf->set_paper('legal', 'portrait');
          
        $html = $this->parser->parse('cd/'.$params['template'], $data, TRUE);
        $this->pdf->load_html($html);
        $this->pdf->render();

        if(empty($params['filename'])){
          // $tmp=FCPATH.'images/temp.pdf';
            $this->pdf->stream('cd.pdf');
        }else{
          $path=$params['path'];
          $filename=$params['filename'];
          $pdf =  $this->pdf->output();
          @mkdir($path, 0777, true);
          if (!write_file($path.'/'.$filename, $pdf)) {
              die('Can not write to disk: '.$path.'/'.$filename);
              
          }
        }
    }
    

    
}