<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');



class Ranking extends MX_Controller {

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
        $this->rankings_dashboard();
    }
    
    function rankings_dashboard(){
        Modules::run('dashboard/dashboard', 'ranking/json/dashboard.json');
    }
    
        function ranking($params){
        
        $this->load->module('dashboard');
        $template="dashboard/widgets/box_info.php";
        $data=array();
        $data['title']='Ranking';
        $data['module_url'] = $this->module_url;
        $data['content']=$this->parser->parse('ranking/widgets/ranking',$data,true,true);
        return $this->dashboard->widget($template, $data);
    }
    
}