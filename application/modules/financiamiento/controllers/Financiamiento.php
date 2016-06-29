<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * test
 * 
 * Description of the class
 * 
 * @author Sebastian Blazquez <seby_1996@hotmail.com>
 */
class Financiamiento extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
        $this->load->model('model_financiamiento');
        $this->load->library('parser');
        $this->load->model('bpm/bpm');
    }
    
    function Index() {
        //$this->session->set_userdata(array('iduser'=>756148209, 'loggedin'=>true));
        redirect($this->base_url.'bpm/engine/newcase/model/form_entrada');
    }
    
    function mostrar_formulario($idwf, $idcase, $token){
        $customData['base_url'] = $this->base_url;
        $customData['idwf'] = $idwf;
        $customData['idcase'] = $idcase;
        $customData['token'] = $token;
        echo $this->parser->parse('financiamiento/form',$customData,true,true);
    }
    
    function continuar_flujo(){
        $datos_formulario=$this->input->post();
        $this->guardar_datos_formulario($datos_formulario);
        $this->devolver_flujo_bpm($datos_formulario);
    }
    
    function devolver_flujo_bpm($datos_formulario){
        //Devuelve el flujo al BPM
        $token=$this->bpm->get_token_byid($datos_formulario['token']);
		$resourceId=$token['resourceId'];
        $idwf=$token['idwf'];
        $idcase=$token['case'];
        $redir = $this->base_url."bpm/engine/run_post/model/$idwf/$idcase/$resourceId";
        redirect($redir);
    }
    
    function guardar_datos_formulario($datos_formulario){
        //Guarda los datos del formulario
        return $this->model_financiamiento->guardar_datos_formulario($datos_formulario);
    }

    function mostrar_formulario_bancos_pyme_bancario($idwf, $idcase, $token){
        //Muestra la respuesta para los que clasificaron en pyme no bancario
        $customData['base_url'] = $this->base_url;
        $customData['idwf'] = $idwf;
        $customData['idcase'] = $idcase;
        $customData['token'] = $token;
        $programas = $this->model_financiamiento->devolver_programas_pyme_bancario($idwf, $idcase);
        if(!$programas){
            $customData['programas']="Otros";
        }else{
            foreach($programas as $programa){
                $customData['programas']=" ".$customData['programas'].$programa." ";
            }
        }
        echo $this->parser->parse('financiamiento/form_pyme_bancario',$customData,true,true);
    }
    
    function devuelve_programas_pyme_bancario(){
        //Devuelve los programas para los quu clasificaroon en pyme no bancario
        $datos_caso=$this->input->post();
        $result = $this->model_financiamiento->devolver_programas_pyme_bancario($datos_caso["idwf"], $datos_caso["idcase"]);
        echo json_encode($result);
    }
    
    function guardar_bancos(){
        //Guarda los datos delbanco ingresados por el banco y devuelve el flujo al bpm
        $datos_formulario=$this->input->post();
        $this->model_financiamiento->actualizar_caso($datos_formulario['idwf'], $datos_formulario['idcase'], $datos_formulario);
        $this->devolver_flujo_bpm($datos_formulario);
    }


/*************************RESPUESTAS*************************/
    function respuesta($customData){
        return $this->parser->parse('financiamiento/respuesta',$customData,true,true);
    }

    function mostrar_respuesta_pyme_bancario($idwf, $idcase, $token){
        //Muestra las respuestas para los programas pyme bancarios
        $programas = $this->model_financiamiento->devolver_bancos_pyme_bancario($idwf, $idcase);
        $customData['base_url'] = $this->base_url;
        
        $rbt = $programas['rbt'];
        $mi_galpon = $programas['mi_galpon'];
        $parques = $programas['parques'];
        
        if(isset($mi_galpon)){
          $customData['respuestas'].= $this->load->view("financiamiento/respuestas/mi_galpon.htm", '', true);
        }
        if(isset($parques)){
          $customData['respuestas'].= $this->load->view("financiamiento/respuestas/parques.htm", '', true);
        }
        if(isset($rbt)){
          if($rbt==0){
            $customData['respuestas'].= $this->load->view("financiamiento/respuestas/rbt_bna.htm", '', true);
          }else{
            $customData['respuestas'].= $this->load->view("financiamiento/respuestas/rbt_bice.htm", '', true);
          }
        }
        if(!isset($customData['respuestas'])){
          $customData['respuestas'].= $this->load->view("financiamiento/respuestas/otros_pyme_banc.htm", '', true);
        }
        echo $this->respuesta($customData);
    }
    
    function mostrar_respuesta_fonapyme($tipo_caso){
        //Muestra las respuestas para los programas fonapyme
        $customData['base_url'] = $this->base_url;
        $customData['respuestas'] = $this->load->view("financiamiento/respuestas/fona_$tipo_caso.htm", '', true);
        echo $this->respuesta($customData);
    }
    
}




