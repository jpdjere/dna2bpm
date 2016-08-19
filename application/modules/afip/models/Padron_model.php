<?php

class Consultas_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->idu = (int) $this->session->userdata('iduser');
        $this->padron_db=new $this->cimongo;
        #DB
        $this->padron_db->switch_db('padfyj');
        //$this->container="?";
    }

    
    
   function por_sector($filter=null){

    $query = array();
    $aquery=array('aggregate'=>'procesos','pipeline'=>array());
    // agrego el filtro
    if($filter)
    $aquery['pipeline'][]= $filter;
    //-group
    $aquery['pipeline'][]=array('$group'=>
                    array(
                      "_id" => '$result.sector_texto',
                      "cant" =>array('$sum'=>1),
                    ),
                );
    $aquery['pipeline'][]= array('$project'=>array(
                    'Sector'=>'$_id',"Cantidad"=>'$cant',"_id"=>0
                    ));
    $aquery['pipeline'][]= array('$sort'=>array(
                    "Cantidad"=>-1
                    ));
    $rs=$this->padron_db->command($aquery);
    return $rs['result'];

    }
   function por_letra($filter=null){
    $this->load->model('app');
    $query = array();
    $aquery=array('aggregate'=>'procesos','pipeline'=>array());
    
    // agrego el filtro
    if($filter)
    $aquery['pipeline'][]= $filter;
    
    
    $aquery['pipeline'][]= array('$group'=>
                    array(
                      "_id" => '$result.idrel',
                      "cant" =>array('$sum'=>1),
                    ),
                );
    $aquery['pipeline'][]= array('$project'=>array(
                    'Letra'=>'$_id',"Cantidad"=>'$cant',"_id"=>0
                    ));
    $aquery['pipeline'][]=                array('$sort'=>array(
                    "Cantidad"=>-1
                    ));
    $rs=$this->padron_db->command($aquery);
    $letras=$this->app->get_ops(748);
    foreach($rs['result'] as &$res)
                $res['Letra_texto']=$letras[$res['Letra']];
    return $rs['result'];

    }

    


}
