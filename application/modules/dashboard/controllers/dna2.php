<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * dna2
 * 
 * Description of the class dna2
 * 
 * @author Juan Ignacio Borda <juanignacioborda@gmail.com>
 * @date   Aug 17, 2014
 */
class Dna2 extends MX_Controller {

    function __construct() {
        parent::__construct();
        ini_set('error_reporting', E_ALL);
    }

    function Showapp($idapp=null) {
        $json = 'dashboard/json/dna2.json';
        $app = (isset($idapp)) ? '?idap=' . $idapp : '';
        $this->load->model('bpm/bpm');
        $this->load->model('dna2/dna2old');
        $dna2url = $this->dna2old->get('url');
        $cpData['frame_src'] = $this->bpm->gateway($dna2url . 'appfront/aplicacion3.php' . $app);
        echo Modules::run('dashboard/dashboard', $json, $cpData);
    }

}

/* End of file dna2 */
/* Location: ./system/application/controllers/welcome.php */