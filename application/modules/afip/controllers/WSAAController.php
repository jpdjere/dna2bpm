<?php

// if (!defined('BASEPATH'))
//     exit('No direct script access allowed');
/**
 * "ventanilla electrónica" de la AFIP
 * 
 * @autor Diego Otero
 * 
 * @version 	1.0 
 * 
 * 
 */

use BeSimple\SoapCommon\Helper as BeSimpleSoapHelper;
use BeSimple\SoapClient\SoapClient as BeSimpleSoapClient;
use BeSimple\SoapCommon\Converter\MtomTypeConverter as MtomTypeConverter;
use BeSimple\SoapCommon\Mime\Part as MimePart;


 
class WSAAController extends MX_Controller {

    var $authReq;
    var $client;

    function __construct() {
        parent::__construct();

        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
        
        #LIBRARIES
//        $this->load->library('parser');
//        $this->load->library('dashboard/ui');

        $this->load->library('WSAAHelper');
        
        
        #MODELS
        $this->load->model('afip/eventanilla_model');
        $this->load->model('afip/seti_model');
        
        #CREDENTIALS
        $this->idu = (int) $this->session->userdata('iduser');
        if(ENVIRONMENT<>'127.0.0.1_afip')
            $this->user->authorize();
        
        //---Output Profiler
        //$this->output->enable_profiler(TRUE);
        #ini_set('display_errors', 1);
        #error_reporting(E_ALL);
        #ini_set('xdebug.var_display_max_depth', 120 );

    }
    
    function Index(){

       # $this->load->view('wsaa_index_view');
    }


    function testEVentanilla()
    {

        $token = $this->getToken('veconsumerws');
        $xml = simplexml_load_string($token);
        
        $options = array(
                'soap_version'    => SOAP_1_1,
                'trace'         => TRUE,
                'features'        => SOAP_SINGLE_ELEMENT_ARRAYS, // make sure that result is array for size=1
                'attachment_type' => BeSimpleSoapHelper::ATTACHMENTS_TYPE_MTOM,
                'cache_wsdl'      => WSDL_CACHE_NONE,
                'proxy_host' => false, 
            );

        $wsdl = 'https://stable-middleware-tecno-ext.afip.gob.ar/ve-ws/services/veconsumer?wsdl';

        $this->client = new BeSimpleSoapClient($wsdl, $options);


        $parametros=array();

        $this->authReq = array();

        $this->authReq['token']= $xml->credentials->token;

        $this->authReq['sign'] = $xml->credentials->sign;
        
        #min prod
        $this->authReq['cuitRepresentada'] ='30710817452';
      
      

        $parametros['authRequest'] = $this->authReq;

        $filter= array();
        $filter['fechaDesde']= '2016-05-05';        
        #$filter['resultadosPorPagina']= '7000';
        #$filter['comunicacionIdDesde']=7954020;
        $filter['sistemaPublicadorId'] = 44;
        $filter['referencia1'] = "F.1272";   
        $filter['estado'] = 1;        

        
        $parametros['filter'] = $filter;
        
        var_dump($parametros);
        
        try {

        $results = $this->client->consultarComunicaciones($parametros);
        

        $this->procesarPagina($results);

     
        $totalRegistros = $results->RespuestaPaginada->totalItems;
        $totalPaginas = $results->RespuestaPaginada->totalPaginas;
        
        echo "Total Items:" . $results->RespuestaPaginada->totalItems ."</br>";
        echo "Total Páginas:" . $results->RespuestaPaginada->totalPaginas ."</br>";

        echo "Pagina: 1";
       
        for ($i=2; $i<=$totalPaginas  ; $i++) { 

            echo "Pagina: " . $i;
            $parametros['filter']['pagina'] =$i;
            $results = $this->client->consultarComunicaciones($parametros);
            $this->procesarPagina($results);        
        }

    



        // obtengo el IdComunicacion 


        }  catch (Exception $e) { 
            //print_r( $client->__getLastRequest());
            echo "<h2>Exception Error!</h2>"; 
            echo $e->getMessage(); 
        } 

    }

    function procesarPagina($results)
    {

        foreach($results->RespuestaPaginada->items as $item)
        {   
            //print_r($item);

            foreach($item as $comunicacion)
            {
                
                /*
                public 'estado' => int 2
                public 'estadoDesc' => string 'Comunicación Leída' (length=20)
                */
               # if($comunicacion->estado==1){ #Identificación del Estado #1 No Leida
                    $parametros=[];
                    $parametros['authRequest']= $this->authReq;
                    $parametros['idComunicacion'] = $comunicacion->idComunicacion;
                    $parametros['incluirAdjuntos'] =true;

                     echo "<br>idComunicacion " . $comunicacion->idComunicacion . "" . $comunicacion->estadoDesc;


                    $results = $this->client->consumirComunicacion($parametros);

                    $comunicaciones_raw="";

                    $cantidad=0;

                    foreach ($results->Comunicacion->adjuntos as $adjunto) {


                        if (isset($adjunto[0]))
                        {
                            $cantidad++;
                            // A MongoDB

                            $params =  array("idComunicacion" => $comunicacion->idComunicacion, 
                                    'content'=>utf8_encode($adjunto[0]->content));

                           
                            Modules::run('afip/eventanilla/save_raw_ventanilla', $params);
                        }
                    }
                    
                    echo $comunicaciones_raw;
                    // call save_raw_data!!
            #}

            }

        }

    }


    /*LLAMAMOS AL WS DE SETI*/
    function callSETI($archivo =NULL)
    {

        $token = $this->getTokenSeti('djprocessorcontribuyente');
        $xml = simplexml_load_string($token);
        

        $options = array(
                'soap_version'    => SOAP_1_1,
                'trace'         => true,
                'features'        => SOAP_SINGLE_ELEMENT_ARRAYS, // make sure that result is array for size=1
                'attachment_type' => BeSimpleSoapHelper::ATTACHMENTS_TYPE_MTOM,
                'cache_wsdl'      => WSDL_CACHE_NONE,
                'proxy_host' => false, 
            );

        $wsdl = 'https://awshomo.afip.gov.ar/setiws/webservices/uploadPresentacionService?wsdl';

        $client = new BeSimpleSoapClient($wsdl, $options);

        $mtomFilter = new MtomTypeConverter();

        $client->getSoapKernel()->registerFilter($mtomFilter);

        $parametros=array();

        $parametros['token'] = $xml->credentials->token;
        $parametros['sign']  =$xml->credentials->sign;
        
        #min prod 30710817452
        $parametros['representadoCuit'] ='30710817452';


        if(is_null($archivo))
        {
        
            $file = "F1273.30710817452.20160530.0001.txt";

        }
        else
        {
            echo "Proceso el archivo " . $archivo;
            $file = $archivo; 
        }


        $ASSETSSETIPATH=APPPATH."modules/afip/assets/SETI/";

        $encodedFile = file_get_contents($ASSETSSETIPATH. $file);
        $mime = new MimePart($encodedFile,'application/octet-stream' ,null, MimePart::ENCODING_BINARY, $file);


        $presentacion = array();
        $presentacion['presentacionDataHandler'] = $mime;
        $presentacion['fileName'] =$file;

        $parametros['presentacion'] = $presentacion;

        try {

            $results = $client->upload($parametros);
            $client->__getLastRequest();

            var_dump($client->__getLastResponse());

            return $results;

        }  catch (Exception $e) { 
                    echo "<h2>Exception Error!</h2>"; 
                    echo $e->getMessage(); 
        } 
    }

    function getToken($wsname)
    {
        $ASSETSPATH=APPPATH."modules/afip/assets/wsaa/";

        $WSDL= $ASSETSPATH."wsaa.wsdl";
        $CERT = $ASSETSPATH."CR_sbarra_509";  
        $PRIVATEKEY = $ASSETSPATH."Sbarra.key"; 
       
       
        $SOURCE='C=AR, O=SBARRA RODRIGO ALBERTO, SERIALNUMBER=CUIT 20303678329, CN=clasificador PYME';
        $DESTINATION='CN=wsaahomo, O=AFIP, C=AR, SERIALNUMBER=CUIT 33693450239';

        $URL ="https://wsaahomo.afip.gov.ar/ws/services/LoginCms";

        $WEBSERVICE = $wsname ;

        $results =  $this->wsaahelper->Autenticar($WEBSERVICE, $WSDL, $URL, $CERT, $PRIVATEKEY, $SOURCE, $DESTINATION);

        return $results;

    }

    function getTokenSeti($wsname)
    {
        $ASSETSPATH=APPPATH."modules/afip/assets/wsaa/";

        $WSDL= $ASSETSPATH."wsaa.wsdl";
        $CERT = $ASSETSPATH."CR_industria_509";  
        $PRIVATEKEY = $ASSETSPATH."industria.key"; 
       
        #<source>cn=front.produccion.gob.ar,ou=clasificador,o=ministerio de produccion,c=ar,serialNumber=CUIT 30710817452</source>
        #$SOURCE='C=AR, O=ministerio de produccion, SERIALNUMBER=CUIT 20303678329, CN=front.produccion.gob.ar';
        $SOURCE='C=AR, O=Ministerio de Produccion, SERIALNUMBER=CUIT 30710817452, CN=front.producion.gob.ar';
        $DESTINATION='CN=wsaahomo, O=AFIP, C=AR, SERIALNUMBER=CUIT 33693450239';

        $URL ="https://wsaahomo.afip.gov.ar/ws/services/LoginCms";

        $WEBSERVICE = $wsname ;

        $results =  $this->wsaahelper->Autenticar($WEBSERVICE, $WSDL, $URL, $CERT, $PRIVATEKEY, $SOURCE, $DESTINATION);

        return $results;

    }    
    
    function testWSAA()
    {

        $results = $this->getToken('veconsumerws') ;       

        print_r($results);

    }

    // function test_ventanilla(){
    //     $raw_ventanilla=$this->load->view('json/1272.json', '', true); # @debug
    //     echo Modules::run('afip/eventanilla/save_raw_ventanilla',$raw_ventanilla);

    // }




    function generaArchivoSeti()
    {
        $items = $this->seti_model->obtenerQueueReadyCount();        
        
        $count_loops = $items/999;
        $count_loops = (int)$count_loops;

        $i = 1;
        while ($i <= $count_loops) {
            $this->process();
            $i++;  
        }

    }

    function process()
    {
        $tipoRegistro ="01";
        $cuit_informante="30710817452";        
        $formulario="1273";
        $formulario_version="00100";
        
        $items = $this->eventanilla_model->obtenerQueueReady();


        
        $cantidadRegistros=str_pad(sizeof($items)+1,5,"0",STR_PAD_LEFT); //maximo 5 posiciones


        $cabecera =$tipoRegistro.$cuit_informante.$formulario.$formulario_version.date("Ymd").$cantidadRegistros;

        echo $cabecera."<br>";


        foreach ($items as $item ) {         
            
            
                $detalle="02";
                
                $idComunicacion = str_pad($item['transaccion'],15, "0", STR_PAD_LEFT);

                $cuit =$item['cuit'];
                
                $caracterizacion="099";  // no volamos , queda solo actividad

                $categoria = $item['categoria'];

                switch ($categoria) {
                    case 'micro':
                        $caracterizacion = "272";
                        break;
                    case 'peq':
                        $caracterizacion ="274";
                        break;
                    case 'tramo1':
                        $caracterizacion ="351";
                        break;
                    case 'tramo2':
                        $caracterizacion ="352";
                        break;
                    default:
                        $caracterizacion="099";
                        break;
                }


            $actividad=str_pad($item['sector'],2,"0",STR_PAD_LEFT);  //01

            $opcionRegimenIVA="S";
            
            $fechaAprobacion=date("Ymd");

            $estadoAprobacion=$item['isPyme'];

            $transaccion=$item['transaccion'];


            $detalle = $detalle . $idComunicacion.$cuit.$caracterizacion.$actividad.$opcionRegimenIVA.$fechaAprobacion.$estadoAprobacion;

            $cabecera=$cabecera."\n".$detalle;

            echo $detalle."<br>";
              
            

        }

            $cantidad =str_pad(sizeof($items)+1, 4, "0", STR_PAD_LEFT);

            $fileName ="F".$formulario.".".$cuit_informante.".".date("Ymd").".".$cantidad.".txt";

            //form F1274 cuitministerio, fecha y cantidad de registros (4 digitos).txt

            $ASSETSSETIPATH=APPPATH."modules/afip/assets/SETI/";

            file_put_contents($ASSETSSETIPATH.$fileName, $cabecera);


            $data['fileName'] = $fileName;
            $data['content'] =$cabecera;

            $this->seti_model->saveSetiCall($data);

            $resultado = $this->callSETI($fileName);
            $response['filename'] = $fileName;
            $response['content'] = $resultado;

            $rtn = $this->seti_model->saveSetiResponse($response);
            
            /*if SETI ok*/
            if($resultado){               
                /*Update Ready from queue*/
                $this->seti_model->update_ready_queue($fileName);
            }
    }

    
}
