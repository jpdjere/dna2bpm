<?php

class Eventanilla_model extends CI_Model {

     function __construct() {
        parent::__construct();
        // $this->load->library('cimongo/cimongo',null,'afip_db');
        //$this->idu = (int) $this->session->userdata('iduser');
        $this->afip_db=new $this->cimongo;
        #DB
        $this->afip_db->switch_db('afip');
        
         

        
         #debug
       
    }
    

    /**
     * Buscar Registros 
     *
     * @name buscar_registros
     *
     * @see Eventanilla()
     *
     * @author Gabriel Fojo <gabriel@trialvd.com.ar>
     *
     * @date Apr 19, 2016
     *
     * @param type $query
     */


    // function get($id) {
    //     $myid=New MongoId($id);
    //     $num=$this->afip_db->where(array('_id'=>$myid))->get('raw_ventanilla')->row();
       
    //     var_dump($num);
    // }
    


    //== Guarda json crudo obtenido de la ventanilla

    function save_raw_ventanilla($params,$overwrite=false){

        $idComunicacion=$params['idComunicacion'];
        $json=(array)json_decode($params['content']);  

        //=== Error: No se puede procesar el JSON
        if(empty($json)){
            $err=array(
            'model'=>'save_raw_ventanilla',
            'msg'=>'Json mal procesado',
            'idComunicacion'=>$idComunicacion,
            'class'=>'error'
            );
            $this->log($err);
            return false;
        }

        // Evita repetidos en ventanilla raw
        $exists=$this->comunicacion_exist($idComunicacion,$json['transaccion']);
        if($exists && $overwrite===false)return false;

        // Guardo en DB
        $this->afip_db->switch_db('afip');
        $data['date']=new MongoDate(time());
        $data['idComunicacion'] = $idComunicacion;
        $data['cuit'] = $json['cuit'];
        $data['transaccion'] = $json['transaccion'];
        $data['raw'] = $params['content'];      
        if($overwrite===false)
            $this->afip_db->insert('raw_ventanilla', $data); 

        // Stack de Procesos
        $json['idComunicacion'] = $idComunicacion;

         return $json;
    }

    //== Guarda proceso listo

    function save_process($process){  
        $this->afip_db->switch_db('afip');
        // Chequeo si existe y guardo el idComunicacion 

        $exist=$this->afip_db->where(array('cuit'=>$process['cuit']))->get('procesos')->row_array();

        if(!empty($exist)){
        // ya existe     
            $err=array(
            'model'=>'save_process',
            'msg'=>'update de cuit',
            'idComunicacion_old'=>$exist['idComunicacion'],
            'idComunicacion_new'=>$process['idComunicacion'],
            'cuit'=>$exist['cuit']
            );
            $this->log($err);
            // vuela de Q y de P
            $this->afip_db->where(array('idComunicacion'=>$exist['idComunicacion']))->delete('queue');
            $this->afip_db->where(array('cuit'=>$process['cuit']))->delete('procesos');
        }


        $this->afip_db->insert('procesos',$process);

    }


    //== Guarda en Queue
    function save_queue($queue){  
        //== Si Q esta ready, actualizo P con isPyme de esta manera P tiene el estado final si es que tiene vinculadas
        if($queue['status']=='ready'){
            $query=array('cuit'=>$queue['cuit']);
            $data=array('result.isPyme'=>$queue['isPyme']);
            $this->afip_db->where($query);
            $this->afip_db->update('procesos',$data);
        }
         $this->afip_db->switch_db('afip');
         $this->afip_db->insert('queue',$queue);
    }



    //== Trae actividades 
   function idrel() {
        $this->afip_db->switch_db('dna3');
        $g750=$this->afip_db->where(array('idop'=>749))->get('options')->row();

        return $g750;
    }


    function obtenerQueueReady()
    {
        $this->afip_db->switch_db('afip');
        return $this->afip_db->where(array('status'=>'ready'))->get('queue', 999)->result_array();
    }


    
    function get_log($query)
    {
        return $this->afip_db->where($query)->get('logs')->result_array();
    }
    
    function delete_log($query)
    {
        // $this->afip_db->debug=true;
        return $this->afip_db->where($query)->delete('logs');
    }
    
    //== Errors
    function log($log)
    {
        $this->afip_db->switch_db('afip');
        $log['date']=new MongoDate(time());
        $this->afip_db->insert('logs',$log);
    }

    //== Acceso al queue
    function comunicacion_exist($idComunicacion,$transaccion)
    {
        $this->afip_db->switch_db('afip');
        return $this->afip_db->where(array('idComunicacion'=>$idComunicacion,'transaccion'=>$transaccion))->get('raw_ventanilla')->row();
    }

    //== Get process
    //== Get process
    function get_process($query,$fields = null,$order=null)
    {
        $this->afip_db->switch_db('afip');
        $this->afip_db->where($query);
        
        if($fields)
            $this->afip_db->select($fields);
        if($order)
            $this->afip_db->order_by($order);
            
        return $this->afip_db->get('procesos')->result();
    }

    //== Get raw
    function get_raw($idComunicacion)
    {
        $this->afip_db->switch_db('afip');
        $query=array('idComunicacion'=>$idComunicacion);
        return $this->afip_db->where($query)->get('raw_ventanilla')->result();
    }

    //== Acceso al queue
    function get_queue($query)
    {
        $this->afip_db->switch_db('afip');
        $res=$this->afip_db->where($query)->get('queue', 999)->result();
        return $res;    

    }

     //== Cambia el status del que
    function update_queue($cuit, $data){
        $this->afip_db->switch_db('afip');
        $query=array('cuit'=>$cuit);
        $this->afip_db->where($query);
        $this->afip_db->update('queue',$data);

    }


    //=== Determina rapidamente si un cuit es pyme, basado en P y Q - p->isPyme es temporal si en Q->status !- ready

    function is_pyme($cuit){
        $this->afip_db->switch_db('afip');
        $query=array('cuit'=>$cuit);
        $proceso=$this->afip_db->where($query)->get('procesos')->row();

        if(!empty($proceso)){
            // No es pyme 
            if($proceso->result['isPyme']==0){
                return 0;
            }else{
                if($proceso->incorporaVinculada==0){
                    // Es pyme , y no tiene vinculadas
                    return 1;
                }else{
                    // Es pyme , tiene vinculadas 
                    $enQueue=$this->get_queue($query);

                    if(empty($enQueue)){
                        // No esta en Q, el estado final es el de P
                        return $proceso->result['isPyme'];
                    }else{
                        // Esta en Q, si es ready , el resultado final se copio a P
                        $enQueue=$enQueue[0];        
                        if($enQueue->status=='ready'){                      
                            return $proceso->result['isPyme'];
                        }else{
                            return false;
                        }
                    }


                }
            }
            return $proceso->result['isPyme'];
        } 



        return false;
        
        

       



    }

    /**
     * Count eventanilla
     */
     
    function get_raw_count($query=array()){
        $rs=$this->afip_db
        ->where($query)
        ->count_all_results('raw_ventanilla');
        return $rs;
    }
    /**
     * Count process
     */
     
    function get_process_count($query=array()){
        $rs=$this->afip_db
        ->where($query)
        ->count_all_results('process');
        return $rs;
    }
    /**
     * QUEUE DISTINCT
     */
     
    function get_queue_distinct(){
        $aquery=array(
                'aggregate'=>'queue',
                'pipeline'=>
                 array(
                    //---group
                        array(
                        '$group'=>array(
                        '_id'=>'$status',
                        'count'=>array('$sum'=>1),
                        )
                    )
                ),
            );
        // var_dump(json_encode($aquery,JSON_PRETTY_PRINT));    
        //   $this->mongowrapper->switch_db('afip_db');
        $ag=$this->afip_db->command($aquery);
        foreach ($ag['result']as $resultado)
            $rs[$resultado['_id']]=$resultado['count'];
        $rs['total_queue']=$this->afip_db
        ->count_all_results('queue');
        
        return $rs;
    }
   
   /**
    * Devuelve las estadisticas de las empresas a revisar
    */
    function get_revision_stats(){
        $aquery=array(
                'aggregate'=>'queue',
                'pipeline'=>
                    array(
                      array (
                        '$match' => array ('status' => 'revision'),
                      ),
                      array (
                        '$lookup' => 
                        array (
                          'from' => 'procesos',
                          'localField' => 'transaccion',
                          'foreignField' => 'transaccion',
                          'as' => 'empresa',
                        ),
                      ),
                      array (
                        '$group' => 
                        array (
                          '_id' => '$empresa.formaJuridica',
                          'cant' => 
                          array (
                            '$sum' => 1,
                          ),
                        ),
                      ),
                      array (
                        '$unwind' => '$_id',
                      ),
                      array(
                         '$project' =>array('cant'=>'$cant','tipo'=>'$_id','_id'=>0)
                          ),
                      array (
                        '$sort' => 
                        array (
                          'cant' => -1,
                        ),
                      ),
                    )
                    );
        // var_dump(json_encode($aquery,JSON_PRETTY_PRINT));    
        //   $this->mongowrapper->switch_db('afip_db');
        $rs=$this->afip_db->command($aquery);
        return $rs['result'];
    }
     /**
    * Devuelve las empresas de las empresas a revisar
    */
    function get_revision_empresas(){
        $aquery=array(
                'aggregate'=>'queue',
                'pipeline'=>
                  array(
                      array (
                        '$match' => array ('status' => 'revision'),
                      ),
                      array (
                        '$lookup' => 
                        array (
                          'from' => 'procesos',
                          'localField' => 'transaccion',
                          'foreignField' => 'transaccion',
                          'as' => 'empresa',
                        ),
                      ),  
                    )
                    );
        // var_dump(json_encode($aquery,JSON_PRETTY_PRINT));    
        //   $this->mongowrapper->switch_db('afip_db');
        $rs=$this->afip_db->command($aquery);
        foreach($rs['result'] as &$r){
            $r=array_merge($r,$r['empresa'][0]);
            
        }
        return $rs['result'];
    }


    function ready_1273_qry() {
      $this->load->model('afip/Eventanilla_model');  
      $query = array('$exists'=>'true');
    


      if(isset($parameter)){
        $query = $parameter;
        } 

    $inputDate = "2016-07-12";
    $dateFilter = new MongoDate(strtotime($inputDate));

    $this->afip_db->switch_db('afip');
    $rtn = $this->afip_db->where(array('1273'=>$query, 'date'=> array('$lte' =>  $dateFilter)))->get('raw_ventanilla')->result_array();
    
    $rtn_cuits = array();

    foreach ($rtn as $key => $value) {    
         $cuit = $value['cuit'];   
         $print_cuit = $cuit. "<br>";          

         $is_pyme = $this->Eventanilla_model->is_pyme($cuit);
         #var_dump($is_pyme, $cuit);

         $rtn_arr = array(0,1);
         if(in_array($is_pyme, $rtn_arr)){
             $find = array('content' => new MongoRegex("/$cuit/"));
             $result_count = $this->afip_db->where($find)->get('raw_seti')->count();
             
             
             if($result_count==0){
                $rtn_cuits[] = $value['idComunicacion'];
                # print_r($print_cuit,$value['idComunicacion']); 
             }           
         }

        
      
         
        
    }
    #fix   
    return $rtn_cuits;
}

    function mark_reprocessed($idComunicacion){

        /*UPDATE RAW VENTANILLA con Readies*/           
            $data = array('reprocesado'=>new MongoDate(time()));
            $query=array('idComunicacion'=>$idComunicacion);
            $this->afip_db->where($query);
            $do_update = $this->afip_db->update('raw_ventanilla',$data);    
    }

}
