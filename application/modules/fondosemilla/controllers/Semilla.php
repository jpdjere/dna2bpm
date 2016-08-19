<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * expertos
 *
 * Description of the class expertos
 *
 * @author Juan Ignacio Borda <juanignacioborda@gmail.com>
 *         @date Jul 18, 2014
 */
class semilla extends MX_Controller {
    //--define el token que guarda la data consolidada para buscadores etc
    public $consolida_resrourceId='oryx_6772A7D9-3D05-4064-8E9F-B23B4F84F164';

    function __construct() {
        parent::__construct();
        $this->load->model('bpm/Kpi_model');        
        $this->load->model('menu/menu_model');
        $this->load->model('app');
        $this->load->model('bpm/bpm');
        $this->load->model('Fondosemilla_model');
        $this->load->module('bpm/kpi');
        $this->user->isloggedin();
        // ---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
        $this->load->config('semilla/config');
        // ----LOAD LANGUAGE
        $this->lang->load('library', $this->config->item('language'));
        $this->idu = (int) $this->session->userdata('iduser');
        $this->load->library('pagination');
        $this->load->library('dashboard/ui');
        /* GROUP */
        $user = $this->user->get_user($this->idu);

        $this->id_group = ($user->{'group'});
        MongoCursor::$timeout = -1;
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
        ini_set('xdebug.var_display_max_depth', 120 );
        
        
    }

    function Index() {
        $grupo_user = 'FondoSemilla /Emprendedor';
        $this->Add_group($grupo_user);
        $this->Emprendedores();
    }

    function Emprendedores($debug=false) {
        $this->user->authorize();
        $grupo_user = 'FondoSemilla /Emprendedor';
        $extraData['css'] = array($this->base_url . 'fondosemilla/assets/css/fondosemilla.css' => 'Estilo Lib');        
        $this->Add_group($grupo_user);
        Modules::run('dashboard/dashboard', 'fondosemilla/json/emprendedores_lite.json',$debug, $extraData);
       // Modules::run('dashboard/dashboard', 'fondosemilla/json/semilla_proyectos.json',$debug);
    }

    function Incubadoras($debug=false) {
        $this->user->authorize();
        $grupo_user = 'FondoSemilla /Incubadora';
        $extraData['css'] = array($this->base_url . 'fondosemilla/assets/css/fondosemilla.css' => 'Estilo Lib'
        );        
        $this->Add_group($grupo_user);
        //Modules::run('dashboard/dashboard', 'expertos/json/expertos_direccion.json',$debug);
        Modules::run('dashboard/dashboard', 'fondosemilla/json/incubadoras_lite.json',$debug, $extraData);
    }
    
    function Coordinador($debug=false) {
        $this->user->authorize();
        $grupo_user = 'FondoSemilla /Jurado-Coordinador';
        $extraData['css'] = array($this->base_url . 'fondosemilla/assets/css/fondosemilla.css' => 'Estilo Lib'
        );
        $extraData['js'] = array($this->base_url . 'fondosemilla/assets/jscript/coordinador.js' => 'JS COORDINADOR'
        );        
        $this->Add_group($grupo_user);
        //Modules::run('dashboard/dashboard', 'expertos/json/expertos_direccion.json',$debug);
        Modules::run('dashboard/dashboard', 'fondosemilla/json/coordinador_lite.json',$debug, $extraData);
    }  
    
    function Profesionales($debug=false) {
        $this->user->authorize();
        $this->Add_group();
        //Modules::run('dashboard/dashboard', 'expertos/json/expertos_direccion.json',$debug);
        Modules::run('dashboard/dashboard', 'expertos/json/expertos_prof.json',$debug);
    }

    function Add_group($grupo_user) {
        $user =$this->user->get_user($this->idu);
        if (!$this->user->isAdmin($user)) {
            $user=$user;
            $group_add = $this->group->get_byname($grupo_user);
            array_push($user->group, (int) $group_add ['idgroup']);
            $user->group = array_unique($user->group);
            $this->user->save($user);
        }
    }
    function widget_2doMe2($chunk = 1, $pagesize = 2000) {
        
        //$data['lang']=$this->lang->language;
        $this->load->model('bpm/bpm');
        $query = array(
            'assign' => $this->idu,
            'status' => 'user'
        );

        //var_dump(json_encode($query));exit;
        $tasks = $this->bpm->get_tasks_byFilter($query, array(), array('checkdate' => 'desc'));
        //$data=$this->prepare_tasks($tasks, $chunk, $pagesize);
        $data = Modules::run('bpm/bpmui/prepare_tasks', $tasks, $chunk, $pagesize);
        
        if (isset($data['mytasks'])) { 
            foreach ($data['mytasks'] as $k => $mytask) {
                $mycase = $this->bpm->get_case($mytask['case']);
                $data['mytasks'][$k]['extra_data']['ip'] = false;
                if (isset($mycase['data']['Empresas']['query']['id'])) {
                    $empresaID = $mycase['data']['Empresas']['query']['id'];
                    $empresa = $this->bpm->get_data('container.empresas', array('id' => $empresaID));
                    $data['mytasks'][$k]['extra_data']['empresa'] = $empresa[0]['1693'];
                }
                if (isset($mycase['data']['Asistencias']['query']['id'])) {
                    $proyectoID = $mycase['data']['Asistencias']['query']['id'];
                    $proyecto = $this->bpm->get_data('container.asistencias', array('id' => $proyectoID));
                    $data['mytasks'][$k]['extra_data']['ip'] = $proyecto[0]['4837'];
                    

                    $url = (isset($mycase['data'] ['Asistencias']['query']['id'])) ? '../dna2/frontcustom/284/list_docs_crefis_eval.php?id=' . $mycase['data'] ['carga_pro_inst']['query'] ['id'] : '#';
                    $data['mytasks'][$k]['link_open'] = $this->bpm->gateway($url);
                    
                }
            }
        } else {
            $data['mytasks'] = array();
        }

        $data['title'] = $this->lang->line('Tasks') . ' ' . $this->lang->line('Pending');

        $data['more_info_link'] = $this->base_url . 'bpm/';
        $data['widget_url'] = base_url() . $this->router->fetch_module() . '/' . $this->router->class . '/' . __FUNCTION__;
        
        //==== Pagination

        $pagination=array_chunk($data['mytasks'],5);
        $pages=array();
        
        foreach($pagination as $chunk){
            $data['mytasks2']=$chunk;
            $pages[]=$this->parser->parse('expertos/widgets/2doMe2_task', $data, true, true);
            
        }
        

        $data['mytasks_paginated']=$this->ui->paginate($pages);

        echo $this->parser->parse('expertos/widgets/2doMe2', $data, true, true);
    }
    
    
    
    function widget_2doMe2_b($chunk = 1, $pagesize = 2000) {
        
        //$data['lang']=$this->lang->language;
        $this->load->model('bpm/bpm');
        $query = array(
            'assign' => $this->idu,
            'status' => 'user',
            'idwf' => 'fondo_semilla2016'
        );

        //var_dump(json_encode($query));exit;
        $tasks = $this->bpm->get_tasks_byFilter($query, array(), array('checkdate' => 'desc'));
        //$data=$this->prepare_tasks($tasks, $chunk, $pagesize);
        $data = Modules::run('bpm/bpmui/prepare_tasks', $tasks, $chunk, $pagesize);
        //var_dump($data);
        //exit();
        if (isset($data['mytasks'])) { 
            foreach ($data['mytasks'] as $k => $mytask) {
                $mycase = $this->bpm->get_case($mytask['case']);
                $data['mytasks'][$k]['extra_data']['ip'] = false;
                if (isset($mycase['data']['Empresas']['query']['id'])) {
                    $empresaID = $mycase['data']['Empresas']['query']['id'];
                    $empresa = $this->bpm->get_data('container.empresas', array('id' => $empresaID));
                    $data['mytasks'][$k]['extra_data']['empresa'] = $empresa[0]['1693'];
                }/*
                if (isset($mycase['data']['Asistencias']['query']['id'])) {
                    $proyectoID = $mycase['data']['Asistencias']['query']['id'];
                    $proyecto = $this->bpm->get_data('container.asistencias', array('id' => $proyectoID));
                    $data['mytasks'][$k]['extra_data']['ip'] = $proyecto[0]['4837'];
                    

                    $url = (isset($mycase['data'] ['Asistencias']['query']['id'])) ? '../dna2/frontcustom/284/list_docs_crefis_eval.php?id=' . $mycase['data'] ['Proyectos_crefis']['query'] ['id'] : '#';
                    $data['mytasks'][$k]['link_open'] = $this->bpm->gateway($url);

                }*/
            }
        } else {
            $data['mytasks'] = array();
        }

        $data['title'] = $this->lang->line('Tasks') . ' ' . $this->lang->line('Pending');

        $data['more_info_link'] = $this->base_url . 'bpm/';
        $data['widget_url'] = base_url() . $this->router->fetch_module() . '/' . $this->router->class . '/' . __FUNCTION__;
        
        //==== Pagination

        $pagination=array_chunk($data['mytasks'],5);
        $pages=array();
        
        foreach($pagination as $chunk){
            $data['mytasks2']=$chunk;
            $pages[]=$this->parser->parse('expertos/widgets/2doMe2_task', $data, true, true);
            
        }
        

        $data['mytasks_paginated']=$this->ui->paginate($pages);

        echo $this->parser->parse('expertos/widgets/2doMe2', $data, true, true);
    }
    
    
    function widget_2doMe2_c($chunk = 1, $pagesize = 2000) {
        
        //$data['lang']=$this->lang->language;
        $this->load->model('bpm/bpm');
        $query = array(
            'status' => 'user',
            'idwf' => 'fondo_semilla2016'
        );

        //var_dump(json_encode($query));exit;
        $tasks = $this->bpm->get_tasks_byFilter($query, array(), array('checkdate' => 'desc'));
        //$data=$this->prepare_tasks($tasks, $chunk, $pagesize);
        $data = Modules::run('bpm/bpmui/prepare_tasks', $tasks, $chunk, $pagesize);
        //var_dump($data);
        //exit();
        if (isset($data['mytasks'])) { 
            foreach ($data['mytasks'] as $k => $mytask) {
                $mycase = $this->bpm->get_case($mytask['case']);
                $data['mytasks'][$k]['extra_data']['ip'] = false;
                /*if (isset($mycase['data']['Empresas']['query']['id'])) {
                    $empresaID = $mycase['data']['Empresas']['query']['id'];
                    $empresa = $this->bpm->get_data('container.empresas', array('id' => $empresaID));
                    $data['mytasks'][$k]['extra_data']['empresa'] = $empresa[0]['1693'];
                }/*
                if (isset($mycase['data']['Asistencias']['query']['id'])) {
                    $proyectoID = $mycase['data']['Asistencias']['query']['id'];
                    $proyecto = $this->bpm->get_data('container.asistencias', array('id' => $proyectoID));
                    $data['mytasks'][$k]['extra_data']['ip'] = $proyecto[0]['4837'];
                    

                    $url = (isset($mycase['data'] ['Asistencias']['query']['id'])) ? '../dna2/frontcustom/284/list_docs_crefis_eval.php?id=' . $mycase['data'] ['Proyectos_crefis']['query'] ['id'] : '#';
                    $data['mytasks'][$k]['link_open'] = $this->bpm->gateway($url);

                }*/
            }
        } else {
            $data['mytasks'] = array();
        }

        $data['title'] = $this->lang->line('Tasks') . ' ' . $this->lang->line('Pending');

        $data['more_info_link'] = $this->base_url . 'bpm/';
        $data['widget_url'] = base_url() . $this->router->fetch_module() . '/' . $this->router->class . '/' . __FUNCTION__;
        
        //==== Pagination

        $pagination=array_chunk($data['mytasks'],5);
        $pages=array();
        
        foreach($pagination as $chunk){
            $data['mytasks2']=$chunk;
            $pages[]=$this->parser->parse('fondosemilla/widgets/2doMe2_task', $data, true, true);
            
        }
        

        $data['mytasks_paginated']=$this->ui->paginate($pages);

        echo $this->parser->parse('fondosemilla/widgets/2doMe2', $data, true, true);
    }
    
function lite(){

    $this->load->model('bpm/bpm');
    $this->load->model('msg');
    $this->lang->language;

    $data['base_url'] = $this->base_url;
    $data['css'] = array($this->base_url . 'fondosemilla/assets/css/fondosemilla.css' => 'Estilo Lib',
        );
     // Inbox
     $data['inbox_count']=true;
     $data['inbox_count_qtty']=count($this->msg->get_msgs_by_filter(array('to'=>$this->idu,'folder'=>'inbox','read'=>false)));
     $data['inbox_count_label_class']='success';
     
     // Tramites
     $data['tramites_count']=true;
     $data['tramites_count_label_class']='success';


     // menu
        $this->load->model('menu/menu_model');
        $query = array('repoId' => 'tramites');
        $repo = $this->menu_model->get_repository($query);
  
  
  
        $tree = Modules::run('menu/explodeExtTree',$repo,'/');
  


    $data['tramites_extra']=(empty($tree[0]->children))?($this->lang->line('no_cases')):($menu); ;
     
    // Mis tramites
     $cases_count = $this->bpm->get_cases_byFilter_count(
                array(
            'iduser' => $this->idu,
            'idwf' => 'fondo_semilla2016',
            'status' => 'open',
                ), array(), array('checkdate' => 'desc')
        );
     $query = array(
            'assign' => $this->idu,
            'idwf' => 'fondo_semilla2016',
            'status' => 'user'
        );
        //var_dump(json_encode($query));exit;
    $tasks_count = $this->bpm->get_tokens_byFilter_count($query);    
    $data['mistramites_count']=true;
    $data['mistramites_count_label_class']='success';
    $data['mistramites_count_qtty']=$cases_count;

    $data['mistramites_extra']="---- Extra ";
    
    // tasks 
    $data['tareas_count']=true;
    $data['tareas_count_label_class']='warning';
    $data['tareas_count_qtty']=$tasks_count;
     

    $data['tareas_extra']=Modules::run('bpm/bpmui/widget_cases');
    // Parse    
     echo $this->parser->parse('lite', $data, true, true);
}

function asignar_incubadora($idwf, $idcase, $tokenId) {
        $this->load->library('parser');
        $this->load->model('user/group');
        $this->load->model('bpm/bpm');
        //$this->load->model('bpm/engine');
        $case = $this->bpm->get_case($idcase, $idwf);
        $renderData = $this->bpm->load_case_data($case, $idwf);
        //----tomo evaluador del caso
        $idu = floatval($renderData['Fondosemillaproyectos']['10034'][0]);
        
        
        //var_dump($idu);
        //exit();
        //----token que hay que finalizar 
        // $src_resourceId = 'oryx_A150EBF2-8F30-4631-B04B-90DBDB019C41';
        // ---Token de pp asignado
        //$lane_resourceId = 'oryx_295810F2-8C34-4D03-80F8-7B5C371381B8';
        
        $src_resourceId ='oryx_CB180436-5368-43F1-8822-1FDDFA4B5A08';
        $lane_resourceId='oryx_3DA3B98D-42F2-4661-8496-A21E619173B9';
        //$this->bpm->assign('model',$idwf,$idcase,$src_resourceId,$lane_resourceId,$idu);
        //exit();
        $url = $this->base_url . "bpm/engine/assign/model/$idwf/$idcase/$src_resourceId/$lane_resourceId/$idu";
        
        redirect($url);
        //$url = Modules::run("bpm/engine/assign/model/$idwf/$idcase/$src_resourceId/$lane_resourceId/$idu");
        //echo($url);
        //exit();
        //redirect($this->base_url ."/fondosemilla/semilla");
    }
    
    function get_cases_by_kpi($kpi){
        //obtiene los casos con el kpi
        return $this->kpi->Get_cases($kpi);
    }


    function exportar_xls($idkpi, $mode= "xls"){
    //  $this->load->module('afip');
    $this->load->module('pacc13/api13');    
    $renderData['base_url'] = $this->base_url;
    $renderData['module_url'] = $this->module_url;    
    $kpi = $this->Kpi_model->get($idkpi);
    $cases = $this->get_cases_by_kpi($kpi);
    $partidos = $this->app->get_ops(58);
    $actividades = $this->app->get_ops(884);
    $incubadoras = $this->api13->incubadoras_listado($filter, 'array');
    foreach ($cases as $key => $case ){
        $current = $this->bpm->get_case($case, 'fondo_semilla2016');
        $data = $this->bpm->load_case_data($current, 'fondo_semilla2016');
        $renderData['data'][$key]['nombre'] = $data['Personas_9915'][0][1783];        
        $renderData['data'][$key]['apellido'] = $data['Personas_9915'][0][1784];
        $renderData['data'][$key]['genero'] = $data['Personas_9915'][0][2319][0];        
        $renderData['data'][$key]['email'] = $data['Personas_9915'][0][1786];
        $renderData['data'][$key]['provincia'] = $data['Personas_9915'][0]['5293'][0];        
        $renderData['data'][$key]['partido'] = $partidos[$data['Personas_9915'][0]['1788'][0]];
        $renderData['data'][$key]['localidad'] = $data['Personas_9915'][0]['1789'];
       // $renderData['data'][$key]['empresa'] = $data['Empresas_9893'][0]['1693'];        
        $renderData['data'][$key]['dni'] = $data['Personas_9915'][0]['1795'];        
        $renderData['data'][$key]['monto_solicitado'] = $data['Fondosemillaproyectos']['10176'];        
        $renderData['data'][$key]['numero'] = $data['Fondosemillaproyectos']['10007'];
        $renderData['data'][$key]['actividad_principal'] = $actividades[$data['Fondosemillaproyectos'][9900][0]];
        $renderData['data'][$key]['incubadora'] = $incubadoras[$data['Fondosemillaproyectos'][10034][0]]['nombre'];
    }
    $template='fondosemilla/exportar_xls';     
    switch($mode){
    case 'str':
     return $this->parser->parse($template,$renderData,true,true);
        break;
    case 'xls':
    header("Content-Description: File Transfer");
    header("Content-type: application/x-msexcel");
    header("Content-Type: application/force-download");
    header("Content-Disposition: attachment; filename='fondo_semilla2016'.xls");
    header("Content-Description: PHP Generated XLS Data");
    $this->parser->parse($template, $renderData);
        break;    
    case 'table':
     $this->parser->parse($template,$renderData);
        break;
    }
 }
    
    function reload_reportes_incubadora($incubadora = null){
        $this->load->module('pacc13/api13');
        $this->load->module('dashboard');
        $this->load->library('parser');
        $template="dashboard/widgets/box_info.php";
        
        
        $renderData['proyectos']= $this->Fondosemilla_model->proyectos_por_incubadora($incubadora);
        
        

        $template = array (
              '10007' => 'NO SE REGISTRAN PROYECTOS PARA ESTA INCUBADORA'
              );
              
        if (count($renderData['proyectos']) == 0){
            $renderData['proyectos'][0] = $template;
        }
        else
        {       
            foreach($renderData['proyectos'] as &$proyecto){
            $proyecto['persona']= $this->Fondosemilla_model->get_persona($proyecto[9915][0]);            
            $proyecto['persona'][5293] = $proyecto['persona'][5293][0]; 
            $proyecto += $template;
            };
        }
       echo $this->parser->parse('tabla-incubadoras', $renderData, true, true);
    }
    
    function reportes_incubadora(){
        $this->load->module('dashboard');
        $this->load->module('pacc13/api13');
        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        $renderData['title'] = 'Consultar Proyectos por Incubadora';
        $template="dashboard/widgets/box_info.php";
        $filter="";
        $renderData['incubadoras']= $this->api13->incubadoras_listado($filter, 'array');
        $renderData['tabla_estado']= "";
        $renderData['content']= $this->parser->parse('widget-incubadoras', $renderData, true, true);
        return $this->dashboard->widget($template, $renderData);
    }
    
    function reportes_casos_por_dni(){
        $this->load->module('dashboard');
        $this->load->module('pacc13/api13');
        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        $renderData['title'] = 'Consultar Casos por DNI';
        $template="dashboard/widgets/box_info.php";
        $filter="";
        $renderData['tabla_estado']= "";
        $renderData['content']= $this->parser->parse('widget-buscador', $renderData, true, true);
        return $this->dashboard->widget($template, $renderData);
    }
    
    function reload_reportes_casos_por_dni($id = null){
        $this->load->module('pacc13/api13');
        $this->load->module('dashboard');
        $this->load->library('parser');
        $template="dashboard/widgets/box_info.php";
        $user= $this->Fondosemilla_model->get_idu_by_id($id);
        $data = $this->bpm->get_cases_byFilter(array('iduser' => $user['idu']));
        foreach ($data as $key => $val){
        $renderData['casos'][$key] = $this->bpm->load_case_data($val, 'fondo_semilla2016');
        $renderData['casos'][$key]['token'] = $val['_id']->{'$id'}; 
        $renderData['casos'][$key]['idcase'] = $val['id'];
        }
        foreach ($renderData['casos'] as $key => &$caso){
            if (!isset($caso['Fondosemillaproyectos'])){ 
                unset($renderData['casos'][$key]);
            }
            $caso['Personas_9915'] = $caso['Personas_9915'][0];
        }
        echo $this->parser->parse('tabla-buscador', $renderData, true, true);
    }    
    
    function dump($incubadora = '3635201511'){
        $data= $this->Fondosemilla_model->proyectos_por_incubadora($incubadora);
        var_dump($data);
        exit;
    }
    
    function tile_total(){
        $this->load->module('dashboard');
        $data['title'] ="N° de Inscripciones Registradas";
        $data['icon']= "ion-person-stalker";
        $data['base_url'] = $this->base_url;
        $data['module_url'] = $this->module_url;
        $data['number'] =count($this->Fondosemilla_model->proyectos());
        $data['more_info_link']= $this->base_url."fondosemilla/semilla/exportar_total";
        return $this->parser->parse('dashboard/tiles/tile-green', $data, true, true);
    }    
    
    
    function tile_fortalecimiento_social(){
        $this->load->module('dashboard');
        $data['title'] ="Fortalecimiento de emprendimiento con impacto social";
        $data['icon']= "ion-person-stalker";
        $data['base_url'] = $this->base_url;
        $data['module_url'] = $this->module_url;
        $data['number'] =count($this->Fondosemilla_model->proyectos_social('4'));
        return $this->parser->parse('dashboard/tiles/tile-orange', $data, true, true);
    } 
    
    function tile_marcha_social(){
        $this->load->module('dashboard');
        $data['title'] ="Puesta en marcha de emprendimiento con impacto social";
        $data['icon']= "ion-person-stalker";
        $data['base_url'] = $this->base_url;
        $data['module_url'] = $this->module_url;
        $data['number'] =count($this->Fondosemilla_model->proyectos_social('3'));
        return $this->parser->parse('dashboard/tiles/tile-orange', $data, true, true);
    } 
    
    
    
    function exportar_total(){
        
        $data = $this->Fondosemilla_model->proyectos_completo();
        var_dump($data);
        exit;
        
        $template='fondosemilla/exportar_total';     

        
        header("Content-Description: File Transfer");
        header("Content-type: application/x-msexcel");
        header("Content-Type: application/force-download");
        header("Content-Disposition: attachment; filename='fondo_semilla2016'.xls");
        header("Content-Description: PHP Generated XLS Data");
        
        
        
    }
}

/* End of file crefis */
    /* Location: ./system/application/controllers/welcome.php */
