<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class False extends MX_Controller {
    function __construct(){
        parent::__construct();
    }
    
    function index(){
    show_error('#FAIL',404);
    }
}