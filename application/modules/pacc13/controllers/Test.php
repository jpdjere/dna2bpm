<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * test
 * 
 * Description of the class
 * 
 * @author Juan Ignacio Borda <juanignacioborda@gmail.com>
 * @date    Oct 27, 2014
 */
class test extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('parser');
    }

    function Index() {
        $data['base_url'] = $this->base_url;
        $data['module_url'] = $this->module_url;
        $this->parser->parse($this->router->fetch_module() . '/tests', $data, false);
    }

}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */