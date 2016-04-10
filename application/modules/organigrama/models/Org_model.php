<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * This class is for manipulate all related to bpm objects: models, cases and tokens
 * @author Juan Ignacio Borda <juanignacioborda@gmail.com>
 * @date Abr 7, 2016
 *
 */
class Org_model extends CI_Model {

    public $org_container = 'organigrama';

    function __construct() {
        parent::__construct();
        $this->idu = $this->user->idu;
        $this->load->library('cimongo/cimongo');

    }
    /**
     * Saves an organigram into database
     */
    function save($data,$idorg=0){
        if($data){
            $this->db->where(array('idorg'=>$idorg))->delete('organigrama');
            $this->db->insert('organigrama',array('idorg'=>$idorg,'data'=>$data));
        } else {
            show_error("Can't save empty data");
        }
    }

    /**
     * Get data from $idorg
     */
    function get($idorg=0){
        $rs=$this->db->where(array('idorg'=>$idorg))->get('organigrama')->result_array();
        return $rs;
    }
}