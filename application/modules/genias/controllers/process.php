<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Process extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('parser');
        $this->load->model('user');
        $this->load->model('app');
        $this->user->authorize();
        //----LOAD LANGUAGE
        $this->lang->load('library', $this->config->item('language'));
        $this->idu = (float) $this->session->userdata('iduser');
        $this->containerEmpresas = 'container.empresas';
        $this->containerGenias = 'container.genias';
        $this->containerTablets = 'container.tablets';
        $this->containerTask = 'container.genias_tasks';
    }

    /* GENIAS */

    public function Insert() {

        $container = $this->containerEmpresas;
        $containerTablet = $this->containerTablets;
        $containerTask = $this->containerTask;
        
        $input = json_decode(file_get_contents('php://input'));

        /*
         * BUSCA LA GENIA X USUARIO
         */
        $queryGenia = array('usuario_tablet' => $this->idu);
        $resultGenia = $this->mongo->db->$containerTablet->findOne($queryGenia);


        $newArr = array();
        foreach ($input as $key => $value) {

            $newArr['7406'] = strval($this->idu);



            if ($key == 7407) {
                list($yearVal, $monthVal, $dayVal) = explode("-", $value);
                $dataArr = array("Y" => $yearVal, 'm' => $monthVal, 'd' => str_replace("T00:00:00", "", $dayVal));
                $newArr[$key] = $dataArr; //date_parse($value);
            } else if ($key == 'id') {
                /* GENERO ID */
                $id = ($value == null || strlen($value) < 6) ? $this->app->genid($container) : $value;
            } else {
                $newArr[$key] = $value;
            }

            /* BUSCO CUIT */
            if ($key == 7411) {
                
                 
                $newValue = str_replace('-','', $value);
                $newArr['7411'] = $newValue;
                
                $queryCuit = array('7411' => $newValue);
                $resultCuit = $this->mongo->db->$container->findOne($queryCuit);

                if ($resultCuit['id'] != null) {
                    $id = $resultCuit['id'];
                }
            }
            /* BUSCO TASK */          
            if ($key == 7818) {
                $task = intval($value);
            }
        }


        $newArr['7586'] = $resultGenia['7586']; //GENIA ID;

        /* Lo paso como Objeto */
        $array = (array) $newArr;
        $result = $this->app->put_array($id, $container, $array);         
        if ($result) {
            /* TASKS  */             
            if ($task) {                
                
                $queryTask = array('finalizada' => 1);
                $result = $this->app->put_array($task, $containerTask, $queryTask);                
            }

            $out = array('status' => 'ok');
        } else {
            $out = array('status' => 'error');
        }
    }

    /*
     * VIEW
     */

    public function View() {

        $container = $this->containerEmpresas;
        $query = array('7406' => strval($this->idu));
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

    /* TABLETS GENIAS */

    public function InsertTablet() {


        $container = $this->containerTablets;
        $input = json_decode(file_get_contents('php://input'));

        $newArr = array();
        foreach ($input as $key => $value) {
            if ($key == 'id') {
                /* GENERO ID */
                $id = ($value == null || strlen($value) < 6) ? $this->app->genid($container) : $value;
            } else {
                $newArr[$key] = $value;
            }


            /* BUSCO LA MAC ADDRESS COMO REFERENCIA */
            if ($key == 'mac') {
                $queryMac = array('mac' => $value);
                $resultCuit = $this->mongo->db->$container->findOne($queryMac);


                if ($resultCuit['id'] != null) {
                    if ($value != null) {
                        $id = $resultCuit['id'];
                    }
                }
            }
        }

        $newArr['7406'] = strval($this->idu);


        /* Lo paso como Objeto */
        $array = (array) $newArr;
        $result = $this->app->put_array($id, $container, $array);
        if ($result) {
            $out = array('status' => 'ok');
        } else {
            $out = array('status' => 'error');
        }
    }

    /*
     * VIEW
     */

    public function ViewTablet() {

        $container = $this->containerTablets;
        $query = ""; //array('7406' => strval($this->idu));
        $resultData = $this->mongo->db->$container->find();

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