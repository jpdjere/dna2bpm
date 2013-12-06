<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Visitas_remote extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('parser');
        $this->load->model('user');
        $this->load->model('app');
        $this->load->model('genias_model');        
        //----LOAD LANGUAGE
        $this->lang->load('library', $this->config->item('language'));
        $this->idu = (int) $this->session->userdata('iduser');
        $this->containerGenias = 'container.genias_visitas';
        if(!$this->idu){
            header("$this->module_url/user/logout");
            exit();
        }
    }

    /* GENIAS */

    public function Insert() {       
        
        $container = $this->containerGenias;        

        $input = json_decode(file_get_contents('php://input'));

        foreach ($input as $thisform) {
            $form = get_object_vars($thisform);

            /* GENERO ID */
            $id = $this->app->genid($container);
            unset($form['id']);
            $form = array_filter($form);

            $form = (array) $form;
            $form['idu'] = (int) ($this->idu);

            /* Insert/Update dato */
            $result = $this->app->put_array($id, $container, $form);
            
            if ($result) {
                /* Update Goal */
                $newResult = $this->genias_model->goal_update('2', $id);
                $out = array('status' => 'ok');
            } else {
                $out = array('status' => 'error');
            }
        }
        
            $this->genias_model->touch($form['cuit']);
        
    }  

    public function Remove() {              
        
        
        $container = $this->containerGenias;
        $input = json_decode(file_get_contents('php://input'));
         foreach ($input as $thisform) {
            $form = get_object_vars($thisform);              
            $resultData = $this->genias_model->visitas_remove($container, $form['id']);
             if ($resultData) {
                $out = array('status' => 'ok');                
            } else {
                $out = array('status' => 'error');
            }
         }
       
    }

}