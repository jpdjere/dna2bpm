<?php

class Fondyf extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->idu = (int) $this->session->userdata('iduser');
        $this->load->library('cimongo/cimongo');
        $this->db = $this->cimongo;
        
    }

    function get_evaluator_by_project_by_id($query) {
        $rtn = array();
        $container = 'container.proyectos_fondyf';
        $fields = array('8668', 'id', '8339', '8334');
        $query = array(8668 => array('$exists' => true));
        $rs = $this->mongo->db->$container->find($query, $fields);
        foreach ($rs as $list) {
            unset($list['_id']);
            $rtn[] = $list;
        }

        return $rtn;
    }

}


