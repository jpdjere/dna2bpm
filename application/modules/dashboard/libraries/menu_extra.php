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
        // Register Scripts
    }

    public function get() {
    return 'H1-H1-H1-H1-H1-H1-H1-';    
    }

}
