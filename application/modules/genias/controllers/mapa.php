<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * mapa
 * 
 * Description of the class
 * 
 * @author Juan Ignacio Borda <juanignacioborda@gmail.com>
 * @date    Jun 10, 2013
 */
class Mapa extends MX_Controller {

    function __construct() {
        parent::__construct();
               parent::__construct();
        $this->base_url = base_url();
        $this->module_url = base_url() . 'genias/';
    }

    function Index() {

        
    }

    function GetEmpresasGenia($geniaId=null){
        
    }
    
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */