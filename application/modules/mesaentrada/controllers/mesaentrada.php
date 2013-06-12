<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * mesaentrada
 * 
 * Description of the class
 * 
 * @author Juan Ignacio Borda <juanignacioborda@gmail.com>
 * @date    Jun 12, 2013
 */
class Mesaentrada extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('user');
        $this->load->model('entrada');
        $this->user->authorize();
        $this->load->library('parser');
        $this->load->library('ui');
        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . 'mesaentrada/';
        //----LOAD LANGUAGE
        $this->idu = (float) $this->session->userdata('iduser');
        //---config
        $this->load->config('config');
        //---QR
        $this->load->module('qr');
    }

    /*
     * Presentamos menu de acciones: info Checkin
     */

    function Index() {
        $cpData['base_url'] = $this->base_url;
        $cpData['module_url'] = $this->module_url;
        $cpData['module_url_encoded'] = $this->qr->encode($this->module_url);
        $cpData['title'] = 'Mesa de Entradas Digital';
        $cpData['css'] = array(
            $this->module_url . 'assets/css/mesaentrada.css' => "Mesa Entrada css",
        );
        $this->ui->compose('index', 'bootstrap.ui.php', $cpData);
    }

    /*
     * Esta funcion da información sobre el movimiento del Expediente / Código
     */

    function Query() {
        $cpData['base_url'] = $this->base_url;
        $cpData['module_url'] = $this->module_url;
        $cpData['title'] = 'QR Code';
        $cpData['reader_title'] = $cpData['title'];
        $cpData['reader_subtitle'] = 'Read QR Codes from any HTML5 enabled device';
        $cpData['css'] = array(
            $this->base_url . "mesaentrada/assets/css/mesaentrada.css" => 'custom css',
        );
        $cpData['js'] = array(
            $this->base_url . "qr/assets/jscript/html5-qrcode.min.js" => 'HTML5 qrcode',
            $this->base_url . "qr/assets/jscript/jquery.animate-colors-min.js" => 'Color Animation',
            $this->base_url . "mesaentrada/assets/jscript/qr.js" => 'Main functions',
        );

        $cpData['global_js'] = array(
            'base_url' => $this->base_url,
            'module_url' => $this->module_url,
            'redir' => $this->module_url . 'info'
        );
        $this->ui->compose('query', 'bootstrap.ui.php', $cpData);
    }

    function gencode() {
        if ($this->input->post('code')) {
            $code = $this->input->post('type') . '/' . $this->input->post('code');
            $data = $this->module_url . 'info/' . $code;
            $this->qr->Gen($data, '9', 'L');
        }
    }

    function Info($type = null, $code = null) {
        $cpData['base_url'] = $this->base_url;
        $cpData['module_url'] = $this->module_url;
        $cpData['module_url_encoded'] = $this->qr->encode($this->module_url);
        $cpData['title'] = ' <i class="icon-qrcode"></i> Mesa de Entradas Digital:: Info Expediente';

        if ($this->input->post('data')) {
            $parts = explode('/', str_replace($this->module_url, '', $this->input->post('data')));
            $type = $parts[1];
            $code = implode('/', array_slice($parts, 2));
            if ($type)
                $cpData['type'] = $type;
            if ($code)
                $cpData['code'] = $code;
            $cpData['title'] = '';
            $this->parser->parse('info', $cpData);
        } else {
            $parts = explode('/', $this->uri->uri_string());
            $code = implode('/', array_slice($parts, 3));
            if ($type)
                $cpData['type'] = $type;
            if ($code)
                $cpData['code'] = $code;
            $this->ui->compose('info', 'bootstrap.ui.php', $cpData, false, false);
        }
    }

    function prepare($arr){
        $rtnArr=array();
        $cant=count($arr);
        for($i=0;$i<$cant;$i++){
           $val=$arr[$i];
           ///-----calculate days
           if(isset($arr[$i+1])){
             $date1=new DateTime($arr[$i+1]['date']);
             $date2=new DateTime($arr[$i]['date']);
             
           } else {
             $date1=new DateTime();
             $date2=new DateTime($arr[$i]['date']);
           }
           $val['days']= $datetime1->diff($datetime2);
           $val['user_data']=$this->user->get_user_array($val['user']);
            $rtnArr[]=$val;
        }
        return $rtnArr;
    }
    
    /*
     * Esta funcion realiza el checkin para el usuario actual o user
     */

    function claim() {
       if ($this->input->post('data')) {
            $parts = explode('/', str_replace($this->module_url, '', $this->input->post('data')));
            $type = $parts[1];
            $code = implode('/', array_slice($parts, 2));
            if ($type)
                $cpData['type'] = $type;
            if ($code)
                $cpData['code'] = $code;
            $this->entrada->claim($type,$code,$this->idu);
            $result=$this->prepare($this->entrada->get($type,$code));
            unset($result['_id']);
            $cpData['result']=$result;
            
            $cpData['title'] = '';
            $this->parser->parse('info', $cpData);
        }
    }

    function Checkin() {
        $cpData['base_url'] = $this->base_url;
        $cpData['module_url'] = $this->module_url;
        $cpData['title'] = 'Check In';
        $cpData['reader_title'] = $cpData['title'];
        $cpData['reader_subtitle'] = 'Read QR Codes from any HTML5 enabled device';
        $cpData['css'] = array(
            $this->base_url . "mesaentrada/assets/css/mesaentrada.css" => 'custom css',
        );
        $cpData['js'] = array(
            $this->base_url . "qr/assets/jscript/html5-qrcode.min.js" => 'HTML5 qrcode',
            $this->base_url . "qr/assets/jscript/jquery.animate-colors-min.js" => 'Color Animation',
            $this->base_url . "mesaentrada/assets/jscript/checkin.js" => 'Main functions',
        );

        $cpData['global_js'] = array(
            'base_url' => $this->base_url,
            'module_url' => $this->module_url,
            'redir' => $this->module_url . 'claim',
            'idu' => $this->idu
        );
        $this->ui->compose('query', 'bootstrap.ui.php', $cpData);
    }

}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */