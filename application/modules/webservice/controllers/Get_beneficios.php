<?php

/* MAIN FUNCTION */
require 'webservice.php';
$myclass = new webservice();

/* by Cuit Query */
if (isset($param_cuit))
    $response_cuits = $myclass->msg_parameter($param_cuit);
else
    $response = $myclass->querys($program_name, $date_from, $date_to);



