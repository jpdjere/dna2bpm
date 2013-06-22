<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class inventory_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->idu = (float) $this->session->userdata('iduser');
        $this->load->library('cimongo/cimongo');
        $this->db = $this->cimongo;
    }

    function claim($type, $code, $user) {
        $data['type'] = $type;
        $data['code'] = $code;
        $data['user'] = $user;
        $data['idu'] = $this->idu;
        $data['date'] = date('Y-m-d H:i:s');
        $rs = $this->get($type, $code);
        $last = array_pop($rs);
        //var_dump($last['idu'] == $this->idu, $last['idu'], $this->idu);
        if ($last) {
            if ((float)$last['idu'] == $this->idu) {
                return;
            } else {
                $this->db->insert('entrada', $data);
            }
        } else {
            $this->db->insert('entrada', $data);
        }
    }

    function get($type, $code) {
        $query = array('type' => $type, 'code' => $code);
        $result = $this->db
                ->where($query)
                ->order_by(array('_id' => true))
                ->get('entrada')
                ->result_array();
        return $result;
    }

}