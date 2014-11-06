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
class Sserver extends MX_Controller {

    function __construct() {
        parent::__construct();
        ini_set("error_reporting", E_ALL);
    }

    function index() {


        $this->load->library("Nusoap_library"); //Soap Library    

        $NAMESPACE = null;
        $this->nusoap_server = new soap_server();

        $this->nusoap_server->debug_flag = false;
        $this->nusoap_server->configureWSDL('SOAP Server', $NAMESPACE);
        $this->nusoap_server->wsdl->schemaTargetNamespace = $NAMESPACE;

        /* WSDL TYPES DECLARATION */
        $this->nusoap_server->wsdl->addComplexType(
                '_dna', 'complexType', 'struct', 'all', '', array(
            'titulo' => array('name' => 'titulo', 'type' => 'xsd:string'),
            'page' => array('name' => 'page', 'type' => 'xsd:int')
                )
        );

// ---- _dna[] -----------------------------------------------------------

        $this->nusoap_server->wsdl->addComplexType(
                'DataArray', 'complexType', 'array', '', 'SOAP-ENC:Array', array(), array(
            array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:_dna[]')
                ), 'tns:_dna'
        );

// ---- Program ----------------------------------------------------------------

        $this->nusoap_server->wsdl->addComplexType(
                'Program', 'complexType', 'struct', 'all', '', array(
            'identificador' => array('name' => 'identificador', 'type' => 'xsd:string'),
            'titulo' => array('name' => 'titulo', 'type' => 'xsd:string'),
            'fecha' => array('name' => 'fecha', 'type' => 'xsd:string'),
            'monto' => array('name' => 'monto', 'type' => 'xsd:int'),
            'estado' => array('name' => 'estado', 'type' => 'xsd:string'),
            'detalle' => array('name' => 'detalle', 'type' => 'xsd:string'),
            'id_dna' => array('name' => 'id_dna', 'type' => 'xsd:int'),
            'programa' => array('name' => 'programa', 'type' => 'tns:DataArray')
                )
        );

// ---- Program[] --------------------------------------------------------------

        $this->nusoap_server->wsdl->addComplexType(
                'ProgramArray', 'complexType', 'array', '', 'SOAP-ENC:Array', array(), array(
            array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:Program[]')
                ), 'tns:Program'
        );

        /* WSDL METHODS REGISTRATION */

        $input_array = array('titulo' => "xsd:string");
        $return_array = array('return' => 'tns:Program');

        $this->nusoap_server->register('getProgram', //method name
                $input_array, $return_array, "urn:SOAPServerWSDL", "urn:" . $NAMESPACE . "/getProgram", "rpc", "encoded", "get profit by program");

        function getProgram() {
            include("get_beneficios.php");
            return $response;
        }

        $this->nusoap_server->service(file_get_contents("php://input"));
        exit();
    }

}

/* End of file webservice */
/* Location: ./system/application/controllers/welcome.php */