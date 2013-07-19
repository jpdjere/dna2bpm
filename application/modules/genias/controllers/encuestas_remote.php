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
            $id = ($thisform->id == null || (int) ($thisform->id) < 6 ) ? $this->app->genid($container) : $thisform->id;

            /* CHECKEO CUIT */
            $queryCuit = array('cuit' => $thisform->cuit);
            $resultCuit = $this->mongo->db->$container->findOne($queryCuit);



            if ($resultCuit['id'] != null) {
                if ($thisform->cuit != null) {
                    $id = $resultCuit['id'];
                }
            }


            /* Lo paso como Objeto */
            $thisform = (array) $thisform;
            $thisform['idu'] = (int) ($this->idu);

            $result = $this->app->put_array($id, $container, $thisform);
            if ($result) {
                $out = array('status' => 'ok');
            } else {
                $out = array('status' => 'error');
            }
        }
    }

    public function View() {

        $container = $this->containerEmpresas;
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