<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login extends MX_Controller {

    public function __construct() {
        parent::__construct();
        //---base variables
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
        //----load parser
        $this->load->library('parser');
        $this->load->config('config');
    }

    function Nouser() {
        $msg = $this->session->userdata('msg');
        $this->lang->load('login', $this->config->item('language'));
        //---add language data
        $data = $this->lang->language;
        $data['authUrl'] = base_url() . 'user/authenticate';
        $data['base_url'] = base_url();
        $data['show_warn'] = $this->config->item('show_warn');
        $data['theme'] = $this->config->item('theme');
        $data['plugins'] = implode(',', $this->config->item('user_plugin'));
        //----load login again with msg 
        $this->parser->parse('user/login', $data);
    }

    function Index() {
        $msg = $this->session->userdata('msg');
        //----LOAD LANGUAGE
        $this->lang->load('login', $this->config->item('language'));
        //---add language data
        $cpData['lang'] = $this->lang->language;

        $cpData['title'] = 'LogIn Form';
        $cpData['authUrl'] = base_url() . 'user/authenticate';
        $cpData['base_url'] = $this->base_url;
        $cpData['module_url'] = $this->module_url;
        $cpData['theme'] = $this->config->item('theme');
        $cpData['plugins'] = implode(',', $this->config->item('user_plugin'));
        //----NO USER

        if ($msg == 'nouser') {
            $cpData['msgcode'] = $this->lang->line('nousr');
        }
        //----USER DOESN'T HAS PROPPER LEVELS

        if ($msg == 'nolevel') {
            $cpData['msgcode'] = $this->lang->line('nolevel') . "<br>" . $this->session->userdata('redir');
        }

        //----USER has to be logged first
        if ($msg == 'hastolog') {
            $cpData['msgcode'] = $this->lang->line('hastolog') . "<br>" . $this->session->userdata('redir');
        }

        $this->session->set_userdata('msg', $msg);
        //---build UI 
        //---define files to viewport
        $cpData['css'] = array(
            $this->module_url . "assets/css/login.css" => 'Login Specific',
        );
        $cpData['js'] = array(
                //$this->module_url . "assets/jscript/login.js" => 'Login',
        );
        //---
        $cpData['global_js'] = array(
            'base_url' => $this->base_url,
            'module_url' => $this->module_url,
            'show_warn' => $this->config->item('show_warn'),
            'msg' => $msg,
            'msgcode' => (isset($cpData['msgcode'])) ? $cpData['msgcode'] : '',
            'authUrl' => $this->base_url . 'user/authenticate'
        );
        $cpData['show_warn'] = ($this->config->item('show_warn') and $msg <> '');
        //----clear data
        $this->session->unset_userdata('msg');
        //$this->ui->makeui('user/ext.ui.php', $cpData);
        //$this->parser->parse('user/login', $cpData);
<<<<<<< HEAD
        //var_dump($cpData);
=======
>>>>>>> 3b4f8e4d1de9452963edcc9d6cdaa4805f398eb5
        $this->ui->compose('user/login.bootstrap.php', 'user/bootstrap.ui.php', $cpData);
    }

}

?>
