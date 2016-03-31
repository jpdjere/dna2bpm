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
class bonita_licitaciones extends MX_Controller {

    function __construct() {
        parent::__construct();
         $this->load->model('user/user');
         $this->load->model('user/group');
         $this->load->model('model_bonita_licitaciones');
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

    
    function Index(){

    $this->user->authorize();
	$this->load->module('dashboard');
	$this->dashboard->dashboard('bonita/json/bonita_licitaciones_abm.json');
    
    }
    
    
    function bonita_licitaciones_list(){
        
        $this->user->authorize();
	    $this->load->module('dashboard');
	    $this->dashboard->dashboard('bonita/json/bonita_licitaciones_list.json');
        
        
    }
   
   function bonita_abm_entidades(){
        $this->user->authorize();
	    $this->load->module('dashboard');
	    $this->dashboard->dashboard('bonita/json/bonita_licitaciones_entidades.json');
       
   }
   
   
   
   function bonita_entidades(){
        $model='model_bonita_licitaciones';
        $entidades = array();
        $customData = array();
        $customData['base_url'] = $this->base_url;
        $entidades = $this->load->model($model)->listar_entidades();
        $lista = '<table id="table_ent" class="table">';
        foreach($entidades as $ent){
             $lista =  $lista.'<tr><td>RAZÓN SOCIAL:  </td><td><a href="" data-id="'.$ent['_id'].'" data-cmd="editar" data-rsocial="'.$ent['rsocial'].'" data-ent_cuit="'.$ent['ent_cuit'].'" data-obs="'.$ent['obs'].'" title=”EDITAR” name="editar">'.$ent['rsocial'].'</a></td><td> -  CUIT: '.$ent['ent_cuit'].'  -</td><td><a href=""   data-id="'.$ent['_id'].'" data-cmd="borrar" name="borrar"> BORRAR</a></td></tr>';
            
        }
        $lista =  $lista.'</table>';
        $customData['lista'] = $lista;
        return $this->parser->parse('bonita/bonita_licitaciones_entidades_view',$customData,true,true);
   }
   
   
   
   function bonita_licitaciones_entidad_nueva(){
        $model='model_bonita_licitaciones';
        $customData = array();
        $customData['base_url'] = $this->base_url;
        $customData['titulo'] = "CARGAR NUEVA ENTIDAD";
        $return['tabla'] = $this->parser->parse('bonita/bonita_licitaciones_entidades_nuevo_view',$customData,true,true);
        echo json_encode($return);
        return $return;   
   }
   
   function bonita_licitaciones_entidad_editar(){
        $model='model_bonita_licitaciones';
        $customData = array();
        $customData = $this->input->post();
        $customData['titulo'] = "EDITAR ENTIDAD";
        $customData['base_url'] = $this->base_url;
        $return['tabla'] = $this->parser->parse('bonita/bonita_licitaciones_entidades_nuevo_view',$customData,true,true);
        echo json_encode($return);
        return $return;
   }
   
   
   
   
   
   
   function bonita_licitaciones_entidad_nueva_cargar(){
        $model='model_bonita_licitaciones';
        $customData = array();
        $fields = array();
        $headerArr = array();
        $fields = $this->input->post();
        
        $i = 0;
        for($i=0; $i < 3 ; $i++){
            switch($i){
                case 0:
                    $headerArr['rsocial'] = $fields['fields'][0]['value'];
                    break;
                case 1:
                    $headerArr['ent_cuit'] = $fields['fields'][1]['value'];
                    break;
                case 2:
                    $headerArr['obs'] = $fields['fields'][2]['value'];
                    break;    
                
            } 
            
            
            
        } 
        $headerArr['borrado'] = 0;
        $result = $this->load->model($model)->guardar_entidades($headerArr);
        echo $result;
   }
   
   function bonita_licitaciones_entidad_editar_cargar(){
        $model='model_bonita_licitaciones';
        $customData = array();
        $fields = array();
        $headerArr = array();
        $fields = $this->input->post();
        
        $i = 0;
        for($i=0; $i < 3 ; $i++){
            switch($i){
                case 0:
                    $headerArr['rsocial'] = $fields['fields'][0]['value'];
                    break;
                case 1:
                    $headerArr['ent_cuit'] = $fields['fields'][1]['value'];
                    break;
                case 2:
                    $headerArr['obs'] = $fields['fields'][2]['value'];
                    break;  
            } 
        } 
        $headerArr['id_mongo'] = $fields['entidad'];
        $headerArr['borrado'] = 0;
        $result = $this->load->model($model)->guardar_entidades_editar($headerArr);
        echo $result;
   }
    
    function bonita_licitaciones_entidad_borrar(){
        $model='model_bonita_licitaciones';
        $fields = $this->input->post();
        $headerArr = array();
        $headerArr['id_mongo'] = $fields['id_mongo'];
        $result = $headerArr['id_mongo'];
        $this->load->model($model)->borrar_entidades($headerArr);
        echo $result;
    }
    
    function bonita_licitaciones_carga(){
        $model='model_bonita_licitaciones';
        $customData = array();
        $licitaciones = $this->load->model($model)->lista_licitaciones();
        $customData['base_url'] = $this->base_url;
        return $this->parser->parse('bonita/bonita_region_rep_view',$customData,true,true);
    }
   
   
   
    
}

