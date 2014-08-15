<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of menu_extra
 *
 * @author juanb
 */
class Menu_extra {

    var $CI;

    public function __construct($params = array()) {
// Set the super object to a local variable for use throughout the class
        $this->CI = & get_instance();
                //---base variables
        $this->base_url = base_url();
        $this->idu = (int) $this->CI->session->userdata('iduser');
        // Register Scripts
    }

    public function get() {
        $this_CI=& get_instance();
        $this_CI->load->model('app');
        $cpData = array();
        $user = $this_CI->user->get_user($this->idu);
        $apps = $this_CI->app->get_apps();

        if ($apps->count()) {
            //----check if the user has access to thi app
            foreach ($apps as $thisApp) {
                $authorized = false;
                if (isset($thisApp['groups'])) {
                    foreach ($thisApp['groups'] as $idgroup) {
                        if (in_array($idgroup, $user->group)) {
                            $authorized = true;
                            break;
                        }
                    }
                }
                //if ($this->user->has('root/modules/application/' . $thisApp['idapp']) or $this->user->isAdmin($user)) {
                if ($authorized or $this_CI->user->isAdmin($user)) {
                    $cpData['apps'][] = array(
                        'icon' => isset($thisApp['icon']) ? $thisApp['icon'] : 'icon-list-alt',
                        'name' => isset($thisApp['title']) ? $thisApp['title'] : $thisApp['idapp'] . '(???)',
                        'link' => $this->base_url . 'dna2/application/' . $thisApp['idapp'],
                        'target' => '_self'
                    );
                }
            }
            $retStr=$this_CI->parser->parse('dashboard/menu_extra_dna2',$cpData,true,true);
            return $retStr;
        }
    }

}
