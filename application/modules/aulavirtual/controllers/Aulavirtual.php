<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * expertos
 *
 * Description of the class expertos
 *
 * @author Juan Ignacio Borda <juanignacioborda@gmail.com>
 *         @date Jul 18, 2014
 */
class Aulavirtual extends MX_Controller {
    //--define el token que guarda la data consolidada para buscadores etc
    public $consolida_resrourceId='oryx_6772A7D9-3D05-4064-8E9F-B23B4F84F164';

    function __construct() {
        parent::__construct();
        $this->load->model('bpm/Kpi_model');        
        $this->load->model('menu/menu_model');
        $this->load->model('app');
        $this->load->model('bpm/bpm');
        $this->load->module('bpm/kpi');
        $this->user->isloggedin();
        // ---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
        // ----LOAD LANGUAGE
        $this->lang->load('library', $this->config->item('language'));
        $this->idu = (int) $this->session->userdata('iduser');
        $this->load->library('pagination');
        $this->load->library('dashboard/ui');
        $this->load->library('upload');
        
        /* GROUP */
        $user = $this->user->get_user($this->idu);

        $this->id_group = ($user->{'group'});
    }

    function Index($debug=false) {
        Modules::run('dashboard/dashboard', 'aulavirtual/json/aulavirtual.json',$debug, $extraData);
       // Modules::run('dashboard/dashboard', 'fondosemilla/json/semilla_proyectos.json',$debug);
    }
    function formulario(){
        $data['lang']= $this->lang->language;
    	$data['base_url'] = base_url();
        $data['module_url'] = $this->module_url;        
        //---4BPM
        $segments = $this->uri->segment_array();
        $data['idcase']=(isset($segments[3]))?$segments[3]:'';
        $data['token']=(isset($segments[4]))?$segments[4]:'';
        return $this->parser->parse('formulario', $data, true, true);
    }
    
    
    function process(){
        $data = $this->input->post();
        $data['files_url'] = FCPATH.'images/user_files/'.$user->idu.'/'.$data['idwf'].'/'.$data['idcase'];

        if (!file_exists(FCPATH.'images/user_files/'.$user->idu.'/'.$data['idwf'].'/'.$data['idcase'])){
             @mkdir(FCPATH.'images/user_files/'.$user->idu.'/'.$data['idwf'].'/'.$data['idcase'],0775,true);
        }
        
        
        
        $this->upload->initialize(array(
       // "file_name"     => array($_FILES['input-file-preview_userfiles']['name'][0],$_FILES['input-file-preview_userfiles']['name'][1],$_FILES['input-file-preview_userfiles']['name'][2]),
        'allowed_types'   => "pdf|PDF",
        'overwrite'       => FALSE,
        'max_size'        => "100000",  //
        'upload_path'     => FCPATH.'images/user_files/'.$user->idu.'/'.$data['idwf'].'/'.$data['idcase'].'/'
        ));
        
        if($_FILES){
		    $this->upload->do_upload('userfile');
            $error=$this->upload->display_errors();
        }
        
        var_dump($data, $data['files_url']);
        exit;
        
        
		if($error==null){
				 $renderData['data'] = $this->upload->data();
				 $renderData['ok'] = 'ok';

				 if($this->Model_aulavirtual->detalle_inscripcion($data['idcase'],$data['idwf'])){
				      $inscripcion['idcase'] = $data['idcase'];
				      $inscripcion['idwf'] = $data['idwf'];
				      $this->Model_aulavirtual->update_inscripcion($data,$inscripcion);
				 }else{
				      $this->Model_aulavirtual->insert_inscripcion($data);
				 }/**
				  * Si viene de BPM devolvemos el flujo al BPM
				  */
				 if(isset($data['token']) && isset($data['idcase'])){
				    if($data['token']<>'' && $data['idcase']<>''){
				        $token=$this->bpm->get_token_byid($data['token']);
				        $resourceId=$token['resourceId'];
				        $idwf=$token['idwf'];
				        $idcase=$token['case'];
				        $redir = $this->base_url."bpm/engine/run_post/model/$idwf/$idcase/$resourceId";
				        $this->load->helper('url');
				        // echo anchor($redir);
				        redirect($redir);
				    }
				 }
		}        
        

        
    }
    
    
}

/* End of file crefis */
    /* Location: ./system/application/controllers/welcome.php */
