<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * fondyf
 * 
 * Description of the class fondyf
 * 
 * @author Juan Ignacio Borda <juanignacioborda@gmail.com>
 * @date   Jul 18, 2014
 */
class Fondyf extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('menu/menu_model');
        $this->user->isloggedin();
        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
        $this->load->config('fondyf/config');
        //----LOAD LANGUAGE
        $this->lang->load('library', $this->config->item('language'));
        $this->idu = (int) $this->session->userdata('iduser');
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
        //----portable indicators are stored as json files
        $kpi = json_decode($this->load->view("fondyf/kpi/kpi_proyectos.json", '', true), true);
        echo Modules::run('bpm/kpi/tile_kpi', $kpi);
    }

    function tile_solicitud() {
        $data['number'] = 'Solicitud';
        $data['title'] = 'Crea una nueva solicitud';
        $data['icon'] = 'ion-document-text';
        $data['more_info_text'] = 'Comenzar';
        $data['more_info_link'] = $this->base_url . 'bpm/engine/newcase/model/fondyfpp';
        echo Modules::run('dashboard/tile', 'dashboard/tiles/tile-green', $data);
    }
  

    function tile_comite() {
        $this->user->authorize();
        $this->load->model('bpm/bpm');
        $this->load->model('dna2/dna2old');
        $dna2url=$this->dna2old->get('url');
        //http://www.accionpyme.mecon.gob.ar/dna2/frontcustom/286/sol_ministro_2014.R.php
        $url=$dna2url."frontcustom/286/sol_ministro_2014";
        $url=$this->bpm->gateway($url);
        $data['number'] = 'Comité';
        $data['title'] = 'Enviar a Comité';
        $data['icon'] = 'ion-archive';
        $data['more_info_text'] = 'Descargar';
        $data['more_info_link'] = $url;
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
            'resourceId' => 'oryx_B5BD09EE-57CF-41BC-A5D5-FAA1410804A5',
        );
        $data['querystring'] = $this->input->post('query');
        //-----busco en el cuit
        $filter['$or'][] = array('data.1695' => array('$regex' => new MongoRegex('/' . $this->input->post('query') . '/i')));
        //-----busco en el nombre empresa
        $filter['$or'][] = array('data.1693' => array('$regex' => new MongoRegex('/' . $this->input->post('query') . '/i')));
        //-----busco en el nro proyecto
        $filter['$or'][] = array('data.8339' => array('$regex' => new MongoRegex('/' . $this->input->post('query') . '/i')));
        //echo json_encode($filter) . '<br>';
        $tokens = $this->bpm->get_tokens_byFilter($filter, array('case', 'data', 'checkdate'), array('checkdate' => false));

        $data['empresas'] = array_map(function ($token) {
            $url = '../dna2/RenderView/printvista.php?idvista=3597&idap=286&id=' . $token['data']['id'];
            return array(
                '_d' => $token['_id'],
                'case' => $token['case'],
                'nombre' => $token['data']['1693'],
                'cuit' => $token['data']['1695'],
                'Nro' => (isset($token['data']['8339'])) ? $token['data']['8339'] : '',
                'fechaent' => date('d/m/Y', strtotime($token['checkdate'])),
                'link_open' => $this->bpm->gateway($url),
            );
        }, $tokens);
        $data['count'] = count($tokens);
        $this->parser->parse($template, $data, false, true);
    }

    function setup() {
        $this->user->authorize();
        echo Modules::run('bpm/kpi/import_kpi', 'fondyf');
    }

    function ministatus_pp() {
        $this->user->authorize();
        $state = Modules::run('bpm/manager/mini_status', 'fondyfpp', 'array');
        $state = array_filter($state, function($task) {
            return $task['type'] == 'Task';
        });
        //---las aplano un poco
        foreach ($state as $task) {
            $task['user'] = (isset($task['status']['user'])) ? $task['status']['user'] : 0;
            $task['finished'] = (isset($task['status']['finished'])) ? $task['status']['finished'] : 0;
            $wfData['mini'][] = $task;
        }
        $wfData['base_url'] = base_url();
        $wf = $this->bpm->load('fondyfpp');
        $wfData+=$wf['data']['properties'];
        $wfData['name'] = 'Mini Status: ' . $wfData['name'];
        return $this->parser->parse('fondyf/ministatus_pp', $wfData, true, true);
    }

    function ver_ficha($idwf,$idcase,$token,$id) {
        $this->user->authorize();
        $this->load->model('bpm/bpm');
        $this->load->model('dna2/dna2old');
        $dna2url=$this->dna2old->get('url');
        $url=$dna2url."RenderEdit/editnew.php?idvista=3560&origen=V&idap=286&id=$id&idwf=$idwf&case=$idcase&token=$token";
        $url=$this->bpm->gateway($url);
        redirect($url);
    }
    function Landing(){
        $this->Add_group();
        redirect($this->module_url);
    }
    function Add_group(){
        $user=$this->user->get_user($this->idu);
        if(!$this->user->isAdmin($user)){
        $this->load->model('user/group');
        $group_add=$this->group->get_byname('FonDyF/EMPRESARIO');
        $update['idu']=$this->idu;
        $update['group']=$user->group;
        array_push($update['group'],(int)$group_add['idgroup']);
        $update['group']=array_unique($update['group']);
        $this->user->update($update);
        }
    }
}

/* End of file fondyf */
/* Location: ./system/application/controllers/welcome.php */