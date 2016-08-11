<?php

class portal_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->idu = (int) $this->session->userdata('iduser');
        $this->load->library('cimongo/cimongo');
        $this->db = $this->cimongo;
        $this->load->model('bpm/bpm');

        $this->afip_db=new $this->cimongo;
        $this->afip_db->switch_db('afip');

    }
    
    
    function get_empresas(){


        // $collection = 'container.empresas';
        // $query = array(
        //     'owner'=> $this->idu,
        //     '1695'=> array('$exists'=>true),
        //     '1693'=> array('$exists'=>true),
        //     );
        // // $this->db->select(array('id','status',1693,1695));
        // $this->db->where($query);
        // $this->db->order_by(array('1693'=>1));
        
        // $rs = $this->db->get($collection);
        // return $rs->result_array();
    }

    function get_afip_data($cuit){
            $this->afip_db->switch_db('afip');
	    $query = array(
            'cuit'=> new MongoInt64($cuit)
            );
           $rs=$this->afip_db->where($query)->get('procesos');
	   return $rs->row();
    }

    function cuits_by_idu_model($idu){
           
        $query = array(
            'idu'=> new MongoInt64($idu)
            );
           $rs=$this->db->where($query)->get('users');
          
        return $rs->row()->cuits_relacionados;
          
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
                    #$enQueue=$this->get_queue($query);

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


    /*relacion cuits users update*/
    function cuit_representadas_update($query, $data){ 
         
        $collection = 'users';        
        $action = array('$addToSet' => array('cuits_relacionados' => $data));
        $options = array('upsert' => true);
        $result = $this->mongowrapper->db->$collection->update($query, $action, $options);
        return $result;
        

    }


}
