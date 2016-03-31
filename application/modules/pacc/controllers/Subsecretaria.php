<?php

/**
 * Description of pacc
 *
 * @author juanb
 * @date   Jan 16, 2015
 * 
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Subsecretaria extends MX_Controller {

    function __construct() {
        parent::__construct();
        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
        $this->load->model('pacc13/pacc13');
    }
    /*
     * Main function if no other invoked
     */
    function Index() {
        $this->dashboard_subsecretaria();
    }
    /**
     * Dashboard del COORDINADOR DE EMPRESAS Y GRUPOS PRODUCTIVOS
     */
    function dashboard_subsecretaria() {
        Modules::run('dashboard/dashboard', 'pacc/json/dashboard_subsecretaria.json');
    }


    function map_table($id, $title=""){
        $this->load->module('dashboard');
        $renderData['title'] = "Detalle de Incubadoras";
        $renderData['table'] = $title;
        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        $template="dashboard/widgets/box_info.php";
        $renderData['class']="map-table"; 
        $renderData['json_url'] = $this->base_url.'map/data_test_json';
        $renderData['provincia'] = $title;
        $renderData['content']= $this->parser->parse('map-table', $renderData, true, true);
        
        return $this->dashboard->widget($template, $renderData);
    }
   
}
