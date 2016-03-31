<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');



class Table extends MX_Controller {

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
        $this->dashboard();
    }
    
    function dashboard(){
        Modules::run('dashboard/dashboard', 'table/json/dashboard.json');
    }
    function dashboard2(){
        Modules::run('dashboard/dashboard', 'table/json/dashboard2.json');
    }
    
    function table(){
    
        $this->load->module('dashboard');
    
        $template="table/widgets/table-widget.php";
        $data=array();
        $data['table_id']='table_'.md5(microtime());
        $data['content'] = $this->parser->parse('table/widgets/table',$data,true,true);
        return $this->dashboard->widget($template, $data);
   
    }
    
 
    
}