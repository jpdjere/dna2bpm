<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * qr
 * 
 * This class scans and generates qrcodes
 * 
 * @author Juan Ignacio Borda <juanignacioborda@gmail.com>
 * @date    May 18, 2013
 *  Level 
 * Level L (Low) 	7% of codewords can be restored.
 * Level M (Medium) 	15% of codewords can be restored.
 * Level Q (Quartile)[33] 	25% of codewords can be restored.
 * Level H (High) 	30% of codewords can be restored.
 */
class Qr extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('user');
        $this->user->authorize();
        $this->load->library('parser');
        $this->load->library('ui');
        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . 'qr/';
        //----LOAD LANGUAGE
        $this->idu = (float) $this->session->userdata('iduser');
        //---config
        $this->load->config('config');
    }

    /*
     * Index
     */

    function Index() {
        $this->gen('www.dna2.org');
    }

    function Get_demo() {
        $cpData['base_url'] = $this->base_url;
        $cpData['module_url'] = $this->module_url;
        $cpData['title'] = 'QR Code';
        $cpData['reader_title'] =$cpData['title'];
        $cpData['reader_subtitle'] ='Read QR Codes from any HTML5 enabled device';
        $cpData['css'] = array(
            $this->module_url . "assets/css/qr.css" => 'custom css',
        );
        $cpData['js'] = array(
            $this->module_url . "assets/jscript/html5-qrcode.min.js" => 'HTML5 qrcode',
            $this->module_url . "assets/jscript/jquery.animate-colors-min.js" => 'Color Animation',
            $this->module_url . "assets/jscript/qr.js" => 'Main functions',
        );
        $this->ui->compose('readqr','bootstrap.ui.php',$cpData);
    }

    function Gen($data, $size = '9', $level = 'H') {
        $config['cachedir'] = 'application/modules/qr/cache/';
        $config['errorlog'] = 'application/modules/qr/log/';        
        $this->load->library('ciqrcode',$config);
        $params['data'] = $data;
        $params['level'] = $level;
        $params['size'] = $size;
        header("Content-Type: image/png");
        $this->ciqrcode->generate($params);
    }

}