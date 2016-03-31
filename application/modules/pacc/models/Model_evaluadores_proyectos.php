<?php

/**
 * Funciones para el manejo de los proyectos e infoormación para evaluadores.
 * 
 * @author MAGonzalez <mglongchamps@gmail.com>
 * @date 14/09/2015
 * 
 */

class Model_evaluadores_proyectos extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        
        
        
        $this->load->library('cimongo/Cimongo.php', '', 'db_pacc');
        $this->db_pacc->switch_db('dna3');
        $this->idu = $this->session->userdata('iduser'); //Id user
               
    }
    /**
     * Devuelve Proyectos Empresas

     * @return array $return
     */
    
    function proyectos_empresa($page=1,$pagesize=5){ 
        $this->load->model('app');
        $result = array();
        $return  =array();
        $id_user =  $this->idu;
        
        $id_u =  strval($id_user);
        $container = 'container.proyectos_pacc';
        $query = array(6404=>$id_u);
        $this->db_pacc->where($query);
        $result = $this->db_pacc->get($container,$pagesize,($page-1)*$pagesize)->result_array();
        $i=0;
        
        $opciones = $this->app->get_ops(648);
        
        foreach ($result as $proy){
            $return[$i]['numero'] = $proy[6390];
            $return[$i]['estado'] = (isset($opciones[$proy[6225][0]]))? $opciones[$proy[6225][0]]:'???';
            $return[$i]['comentario'] = (isset($proy[5673]))? $proy[5673]:'';
            
            $container_empresas = 'container.empresas';
            $query_empresas = array("id"=>$proy[6223][0]);
            
            $this->db_pacc->where($query_empresas);
            $result_empresas = $this->db_pacc->get($container_empresas)->result_array();
            
            foreach ($result_empresas as $emp){
                $return[$i]['cuit']=(isset($emp[1695]))? $emp[1695]:'';
                $return[$i]['empresa']=(isset($emp[1693]))? $emp[1693]:'';
                
            }
            
            $i++;
        }
        
        
        return $return; 
        
    }
    
    
    /**
     * Devuelve Proyectos Emprendedores
     * 
     * @return array $return
     */
    
    function proyectos_emprendedor(){ 
        $this->load->model('app');
        $result = array();
        $return  =array();
        $id_user =  $this->idu;
        
        $id_u =  strval($id_user);
        $container = 'container.proyectos_pacc';
        $query = array(6096=>$id_u);
        //var_dump($query);
        $this->db_pacc->where($query);
        $result = $this->db_pacc->get($container)->result_array();
        $i=0;
        
        $opciones = $this->app->get_ops(648);
        //var_dump($result);
        //exit();
        foreach ($result as $proy){
            $return[$i]['numero'] = $proy[5691];//$proy[7356];
            $return[$i]['estado'] = (isset($opciones[$proy[5689][0]]))? $opciones[$proy[5689][0]]:'???';
            $return[$i]['comentario'] = (isset($proy[5673]))? $proy[5673]:'';
            
            $container_empresas = 'container.empresas';
            $query_empresas = array("id"=>$proy[6065][0]);
            
            $this->db_pacc->where($query_empresas);
            $result_empresas = $this->db_pacc->get($container_empresas)->result_array();
            
            foreach ($result_empresas as $emp){
                $return[$i]['cuit']=(isset($emp[1695]))? $emp[1695]:'';
                $return[$i]['empresa']=(isset($emp[1693]))? $emp[1693]:'';
                
            }
            
            $i++;
        }
        
        //var_dump($return);
        return $return; 
        
    }
    
}





