<?php


    function compact_serialized($serialized){
        $mydata=array();
        foreach ($serialized as $v){
            $mydata[$v['name']]=$v['value'];
        }
        return $mydata;
    }
    
    // Profile    
    function get_gravatar($email) {
    $code=md5( strtolower( trim( $email ) ) );
    if($str = @file_get_contents( "http://www.gravatar.com/$code.php" )){
    $profile = unserialize( $str );
        // Chequeo en Gravatar.com
        if ( is_array( $profile ) && isset( $profile['entry'] ) ){
            return($profile['entry'][0]['thumbnailUrl']);
        }
    }else{
            // Devuelvo el default
           return base_url() . 'genias/assets/images/avatar-hombre.jpg';

        }
    }
    
?>
