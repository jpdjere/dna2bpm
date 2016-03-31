<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');



class Progress extends MX_Controller {

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
        $this->progress_dashboard();
    }
    
    function progress_dashboard(){
        Modules::run('dashboard/dashboard', 'progress/json/dashboard.json');
    }
    
    function bars($params){
        $default=array(
            'value'=> $params['value'],
            'label'=> $params['label']
            );
        $renderData=array_merge($default,$params);
        $renderData['class']=(isset($params['json_url'])) ? "bar json_bar":"bar";
   
        return $this->parser->parse('progress/widgets/progress-bars',$renderData,true);        
    }
    
     function data_test(){
        $data=array(
            'value'=> 32,
            'label'=> "Task Progress 1"
  
            );
        header('Content-type: application/json;charset=UTF-8');
        echo json_encode($data);
    }
        function data_test1(){
        $data=array(
            'value'=> 25,
            'label'=> "Task Progress 2"
            
            );
        header('Content-type: application/json;charset=UTF-8');
        echo json_encode($data);
    }
        function data_test2(){
        $data=array(
            'value'=> 50,
            'label'=> "Task Progress 3"
            
            );
        header('Content-type: application/json;charset=UTF-8');
        echo json_encode($data);
    }
        function data_test3(){
        $data=array(
            'value'=> 100,
            'label'=> "Task Progress 4"
            );
        header('Content-type: application/json;charset=UTF-8');
        echo json_encode($data);
    }
 function success_bar($params) {
    $params['update_class'] = 'success';
    return $this->bars($params);
 }
 function warning_bar($params) {
    $params['update_class'] = 'warning';
    return $this->bars($params);
 }
 function danger_bar($params){
    $params['update_class'] = 'danger'; 
    return $this->bars($params);
 }
 function info_bar($params) {
    $params['update_class'] = 'info';
    return $this->bars($params);
 }
 
 
 
 
 
 
 
 
 
 
    
}