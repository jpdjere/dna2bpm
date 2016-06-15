<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mysql_crefis_model extends CI_Model {

    function mysql_crefis_model() {
        parent::__construct();
        
        $dbconnect = $this->load->database('dna2', $this->db);
    }

    /* ACTIVE PERIODS DNA2 */

    function update_4970($id,$idpreg,$valor) {
        
            $data = array(
               'valor' => $valor
            );

            $this->db->where('id', $id);
            $this->db->where('idpreg', $idpreg);
            $this->db->update('td_crefis', $data); 

            /*UPDATE HIST*/
            $this->insert_history($id);
    }

    function insert_history($id){
        /*    idparent    idpreg  valor   idform  iduser  fecha   */
        $data = array(
           'id' => $id ,
           'idpreg' => $idpreg,
           'valor'=> $valor,
           'idform' => 280,
           'iduser'=>$this->session->userdata[iduser],
           'fecha' => date('Y-m-d H:i:s')
        );
        
        $this->db->insert('th_crefis', $data); 
    }
}
