<?php

class Forms_model extends CI_Model {

    function __construct() {
        parent::__construct();
        //$this->idu = (int) $this->session->userdata('iduser');
        $this->load->library('cimongo/cimongo');
        $this->db = $this->cimongo;
        //$this->container="?";

    }
    
    
    function get_agents_data() {
        $container = 'container.agentes_secretaria';
        $result = $this->mongowrapper->dna2->$container->find();
        return $result;
    }
   
   /**
     * Buscar Agentes 
     *
     * @name buscar_agentes_registrados
     *
     * @see Viaticos()
     *
     * @author Diego Otero <daotero@industria.gob.ar>
     *
     * @date Apr 19, 2016
     *
     * @param type $query
     */
    function buscar_agentes_registrados($query = null, $fields = null) {
        
        $rtn = array();
        $container = 'container.agentes_secretaria';

        $rs = $this->mongowrapper->db->$container->find($query);

        foreach ($rs as $each) {
            $rtn[] = $each;
        }

        return $rtn;
    }
    
    /**
     * Buscar Viaticos
     *
     * @name buscar_viaticos
     *
     * @see Viaticos()
     *
     * @author Diego Otero <daotero@industria.gob.ar>
     *
     * @date Apr 19, 2016
     *
     * @param type $query
     */
    function buscar_viaticos($query = null) {
        $rtn = array();
        $container = 'container.gestion_viaticos';
        $rs = $this->mongowrapper->db->$container->find($query);

        foreach ($rs as $each) {
            $rtn[] = $each;
        }

        return $rtn;
    }
    
    
   /**
     * Save Viaticos 
     *
     * @name save
     *
     * @see Viaticos()
     *
     * @author Diego Otero <daotero@industria.gob.ar>
     *
     * @date Apr 19, 2016
     *
     * @param type $query
     */
    function save($parameter) {
        
        
        $options = array('upsert' => true, 'w' => 1);
        $container = 'container.gestion_viaticos';
        $query = array();
        $id = (float) $this->app->genid($container);
       
        $parameter['id'] = $id;
        $query = array('id' => (float) $id);
       
        $rs = $this->mongowrapper->db->$container->update($query, array('$set' => $parameter), $options);
        return $id;
    }
    
    

}
