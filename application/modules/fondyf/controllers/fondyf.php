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
        $template='fondyf/listar_proyectos';
        $filter = array(
            'idwf' => 'fondyfpp',
            'resourceId' => 'oryx_B5BD09EE-57CF-41BC-A5D5-FAA1410804A5',
            
        );
        //-----busco en el cuit
        $filter['$or'][]=array('data.1695' => array('$regex' => new MongoRegex('/' . $this->input->post('query') . '/i')));
        //-----busco en el nombre empresa
        $filter['$or'][]=array('data.1693' => array('$regex' => new MongoRegex('/' . $this->input->post('query') . '/i')));
        //-----busco en el nro proyecto
        $filter['$or'][]=array('data.8339' => array('$regex' => new MongoRegex('/' . $this->input->post('query') . '/i')));
        //echo json_encode($filter) . '<br>';
        $tokens = $this->bpm->get_tokens_byFilter($filter,array('case', 'data','checkdate'),  array('checkdate'=>false));
        
        $data['empresas'] = array_map(function ($token) {
         $url='../dna2/RenderView/printvista.php?idvista=3555&idap=284&id='.$token['data']['id'];
            return array(
         '_d'=>$token['_id'],
         'case'=>$token['case'],
         'nombre'=>$token['data']['1693'],
         'cuit'=>$token['data']['1695'],
         'Nro'=>(isset($token['data']['8339'])) ? $token['data']['8339']:'',
         'fechaent'=>date('d/m/Y',  strtotime($token['checkdate'])),
         'link_open'=>$this->bpm->gateway($url),
                
                    );
        }, $tokens);
        $this->parser->parse($template,$data);
    }

}

/* End of file fondyf */
/* Location: ./system/application/controllers/welcome.php */