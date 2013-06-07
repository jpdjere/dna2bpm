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
        $this->load->helper('url');
        echo anchor('map/demo1','Demo 1').'<hr/>';
        echo anchor('map/demo_json','Demo Json').'<hr/>';
        echo anchor('map/pickup','PcickUp');
    }

function pickup() {
        $this->load->library('ui');
        //---prepare globals 4 js
        $renderData['title'] = "Pick Up on Click";
        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        $renderData['css'] = array(
            $this->module_url . 'assets/css/map.css' => 'MAP CSS'
        );
        $renderData['js'] = array(
            $this->module_url . 'assets/jscript/demo/pickup.js' => 'DEMO1 JS'
        );
        $renderData['global_js'] = array(
            'base_url' => $this->base_url,
            'module_url' => $this->module_url,
        );
        $this->ui->compose('demo1', 'map/bootstrap-map.ui.php', $renderData);
    }
function demo_json(){
    $this->json($this->module_url . 'assets/json/demo.json');
}
    function demo1() {
        $this->load->library('ui');
        //---prepare globals 4 js
        $renderData['title'] = "Demo 1 Harcoded all";
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

    private function json($url = null) {
        $this->load->library('ui');
        //---prepare globals 4 js
        
        $renderData['title'] = "Demo JSON url:$url";
        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        $renderData['css'] = array(
            $this->module_url . 'assets/css/map.css' => 'MAP CSS'
        );
        $renderData['js'] = array(
            $this->module_url . 'assets/jscript/demo/demo.json.js' => 'DEMO JSON'
        );
        $renderData['global_js'] = array(
            'base_url' => $this->base_url,
            'module_url' => $this->module_url,
            'json_url' => $url,
        );
        $this->ui->compose('demo1', 'map/bootstrap-map.ui.php', $renderData);
    }

}

/* End of file map */
/* Location: ./system/application/controllers/welcome.php */