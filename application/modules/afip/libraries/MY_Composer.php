<?php
/**
 * Description of MY_Composer
 *
 * @author sfiorentino
 */
class MY_Composer 
{
    function __construct() 
    {
        include(APPPATH."/vendor/autoload.php");
    }
}