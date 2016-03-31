<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');



class Timeline extends MX_Controller {

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
    
    function Index(){
        $this->timelines_dashboard();
    }
    
    function timelines_dashboard(){
        Modules::run('dashboard/dashboard', 'timeline/json/dashboard.json');
    }
    
        function data_test(){
            
        $data = array();    
            
        $data[0]=array(
            'event_title' => "Tarea1",
            'icon' => "fa-clock-o",
            'content'=> "Esto es el contenido de tareas",
            'background' =>"bg-red"
            );
            
        $data[1]=array(
            'event_title' => "Tarea2",
            'icon' => "fa-clock-o",
            'content'=> "Esto es el contenido de tareas",
            'background' =>"bg-red"
            );
            
        $data[2]=array(
            'event_title' => "Tarea3",
            'icon' => "fa-clock-o",
            'content'=> "Esto es el contenido de tareas",
            'background' =>"bg-red"
            );
            
        header('Content-type: application/json;charset=UTF-8');
        echo json_encode($data);
    }
    
    function timeline($params){
        
        $this->load->module('dashboard');
        $template="dashboard/widgets/box_info.php";
        $data=array();
        $data['json_url'] = $this->base_url.'timeline/data_test';
        $data['title']= 'Título Timeline';
  
        for ( $i= 0; $i<count($params); $i++){
            
            $data['events'][$i] = $params[$i];
            
        }
       
        $data['class']=(isset($params['json_url'])) ? "tl json_tl":"tl";
        
        return $this->parser->parse('timeline/widgets/time-lines',$data,true,true);
       // return $this->dashboard->widget($template, $data);
    }
    
}