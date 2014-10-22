<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * WEBSERVICE
 * 
 * Description of the class testing
 * 
 * @author Diego Otero <xxcynicxx@gmail.com>
 * @date   Oct 21, 2014
 */
class Testing extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library("Nusoap_library"); //cargando mi biblioteca
        ini_set("soap.wsdl_cache_enabled", "0");
    }

    function index() {
        $params = array(
            'a' => 4,
            'b' => 3,
        );
        $wsdlURL = 'http://localhost/dna2bpm/webservice/?wsdl';
        $this->nusoap_client = new soapclient($wsdlURL, array(true));
        echo $this->nusoap_client->__soapCall('addnumbers', $params);
    }

}
