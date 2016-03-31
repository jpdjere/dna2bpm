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
class Ga extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('user');
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


    function Gen_url($url = null, $size = '9', $level = 'H') {
        if ($url) {
            $url_gen = base64_decode(urldecode($url));
        }

        if ($this->input->post('url')) {
            $url_gen = $this->input->post('url');
            $size = ($this->input->post('size')) ? $this->input->post('size') : 9;
            $level = ($this->input->post('level')) ? $this->input->post('level') : 'H';
        }

        $this->gen($url_gen, $size, $level);
        //echo "<img src='".$this->module_url."gen_url/".base64_encode($url_gen)."' width='100' height='100'/>";
        //echo base64_encode($url_gen);
    }

    /*
     * Index
     */

    function Index() {
    }


    function Gen($data, $size = '9', $level = 'H') {
        $config['cachedir'] = 'application/modules/qr/cache/';
        if (!is_writable($config['cachedir'])) {
            show_error($config['cachedir'] . ' is not writable');
        }
        $config['errorlog'] = 'application/modules/qr/log/';
        if (!is_writable($config['errorlog'])) {
            show_error($config['errorlog'] . ' is not writable');
        }
        $this->load->library('ciqrcode', $config);
        $params['data'] = $data;
        $params['level'] = $level;
        $params['size'] = $size;
        header("Content-Type: image/png");
        $this->ciqrcode->generate($params);
    }

}