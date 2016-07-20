<?php

// if (!defined('BASEPATH'))
//     exit('No direct script access allowed');
/**
 * "ventanilla electrónica" de la AFIP
 * 
 * @autor Diego Otero
 * 
 * @version 	1.0 
 * 
 * 
 */
 

 
class Eventanilla extends MX_Controller {

    function __construct() {
        parent::__construct();

        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
        
        #LIBRARIES
        $this->load->library('parser');
        $this->load->library('dashboard/ui');
        
        
        #MODELS
        $this->load->model('afip/eventanilla_model');
        $this->load->model('afip/consultas_model');

        
        #CREDENTIALS
        $this->idu = (int) $this->session->userdata('iduser');
        if(ENVIRONMENT<>'127.0.0.1_afip')
            $this->user->authorize();
        
        //---Output Profiler
        //$this->output->enable_profiler(TRUE);
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
        ini_set('xdebug.var_display_max_depth', 120 );


    }
    

#================================================
    //=== Ventanilla Raw
#================================================ 
    
    function save_raw_ventanilla($raw='',$overwrite=false){

        $my_process=$this->eventanilla_model->save_raw_ventanilla($raw,$overwrite);

        // Evita ids repetidos 
        if($my_process===false && $overwrite===false)return false;

        $q=array();
        $sendToQueue=true;
        $q['idComunicacion']=$my_process['idComunicacion'];
        $q['transaccion']=$my_process['transaccion'];
        $q['date']=new MongoDate(time());
        $q['cuit']=$my_process['cuit'];
        $q['status']= 'ready';
        $q['solicitaPagoTrimestral']=(empty($my_process['solicitaPagoTrimestral']))?('N'):('S');

        //=== Monotributo sale derecho

        // if($my_process['monotributo']==1){
        //     $my_process['result']['isPyme']=$q['isPyme']=1;
        //     $my_process['result']['sector']=$q['sector'];
        //     $my_process['result']['sector_texto']=$q['sector_texto']=4;
        //     $my_process['result']['categoria']=$q['categoria']='micro';
        //     $this->eventanilla_model->save_process($my_process);
        //     // Queue
        //     $q['status']= 'ready';
        //     $this->eventanilla_model->save_queue($q);

        //     return;
        // }

        //=== Guardo en Procesos

        $my_process['result']=$this->preprocess($my_process); 

        //=== Error salgo
        if($my_process['result']===false)return false;


        $q['isPyme']=$my_process['result']['isPyme'];
        $this->eventanilla_model->save_process($my_process);
        

        /**
        * POST CHECK forma juridica
        */
        $formasMasinformacion=array(
            'AGRUP COLABORACION',
            'CONDOMINIO',
            'CONS. PROPIET.',
            'CONSORCIOS DE COOPERACION',
            'COOPERATIVA',
            'COOPERATIVA EFECTORA',
            'UNION TRANSIT.',
            'MUTUAL',
            
            );
        $formasExcluidas=array(
            'ECONOMIA MIXTA',
            'EMP. DEL ESTADO',
            'C/P.EST. MAY.',
            'ORGAN. PUBLICO',
            'ORGAN. PUBLICO INTERNACIONAL',
            'DIR ADM ESTATAL',
            'GARANT. RECIP.',
            'FONDO COMUN DE INVERSION',
            'FIDEICOMISO FINANCIERO',
            'FIDEICOMISO PUBLICO',
            'ENTIDADES DE DER. PUB. NO EST.',
            'ASOCIACIÓN CIVIL EXTRANJERA',/// en la tabla vino con espacio
            'ASOC. CIVIL EXT. ACTO AISLADO',
            'AG.POL. - PARTIDO POLÍTIC0',
            'AG. POL. - ALIANZA TRANSITORIA',
            'AG. POL. - CONFEDERAC. DE PART',
            'FIDEICOMISO TESTAMENTARIO',
            'IGLESIA CATOLICA',
            'GLESIAS, ENTIDADES RELIGIOSAS',
            );
        //----para pedir más información
        if(isset($my_process['formaJuridica']) && in_array($my_process['formaJuridica'],$formasMasinformacion) && $q['isPyme']==1){
            $q['status']='revision';
            $q['status_extra']='formaJuridica';
        }

        //---@todo
        //----Excluidas
        if(isset($my_process['formaJuridica']) && in_array(trim($my_process['formaJuridica']),$formasExcluidas)){
            $my_process['result']['isPyme']=0;
        }
        

        //== excentoIva =1 
        if(!empty($my_process['exentoIva']) && $q['isPyme']==1){
            $q['status']='revision';
            $q['status_extra']='exentoIva';
        }

        
         if($sendToQueue){
            # status : ready | waiting | revision 

            if(isset($my_process['result']['categoria']))$q['categoria']=$my_process['result']['categoria'];
            if(isset($my_process['result']['actividad']))$q['actividad']=$my_process['result']['actividad'];
            if(isset($my_process['result']['sector']))$q['sector']=$my_process['result']['sector'];
            
            

            if($my_process['incorporaVinculada']==1 && !empty($my_process['vinculadas']->detalles) && $q['isPyme']==1){
                // Ready
                $q['status']= 'waiting';
                $q['status_extra']='incorporaVinculada & isPyme==1';
            }

                $this->eventanilla_model->save_queue($q);

         }



    }
    
    //=== Preproceso

    private function preprocess($data){

        // agarro los periodos
        $periodos=array();
        foreach($data as $k=>$v){
        $pattern = '/^periodoFiscal([0-9]{4})/';
        preg_match($pattern, $k, $matches);
            if(!empty($matches[1]) && is_numeric($matches[1]) )
                if(!empty($v->actividades))       
                    $periodos[$matches[1]]=$v;
        } 
      

        // Sin ventas
        if(empty($periodos)){
            return $this->preprocessSinVentas($data);
        }


        // Ordeno años, y elimino el ultimo si no tiene actividades
        ksort($periodos);
        $coef=1; 
        $limit=(count($periodos)>3)?(3):(count($periodos));


        // Saco promedio de periodos y 
        $actividades=array();
        $total=0;
        $i=0;

        foreach($periodos as $p){
           $total_year=0;
           foreach($p->actividades as $myact){
                // Si es solo un año , saco proporcional
           
                if(!isset($myact->monto))$myact->monto=0;

                if($limit==1){
                    $desde = date_create_from_format('Ym', (string)$myact->periodoDesde);
                    $hasta = date_create_from_format('Ym', (string)$myact->periodoHasta);
                    $interval = $desde->diff($hasta);
                    $coef=$interval->m/12;
                }

                $total_year+=$myact->monto*$coef;
                // Sumo los montos de todos los años de cada actividad para elegir la que va
                if(empty($actividades[$myact->codActividad]))$actividades[$myact->codActividad]=0;
                $actividades[$myact->codActividad]+=$myact->monto;     
           }
           $total+=$total_year; 
           $i++;
           if($i>=$limit)break;
       }


      $response['promedio']=($i==0)?(0):($total/$i); 
      $response['actividades']=$actividades;

    // Actvidad de mayor ingreso

       $max=max($actividades);
       $key = array_search($max, $actividades); // $key = 2;
       $response['actividad']=str_pad((string)$key,6,'000',STR_PAD_LEFT); // Padding a string 6 digitos


    //=== Calculo Clasificacion
      $response+=$this->idrel($response['actividad']);

      // excentoIva exception 
        if(empty($periodos) && $data['exentoIva']===1){
            $response['isPyme']=0;
            return $response;
        }

    // Error
    if(empty($response['sector'])){
        $err=array(
            'msg'=>'No se pudo determinar sector',
            'data'=>$response['actividad'],
            'idComunicacion'=>$data['idComunicacion'],
            'class'=>'error'
        );
        $this->eventanilla_model->log($err);
        return false;
    }

    // Idrel No entran en pyme
    $halfAct=substr($response['actividad'], 0,3);
    if(in_array($response['idrel'],array('K','T','U','O')) || ($response['idrel']=='R' && $halfAct =='920')){
         $response['isPyme']=0;

         return $response;
    }

      
    $montos[1]=array('micro'=> 2000000,'peq'=>13000000,'tramo1'=>100000000,'tramo2'=>160000000); // Agro
    $montos[2]=array('micro'=> 7500000,'peq'=>45500000,'tramo1'=>360000000,'tramo2'=>540000000); // Ind
    $montos[3]=array('micro'=> 7500000,'peq'=>45500000,'tramo1'=>360000000,'tramo2'=>540000000); // Min
    $montos[4]=array('micro'=> 2500000,'peq'=>15000000,'tramo1'=>125000000,'tramo2'=>180000000); // Servicios
    $montos[5]=array('micro'=> 3500000,'peq'=>22500000,'tramo1'=>180000000,'tramo2'=>270000000); // Construccion
    $montos[6]=array('micro'=> 9000000,'peq'=>55000000,'tramo1'=>450000000,'tramo2'=>650000000); // Comercio



    $myclass='';
    $sector=$montos[$response['sector']];
    krsort($sector);
    //var_dump($sector);
    foreach ($sector as $clasificacion=>$cant)
        if($response['promedio']<=$cant)$myclass=$clasificacion;
    
    $response['date']=new MongoDate(time());
    if(empty($myclass)){
        // No clasifica como pyme
         $response['isPyme']=0;

    }else{
        // Bingo!
        $response['isPyme']=1;
        $response['categoria']=$myclass;

    }

    return $response;

    }


#================================================
    //=== Sin ventas
#================================================ 

private function preprocessSinVentas($data){

    if(!isset($data['idActividadPrincipal']))return false;
    $act=$data['idActividadPrincipal'];
    $act=str_pad((string)$act,6,'000',STR_PAD_LEFT);
    $response=$this->idrel($act);

    // Error
    if(empty($response['sector'])){
        $err=array(
            'msg'=>'No se pudo determinar sector',
            'data'=>$response['actividad'],
            'idComunicacion'=>$data['idComunicacion'],
            'class'=>'error'
        );
        $this->eventanilla_model->log($err);
        return false;
    }

    // Idrel No entran en pyme
    $halfAct=substr($act, 0,3);
    if(in_array($response['idrel'],array('K','T','U','O')) || ($response['idrel']=='R' && $halfAct =='920')){
         $response['isPyme']=0;
         return $response;
    }

    $response['isPyme']=1;
    $response['categoria']='micro';
    $response['actividad']=$act;
    $response['date']=new MongoDate();
    return $response;
}

#================================================
    //=== Determino Letra
#================================================ 

    private function idrel($id){



        $g750=$this->eventanilla_model->idrel();

        $excep1=array('591110'=>'J','591120'=>'J','602320'=>'J','631200'=>'J');
        $excep2=array('620100'=>'J','620200'=>'J','620300'=>'J','620900'=>'J');

        $tabla[1]=array('A');
        $tabla[2]=array('C',);
        $tabla[3]=array('B');
        $tabla[4]=array('D','E','H','I','J','M','N','P','Q','R','S','K','L','T','U','O');
        $tabla[5]=array('F');
        $tabla[6]=array('G');

        $idrel='';
        $halfId=substr($id, 0,3);
        $ret=array();

        for($i=0;$i<count($g750->data);$i++){
            if($g750->data[$i]['value']==$halfId){
                $idrel=$g750->data[$i]['idrel'];
                break;
            }
        }


        if(array_key_exists($id,$excep1) || array_key_exists($id,$excep2) ){
            // excepciones
                $ret['idrel']='J';
                $ret['sector']=2;
        }else{
            // Buscamos sector para el resto
            foreach($tabla as $k=>$v){
                if(in_array($idrel,$v)){
                    $ret['idrel']=$idrel;
                    $ret['sector']=$k;
                    break;
                }
            }
        }


        //---guardo el sector en texto
        $tablas[1]='Agropecuario';
        $tablas[2]='Industria';
        $tablas[3]='Minería';
        $tablas[4]='Servicios';
        $tablas[5]='Construccion';
        $tablas[6]='Comercio';
        $ret['sector_texto']=$tablas[$ret['sector']];

        return $ret;

    }



#================================================
    //=== Cron - Proceso de estado waiting
#================================================ 

    function process_waiting_queue(){

        $waiting=$this->eventanilla_model->get_queue(array('status'=>'waiting'));
        $log=array();
        $debug=false;

        // Loop de cuits en waiting
        foreach($waiting as $myQ){
         
            $process=$this->eventanilla_model->get_process(array('cuit'=>$myQ->cuit));
            $process=array_pop($process);
            $grupo=array();
            
           // Error: improbable pero... 
            if(empty($process) && !$debug){
                $err=array(
                    'controller'=>'process_waiting_queue',
                    'msg'=>'No se encuentra el cuit en procesos',
                    'cuit'=>$myQ->cuit,
                    'class'=>'error'
                );
                $this->eventanilla_model->log($err);
                return false;
            }

           //== Loop Vinculadas 

            $data['status']='ready';

            //== Salta el error de vinculadas=1

            if(empty($process->vinculadas['detalles'])){
                $log[$myQ->cuit]='Vinculada vacia';
                if(!$debug)
                    $this->reprocess($process->idcomunicacion);
                continue;
            }
            

            //=== Loop Vinculadas
            foreach($process->vinculadas['detalles'] as $vinculadas){
                $cuit=$vinculadas['cuit'];

                // Extranjeros - Cambio de status
                if($vinculadas['vinculacion']==2 ){
                    if(isset($vinculadas['pais']) && $vinculadas['pais']!=200){
                        $data['status']='revision';
                        $log[$myQ->cuit][$cuit]['status']='revision';
                        break;
                    }
                }

                // Tier1
                if(($vinculadas['vinculacion']==2 || $vinculadas['vinculacion']==3) && $cuit!=$myQ->cuit){
                    $isPyme=$this->eventanilla_model->is_pyme($cuit);
                    $log[$myQ->cuit][$cuit]['isPyme']=$isPyme;
                    
                    // Hay en espera?
                    if($isPyme===false){
                        $data['status']='waiting';
                        $log[$myQ->cuit][$cuit]['status']='waiting';
                        break;
                    }   

                    // Una no es Pyme cierro
                    if($isPyme===0){
                        $data['status']='ready';
                        $data['isPyme']=0;
                        $log[$myQ->cuit][$cuit]['status']='ready';
                        break;
                    }

                }
      

                // Grupos economicos
                // if($vinculadas['vinculacion']==2 ){
                //     $vinculadaProcess=$this->eventanilla_model->get_process(array('cuit'=>$cuit));
                //     if(!empty($vinculadaProcess)){
                //     $vinculadaProcess=$vinculadaProcess[0]; 
                //     $detalles=$vinculadaProcess->vinculadas['detalles'];
                //         foreach($vinculadaProcess->vinculadas['detalles'] as $det){
                //             if($det['vinculacion']==3)
                //                 $grupo[$det['cuit']][]=$cuit;
                //         }
                //     }
                // }

            } 
            //--- ./loop vinculadas

            // Son todas pymes y no hay en espera
            // if(!empty($isPyme) && !$waiting){
            //     $ready=true;

            //     //== Verifico si hay grupo economico antes de largarlo
            //     // if(!empty($grupo)){
            //     //     $ready=false;
            //     //     //$this->check_group($grupo);
            //     //     var_dump($grupo);
            //     // }

            // }


            $log[$myQ->cuit]['status']=$data;

            //==== Log de cambio de estado
            if($data['status']=='ready' && !$debug){
                $err=array(
                    'controller'=>'process_waiting_queue',
                    'msg'=>'Cambio de estado -> Ready',
                    'cuit'=>$myQ->cuit,
                    'class'=>'log'
                );
                $this->eventanilla_model->log($err);
            }

            if(!$debug)
                $this->eventanilla_model->update_queue($myQ->cuit,$data);

               
        }/// ./loop

        echo json_encode($log);
    }
    


    //=== Grupo economico

    function check_group($grupo){
        foreach($grupo as $cont=>$mygroup){
            $mygroup[]=$cont;
        }
        var_dump($mygroup);
    }



    //=== Guardo crudo de seti
    
    function reprocess($idComunicacion){

        $raw=$this->eventanilla_model->get_raw((integer)$idComunicacion);

        if(empty($raw))return false;
        
        $raw=array_pop($raw);
        $params =  array("idComunicacion" => $raw->idcomunicacion,'content'=>$raw->raw);
        $this->save_raw_ventanilla($params,true);
        return true;
    }
    
    /**
     * Reprocess empty periodos as Micro
     */ 
    
    function reprocess_log(){
        $query=array(
             "msg"=>"No hay periodos informados"
            );
        $logs=$this->eventanilla_model->get_log($query);
        echo "Procesando ".count($logs).'<hr>';
        $i=0;
        foreach($logs as $log){
            $i++;
            if($this->reprocess($log['idComunicacion'])){
                echo "<h3>$i:".$log['idComunicacion'].' Ok!</h3><hr/>';
                $lquery=array(
                    'idComunicacion'=>$log['idComunicacion'],
                    'transaccion'=>$log['transaccion']
                    );
                $this->eventanilla_model->delete_log($lquery);
            } else {
                echo '<h3 style="color:red">'.$i.':'.$log['idComunicacion'].' ERROR!</h3><hr/>';
            }
            
            
        }

    }
    function reprocess_12(){
        $this->afip_db->switch_db('afip');
        $logs=$this->afip_db->where(
            array('queue'=>array('$eq'=>null))
            )
            ->get('analisis')->result_array();
        echo "Procesando ".count($logs).'<hr>';
        $i=0;
        foreach($logs as $log){
            $i++;
            if($this->reprocess($log['idComunicacion'])){
                echo "<h3>$i:".$log['idComunicacion'].' Ok!</h3><hr/>';
                $lquery=array(
                    'idComunicacion'=>$log['idComunicacion'],
                    'transaccion'=>$log['transaccion']
                    );
                $this->eventanilla_model->delete_log($lquery);
            } else {
                echo '<h3 style="color:red">'.$i.':'.$log['idComunicacion'].' ERROR!</h3><hr/>';
            }
            
            
        }

    }
    function reprocess_noactividad(){
        $this->load->model('eventanilla_model');
        
        /**
         * { "result.sector_texto": { $exists: false } }
         */ 
        $logs=$this->eventanilla_model->afip_db->where(
            array('result.actividad'=>array('$exists'=>false))
            )
            ->get('procesos')->result_array();
        echo "Procesando Sin Actividad: ".count($logs).'<hr>';
        $i=0;
        foreach($logs as $log){
            $i++;
            $log['result']=$this->preprocessSinVentas($log);
            var_dump($log['result']);echo "<hr>";
            $this->eventanilla_model->save_process($log);
            
            
        }

    }
        
        
        



    #FIX seti
    function fix_1273(){  
        $rtn = $this->eventanilla_model->ready_1273_qry();
        
        foreach ($rtn as $value) {
            //var_dump($value);
            $idComunicacion = $this->reprocess($value);
            $this->eventanilla_model->mark_reprocessed($value);
        }
                
    }
    function index(){
    }

    function test(){

        // Para testing

        $dummy=$this->load->view('json/sinventas.json', '', true); # @debug
         //$dummy=$this->load->view('json/grupos_l1.json', '', true); # @debug
        //$dummy=$this->load->view('json/grupos_l2a.json', '', true); # @debug
         // $dummy=$this->load->view('json/grupos_l2b.json', '', true); # @debug
         // $dummy=$this->load->view('json/grupos_l3.json', '', true); # @debug
        // $dummy=$this->load->view('json/micro.json', '', true); # @debug
         $idCom=rand(0, 9999);
         //$idCom=9330;
         $params =  array("idComunicacion" => $idCom, 'content'=>$dummy);
         $this->save_raw_ventanilla($params);

    }


    
}//class
