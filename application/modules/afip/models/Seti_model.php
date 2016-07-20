<?php

class Seti_model extends CI_Model {

     function __construct() {
        parent::__construct();
        //$this->idu = (int) $this->session->userdata('iduser');
        //$this->load->library('cimongo/Cimongo.php', '', 'afip_db');
        $this->afip_db=new $this->cimongo;
        #DB
        $this->afip_db->switch_db('afip');
        
         
    }

    function saveSetiCall($data)    
    {
    	  $this->afip_db->insert('raw_seti', $data); 
    }


    function saveSetiResponse($data)
    {
       $this->afip_db->insert('response_seti', $data);    
    }

    function removeFromQueue($documento)
    {
        $this->afip_db->remove('queue', $documento->_id );
    }

    function obtenerQueueReadyCount()
    {
        $this->afip_db->switch_db('afip');
        return $this->afip_db->where(array('status'=>'ready'))->get('queue')->count();
    }

    
    function update_ready_queue($fileName, $cuit){   
   
         $variable =  $this->afip_db->where(array('status'=>'ready', 'cuit'=>$cuit))->get('queue')->result_array();

         foreach ($variable as $value) {
           
            $transaccion = $value['transaccion'];
            #$cuit = $value['cuit'];

            $data_array_1273 = array('fechaInformado'=>new MongoDate(time()), 'fileName'=>$fileName);

            $data = array('1273'=>$data_array_1273);
            

            /*UPDATE RAW VENTANILLA con Readies*/
            $query=array('cuit'=>$cuit, 'transaccion'=>$transaccion);
            $this->afip_db->where($query);
            $do_update = $this->afip_db->update('raw_ventanilla',$data);    

            /*delete Ready from QUEUE*/
            if($do_update){
                $query['status'] = 'ready';                
                $this->afip_db->where($query);
                $do_delete = $this->afip_db->delete('queue');                
            }

         }

    }


    function getDateTimeFromMongoId($mongoId)
    {
    $dateTime = new DateTime('@'.$mongoId->getTimestamp());    
    return $dateTime;
    }

    function update_ready_queue_recursive($limit=4000){   
   
         $variable =  $this->afip_db->where(array('1273'=>true))->limit($limit)->get('raw_ventanilla')->result_array();


         foreach ($variable as $value) {
           
            $transaccion = $value['transaccion'];
            $cuit = $value['cuit'];

            /*GET HISTORIC SETI DATA*/
            $find = array('content' => new MongoRegex("/$cuit/"));
            $result_seti = $this->afip_db->where($find)->limit(1)->get('raw_seti')->result_array();
            

            foreach ($result_seti as $seti) {
                # code...
                $get_date = array($this->getDateTimeFromMongoId($seti['_id']));
                $array_date = json_decode(json_encode($get_date), true);
                $fechaInformado = new MongoDate(strtotime(date($array_date[0]['date'])));
                

                $data_array_1273 = array('fechaInformado'=>$fechaInformado, 'fileName'=>$seti['fileName']);
                $data = array('1273'=>$data_array_1273);
                

                #UPDATE RAW VENTANILLA con Readies
                $query=array('cuit'=>$cuit, 'transaccion'=>$transaccion);
                $this->afip_db->where($query);
                $do_update = $this->afip_db->update('raw_ventanilla',$data);

                var_dump($transaccion, $cuit);

            }


            
            /*
            $data_array_1273 = array('fechaInformado'=>new MongoDate(time()), 'fileName'=>$fileName);

            $data = array('1273'=>$data_array_1273);
            

            #UPDATE RAW VENTANILLA con Readies
            $query=array('cuit'=>$cuit, 'transaccion'=>$transaccion);
            $this->afip_db->where($query);
            $do_update = $this->afip_db->update('raw_ventanilla',$data);    */

            

         }

    }

    
}

?>