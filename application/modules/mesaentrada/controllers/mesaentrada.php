<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * mesaentrada
 * 
 * Description of the class
 * 
 * @author Juan Ignacio Borda <juanignacioborda@gmail.com>
 * @date    Jun 12, 2013
 */
class mesaentrada extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('user');
        $this->user->authorize();
        $this->load->library('parser');
        $this->load->library('ui');
        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . 'mesaentrada/';
        //----LOAD LANGUAGE
        $this->idu = (float) $this->session->userdata('iduser');
        //---config
        $this->load->config('config');
        //---QR
        $this->load->module('qr');
    }

    /*
     * Presentamos menu de acciones: info Checkin
     */

    function Index() {
        $cpData['base_url'] = $this->base_url;
        $cpData['module_url'] = $this->module_url;
        $cpData['module_url_encoded'] = $this->qr->encode($this->module_url);
        $cpData['title'] = 'Mesa de Entradas Digital';
        $this->ui->compose('index', 'bootstrap.ui.php', $cpData);
    }

    /*
     * Esta funcion da información sobre el movimiento del Expediente / Código
     */

    function info($code) {
        
    }

    /*
     * Esta funcion realiza el checkin para el usuario actual o user
     */

    function checkin($code, $user = null) {
        
    }

}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */