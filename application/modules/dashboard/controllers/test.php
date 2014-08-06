<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * test
 * 
 * Description of the class
 * 
 * @author Juan Ignacio Borda <juanignacioborda@gmail.com>
 * @date    May 28, 2014
 */
class test extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('user/user');
        $this->idu = (int) $this->session->userdata('iduser');
    }

    function Index() {
        $module = "bpm";
        $controller = 'manager';
        $function = 'show_tasks';

        //$this->load->module($module);
        //$widget=$this->$module->run($controller.'/'.$function);
        $widget = Modules::run($module . '/' . $controller . '/' . $function);
        var_dump($widget);
    }

    function hooks_group($user = null) {
        $user = ($user) ? $user : $this->user->get_user((int) $this->idu);
        if (is_file(FCPATH . APPPATH . "modules/dashboard/views/hooks/groups.json")) {
            $config = json_decode($this->load->view('hooks/groups.json', '', true));
            foreach ($config->hooks as $hook) {
                if (array_intersect($user->group, $hook->group)) {
                    var_dump($this->base_url, $hook->redir);
                    var_dump($this->base_url . $hook->redir);
                }
            }
        }
    }

}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */