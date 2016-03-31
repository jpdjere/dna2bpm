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

class evaluador extends MX_Controller {

    function __construct() {
        parent::__construct();

        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
    }

    /*
     * Main function if no other invoked
     */

    function Index() {
        $this->dashboard();
    }
    
    /**
     * Dashboard del COORDINADOR DE EMPRESAS Y GRUPOS PRODUCTIVOS
     */
    function dashboard($debug=false) {
        Modules::run('dashboard/dashboard', 'pacc13/json/dashboard_evaluador.json',$debug);
    }

}
