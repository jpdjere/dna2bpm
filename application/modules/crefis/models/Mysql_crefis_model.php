<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mysql_crefis_model extends CI_Model {

    function mysql_crefis_model() {
        parent::__construct();
        
        $dbconnect = $this->load->database('dna2', $this->db);
    }

    /* ACTIVE PERIODS DNA2 */

    function update_4970($id) {
        
            $data = array(
               'valor' => 45
            );

            $this->db->where('id', $id);
            $this->db->where('idpreg', 4970);
            $this->db->update('td_crefis', $data); 
    }
}
