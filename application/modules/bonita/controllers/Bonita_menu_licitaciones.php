<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * inventory
 * 
 * Description of the class
 * 
 * @author Martin González 
 * @date    Apr 20, 2015
 */
class bonita_menu_licitaciones extends MX_Controller {

    function __construct() {
        parent::__construct();
         $this->load->model('user/user');
         $this->load->model('user/group');
         //$this->load->model('inventory_model');
         $this->user->authorize('modules/bonita');
         $this->load->library('parser');
//         $this->load->library('ui');
// //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . 'bonita/';
// ;
// //----LOAD LANGUAGE
         $this->idu = (float) $this->session->userdata('iduser');
// //---config
         $this->load->config('bonita/config');
// //---QR
         //$this->load->module('qr');
    }

    /*
     * Presentamos menu de acciones: info Checkin
     */

    function Index(){

     	//Modules::run('dashboard/dashboard','inventory/json/inventory.json');
	$this->user->authorize();
	$this->load->module('dashboard');
	$this->dashboard->dashboard('bonita/json/bonita_dashboard_lic.json');
    
    }
 
 
    

    function Query() {
        $cpData['base_url'] = $this->base_url;
        $cpData['module_url'] = $this->module_url;
        $cpData['title'] = 'QR Code';
        $cpData['reader_title'] = $cpData['title'];
        $cpData['reader_subtitle'] = 'Read QR Codes from any HTML5 enabled device';
//         $cpData['css'] = array(
//             $this->base_url . "inventory/assets/css/inventory.css" => 'custom css',
//         );
//         $cpData['js'] = array(
//             $this->base_url . "qr/assets/jscript/html5-qrcode.min.js" => 'HTML5 qrcode',
//             $this->base_url . "qr/assets/jscript/jquery.animate-colors-min.js" => 'Color Animation',
//             $this->base_url . "inventory/assets/jscript/qr.js" => 'Main functions'
//         );

//         $cpData['global_js'] = array(
//             'base_url' => $this->base_url,
//             'module_url' => $this->module_url,
//             'redir' => $this->module_url . 'info'
//         );
       
//        $cpData['myjs']='<script type="text/javascript" src="'.$this->base_url.'qr/assets/jscript/html5-qrcode.min.js"></script>';
//        $cpData['myjs'].='<script type="text/javascript" src="'.$this->base_url.'qr/assets/jscript/jquery.animate-colors-min.js"></script>';
//        $cpData['myjs'].='<script type="text/javascript" src="'.$this->base_url.'inventory/assets/jscript/qr.js"></script>';
        echo $this->parser->parse('query', $cpData, true,true);  
//               $this->load->library('ui');
//               echo $this->ui->compose('query', 'bootstrap.ui.php', $cpData);


    }

    
   
    
    
    
        
    
    
    
    
    function bonita_licitaciones_ops() {
        
        $model='model_bonita';
        
        $customData = array();
        
        $customData['tabla'] =
                '<tr><a href="'.$this->module_url.'bonita_licitaciones/bonita_abm_entidades/" target="_blank">ABM Entidades</a></tr></br>'.
                '<tr><a href="'.$this->module_url.'bonita_licitaciones/bonita_licitaciones_list/" target="_blank">ABM Licitaciones</a></tr></br>'.
                '<tr><a href="'.$this->module_url.'bonita_licitaciones/bonita_licitaciones_carga_datos/" target="_blank">Cargar Licitaciones</a></tr></br>'/*.
                '<tr><a href="'.$this->module_url.'bonita_reportes/bonita_reporte_sectores/" target="_blank">Reportes por Sector</a></tr></br>'.
                '<tr><a href="'.$this->module_url.'bonita_reportes/bonita_reporte_sectores_tam/" target="_blank">Reportes por Sector y Tamaño</a></tr></br>'*/;
                    
        return $this->parser->parse('bonita/bonita_licitaciones_opt_view',$customData,true,true);    
    }
   
    
    
    
}

