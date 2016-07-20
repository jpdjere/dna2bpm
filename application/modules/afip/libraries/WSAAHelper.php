<?php

/**
 * LIbrary para invocar al ws de autenticación de AFIP
 *
 * @author sfiorentino
 */
class WSAAHelper 
{

    private $traWorkingPath;

    function __construct() 
    {
        // TODO: ver como invocar esto en el contructor del lado del Controller.
        $this->traWorkingPath = APPPATH."modules/afip/libraries/";
    }

    function Autenticar($SERVICE, $wsdl, $url, $certificado, $clavePrivada, $source, $destination)
    {

  		$this->CreateTRA($SERVICE, $source, $destination);
	 	  $CMS=$this->SignTRA($certificado, $clavePrivada, '');
      $TA=$this->CallWSAA($CMS,$wsdl,$url);
		//  if (!file_put_contents(APPPATH."modules/afip/libraries/TA.xml", $TA)) {exit();}
      return $TA;

    }

    function CreateTRA($SERVICE, $SOURCE, $DESTINATION)
  	{
  	  $TRA = new SimpleXMLElement(
  	    '<?xml version="1.0" encoding="UTF-8"?>' .
  	    '<loginTicketRequest version="1.0">'.
  	    '</loginTicketRequest>');
  	  $TRA->addChild('header');

  	  $TRA->header->addChild('source',$SOURCE);
  	  $TRA->header->addChild('destination',$DESTINATION);
  	  $TRA->header->addChild('uniqueId',date('U'));
  	  $TRA->header->addChild('generationTime',date('c',date('U')-60));
  	  $TRA->header->addChild('expirationTime',date('c',date('U')+60));
  	  $TRA->addChild('service',$SERVICE);
  	  $TRA->asXML($this->traWorkingPath."TRA.xml");

  	}
	#==============================================================================
	# This functions makes the PKCS#7 signature using TRA as input file, CERT and
	# PRIVATEKEY to sign. Generates an intermediate file and finally trims the 
	# MIME heading leaving the final CMS required by WSAA.
    function SignTRA($CERT, $PRIVATEKEY,$PASSPHRASE='')
    {
    
      $STATUS=openssl_pkcs7_sign($this->traWorkingPath . "TRA.xml", $this->traWorkingPath ."TRA.tmp", "file://".$CERT,
        array("file://".$PRIVATEKEY, $PASSPHRASE),
        array(),
        !PKCS7_DETACHED
        );
      if (!$STATUS) {exit("ERROR generating PKCS#7 signature\n");}
      $inf=fopen($this->traWorkingPath . "TRA.tmp", "r");
      $i=0;
      $CMS="";
      while (!feof($inf)) 
        { 
          $buffer=fgets($inf);
          if ( $i++ >= 4 ) {$CMS.=$buffer;}
        }
      fclose($inf);
    #  unlink("TRA.xml");
      unlink($this->traWorkingPath . "TRA.tmp");
      return $CMS;
    }
    
    function CallWSAA($CMS, $WSDL, $URL)
    {
      $client=new SoapClient($WSDL, array(        
              'soap_version'   => SOAP_1_2,
              'location'       => $URL,
              'trace'          => 1,
              'exceptions'     => 0
              )); 
      $results=$client->loginCms(array('in0'=>$CMS));

      //TODO: habilitar este debug según environment de la app.
     // file_put_contents("request-loginCms.xml",$client->__getLastRequest());
     // file_put_contents("response-loginCms.xml",$client->__getLastResponse());
      if (is_soap_fault($results)) 
        {exit("SOAP Fault: ".$results->faultcode."\n".$results->faultstring."\n");}
      return $results->loginCmsReturn;
    }
}