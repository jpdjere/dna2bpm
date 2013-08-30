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
        $this->user->authorize('modules/genias/controllers/genias');
        //----LOAD LANGUAGE
        $this->lang->load('library', $this->config->item('language'));
        $this->idu = (int) $this->session->userdata('iduser');
        $this->containerGenias = 'container.genias_visitas';
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
                var_dump($newResult);
                
                $out = array('status' => 'ok');
            } else {
                $out = array('status' => 'error');
            }
        }
        
            $this->genias_model->touch($form['cuit']);
        
    }

    /*
     * FIX goals
     */    
    public function Fix() {
        

        $container = $this->containerGenias;
        $query = array('idu' => (int) ($this->idu));
        $result = $this->genias_model->get_visitas_all();
        foreach ($result  as $returnData) {
            $id = $returnData['id'];
            $idu = $returnData['idu'];
            $fecha = $returnData['fecha'];
           $newResult = $this->genias_model->goal_update_all('2', $id,$idu, $fecha);
           var_dump($newResult);
           
        }
        exit();       
    }

    public function Remove() {       
        $container = $this->containerGenias;
        $input = json_decode(file_get_contents('php://input'));
         foreach ($input as $thisform) {
            $form = get_object_vars($thisform);              
            $resultData = $this->genias_model->visitas_remove($container, $form['id']);
             if ($resultData) {
                /* Update Goal */
                $this->genias_model->goal_remove($form['id']);
                $out = array('status' => 'ok');
            } else {
                $out = array('status' => 'error');
            }
         }
       
    }
    
   

}