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
class Sandbox extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library("Nusoap_library"); //cargando mi biblioteca
        ini_set("soap.wsdl_cache_enabled", "0");
        
    }

    function index() {

        ini_set('soap.wsdl_cache_enabled', '0');
        ini_set('soap.wsdl_cache_ttl', '0');

        $params = array(
            'program' => '',
            'parameter' => '',
        );
        $wsdlURL = 'http://'. $_SERVER['HTTP_HOST'] .'/dna2bpm/index.php/webservice/dispatcher/?wsdl';       
        $this->nusoap_client = new soapclient($wsdlURL);
        echo $this->nusoap_client->__soapCall('get_beneficio', $params);
    }  

}