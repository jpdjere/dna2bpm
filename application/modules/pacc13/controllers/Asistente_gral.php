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

class asistente_gral extends MX_Controller {

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
        $this->dashboard();
    }
    /**
     * Dashboard del COORDINADOR DE EMPRESAS Y GRUPOS PRODUCTIVOS
     */
    function dashboard($debug=false) {
        Modules::run('dashboard/dashboard', 'pacc13/json/dashboard_asistente_gral.json',$debug);
    }


}
