<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * test
 * 
 * Description of the class
 * 
 * @author Juan Ignacio Borda <juanignacioborda@gmail.com>
 */
class Perfil extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
        $this->load->library('parser');
        $this->load->model('portal_model');
        $this->load->model('bpm/bpm');
        //$this->load->model('afip/consultas_model');
        $this->load->model('app');
        $this->load->library('dashboard/ui');
        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
        $this->user->authorize();
        //----LOAD LANGUAGE
        $this->lang->load('perfil', $this->config->item('language'));
        $this->lang->load('dashboard/dashboard', $this->config->item('language'));
        $this->idu = $this->user->idu;

        ini_set('display_errors', 0);
        error_reporting(E_ALL);
        ini_set('xdebug.var_display_max_depth', 120 );

    }



    # ====================================
    #   Hub
    # ====================================

    function Index($cuit=null,$debug=0) {
        $this->load->model('user/user');
        $userdata=$this->user->get_user($this->idu);
        if(count($userdata->group)==1 && $userdata->group[0]==1000){
            // registro nuevo va al hub
            $this->hub();
        }else{
            var_dump($userdata->group);
            // hook
            if(in_array(1027,$userdata->group)){
                redirect('perfil/empresa');
            }elseif(in_array(1029,$userdata->group)){
                redirect('perfil/emprendedor');
            }elseif(in_array(1030,$userdata->group)){
                 redirect('perfil/incubadora');

            }elseif(in_array(1014,$userdata->group)){
                 redirect('perfil/experto');
                 
            }else{
               // $this->empresa();
                show_error('No tiene permisos');
            }   

        }



    }

    function Hub($cuit=null,$debug=0) {

         $this->load->module('dashboard');
         $this->dashboard->dashboard('perfil/json/hub.json',$debug);

    }


    function Hub_registro($cuit=null,$debug=0) {

        $customData['lang']= $this->lang->language;
        $customData['base_url'] = $this->base_url;
        $callout=array('body'=>$customData['lang']['text_registro'],'title'=>'');
        echo $this->ui->callout($callout);
        echo $this->parser->parse('perfil/hub', $customData, true, true);  


    }




    # ====================================
    #   Empresa
    # ====================================

    function registro_pyme() {

        $mygroup=1027;
        $user=$this->user->get_user($this->idu);
        $data['idu']=$this->idu;
        if(!in_array($mygroup,$user->group)){
            $user->group[]=$mygroup;
            $user->group[]=8;
            $data['group']=$user->group;
            $this->user->put_user($data);

        }
         redirect('perfil/empresa');
    }


    function Empresa($cuit=null,$debug=0) {

        $this->load->module('dashboard');
        $this->dashboard->dashboard('perfil/json/empresa.json',$debug);

    }

    //=== Asociacion de cuits
    function asocia(){
        $customData['lang']= $this->lang->language;
        $callout=array('body'=>$customData['lang']['text_asocia'],'title'=>'');
        echo $this->ui->callout($callout);
        echo $this->Asocia_cuit();
    }

    //=== Profile

    function profile(){

        $cuit=$this->get_cuit();
        if(empty($cuit)){
            echo('No hay cuits asociados');
            return;
        }

        $certificado=$this->has1273($cuit);      
        $customData['certificado']=($certificado)?(''):('disabled');


        $opt="";
        $midata=$this->user->get_user((int) $this->idu);
        $lista=array();
        foreach($midata->cuits_relacionados as $empresa){
                
            $afip_data=$this->portal_model->get_afip_data($empresa['cuit']);
            if(empty($afip_data))continue; 
            if(in_array($empresa['cuit'],$lista))continue; 
            $lista[]=$empresa['cuit'];
            
            $selected=($empresa['cuit']==$cuit)?('selected'):('');
            $opt.="<option  value='{$empresa['cuit']}' $selected> {$afip_data->denominacion} | {$empresa['cuit']}   </option>\n";

        }
        $customData['empresas']="<select class='form-control' id='search_empresa'>$opt</select>";
        // $customData['avatar']=$this->user->get_avatar();

        $actividades=$this->app->get_ops(750);
        // $customData['empresas'] = array();
        if(isset($cuit)){
            $afip=$this->get_afip_data($cuit);
            if($afip){
                $afip['actividad_texto']=(isset($afip['actividad']))? @$actividades[$afip['actividad']]:'-----';
                $customData=array_merge($customData,$afip);
                
            }
        }

        $customData['base_url']=$this->base_url;
        $customData['cuit']=$cuit;
        echo $this->parser->parse('perfil/profile', $customData, true, true);
    }

        //=== Estadisticas

        function estadisticas(){

            $cuit=$this->get_cuit();
            if(empty($cuit)){
                echo('No hay cuits asociados');
                return;
            }

            $customData=array();
            $afip=$this->get_afip_data($cuit);
            $customData['periodos']='';
            if(!empty($afip['periodos'])){
                foreach($afip['periodos'] as $k=>$monto){
                $customData['periodos'].="<p>$k <span class='label label-info'>AR $monto</span></p>";
                }
            }

            
             
            echo $this->parser->parse('estadisticas', $customData, true, true);
        }


    # ====================================
    #   Incubadora
    # ====================================

    function Incubadora($cuit=null,$debug=0) {
         $this->load->module('dashboard');
         $this->dashboard->dashboard('perfil/json/incubadora.json',$debug);
    }


    function registro_incubadora() {

        $mygroup=1030;
        $user=$this->user->get_user($this->idu);
        $data['idu']=$this->idu;
        if(!in_array($mygroup,$user->group)){
            $user->group[]=$mygroup;
            $user->group[]=8;
            $data['group']=$user->group;
            $this->user->put_user($data);

        }
         redirect('perfil/incubadora');
    }

    # ====================================
    #   Emprendedor
    # ====================================

    function Emprendedor($cuit=null,$debug=0) {
        $this->load->module('dashboard');
        $this->dashboard->dashboard('perfil/json/emprendedor.json',$debug);
    }

    function registro_emprendedor() {

        $mygroup=1029;
        $user=$this->user->get_user($this->idu);
        $data['idu']=$this->idu;
        if(!in_array($mygroup,$user->group)){
            $user->group[]=$mygroup;
            $user->group[]=8;
            $data['group']=$user->group;
            $this->user->put_user($data);

        }
         redirect('perfil/emprendedor');
    }


    # ====================================
    #   Experto
    # ====================================

    function Experto($cuit=null,$debug=0) {
        $this->load->module('dashboard');
        $this->dashboard->dashboard('perfil/json/experto.json',$debug);
    }

    function registro_experto() {

        $mygroup=1014;
        $user=$this->user->get_user($this->idu);
        $data['idu']=$this->idu;
        if(!in_array($mygroup,$user->group)){
            $user->group[]=$mygroup;
            $user->group[]=8;
            $data['group']=$user->group;
            $this->user->put_user($data);
        }
         redirect('perfil/experto');
    }

    // function Experto() {
    //     $data['base_url'] = $this->base_url;
    //     $data['title'] = 'Expertos Pyme';
    //     $data['logobar'] = $this->ui->render_logobar();
        

    //     $data_select = NULL;        

    //     echo $this->parser->parse('perfil/form_expertos', $data, true, true);
    // }


    /*DATA 4 EXPERTOS PYME*/
    function expertos_get_afip_data(){


        $this->load->module('afip/api');        

        #$cuit=30710303777;
        $cuit=$this->input->post('cuit');       

        #$transaccion=489167004;
        $transaccion=(int)$this->input->post('transaccion');
      #  echo $cuit . "xxxxx" . $transaccion;

        $data = $this->api->get_data_by_cuit($cuit);    

        $rtn = array();               

            if(!isset($data->cuit)) {#NO cuit
                $rtn['msg'] = "error_cuit";                 
            } else if($transaccion!=$data->transaccion){
                $rtn['msg'] = 'error_transaccion';     
            }
        
                
        if($transaccion==$data->transaccion){
            
            $rtn['cuit'] = $data->cuit;
            $rtn['razon_social'] = $data->denominacion;
            $rtn['fecha_inicio_actividades'] = $data->fechaInscripcion;
            $rtn['razon_social'] = $data->denominacion;
            $rtn['empleados'] = $data->empleado;
            $rtn['descripcion_actividad_principal'] = $data->descripcionActividadPrincipal;
            $rtn['domicilio'] = $data->domicilioLegal . " " . $data->domicilioLegalLocalidad . " ".  $data->domicilioLegalDescripcionProvincia;
            // if($data->tienePeriodo2014=='S')
            //     $rtn['2014'] = $data->periodoFiscal2014['total'];
            // if($data->tienePeriodo2015=='S')
            //     $rtn['2015'] = $data->periodoFiscal2015['total'];
            // if($data->tienePeriodo2016=='S')
            //     $rtn['2016'] = $data->periodoFiscal2016['total'];
       
            $rtn['msg'] = 'ok';

            /*UPDATE users collection*/
            $query=array('idu'=>$this->idu);            
            $data_array_cuit = array('cuit'=>$cuit,'date'=>new MongoDate(time()));
            $data = array('cuits_relacionados'=>$data_array_cuit); 
            $update=$this->portal_model->cuit_representadas_update($query, $data_array_cuit);

            if(isset($update))
                 $rtn['msg'] = 'success_update';
        } 
        /*MSG*/
        echo json_encode($rtn);
     }


    # ====================================
    #   General
    # ====================================

    function Asocia_cuit() {
        $data['base_url'] = $this->base_url;
        $data['title'] = 'Asocia CUIT';
       
        echo $this->parser->parse('perfil/form_asocia_cuit', $data, true, true);
    }


    function get_afip_data($cuit){
       
        $data=$this->portal_model->get_afip_data($cuit);
        $isPyme=$this->portal_model->is_pyme($cuit);
        $resp=array();
        if(!empty($data->result)){
            $resp['sector']=(empty($data->result['sector_texto']))?(''):($data->result['sector_texto']);
            $resp['categoria']=(empty($data->result['categoria']))?(''):($data->result['categoria']);
            $resp['actividad']=(empty($data->result['actividad']))?(''):($data->result['actividad']);
            $resp['isPyme']=($isPyme===1)?('Pyme'):('-');
            //== Facturacion
            $periodos=array();
            foreach($data as $k=>$v){
             $pattern = '/^periodoFiscal([0-9]{4})/';
             preg_match($pattern, $k, $matches);
                 if(!empty($matches[1]) && is_numeric($matches[1]) )
                     if(!empty($v['actividades']))       
                         $periodos[$matches[1]]=$v['total'];
            } 
            $resp['periodos']=$periodos;

        }

       return $resp;
    }


    function AX_get_afip_data(){
        $cuit=$this->input->post('cuit');
        $data=$this->get_afip_data($cuit);

        echo json_encode($data);
    }

    private function get_cuit(){
        $cuit=(int)$this->uri->segment(3);

        $midata=$this->user->get_user((int) $this->idu);
        if(!isset($midata->cuits_relacionados))
            return false;

     

        if(empty($cuit)){
            // Va el primero de la lista

            $ret=array_pop($midata->cuits_relacionados);
            return (int)$ret['cuit'];
         }else{
            // chequeo si el elegido esta en la lista
            $found=false;
            foreach($midata->cuits_relacionados as $needle){
                if($needle['cuit']==$cuit)
                    return (int)$cuit;
            }
            return $found;

         }

            
    }


//=== Eficacia

function eficacia(){

$cuit=$this->get_cuit();
if(empty($cuit)){
    echo('No hay cuits asociados');
    return;
}



// Programas
 $cases = $this->bpm->get_cases_byFilter(
        array(
    'iduser' => $this->idu,
    'status' => 'open',
        ), array(), array('checkdate' => 'desc')
);

$has1273=$this->has1273();
$customData=array();
$userdata=$this->user->getbyid($this->idu);

$eficacia['registro']=25;
$eficacia['certificado']=($has1273)?(25):(0);
$eficacia['aplica']=(empty($cases))?(0):(25);
$eficacia['perfil']=(empty($userdata->phone))?(0):(25);

$customData['porcentaje']=0;
foreach($eficacia as $v)
    $customData['porcentaje']+=$v;

$customData['registro']=($eficacia['registro']==0)?('fa fa-times text-danger'):('fa fa-check text-success');
$customData['certificado']=($eficacia['certificado']==0)?('fa fa-times text-danger'):('fa fa-check text-success');
$customData['aplica']=($eficacia['aplica']==0)?('fa fa-times text-danger'):('fa fa-check text-success');
$customData['perfil']=($eficacia['perfil']==0)?('fa fa-times text-danger'):('fa fa-check text-success');

echo $this->parser->parse('eficacia', $customData, true, true);

}



//=== Inbox

function inbox(){
  echo Modules::run('inbox/widget');
}

function news(){
echo <<<_EOF_
<div class="row">
<div class="col-sm-3">
<img style="width:100%; height:auto; padding-right:10px;padding-botton:10px" class="pull-left" src="  http://www.produccion.gob.ar/wp-content/uploads/2016/06/Mayer-I.jpg" alt="">
</div>
<div class="col-sm-9">
<h4>La Secretaría de Emprendedores y PyMES promueve la innovación y el desarrollo productivo cordobés</h4>
<p>El secretario de Emprendedores y PyMEs del Ministerio de Producción de la Nación, Mariano Mayer, y el subsecretario de Emprendedores, Esteban Campero, se reunieron con autoridades, emprendedores y empresarios de Córdoba con quienes analizaron medidas concretas para fortalecer el <a class="label label-info" href="#">...</a></p>
</div>
</div>


_EOF_;
}

//== Programas aplicados
function programas(){

// Programas
 $cases = $this->bpm->get_cases_byFilter(
        array(
    'iduser' => $this->idu,
    'status' => 'open',
        ), array(), array('checkdate' => 'desc')
);


$customData['programas']=count($cases);

// Programas adquiridos
 $cases2 = $this->bpm->get_cases_byFilter(
        array(
    'iduser' => $this->idu,
    'status' => 'closed',
        ), array(), array('checkdate' => 'desc')
);
$customData['adquiridos']=count($cases2);

echo $this->parser->parse('programas', $customData, true, true);          
}



function has1273(){
// Certificado
$this->load->model('afip/consultas_model');
$cuit=$this->get_cuit();
$ret=$this->consultas_model->has_1273($cuit);
return !empty($ret);

}
 

}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */