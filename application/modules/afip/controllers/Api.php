<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * api
 *
 * esta clase provee servicios para componentes externos ya sea en formato JSON
 * u otros necesarios dependiendo del cliente.
 *
 * @author Juan Ignacio Borda <juanignacioborda@gmail.com>
 * @date    Jan 28, 2015
 */
class Api extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('api');
        $this->load->helper('html');
        $this->load->model('bpm/bpm');
        $this->load->model('consultas_model');
        
        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
    }

    /**
     * List all api methods not in ignore_arr
     *
     */
    function Index() {
        $ignore_arr = array('Index', '__construct', '__get');
        $methods = array_diff(get_class_methods('api'), $ignore_arr);
        asort($methods);
        $links = array_map(function($item) {
            return '<a href="' . $this->module_url . strtolower(get_class()) . '/' . strtolower($item) . '">' . $item . '</a>';
        }, $methods);
        $attributes = array('class' => 'api_endpoint');
        echo ul($links, $attributes);
    }

     /**
      * Da un status de las solicitudes por provincia
      */
     function por_provincia($mode = 'json') {
        $this->load->model('afip/consultas_model');
        $data=array();
        $data=$this->consultas_model->por_provincia();
        
        switch ($mode) {
            case "object":
            return (object) $data;
            break;
            case "array":
            return($data);
            break;
            case "dump":
            var_export($data);
            break;
            case "json":
            output_json($data);
            break;
            default:
            return($data);
        }
    }
     function por_sector($mode = 'json',$provincia=null) {
        $this->load->model('afip/consultas_model');
        $data=array();
        $filter=null;
        //---agrego filtro por provincia.
        if($provincia)
            $filter=array('$match'=>array('domicilioLegalDescripcionProvincia'=>urldecode($provincia)));
        $data=$this->consultas_model->por_sector($filter);
        
        switch ($mode) {
            case "object":
            return (object) $data;
            break;
            case "array":
            return($data);
            break;
            case "dump":
            var_export($data);
            break;
            case "json":
            output_json($data);
            break;
            default:
            return($data);
        }
    }
     function por_categoria($mode = 'json',$provincia=null) {
        $this->load->model('afip/consultas_model');
        $data=array();
        $filter=null;
        //---agrego filtro por provincia.
        if($provincia)
            $filter=array('$match'=>array('domicilioLegalDescripcionProvincia'=>urldecode($provincia)));
        $data=$this->consultas_model->por_categoria($filter);
        
        switch ($mode) {
            case "object":
            return (object) $data;
            break;
            case "array":
            return($data);
            break;
            case "dump":
            var_export($data);
            break;
            case "json":
            output_json($data);
            break;
            default:
            return($data);
        }
    }
     function por_letra($mode = 'json') {
        $this->load->model('afip/consultas_model');
        $data=array();
        $data=$this->consultas_model->por_letra();
        
        switch ($mode) {
            case "object":
            return (object) $data;
            break;
            case "array":
            return($data);
            break;
            case "dump":
            var_export($data);
            break;
            case "json":
            output_json($data);
            break;
            default:
            return($data);
        }
    }
     function isPyme($mode = 'json') {
        $this->load->model('afip/consultas_model');
        $data=array();
        $data=$this->consultas_model->isPyme();
        
        switch ($mode) {
            case "object":
            return (object) $data;
            break;
            case "array":
            return($data);
            break;
            case "dump":
            var_export($data);
            break;
            case "json":
            output_json($data);
            break;
            default:
            return($data);
        }
    }
     function status($mode = 'json') {
        $this->load->model('afip/eventanilla_model');
        $data=array();
        $data=$this->eventanilla_model->get_queue_distinct();
        $data['F1272']= $this->eventanilla_model->get_raw_count();
        $data['F1273']= $this->eventanilla_model->get_raw_count(array('1273'=>array('$exists'=>true)));
        // if (!count($rs))
        //     $rs = $this->user->getbygroup(1);

        // $users=array_map(function($user){
        //     return $user['idu'];
        // },$rs);
        
        switch ($mode) {
            case "object":
            return (object) $data;
            break;
            case "array":
            return($data);
            break;
            case "dump":
            var_export($data);
            break;
            case "json":
            output_json($data);
            break;
            default:
            return($data);
        }
    }
     function F1272xSemana($mode = 'json') {
        $this->load->model('afip/eventanilla_model');
        $data=array();
        $data=$this->consultas_model->F1272xSemana();
       
        switch ($mode) {
            case "object":
            return (object) $data;
            break;
            case "array":
            return($data);
            break;
            case "dump":
            var_export($data);
            break;
            case "json":
            output_json($data);
            break;
            default:
            return($data);
        }
    }
     function F1272xMes($mode = 'json') {
        $this->load->model('afip/eventanilla_model');
        $data=array();
        $data=$this->consultas_model->F1272xMes();
        switch ($mode) {
            case "object":
            return (object) $data;
            break;
            case "array":
            return($data);
            break;
            case "dump":
            var_export($data);
            break;
            case "json":
            output_json($data);
            break;
            default:
            return($data);
        }
    }
     /**
      * devuelve stats de revision
      */
     function get_revision_stats($mode = 'json') {
        $this->load->model('afip/eventanilla_model');
        $data=array();
        $data=$this->eventanilla_model->get_revision_stats();
        
        switch ($mode) {
            case "object":
            return (object) $data;
            break;
            case "array":
            return($data);
            break;
            case "dump":
            var_export($data);
            break;
            case "json":
            output_json($data);
            break;
            case "table":
            $this->load->library('table');
            $d[0]=array('Cantidad','Tipo');
            $data=array_merge($d,$data);
            echo $this->table->generate($data);
            break;
            default:
            return($data);
        }
    }
    function get_revision_empresas($mode = 'json') {
        $this->load->model('afip/eventanilla_model');
        $data=array();
        $data=$this->eventanilla_model->get_revision_empresas();
        
        switch ($mode) {
            case "object":
            return (object) $data;
            break;
            case "array":
            return($data);
            break;
            case "dump":
            var_export($data);
            break;
            case "json":
            output_json($data);
            break;
            case "table":
            $this->load->library('table');
            $d[0]=array_keys($data[0]);
            $data=array_merge($d,$data);
            echo $this->table->generate($data);
            break;
            default:
            return($data);
        }
    }

    function status_vinculadas($mode = 'json') {
        $this->load->model('afip/consultas_model');
        
        $rtn = $this->consultas_model->vinculadas($count=true); 
        $data=array('count'=>$rtn);
        switch ($mode) {
            case "object":
            return (object) $data;
            break;
            case "array":
            return($data);
            break;
            case "dump":
            var_export($data);
            break;
            case "json":
            output_json($data);
            break;
            case "table":
            $this->load->library('table');
            $d[0]=array('Cantidad','Tipo');
            $data=array_merge($d,$data);
            echo $this->table->generate($data);
            break;
            default:
            return($data);
        }
        
    }
    
    
   function get_fecha_entrada($cuit){
        $cuit = (int)$cuit;

       $rtn = $this->consultas_model->buscar_cuits_registrados($cuit);

       var_dump($rtn);
       exit;


    }
    
    function get_data_by_cuit_format($cuit, $mode = 'json'){

        //$cuit = '30712072772';
        $this->user->authorize();                       

        
        $data=$this->consultas_model->cuits_certificados($cuit);
        //var_dump($data);
        
        return $cuit;
        switch ($mode) {
            case "object":
                return (object) $data;
                break;
            case "array":
                return($data);
                break;
            case "json":
                output_json($data);
                break;
            default:
                return($data);
        }
        //var_dump($data);
        return $data;

     } 
    
    function get_fecha_proceso($cuit){
       $data = "hola";
       echo $data;
       
       return $data;
    }
    
    function get_fecha_salida($cuit){
       $data = "hola";
       echo $data;
       
       return $data;
    }

    function has_1273($cuit){
       $ret= $this->consultas_model->has_1273((float)$cuit);
       var_dump($ret);
    }
    
}

/* End of file Api.php */
/* Location: ./afip/api */