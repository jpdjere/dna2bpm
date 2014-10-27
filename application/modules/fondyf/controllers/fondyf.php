<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * fondyf
 *
 * Description of the class fondyf
 *
 * @author Juan Ignacio Borda <juanignacioborda@gmail.com>
 *         @date Jul 18, 2014
 */
class Fondyf extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('menu/menu_model');
        $this->user->isloggedin();
        // ---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
        $this->load->config('fondyf/config');
        // ----LOAD LANGUAGE
        $this->lang->load('library', $this->config->item('language'));
        $this->idu = (int) $this->session->userdata('iduser');


        /* GROUP */
        $user = $this->user->get_user($this->idu);

        $this->id_group = ($user->{'group'});
    }

    function Index() {
        $this->Add_group();
        $this->proyecto();
    }

    function Proyecto() {
        $this->user->authorize();
        Modules::run('dashboard/dashboard', 'fondyf/json/fondyf_proyectos.json');
    }

    function Evaluador() {
        $this->user->authorize();
        Modules::run('dashboard/dashboard', 'fondyf/json/fondyf_evaluador.json');
    }

    function Admin() {
        $this->user->authorize();
        Modules::run('dashboard/dashboard', 'fondyf/json/fondyf_admin.json');
    }

    function tile_proyectos() {
        // ----portable indicators are stored as json files
        $kpi = json_decode($this->load->view("fondyf/kpi/empresa_proyectos_presentados.json", '', true), true);
        echo Modules::run('bpm/kpi/tile_kpi', $kpi);
    }

    function tile_solicitud() {
        $data ['number'] = 'Solicitud';
        $data ['title'] = 'Crea una nueva solicitud';
        $data ['icon'] = 'ion-document-text';
        $data ['more_info_text'] = 'Comenzar';
        $data ['more_info_link'] = $this->base_url . 'bpm/engine/newcase/model/fondyfpp';
        echo Modules::run('dashboard/tile', 'dashboard/tiles/tile-green', $data);
    }

    function tile_comite() {
        $this->user->authorize();
        $this->load->model('bpm/bpm');
        $this->load->model('dna2/dna2old');
        $dna2url = $this->dna2old->get('url');
        // http://www.accionpyme.mecon.gob.ar/dna2/frontcustom/286/sol_ministro_2014.R.php
        $url = $dna2url . "frontcustom/286/sol_ministro_2014.R.php";
        $url = $this->bpm->gateway($url);
        $data ['number'] = 'Comité';
        $data ['title'] = 'Enviar a Comité';
        $data ['icon'] = 'ion-archive';
        $data ['more_info_text'] = 'Descargar';
        $data ['more_info_link'] = $url;
        echo Modules::run('dashboard/tile', 'dashboard/tiles/tile-green', $data);
    }

    function tile_buscar() {
        $this->user->authorize();
        $data = array();
        return $this->parser->parse('fondyf/buscar_proyecto', $data, true);
    }

    function buscar($type = null) {
        $this->user->authorize();
        $this->load->model('bpm/bpm');
        $this->load->library('parser');
        $template = 'fondyf/listar_proyectos';
        $filter = array(
            'idwf' => 'fondyfpp',
            'resourceId' => 'oryx_508C9A17-620B-4A6F-8508-D3D14DAB6DA2'
        );
        $data ['querystring'] = $this->input->post('query');
        // -----busco en el cuit
        $filter ['$or'] [] = array(
            'data.1695' => array(
                '$regex' => new MongoRegex('/' . $this->input->post('query') . '/i')
            )
        );
        // -----busco en el nombre empresa
        $filter ['$or'] [] = array(
            'data.1693' => array(
                '$regex' => new MongoRegex('/' . $this->input->post('query') . '/i')
            )
        );
        // -----busco en el nro proyecto
        $filter ['$or'] [] = array(
            'data.8339' => array(
                '$regex' => new MongoRegex('/' . $this->input->post('query') . '/i')
            )
        );
        $filter ['$or'] [] = array(
            'case' => array(
                '$regex' => new MongoRegex('/' . $this->input->post('query') . '/i')
            )
        );
        $tokens = $this->bpm->get_tokens_byFilter($filter, array(
            'case',
            'data',
            'checkdate'
                ), array(
            'checkdate' => false
        ));
//        var_dump(json_encode($filter),count($tokens));
        $data ['empresas'] = array_map(function ($token) {
            // var_dump($token['_id']);
            $case = $this->bpm->get_case($token ['case']);
            $data = $this->bpm->load_case_data($case);

            $url = (isset($data ['Proyectos_fondyf']['id'])) ? '../dna2/RenderView/printvista.php?idvista=3597&idap=286&id=' . $data ['Proyectos_fondyf'] ['id'] : '#';
            $url_msg = (isset($token ['case'])) ? 'show_msgs/fondyfpp/' . $token ['case'] : null;
            /* FonDyF/COORDINADOR (134) */
            $url_bpm_check = (in_array(134, $this->id_group)) ? '/bpm/engine/run/model/fondyfpp/' . $token ['case'] : null;

            $url_bpm = 0;
            if (isset($url_bpm_check))
                $url_bpm = $this->bpm->gateway($url_bpm_check);

            /* STATUS */
            $status = "N/A";
            if (isset($data ['Proyectos_fondyf'] ['8334'])) {
                $this->load->model('app');
                $option = $this->app->get_ops(772);
                $status = $option[$data ['Proyectos_fondyf'] ['8334'][0]];
            }


            return array(
                '_d' => $token ['_id'],
                'case' => $token ['case'],
                'nombre' => (isset($data['Empresas']['1693'])) ? $data['Empresas']['1693'] : '',
                'cuit' => (isset($data['Empresas']['1695'])) ? $data['Empresas']['1695'] : '',
                'Nro' => (isset($data ['Proyectos_fondyf'] ['8339'])) ? $data ['Proyectos_fondyf'] ['8339'] : 'N/A',
                'estado' => $status,
                'fechaent' => date('d/m/Y', strtotime($token ['checkdate'])),
                'link_open' => $this->bpm->gateway($url), 'link_msg' => $url_msg, 'url_bpm' => $url_bpm
            );
        }, $tokens);
        $data ['count'] = count($tokens);
        $this->parser->parse($template, $data, false, true);
    }

    function mini_status_resultado($idwf, $resourceId, $status) {

        $this->user->authorize();
        $this->load->model('bpm/bpm');
        $this->load->library('parser');
        $template = 'fondyf/listar_proyectos';
        $filter = array(
            'idwf' => $idwf,
            'resourceId' => $resourceId,
            'status' => $status,
        );

        $tokens = $this->bpm->get_tokens_byFilter($filter, array(
            'case',
            'data',
            'checkdate'
                ), array(
            'checkdate' => false
        ));
//        var_dump(json_encode($filter),count($tokens));


        $data ['empresas'] = array_map(function ($token) {
            // var_dump($token['_id']);
            $case = $this->bpm->get_case($token ['case']);
            $data = $this->bpm->load_case_data($case);

            $url = (isset($data ['Proyectos_fondyf']['id'])) ? '../dna2/RenderView/printvista.php?idvista=3597&idap=286&id=' . $data ['Proyectos_fondyf'] ['id'] : '#';
            $url_msg = (isset($token ['case'])) ? 'show_msgs/fondyfpp/' . $token ['case'] : null;

            /* FonDyF/COORDINADOR (134) */
            $url_bpm_check = (in_array(134, $this->id_group)) ? '/bpm/engine/run/model/fondyfpp/' . $token ['case'] : null;

            $url_bpm = 0;
            if (isset($url_bpm_check))
                $url_bpm = $this->bpm->gateway($url_bpm_check);

            /* STATUS */
            $status = "N/A";
            if (isset($data ['Proyectos_fondyf'] ['8334'])) {
                $this->load->model('app');
                $option = $this->app->get_ops(772);
                $status = $option[$data ['Proyectos_fondyf'] ['8334'][0]];
            }

            return array(
                '_d' => $token ['_id'],
                'case' => $token ['case'],
                'nombre' => (isset($data['Empresas']['1693'])) ? $data['Empresas']['1693'] : '',
                'cuit' => (isset($data['Empresas']['1695'])) ? $data['Empresas']['1695'] : '',
                'Nro' => (isset($data ['Proyectos_fondyf'] ['8339'])) ? $data ['Proyectos_fondyf'] ['8339'] : 'N/A',
                'estado' => $status,
                'fechaent' => date('d/m/Y', strtotime($token ['checkdate'])),
                'link_open' => $this->bpm->gateway($url), 'link_msg' => $url_msg, 'url_bpm' => $url_bpm
            );
        }, $tokens);


        $data ['count'] = count($tokens);
        //---saco título para el resultado
        $mywf = $this->bpm->load($idwf);
        $wf = $this->bpm->bindArrayToObject($mywf ['data']);
        //---tomo el template de la tarea
        $shape = $this->bpm->get_shape($resourceId, $wf);
        $add = ($status == 'user') ? 'En curso' : 'Finalizado';
        $data['querystring'] = $shape->properties->name . ' / ' . $add;



        $this->parser->parse($template, $data, false, true);
    }

    function setup() {
        $this->user->authorize();
        echo Modules::run('bpm/kpi/import_kpi', 'fondyf');
    }

    function ministatus_pp() {
        $this->user->authorize();
        $state = Modules::run('bpm/manager/mini_status', 'fondyfpp', 'array');

        $state = array_filter($state, function ($task) {
            return $task ['type'] == 'Task';
        });
        // ---las aplano un poco
        foreach ($state as $task) {
            $task ['user'] = (isset($task ['status'] ['user'])) ? $task ['status'] ['user'] : 0;
            $task ['finished'] = (isset($task ['status'] ['finished'])) ? $task ['status'] ['finished'] : 0;
            $wfData ['mini'] [] = $task;
        }

        //var_dump($wfData);
        $wfData ['base_url'] = base_url();
        $wf = $this->bpm->load('fondyfpp');
        $wfData += $wf ['data'] ['properties'];
        $wfData ['name'] = 'Mini Status: ' . $wfData ['name'];
        return $this->parser->parse('fondyf/ministatus_pp', $wfData, true, true);
    }

    /**
     * PROYECTS AMOUNT 
     *
     * Description Calculate the amount  of money  in projects grouped by status
     * name proyects_amount
     * @author Diego Otero 
     */
    function proyects_amount() {
        $this->user->authorize();
        $state = Modules::run('bpm/manager/status_amounts', 'fondyfpp', 'array');

        foreach ($state as $key => $task) {
            $new_task = array();
            $new_task['status'] = $key;
            $new_task['amount'] = "$" . @number_format(array_sum($task), 2, ",", ".");
            $wfData['mini'][] = $new_task;
        }

        // var_dump($wfData);exit;

        $wfData ['base_url'] = base_url();
        $wf = $this->bpm->load('fondyfpp');
        $wfData += $wf ['data'] ['properties'];
        $wfData ['name'] = 'Montos por Estados';

        return $this->parser->parse('fondyf/montos_estados', $wfData, true, true);
    }

    /**
     * PROYECTS EVALUATOR 
     *
     * Description 
     * name projects_evaluator
     * @author Diego Otero 
     */
    function projects_evaluator() {
        $this->user->authorize();
        $state = Modules::run('bpm/manager/evaluator_projects', 'fondyfpp', 'array');
        
       
        foreach ($state as $key => $task) {
            $new_task = array();
            $project = null;
            foreach ($task as $each) {
               
                $user = (array) $this->user->get_user_safe($key);
                $evaluator_info = strtoupper($user['nick']) . " (" . $user['name'] . " " . $user['lastname'] . ")";
                
                $url = '../dna2/RenderView/printvista.php?idvista=3597&idap=286&id='. $each['project_id'];
                
                $projData['url'] = $this->bpm->gateway($url);
                $projData['project_value'] = $each['project_ip'];
                $projData['status'] = $each['status'];

                $project .= $this->parser->parse('fondyf/proyectos_evaluador_anchor', $projData, true, true);
            }


            $new_task['evaluator'] = $evaluator_info;
            $new_task['project'] = $project;
            $wfData['mini'][] = $new_task;
        }

        $wfData ['base_url'] = base_url();
        $wf = $this->bpm->load('fondyfpp');
        $wfData += $wf ['data'] ['properties'];
        $wfData ['name'] = 'Evaluadores por proyecto';

        return $this->parser->parse('fondyf/proyectos_evaluador', $wfData, true, true);
    }

    function ver_ficha($idwf, $idcase, $token, $id = null) {

        $this->user->authorize();
        $this->load->model('bpm/bpm');
        $this->load->model('dna2/dna2old');
        $dna2url = $this->dna2old->get('url');
        if ($id) {
            $url = $dna2url . "RenderEdit/editnew.php?idvista=3560&origen=V&idap=286&id=$id&idwf=$idwf&case=$idcase&token=$token";
        } else {
            $url = $dna2url . "RenderEdit/editnew.php?idvista=3560&origen=V&idap=286&idwf=$idwf&case=$idcase&token=$token";
        }

        $url = $this->bpm->gateway($url);
        redirect($url);
    }

    function imprimir_proyecto($idwf, $idcase, $token, $id = null) {

        $this->user->authorize();
        $this->load->model('bpm/bpm');
        $this->load->model('dna2/dna2old');
        $dna2url = $this->dna2old->get('url');
        if ($id) {
            $url = $dna2url . "frontcustom/284/proyecto_fondyf_preA.php?id=$id&idwf=$idwf&case=$idcase&token=$token";
        } else {
            show_error('El Caso no tiene id de proyecto');
        }

        $url = $this->bpm->gateway($url);
        redirect($url);
    }

    function fix_data($case = null) {
        $debug = false;
        $this->load->model('bpm/bpm');
        $resourceId = 'oryx_508C9A17-620B-4A6F-8508-D3D14DAB6DA2';
        $filter = ($case) ? array('idwf' => 'fondyfpp', 'id' => $case) : array('idwf' => 'fondyfpp');
        $rs = $this->bpm->get_cases_byFilter($filter);
        foreach ($rs as $case) {
            if ($debug)
                var_dump($case['id']);
            $token = $this->bpm->consolidate_data('fondyfpp', $case['id'], $resourceId);
        }
    }

    function Landing() {
        $this->Add_group();
        redirect($this->module_url);
    }

    function Add_group() {
        $user = $this->user->get_user($this->idu);
        if (!$this->user->isAdmin($user)) {
            $this->load->model('user/group');
            $group_add = $this->group->get_byname('FonDyF/EMPRESARIO');
            $update ['idu'] = $this->idu;
            $update ['group'] = $user->group;
            array_push($update ['group'], (int) $group_add ['idgroup']);
            $update ['group'] = array_unique($update ['group']);
            $this->user->update($update);
        }
    }

    function asignar_evaluador($idwf, $idcase, $tokenId) {
        $this->load->library('parser');
        $this->load->model('user/group');
        $this->load->model('bpm/bpm');
        $case = $this->bpm->get_case($idcase, $idwf);
        $renderData = $this->bpm->load_case_data($case, $idwf);
        //----tomo evaluador del caso
        $evaluador = $renderData['Proyectos_fondyf']['8668'][0];
        //----token que hay que finalizar
        $src_resourceId = 'oryx_1C122FC6-1C7F-425A-A0A2-E9EA1892177E';
        // ---Token de pp asignado
        $lane_resourceId = 'oryx_295810F2-8C34-4D03-80F8-7B5C371381B8';

        $url = $this->base_url . "bpm/engine/assign/model/$idwf/$idcase/$src_resourceId/$lane_resourceId/$evaluador";

        redirect($url);
    }

    function info($tipo,$idcase) {
        $idwf='fondyfpp';
        $this->load->model('bpm/bpm');
        $this->load->library('parser');
        $this->load->library('bpm/ui');
        $renderData = array();
        $renderData ['base_url'] = $this->base_url;
        // ---prepare UI
        $renderData ['js'] = array(
            $this->base_url . 'bpm/assets/jscript/modal_window.js' => 'Modal Window Generic JS'
        );
        // ---prepare globals 4 js
        $renderData ['global_js'] = array(
            'base_url' => $this->base_url,
            'module_url' => $this->base_url . 'bpm'
        );
//        $this->bpm->debug['load_case_data'] = true;
        $user = $this->user->getuser((int) $this->session->userdata('iduser'));
        $case = $this->bpm->get_case($idcase, $idwf);
        $this->user->Initiator = $case['iduser'];
        //---saco título para el resultado
        $mywf = $this->bpm->load($idwf);
        $wf = $this->bpm->bindArrayToObject($mywf ['data']);
        //---tomo el template de la tarea
        //$shape = $this->bpm->get_shape($resourceId, $wf);

        $data = $this->bpm->load_case_data($case, $idwf);
        $data['user'] = (array) $user;
        
        //$resources = $this->bpm->get_resources($shape, $wf, $case);
        //---if has no messageref and noone is assigned then
        //---fire a message to lane or self         
//            if (!count($resources['assign']) and !$shape->properties->messageref) {
//                $lane = $this->bpm->find_parent($shape, 'Lane', $wf);
//                //---try to get resources from lane
//                if ($lane) {
//                    $resources = $this->bpm->get_resources($lane, $wf);
//                }
//                //---if can't get resources from lane then assign it self as destinatary
//                if (!count($resources['assign']))
//                    $resources['assign'][] = $this->user->Initiator;
//            }
        //---process inbox--------------
        
        $renderData['name'] ='Ingresar Proyecto';
        $renderData['text'] = '';
        $renderData['text'] .= '<hr/>';
//        $renderData['text'] .=nl2br();
        $this->ui->compose('bpm/modal_msg_little', 'bpm/bootstrap.ui.php', $renderData);
    }

    function set_evaluador($idwf, $idcase, $tokenId) {
        $this->load->library('parser');
        $this->load->model('user/group');
        $this->load->model('bpm/bpm');
        $this->load->library('bpm/ui');

        $group_name = 'FonDyF/EVALUADOR TÉCNICO';
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

        $renderData ['title'] = "FonDyF::Assignar Evaluador";
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

        $this->ui->compose('fondyf/get_user', 'bpm/bootstrap.ui.php', $renderData);
    }

    function show_msgs($idwf, $idcase) {

        $filter = array(
            'idwf' => $idwf,
            'case' => $idcase,
        );
        $cdata = array();
        $cdata['title'] = "Notificaciones: ";
        echo Modules::run('inbox/show_msgs_by_filter', $filter, $cdata);
    }

}

/* End of file fondyf */
    /* Location: ./system/application/controllers/welcome.php */    