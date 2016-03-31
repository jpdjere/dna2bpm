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
        ini_set("error_reporting", 0);
    }

    function index() {


        $this->load->library("Nusoap_library"); //Soap Library    

        $NAMESPACE = 'http://' . $_SERVER['HTTP_HOST'] . '/dna2bpm/index.php/webservice/sserver';
        $this->nusoap_server = new soap_server();

        $this->nusoap_server->debug_flag = false;
        $this->nusoap_server->configureWSDL('SOAP Server', $NAMESPACE);
        $this->nusoap_server->wsdl->schemaTargetNamespace = $NAMESPACE;


        /* WSDL TYPES DECLARATION */
        $this->nusoap_server->wsdl->addComplexType(
                '_dna_cuit', 'complexType', 'struct', 'all', '', array(
            'param_cuit' => array('name' => 'param_cuit', 'type' => 'xsd:string')
                )
        );


// ---- Cuit ----------------------------------------------------------------

        $this->nusoap_server->wsdl->addComplexType(
                'Cuit', 'complexType', 'struct', 'all', '', array(
            'consulta' => array('name' => 'consulta', 'type' => 'xsd:string'),
            'resultado' => array('name' => 'resultado', 'type' => 'xsd:string'),
            'identificador' => array('name' => 'identificador', 'type' => 'xsd:string'),
            'clanae' => array('name' => 'clanae', 'type' => 'xsd:string'),
            'fecha' => array('name' => 'fecha', 'type' => 'xsd:string'),
            'monto' => array('name' => 'monto', 'type' => 'xsd:string'),
            'codigo_postal' => array('name' => 'codigo_postal', 'type' => 'xsd:string'),
            'localidad' => array('name' => 'localidad', 'type' => 'xsd:string'),
            'provincia' => array('name' => 'provincia', 'type' => 'xsd:string'),
            'programa' => array('name' => 'programa', 'type' => 'xsd:string')
                )
        );

// ---- Cuit[] --------------------------------------------------------------

        $this->nusoap_server->wsdl->addComplexType(
                'CuitArray', 'complexType', 'array', '', 'SOAP-ENC:Array', array(), array(
            array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:Cuit[]')
                ), 'tns:Program'
        );

        /* WSDL METHODS REGISTRATION */
        $input_array = array("param_cuit" => "xsd:string");



        $return_array = array('return' => 'tns:CuitArray');

        $this->nusoap_server->register(
                'cuitQueryMethod', $input_array, $return_array, "urn:SOAPServerWSDL", "urn:" . $NAMESPACE . "/cuitQueryMethod", "rpc", "encoded", "get profit by Cuit");

        function cuitQueryMethod($param_cuit) {
            include("get_beneficios.php");
            return $response_cuits;
        }

        ///////////////////////////////////////////////////////////////

        /* WSDL TYPES DECLARATION */
        $this->nusoap_server->wsdl->addComplexType(
                '_dna', 'complexType', 'struct', 'all', '', array(
            'program_name' => array('name' => 'program_name', 'type' => 'xsd:string'),
            'date_from' => array('name' => 'date_from', 'type' => 'xsd:string'),
            'date_to' => array('name' => 'date_to', 'type' => 'xsd:string')
                )
        );


// ---- Program ----------------------------------------------------------------

        $this->nusoap_server->wsdl->addComplexType(
                'Program', 'complexType', 'struct', 'all', '', array(
            'consulta' => array('name' => 'consulta', 'type' => 'xsd:string'),
            'resultado' => array('name' => 'resultado', 'type' => 'xsd:string'),
            'identificador' => array('name' => 'identificador', 'type' => 'xsd:string'),
            'clanae' => array('name' => 'clanae', 'type' => 'xsd:string'),
            'fecha' => array('name' => 'fecha', 'type' => 'xsd:string'),
            'monto' => array('name' => 'monto', 'type' => 'xsd:string'),
            'codigo_postal' => array('name' => 'codigo_postal', 'type' => 'xsd:string'),
            'localidad' => array('name' => 'localidad', 'type' => 'xsd:string'),
            'provincia' => array('name' => 'provincia', 'type' => 'xsd:string'),
            'programa' => array('name' => 'programa', 'type' => 'xsd:string')
                )
        );

// ---- Program[] --------------------------------------------------------------

        $this->nusoap_server->wsdl->addComplexType(
                'ProgramArray', 'complexType', 'array', '', 'SOAP-ENC:Array', array(), array(
            array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:Program[]')
                ), 'tns:Program'
        );

        /* WSDL METHODS REGISTRATION */
        $input_array = array("program_name" => "xsd:string", "date_from" => "xsd:string", "date_to" => "xsd:string");

        $return_array = array('return' => 'tns:ProgramArray');

        $this->nusoap_server->register(
                'getPrograms', $input_array, $return_array, "urn:SOAPServerWSDL", "urn:" . $NAMESPACE . "/getPrograms", "rpc", "encoded", "get profit by program");

        function getProgram($input_array) {
            include("get_beneficios.php");
            return $response;
        }

        function getPrograms($program_name, $date_from, $date_to) {

            include("get_beneficios.php");
            return $response;
        }

        $HTTP_RAW_POST_DATA = isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : '';
        $this->nusoap_server->service($HTTP_RAW_POST_DATA);
        exit();
    }

}

/* End of file webservice */
