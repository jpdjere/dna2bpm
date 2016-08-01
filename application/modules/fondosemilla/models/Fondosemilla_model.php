<?php

class Fondosemilla_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->idu = (int) $this->session->userdata('iduser');
        $this->load->library('cimongo/cimongo');
        $this->db = $this->cimongo;
        $this->load->model('bpm/bpm');
    }

    function proyectos_por_incubadora($id){
        $rtn = array();
        $container = 'container.fondosemillaproyectos';
        $query = array( '10034' => $id);
        $this->db->where($query);
        $result = $this->db->get($container)->result_array();
        return $result; 
    }
    
}
