<?php

/**
 * Description of pacc
 *
 * @author juanb
 * @date   Jan 16, 2015
 * 
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class pacc11 extends MX_Controller {

    function __construct() {
        parent::__construct();

        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
    }

    /*
     * Main function if no other invoked
     */

    function Index() {
        echo "<h1>" . $this->router->fetch_module() . '</h1>';
    }
    
    function asignar_evaluador($idwf, $idcase, $tokenId) {
        $this->load->library('parser');
        $this->load->model('user/group');
        $this->load->model('bpm/bpm');
        $case = $this->bpm->get_case($idcase, $idwf);
        $renderData = $this->bpm->load_case_data($case, $idwf);
        //----tomo evaluador del caso
        $evaluador = $renderData['Proyectos_pacc']['6404'][0];
        //----token que hay que finalizar
        $src_resourceId = 'oryx_71CCC6C6-38E5-41AB-A92B-32560937098D';
        // ---Token de pp asignado
        $lane_resourceId = 'oryx_295810F2-8C34-4D03-80F8-7B5C371381B8';

        $url = $this->base_url . "bpm/engine/assign/model/$idwf/$idcase/$src_resourceId/$lane_resourceId/$evaluador";

        redirect($url);
    }

    function asignar_evaluador_pde($idwf, $idcase, $tokenId) {
        $this->load->library('parser');
        $this->load->model('user/group');
        $this->load->model('bpm/bpm');
        $case = $this->bpm->get_case($idcase, $idwf);
        $renderData = $this->bpm->load_case_data($case, $idwf);
        //----tomo evaluador del caso
        $evaluador = $renderData['Proyectos_pacc']['6404'][0];
        //----token que hay que finalizar
        $src_resourceId = 'oryx_B2680C93-D39D-405B-8BA8-5999A3628F2E';
        // ---Token de pp asignado
        $lane_resourceId = 'oryx_B1093DB1-19FD-490F-BFCD-9D11EAACBA83';

        $url = $this->base_url . "bpm/engine/assign/model/$idwf/$idcase/$src_resourceId/$lane_resourceId/$evaluador";

        redirect($url);
    }
   
    function asignar_evaluador_sde($idwf, $idcase, $tokenId, $src_resourceId) {
        $this->load->library('parser');
        $this->load->model('user/group');
        $this->load->model('bpm/bpm');
        $case = $this->bpm->get_case($idcase, $idwf);
        $renderData = $this->bpm->load_case_data($case, $idwf);
        //----tomo evaluador administrativo
        $evaluador_admin = $renderData['Proyectos_pacc']['6743'][0];
        //----tomo evaluador técnico
        $evaluador = $renderData['Proyectos_pacc']['6404'][0];
        //----token que hay que finalizar (self)
        //$src_resourceId = 'oryx_BD7F84C3-73FE-48E0-831F-DEB0B9F78DCC';
        // ---Token de pp asignado
        $lane_resourceId = 'oryx_0DA9E38A-92D8-4B19-A948-46CEF3168613';
        
        $url = $this->base_url . "bpm/engine/assign/model/$idwf/$idcase/$src_resourceId/$lane_resourceId/$evaluador";

        redirect($url);
    }
    
    function asignar_evaluador_administrativo_sde($idwf, $idcase, $tokenId, $src_resourceId) {
        $this->load->library('parser');
        $this->load->model('user/group');
        $this->load->model('bpm/bpm');
        $case = $this->bpm->get_case($idcase, $idwf);
        $renderData = $this->bpm->load_case_data($case, $idwf);
        //----tomo evaluador administrativo
        $evaluador = $renderData['Proyectos_pacc']['6743'][0];
        //----token que hay que finalizar (self)
        //$src_resourceId = 'oryx_BD7F84C3-73FE-48E0-831F-DEB0B9F78DCC';
        // ---Token de Lane asignado
        $lane_resourceId = 'oryx_CD23C511-FAE2-4549-8D26-2182224D770F';
        
        $url = $this->base_url . "bpm/engine/assign/model/$idwf/$idcase/$src_resourceId/$lane_resourceId/$evaluador";

        redirect($url);
    }
 
    function clone_case($from_idwf, $to_idwf, $idcase) {
        $this->load->model('bpm/bpm');
        $this->load->module('bpm/engine');
        $case = $this->bpm->get_case($idcase, $from_idwf);
        $Initiator=$case['iduser'];
        $case_to = $this->bpm->get_case($idcase, $to_idwf);
        $silent=true;
        $run_resourceId = null;
        if (!$case_to) {

            $this->bpm->gen_case($to_idwf, $idcase);
            $case_to = $this->bpm->get_case($idcase, $to_idwf);
            $case_to['data'] = $case['data'];
            $case_to['data']['Initiator'] =$Initiator; 
            $case_to['iduser'] = $case['iduser'];
            $case_to = $this->bpm->save_case($case_to);
            $this->engine->Startcase('model', $to_idwf, $idcase,$silent);
            $this->engine->Run('model', $to_idwf, $idcase,$run_resourceId,$silent);
        } else {
            /*
             *   continue case
             */
            $mywf = $this->bpm->load($to_idwf);
            if (!$mywf) {
                show_error("Model referenced:$idwf does not exists");
            }
            $wf = bindArrayToObject($mywf ['data']);
            // ---Get all start points of diagram
            $start_shapes = $this->bpm->get_start_shapes($wf);
            // ----Raise an error if doesn't found any start point
            if (!$start_shapes)
                show_error("The Schema doesn't have an start point");
            // ---Start all StartNoneEvents as possible
            foreach ($start_shapes as $start_shape) {
                $this->bpm->set_token($to_idwf, $idcase, $start_shape->resourceId, $start_shape->stencil->id, 'pending');
            }
            $this->engine->Run('model', $to_idwf, $idcase,$run_resourceId,$silent);
        }

        //----run case
        // Modules::run("bpm/run/model/$to_idwf/$idcase");
    }
    /**
     * Funcion crear las SDE a partir de un proyecto
     * @todo customizar valores según MJL
     * 
     * Modules::run('pacc11/create_SDE',$idwf,$idcase);
     * 
     */
     
    function create_SDE($idwf,$idcase){
        $this->load->model('bpm/bpm');
        $this->load->model('app');
        $this->load->module('bpm/engine');
        $case = $this->bpm->get_case($idcase, $idwf);
        //---tomo el Initiator del clone anterior
        $Initiator=$case['data']['Initiator'];
        $id=$case['data']['Proyectos_pacc']['query']['id'];
        $id_empresa=$case['data']['Empresas']['query']['id'];
        $idframe=6243;
        $actividades=$this->app->getvalue($id, $idframe);
        $ids_actividades=array();
       
        $connector_actividad=array(
            "connector" => "mongo",
            "version" => "dna2.1",
            "datastoreref" => "dna3",
            "itemsubjectref" => "container.actividades_pacc_11",
             "query" =>array("id" => null)
            );
            
            $resourceId=null;
            $silent=true;
            
        foreach($actividades as $id_actividad){
            $actividad=$this->app->getall($id_actividad,'container.actividades_pacc_11');
            $data=array();
            $data['id']=$id_actividad;
            $data['Empresas']=$case['data']['Empresas'];
            $data['Proyectos_pacc']=$case['data']['Proyectos_pacc'];
            //----para seguir el tracking de quien es el Iniciador original del caso
            $data['Initiator']=$Initiator;
            $data['Actividades_pacc_11']=$connector_actividad;
            $data['Actividades_pacc_11']['query']['id']=new MongoInt64($id_actividad);
            $caseactividad=$this->bpm->gen_case('pacc1SDE',$idcase.'-SDE-'.$actividad['6241'],$data);
            $this->bpm->engine->Startcase('model', 'pacc1SDE', $caseactividad, true);
            $ids_actividades[]=$caseactividad;
            $this->engine->Run('model', 'pacc1SDE', $caseactividad,$resourceId,$silent);
            
        }
        // $this->engine->Run('model', 'pacc1SDE', $ids_actividades[0],null,true);
        // $this->engine->Run('model', 'pacc1SDE', $ids_actividades[0]);
        
    }
 
    /**
     * Funcion para salida de KPI proyectos pre-aprobados
     * @todo customizar valores según MJL
     */ 
    
    function imprimir_proyecto($idwf, $idcase, $token, $id = null) {

        $this->user->authorize();
        $this->load->model('bpm/bpm');
        $this->load->model('dna2/dna2old');
        $dna2url = $this->dna2old->get('url');
//         if ($id) {
//             $url = $dna2url . "frontcustom/284/proyecto_fondyf_preA_new.php?id=$id&idwf=$idwf&case=$idcase&token=$token";         
//         } else {
//             show_error('El Caso no tiene id de proyecto');
//         }
//         $url = $this->bpm->gateway($url);
//         redirect($url);
        if ($id) {
            $todo = $id . '&idwf=' . $idwf . '&case=' . $idcase . '&token=' . $token;
//                <p align='left'>2. <a href="{$dna2url}frontcustom/290/cartacompromiso1-1.php?id=$todo" target="_blank">Carta compromiso</a></p>
            echo <<<BLOCK
                <p align='left'>1. <a href="{$dna2url}frontcustom/290/pacc13.externo2016.print.php?id=$todo" target="_blank">Imprimir del Plan de Negocio</a></p>
BLOCK;
        } else {
            echo 'div class="alert alert-success" role="alert">El Caso no tiene id de proyecto</div>';
        }
    }
}