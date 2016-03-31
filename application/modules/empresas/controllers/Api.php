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
        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
        $this->idu = $this->session->userdata('iduser'); //Id user

        /* LOAD MODEL */
        $this->load->model('empresas/model_empresas');
    }

    function Index() {
        $ignore_arr = array('Index', '__construct', '__get');
        $methods = array_diff(get_class_methods(get_class($this)), $ignore_arr);
        asort($methods);
        $links = array_map(function($item) {
            return '<a href="' . $this->module_url . strtolower(get_class()) . '/' . strtolower($item) . '">' . $item . '</a>';
        }, $methods);
        $attributes = array('class' => 'api_endpoint');
        echo ul($links, $attributes);
    }

    /**
     * Altas de empresas por anio
     *
     * devuelve las altas por mes para determinado año
     *
     * @year anio para filtrar 
     *
     * @author Juan ignacio Borda <juanignacioborda@gmail.com>
     *
     * @param year 
     *
     * @date Mar,21 2015
     */
    function altas_anio($year=null,$mode=null) {
        if(!$year) $year=date('Y');
        
        $data=array( 
        'key' => 'date',
        'items' =>   array ('qtty'),
        'labels' => 'date',
        'xLabels' => 'month',
        'postUnits' => '',
        );
        $data['data']=$this->model_empresas->altas_anio($year);
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
    function altas_todas($mode=null) {
        
        $data=array( 
        'key' => 'date',
        'items' =>   array ('qtty'),
        'labels' => ['Cantidad'],
        'xLabels' => 'year',
        'postUnits' => '',
        );
        $data['data']=$this->model_empresas->altas_todas();
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

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */