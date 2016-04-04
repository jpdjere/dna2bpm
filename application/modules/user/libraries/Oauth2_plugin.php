<?php

/*
 * 
 * 
 */

/**
 * This libray load submodules and apply bindings for replaced functions
 * ad_user_plugin
 * ad_group_plugin
 * 
 * @author Juan Ignacio Borda <juanignacioborda@gmail.com>
 */
class oauth2_plugin {

    function __construct() {
        //parent::__construct();
        $ci = & get_instance();
        if ($ci) {
            $ci->user->loginExtra =array(
                array('html'=>$ci->load->view('oauth2/google.php','',true)),
                array('html'=>$ci->load->view('oauth2/facebook.php','',true)),
                );
            
        }
    }

    function apply() {
        return true;
    }

}
