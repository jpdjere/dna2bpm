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

        ini_set('display_errors', 1);
        error_reporting(E_ALL);
        ini_set('xdebug.var_display_max_depth', 120 );

    }

    function Index() {
        
    }
    
    # ====================================
    #   Empresa
    # ====================================

    function Empresa($cuit=null,$debug=1) {
        $this->load->module('dashboard');
        $this->dashboard->dashboard('perfil/json/empresa.json',$debug);
    }

    //=== Profile

    function profile(){
        $data=$this->user->get_user((int) $this->idu);
        $customData['avatar']=$this->user->get_avatar();
        $customData['empresas'] = $this->portal_model->get_empresas();    

        if(isset($customData['empresas'][0])){
            $afip=$this->get_afip_data($customData['empresas'][0][1695]);
            $customData=array_merge($customData,$afip);
        }

        $customData['base_url']=$this->base_url;

        echo $this->parser->parse('portal/profile', $customData, true, true);
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