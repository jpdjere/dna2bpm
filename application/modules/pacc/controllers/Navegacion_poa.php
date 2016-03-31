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

class Navegacion_poa extends MX_Controller {

    function __construct() {
        parent::__construct();
        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
        $this->load->module('pacc11/api11');
        $this->idu = $this->session->userdata('iduser');
    }
    /*
     * Main function if no other invoked
     */
    function Index() {
        $this->dashboard_navegacion_poa();
    }
    /**
     * Dashboard del COORDINADOR DE INCUBADORAS
     */
    function dashboard_navegacion_poa() {
        Modules::run('dashboard/dashboard', 'pacc/json/dashboard_navegacion_poa.json');
    }

    function navegacion_poa(){
        $this->load->module('dashboard');
        $template="dashboard/widgets/box_info.php";
        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        $renderData['title'] = "Herramientas";
        $renderData['content']= $this->parser->parse('navegacion-poa', $renderData, true, true);
       
        return $this->dashboard->widget($template, $renderData);
    }
    

 
}
