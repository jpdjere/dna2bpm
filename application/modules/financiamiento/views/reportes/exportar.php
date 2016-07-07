<?php   

    header("Content-Description: File Transfer");
    header("Content-type: application/x-msexcel" ); 
    header("Content-Type: application/force-download");
    header("Content-Disposition: attachment; filename=".$filename);
    header("Content-Description: PHP Generated XLS Data" );                
    header("Content-type: text/html; charset=utf-8" ); 

?>

{content}