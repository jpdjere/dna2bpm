<?php


if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * genias
 *
*/
class Seminarios extends MX_Controller {


    function __construct() {
        parent::__construct();
        //----habilita acceso a todo los metodos de este controlador
        $this->user->authorize('modules/genias/controllers/genias');
        $this->load->config('config');
        $this->load->library('parser');
        $this->load->library('ui');
        $this->load->model('app');
        $this->load->model('user/user');
        $this->load->model('bpm/bpm');
        $this->load->model('user/rbac');
        $this->load->model('genias/genias_model');
        $this->load->helper('genias/tools');

        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . 'genias/';
        //----LOAD LANGUAGE
        $this->lang->load('library', $this->config->item('language'));

        // IDU : Chequeo de sesion
        $this->idu = (int) $this->session->userdata('iduser');
        if (!$this->idu) {
            header("$this->module_url/user/logout");
            exit();
        }

        ini_set('xdebug.var_display_max_depth', 100);
    }
    
    // ========= RENDER =========
    
    
    function render($file, $customData) {
    	$this->load->model('user/user');
    	$this->load->model('msg');
    	$this->load->language('inbox');
    	$cpData['lang'] = $this->lang->language;
    	$segments = $this->uri->segment_array();
    	$cpData['nolayout'] = (in_array('nolayout', $segments)) ? '1' : '0';
    	$cpData['theme'] = $this->config->item('theme');
    	$cpData['base_url'] = $this->base_url;
    	$cpData['module_url'] = $this->module_url;
    	$cpData['global_js'] = array(
    			'base_url' => $this->base_url,
    			'module_url' => $this->module_url,
    			'idu' => $this->idu
    	);
    	$user = $this->user->get_user($this->idu);
    	$cpData['user'] = (array) $user;
    	$cpData['isAdmin'] = $this->user->isAdmin($user);
    	$cpData['username'] = $user->lastname . ", " . $user->name;
    	$cpData['usermail'] = $user->email;
    	// Profile
    	//$cpData['profile_img'] = get_gravatar($user->email);
    
    	$cpData['gravatar'] = (isset($user->avatar)) ? $this->base_url . $user->avatar : get_gravatar($user->email);
    	$cpData['genia'] = $this->get_genia('nombre');
    	$cpData['rol'] = $this->get_genia('rol');
    	$cpData['rol_icono'] = ($cpData['rol'] == 'coordinador') ? ('fa fa-group') : ('fa fa-user');
    
    	// Listado de genias de donde soy user
    	$mygenias = $this->get_genia();
    	$cpData['genias'] = $mygenias['genias'];
    	$cpData = array_replace_recursive($customData, $cpData);
    
    	/* Inbox Count MSgs */
    	$mymgs = $this->msg->get_msgs($this->idu);
    	$cpData['inbox_count'] = $mymgs->count();
    
    	// offline mark
    	$cpData['is_offline'] = ($this->uri->segment(3) == 'offline') ? ('offline') : ('');
    
    	$this->ui->compose($file, 'layout.php', $cpData);
    }
    
    
    
	
	function Index(){
		$customData = array();
		$customData['base_url'] = base_url();
		$customData['module_url'] = base_url() . 'genias/';
		$customData['js'] = array($this->module_url . "assets/jscript/seminarios.js" => 'Seminarios JS', $this->module_url . "assets/jscript/jquery-validate/jquery.validate.min_1.js" => 'Validate');
		$customData['css'] = array($this->module_url . "assets/css/dashboard.css" => 'Dashboard CSS');

		$this->render('seminarios', $customData);
		
	}
	
	// Get 
	function get_genia($attr = null) {
	
		$genia = $this->genias_model->get_genia($this->idu);
		if ($attr == 'rol') {
			return $genia['rol'];
		} else {
			return $genia;
		}
	}
	
	
	
	
}// Class

