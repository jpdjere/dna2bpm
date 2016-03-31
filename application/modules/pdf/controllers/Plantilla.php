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
        $this->pdf->stream("plantilla.pdf");
    }

    function html($file = null) {
        $cpData = array();
        $cpData['base_url'] = $this->base_url;
        $cpData['module_url'] = $this->module_url;
        $cpData['title'] = 'HTML';

        $this->parser->parse('pdf/plantilla_institucional', $cpData);
    }
    function crear($data=array(),$template='pdf/plantilla_institucional',$path=null,$filename='file.pdf') {
        $cpData = array();
        $cpData['base_url'] = $this->base_url;
        $cpData['module_url'] = $this->module_url;
        $cpData+=$data;
        $this->load->library('pdf');
        $html = $this->parser->parse($template, $cpData, TRUE);
        $this->pdf->load_html($html);
        $this->pdf->render();
        if(is_null($path)){
          // $tmp=FCPATH.'images/temp.pdf';
            $this->pdf->stream('file.pdf');
        }else{
          $this->load->helper('file');
          $pdf =  $this->pdf->output();
          if(!is_dir($path))
            mkdir($path, 0777, true);
          if (!write_file($path.'/'.$filename, $pdf)) {
              die('Can not write to disk: '.$path.'/'.$filename);
              
          }
        }
    }
    
    function test(){
        Modules::run('pdf/plantilla/crear',array(
            'title'=>'título del PDF',
            'body'=>'Mariano PDF Mariano PDF Mariano PDF Mariano PDF Mariano PDF Mariano PDF Mariano PDF ',
            ),
            'pdf/plantilla_institucional',
            'images/user_files',
            'testo.pdf'
            );
        
    }

}
