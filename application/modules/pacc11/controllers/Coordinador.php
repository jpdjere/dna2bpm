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

class coordinador extends MX_Controller {

    function __construct() {
        parent::__construct();

        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
    }

   /*
     * FUNCION PRINCIPAL
     */

    function Index() {
        $this->dashboard_empresas();
    }
    /**
     * Dashboard del COORDINADOR DE EMPRESAS Y GRUPOS PRODUCTIVOS
     */
    function dashboard_empresas($debug=false) {
        Modules::run('dashboard/dashboard', 'pacc11/json/dashboard_coordinador_empresas.json',$debug);
    }


}
