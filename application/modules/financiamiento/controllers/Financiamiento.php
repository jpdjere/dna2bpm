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
    
    function test(){
        $idcase="AMCB";
        $token="576aaaa13312591d068b456c";
        
        $nombre_clase="Mongowrapper";
        spl_autoload_register(function ($nombre_clase) {
            include $nombre_clase . '.php';
        });
        $Mongo  = new Mongowrapper();
        $db=$Mongo->selectdb("formentrada");
        $collection=$db->selectCollection("container.formulario_entrada");
        $query=array('idwf' => "form_entrada", "idcase"=>$idcase, "token"=>$token);
        $modify=array('$set' => array('rbt' => true));
        $cursor=$collection->findAndModify($query, $modify);
        foreach ($cursor as $doc) {
            var_dump($doc);
        }
    }
    
    function Index() {
        header('Location: '.$this->base_url.'bpm/engine/newcase/model/form_entrada');
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
        //Guarda los datos en mongo y devuelve el flujo al BPM
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
    
    function mostrar_respuesta_pyme_bancario($idwf, $idcase, $token){
        echo $idwf, $idcase, $token, $programas; exit();
        
        $customData['base_url'] = $this->base_url;
        $customData['idwf'] = $idwf;
        $customData['idcase'] = $idcase;
        $customData['token'] = $token;
        echo $this->parser->parse('financiamiento/respuesta_pyme_bancario',$customData,true,true);
    }
}

/*$arraytest=array();
$arraytest["programa"]="RBT";
$CI->bpm->update_case("form_entrada", "UUQM", $arraytest);
//echo $DS->idwf;
//echo "hola";
return true;*/
/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */