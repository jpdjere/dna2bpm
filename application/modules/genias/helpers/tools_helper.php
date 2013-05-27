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
    
    
    function iso_encode($d){
        $date=date_create_from_format('d-m-Y', $d);
        return date_format($date, 'Y-m-d');
    }
    
    function iso_decode($d){
    $date=date_create_from_format('Y-m-d', $d);
    return date_format($date, 'd-m-Y');
    }
    
?>
