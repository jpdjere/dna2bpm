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
class Webservice extends MX_Controller {

    function __construct() {
        parent::__construct();
        ini_set("error_reporting", E_ALL);
    }


    function index() {

        $this->load->library("Nusoap_library"); //Soap Library
        
        /*PROGRAM CLASSES*/
        $this->load->library("programs/crefis"); 
        $crefis = $this->crefis = new crefis();
        $crefis_monto = $crefis->monto("50");
        
        
       
        
        
      //  var_dump($crefis->monto("50"));
        
        
        $this->nusoap_server = new soap_server();
        $ns = null;
        $service = null;
        $this->nusoap_server->configureWSDL("SOAP Server", $ns);
        $this->nusoap_server->wsdl->schemaTargetNamespace = $ns;

        /**
         * METODO | addnumbers
         * 
         * @param 
         * @cat WS
         * @type PHP
         * @author Diego Otero
         * @function addnumbers 
         * */
        $input_array = array('a' => "xsd:string", 'b' => "xsd:string");
        $return_array = array("return" => "xsd:string");
        $this->nusoap_server->register('addnumbers', //method name
                $input_array, $return_array, "urn:SOAPServerWSDL", "urn:" . $ns . "/addnumbers", "rpc", "encoded", "Addition Of Two Numbers");

        
        
        function addnumbers($a, $b) {               
            $c = $a + $b;
            return $c;
        }        
    
        $this->nusoap_server->service(file_get_contents("php://input"));
        
        
        
    }

}

/* End of file webservice */
/* Location: ./system/application/controllers/welcome.php */