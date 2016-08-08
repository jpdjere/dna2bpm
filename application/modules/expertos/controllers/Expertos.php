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
class expertos extends MX_Controller {
    //--define el token que guarda la data consolidada para buscadores etc
    public $consolida_resrourceId='oryx_6772A7D9-3D05-4064-8E9F-B23B4F84F164';

    function __construct() {
        parent::__construct();
        $this->load->model('menu/menu_model');
        $this->load->model('bpm/bpm');
        $this->load->model('expertos/expertos_model');
        $this->user->isloggedin();
        // ---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
        $this->load->config('expertos/config');
        // ----LOAD LANGUAGE
        $this->lang->load('library', $this->config->item('language'));
        $this->idu = (int) $this->session->userdata('iduser');
        $this->load->library('pagination');
        $this->load->library('dashboard/ui');
        /* GROUP */
        $user = $this->user->get_user($this->idu);

        $this->id_group = ($user->{'group'});
    }

    function Index() {
        //$this->Add_group();
        //$this->proyecto();
    }

    function Proyecto($debug=false) {
        $this->user->authorize();
        Modules::run('dashboard/dashboard', 'expertos/json/expertos_proyectos.json',$debug);
    }

    function Direccion($debug=false) {
        $this->user->authorize();
        $grupo_user='Expertos/DireccionBPM';
        $this->Add_group($grupo_user);
        //Modules::run('dashboard/dashboard', 'expertos/json/expertos_direccion.json',$debug);
        Modules::run('dashboard/dashboard', 'expertos/json/expertos_admin.json',$debug);
      
        
    }
    
    function Profesionales($debug=false) {
        $this->user->authorize();
        $grupo_user='Expertos/Empresa / Institucion';
        $this->Add_group($grupo_user);
        $grupo_user='Expertos/Expertos';
        $this->Add_group($grupo_user);
        //Modules::run('dashboard/dashboard', 'expertos/json/expertos_direccion.json',$debug);
        Modules::run('dashboard/dashboard', 'expertos/json/expertos_prof.json',$debug);
      
        
    }
    
    
    

    function Admin($debug=false) {
        $this->user->authorize();
        Modules::run('dashboard/dashboard', 'expertos/json/expertos_admin.json',$debug);
    }

    function Mesa_de_entradas($debug=false) {
        $this->user->authorize();
        Modules::run('dashboard/dashboard', 'crefis/json/crefis_mesaentrada.json',$debug);
    }



    function eliminar_en_preparacion($process = false) {
        $this->user->authorize();
        $filter = array(
            'resourceId' => 'oryx_B5BD09EE-57CF-41BC-A5D5-FAA1410804A5',
            'status' => 'user',
            'idwf' => 'crefisGral',
        );

        $tokens = $this->bpm->get_tokens_byFilter($filter, array('case'));
        foreach ($tokens as $token) {
            echo "Processing:" . $token['case'] . '<hr/>';
            if ($process)
                $this->bpm->delete_case('crefisGral', $token['case']);
        }
    }
    /**
     * PROYECTS AMOUNT
     *
     * Description Calculate the amount  of money  in projects grouped by status
     * name proyects_amount
     * @author Diego Otero
     */
    function proyects_amount($filtroproy = null) {

        $this->user->authorize();
        $this->load->model('crefis/crefis_model');

        /* OPTIONS */
        $this->load->model('app');
        $option = $this->app->get_ops(772);

        $llamado = (isset($filtroproy)) ? $filtroproy['llamado'] : array('$exists' => true);
        $query = array('8335' => $llamado);

        $cases = $this->crefis_model->get_cases_byFilter_container('crefisGral', 195, $query);
        $cases_arr = array();
        foreach ($cases as $case) {
            $id = $case['data']['Proyectos_crefis']['query']['id'];
            if (isset($id)) {
                $query = array('id' => $id);
                $values = $this->crefis_model->get_amount_stats_by_id($query);

                $ctrl_value = (isset($values[0][4970][0])) ? $values[0][4970][0] : $values[0][4970];
                $value8326 = (isset($values[0][8326])) ? str_replace(",", ".", str_replace(".", "", $values[0][8326])) : 0;
                $value8573 = (isset($values[0][8573])) ? str_replace(",", ".", str_replace(".", "", $values[0][8573])) : 0;

                $amount = ($ctrl_value >= 30) ? $value8573 : $value8326;
                $cases_arr[$option[$ctrl_value]][]  = (float) $amount;
            }
        }


        foreach ($cases_arr as $key => $task) {
            $new_task = array();
            $new_task['status'] = $key;
            $new_task['how_many'] = count($task);
            $new_task['amount'] = "$" . @number_format(array_sum($task), 2, ",", ".");
            $wfData['mini'][] = $new_task;
        }


        $wfData ['base_url'] = base_url();
        $wf = $this->bpm->load('crefisGral');
        $wfData += $wf ['data'] ['properties'];
        $wfData ['name'] = 'Montos por Estados';

        return $this->parser->parse('crefis/montos_estados', $wfData, true, true);
    }

    /* REFACTOR */

    function proyects_amount_ori() {
        $this->user->authorize();
        $state = $this->status_amounts();

        foreach ($state as $key => $task) {

            $new_task = array();
            $new_task['status'] = $key;
            $new_task['how_many'] = count($task);
            $new_task['amount'] = "$" . @number_format(array_sum($task), 2, ",", ".");
            $wfData['mini'][] = $new_task;
        }

        $wfData ['base_url'] = base_url();
        $wf = $this->bpm->load('crefisGral');
        $wfData += $wf ['data'] ['properties'];
        $wfData ['name'] = 'Montos por Estados';

        return $this->parser->parse('crefis/montos_estados', $wfData, true, true);
    }

    /**
     * STATUS_AMOUNTS
     *
     * Description Calculate the amount  of money  in projects grouped by status
     * name status_amounts
     * @author Diego Otero



    /* END REFACTOR */

    /**
     * PROYECTS EVALUATOR
     *
     * Description
     * name projects_evaluator
     * @author Diego Otero
     */


    /**
     * EVALUATOR PROJECTS
     *
     * Description
     * name evaluator_projects
     * @author Diego Otero
     */
    function evaluator_projects() {
        $this->load->model('crefis_model');

        $output = 'array';
        $filter = array();

        $filter['idwf'] = 'crefisGral';
        $querys = $this->crefis_model->get_evaluator_by_project($filter);
        //var_dump($querys);exit;

        /* OPTIONS */
        $this->load->model('app');
        $option = $this->app->get_ops(772);


        foreach ($querys[0] as $values) {

            $ctrl_value = (isset($values[4970][0])) ? $values[4970][0] : $values[4970];


            $evaluator_id = $values[8668][0];

            list($filing_year, $filing_month, $filing_day) = explode("/", $values[8340]);
            $filing_date = $filing_day . "/" . $filing_month . "/" . $filing_year;

            $company_id = floatval($values[8325][0]);
            $company = $this->crefis_model->get_company_by_project_by_id($company_id);

            $proyect_array = array(
                "project_ip" => $values[8339]
                , "project_id" => $values['id']
                , "status" => $option[$ctrl_value]
                , "filing_date" => $filing_date
                , "cuit" => $company[0][1695], "business_name" => $company[0][1693]
            );
            $cases_arr[$evaluator_id][] = $proyect_array;
        }

        return $cases_arr;
    }

    function Landing() {
        $this->Add_group();
        redirect($this->module_url);
    }

   /**
     * Agrega el grupo a los que entran al panel para que puedan ejecutar el BPM
     */
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

    function set_evaluador($idwf, $idcase, $tokenId) {
        $this->load->library('parser');
        $this->load->model('bpm/bpm');
        $this->load->library('bpm/ui');

        $group_name = 'crefis/EVALUADOR TÉCNICO';
        $case = $this->bpm->get_case($idcase, $idwf);
        $renderData = $this->bpm->load_case_data($case, $idwf);
        $mywf = $this->bpm->load($idwf);
        $wf = $this->bpm->bindArrayToObject($mywf ['data']);
        //---tomo el template de la tarea
        $shape = $this->bpm->get_shape('oryx_86F5055B-EF9B-4EB3-A636-F4D8AD782981', $wf);
        //----token que hay que finalizar
        $src_resourceId = 'oryx_86F5055B-EF9B-4EB3-A636-F4D8AD782981';
        // ---Token de pp asignado
        $lane_resourceId = 'oryx_295810F2-8C34-4D03-80F8-7B5C371381B8';
        // ----get evaluadores
        $evaluadores = $this->user->getbygroupname($group_name);
        $url = $this->base_url . "bpm/engine/assign/model/$idwf/$idcase/$src_resourceId/$lane_resourceId";
        $evaluadores = array_map(function ($user) use($url) {
            // var_dump($user);exit;
            $rtn_arr = array(
                'iduser' => $user->idu,
                'name' => $user->name,
                'lastname' => $user->lastname,
                'nick' => $user->nick,
                'url' => $url . '/' . $user->idu
            );
            return $rtn_arr;
        }, $evaluadores);

        $renderData ['title'] = "crefis::Assignar Evaluador";
        $renderData ['name'] = "Assignar Evaluador";
        $renderData ['documentation'] = ($shape->properties->documentation <> '') ? nl2br($this->parser->parse_string($shape->properties->documentation, $renderData, true, true)) : '';
        $renderData ['base_url'] = base_url();
        $renderData ['button'] = $evaluadores;
        // ---prepare UI
        $renderData ['js'] = array(
            $this->base_url . 'bpm/assets/jscript/modal_window.js' => 'Modal Window Generic JS'
        );
        $renderData ['global_js'] = array(
            'base_url' => $this->base_url,
            'module_url' => $this->module_url,
            'idwf' => $idwf,
            'idcase' => $idcase,
            'resourceId' => $src_resourceId
        );

        $this->ui->compose('crefis/get_user', 'bpm/bootstrap.ui.php', $renderData);
    }

    function clone_case($from_idwf, $to_idwf, $idcase) {
        $this->load->model('bpm/bpm');
        $this->load->module('bpm/engine');
        $case = $this->bpm->get_case($idcase, $from_idwf);
        $case_to = $this->bpm->get_case($idcase, $to_idwf);
        if (!$case_to) {

            $this->bpm->gen_case($to_idwf, $idcase);
            $case_to = $this->bpm->get_case($idcase, $to_idwf);
            $case_to['data'] = $case['data'];
            $case_to['iduser'] = $case['iduser'];
            $case_to = $this->bpm->save_case($case_to);
            $this->engine->Startcase('model', $to_idwf, $idcase);
        } else {
            /*
             *   continue case
             */
            $mywf = $this->bpm->load($to_idwf);
            if (!$mywf) {
                show_error("Model referenced:$idwf does not exists");
            }
            $wf = bindArrayToObject($mywf ['data']);
            // ---Get all start points of diagram
            $start_shapes = $this->bpm->get_start_shapes($wf);
            // ----Raise an error if doesn't found any start point
            if (!$start_shapes)
                show_error("The Schema doesn't have an start point");
            // ---Start all StartNoneEvents as possible
            foreach ($start_shapes as $start_shape) {
                $this->bpm->set_token($to_idwf, $idcase, $start_shape->resourceId, $start_shape->stencil->id, 'pending');
            }
            $this->engine->Run('model', $to_idwf, $idcase);
        }

        //----run case
        // Modules::run("bpm/run/model/$to_idwf/$idcase");
    }

    function show_msgs($idcase) {

        $idwfs = array('crefisGral');
        foreach ($idwfs as $idwf) {
            $filter = array(
                'idwf' => $idwf,
                'case' => $idcase,
            );

            //$title = ($idwf == 'crefisGral') ? "Pre Aprobados" : "Aprobados";
            $title='al Proyecto';
            $cdata = array();
            //$cdata['title'] = "Notificaciones (" . $title . "): ";
            $cdata['title'] = "Notificaciones " . $title . ": ";
            echo Modules::run('inbox/show_msgs_by_filter', $filter, $cdata);
        }
    }

    function delegate_case() {

        $this->load->model('crefis_model');
        $idwf = 'crefisGral';
        $idcase = 'XYIK';
        $iduser_dest = -2101255759;

        $update = $this->crefis_model->delegate_case_action($idwf, $idcase, $iduser_dest);
        return $update;
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
        $this->load->module('bpm/engine');
        $query1 = array(
            'iduser' => $this->idu,
            //'status' => 'user',
            'idwf' => 'Expertos_Base'
        );
        //var_dump($this->idu);
        //var_dump(json_encode($query));exit;
        $cases=$this->bpm->get_cases_byFilter($query1);
        //echo "Mass Revert:".count($cases);
        $me=$this->user->idu;
        foreach($cases as $case){
        $this->user->idu=$case['iduser'];
            //var_dump($case);
            if($case['token_status']['oryx_D86216E3-A7DA-49DF-9886-AE1028BF67DD']== "pending"){
                $this->engine->Run('model', 'Expertos_Base', $case['id'],null, true);
            }
            //echo '<hr>';
        
        }
        
         $query2 = array(
            'iduser' => $this->idu,
            //'status' => 'user',
            'idwf' => 'Consultores_Base'
        );
        //var_dump($this->idu);
        //var_dump(json_encode($query));exit;
        $cases=$this->bpm->get_cases_byFilter($query2);
        //echo "Mass Revert:".count($cases);
        $me=$this->user->idu;
        foreach($cases as $case){
        $this->user->idu=$case['iduser'];
            //var_dump($case);
            if($case['token_status']['oryx_D86216E3-A7DA-49DF-9886-AE1028BF67DD']== "pending"){
                $this->engine->Run('model', 'Consultores_Base', $case['id'],null, true);
            }
            //echo '<hr>';
        
        }
        
        
        
        
        $query = array(
            'iduser' => $this->idu,
            'status' => 'user',
            //'resourceId'=>array('$in'=>array('oryx_239E7788-6856-4E5C-AD8F-AFB3E7E148CB','oryx_418574EB-BC6F-4BEA-B0DF-057AC8DB9F89','oryx_F99531B2-44B0-4308-ACB0-79C03B9824B6')),
            //'resourceId'=>array('oryx_F99531B2-44B0-4308-ACB0-79C03B9824B6'),
            //'assign' => $this->idu,
            //'idwf' => array('$in'=>array('Expertos_Base','carga_pro_inst'))//'Expertos_Base'
            'idwf' => 'carga_pro_inst'//'Expertos_Base'
        );
        $tasks = $this->bpm->get_tasks_byFilter($query, array(), array('checkdate' => 'desc'));
        //var_dump($tasks);
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
            //'assign' => $this->idu,
            'status' => 'user',
            'resourceId' =>'oryx_6D62D76F-20D0-4DF5-A56D-AE1731BFCDA8',
            'idwf' =>array('$in'=>array('Expertos_Base','carga_pro_inst')) 
            //'idwf' => 'Expertos_Base'
        );

        //var_dump(json_encode($query));exit;
        $tasks = $this->bpm->get_tasks_byFilter($query, array(), array('checkdate' => 'desc'));
        //$data=$this->prepare_tasks($tasks, $chunk, $pagesize);
        $data = Modules::run('bpm/bpmui/prepare_tasks', $tasks, $chunk, $pagesize);
        
        if (isset($data['mytasks'])) { 
            foreach ($data['mytasks'] as $k => $mytask) {
                $mycase = $this->bpm->get_case($mytask['case']);
                //var_dump($mycase);
                //$empresaID = $mycase['data']['Empresas']['query']['id'][0];
                //var_dump($empresaID);
                //$empresa = $this->bpm->get_data('container.empresas', array('id' => $empresaID));
                //var_dump($empresa);
                $data['mytasks'][$k]['extra_data']['ip'] = false;
                if (isset($mycase['data']['Empresas']['query']['id'][0])) {
                    
                    $empresaID = $mycase['data']['Empresas']['query']['id'][0];
                    $empresa = $this->bpm->get_data('container.empresas', array('id' => $empresaID));
                    $data['mytasks'][$k]['extra_data']['ip'] = $empresa[0]['1695'];
                    
                    //var_dump($empresaID);
                    
                    $data['mytasks'][$k]['extra_data']['empresa'] = $empresa[0]['1693'];
                    //var_dump($empresa);
                }/*
                if (isset($mycase['data']['Asistencias']['query']['id'])) {
                    $proyectoID = $mycase['data']['Asistencias']['query']['id'];
                    $proyecto = $this->bpm->get_data('container.asistencias', array('id' => $proyectoID));
                    $data['mytasks'][$k]['extra_data']['ip'] = $proyecto[0]['4837'];
                    

                    $url = (isset($mycase['data'] ['Asistencias']['query']['id'])) ? '../dna2/frontcustom/284/list_docs_crefis_eval.php?id=' . $mycase['data'] ['Proyectos_crefis']['query'] ['id'] : '#';
                    $data['mytasks'][$k]['link_open'] = $this->bpm->gateway($url);

                }*/
            }
            //var_dump($data);
        } else {
            $data['mytasks'] = array();
        }

        $data['title'] =  $this->lang->line('Tasks') . ' ' . $this->lang->line('Pending');

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
    
    function widget_2doMe2_d($chunk = 1, $pagesize = 2000) {
        
        //$data['lang']=$this->lang->language;
        $this->load->model('bpm/bpm');
        $this->load->module('bpm/engine');
        
        
        $query = array(
            //'iduser' => $this->idu,
            //'case' => 'CASQ',
            
            'assign' => $this->idu,
            'status' => 'user',
            'type' => 'Task',
            'idwf' => 'Expertos_Base'
        );
        $tasks = $this->bpm->get_tasks_byFilter($query, array(), array('checkdate' => 'desc'));
        //var_dump($tasks);
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
    
    
    
    
    

    public function faq() {
        $this->user->authorize();
        $config['title']="Preguntas frecuentes";
        $config['class']="info";
        $config['body']="<a class='btn btn-info' href='http://www.accionpyme.mecon.gob.ar/downloads/produccion/capacitacionPyme/faq_2016.pdf' target='_blank'><i class='fa fa-file-pdf-o'></i>
 Descargar</a>";
        echo $this->ui->callout($config);

    }

    /*public function mass_revert(){
        $resourceId='oryx_D86216E3-A7DA-49DF-9886-AE1028BF67DD';
        $this->load->module('bpm/case_manager');
        $this->load->module('bpm/engine');
        $modelo = 'Consultores_Base';
        $query=array(
            'idwf'=> $modelo
            
            );
        $cases=$this->bpm->get_cases_byFilter($query);
        echo "Mass Revert:".count($cases);
        foreach($cases as $case){
            var_dump($case);
            $this->user->idu=$case['iduser'];
            //$this->case_manager->revert('model', $modelo, $case['id'], $resourceId);
            //$this->engine->Run('model', $modelo, $case['id'],null, true);
            if($case['token_status']['oryx_D86216E3-A7DA-49DF-9886-AE1028BF67DD']== "pending"){
                $this->engine->Run('model', $modelo, $case['id'],null, true);
            }
            echo '<hr>';
        }
        
    }*/
    public function mass_up(){
        $resourceId='oryx_A4F665D4-3F0D-4EBB-980E-689F527F092B';
        $this->load->module('bpm/case_manager');
        $this->load->module('bpm/engine');
        $modelo = 'Expertos_Base';
        $model = 'expertos_model';
        //$modelo2 = 'carga_pro_inst';
        $query=array(
            'idwf'=> $modelo
           
            );
            
        $cases=$this->bpm->get_cases_byFilter($query);
        echo "Mass up ".$modelo.":" .count($cases);
        foreach($cases as $case){
            //var_dump($case['resourceId']);
//oryx_F99531B2-44B0-4308-ACB0-79C03B9824B6
            var_dump($case['idwf']);
            var_dump($case['id']);
            var_dump($case['iduser']);
            
            $rf = $this->$model->update_tokens($case['idwf'],$case['id'],$case['iduser']);
            var_dump($rf);
        }
        
    }
    
    function buscador(){
    
        $this->load->module('dashboard');
        $renderData['title'] = "Buscador";
        $renderData['base_url'] = $this->base_url;
        $renderData['module_url'] = $this->module_url;
        $renderData ['js'] = array(
            $this->module_url . '/assets/jscript/buscador.js' => 'Buscador'
        );
        $renderData ['css'] = array(
            $this->module_url . '/assets/css/buscador.css' => 'Buscador'
        );        
        $template="dashboard/widgets/box_info.php";
        $renderData['tabla_contenido'] = "";
        $renderData['content']= $this->parser->parse('buscador', $renderData, true, true);
        return $this->dashboard->widget($template, $renderData);
    }
    
    function tabla($val, $type){
        
        $val = urldecode($val);

        
        if ($type == 'cuit' ){ 
            $type = '1695';} else{
                $type = '1693';
            }

        $data['data']= $this->expertos_model->get_company($type, $val);
        echo $this->parser->parse('resultados', $data, true, true);
    }
    
    function empresas_lite(){

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
            'idwf' => array ('Expertos_Empresas'),
            'status' => 'open',
                ), array(), array('checkdate' => 'desc')
        );
        $query = array(
            'assign' => $this->idu,
            'idwf' => array ('Expertos_Empresas'),
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
        echo $this->parser->parse('expertos/empresas-lite', $data, true, true);
    }

    function experto_lite(){

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
            'idwf' => array ('$in' => array('carga_pro_inst','Expertos_Base')),
            'status' => 'open',
                ), array(), array('checkdate' => 'desc')
        );
        $query = array(
            'assign' => $this->idu,
            'idwf' => array ('$in' => array('carga_pro_inst','Expertos_Base')),
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
        echo $this->parser->parse('expertos-lite', $data, true, true);
}    
    
    function empresas(){
        $this->user->authorize();
        $grupo_user = 'Expertos/Expertos';
        $extraData['css'] = array($this->base_url . 'fondosemilla/assets/css/fondosemilla.css' => 'Estilo Lib'
        );        
        $this->Add_group($grupo_user);
        Modules::run('dashboard/dashboard', 'expertos/json/empresas_lite.json',$debug = false, $extraData);    
        
    }
    
    function experto($debug = false){
        $this->user->authorize();
        $grupo_user = 'Expertos/Empresa / Institucion';
        $extraData['css'] = array($this->base_url . 'fondosemilla/assets/css/fondosemilla.css' => 'Estilo Lib'
        );        
        $this->Add_group($grupo_user);
        //Modules::run('dashboard/dashboard', 'expertos/json/expertos_direccion.json',$debug);
        Modules::run('dashboard/dashboard', 'expertos/json/expertos_lite.json',$debug, $extraData);        
    }
    
}

/* End of file crefis */
    /* Location: ./system/application/controllers/welcome.php */
