<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Encuestas_remote extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('parser');
        $this->load->model('user');
        $this->load->model('app');
        $this->load->model('genias_model');
        $this->user->authorize('modules/genias/controllers/genias');
        //----LOAD LANGUAGE
        $this->lang->load('library', $this->config->item('language'));
        $this->idu = (int) $this->session->userdata('iduser');        
        $this->containerGenias = 'container.genias_encuestas';
    }

    /* GENIAS */
    public function Insert() {        
        
        $container = $this->containerGenias;
        $input = json_decode(file_get_contents('php://input'));
        foreach ($input as $thisform) {         
          
            /* GENERO ID */
            $id = ($thisform->id == null || strlen($thisform->id) < 6 ) ? $this->app->genid($container) : $thisform->id;           
           

            /* Lo paso como Objeto */
           $thisform = (array) $thisform;
           $thisform['idu'] = (int)($this->idu);
          
            $result = $this->app->put_array($id, $container, $thisform);


            if ($result) {               
                /* Update Goal */
                $this->genias_model->goal_update('2',$id);
                $out = array('status' => 'ok');
            } else {
                $out = array('status' => 'error');
            }
        }
    } 
    
}