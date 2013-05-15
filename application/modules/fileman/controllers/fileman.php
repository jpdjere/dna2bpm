<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * fileman
 * 
 * Description of the class
 * 
 * @author Juan Ignacio Borda <juanignacioborda@gmail.com>
 * @date    May 15, 2013
 */
class Fileman extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('parser');
        $this->load->model('user');
        $this->load->library('ui');
        $this->load->model('app');
        $this->user->authorize();
        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . 'user/';
        //----LOAD LANGUAGE
        $this->lang->load('library', $this->config->item('language'));
        $this->idu = (float) $this->session->userdata('iduser');
    }

    /*
     * Index
     */

    function Index() {
        echo "Index";
            $this->ui->compose('profileEdit', 'bootstrap.ui.php', $customData);
    }


}