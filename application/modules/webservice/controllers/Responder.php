<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * WEBSERVICE
 * 
 * Description of the class WEBSERVICE
 * 
 * @author Diego Otero <xxcynicxx@gmail.com>
 * @date   Oct 21, 2014
 */
class Responder extends MX_Controller {

    function __construct() {
        
    }

    function index() {
        ini_set('soap.wsdl_cache_enabled', '0');
        ini_set('soap.wsdl_cache_ttl', '0');

        $REMOTE = 'http://dna2-tests.industria.gob.ar/dna2bpm/index.php/webservice/sserver?wsdl';
        $NAMESPACE = 'http://' . $_SERVER['HTTP_HOST'] . '/dna2bpm/index.php/webservice/sserver?wsdl';
        
        
        /* POST VALUE */
        $cuit = $this->input->post("cuit");


        $client = new SoapClient($NAMESPACE, array('trace' => 1, 'exceptions' => 1));
        try {
            $result = $client->cuitQueryMethod($cuit);
            echo (json_encode($result));
        } catch (SoapFault $fault) {
            echo $fault->getMessage() . '<br />';
            echo 'REQUEST <br />';
            echo '<pre>';
            echo $client->__getLastRequestHeaders();
            echo $client->__getLastRequest();
            echo '</pre>';
            echo 'RESPONSE <br />';
            echo '<pre>';
            echo $client->__getLastResponseHeaders();
            echo $client->__getLastResponse();
            echo '</pre>';
            echo 'TRACE <br />';
            echo '<pre>';
            var_dump($fault->getTrace());
            echo '</pre>';
        }
    }

}
