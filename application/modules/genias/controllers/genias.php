<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * genias
 *
 */
class Genias extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->config('config');
        $this->load->library('parser');
        $this->load->library('ui');
        $this->load->model('app');
        $this->load->model('user/user');
        $this->load->model('user/rbac');
        $this->load->model('genias/genias_model');


        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . 'genias/';
        $this->user->authorize();
        //----LOAD LANGUAGE
        $this->lang->load('library', $this->config->item('language'));
        $this->idu = (float) $this->session->userdata('iduser');
    }
    

    
    function Index() {

        $customData = array();
        $customData['base_url'] = base_url();
        $customData['module_url'] = base_url() . 'genias/';


        $customData['js'] = array($this->module_url . "assets/jscript/dashboard.js" => 'Dashboard JS');
        
        // Goals
        $customData['goals']=(array)$this->genias_model->goals_get($this->idu);
             
        // Projects
        $projects=$this->genias_model->config_get('projects');
        $customData['projects']=$projects['items'];

        foreach($this->genias_model->goals_get($this->idu) as $goal){
            foreach($customData['projects'] as $current){
                if($current['id']==$goal['proyecto']) $goal['proyecto_name']=$current['name'];
            }
            $goal['cumplidas']=6;
            $metas_cumplidas=($goal['cumplidas']==$goal['cantidad'])?(true):(false);
            $goal['class']=($metas_cumplidas)?('well'):('alert alert-info');

            $days_back=date('Y-m-d',strtotime("-5 day"));
            if(($goal['hasta']<$days_back)&&(!$metas_cumplidas))$goal['class']='alert alert-error';
            $customData['goals'][]=$goal;
        }


        $this->render('dashboard', $customData);
        
        
    }

    function render($file, $customData) {

        $this->load->model('user/user');
        $this->user->authorize();
        $cpData = $this->lang->language;
        $segments = $this->uri->segment_array();
        $cpData['nolayout'] = (in_array('nolayout', $segments)) ? '1' : '0';
        //var_dump($level);
        $cpData['theme'] = $this->config->item('theme');
        $cpData['base_url'] = $this->base_url;
        $cpData['module_url'] = $this->module_url;


        $cpData['global_js'] = array(
            'base_url' => $this->base_url,
            'module_url' => $this->module_url,
        );
        $user = $this->user->get_user($this->idu);
        $cpData['user'] = (array) $user;
        $cpData['isAdmin'] = $this->user->isAdmin($user);
        $cpData['username']=$user->lastname.", ".$user->name;
        $cpData['username']=$user->email;
        // Profile 
        $cpData['profile_img']=$this->get_gravatar($user->email);

        $cpData+=$customData;
        $this->ui->compose($file, 'layout.php', $cpData);
    }
    //* ------ METAS ------ */
    
    function goals_new() {
        
        $this->user->authorize();
        $customData = $this->lang->language;
        $data=$this->input->post('data');
        $mydata=array(
            'idu'=>$this->idu     
        );  
        foreach($data as $k=>$v){
            $mydata[$v['name']]=$v['value'];
        }
                  
        $date = date_create_from_format('d-m-Y', $mydata['desde']);
        $mydata['desde']=date_format($date, 'Y-m-d');
        $date = date_create_from_format('d-m-Y', $mydata['hasta']);
        $mydata['hasta']=date_format($date, 'Y-m-d');

        $this->genias_model->goals_new($mydata);
    }

    

    function programs() {
        $this->user->authorize();
        $customData = $this->lang->language;
        $this->render('programs', $customData);
    }

    /* ------ TAREAS ------ */
    
    // Render page
    function tasks() {
        $this->user->authorize();
        $customData = $this->lang->language;
        $customData['css'] = array($this->module_url . "assets/css/tasks.css" => 'Genias CSS');    
        $projects=$this->genias_model->config_get('projects');
        $customData['projects']=$projects['items'];
        $this->render('tasks', $customData);
    }
    
    function add_task(){
        $this->user->authorize();
        $customData = $this->lang->language;
        $mydata=$this->input->post('data');


        foreach ($mydata as $k=>$v){
            echo "$k $v<br>";
        }
        //$this->genias_model->add_task($mydata);
    }
    
    
    
    /* ------ MAP ------ */
    // Render page
    function map() {
        $this->user->authorize();
        $customData = $this->lang->language;
        $this->render('map', $customData);
    }

    /* ------ SCHEDULER ------ */
    // Render page
    function scheduler() {
        $this->user->authorize();
        $customData = $this->lang->language;
        $customData['js'] = array($this->module_url . "assets/jscript/scheduler.js" => 'Inicio Scheduler JS');
        $customData['css'] = array($this->module_url . "assets/css/genias.css" => 'Genias CSS');      
        
        $projects=$this->genias_model->config_get('projects');
        $customData['projects']=$projects['items'];
        //print_r($customData['projects']);
	$year = date('Y');
	$month = date('m');
        
        $this->render('scheduler', $customData);

    }
    
    // Draw items 
    function scheduler_get_json() {

	echo json_encode(array(
	
		array(
			'id' => 111,
			'title' => "Event1",
			'start' => "2013-04-10",
			'end' => "2013-04-11"
		),
		
		array(
			'id' => 222,
			'title' => "Event2",
			'start' => "2013-04-20",
			'end' => "2013-04-21"
		)
	
	));
    }

    /* ------ CONTACTS ------ */
    // Render page
    
    function contacts() {
        $this->user->authorize();
        $customData = $this->lang->language;
        $this->render('contacts', $customData);
    }
    
        /* ------ ??? ------ */
    
    function Form() {        
       
        //echo $this->idu;   
        
        
        //---Libraries
        $this->load->library('parser');
        $this->load->library('ui');

        $cpData = $this->lang->language;
        $segments = $this->uri->segment_array();
        $cpData['theme'] = $this->config->item('theme');
        $cpData['base_url'] = $this->base_url;
        $cpData['module_url'] = $this->module_url;
        $cpData['title'] = 'Formulario Genias.';        
        
        
        $cpData['js'] = array(           
            $this->module_url . 'assets/jscript/ext.data.js' => 'Base Data',
            $this->module_url . 'assets/jscript/app.js' => 'Objetos Custom D!',
            $this->module_url . 'assets/jscript/ext.viewport.js' => '',              
        );

        $cpData['global_js'] = array(
            'base_url' => $this->base_url,
            'module_url' => $this->module_url,
        );
        $this->ui->makeui('ext.ui.php', $cpData);
    }   
     

    function App() {
        /* REMOTE */    
        echo '{"user":"'.$this->idu.'"}';
    }
    
    /* ------ CONFIG ------ */
    // Render page
    
    function config() {
        $this->user->authorize();
        $customData = $this->lang->language;
        $projects=$this->genias_model->config_get('projects');
        $customData['js'] = array($this->module_url . "assets/jscript/config.js" => 'Config JS');
        $customData['projects']=$projects['items'];

        $this->render('config', $customData);
    }
    
    // Change projects id from here
    function config_set_projects() {
        $this->user->authorize();
        $myProjects=$this->input->post('data');

        // Preparo array para la base
        $items=array();
        for($i=0;$i<count($myProjects);$i+=2){
            $items[]=array('id'=>(int)$myProjects[$i+1]['value'],'name'=>$myProjects[$i]['value']);
        }            
        $mydata=array('name'=>'projects','items'=>$items);

        $error= $this->genias_model->config_set($mydata);
        echo (is_null($error))?("Registro guardado"):("No se ha podido guardar el registro");

    }
    
    // Profile    
    function get_gravatar($email) {
    $code=md5( strtolower( trim( $email ) ) );
    if($str = @file_get_contents( "http://www.gravatar.com/$code.php" )){
    $profile = unserialize( $str );
        // Chequeo en Gravatar.com
        if ( is_array( $profile ) && isset( $profile['entry'] ) ){
            return($profile['entry'][0]['thumbnailUrl']);
        }
    }else{
            // Devuelvo el default
           return base_url() . 'genias/assets/images/avatar-hombre.jpg';

        }
    }



}// close


