<?php

/**
 * @class genia
 *
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Genias_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    /* ---- TASKS---- */

    function remove_task($id) {
;
        $container = 'container.genias_tasks';
        $query = array('id' => (integer) $id);
        $rs = $this->mongo->db->$container->remove($query);
        return $rs['err'];
    }

    function add_task($task) {
        $options = array('upsert' => true, 'safe' => true);
        $container = 'container.genias_tasks';
        $query = array('id' => (integer) $task['id']);
        $rs = $this->mongo->db->$container->update($query, $task, $options);
        return $rs['err'];
    }

    function get_tasks($idu, $proyecto) {

        $query = array('idu' => (double) $idu, 'proyecto' => $proyecto);
        $container = 'container.genias_tasks';
        $result = $this->mongo->db->$container->find($query)->sort(array('id' => -1));

        //var_dump($result, json_encode($result), $result->count());
        return $result;
    }

    /* ---- GOALS---- */

    function add_goal($goal) {
        $options = array('upsert' => true, 'safe' => true);
        $container = 'container.genias_goals';
        $query = array('id' => (integer) $goal['id']);
        return $this->mongo->db->$container->update($query, $goal, $options);
    }

    function get_goals($idu) {
        $query = array('idu' => (double) $idu);
        $container = 'container.genias_goals';
        $result = $this->mongo->db->$container->find($query)->sort(array('desde' => -1));
        //var_dump($result, json_encode($result), $result->count());

        return $result;
    }

    function get_case($case) {
        $query = array('id' => $case);
        $container = 'case';
        $result = $this->mongo->db->$container->findOne($query);
        return $result;
    }

    // -- Config -- //

    function get_config_item($name) {
        $container = 'container.genias_config';
        $query = array('name' => $name);
        $result = $this->mongo->db->$container->findOne($query);
        return $result;
    }

    function config_set($data) {
        $container = 'container.genias_config';
        $options = array('upsert' => true, 'safe' => true);
        $query = array('name' => 'projects');
        $rs = $this->mongo->db->$container->update($query, $data, $options);
        return $rs['err'];
    }

    function get_empresas($query) {
        $rtn=array();
        $query['status'] = 'activa';
        $fields=array('id',
            'status',
            '1693',//nombre
            '1695',//cuit
            '7751',//Longitud
            '7752',//Latitud
            );
        $container = 'container.empresas';
        $result = $this->mongo->db->$container->find($query,$fields);
        foreach($result as $empresa){
            unset($empresa['_id']);
            $rtn[]=$empresa;
        }
        return $rtn;
    }

}