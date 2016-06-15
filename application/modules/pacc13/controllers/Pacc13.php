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

class pacc13 extends MX_Controller {

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
  
    function asignar_evaluador($idwf, $idcase, $tokenId) {
        $this->load->library('parser');
        $this->load->model('user/group');
        $this->load->model('bpm/bpm');
        $case = $this->bpm->get_case($idcase, $idwf);
        $renderData = $this->bpm->load_case_data($case, $idwf);
        //----tomo evaluador del caso
        $evaluador = $renderData['Proyectos_pacc']['6096'][0];
        //----token que hay que finalizar 
         $src_resourceId = 'oryx_A150EBF2-8F30-4631-B04B-90DBDB019C41';
        // ---Token de pp asignado
        $lane_resourceId = 'oryx_295810F2-8C34-4D03-80F8-7B5C371381B8';

        $url = $this->base_url . "bpm/engine/assign/model/$idwf/$idcase/$src_resourceId/$lane_resourceId/$evaluador";

        redirect($url);
    }

    function asignar_evaluador_ppf($idwf, $idcase, $tokenId) {
        $this->load->library('parser');
        $this->load->model('user/group');
        $this->load->model('bpm/bpm');
        $case = $this->bpm->get_case($idcase, $idwf);
        $renderData = $this->bpm->load_case_data($case, $idwf);
        //----tomo evaluador del caso
        $evaluador = $renderData['Proyectos_pacc']['6096'][0];
        //----token que hay que finalizar
        $src_resourceId = 'oryx_42CD1D03-1250-4CA5-9868-4498DB9D498B';
        // ---Token de pp asignado (Lane)
        $lane_resourceId = 'oryx_AD0108D7-CA5D-4989-845E-CBC0E6158CF3';

        $url = $this->base_url . "bpm/engine/assign/model/$idwf/$idcase/$src_resourceId/$lane_resourceId/$evaluador";

        redirect($url);
    }

    /**
     * Asigna un evaluador para una solicitud de desembolso
     */ 
    
    function asignar_evaluador_sde($idwf, $idcase, $tokenId) {
        $this->load->library('parser');
        $this->load->model('user/group');
        $this->load->model('bpm/bpm');
        $case = $this->bpm->get_case($idcase, $idwf);
        $renderData = $this->bpm->load_case_data($case, $idwf);
        //----tomo evaluador administrativo
        $evaluador_admin = $renderData['Proyectos_pacc']['7106'][0];
        //----tomo evaluador técnico
        $evaluador = $renderData['Proyectos_pacc']['6096'][0];
        //----token que hay que finalizar (self)
        $src_resourceId = 'oryx_43E6BB74-5545-4CAB-BD71-3F3B42533211';
        // ---Token de pp asignado (Lane)
        $lane_resourceId = 'oryx_0DA9E38A-92D8-4B19-A948-46CEF3168613';
        
        $url = $this->base_url . "bpm/engine/assign/model/$idwf/$idcase/$src_resourceId/$lane_resourceId/$evaluador";

        redirect($url);
    }

    function asignar_evaluador_administrativo_sde($idwf, $idcase, $tokenId) {
        $this->load->library('parser');
        $this->load->model('user/group');
        $this->load->model('bpm/bpm');
        $case = $this->bpm->get_case($idcase, $idwf);
        $renderData = $this->bpm->load_case_data($case, $idwf);
        //----tomo evaluador administrativo
        $evaluador = $renderData['Proyectos_pacc']['7106'][0];
        //----token que hay que finalizar (self)
        $src_resourceId = 'oryx_BD7F84C3-73FE-48E0-831F-DEB0B9F78DCC';
        // ---Token de Lane asignado
        $lane_resourceId = 'oryx_CD23C511-FAE2-4549-8D26-2182224D770F';
        
        $url = $this->base_url . "bpm/engine/assign/model/$idwf/$idcase/$src_resourceId/$lane_resourceId/$evaluador";

        redirect($url);
    }

    function clone_case($from_idwf, $to_idwf, $idcase) {
        $this->load->model('bpm/bpm');
        $this->load->module('bpm/engine');
        $case = $this->bpm->get_case($idcase, $from_idwf);
        $case_to = $this->bpm->get_case($idcase, $to_idwf);
        if (!$case_to) {

            $this->bpm->gen_case($to_idwf, $idcase);
            $case_to = $this->bpm->get_case($idcase, $to_idwf);
            $case_to['data'] = $case['data'];
            $case_to['iduser'] = $case['iduser'];
            $case_to = $this->bpm->save_case($case_to);
            $this->engine->Startcase('model', $to_idwf, $idcase);
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
            $this->engine->Run('model', $to_idwf, $idcase);
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
     
    function create_SDE($idwf,$idcase,$suffix){
        $this->load->model('bpm/bpm');
        $this->load->model('app');
        $this->load->module('bpm/engine');
        $case = $this->bpm->get_case($idcase, $idwf);
        $id=$case['data']['Proyectos_pacc']['query']['id'];
        $id_empresa=$case['data']['Empresas']['query']['id'];
        
        $data['Empresas']=$case['data']['Empresas'];
        $data['Proyectos_pacc']=$case['data']['Proyectos_pacc'];
        
        $caserendicion=$this->bpm->gen_case('pacc3SDAREND',$idcase.'-'.$suffix,$data);
        $this->bpm->engine->Startcase('model', 'pacc3SDAREND', $caserendicion, true);
        $resourceId=null;
        $silent=true;
        $this->engine->Run('model', 'pacc3SDAREND', $caserendicion,$resourceId,$silent);
        
    }


}
