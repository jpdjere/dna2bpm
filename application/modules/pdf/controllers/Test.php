<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * test
 * 
 * TEST PDF and composer
 * 
 * @author Juan Ignacio Borda <juanignacioborda@gmail.com>
 * @date    Dec 9, 2013
 */
include(APPPATH.'modules/pdf/vendor/autoload.php') ;

use Dompdf\Dompdf;

class Test extends MX_Controller {

    function __construct() {
        parent::__construct();
    }

    function Index() {
        
        
        
        // instantiate and use the dompdf class
        $dompdf = new Dompdf();
        $dompdf->loadHtml('hello world');
        
        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'landscape');
        
        // Render the HTML as PDF
        $dompdf->render();
        
        // Output the generated PDF to Browser
        $dompdf->stream();
    }
    
}