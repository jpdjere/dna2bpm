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
        $this->load->model('Model_aulavirtual');
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

    function Index($debug = false, $extraData = null) {
        Modules::run('dashboard/dashboard', 'aulavirtual/json/aulavirtual.json',$debug, $extraData);
       // Modules::run('dashboard/dashboard', 'fondosemilla/json/semilla_proyectos.json',$debug);
    }
    
    function Confirmacion($debug = false, $extraData = null) {
        Modules::run('dashboard/dashboard', 'aulavirtual/json/confirmacion.json',$debug, $extraData);
       // Modules::run('dashboard/dashboard', 'fondosemilla/json/semilla_proyectos.json',$debug);
    }
    
    
    function formulario(){
        $user = $this->user->get_user($this->idu);
        $data['lang']= $this->lang->language;
    	$data['base_url'] = base_url();
        $data['module_url'] = $this->module_url;
        $data['idu'] = $user->idu;
        //---4BPM
        $segments = $this->uri->segment_array();

        return $this->parser->parse('formulario', $data, true, true);
    }
    
    function process(){
		$this->load->helper('url');
        $data = $this->input->post();
        $user = $this->user->get_user($this->idu);
        $data['files_url'] = FCPATH.'images/user_files/'.$user->idu;

        if (!file_exists(FCPATH.'images/user_files/'.$user->idu)){
             @mkdir(FCPATH.'images/user_files/'.$user->idu,0775,true);
        }
        
        $this->upload->initialize(array(
       // "file_name"     => array($_FILES['input-file-preview_userfiles']['name'][0],$_FILES['input-file-preview_userfiles']['name'][1],$_FILES['input-file-preview_userfiles']['name'][2]),
        'allowed_types'   => "pdf|PDF",
        'overwrite'       => true,
        'max_size'        => "100",  //
        'upload_path'     => FCPATH.'images/user_files/'.$user->idu.'/'
        ));
        
        if($_FILES){
		    $this->upload->do_upload('userfile');
            $error=$this->upload->display_errors();
        }
        
		if($error==null){
			$renderData['data'] = $this->upload->data();
			$this->Model_aulavirtual->insert_inscripcion($data);
            $extraData['alerts'] = '<div class="alert alert-success"><strong>Success!</strong> Su inscripción fue registrado con éxito</div>';
		    $this->Confirmacion(false, $extraData);
		}else{
        $extraData['alerts'] = '<div class="alert alert-danger"><strong>Aviso!</strong> Su inscripción no pudo ser registrada.'.$error.'</div>';   
		$this->Index(false, $extraData);
		}        
        
        
    }

}

/* End of file crefis */
    /* Location: ./system/application/controllers/welcome.php */
