<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * manifest
 * 
 * Devuelve el manifiesto parseado para actualizar valores
 * 
 * @author Juan Ignacio Borda <juanignacioborda@gmail.com>
 * @date    Jul 17, 2013
 */
class manifest extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('parser');
        $file = getcwd() . '/application/modules/genias/assets/manifest/offline.appcache';
        $file_content = file_get_contents($file);
        $cpData['fecha'] = date('Y-m-d H');
        $cpData['base_url'] = base_url();
        $cpData['module_url'] = base_url() . 'genias/';
        header("Content-Type: text/cache-manifest; charset=utf-8");
        $content = $this->parser->parse_string($file_content, $cpData);
        echo $content;
        //readfile($file);
        exit;
    }

}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */