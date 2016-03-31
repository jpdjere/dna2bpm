<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Session_remote extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->user->authorize('modules/genias/controllers/genias');
        $this->load->library('parser');
        $this->load->model('user');
        $this->load->model('app');
        $this->load->model('genias_model');        
        //----LOAD LANGUAGE
        $this->lang->load('library', $this->config->item('language'));
        $this->idu = (int) $this->session->userdata('iduser');
    }

    /*
     * CHECK LOGIN
     */

    public function ChkLogin() {
        $iduchK = array('idu' => $this->idu);
        $rtnArr = array();
        $rtnArr['totalCount'] = 1;
        $rtnArr['rows'] = $iduchK;
        echo json_encode($rtnArr);
    }

}