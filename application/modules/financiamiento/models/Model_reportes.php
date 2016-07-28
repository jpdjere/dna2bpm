<?php

/**
 * Funciones para el manejo de datos del container formulario_entrada
 * @author Sebastian Blazquez <seby_1996@hotmail.com>
 * @date 3/06/2016
 * 
 */

class Model_reportes extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->library('cimongo/Cimongo.php', '', 'db_formentrada');
        $this->db_formentrada->switch_db('formentrada');
        $this->container='container.formulario_entrada';
    }
    
    function get_cases_data($cases){
        foreach($cases as $case){
            $query = array('idcase' => $case);
            $this->db_formentrada->where($query);
            $datos = $this->db_formentrada->get($this->container)->result_array();
            $datos=$datos[0];
            $datos['case']=$case;
            $data[]=$datos;
        }
        return $data;
    }
    
    function get_data_by_caseid($query){
        $this->db_formentrada->where($query);
        return $this->db_formentrada->get($this->container)->result_array();
    }
    
    function get_data_by_query($query){
        $this->db_formentrada->where($query);
        return $this->db_formentrada->get($this->container)->result_array();
    }
}





