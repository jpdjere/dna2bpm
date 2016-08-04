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
        $this->load->model('portal_model');


        $this->idu = $this->user->idu;
        
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


    /* CUITS RELACIONADO BY USER ID*/
   /* function cuits_by_idu($mode = 'json'){

        $this->user->authorize();               
        $this->idu = $this->user->idu;
        $this->load->model('afip/eventanilla_model');
        $result=$this->portal_model->cuits_by_idu_model($this->idu);
        $data = array();
        if($result){
            //var_dump($result);
            foreach ($result as $key => $value) { 
                foreach ($value as $cuit=>$data) {  
                    
                    //$procesos=$this->eventanilla_model->get_process(array('cuit'=>$cuit),array('denominacion'),array('denominacion'=>'ASC'));                                 
                    $procesos=$this->eventanilla_model->get_process(array('cuit'=>$cuit));                                 
                    $data=array(
                        'cuit'=>$cuit,
                        'razon_social'=>$procesos[0]->denominacion,
                        );
                }
            }        
        }
       
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

    }   */
    
    function cuits_by_idu($mode = 'json'){

        $this->user->authorize();               
        $this->idu = $this->user->idu;
        $this->load->model('afip/eventanilla_model');
        $result=$this->portal_model->cuits_by_idu_model($this->idu);
        $data = array();
        if($result){
            foreach ($result as $key => $value) { 
                foreach ($value as $cuit=>$date) {  
                    //$procesos=$this->eventanilla_model->get_process(array('cuit'=>$cuit));                                 
                    $data[] =array(
                        'cuit'=>$cuit,
                        //'razon_social'=>$procesos[0]->denominacion,
                        );
                }
            }        
        }
       
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

    } 

    
    
}

/* End of file Api.php */
/* Location: ./afip/api */