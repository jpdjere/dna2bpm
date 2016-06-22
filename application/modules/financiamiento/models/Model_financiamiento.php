<?php

/**
 * Funciones para el manejo de datos del container inscripciones
 * @author Luciano Menez <lucianomenez1212@gmail.com>
 * @date 3/05/2016
 * 
 */

class Model_financiamiento extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->library('cimongo/Cimongo.php', '', 'db_formentrada');
        $this->db_formentrada->switch_db('formentrada');
        $this->container='container.formulario_entrada';
    }
    
    function guardar_datos_formulario($datos_formulario = array()){
        return $this->db_formentrada->insert($this->container, $datos_formulario);
    }
    
    function devolver_programas_pyme_bancario($idwf, $idcase){
        $query = array('idwf' => $idwf, 'idcase' => $idcase);
        return $this->db_formentrada->where($query)->result_array();
    }
    
    function actualizar_programas($idwf, $idcase, $programas){
        $query = array('idwf' => $idwf, 'idcase' => $idcase);
        $data = array('programas'=> $programas);
        $options = array('upsert' => true);
        $this->db_formentrada->where($query);
        return $this->db_formentrada->update($this->container, $data, $options);
    }
}





