<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */class Users extends MX_Controller{

    function __construct() {
        parent::__construct();
        $this->load->config('config');
        $this->load->library('parser');
        $this->load->library('ui');
        $this->load->model('app');
        $this->load->model('user/user');
        $this->load->model('user/rbac');
        $this->load->model('genias/genias_model');
        $this->load->helper('genias/tools');

        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . 'genias/';
        $this->user->authorize();
        //----LOAD LANGUAGE
        $this->lang->load('library', $this->config->item('language'));
        $this->idu = (float) $this->session->userdata('iduser');
    }
    
    public function Index(){
        
    }
    
   function Get_User($action) {
        $this->user->authorize();
        $segments = $this->uri->segments;
        $debug = (in_array('debug', $segments)) ? true : false;
        $cpData = $this->lang->language;
        $rtnArr = array();
        $i = 0;
        switch ($action) {
            case 'read':
                $start = ($this->input->post('start')) ? $this->input->post('start') : 0;
                $limit = ($this->input->post('limit')) ? $this->input->post('limit') : 150;
                $query = $this->input->post('query');
                $idgroup = (int) 98;
                $sortObj = json_decode($this->input->post('sort'));
                // build sort array
                $sort = array();
                if (!empty($sortObj)) {
                    foreach ($sortObj as $value) {
                        $sort[$value->property] = $value->direction;
                    }
                }
                $rs = $this->user->get_users($start, $limit, $sort, $query, $idgroup);
                $rtnArr['totalCount'] = $rs->num_rows();

                foreach ($rs->result() as $thisUser) {
                    $rtnArr['rows'][] = $thisUser;
                    //break;
                }
                break;
            case 'update':
                $user_data = $_POST;
                $user = $this->user->add($user_data);
                $rtnArr['success'] = true;
                $rtnArr['msg'] = 'User updated: ok!';
                $rtnArr['data'] = $user;
                break;
        }
        //var_dump($cpData);
        if (!$debug) {
            header('Content-type: application/json;charset=UTF-8');
            echo json_encode($rtnArr);
        } else {
            var_dump($rtnArr);
        }
        //$this->load->view('footer');
    }

}
?>
