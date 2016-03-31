<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class sgrbpm_model extends CI_Model {
   

    function __construct() {
        parent::__construct();
        $this->idu = (int) $this->session->userdata('iduser');
        $this->load->library('cimongo/cimongo');
        $this->db = $this->cimongo;

    }




}
