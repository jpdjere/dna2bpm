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
        $this->user->authorize();
        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
        //----LOAD LANGUAGE
        $this->lang->load('library', $this->config->item('language'));
        $this->idu = (int) $this->session->userdata('iduser');
    }

    function Index() {
        $this->proyecto();
    }

    function Proyecto() {
        Modules::run('dashboard/dashboard', 'fondyf/json/fondyf_proyectos.json');
    }

    function Evaluador() {
        Modules::run('dashboard/dashboard', 'fondyf/json/fondyf_evaluador.json');
    }

    function Admin() {
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

    function tile_buscar() {
        $data = array();
        return $this->parser->parse('fondyf/buscar_proyecto', $data, true);
    }

    function buscar($type = null) {
        $this->load->model('bpm/bpm');
        $this->load->library('parser');
        $filter = array(
            'idwf' => 'fondyfpp',
            'resourceId' => 'oryx_B5BD09EE-57CF-41BC-A5D5-FAA1410804A5',
            'data.1693' => array('$regex' => new MongoRegex('/' . $this->input->post('query') . '/i'))
        );
        echo json_encode($filter) . '<br>';
        $tokens = $this->bpm->get_tokens_byFilter($filter,array('data','checkdate'),  array('checkdate'=>false));
        $data['empresas'] = array_map(function ($token) {
            return array(
         'nombre'=>$token['data']['1693'],
         'cuit'=>$token['data']['1695'],
         'fechaent'=>date('d/m/Y',  strtotime($token['checkdate'])),
                    );
        }, $tokens);
        $this->parser->parse('fondyf/listar_empresas',$data);
    }

}

/* End of file fondyf */
/* Location: ./system/application/controllers/welcome.php */