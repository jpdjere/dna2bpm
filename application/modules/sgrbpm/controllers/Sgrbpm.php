<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * sgrbpm
 *
 * Description of the class sgrbpm
 *
 * @author Juan Ignacio Borda <juanignacioborda@gmail.com>
 *         @date Jul 18, 2014
 */
class Sgrbpm extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('menu/menu_model');
        $this->load->model('bpm/bpm');
        $this->load->model('sgr/sgr_model');
        $this->user->isloggedin();
        // ---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
        $this->load->config('fondyf/config');
        // ----LOAD LANGUAGE
        $this->lang->load('library', $this->config->item('language'));
        $this->idu = (int) $this->session->userdata('iduser');
        $this->load->library('pagination');

        /* GROUP */
        $user = $this->user->get_user($this->idu);

        $this->id_group = ($user->{'group'});
    }

    function Index() {
        $this->sociedades();
    }

    //==========
    function sociedades() {
        $this->user->authorize();
        Modules::run('dashboard/dashboard', 'sgrbpm/json/sgr_sociedades.json');
    }

    function anexos() {
        $this->user->authorize();

        $customData = array();
        $customData['sgr_nombre'] = $this->sgr_nombre;
        $customData['sgr_id'] = $this->sgr_id;
        $customData['sgr_id_encode'] = base64_encode($this->sgr_id);
        $customData['base_url'] = base_url();
        $customData['module_url'] = base_url() . 'sgr/';

        $customData['base_url_dna2'] = 'http://' . $_SERVER['HTTP_HOST'] . '/dna2/';

        $customData['titulo'] = "Dashboard";
        $customData['js'] = array($this->module_url . "assets/jscript/dashboard.js" => 'Dashboard JS', $this->module_url . "assets/jscript/jquery-validate/jquery.validate.min_1.js" => 'Validate');
        $customData['css'] = array($this->module_url . "assets/css/dashboard.css" => 'Dashboard CSS');
        //$customData['layout']="layout.php"; 

        $sections = array();
        $sections['Anexos'] = array();
        $customData['anexo_list'] = $this->AnexosDB('_blank');

        /* FRE SESSION */
        if (isset($this->session->userdata['fre_session']))
            $customData['fre_session'] = $this->session->userdata['fre_session'];

        //$customData['fre_list'] = $this->freDB();

        /* ORGANOS SOCIALES */
        // $social_structure = $this->model_organos_sociales->get_ident();
        $print_file = anchor('sgr/dna2_social_structure_asset/RenderEdit/' . $social_structure, ' <i class="fa fa-print" alt="Organos Sociales"> Organos Sociales </i>', array('target' => '_blank', 'class' => 'btn btn-primary'));
        $list_files = "<li>" . $print_file . "</li>";
        $customData['social_structure'] = $list_files;
        $customData['social_structure'] = ($this->idu == -342725103) ? $list_files : '';

        /* RENDER */
        //$this->render('main_dashboard', $customData);

        return $this->parser->parse('anexos_dashboard', $customData, true, true);
    }

    function anexos_tile() {
        $this->user->authorize();
        return $this->parser->parse('anexos_tile', $customData, true, true);
    }
    
    function reports() {
        $this->user->authorize();
        return $this->parser->parse('reports_dashboard', $customData, true, true);
    }

    function anexos_dna2() {
        $this->user->authorize();
        return $this->parser->parse('anexos_dna2_tile', $customData, true, true);
    }

    function anexos_models_tile() {
        $this->user->authorize();
        return $this->parser->parse('anexos_models_tile', $customData, true, true);
    }
    
    function documentation() {
        $this->user->authorize();
        return $this->parser->parse('documentation_tile', $customData, true, true);
    }
    
    function central() {
        $this->user->authorize();
        return $this->parser->parse('central_tile', $customData, true, true);
    }


    function AnexosDB($target = '_self') {
        $module_url = base_url() . 'sgr/';
        $anexosArr = $this->sgr_model->get_anexos();
        $result = "";
        foreach ($anexosArr as $anexo) {
            /*
             * FILTER 4 FRE
             * FONDOS DE AFECTACIÓN ESPECÍFICOS, no deben tener la opcion de subir los Anexo 6, ni 6.1 ni 6.2.
             */
            $chunk_id = (int) $anexo['id'];
            $limit_chunk_id = (isset($this->session->userdata['fre_session'])) ? 3 : 0;

            if ($chunk_id > $limit_chunk_id)
                $result .= '<li><a target="' . $target . '" href=  "' . $module_url . 'anexo_code/' . $anexo['number'] . '"> ' . $anexo['title'] . ' <strong>[' . $anexo['short'] . ']</strong></a></li>';
        }
        return $result;
    }

}

/* End of file fondyf */
    /* Location: ./system/application/controllers/welcome.php */    
