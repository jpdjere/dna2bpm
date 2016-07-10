<?php

class Consultas_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->idu = (int) $this->session->userdata('iduser');
        $this->load->library('cimongo/Cimongo.php', '', 'sgr_db');
        $this->sgr_db->switch_db('sgr');        
    }


    /**
     * Buscar CUITS status
     *
     * @name buscar_cuits_registrados
     *
     * @see SGR()
     *
     * @author Diego Otero <daotero@industria.gob.ar>
     *
     * @date Apr 19, 2016
     *
     * @param type $query
     */
    function buscar_cuits_registrados($parameter=null, $collection='container.sgr_anexo_06') {
       

        $suma_query=array(
                'aggregate'=>'container.sgr_periodos',
                'pipeline'=>
                  array(
                      array (
                        '$lookup' => array (
                            'from' => 'container.sgr_anexo_06',
                            'localField' => 'filename',
                            'foreignField' => 'filename',
                            'as' => 'anexo')                        
                      ),
                      array ('$unwind' => '$anexo'),  
                       array ('$unwind' => '$anexo.5272'), 
                      array ('$match' => array (
                        'anexo.1695'  => $parameter, 
                        'status'=>'activo' ,                        
                        )),  
                      array(
                        '$group' => array(
                        '_id' => null,                    
                        'suma' => array('$sum' => '$anexo.5597'),
                        'cuit' => array('$first' => '$anexo.1695'), 
                        'rs' => array('$first' => '$anexo.1693'), 
                        'tipo_socio' => array('$first' => '$anexo.5272')
                        ),
                    ), 

                ));

        $resta_query=array(
                'aggregate'=>'container.sgr_periodos',
                'pipeline'=>
                  array(
                      array (
                        '$lookup' => array (
                            'from' => 'container.sgr_anexo_06',
                            'localField' => 'filename',
                            'foreignField' => 'filename',
                            'as' => 'anexo')                        
                      ),
                      array ('$unwind' => '$anexo'),  
                      array ('$match' => array (
                        'anexo.5248'  => $parameter, 
                        'status'=>'activo' ,                        
                        )),  
                      array(
                        '$group' => array(
                        '_id' => null,                    
                        'resta' => array('$sum' => '$anexo.5597') 
                        ),
                    ), 

                ));
    
         $suma=$this->sgr_db->command($suma_query);   
         $resta=$this->sgr_db->command($resta_query);            
         
         $balance = ($suma['result'][0]['suma']-$resta['result'][0]['resta']);


         if($balance>0){
            return $suma['result'][0];
         }
   }


    /**
     * Buscar CUITS status
     *
     * @name buscar_cuits_registrados
     *
     * @see SGR()
     *
     * @author Diego Otero <daotero@industria.gob.ar>
     *
     * @date Apr 19, 2016
     *
     * @param type $query
     */
    function buscar_cuits_vinculados($parameter=null, $collection='container.sgr_anexo_06') { 

        $esVinculado = 'SI';
        $suma_query=array(
                'aggregate'=>'container.sgr_periodos',
                'pipeline'=>
                  array(
                      array (
                        '$lookup' => array (
                            'from' => 'container.sgr_anexo_061',
                            'localField' => 'filename',
                            'foreignField' => 'filename',
                            'as' => 'anexo')                        
                      ),
                    array ('$unwind' => '$anexo'),
                    array ('$match' => array (
                        'anexo.CUIT_SOCIO_INCORPORADO'  => $parameter, 
                        'anexo.CUIT_VINCULADO'  => array('$ne'=> ''), 
                        'anexo.TIENE_VINCULACION' => array('$regex' => new MongoRegex("/^$esVinculado/i")),
                        'status'=>'activo' ,                        
                    )),
                ));

      
    
         $suma=$this->sgr_db->command($suma_query);   
         return $suma['result'];           
         
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
    function cuits_certificados($parameter) {
       $this->afip_db->switch_db('afip');
       return $this->afip_db->where(array('cuit'=>$parameter))->get('procesos')->row();
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


}
