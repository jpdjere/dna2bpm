<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * test
 * 
 * Description of the class --
 * 
 * @author Juan Ignacio Borda <juanignacioborda@gmail.com>
 * @date    May 28, 2014
 */
class Lite extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->config('dashboard/config');
        $this->load->library('parser');

        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
        $this->user->authorize();
        //----LOAD LANGUAGE
        $this->lang->load('library', $this->config->item('language'));
        $this->idu = $this->user->idu;
    }

    function Index() {
        Modules::run('dashboard/dashboard', 'dashboard/json/lite.json');
    }
    

    
function lite(){

    $this->load->model('bpm/bpm');
    $this->load->model('msg');
     
     $data['base_url'] = $this->base_url;
     
     // Inbox
     $data['inbox_count']=true;
     $data['inbox_count_qtty']=count($this->msg->get_msgs_by_filter(array('to'=>$this->idu,'folder'=>'inbox','read'=>false)));
     $data['inbox_count_label_class']='success';
     
     // Tramites
     $data['tramites_count']=true;
     $data['tramites_count_label_class']='success';
     $data['tramites_count_qtty']=666;
     $data['tramites_extra']="---- Extra ";
     
    // Mis tramites
     $cases = $this->bpm->get_cases_byFilter(
                array(
            'iduser' => $this->idu,
            'status' => 'open',
                ), array(), array('checkdate' => 'desc')
        );
    $data['mistramites_count']=true;
    $data['mistramites_count_label_class']='success';
    $data['mistramites_count_qtty']=count($cases);

    // tasks 
    $data['tareas_count']=true;
    $data['tareas_count_label_class']='success';
    
    $query = array(
            'assign' => $this->idu,
            'status' => 'user'
    );

    $tasks = $this->bpm->get_tasks_byFilter($query);
    $data['tareas_count_qtty'] = count($tasks);
     


    // Parse    
     echo $this->parser->parse('lite', $data, true, true);
}
    
    
    function mis_tramites(){
        echo "---- Mis tramites";
    }

    function tramites(){
        echo "---- Tramites";
    }
    
    

    

}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */