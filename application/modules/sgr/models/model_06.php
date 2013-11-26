<?php

class Model_06 {
    /* VALIDADOR ANEXO 06 */

    public function __construct() {
      
    }

    function save($parameter) {
        //INSERT
        $this->debug($parameter);
        
    }
    
     function debug($parameter) {
        return "<pre>" . var_dump($parameter) . "</pre><hr>";
    }

}
