<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * empresa
 * 
 * Description of the class empresa
 * 
 * @author Juan Ignacio Borda <juanignacioborda@gmail.com>
 * @date   Jul 31, 2014
 */
class Empresas extends MX_Controller {

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
        Modules::run('dashboard/dashboard', 'empresas/json/empresa.json');
    }
    function tile_cargar_empresa() {
        $data['number'] = 'Empresa';
        $data['title'] = 'Cargar una empresa';
        $data['icon'] = 'ion-document-text';
        $data['more_info_text'] = 'Comenzar';
        $data['more_info_link'] = $this->base_url . 'bpm/engine/newcase/model/empresa_carga';
        echo Modules::run('dashboard/tile', 'dashboard/tiles/tile-green', $data);
    }

    function tile_buscar() {
        $data = array();
        return $this->parser->parse('empresa/buscar_empresa', $data, true);
    }

    function buscar($type = null) {
        $this->load->model('bpm/bpm');
        $this->load->library('parser');
        $template='empresa/listar_proyectos';
        $filter = array(
            'idwf' => 'empresapp',
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

    function setup(){
       echo Modules::run('bpm/kpi/import_kpi','empresas');
    }
}

/* End of file empresa */
/* Location: ./system/application/controllers/welcome.php */