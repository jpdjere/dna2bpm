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

    // ======= TAREAS ======= //

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

    // ======= METAS ======= //

    function add_goal($goal) {
        $options = array('upsert' => true, 'safe' => true);
        $container = 'container.genias_goals';
        $query = array('id' => (integer) $goal['id']);
        return $this->mongo->db->$container->update($query, $goal, $options);
    }

    function get_goals($idu) {
        
        $genia = $this->get_genia($idu); 
        if($genia['rol']=='coordinador'){
            $query = array('idu' => array('$in'=>$genia['users']));
        }else{
            $query = array('idu' => (double) $idu);
        }
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

    // ======= CONFIG ======= //

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
        $rtn = array();
        $query['status'] = 'activa';
        $fields = array('id',
            'status'
            , '1693'  //     Nombre de la empresa
            , '1695'  //     CUIT
            , '7819' // 	Longitud
            , '7820' // 	Latitud
            , '4651' // 	Provincia
            , '4653' //     Calle Ruta
            , '4654' //     Nro /km
            , '4655' //     Piso
            , '4656' //     Dto Oficina
            , '1699' // 	Partido
        );
        $container = 'container.empresas';
        $result = $this->mongo->db->$container->find($query, $fields);
        foreach ($result as $empresa) {
            unset($empresa['_id']);
            $rtn[] = $empresa;
        }
        return $rtn;
    }
    
    
    
    // ======= USER CONTROL ======= //
    
    function get_genia($idu){
        $container = 'container.genias';
        // Es usuario?
        $query=array('users'=>(double)$idu);
        
        $result = $this->mongo->db->$container->findone($query); 
        var_dump($result);
        if($result){
           $genia=array('nombre'=>$result['nombre'],'id'=>$result['_id'],'rol'=>'user');
           return $genia;
        }
        // Es coordinador?
        
        $query=array('coordinadores'=>((double)$idu));
        $result = $this->mongo->db->$container->findone($query); 
        if($result){
           $genia=array('nombre'=>$result['nombre'],'id'=>$result['_id'],'rol'=>'coordinador','users'=>$result['users']);
           return $genia;
        }
        
        return false;
    }

}