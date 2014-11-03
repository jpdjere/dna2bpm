<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Functions extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->idu = (int) $this->session->userdata('iduser');
        
    }

    function getvalue($id, $idframe) {           
        
        $rtnVal = null;
        $result = null;
        $id = (double) $id;
        $idframe = (int) $idframe;
        //----Get container
        $frame = $this->mongo->db->frames->findOne(array('idframe' => $idframe), array('container'));
        $query = array('id' => $id);
        $fields = array((string) $idframe);
        if ($frame['container']) {
            $result = $this->mongo->db->selectCollection($frame['container'])->findOne($query, $fields);
        } else {
            trigger_error("container property missing for: $idframe");
        }
        //var_dump($frame['container'],json_encode($query),json_encode($fields),$result);
        $rtnVal = (isset($result[$idframe])) ? $result[$idframe] : null;
        return $rtnVal;
    }
}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
