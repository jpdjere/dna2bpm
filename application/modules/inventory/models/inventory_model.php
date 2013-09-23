<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class inventory_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->idu = (float) $this->session->userdata('iduser');
        $this->load->library('cimongo/cimongo');
        $this->load->config('config');
        $this->db = $this->cimongo;
        $this->container = ($this->config->item('container')) ? $this->config->item('container') : 'inventory';
    }

    function claim($type, $code, $user) {
        $data['type'] = $type;
        $data['code'] = $code;
        $data['user'] = (float) $user;
        $data['idu'] = $this->idu;
        $data['date'] = date('Y-m-d H:i:s');
        $rs = $this->get($type, $code);
        $last = array_pop($rs);
        //var_dump($last['idu'] == $this->idu, $last['idu'], $this->idu);
        if ($last) {
            if ((float) $last['user'] == $data['user']) {
                return;
            } else {
                $this->db->insert($this->container, $data);
            }
        } else {
            $this->db->insert($this->container, $data);
        }
    }

    function get($type, $code) {
        $query = array('type' => $type, 'code' => $code);
        $result = $this->db
                ->where($query)
                ->order_by(array('_id' => true))
                ->get($this->container);
        return $result->result_array();
    }

    function getbyuser($idu) {
        $query = array('user' => $idu);
        $result = $this->db
                ->where($query)
                ->order_by(array('_id' => true))
                ->get($this->container);
        return $result->result_array();
    }
    
    function getbygroup($idgroup) {
        $group=$this->user->getbygroup($idgroup);
        //var_dump($group);
        $userarr=array();
        foreach((array)$group as $user) $userarr[]=$user->idu;
        $query = array('user' =>array('$in',$userarr) );
        //$this->db->debug=true;
        $result = $this->db
                ->where_in('user',$userarr)
                ->order_by(array('_id' => true))
                ->get($this->container);
        return $result->result_array();
    }

}