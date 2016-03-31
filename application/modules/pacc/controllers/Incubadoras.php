<?php
/**
* CONTROLADOR DEl DASHBOARD DEl COORDINAR DE INCUBADORAS
* 
* ESTE CONTROLADOR TIENE LOS DATOS TOTALES DE LOS PROYECTOS DE LAS INCUBADORAS
* JUNTO CON UN RANKING DE LAS PRIMERAS 10
* 
* 
* * LOS SCRIPTS DE MANEJO DE LA UI ESTÁN INVOCADOS EN EL DASHBOARD incubadoras.json
* 
* @author Luciano Menez <lucianomenez1212@gmail.com>
* @date Abril 8, 2015
*/
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Incubadoras extends MX_Controller {

    function __construct() {
        parent::__construct();
        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
        $this->load->module('pacc13/api13');
        $this->idu = $this->session->userdata('iduser');
    }
    /*
     * FUNCION PRINCIPAL
     */
    function Index() {
        $this->dashboard_incubadoras();
    }
    /**
     * Dashboard del COORDINADOR DE INCUBADORAS
     */
    function dashboard_incubadoras() {
        Modules::run('dashboard/dashboard', 'pacc/json/dashboard_incubadoras.json');
    }
    
    //MUESTREO DE PROYECTOS TOTALES DE TODAS LAS INCUBADORAS
    function estado_pp(){
        
        $this->load->module('dashboard');
        $renderData['title'] = "Estado de los Proyectos Presentados";
        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        
        $template="dashboard/widgets/box_info.php";
        
        $facturas_aprobadas = $this->api13->incubadoras_facturas_aprobadas('array');
        $proyectos_aprobados = $this->api13->incubadoras_proy_aprobados('array');
        $proyectos_presentados = $this->api13->incubadoras_proy_presentados('array');
        $rpresentadas = $this->api13->incubadoras_retibuciones_presentadas('array');
        $rpagadas = $this->api13->incubadoras_retibuciones_pagadas('array');
        $renderData['presentados'] =  $proyectos_presentados['result'][0]['cantidad'];
        $renderData['aprobados'] =  $proyectos_aprobados['result'][0]['cantidad'];
        $renderData['faprobadas'] =  $facturas_aprobadas['result'][0]['cantidad'];
        $renderData['rpresentadas'] =  $rpresentadas['result'][0]['cantidad'];
        $renderData['rpagadas'] =  $rpagadas['result'][0]['cantidad'];
        $renderData['content']= $this->parser->parse('incubadoras-pp', $renderData, true, true);
        return $this->dashboard->widget($template, $renderData);
    }
    
    //RANKING DE LAS 10 INCUBADORAS CON MAS PROYECTOS
    function ranking_incubadoras(){
        
        $this->load->module('dashboard');
        $provincias = $this->app->get_ops(39);
        $data = $this->api13->incubadoras_ranking(null, 'array');
        $nombres = $this->api13->incubadoras_listado('array');
        $renderData['data'] = array (10);
        $renderData['title'] = "Ranking de Incubadoras";
        $renderData['class-incubadoras'] = "tour-incubadoras incubadoras-dos";
        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        $template="dashboard/widgets/box_info.php";
        $renderData['data'] = ($data['result']);
        for ($i = 0; $i< count($renderData['data']); $i++){
            $renderData['data'][$i]['puesto'] = $i + 1;
        };
        $renderData['data'] = array_slice( $renderData['data'], 0, 10);
        foreach($renderData['data'] as $id =>  $value){
          $renderData['data'][$id]['nombre'] = isset($nombres[$value['_id']['incubadora']]) ? $nombres[$value['_id']['incubadora']]['nombre'] : $value['_id']['incubadora'];
        };

        $renderData['content']= $this->parser->parse('ranking-incubadoras', $renderData, true, true);
       
        return $this->dashboard->widget($template, $renderData);
    }

    //FAQ
    function faq_incubadoras() {
        $this->load->module('dashboard');
        $template = "dashboard/widgets/box_info.php";
        $data = array();
        $renderData['title'] = "F.A.Q.";
        $renderData['module_url'] = $this->module_url;
        $renderData['content']= $this->parser->parse('pacc/faq-incubadoras', $renderData, true, true);
        return $this->dashboard->widget($template, $renderData);
    }
 
}
