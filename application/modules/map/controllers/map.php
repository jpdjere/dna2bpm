<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * map
 * 
 * This class provides map services an geolocation
 * 
 * @author Juan Ignacio Borda <juanignacioborda@gmail.com>
 * @date   Jun 6, 2013
 */
class map extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->base_url = base_url();
        $this->module_url = base_url() . 'map/';
        $this->load->library('parser');
    }

    function Index() {
        $this->demo1();
    }

    function demo1() {
        $this->load->library('ui');
        //---prepare globals 4 js
        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        $renderData['css'] = array(
                $this->module_url . 'assets/css/map.css' => 'MAP CSS'
        );
        $renderData['js'] = array(
                $this->module_url . 'assets/jscript/demo/demo1.js' => 'DEMO1 JS'
            );
        $renderData['global_js'] = array(
            'base_url' => $this->base_url,
            'module_url' => $this->module_url,
        );
        $this->ui->compose('demo1', 'map/bootstrap-map.ui.php', $renderData);
    }

}

/* End of file map */
/* Location: ./system/application/controllers/welcome.php */