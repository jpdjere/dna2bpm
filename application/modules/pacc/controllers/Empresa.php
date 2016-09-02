<?php

/**
 * Description of pacc
 *
 * @author juanignacioborda@gmail.com
 * @date    2015-09-09
 *
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Empresa extends MX_Controller {

    function __construct() {
        parent::__construct();

        //---base variables
        $this->load->model('model_evaluadores_proyectos');
        $this->base_url = base_url();
        //$this->idu = (int) $this->session->userdata('iduser');
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
    }
    /*
     * Main function if no other invoked
     */
    function Index(){
        $this->dashboard();
    }

    /**
     * Dashboard para empresas
     */
    function dashboard($debug=false){
        $this->Add_group();
        Modules::run('dashboard/dashboard', 'pacc/json/dashboard_empresa.json',$debug);
    }
    function Add_group() {
        $user =$this->user->get_user($this->user->idu);
        if (!$this->user->isAdmin($user)) {
            $user=$user;
            $group_add = $this->group->get_byname('PACC/PACC 1.1 /EMPRESA');
            array_push($user->group, (int) $group_add ['idgroup']);
            $user->group = array_unique($user->group);
            $this->user->save($user);
        }
    }

    function tile_solicitud_PACC11() {
        $data ['number'] = 'Proyecto';
        $data ['title'] = 'PACC Empresas';
        $data ['icon'] = 'ion-document-text';
        $data ['more_info_text'] = 'Comenzar';
        $data ['more_info_link'] = $this->base_url . 'bpm/engine/newcase/model/pacc1PDE';
        echo Modules::run('dashboard/tile', 'dashboard/tiles/tile-green', $data);
    }

    function widget_2doMe2($chunk = 1, $pagesize = 5) {
        //$data['lang']=$this->lang->language;
        $this->load->model('bpm/bpm');
        $query = array(
            'assign' => $this->user->idu,
            'status' => 'user'
        );


        $tasks = $this->bpm->get_tasks_byFilter($query, array(), array('checkdate' => 'desc'));
        //$data=$this->prepare_tasks($tasks, $chunk, $pagesize);
        $data = Modules::run('bpm/bpmui/prepare_tasks', $tasks, $chunk, $pagesize);
        //var_dump();exit;
        if (isset($data['mytasks'])) {
            foreach ($data['mytasks'] as $k => $mytask) {
                $mycase = $this->bpm->get_case($mytask['case']);
                $data['mytasks'][$k]['extra_data']['ip'] = false;
                if (isset($mycase['data']['Empresas']['query']['id'])) {
                    $empresaID = $mycase['data']['Empresas']['query']['id'];
                    $empresa = $this->bpm->get_data('container.empresas', array('id' => $empresaID));
                    $data['mytasks'][$k]['extra_data']['empresa'] = $empresa[0]['1693'];
                }
                if (isset($mycase['data']['Proyectos_fondyf']['query']['id'])) {
                    
                    $proyectoID = $mycase['data']['Proyectos_fondyf']['query']['id'];
                    $proyecto = $this->bpm->get_data('container.proyectos_fondyf', array('id' => $proyectoID));
                    $data['mytasks'][$k]['extra_data']['ip'] = $proyecto[0]['8339'];
                    
                    $url = (isset($mycase['data'] ['Proyectos_fondyf']['query']['id'])) ? '../dna2/frontcustom/284/list_docs_fondyf_eval.php?id=' . $mycase['data'] ['Proyectos_fondyf']['query'] ['id'] : '#';
                    $data['mytasks'][$k]['link_open'] = $this->bpm->gateway($url);

                }
            }
        } else {
            $data['mytasks'] = array();
        }

        $data['title'] = $this->lang->line('Tasks') . ' ' . $this->lang->line('Pending');

        $data['more_info_link'] = $this->base_url . 'bpm/';
        $data['widget_url'] = base_url() . $this->router->fetch_module() . '/' . $this->router->class . '/' . __FUNCTION__;
      echo $this->parser->parse('pacc/widgets/2doMe2', $data, true, true);
    }







    }



