<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_organos_sociales extends CI_Model {

    function model_organos_sociales() {
        parent::__construct();
        // IDU : Chequeo de sesion
        $this->idu = (float) switch_users($this->session->userdata('iduser'));
        if (!$this->idu) {
            header("$this->module_url/user/logout");
            exit();
        }

        /* DATOS SGR */
        $sgrArr = $this->sgr_model->get_sgr();
        foreach ($sgrArr as $sgr) {
            $this->sgr_id = $sgr['id'];
            $this->sgr_nombre = $sgr['1693'];
            $this->sgr_cuit = $sgr['1695'];
        }

        $dbconnect = $this->load->database('dna2');
    }

    function get_ident() {
        
        $id = NULL;
        
        $this->db->select('*');
        $this->db->where('ident', '182');
        $this->db->where('estado', 'activa');
        $this->db->where('idu', $this->idu);
        $query = $this->db->get('idsent');
        foreach ($query->result() as $row) 
            $id = $row->id;
        
        return $id;
    }

}
