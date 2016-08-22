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
 
class Padron extends MX_Controller {    
    function __construct() {
        parent::__construct();

        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
        
        #LIBRARIES
        $this->load->library('parser');
        $this->load->library('dashboard/ui');
        
        
        #MODELS
        $this->load->model('afip/eventanilla_model');
        $this->load->model('afip/consultas_model');

        $this->g750=$this->eventanilla_model->idrel();
        
        #CREDENTIALS
        $this->idu = (int) $this->session->userdata('iduser');
        $this->user->authorize();
        
     

    }
    
    function Index() {
        $this->dashboard();
    }
    /**
     * Dashboard para Admins
     */
    function dashboard($debug=false){
        Modules::run('dashboard/dashboard', 'afip/dashboards/dashboard_padron.json',$debug);

    }
    
    function reprocess(){
        $this->load->library('mongowrapper');
        $tabla[1]=array('A');
        $tabla[2]=array('C',);
        $tabla[3]=array('B');
        $tabla[4]=array('D','E','H','I','J','M','N','P','Q','R','S','K','L','T','U','O');
        $tabla[5]=array('F');
        $tabla[6]=array('G');
        $padron=$this->mongowrapper->padfyj;
        set_time_limit(3600);
        $rs=$padron->inscripcion_afip->find(array('idrel'=>array('$exists'=>false)));
        // $rs=$this->mongowrapper->db->users->find();
        echo"Procesando:".$rs->count();
        foreach($rs as $reg){
            $act=str_pad((string)$reg['ACTIVIDAD'],6,'000',STR_PAD_LEFT);
            $halfAct=substr($act, 0,3);
            for($i=0;$i<count($this->g750->data);$i++){
                if($this->g750->data[$i]['value']==$halfAct){
                    $reg['idrel']=$this->g750->data[$i]['idrel'];
                    break;
                }
            }
            
            foreach($tabla as $k=>$v){
                if(in_array($reg['idrel'],$v)){
                    $reg['sector']=$k;
                    break;
                }
            }   
                $padron->inscripcion_afip->save($reg);
        }
    }
    

    
}//class
