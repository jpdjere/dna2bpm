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

        $this->load->library("Nusoap_library"); //cargando mi biblioteca

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


        /* addComplexType para arrays */
        $this->nusoap_server->wsdl->addComplexType(
                    'ArregloDeCadenas', 
                    'complexType', 
                    'array', 
                    'sequence', 
                    'http://schemas.xmlsoap.org/soap/encoding/:Array', 
                    array(), 
                    array(array('ref' => 'http://schemas.xmlsoap.org/soap/encoding/:arrayType',
                    'wsdl:arrayType' => 'xsd:string[]')
                    ), 
                'xsd:string'
        );
        
        
        $this->nusoap_server->wsdl->addComplexType(
        'Estructura',
        'complexType',
        'struct',
        'all',
        '',
        array(
        'Nombre' => array('name' => 'Nombre', 'type' => 'xsd:string'),
        'Apellidos'=>array('name' => 'Apellidos', 'type' => 'xsd:string'),
        'Edad'=>array('name' => 'Edad', 'type' => 'xsd:integer')
        )
);
        
        $this->nusoap_server->wsdl->addComplexType(
        'ArregloDeEstructuras',
        'complexType',
        'array',
        'sequence',
        'http://schemas.xmlsoap.org/soap/encoding/:Array',
        array(),
        array(array('ref' => 'http://schemas.xmlsoap.org/soap/encoding/:arrayType',
         'wsdl:arrayType' => 'tns:Estructura[]')
        ),
        'tns:Estructura'  
);
        
    }   

    function index() {

        function addnumbers($a, $b) {
            $c = $a + $b;
            return $c;
        }

        $this->nusoap_server->service(file_get_contents("php://input"));
    }

}

/* End of file webservice */
/* Location: ./system/application/controllers/welcome.php */