<?php

class Consultas_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->idu = (int) $this->session->userdata('iduser');
        $this->afip_db=new $this->cimongo;
        #DB
        $this->afip_db->switch_db('afip');
        //$this->container="?";
    }


    /**
     * Buscar CUITS status
     *
     * @name buscar_cuits_registrados
     *
     * @see Afip()
     *
     * @author Diego Otero <daotero@industria.gob.ar>
     *
     * @date Apr 19, 2016
     *
     * @param type $query
     */
    function buscar_cuits_registrados($parameter=null, $collection='queue') {
       $this->afip_db->switch_db('afip');
       return $this->afip_db->where(array('cuit'=>new MongoInt64($parameter)))->get($collection)->row();
   }



    /**
     * Buscar CUITS para Certificados
     *
     * @name cuits_certificados
     *
     * @see Afip()
     *
     * @author Diego Otero <daotero@industria.gob.ar>
     *
     * @date Apr 19, 2016
     *
     * @param type $query
     */
    function cuits_certificados($cuit='') {
       $this->afip_db->switch_db('afip');
     
       if(empty($cuit))return false;
       if($this->has_1273($cuit)==false)return false;
       if(empty($this->isPyme($cuit)))return false;
       if($this->isPyme($cuit))return false;

       $query=array('cuit'=>new MongoInt64($cuit));

       //return $this->afip_db->where(array('cuit'=>$parameter))->get('procesos')->row();
       return $this->afip_db->where(array('cuit'=>new MongoInt64($cuit)))->get('procesos')->row();

   }

    /**
     * QUEUE STATUS
     *
     * @name show_queue_qry
     *
     * @see Afip()
     *
     * @author Diego Otero <daotero@industria.gob.ar>
     *
     * @date Apr 19, 2016
     *
     * @param type $query
     */
    function show_queue_qry($parameter=null) {
      $query = array('$ne'=>'ready');


      if(isset($parameter)){
        $query = $parameter;
    } 

    $this->afip_db->switch_db('afip');
    return $this->afip_db->where(array('status'=>$query))->get('queue')->result_array();
}

    /**
     * RAW/PROCESS/QUEUE STATUS
     *
     * @name show_source_qry
     *
     * @see Afip()
     *
     * @author Diego Otero <daotero@industria.gob.ar>
     *
     * @date Apr 19, 2016
     *
     * @param type $query $cuit, $collection
     */
    function show_source_qry($parameter, $collection) {
       $this->afip_db->switch_db('afip');
       return $this->afip_db->where(array('cuit'=>$parameter))->get($collection)->row();
       var_dump($this->afip_db);
   }


   function vinculadas($paramter=null){

    #"vinculadas" : {$exists:true}, "vinculadas.detalles":{$size: 0}


    $query = array();
    $query["vinculadas"] = array('$exists'=>true);
    $query["vinculadas.detalles"] = array('$size'=>0);

    $this->afip_db->switch_db('afip');
        if(isset($paramter))
            return $this->afip_db->where($query)->count_all_results('procesos');
        else     
            return $this->afip_db->where($query)->get('procesos')->result_array();
    
        

    }
   
   function por_provincia($paramter=null){

    $query = array();
    $aquery=array(
    'aggregate'=>'procesos',
                'pipeline'=>
            array(
                array('$group'=>
                    array(
                      "_id" => '$domicilioLegalDescripcionProvincia',
                      "cant" =>array('$sum'=>1),
                    ),
                ),
                array('$project'=>array(
                    'Provincia'=>'$_id',"Cantidad"=>'$cant',"_id"=>0
                    )),
                array('$sort'=>array(
                    "Cantidad"=>-1
                    )),
        )
    );
    $rs=$this->afip_db->command($aquery);
    return $rs['result'];

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
    $rs=$this->afip_db->command($aquery);
    return $rs['result'];

    }
   function por_categoria($filter=null){

    $query = array();
    $aquery=array('aggregate'=>'procesos','pipeline'=>array());
    // agrego el filtro
    if($filter)
    $aquery['pipeline'][]= $filter;
    //-group
    $aquery['pipeline'][]=array('$group'=>
                    array(
                      "_id" => '$result.categoria',
                      "cant" =>array('$sum'=>1),
                    ),
                );
    $aquery['pipeline'][]= array('$project'=>array(
                    'Categoria'=>'$_id',"Cantidad"=>'$cant',"_id"=>0
                    ));
    $aquery['pipeline'][]= array('$sort'=>array(
                    "Cantidad"=>-1
                    ));
    $rs=$this->afip_db->command($aquery);
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
    $rs=$this->afip_db->command($aquery);
    $letras=$this->app->get_ops(748);
    foreach($rs['result'] as &$res)
                $res['Letra_texto']=$letras[$res['Letra']];
    return $rs['result'];

    }
   function isPyme($paramter=null){

    $query = array();
    $aquery=array(
    'aggregate'=>'procesos',
                'pipeline'=>
            array(
                array('$project'=>array(
                        'isPyme'=>array('$cond'=>[array('$eq'=>['$result.isPyme',1]),'Si','No']),
                        )
                    ),
                array('$group'=>
                    array(
                      "_id" => '$isPyme',
                      "cant" =>array('$sum'=>1),
                      
                    ),
                ),
                 array('$project'=>array(
                    'isPyme'=>'$_id',"Cantidad"=>'$cant',"_id"=>0
                    )),
                array('$sort'=>array(
                    "Cantidad"=>-1
                    )),
        )
    );
    //  echo json_encode($aquery,JSON_PRETTY_PRINT);exit;
    $rs=$this->afip_db->command($aquery);
    return $rs['result'];

    }
   function F1272xSemana($paramter=null){

    $query = array();
    $aquery=array(
    'aggregate'=>'raw_ventanilla',
                'pipeline'=>
            array(
                array('$group'=>
                    array(
                      "_id" =>array('$week'=>'$date'),
                      "cant" =>array('$sum'=>1),
                      "fecha" =>array('$first'=>'$date'),
                      
                    ),
                ),
                array('$sort'=>array(
                    "fecha"=>1
                    )),
                array('$project'=>array(
                        'Fecha'=>array('$dateToString'=> array('format'=>"%Y-%m-%d", 'date'=> '$fecha' )),
                        'Cantidad'=>'$cant',
                        '_id'=>0,
                        )
                    ),
                 
        )
    );
    //   echo json_encode($aquery);exit;
    $rs=$this->afip_db->command($aquery);
    return array('data'=>$rs['result'],'key'=>'Fecha','items'=>array('Cantidad'),'labels'=>array('Cantidad'));

    }
   function F1272xMes($paramter=null){

    $query = array();
    $aquery=array(
    'aggregate'=>'raw_ventanilla',
                'pipeline'=>
            array(
                array('$group'=>
                    array(
                      "_id" =>array('$month'=>'$date'),
                      "cant" =>array('$sum'=>1),
                      "fecha" =>array('$first'=>'$date'),
                      
                    ),
                ),
                array('$sort'=>array(
                    "fecha"=>1
                    )),
                array('$project'=>array(
                        'Fecha'=>array('$dateToString'=> array('format'=>"%m/%Y", 'date'=> '$fecha' )),
                        'Cantidad'=>'$cant',
                        '_id'=>0,
                        )
                    ),
                 
        )
    );
    //  echo json_encode($aquery,JSON_PRETTY_PRINT);exit;
    $rs=$this->afip_db->command($aquery);
    return array('data'=>$rs['result'],'key'=>'Fecha','items'=>array('Cantidad'),'labels'=>array('Cantidad'));

    }


    function has_1273($cuit){
        //@todo caducidad del certificado
        $query=array('cuit'=>$cuit,'1273'=>array('$exists'=>true));
        $_1273=$this->afip_db->where($query)->get('raw_ventanilla')->result_array();
        return !empty($_1273);

    }

    


}
