<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * test
 * 
 * Description of the class test
 * 
 * @author Juan Ignacio Borda <juanignacioborda@gmail.com>
 * @date   Apr 12, 2013
 */

class Io extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
        $this->debug_manual = null;
        $this->load->config('socketio');
    }
    function Index(){
        $this->load->model('bpm/bpm');
        $this->load->library('bpm/socketio_plugin');
        echo "<h1>TEST socket.io on port</h1>";
        
    }
    
    
}
