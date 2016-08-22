<?php

class tools {

    var $CI;
    var $scripts;
    var $styles;

    public function __construct($params = array()) {
        log_message('debug', "Extui Class Initialized");

// Set the super object to a local variable for use throughout the class
        $this->CI = & get_instance();
        // Register Scripts
    }

    
    //=== Funcion para deserializar datos de un submit ajax jquery con serializearray
    
    function deserialize($data,$allow=array()){
    $clean=array();
    foreach ($data as $item) {
        if(in_array($item['name'],$allow))
            $clean[$item['name']]=htmlspecialchars($item['value']);
    }
    return $clean;
    }



}

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */