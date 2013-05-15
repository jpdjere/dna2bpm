<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Fileman extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->idu = $this->session->userdata('iduser');

        $this->load->library('cimongo/cimongo');
        $this->db = $this->cimongo;

        //$this->load->database();
    }

}

?>