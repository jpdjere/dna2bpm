<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mysql_crefis_model extends CI_Model {

    function mysql_crefis_model() {
        parent::__construct();
        
        $this->dna2_old = $this->load->database('dna2', TRUE);
    }

    /* ACTIVE PERIODS DNA2 */

    function update_4970($id) {
        
            $data = array(
               'valor' => 45
            );

            $this->dna2_old->where('id', $id);
            $this->dna2_old->where('idpreg', 4970);
            $this->dna2_old->update('td_crefis', $data); 

            /*UPDATE HIST*/
            $this->insert_history($id);
    }

    function insert_history($id){
        /*    idparent    idpreg  valor   idform  iduser  fecha   */
        $data = array(
           'id' => $id ,
           'idpreg' => 4970,
           'valor'=> 45,
           'idform' => 280,
           'iduser'=>1322971723,
           'fecha' => date('Y-m-d H:i:s')
        );
        
        $this->dna2_old->insert('th_crefis', $data); 
    }
}
