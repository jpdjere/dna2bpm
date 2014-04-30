<?php

/**
 * Description of pdf
 *
 * @author juanb
 * @date   Jan 28, 2014
 * 
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Plantilla extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('parser');
        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
    }

    /*
     * Main function if no other invoked
     */

    function pdf() {
        $cpData = array();
        $cpData['base_url'] = $this->base_url;
        $cpData['module_url'] = $this->module_url;
        $cpData['title'] = 'PDF';
        $this->load->library('pdf');
        $this->pdf->parse('pdf/plantilla_institucional', $cpData);
        $this->pdf->render();
        $this->pdf->stream("recibo_sueldo.pdf");
    }

    function html($file = null) {
        $cpData = array();
        $cpData['base_url'] = $this->base_url;
        $cpData['module_url'] = $this->module_url;
        $cpData['title'] = 'HTML';

        $this->parser->parse('pdf/plantilla_institucional', $cpData);
    }

}
