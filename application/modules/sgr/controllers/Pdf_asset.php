<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * ASSETS Controller
 * This file allows you to  access assets from within your modules directory
 * 
 * @author Diego
 * 
 * @version 	1.0 (2012-05-27)
 * 
 */

class pdf_asset extends CI_Controller {

    function __construct() {
        parent::__construct();


        /* Additional SGR users */
        $this->load->model('sgr/sgr_model');
        $additional_users = $this->sgr_model->additional_users($this->session->userdata('iduser'));
        $this->idu = (isset($additional_users)) ? $additional_users['sgr_idu'] : $this->session->userdata('iduser');

        //$this->user->authorize();
        //---get working directory and map it to your module
        $var = array_shift($this->uri->segments);
        $var = array_shift($this->uri->segments);
        $file = getcwd() . '/anexos_sgr/' . implode('/', $this->uri->segments);
        $file = str_replace("%20", " ", $file);
        //----get path parts form extension
        $path_parts = pathinfo($file);
        //---set the type for the headers
        $file_type = strtolower($path_parts['extension']);
        if (is_file($file)) {
            header('Content-type: application/pdf');
            readfile($file);
        } else {
            echo "file" . $file;
        }
        exit;
    }

}