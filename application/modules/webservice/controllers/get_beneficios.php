<?php

require 'webservice.php';
$myclass = new webservice();

/* VARS */
$a = $program;
$b = $parameter;

$response = $myclass->msg($a, $b);

