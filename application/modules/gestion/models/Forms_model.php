<?php

class Forms_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->idu = (int) $this->session->userdata('iduser');
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

        $rs = $this->mongowrapper->db->$container->find();

        foreach ($rs as $each) {
            $rtn[] = $each;
        }

        return $rtn;
    }
    

}
