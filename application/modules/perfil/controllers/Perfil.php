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
        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
        $this->user->authorize();
        //----LOAD LANGUAGE
       // $this->lang->load('library', $this->config->item('language'));
        $this->lang->load('dashboard/dashboard', $this->config->item('language'));
        $this->idu = $this->user->idu;
    }

    function Index() {
        
    }
    
    function Empresa($cuit=null,$debug=1) {
        $this->load->module('dashboard');
        $this->dashboard->dashboard('perfil/json/empresa.json',$debug);
    }
    function Incubadora() {
        
    }
    function Experto() {
        
    }

    //=== Profile


function profile(){
    // $config=array('body'=>'Im a callout','title'=>'Callout','class'=>'info');
    //  echo $this->ui->callout($config);
    $data=$this->user->get_user((int) $this->idu);
    $avatar=$this->user->get_avatar();
    $empresas = $this->portal_model->get_empresas();
    //var_dump($empresas);
    //exit();
    $select = '<select class="form-control"  data-live-search="true">';
    $id =0;
    foreach($empresas as $empresa){
        $select = $select. '<option id="'.$id.'" value="'.$empresa['1693'].'">Empresa:'.$empresa["1693"].' CUIT:'.$empresa["1695"].'</option>' ;
        $id++;
        
    }
    $select = $select.'</select>';
    //echo ($select);
    //exit();
echo <<<_EOF_
<div class="row">
<div class="col-sm-3">
<img src="$avatar"  class="avatar" style="width:120px" >
</div>
<div class="col-sm-9">
{$select}
<h3 ></h3>
<ul class='list-unstyled'>
<li><strong>Sector:</strong> Minería</li>
<li><strong>Clasificación:</strong> Pyme</li>
<li><strong>Sector:</strong> Tramo1</li>
</ul>
<a type="button" href="{$this->base_url}dashboard/profile" class="pull-right btn btn-general btn-xs "><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
 Editar</a>
</div>
</div>

<div class="" style="border-top:1px solid #ccc;height:10px;margin-top:9px"></div>

<div class="row">
<div class="col-sm-6">
<button type="button" class="btn btn-primary btn-md btn-block">Mi certificado PYME</button>
</div>
<div class="col-sm-6">
<button type="button" class="btn btn-primary btn-md btn-block disabled">Mis Archivos</button>
</div>
</div>

_EOF_;
    

}


//=== Eficacia

function eficacia(){
echo <<<_EOF_
<div class="row">
<div class="col-sm-3">
<input type="text" class="knob" value="25" >
</div>
<div class="col-sm-9">
<ul class='list-unstyled'>
<li><i class="fa fa-times text-danger" aria-hidden="true"></i> Balance no presentado</li>
<li><i class="fa fa-times text-danger" aria-hidden="true"></i> Formulario de certificación</li>
<li><i class="fa fa-times text-danger" aria-hidden="true"></i> Validación de programa</li>
</ul>
</div>
</div>
_EOF_;


}

//=== Estadisticas

function estadisticas(){
echo <<<_EOF_
    <p>Facturación: <span class='label label-info'>AR1.500.000</span> | Programas: 4 | Adquiridos: 1</p> 
_EOF_;
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