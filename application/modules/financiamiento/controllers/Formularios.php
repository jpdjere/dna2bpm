<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Controlador principal del Módulo de Formularios
 * 
 * Este controlador ejecuta los Dashboards que contienen el registro de los formularios de inscripcion.
 * A su vez por cada dashboard se renderiza una vista con sus datos de autollenado correspondientes
 * 
 * @autor Menez Luciano
 * 

 * 
 * 
 */
class Formularios extends MX_Controller {

    function __construct() {
        parent::__construct();
        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
        $this->idu = $this->user->idu;
        $this->repo_path=FCPATH;
        $this->load->model('Model_inscripciones');
        $this->load->model('app');
        $this->load->helper('form');
        $this->load->helper('file');
        $this->load->library('parser');
     //   $this->load->library('user/ui');
        $this->load->library('Upload');
        //$this->load->library('MY_Upload');
        //---Output Profiler
        //$this->output->enable_profiler(TRUE);
    }
    function Index(){
        $this->user->authorize();
        $this->git_dashboard();
    }
    function git_dashboard(){
        $this->user->authorize();
        Modules::run('dashboard/dashboard', 'financiamiento/json/dashboard.json');
    }
    function prestamos_personales(){
        Modules::run('dashboard/dashboard', 'financiamiento/json/prestamos_personales.json');
    }
    function becas_bicentenario(){
        $ui2=$this->load->library('user/ui',null,'ui2');
        $data['title']="Becas Bicentenario";
    	$data['base_url'] = base_url();
        $data['module_url'] = $this->module_url;
        $this->ui2->compose('financiamiento/becas_bicentenario', 'perfil/bootstrap3.ui.php', $data);
    }
    
    function concurso_musica_intro(){
        $ui2=$this->load->library('user/ui',null,'ui2');
        $data['title']="Concurso de Música";
    	$data['base_url'] = base_url();
        $data['module_url'] = $this->module_url;
        $this->ui2->compose('formularios/intro_musica', 'perfil/bootstrap3.ui.php', $data);
    }
    
    function concurso_letras_intro(){
        $ui2=$this->load->library('user/ui',null,'ui2');
        $data['title']="Concurso de Letras";
    	$data['base_url'] = base_url();
        $data['module_url'] = $this->module_url;
        $this->ui2->compose('formularios/intro_letras', 'perfil/bootstrap3.ui.php', $data);
    }
    function beca_grupal(){
        Modules::run('dashboard/dashboard', 'formularios/json/beca_grupal.json');
        //---restauro panel
        $this->session->set_userdata('json', $this->config->item('default_dashboard'));
    }
    function beca_individual(){
        Modules::run('dashboard/dashboard', 'formularios/json/individual.json');
        //---restauro panel
        $this->session->set_userdata('json', $this->config->item('default_dashboard'));
    }

    function microcreditos(){
        Modules::run('dashboard/dashboard', 'formularios/json/microcreditos.json');
    }
    function confirmacion($data){
        Modules::run('dashboard/dashboard', 'formularios/json/confirmacion.json', false, $data);
    }
    function hipotecario(){
        Modules::run('dashboard/dashboard', 'formularios/json/hipotecario.json');
    }
    function subsidio(){
        Modules::run('dashboard/dashboard', 'formularios/json/subsidio.json');
    }
    function reglamento(){
        Modules::run('dashboard/dashboard', 'formularios/json/reglamento.json');
    }


#=== Concurso Letras

    function concurso_letras(){
        Modules::run('dashboard/dashboard', 'formularios/json/concurso_letras.json');
         //---restauro panel
        $this->session->set_userdata('json', $this->config->item('default_dashboard'));

    }

    function view_concurso_letras(){
        //---4BPM

        $segments = $this->uri->segment_array();
        $data['idcase']=(isset($segments[3]))?$segments[3]:'';
        $data['token']=(isset($segments[4]))?$segments[4]:'';
        
        $data['base_url'] = base_url();
        $data['module_url'] = $this->module_url;
        $data['tipo_inscripcion'] = "CL"; 
        $data+=(array) $this->user->get_user((int) $this->idu);
        return $this->parser->parse('concurso-letras', $data, true, true);
    }

#=== Concurso Musica

    function concurso_musica(){
        Modules::run('dashboard/dashboard', 'formularios/json/concurso_musica.json');
         //---restauro panel
        $this->session->set_userdata('json', $this->config->item('default_dashboard'));
    }
    function view_concurso_musica(){
        //---4BPM
        $segments = $this->uri->segment_array();
        $data['idcase']=(isset($segments[3]))?$segments[3]:'';
        $data['token']=(isset($segments[4]))?$segments[4]:'';
        
        $data['base_url'] = base_url();
        $data['module_url'] = $this->module_url;
        $data['tipo_inscripcion'] = "CM"; 
        $data+=(array) $this->user->get_user((int) $this->idu);
        return $this->parser->parse('concurso-musica', $data, true, true);
    }



    function view_prestamos_personales(){
        $data['tipo_inscripcion'] = "PP"; 
        $data+=(array) $this->user->get_user((int) $this->idu);
        return $this->parser->parse('prestamos-personales', $data, true, true);
    }
    
    function view_confirmacion(){
        return $this->parser->parse('confirmacion', $data, true, true);
    }
    function view_beca_grupal(){
        //---4BPM
        $segments = $this->uri->segment_array();
        $data['idcase']=(isset($segments[3]))?$segments[3]:'';
        $data['token']=(isset($segments[4]))?$segments[4]:'';
        
    	$data['base_url'] = base_url();
        $data['module_url'] = $this->module_url;
        $data['tipo_inscripcion'] = "BG"; 
        $data+=(array) $this->user->get_user((int) $this->idu);
        $disciplinas = $this->app->get_option(1001);
        $disciplina_principal = $disciplinas;
        if(isset($data['disciplina_principal'])){
            foreach($disciplina_principal['data'] as &$disciplina){
                if($disciplina['value']==$data['disciplina']){
                    $disciplina['checked']='checked';
                }    
            }
        }
        $data['disciplina_principal'] = $disciplina_principal['data'];        
        return $this->parser->parse('beca-grupal', $data, true, true);
    }
    function view_individual(){
        //---4BPM
        $segments = $this->uri->segment_array();
        $data['idcase']=(isset($segments[3]))?$segments[3]:'';
        $data['token']=(isset($segments[4]))?$segments[4]:'';
         //$this->lang->load('profile', $this->config->item('language'));
        $data['lang']= $this->lang->language;
    	$data['base_url'] = base_url();
        $data['module_url'] = $this->module_url;
        $data['disabled']=($disabled)?('disabled'):('');
        $data['error'] = "";
        $disciplinas = $this->app->get_option(1001);
        $disciplina_principal = $disciplinas;
        if(isset($data['disciplina_principal'])){
            foreach($disciplina_principal['data'] as &$disciplina){
                if($disciplina['value']==$data['disciplina']){
                    $disciplina['checked']='checked';
                }    
            }
        }
        $data['disciplina_principal'] = $disciplina_principal['data']; 
        $provincias = $this->app->get_option(39);
        $data['tipo_inscripcion'] = "I"; 
        $data+=(array) $this->user->get_user((int) $this->idu);

        return $this->parser->parse('individual', $data, true, true);
    }
    function view_microcreditos(){
        $renderData['tipo_inscripcion'] = "M"; 
        return $this->parser->parse('microcreditos', $renderData, true, true);
    }
    function view_hipotecario(){
        $renderData['tipo_inscripcion'] = "H"; 
        return $this->parser->parse('hipotecario', $renderData, true, true);
    }
    function view_subsidio(){
        $renderData['tipo_inscripcion'] = "S"; 
        $renderData['error'] = "";
        return $this->parser->parse('subsidio', $renderData, true, true);
    }
    
    function delete_user($id = 224293017){
        $this->user->delete($id);
    }

    function get($filter = null){
        $data = $this->Model_inscripciones->inscripciones_cargadas($filter);
        var_dump($data);
        exit;
    }
    
    function delete($filter = null){
        $data = $this->Model_inscripciones->borrar_inscripciones_db($filter);
        var_dump($data);
        exit;
    }

    function view_reglamento(){
        return $this->parser->parse('reglamento', $renderData, true, true);
    }
    
    function check_dni(){
        
        $rtn=true;
        if($this->input->post('dni')){
            $rs=$this->user->getbyidnumber($this->input->post('dni'));
            $rtn=(empty($rs))?false:true;
        }
        $this->output->set_content_type('json','utf-8');
        $this->output->set_output(json_encode($rtn));
    }

    function check_dni2($dni){
        $rtn=true;
        $data=$this->user->getbyidnumber($dni);
        $this->output->set_content_type('json','utf-8');
        $this->output->set_output(json_encode($data));
    }
    
    function check_inscripcion(){
        
        $rtn=true;
        if($this->input->post('idnumber')){
            $rs=$this->Model_inscripciones->check_dni_inscripcion($this->input->post('idnumber'));
            $rtn=(!empty($rs))?false:true;
        }
        
        $this->output->set_content_type('json','utf-8');
        $this->output->set_output(json_encode($rtn));
    } 
    
    public function do_upload(){
       
        $this->load->model('bpm/bpm');
        $data = $this->input->post();
        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        $user = $this->user->get_user((int) $this->idu);
        
        $renderData['css'] = array(
            $this->module_url . 'assets/css/formularios.css' => 'Funciones',
            );
            
        if (!file_exists(FCPATH.'images/user_files/'.$user->idu)){
             @mkdir(FCPATH.'images/user_files/'.$user->idu,0775,true);
        }
        //---cuento los files
        //SANITIZO LOS INPUTS DE FILES, SOLUCION HASTA QUE SE ME OCURRA ALGO MAS ELEGANTE
        $i = 0;
        foreach ($_FILES as $fields){
            foreach($fields['name'] as $nombres){
                if (!isset($nombres[$i])){
                    unset($_FILES['input-file-preview_userfiles']['name'][$i]);
                    unset($_FILES['input-file-preview_userfiles']['size'][$i]);
                    unset($_FILES['input-file-preview_userfiles']['error'][$i]);
                    unset($_FILES['input-file-preview_userfiles']['type'][$i]);
                    unset($_FILES['input-file-preview_userfiles']['tmp_name'][$i]);
                };
            $i++;    
            };
        }
        // FIN
        $cant_archivos=count($_FILES['input-file-preview_userfiles']['name']);
        
        $this->upload->initialize(array(
       // "file_name"     => array($_FILES['input-file-preview_userfiles']['name'][0],$_FILES['input-file-preview_userfiles']['name'][1],$_FILES['input-file-preview_userfiles']['name'][2]),
        'allowed_types'   => "gif|jpg|png|jpeg|JPG|PNG|JPEG|pdf|PDF",
        'overwrite'       => FALSE,
        'max_size'        => "100000",  //
        'upload_path'     => FCPATH.'images/user_files/'.$user->idu
        ));
        $error=null;
        if($cant_archivos){
            $this->upload->do_multi_upload('input-file-preview_userfiles');
            $error=$this->upload->display_errors();
        }
		var_dump($error);
		if($error==null){
                 $renderData['data'] = $this->upload->data();
                 $renderData['ok'] = 'ok';
				 $this->Model_inscripciones->insert_inscripciones($data);
				 /**
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
				 } else { 
				 $renderData['error'] = "Su Inscripcion fue registrada correctamente";
				 $renderData['user'] = $data;
				 $this->confirmacion($renderData);
				 }
				}
				else
				{
				$renderData['error'] = $this->upload->display_errors();
			    $this->confirmacion($renderData);
				}
				
}




}

