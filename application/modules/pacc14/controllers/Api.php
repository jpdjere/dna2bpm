<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * api
 * 
 * esta clase provee servicios para componentes externos ya sea en formato JSON 
 * u otros necesarios dependiendo del cliente.
 * 
 * @author Juan Ignacio Borda <juanignacioborda@gmail.com>
 * @date    Jan 28, 2015
 */
class api extends MX_Controller {

    function __construct() {
        parent::__construct();
        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
    }

    function Index() {
        
    }

}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */