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
 * 'http://www.accionpyme.mecon.gob.ar/dna2/XML-Import/SGR_socios/printVista.php?file=' . base64_encode($file['filename']), '
 * http://www.accionpyme.mecon.gob.ar/dna2/sgr/XML-Import/SGR_socios/?filename=Q0FQSVRBTCBTT0NJQUwgLSBBQ0lOREFSIFBZTUVTIFMuRy5SLiAtIDIwMTMtMDEtMTQgMDM6MzI6MDMueGxz
 */

class dna2_asset extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('user/user');
        // IDU : Chequeo de sesion
        $this->idu = (float) $this->session->userdata('iduser');

        /* bypass session */
        session_start();
        $_SESSION['idu'] = $this->idu;

        
        
        
        $actual_link = 'http://' . $_SERVER[HTTP_HOST] . '/dna2/' . implode('/', $this->uri->segments);
        $actual_link = str_replace("sgr/dna2_asset/", "", $actual_link);
        $actual_link = str_replace("printVista.php/", "printVista.php?file=", $actual_link);
        
        header('Location: ' . $actual_link);

        exit;
    }

}
