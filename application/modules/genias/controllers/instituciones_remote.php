<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Instituciones_remote extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('parser');
        $this->load->model('user');
        $this->load->model('app');
        $this->load->model('genias/genias_model');
        $this->user->authorize('modules/genias/controllers/genias');
        //----LOAD LANGUAGE
        $this->lang->load('library', $this->config->item('language'));
        $this->idu = (float) $this->session->userdata('iduser');
        $this->containerInstituciones = 'container.agencias';
        $this->containerGenias = 'container.genias';
        $this->containerTablets = 'container.tablets';
        $this->containerTask = 'container.genias_tasks';
    }

    /* GENIAS */

    public function Insert() {
        $container = $this->containerInstituciones;
        $containerTask = $this->containerTask;

        $input = json_decode(file_get_contents('php://input'));

        foreach ($input as $thisform) {   
//            print_r($thisform);
//            continue;
            //$form=array_filter((array)$thisform);
            $form= get_object_vars($thisform);
//            var_dump('FORM',$form,'THIS-FORM',$thisform);
            //---preparo arrays
            $form['8104']=(isset($form['8104'])) ? (array)$form['8104']:null;
            $nuevo = ($form['id'] == null || strlen($form['id']) < 6 );
            /* CHECKEO CUIT */
            $queryCuit = array('4896' => $form['4896']);
            
            $resultCuit = $this->mongo->db->$container->findOne($queryCuit);
            if ($resultCuit['id'] != null) {
                if ($form[4896] != null) {
                    $form['id'] = $resultCuit['id'];
                    $nuevo=false;
                    }
            }
            /* GENERO ID */
            $id = ($nuevo) ? $this->app->genid($container) : $form['id'];
            unset($form['id']);
            $form=array_filter($form);
            $form['status'] = 'activa';
            $form['origen'] = 'genia2013';          
            //$form['origenGenia'] = (int) ($this->idu);
            

            /* IDENTIFICO TAREA */
            $getTask =(isset($form['task'])) ? (int) $form['task']:null;

            /* Lo paso como Objeto */
            $form = (array) $form;
            /* solo pongo idu si es nuevo */
            if ($nuevo)
                $form['idu'] = (int) ($this->idu);

            /* Insert/Update dato de la empresa */
            $result = $this->app->put_array($id, $container, $form);

            if ($result) {
                /* Update Task */
                $idTask = ($getTask == null || (int) ($getTask) < 6) ? $this->app->genid($containerTask) : $getTask;
                $queryTask = array('finalizada' => 1);
                $result = $this->app->put_array($idTask, $containerTask, $queryTask);

                 //
                $out = array('status' => 'ok');
            } else {
                $out = array('status' => 'error');
            }
            
        }
           $this->genias_model->touch($form['1695']);     
    }

    /*
     * VIEW
     */

    public function View() {

        $container = $this->containerInstituciones;
        $query = array('7406' => (int) ($this->idu));
        $resultData = $this->mongo->db->$container->find($query);

        foreach ($resultData as $returnData) {
            $fileArrMongo[] = $returnData;
        }
        //return $fileArrMongo;             

        if (!empty($fileArrMongo)) {
            echo json_encode(array(
                'success' => true,
                'message' => "Loaded data",
                'data' => $fileArrMongo
            ));
        }
    }
    
}