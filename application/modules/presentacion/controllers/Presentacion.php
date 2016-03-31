<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class Presentacion extends MX_Controller {

    function __construct() {
        parent::__construct();
        //$this->user->authorize();
        $this->load->library('parser');
        $parts=$this->uri->segments;
        unset($parts[1]);
        
        //---base variables
        $data['base_url'] = base_url();
        $data['module_url'] = base_url() . $this->router->fetch_module() . '/';
        //---get working directory and map it to your module
        //$data['user']=$this->user->get_user();
        $data['slides'] = $this->parser->parse(implode('/',$parts),$data,false,true);
        ;
        //----get path parts form extension
        // $path_parts = pathinfo($file);
        //var_dump($parts,$data);
        
        echo $this->parser->parse('presentacion/template',$data);
        exit;
    }

    function index() {
        $this->fdefault();
    }

    function fdefault(){
        $this->load->view('default');
    }

}

?>
