<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Padfyj_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->idu = (float) $this->session->userdata('iduser');
        $this->load->library('cimongo/cimongo');
        $this->db = $this->cimongo;
        $this->db->switch_db('sgr');
    }

    function save($array) {
        //---rpimero busco si existe
        $query = array('CUIT' => $array['CUIT']);
        $details=array();
        //---Comentar para la primera pasada.
        //$details = $this->db->get_where('padfyj', $query)->result_array();
        if ($details[0]) {
            if (count($details) > 1) {
                var_dump($query, json_encode($query));
                show_error("Hay mas de 1 para:" . $array['CUIT']);
            }
            echo "Existe: " . $array['CUIT'] . ' ' . $array['DENOMINACION'] . "<br/>";
            $criteria = array('_id' => $details[0]['_id']);
            /*
              $this->db->where($creiteria);
              $result = $this->db->update('padfyj', $array);
              var_dump($criteria,$array);
             */
        } else {
            //echo "Inserto: " . $array['CUIT'] . ' ' . $array['DENOMINACION'] . "<br/>";
            $result = $this->db->insert('padfyj', $array);
        }
        return $result;
    }
    
    function info(){
        
       /* $container = 'padfyj';
        $query['CUIT'] = '23233265519';
        $result = $this->mongo->db->$container->find($query);
        return $result;*/
        
        $container = $this->containerEmpresas;
        $query = array('7406' => (int) ($this->idu));
        $resultData = $this->mongo->db->$container->find($query);
        return $resultData;
        
    }
    
    function search_name($cuit){        
        $container = 'padfyj';
        $query = array("CUIT"=>$cuit);
        $fields = array("DENOMINACION");
        $resultData = $this->mongo->db->$container->findOne($query);
        return $resultData["DENOMINACION"];
        
    }

}