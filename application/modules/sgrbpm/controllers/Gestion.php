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
class Gestion extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('menu/menu_model');
        $this->load->model('bpm/bpm');
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
        $this->gestion();
    }

    //==========
    function gestion() {
        $this->user->authorize();
        Modules::run('dashboard/dashboard', 'sgrbpm/json/sgr_gestion.json');
    }
    
    
    function demo1() {
        $this->user->authorize();
        echo "Widget de prueba Gestion";
    }  

    function demo2() {
        $this->user->authorize();
        echo "Un vinito para Fojo Gestion";
    }  
    
    
    
}

/* End of file fondyf */
    /* Location: ./system/application/controllers/welcome.php */    
