<?php
/**
 * @class genia
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class Genias_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
    /* ---- TASKS---- */
    
    function add_task($task){
        $options = array('upsert' => true, 'safe' => true);
        $container='container.genias_tasks';
        return $this->mongo->db->$container->save($task, $options);
    }
    
    function add_goal($goal){
        $options = array('upsert' => true, 'safe' => true);
        $container='container.genias';
        return $this->mongo->db->$container->save($goal, $options);
    }
    
    function get_goals($idu){
        $query = array('idu' =>(double) $idu);
        $container='container.genias';
        $result = $this->mongo->db->$container->find($query)->sort(array('desde'=>-1));        
        //var_dump($result, json_encode($result), $result->count());

        return $result;
    }
    
    // -- Config -- //
    
    function get_config_item($name){
        $container='container.genias_config';
        $query=array('name'=>$name);
        $result = $this->mongo->db->$container->findOne($query); 
        return $result;
    }
    
        function config_set($data){
        $container='container.genias_config';
        $options = array('upsert' => true, 'safe' => true);
        $query=array('name'=>'projects');
        $rs= $this->mongo->db->$container->update($query, $data, $options);
        return $rs['err'];
    }
    

}
