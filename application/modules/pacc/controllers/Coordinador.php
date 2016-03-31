<?php

/**
 /**
* CONTROLADOR DEL DASHBOAR DE COORDINADORES
* 
* MUESTRA DESEMPEÑOS Y PROYECTOS PERTINENTES AL COORDINAR
* 
* LOS SCRIPTS DE MANEJO DE LA UI ESTÁN INVOCADOS EN EL DASHBOARD
* 
* @author Luciano Menez <lucianomenez1212@gmail.com>
* @date Mar 7, 2015
*/

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Coordinador extends MX_Controller {

    function __construct() {
        parent::__construct();

        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
    }

    /*
     * FUNCION PRINCIPAL
     */

    function Index() {
        $this->dashboard_empresas();
    }
    /**
     * Dashboard del COORDINADOR DE EMPRESAS Y GRUPOS PRODUCTIVOS
     */
    function dashboard_empresas($debug=false) {
        Modules::run('dashboard/dashboard', 'pacc/json/dashboard_coordinador_empresas.json',$debug);
    }

   
    //TABLA DE PRIORIDADES 
    function tabla_prioridades() {
        $this->load->module('dashboard');
        $template = "dashboard/widgets/box_info.php";
        $data = array();
        $data['title'] = "Prioridades";
        $data['class-coordinador'] = "tour-coordinadores paso-uno";
        $data['content'] = $this->data_table($data);

        return $this->parser->parse('pacc/full-table', $data, true, true);
    }

    function half_table() {

        $this->load->module('dashboard');
        $this->load->module('pacc/api');
        $template = "dashboard/widgets/box_info.php";
        $data = array();
        $data['module_url'] = $this->module_url;
        $data['title'] = "Desempeño Analistas";
        $data['users'] = $this->api->ranking_analista($filter, $mode);
        $data['class'] = (isset($json_url)) ? "team_ranking_json" : "team_ranking";
        return $this->parser->parse('pacc/half-table', $data, true, true);
    }
    
    //RANKING DE ANALISTAS
    function half_table_rank(){
        $this->load->module('pacc');
        $this->load->module('pacc/api');
        $template="dashboard/widgets/box_info.php";
        $data=array();
        $data['title']= "Desempeño de analistas";
        $data['class']= "tour-coordinadores paso-dos";
        $data['users'] = $this->api->ranking_analista($filter, $mode);
        $data['content']= $this->data_table($data);
        
  
        
        return $this->parser->parse('pacc/half-table-rank',$data,true,true);
    } 

    function bars() {
        $this->load->module('dashboard');
        $template = "dashboard/widgets/box_info.php";
        $data = array();
        $data['json_url'] = $this->base_url . 'demo/data_bars';
        $data['class'] = "data_bars";
        $data['title'] = "Fuerza Laboral Disponible";
        return $this->parser->parse('pacc/bars', $data, true, true);
    }

    //mockup de CHARTS
    function charts() {
        $this->load->module('dashboard');
        $template = "dashboard/widgets/box_info.php";
        $data = array();
        $data['title'] = "SDE - Últimos 28 días";
        $data['json_url'] = $this->base_url . 'demo/data_lines_json';
        $data['class'] = "data_lines";
        $data['class-tour'] = "tour-coordinadores paso-cinco";
        return $this->parser->parse('pacc/charts', $data, true, true);
    }

    //KNOBS EMPRESAS
    function knobs_empresas() {
        $this->load->module('dashboard');
        $template = "dashboard/widgets/box_info.php";
        $data = array();
        $data['title'] = "Metas para Empresas";
        $data['base_url'] = $this->base_url;
        return $this->parser->parse('pacc/knobs-empresas', $data, true, true);
    }

    //KNOBS EMPRENDEDORES
    function knobs_emprendedores() {
        $this->load->module('dashboard');
        $template = "dashboard/widgets/box_info.php";
        $data = array();
        $data['title'] = "Metas para Emprendedores";
        $data['base_url'] = $this->base_url;
        return $this->parser->parse('pacc/knobs-emprendedores', $data, true, true);
    }

    //KNOBS EMPRENDEDORES PITCHS
    function knobs_emprendedores_pitchs() {
        $this->load->module('dashboard');
        $template = "dashboard/widgets/box_info.php";
        $data = array();
        $data['title'] = "PITCHs";
        $data['base_url'] = $this->base_url;
        return $this->parser->parse('pacc/knobs-pitchs', $data, true, true);
    }

    function data_table($data) {
        $this->load->model('pacc/model_pacc');
        $this->load->model('bpm/bpm');
        $this->load->model('app');
        /*
          pacc1PDE (PDE)
          inicio y fin:
          oryx_4C79F11E-C212-4140-8B99-1162B761B636 (task - Evalua FPP)
         *
         */
        $fields=array('checkdate','case');
        $filter=array(
            'status'=>'open',
            'resourceId'=>'oryx_4C79F11E-C212-4140-8B99-1162B761B636',
            'case'=>'pacc1PDE'
        );
        //$tokens=$this->bpm->get_tokens_byFilter($filter, $fields);
        //----array con los pares de idwf y Lane que hay que contar
        $pconfig=array(
        array(
            'idwf'=>'pacc1PDE',
            'lane'=>'oryx_295810F2-8C34-4D03-80F8-7B5C371381B8'
            ),
        array(
            'idwf'=>'pacc1PDEF',
            'lane'=>'oryx_4C79F11E-C212-4140-8B99-1162B761B636'
            ),
        array(
            'idwf'=>'pacc1PDEF',
            'lane'=>'oryx_FB1BEEB3-A7B8-44D4-83AB-565A9EAF34F7'
            ),
        array(
            'idwf'=>'pacc1PDEF',
            'lane'=>'oryx_B1093DB1-19FD-490F-BFCD-9D11EAACBA83'
            ),
        array(
            'idwf'=>'pacc1SDE',
            'lane'=>'oryx_CD23C511-FAE2-4549-8D26-2182224D770F'
            ),
        array(
            'idwf'=>'pacc3PP',
            'lane'=>'oryx_295810F2-8C34-4D03-80F8-7B5C371381B8'
            ),
        array(
            'idwf'=>'pacc3PPF',
            'lane'=>'oryx_AD0108D7-CA5D-4989-845E-CBC0E6158CF3'
            ),
        array(
            'idwf'=>'pacc3PPF',
            'lane'=>'oryx_0DA9E38A-92D8-4B19-A948-46CEF3168613'
            ),
        array(
            'idwf'=>'pacc3REND',
            'lane'=>'oryx_CD23C511-FAE2-4549-8D26-2182224D770F'
            ),
        array(
            'idwf'=>'pacc3REND',
            'lane'=>'oryx_0DA9E38A-92D8-4B19-A948-46CEF3168613'
            ),
        );
        $tokens=$this->model_pacc->prioridades($pconfig);
        //----estado pacc 6225 -> idop =648
        $op=$this->app->get_ops(648);
        /*
      '_id' => 
        object(MongoId)[38]
          public '$id' => string '5537b49b2a3f7d66356c1891' (length=24)
      'interval' => 
        array (size=8)
          'y' => int 0
          'm' => int 0
          'd' => int 4
          'h' => int 21
          'i' => int 36
          's' => int 30
          'invert' => int 0
          'days' => int 4
      'iduser' => int -952817675
      'status' => string 'open' (length=4)
      'idwf' => string 'pacc1PDE' (length=8)
      'case' => string 'CIPK' (length=4)
      'resourceId' => string 'oryx_B3E23704-51DA-421D-A723-9AF7AC562FAB' (length=41)
      'type' => string 'Lane' (length=4)
        */
        foreach($tokens as $token){
            $case=$this->bpm->get_case($token['case'],$token['idwf']);
            $case_data = $this->bpm->load_case_data($case);
            $user=$this->user->get_user_safe($case['iduser']);
            $class='success';
            if($token['interval']['days']>5)
                $class='warning';
            if($token['interval']['days']>10)
                $class='danger';
            $status='----';
            if(isset($case_data['Proyectos_pacc'])){
            $status=(isset($case_data['Proyectos_pacc'][6225][0]))?$op[$case_data['Proyectos_pacc'][6225][0]]:'???';
            }
            //var_dump($case_data);
            
            $empresa = array();
            //var_dump($case_data['Proyectos_pacc'][6223][0]);
            if(isset($case_data['Proyectos_pacc'][6223][0])){
            $empresa=$this->model_pacc->datos_empresa($case_data['Proyectos_pacc'][6223][0]);
            //var_dump($empresa);
            //exit();
            }else{
                $empresa['cuit'] = '???';
                $empresa['nombre'] = '???';
            }
            
            $comentarios = (isset($case_data['Proyectos_pacc'][5673]))?($case_data['Proyectos_pacc'][5673]):'???';
            
            $data[]=array(
            'id' => $case['id'],
            'name' => $user->name.' '.$user->lastname,
            'class' => $class,
            'delay' => $token['interval']['days'],
            'status' => $status,
            'cuit' => $empresa['cuit'],
            'empresa' => $empresa['nombre'],    
            'comments' => $comentarios//'Comentarios sobre la presentación del proyecto'
            );
        }
        //var_dump($data);
        return $data;
    }

    function data_lines_json() {

        $arr_presentados = [[0, 0], [2, 2], [4, 4], [6, 6], [8, 8], [10, 10], [12, 12], [14, 14], [16, 16], [18, 18], [20, 20], [22, 22], [24, 24], [26, 26], [28, 28]];
        $arr_evaluados = [[0, 0], [2, 0], [4, 1], [6, 1], [8, 1], [10, 1], [12, 1], [14, 1], [16, 1], [18, 1], [20, 1], [22, 1], [24, 1], [26, 1], [28, 1]];

        $obj = array('presentados' => $arr_presentados,
            'evaluados' => $arr_evaluados);

        header('Content-type: application/json;charset=UTF-8');
        echo json_encode($obj);
    }

   
    //FUNCION QUE GENERA EL MAPA
    function map_demo() {
        //---prepare globals 4 js
        $this->load->module('dashboard');
        $renderData['title'] = "Mapa de Proyectos Presentados";
        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        $renderData['json_url'] = $this->base_url . 'demo/assets/json/crefis2010.json';
        $renderData['map_id'] = 'mapdiv-' . microtime();
        $renderData['map_class'] = 'map_heat';
        $renderData['class-tour'] = 'tour-coordinadores paso-siete';
        $template = "dashboard/widgets/box_info.php";
        $renderData['content'] = $this->parser->parse('ammap_div', $renderData, true, true);
        return $this->dashboard->widget($template, $renderData);
    }

    // controlador cantidad de proyectos

    function area_charts() {
        $this->load->module('dashboard');
        $template = "dashboard/widgets/box_info.php";
        $data = array();
        $data['title'] = " SDE - Evaluada / Ingresada - Últimos 28 días";
        $data['class-incubar'] = "tour-incubar incubar-seis tour-coordinadores paso-cinco";
        $data['id'] = "sde_charts";
        $data['json_url'] = $this->base_url . 'pacc/coordinador/sde_ultimos_28_dias';
        
        return $this->parser->parse('area-charts', $data, true, true);
    }
    
    function sde_ultimos_28_dias(){
        $this->load->helper('api');
        $this->load->module('pacc11/api11');
        $arr=$this->api11->retribuciones_pagadas_empresas('array');
        $rtnArr=array();
        foreach($arr as $key=>$value){
            $rtnArr[]=array(
                'y'=>$key,
                'item1'=>1,
                'item2'=>0
                );
        }
        output_json($rtnArr);
    }


    function full_bars() {
        $this->load->module('dashboard');
        $template = "dashboard/widgets/box_info.php";
        $data = array();
        $data['title'] = "Proyectos Reclamados";
        $data['json_url'] = $this->base_url . 'demo/data_bars';
        $data['class'] = 'data_bars';

        return $this->parser->parse('pacc/bars', $data, true, true);
    }
    
  
}
