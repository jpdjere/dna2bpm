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
        $this->load->model('app');
        $this->load->library('dashboard/ui');
        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
        $this->user->authorize();
        //----LOAD LANGUAGE
       // $this->lang->load('library', $this->config->item('language'));
        $this->lang->load('dashboard/dashboard', $this->config->item('language'));
        $this->idu = $this->user->idu;

        ini_set('display_errors', 0);
        #error_reporting(E_ALL);
        ini_set('xdebug.var_display_max_depth', 120 );

    }

    function Index() {
        
    }
    
    # ====================================
    #   Empresa
    # ====================================

    function Empresa($cuit=null,$debug=0) {

        $this->load->module('dashboard');
        $this->dashboard->dashboard('perfil/json/empresa.json',$debug);

    }

    //=== Profile

    function profile(){

        $cuit=$this->get_cuit();
        $data=$this->user->get_user((int) $this->idu);
        $customData['avatar']=$this->user->get_avatar();
        $customData['empresas'] = $this->portal_model->get_empresas(); 
        foreach($customData['empresas'] as &$emp){
            if(str_replace('-','',$emp['1695'])==$cuit) $emp['selected']='selected="selected"';
        }
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
            $customData=array();
            $afip=$this->get_afip_data($cuit);
            $customData['periodos']='';
            if(!empty($afip['periodos'])){
                foreach($afip['periodos'] as $k=>$monto){
                $customData['periodos'].="<p>$k <span class='label label-info'>AR $monto</span></p>";
                }
            }

            // Programas
             $cases = $this->bpm->get_cases_byFilter(
                    array(
                'iduser' => $this->idu,
                'status' => 'open',
                    ), array(), array('checkdate' => 'desc')
            );
           //  var_dump($cases);
            $customData['programas']=count($cases);

            // Programas adquiridos
             $cases2 = $this->bpm->get_cases_byFilter(
                    array(
                'iduser' => $this->idu,
                'status' => 'closed',
                    ), array(), array('checkdate' => 'desc')
            );
            $customData['adquiridos']=count($cases2);
             
            echo $this->parser->parse('estadisticas', $customData, true, true);
        }


    # ====================================
    #   Incubadora
    # ====================================

    function Incubadora() {
        
    }

    # ====================================
    #   Experto
    # ====================================

    function Experto() {
        $data['base_url'] = $this->base_url;
        $data['title'] = 'Expertos Pyme';
        $data['logobar'] = $this->ui->render_logobar();
        

        $data_select = NULL;        

        echo $this->parser->parse('form_expertos', $data, true, true);
    }

    /*DATA 4 EXPERTOS PYME*/
    function expertos_get_afip_data(){

        $this->load->module('afip/api');        

        #$cuit=30710303777;
        $cuit=$this->input->post('cuit');       

        #$transaccion=489167005;
        $transaccion=$this->input->post('transaccion');

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
            $rtn['empleados'] = $data->cantEmpleados;
            $rtn['descripcion_actividad_principal'] = $data->descripcionActividadPrincipal;
            $rtn['domicilio'] = $data->domicilioLegal . " " . $data->domicilioLegalLocalidad . " ".  $data->domicilioLegalDescripcionProvincia;
            if($data->tienePeriodo2014=='S')
                $rtn['2014'] = $data->periodoFiscal2014['total'];
            if($data->tienePeriodo2015=='S')
                $rtn['2015'] = $data->periodoFiscal2015['total'];
            if($data->tienePeriodo2016=='S')
                $rtn['2016'] = $data->periodoFiscal2016['total'];
       
            $rtn['msg'] = 'ok';

            /*UPDATE users collection*/
            $query=array('idu'=>$this->idu);            
            $data_array_cuit = array($cuit=>new MongoDate(time()));
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
        if(empty($cuit)){
            $customData['empresas'] = $this->portal_model->get_empresas(); 
            if(!empty($customData['empresas'][0][1695]))
                $cuit=(int)str_replace('-', '', $customData['empresas'][0][1695]);
        }
        return $cuit;
    }





//=== Eficacia

function eficacia(){

// Programas
 $cases = $this->bpm->get_cases_byFilter(
        array(
    'iduser' => $this->idu,
    'status' => 'open',
        ), array(), array('checkdate' => 'desc')
);
$customData=array();
$userdata=$this->user->getbyid($this->idu);

$eficacia['registro']=25;
$eficacia['certificado']=0;
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


 

}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */