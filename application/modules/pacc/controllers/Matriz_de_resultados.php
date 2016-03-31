<?php
/**
* CONTROLADOR DEl DASHBOARD DE MATRIZ DE RESULTADOS
* 
* ESTE CONTROLADOR MANEJA TODOS LOS EVENTOS DE ALTA/BAJA/MODIFICACION
* QUE SE PUEDEN HACER SOBRE COMPONENTES E INDICADORES
* 
* *TODAVIA EN DESARROLLO
* 
* * LOS SCRIPTS DE MANEJO DE LA UI ESTÁN INVOCADOS EN EL DASHBOARD matriz_de_resultados.json
* 
* @author Luciano Menez <lucianomenez1212@gmail.com>
* @date Oct 23, 2015
*/
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Matriz_de_resultados extends MX_Controller {

    function __construct() {
        parent::__construct();
        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
        $this->load->module('pacc11/api11');
        $this->load->model('model_matriz_resultado');
        $this->idu = $this->session->userdata('iduser');
    }
    /*
     * Main function if no other invoked
     */
    function Index() {
        $this->dashboard_matriz_de_resultados();
    }
    /**
     * Dashboard MATRIZ DE RESULTADOS
     */
    function dashboard_matriz_de_resultados() {
        Modules::run('dashboard/dashboard', 'pacc/json/dashboard_matriz_de_resultados.json');
    }

    function matriz_producto(){
        $this->load->module('dashboard');
        $template="dashboard/widgets/box_info.php";
        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        $renderData['title'] = "Matriz de resultados";
        $renderData['content']= $this->parser->parse('matriz-producto', $renderData, true, true);
       
        return $this->dashboard->widget($template, $renderData);
    }
    
   function matriz_indicador(){
        $this->load->module('dashboard');
        $template="dashboard/widgets/box_info.php";
        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        $renderData['title'] = "Matriz de resultados";
        $renderData['content']= $this->parser->parse('matriz-indicador', $renderData, true, true);
       
        return $this->dashboard->widget($template, $renderData);
    }


    function dump(){
        
        $data = $this->model_matriz_resultado->lista_cargados();
        var_dump($data);
        exit;
        
        
    }

 
}
