<?php

/**
* CONTROLADOR DEL DASHBOARD PARA EL ROL EVALUADOR
* 
* MUESTRA LOS PROYECTOS ASIGNADOS A CADA EVALUADOR Y UN RANKING DE DESEMPEÑO
* DE LOS EVALUADORES EN GENERAL
* 
* LOS SCRIPTS DE MANEJO DE LA UI ESTÁN INVOCADOS EN EL DASHBOARD
* 
* @author Luciano Menez <lucianomenez1212@gmail.com>
* @date Jun 27, 2015
*/
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Evaluador extends MX_Controller {

    function __construct() {
        parent::__construct();
        
        //---base variables
        $this->load->model('model_evaluadores_proyectos');
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
    }
    /*
     * FUNCION PRINCIPAL
     */
    function Index(){
        $this->dashboard();
    }

    /**
     * Dashboard del EVALUADOR DE EMPRESAS Y GRUPOS PRODUCTIVOS
     */
    function dashboard($debug=false){
        Modules::run('dashboard/dashboard', 'pacc/json/dashboard_evaluador.json',$debug);
        
    }
    
    //LISTA LOS PROYECTOS ASIGNADOS AL EVALUADOR LOGUEADO
    function lista_de_proyectos() {
        $this->load->module('dashboard');
        $template = "dashboard/widgets/box_info.php";
        $data = array();
        
        $data['empresas']= $this->model_evaluadores_proyectos->proyectos_empresa();
        $data['emprendedores']= $this->model_evaluadores_proyectos->proyectos_emprendedor();
        
        $data['title'] = "Listado de Proyectos";
        $data['content']= $this->parser->parse('pacc/half-table-tabulado', $data, true, true);
        
        return $this->dashboard->widget($template, $data);
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
            if($case_data['Proyectos_pacc']){
            $status=(isset($case_data['Proyectos_pacc'][6225][0]))?$op[$case_data['Proyectos_pacc'][6225][0]]:'???';
            }
            $data[]=array(
            'id' => $case['id'],
            'name' => $user->name.' '.$user->lastname,
            'class' => $class,
            'delay' => $token['interval']['days'],
            'status' => $status,
            'comments' => 'Comentarios sobre la presentación del proyecto'
            );
        }
        return $data;
    }

    function full_table_rank(){
        $this->load->module('pacc');
        $template="dashboard/widgets/box_info.php";
        $data=array();
        $data['title']= "Prioridades";
        $data['content']= $this->data_table($data);
        
        return $this->parser->parse('pacc/full-table-rank',$data,true,true);
    }
    
    //RANKING DE EVALUADORES
    function half_table_rank(){
        $this->load->module('pacc');
        $this->load->module('pacc/api');
        $template="dashboard/widgets/box_info.php";
        $data=array();
        $data['title']= "Desempeño de analistas";
        $data['users'] = $this->api->ranking_analista($filter, $mode);
        $data['content']= $this->data_table($data);
        
  
        
        return $this->parser->parse('pacc/half-table-rank',$data,true,true);
    } 
    

        
        
        
        
        
        
        
        
    }    
        
        

