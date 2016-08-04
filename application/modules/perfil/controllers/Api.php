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
    function cuits_by_idu($mode = 'json'){

        $this->user->authorize();               
        $this->idu = $this->user->idu;
        $this->load->model('afip/eventanilla_model');
        // $results=$this->portal_model->cuits_by_idu_model($this->idu);
        $results=$this->portal_model->cuits_by_idu_model(-639429126);
        $data = array();
        if($results){
            foreach ($results as $result) {  
                // var_dump($result);
                $cuits[]=new MongoInt64($result['cuit']);
            }
            $query=array('cuit'=>array('$in'=>$cuits));
            $procesos=$this->eventanilla_model->get_process($query,array('denominacion','cuit'),array('denominacion'=>'ASC'));                                 
            foreach ($procesos as $result) { 
                    
                        $data[] =array(
                            'cuit'=>$result->cuit ,
                            'razon_social'=>$result->denominacion,
                            );
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