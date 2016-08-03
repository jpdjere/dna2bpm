<?php

/**
 * Funciones para el manejo de datos del container inscripciones
 * @author Luciano Menez <lucianomenez1212@gmail.com>
 * @date 3/05/2016
 * 
 */

class Model_aulavirtual extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->library('cimongo/cimongo', '', 'db');
    }
    /**
     * Lista todas las inscripciones cargadas
     * @return array $result
     */
    
    function inscripciones_cargadas($filter = null){ //
        /*
         * Devuelve array con la info de todas las inscripciones, 
         ** En $filter recibe parametro de filtrado
        */
        $rtn = array();
        $container = 'container.aulavirtual';
        //$fields = array('filename','borrado');
        //$query = array();
        if ($filter){
            $query = array('borrado'=>0,
                           'idwf'=>$filter);
        }else{
            $query = array('borrado'=>0);            
        }
        $this->db->where($query);
        $result = $this->db->get($container)->result_array();

        return $result; 
    }
    /*
     * *
     * Cuenta la cantidad de inscripciones cargadas
     * 
     * @return int $result
     */
    function count_cargados(){ //Cuenta la cantidad de inscripciones cargadas.
        $rtn = array();
        $container = 'container.aulavirtual';
        //$fields = array('filename');
        //$query = array();
        $result = $this->db->count_all($container);
        return $result;
    }   
    
    /**
     * Destalle de inscripcion
     * 
     * @param MongoID $id_inscripcion
     * @return array $result
     */
    
    function detalle_inscripciones($idcase, $idwf){ //Destalle de inscripcion
        /*
         * Devuelve array con los campos de la inscripcion
         * 
         */
        $result = array();
        $container = 'container.aulavirtual';
        
        if($idcase != ''){
     //   $mongoID=new MongoID($id_inscripcion);
        $query= array('idcase'=>$idcase,
                      'idwf'=>$idwf,
                       'borrado'=>0,);
        
        $this->db->where($query);
        $result = $this->db->get($container)->result_array();
        }else{
        $query= array('borrado'=>0,);
        $this->db->where($query);
        $result = $this->db->get($container)->result_array(); 
        }
        return $result;
    }
    
    /**
     * Borra inscripcion logicamente
     * 
     * @param MongoID $id_inscripcion
     */
    function borrar_inscripciones_db($idwf, $idcase){ //Borra inscripcion se la pasa id inscripcion
        $container = 'container.aulavirtual';
        $data = array(
            'borrado' => 1
            );
        $query= array('idwf'=>$idwf,
                      'idcase' => $idcase);        
        $this->db->where($query);
        $this->db->update($container,$data);
    }
    
    function insert_inscripcion($val_arr = array()) { //inserta nueva inscripcion -Se le pasa el nuevo array 
        $container = 'container.aulavirtual';
        $hoy = getdate(); //SI CON ESTA! YA SE!
        $val_arr['date'] = $hoy;
        $val_arr['borrado'] = 0;
        //$result = 
        $this->db->insert($container,$val_arr);
        //return $result;
    }
    
    function update_inscripcion ($data, $inscripcion) {
        $result = array();
        $container = 'container.aulavirtual';
        $query= array('idcase'=>$inscripcion['idcase'],
                       'idwf' => $inscripcion['idwf']);
        $this->db->where($query);
        $result = $this->db->update($container, $data);
    }
    
}





