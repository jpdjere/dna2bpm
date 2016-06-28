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
        $this->db_formentrada->where($query);
        $caso = $this->db_formentrada->get($this->container)->result_array();
        $programas = $caso[0]['programa'];
        if(!$programas){
            return array();
        }else{
            return array_filter($programas);
        }
    }
    
    function actualizar_caso($idwf, $idcase, $datos){
        $query = array('idwf' => $idwf, 'idcase' => $idcase);
        $options = array('upsert' => true);
        $this->db_formentrada->where($query);
        return $this->db_formentrada->update($this->container, $datos, $options);
    }
    
    function devolver_bancos_pyme_bancario($idwf, $idcase){
        $query = array('idwf' => $idwf, 'idcase' => $idcase);
        $this->db_formentrada->where($query);
        $caso = $this->db_formentrada->get($this->container)->result_array();
        $programas['rbt'] = $caso[0]['rbt'];
        $programas['parques'] = $caso[0]['parques'];
        $programas['mi_galpon'] = $caso[0]['mi_galpon'];
        $programas=array_filter($programas, function($dato){
            if(is_null($dato)){
                return false;
            }else{
                return true;
            }
        });
        if(!$programas){
            return array();
        }else{
            return $programas;
        }
    }
}





