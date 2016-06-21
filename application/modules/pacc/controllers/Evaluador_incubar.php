<?php

/**
 * Description of pacc
 *
 *
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Evaluador_incubar extends MX_Controller {

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
     * Dashboard para Incubar
     */
    function dashboard($debug=false){                       
        Modules::run('dashboard/dashboard', 'pacc/json/dashboard_evaluador_incubar.json',$debug);
    }

    function tile_buscar() {
        $this->user->authorize();
        $data = array();
        return $this->parser->parse('pacc/buscar_agencia', $data, true);
    }


    function buscar($type = null) {
        $this->user->authorize();
        $this->load->library('parser');
        $template = 'pacc/listar_agencias';
        $filter = array(
            'idwf' => 'INCUBAR',
            'resourceId' =>$this->consolida_resrourceId
        );
        $data ['querystring'] = $this->input->post('query');
        // -----busco en el cuit
        $filter ['$or'] [] = array(
            'data.6069' => array(
                '$regex' => new MongoRegex('/' . $this->input->post('query') . '/i')
            )
        );
        // -----busco en el nombre empresa
        $filter ['$or'] [] = array(
            'data.4896' => array(
                '$regex' => new MongoRegex('/' . $this->input->post('query') . '/i')
            )
        );
        // -----busco en el nro Inscripción
        $filter ['$or'] [] = array(
            'data.9985' => array(
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
            $case = $this->bpm->get_case($token ['case'], 'INCUBAR');
            $INCUBAR = $this->bpm->get_case($token ['case'], 'INCUBAR');
            $data = $this->bpm->load_case_data($case);


            $url = (isset($data ['Agencias']['id'])) ? '../dna2/frontcustom/231/list_docs_crefis_eval.php?id=' . $data ['Agencias'] ['id'] : '#';
            $url_msg = (isset($token ['case'])) ? $this->base_url . 'crefis/show_msgs/' . $token ['case'] : null;
            /* crefis/COORDINADOR (134) */
            $hist=$this->bpm->get_token_history('INCUBAR',$token['case']);
            foreach($hist as $t) $keys[$t['resourceId']]=$t['status'];
            $keys = array_keys($case['token_status']);
            $url_clone = ''; 
            if ((in_array(584, $this->id_group) or in_array(586, $this->id_group) or $this->user->isAdmin())
                    and $case['status'] == 'open' and in_array($data ['Agencias'] ['9498'][0], array('070', 70)) //---checkeo que esté en alguno de esos estados
                    ){ 
                $model = 'INCUBAR';
                $estado = $data ['Agencias'] ['4970'][0];
				$idResource = 'oryx_2B2D66FE-215C-4D64-8C5F-7BF0BE353B00'; //de arranque del play????????????
/*
                switch($estado){
                    case 40:
                        break;
                    case 60:
                        $idResource = 'oryx_235D6339-A2ED-476B-A570-3233C86548EE';
                        break;
                    case 48:
                    case 59:                    
                        $idResource = 'oryx_FDD40364-9DB8-4090-B569-7D795E380F18';
                        break;
                }
*/

                $url_clone = $this->base_url . 'bpm/engine/run/model/' . $model. '/' .$token['case'] . '/'.$idResource;}
			else{
				$url_clone = null;
			}
                
            /*$url_clone = (
                    (in_array(584, $this->id_group) or in_array(586, $this->id_group) or $this->user->isAdmin()) and $case['status'] == 'open' and in_array('oryx_05695DC8-1842-49D1-8327-1DAB8C164D35', $keys) //---está finalizado pero por esta figura
                    and in_array($data ['Agencias'] ['4970'][0], array(30, 40, 60)) //---checkeo que esté en alguno de esos estados
                    ) ? $this->base_url . 'bpm/engine/run/model/' . $model. '' .$token['case'] . '/oryx_69057B4E-A899-40F8-8A27-7D8C2A5100CE':null;*/
            //---link para cancelar solo para coordinador
            $url_cancelar_pp = ((in_array(134, $this->id_group) or $this->user->isAdmin()) and $case['status'] == 'open') ? $this->base_url . 'crefis/cancelar_pp/' . $token ['case'] : null;
            $url_cancelar_pde = (
                    (in_array(134, $this->id_group) or $this->user->isAdmin()) and in_array('oryx_3346C091-4A4D-4DCD-8DEC-B23C5FE7F80C', $keys) //---está finalizado pero por esta figura
                    and $case['status'] == 'closed'
                    ) ? $this->base_url . 'crefis/cancelar_pde/' . $token ['case'] : null;
            //---link para reevaluar solo para coordinador
            $url_reevaluar_pp = ((in_array(134, $this->id_group) or $this->user->isAdmin()) and $case['status'] == 'open') ? $this->base_url . 'crefis/reevaluar_pp/' . $token ['case'] : null;
            $url_reevaluar_pde = (
                    (in_array(134, $this->id_group) or $this->user->isAdmin()) and in_array('oryx_3346C091-4A4D-4DCD-8DEC-B23C5FE7F80C', $keys) //---está finalizado pero por esta figura
                    and $case['status'] == 'closed'
                    ) ? $this->base_url . 'crefis/reevaluar_pde/' . $token ['case'] : null;
            //---url para checkear

            $url_bpm = '';
            if (in_array(145, $this->id_group) or in_array(1001, $this->id_group) or $this->user->isAdmin()) {
                $model = ($INCUBAR) ? 'INCUBAR' : 'INCUBAR';
                $url_bpm = $this->base_url . 'bpm/engine/run/model/' . $model . '/' . $token ['case'];
            }

            /* STATUS */
            $status = "N/A";
            if (isset($data ['Agencias'] ['9498'])) {
                $this->load->model('app');
                $option = $this->app->get_ops(849);
                $status = $option[$data ['Agencias'] ['9498'][0]];
            }


            return array(
                '_d' => $token ['_id'],
                'case' => $token ['case'],
                'nombre' => (isset($data['Agencias']['4896'])) ? $data['Agencias']['4896'] : 'XXXX',
                'cuit' => (isset($data['Agencias']['6069'])) ? $data['Agencias']['6069'] : 'Error//no está cargado',
                'Nro' => (isset($data ['Agencias']['9985'])) ? $data ['Agencias'] ['9985'] : 'N/A',
                'estado' => $status,
                'fechaent' => date('d/m/Y', strtotime($token ['checkdate'])),
                'link_open' => $this->bpm->gateway($url),
                'link_msg' => $url_msg,
                'url_clone' => $url_clone,
                'url_bpm' => $url_bpm,
                'url_cancelar_pp' => $url_cancelar_pp,
                'url_cancelar_pde' => $url_cancelar_pde,
                'url_reevaluar_pp' => $url_reevaluar_pp,
                'url_reevaluar_pde' => $url_reevaluar_pde,

            );
        }, $tokens);
        $data ['count'] = count($tokens);
        $data['base_url'] = $this->base_url;
        // var_dump($keys,$data);exit;


        $this->parser->parse($template, $data, false, true);
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
                if (isset($mycase['data']['Agencias']['query']['id'])) {
                    $empresaID = $mycase['data']['Agencias']['query']['id'];
                    $empresa = $this->bpm->get_data('container.agencias', array('id' => $empresaID));
                    $data['mytasks'][$k]['extra_data']['empresa'] = $empresa[0]['4896'];
                }
                /*if (isset($mycase['data']['Proyectos_fondyf']['query']['id'])) {
                    
                    $proyectoID = $mycase['data']['Proyectos_fondyf']['query']['id'];
                    $proyecto = $this->bpm->get_data('container.proyectos_fondyf', array('id' => $proyectoID));
                    $data['mytasks'][$k]['extra_data']['ip'] = $proyecto[0]['8339'];
                    
                    $url = (isset($mycase['data'] ['Proyectos_fondyf']['query']['id'])) ? '../dna2/frontcustom/284/list_docs_fondyf_eval.php?id=' . $mycase['data'] ['Proyectos_fondyf']['query'] ['id'] : '#';
                    $data['mytasks'][$k]['link_open'] = $this->bpm->gateway($url);

                }*/
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



