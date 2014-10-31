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
class Dispatcher extends MX_Controller {

    function __construct() {
        parent::__construct();
        ini_set("error_reporting", E_ALL);
    }

    function index() {


        $this->load->library("Nusoap_library"); //Soap Library        

        $this->nusoap_server = new soap_server();
        $ns = null;
        $service = null;
        $this->nusoap_server->configureWSDL("SOAP Server", $ns);
        $this->nusoap_server->wsdl->schemaTargetNamespace = $ns;

        /**
         * METODO | get_beneficio
         * 
         * @param 
         * @cat WS
         * @type PHP
         * @author Diego Otero
         * @function get_beneficio 
         * */
        $input_array = array('program' => "xsd:string", 'parameter' => "xsd:string");
        $return_array = array("return" => "xsd:string");
        $this->nusoap_server->register('get_beneficio', //method name
                $input_array, $return_array, "urn:SOAPServerWSDL", "urn:" . $ns . "/get_beneficio", "rpc", "encoded", "get profit by program");

        function get_beneficio($program, $parameter) {

            include("get_beneficios.php");
            return $pepe;
        }

        $this->nusoap_server->service(file_get_contents("php://input"));
    }

}

/* End of file webservice */
/* Location: ./system/application/controllers/welcome.php */