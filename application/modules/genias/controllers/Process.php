<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Process extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('parser');
        $this->load->model('user');
        $this->load->model('app');
        $this->user->authorize('modules/genias/controllers/genias');
        //----LOAD LANGUAGE
        $this->lang->load('library', $this->config->item('language'));
        $this->idu = (float) $this->session->userdata('iduser');
        $this->containerEmpresas = 'container.empresas';
        $this->containerGenias = 'container.genias';
        $this->containerTablets = 'container.tablets';
        $this->containerTask = 'container.genias_tasks';
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
                $resultCuit = $this->mongowrapper->db->$container->findOne($queryMac);


                if ($resultCuit['id'] != null) {
                    if ($value != null) {
                        $id = $resultCuit['id'];
                    }
                }
            }
        }

        $newArr['7406'] = (int)($this->idu);


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
        $resultData = $this->mongowrapper->db->$container->find();

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