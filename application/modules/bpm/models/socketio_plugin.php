<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version1X;
// include(FCPATH.'application/modules/bpm/libraries/elephant.io-master/src/Client.php');    
// include(FCPATH.'application/modules/bpm/libraries/elephant.io-master/src/EngineInterface.php');    
// include(FCPATH.'application/modules/bpm/libraries/elephant.io-master/src/Engine/AbstractSocketIO.php');    
// include(FCPATH.'application/modules/bpm/libraries/elephant.io-master/src/Engine/SocketIO/Version1X.php');    
class Socketio_plugin extends CI_Model{

    //put your code here
    var $CI;

    public function __construct() {

// Set the super object to a local variable for use throughout the class
        $CI = & get_instance();
        $CI->load->config('bpm/socketio');
        //---add hooks to BPM class, check the class is loaded first
        if(property_exists($CI,'bpm')){
           $CI->bpm->movenext_hook[]=array(strtolower(__CLASS__)=>'movenext_hook'); 
        } 
    }
    
    function movenext_hook($idwf,$idcase,$token=null,$case=null){
        if($token['type']=='Task'){
            var_dump($idwf,$idcase,$token,$case,$this->config->item('socketio_host'));exit;
            $client = new Client(new Version1X($this->config->item('socketio_host')));
            $client->initialize();
            @$client->emit('chat message',$token['title'].'->'.$token['status']);
            $client->close();
        }
    }
}