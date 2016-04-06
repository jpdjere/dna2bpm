<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * ASSETS Controller
 * This file allows you to  access assets from within your modules directory
 * 
 * @author Diego Otero
 * 
 * @version 	1.0 (2012-05-27)
 * http://www.accionpyme.mecon.gob.ar/dna2/XML-Import/SGR_socios/printVista.php?file=' . base64_encode($file['filename']), '
 * http://www.accionpyme.mecon.gob.ar/dna2/sgr/XML-Import/SGR_socios/?filename=Q0FQSVRBTCBTT0NJQUwgLSBBQ0lOREFSIFBZTUVTIFMuRy5SLiAtIDIwMTMtMDEtMTQgMDM6MzI6MDMueGxz
 * http://www.accionpyme.mecon.gob.ar/dna2/XML-Import/SGR_socios?filename=Q0FQSVRBTCUyMFNPQ0lBTCUyMC0lMjBBQ0lOREFSJTIwUFlNRVMlMjBTLkcuUi4lMjAtJTIwMjAxMy0xMS0yMSUyMDA5OjM2OjE1Lnhscw==
 */

class Dna2_asset extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('user/user');
        $this->load->helper('sgr/tools');

        
        /* Additional SGR users */
        $this->load->model('sgr/sgr_model');
        $additional_users = $this->sgr_model->additional_users($this->session->userdata('iduser'));
        $this->idu = (isset($additional_users)) ? $additional_users['sgr_idu'] : $this->session->userdata('iduser');
       

        /* bypass session */
        session_start();
        $_SESSION['idu'] = $this->idu;

        $cript_file = array_pop($this->uri->segments);
        $cript_file = str_replace("%20", " ", $cript_file);

        $actual_link = 'http://' . $_SERVER[HTTP_HOST] . '/dna2/' . implode('/', $this->uri->segments);
        $actual_link = str_replace("sgr/dna2_asset/", "", $actual_link);
        $actual_link = str_replace($cript_file, base64_encode($cript_file), $actual_link);
        header('Location: ' . $actual_link . "/printVista.php?file=" . base64_encode($cript_file));
        exit;
    }

}
