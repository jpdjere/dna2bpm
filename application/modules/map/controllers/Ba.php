<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * map
 * 
 * This class provides map services an geolocation
 * 
 * @author Juan Ignacio Borda <juanignacioborda@gmail.com>
 * @date   Jun 6, 2013
 */
class Ba extends MX_Controller {

    function __construct() {
        
       
        
        parent::__construct();
        $this->base_url = base_url();
        $this->module_url = base_url() . 'map/';
        $this->load->library('parser');
        
  
    }

    function Index(){
        
        $this->map_dashboard();
    }
    
    function map_dashboard(){
        
        Modules::run('dashboard/dashboard', 'map/json/dashboard-ba.json');
        
    }
    
    function map(){
        
        $this->load->module('dashboard');
        $renderData['title'] = "Mapa De Buenos Aires";
        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        $template="dashboard/widgets/box_info.php";
        $renderData['content']= $this->parser->parse('map-svg-bs', $renderData, true, true);
        
        return $this->dashboard->widget($template, $renderData);
        
    }
    
}

