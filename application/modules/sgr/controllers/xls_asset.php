<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * ASSETS Controller
 * This file allows you to  access assets from within your modules directory
 * 
 * @author Borda Juan Ignacio
 * 
 * @version 	1.0 (2012-05-27)
 * 
 */

class xls_asset extends CI_Controller {

    function __construct() {
        parent::__construct();
        //$this->user->authorize();
        //---get working directory and map it to your module
        $var = array_shift($this->uri->segments);
        $file = getcwd() . '/anexos_sgr/' . implode('/', $this->uri->segments);
        $file = str_replace("%20", " ", $file);
        //----get path parts form extension
        $path_parts = pathinfo( $file);
        //---set the type for the headers
        $file_type=  strtolower($path_parts['extension']);        
        if (is_file($file)) {
            header('Content-type: application/vnd.ms-excel'); 
            readfile($file);
        } else {
            echo "file" .$file;
           
        }
        exit;
    }
}