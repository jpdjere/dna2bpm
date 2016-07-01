<?php

class Eventanilla_model extends CI_Model {

     function __construct() {
        parent::__construct();
        //$this->idu = (int) $this->session->userdata('iduser');
        $this->load->library('cimongo/Cimongo.php', '', 'afip_db');
        $this->db = $this->cimongo;
        
        
        #DB
        $this->afip_db->switch_db('afip');
        $this->container = 'container.declaracion_jurada';
        
         #debug
       
    }
    

    /**
     * Buscar Registros 
     *
     * @name buscar_registros
     *
     * @see Eventanilla()
     *
     * @author Diego Otero <daotero@industria.gob.ar>
     *
     * @date Apr 19, 2016
     *
     * @param type $query
     */
    function buscar_registros() {

        $rtn = array();

        $rs = $this->mongowrapper->afip->$this->container->find();
        var_dump($this->mongowrapper->afip);
    
        foreach ($rs as $each) {
            $rtn[] = $each;
        }

        return $rtn;
    }
    
   
    

}
