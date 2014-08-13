<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class dna2old extends CI_Model {
    /*
     * Sets container for bpm models
     */

    public $container = 'dna2old';

    function __construct() {
        parent::__construct();
        $this->idu = $this->session->userdata('iduser');
        $this->load->library('cimongo/cimongo');
        $this->db = $this->cimongo;
    }

    function get($param) {
    $fields=array($param);
    $rs=$this->db->get($this->container,$fields)->result_array();
    return (isset($rs[0][$param]))? $rs[0][$param]: false;
    }

}
