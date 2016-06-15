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

        
        #CREDENTIALS
        $this->idu = (int) $this->session->userdata('iduser');
        $this->user->authorize();
        
        //---Output Profiler
        //$this->output->enable_profiler(TRUE);
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
        ini_set('xdebug.var_display_max_depth', 120 );


    }
    
    function Index(){

        // Para testing

         $dummy=$this->load->view('json/test_vinculadas.json', '', true); # @debug
        // $dummy=$this->load->view('json/grande.json', '', true); # @debug
         $idCom=rand(0, 9999);
         //$idCom=9330;
         $params =  array("idComunicacion" => $idCom, 'content'=>$dummy);
         $this->save_raw_ventanilla($params);

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
        
        // Error: kansas is going bye bye
        if(empty($periodos)){
            $err=array(
                'msg'=>'No hay periodos informados',
                'idComunicacion'=>$data['idComunicacion'],
                'transaccion'=>$data['transaccion'],
                'class'=>'error'
            );
            $this->eventanilla_model->log($err);
            return false;
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
    if(in_array($response['idrel'],array('K','L','T','U','O')) || ($response['idrel']=='R' && $halfAct =='920')){
         $response['isPyme']=false;

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

//=== determino letra

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



//=== Ventanilla Raw
    
    function save_raw_ventanilla($raw=''){

        $my_process=$this->eventanilla_model->save_raw_ventanilla($raw);

        // Evita ids repetidos 
        if($my_process===false)return false;


        $q=array();
        $sendToQueue=true;
        $q['idComunicacion']=$my_process['idComunicacion'];
        $q['transaccion']=$my_process['transaccion'];
        $q['date']=new MongoDate(time());
        $q['cuit']=$my_process['cuit'];
        $q['status']= 'ready';

        //=== Monotributo sale derecho

            if($my_process['monotributo']==1){
                $my_process['result']['isPyme']=$q['isPyme']=1;
                $my_process['result']['sector']=$q['sector']=4;
                $my_process['result']['categoria']=$q['categoria']='micro';
                $this->eventanilla_model->save_process($my_process);
                // Queue
                $q['status']= 'ready';
                $this->eventanilla_model->save_queue($q);

                return;
            }

        //=== Guardo en Procesos

        $my_process['result']=$this->preprocess($my_process);
        $q['isPyme']=$my_process['result']['isPyme'];
        if($my_process['result']==false)return false;
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
        if(in_array($my_process['formaJuridica'],$formasMasinformacion)){
            $q['status']='revision';
        }
        //---@todo
        //----Excluidas
        if(in_array(trim($my_process['formaJuridica']),$formasExcluidas)){
            $my_process['result']['isPyme']=0;
        }
        

         if($sendToQueue){
            # status : ready | waiting | revision 
            
            if(isset($my_process['result']['categoria']))$q['categoria']=$my_process['result']['categoria'];
            if(isset($my_process['result']['actividad']))$q['actividad']=$my_process['result']['actividad'];
            if(isset($my_process['result']['sector']))$q['sector']=$my_process['result']['sector'];

            if($my_process['incorporaVinculada']==1 && $q['isPyme']==1){
                // Ready
                $q['status']= 'waiting';
            }

                $this->eventanilla_model->save_queue($q);

         }



    }


    //=== Cron Queue
    
    function process_waiting_queue(){

        $waiting=$this->eventanilla_model->get_queue(array('status'=>'waiting'));

        // Loop de cuits en waiting
        foreach($waiting as $myQ){
          //  
            var_dump('---'.$myQ->cuit.'----');
            $process=$this->eventanilla_model->get_process(array('cuit'=>$myQ->cuit));
            $process=array_pop($process);
            $grupo=array();
            
           // Error: improbable pero... 
            if(empty($process)){
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
            $ready=false;
            $waiting=false;
            foreach($process->vinculadas['detalles'] as $vinculadas){
                $cuit=$vinculadas['cuit'];

                // Tier1
                if($vinculadas['vinculacion']==2 || $vinculadas['vinculacion']==3){
                    $isPyme=$this->eventanilla_model->is_pyme($cuit);
                    var_dump($cuit.' isPyme:'.$isPyme);
                    // Hay en espera?
                    if($isPyme===false)
                        $waiting=true;
                    
                    // Una que no es pyme, cerramos
                    if($isPyme===0){
                        $ready=true;
                        break;
                    }
                    
                }
      

                // Grupos economicos
                if($vinculadas['vinculacion']==2 ){
                    $vinculadaProcess=$this->eventanilla_model->get_process(array('cuit'=>$cuit));
                    if(!empty($vinculadaProcess)){
                    $vinculadaProcess=$vinculadaProcess[0]; 
                        foreach($vinculadaProcess->vinculadas['detalles'] as $det){
                            if($det['vinculacion']==3)
                                $grupo[$det['cuit']][]=$cuit;
                        }
                    }
                }

            } // ./vinculadas

            // Son todas pymes y no hay en espera
            if(!empty($isPyme) && !$waiting){
                $ready=true;

                //== Verifico si hay grupo economico antes de largarlo
                if(!empty($grupo)){
                    $ready=false;
                    $this->check_group($grupo);
                }

            }





           var_dump('>>>> Final <<<<<',$ready,$isPyme); 
           
            if($ready){
                 $data=array('isPyme'=>$isPyme,'status'=>'ready');
                 //$this->eventanilla_model->update_queue($myQ->cuit,$data);

            }

               
        }/// ./loop
    }
    


    //=== Grupo economico

    function check_group($grupo){
        foreach($grupo as $cont=>$mygroup){
            $mygroup[]=$cont;
            $mygroup[]=$cont;   

        }
        var_dump($mygroup);
    }



    //=== Guardo crudo de seti
    
    function save_raw_seti(){

    }






    
}//class
