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
        //$this->session->set_userdata(array('iduser'=>756148209, 'loggedin'=>true));   //local
        //$this->session->set_userdata(array('iduser'=>2013235470, 'loggedin'=>true));  //test
        //$this->session->set_userdata(array('iduser'=>1816360748, 'loggedin'=>true));  //produccion
        redirect($this->base_url.'bpm/engine/newcase/model/form_entrada');
    }
    
    function mostrar_formulario($idwf, $idcase, $token){
        if(!(isset($idwf)&&isset($idcase)&&isset($token))){
            $this->Index();
        }
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
        try{
            $token=$this->bpm->get_token_byid($datos_formulario['token']);
		}catch(MongoException $e){
            $this->mensaje_salida();
		}
		$resourceId=$token['resourceId'];
        $idwf=$token['idwf'];
        $idcase=$token['case'];
        $this->run_post_forge($idwf, $idcase, $resourceId);
    }
    
    function guardar_datos_formulario($datos_formulario){
        //Guarda los datos del formulario
        return $this->model_financiamiento->guardar_datos_formulario($datos_formulario);
    }

    function mostrar_formulario_bancos_pyme_bancario($idwf, $idcase, $token){
        //Muestra la respuesta para los que clasificaron en pyme no bancario
        if(!(isset($idwf)&&isset($idcase)&&isset($token))){
            $this->Index();
        }
        $customData['base_url'] = $this->base_url;
        $customData['idwf'] = $idwf;
        $customData['idcase'] = $idcase;
        $customData['token'] = $token;
        $programas = $this->model_financiamiento->devolver_programas_pyme_bancario($idwf, $idcase);
        if(!$programas){
            $datos_formulario['idwf']=$idwf;
            $datos_formulario['idcase']=$idcase;
            $datos_formulario['token']=$token;
            $datos=array('programa'=>array('otros'));
            $this->model_financiamiento->actualizar_caso($idwf, $idcase, $datos);
            $this->devolver_flujo_bpm($datos_formulario);
        }else{
            $customData['programas']=$this->devolver_programas_encadenados($programas);
        }
        echo $this->parser->parse('financiamiento/form_pyme_bancario',$customData,true,true);
    }
    
    function devuelve_programas_pyme_bancario(){
        //Devuelve los programas para los quu clasificaroon en pyme no bancario
        $datos_caso=$this->input->post();
        $result = $this->model_financiamiento->devolver_programas_pyme_bancario($datos_caso["idwf"], $datos_caso["idcase"]);
        echo json_encode($result);
    }
    
    function guardar_datos_extra(){
        //Guarda los datos delbanco ingresados por el banco y devuelve el flujo al bpm
        $datos_formulario=$this->input->post();
        $this->model_financiamiento->actualizar_caso($datos_formulario['idwf'], $datos_formulario['idcase'], $datos_formulario);
        $this->devolver_flujo_bpm($datos_formulario);
    }

    function devolver_programas_encadenados($programas){
        //Recibe los programas en un array y devuelve un string con los programas concatenados
        foreach($programas as $clave=>$programa){
            if($programa=='parques'){
                $programas[$clave]="Parques";
            }elseif($programa=='rbt'){
                $programas[$clave]="Régimen de bonificación de tasas(RBT)";
            }elseif($programa=='mi_galpon'){
                $programas[$clave]="Mi Galpón";
            }
        }
        if(count($programas)==3){
            return sprintf("%s, %s y %s.", $programas[0], $programas[1], $programas[2]);
        }elseif(count($programas)==2){
            return sprintf("%s y %s.", $programas[0], $programas[1]);
        }elseif(count($programas)==1){
            return sprintf("%s.", $programas[0]);
        }else{
            return '';
        }
    }
    
    function volver(){
        $datos_formulario=$this->input->post();
        $this->devolver_flujo_bpm($datos_formulario);
    }
    
    function mensaje_salida(){
        die($this->parser->parse('financiamiento/respuesta_error',array("base_url"=>$this->base_url),true,true));
    }

/*************************RESPUESTAS*************************/
    function respuesta($customData){
        return $this->parser->parse('financiamiento/respuesta',$customData,true,true);
    }
    
    //Bancario
    function mostrar_respuesta_pyme_bancario($idwf, $idcase, $token){
        //Muestra las respuestas para los programas pyme bancarios
        if(!(isset($idwf)&&isset($idcase)&&isset($token))){
            $this->Index();
        }
        $programas = $this->model_financiamiento->devolver_bancos_pyme_bancario($idwf, $idcase);
        $customData['base_url'] = $this->base_url;
        $customData['idcase'] = $idcase;
        $customData['idwf'] = $idwf;
        $customData['token'] = $token;
        
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

    //FonaPyme
    function mostrar_respuesta_fonapyme($tipo_caso, $idwf, $idcase, $token){
        if(!(isset($tipo_caso)&&isset($idwf)&&isset($idcase)&&isset($token))){
            $this->Index();
        }
        //Muestra las respuestas para los programas fonapyme
        $customData['base_url'] = $this->base_url;
        $customData['idcase'] = $idcase;
        $customData['idwf'] = $idwf;
        $customData['token'] = $token;
        $customData['respuestas'] = $this->load->view("financiamiento/respuestas/fona_$tipo_caso.htm", '', true);
        echo $this->respuesta($customData);
    }
    
    //Gran Empresa
    function mostrar_respuesta_gran_empresa($tipo_empresa, $idwf, $idcase, $token){
        if(!(isset($tipo_empresa)&&isset($idwf)&&isset($idcase)&&isset($token))){
            $this->Index();
        }
        //Muestra las respuestas para los programas fonapyme
        $customData['base_url'] = $this->base_url;
        $customData['idcase'] = $idcase;
        $customData['idwf'] = $idwf;
        $customData['token'] = $token;
        $customData['respuestas'] = $this->load->view("financiamiento/respuestas/gran_empresa_$tipo_empresa.htm", '', true);
        echo $this->respuesta($customData);
    }

/*************************FUNCIONES BPM*************************/
    function bindArrayToObject($array) {
        $return = new stdClass();
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                $return->$k = $this->bindArrayToObject($v);
            } else {
                $return->$k = $v;
            }
        }
        return $return;
    }
    
    function run_post_forge($idwf, $case, $resourceId){
        $this->finish_user_tasks($idwf, $case);
        $resourceId = urldecode($resourceId);
        $mywf = $this->bpm->load($idwf, true);
        $mywf ['data'] ['idwf'] = $idwf;
        $mywf ['data'] ['case'] = $case;
        $wf = $this->bindArrayToObject($mywf ['data']);
        if ($resourceId) {
            $shape = $this->bpm->get_shape($resourceId, $wf);
            if ($shape) {
                $this->bpm->movenext($shape, $wf);
            } else {
                show_error("The shape $resourceId doesn't exists anymore");
            }
        }
        // ---Redir the browser to engine Run
        $redir = "bpm/engine/run/model/$idwf/$case";
        redirect($this->base_url . $redir);
    }
    
    function finish_user_tasks($idwf, $case){
        $thisCase = $this->bpm->get_case($case, $idwf);
        $locked = (isset($thisCase ['locked'])) ? $thisCase ['locked'] : false;
        if ($locked) {
            $user_lock = (array) $this->user->get_user($thisCase ['lockedBy']);
            $msg_data = array(
                'user_lock' => $user_lock ['name'] . ' ' . $user_lock ['lastname'],
                'time' => date($this->lang->line('dateTimeFmt'), strtotime($thisCase ['lockedDate']))
            );
            $this->show_modal($this->lang->line('lock'), $this->parser->parse_string($this->lang->line('caseLocked'), $msg_data));
        } else {
            //---check Exists.
            $mywf = $this->bpm->load($idwf, true);
            if($mywf){
                $mywf ['data'] ['idwf'] = $idwf;
                $mywf ['data'] ['case'] = $case;
                $mywf ['data'] ['folder'] = $mywf ['folder'];
                $wf = $this->bindArrayToObject($mywf ['data']);
                $filter = array(
                    'idwf' => $idwf,
                    'case' => $case,
                    'status' => array('$in'=>array('user'))
                );
                $open = $this->bpm->get_tokens_byFilter($filter);
                $i = 1;
                while ($i <= 100 and $open = $this->bpm->get_tokens_byFilter($filter)) {
                    $i ++;
                    foreach ($open as $token) {
                        $resourceId = $token ['resourceId'];
                        $shape = $this->bpm->get_shape($resourceId, $wf);
                        if (!$shape) {
                            show_error("Can't find $resourceId in model: engine line " . __LINE__);
                        }
                        $token['status']='finished';
                        $this->bpm->save_token($token);
                    }
                }
            } else {
                show_error("Model: $idwf doesn't exitst contact Administrator");
            }
        }
    }
}




