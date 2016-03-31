<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 
 * Controller description
 * 
 * 
 * @author juanb
 * @date   Feb 2, 2015
 */
class planificacion extends MX_Controller {

    function __construct() {
        parent::__construct();
        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
    }

    function Index() {
        echo "<h1>Dashboard planificacion</h1>";
    }

}

/* End of file planificacion.php */