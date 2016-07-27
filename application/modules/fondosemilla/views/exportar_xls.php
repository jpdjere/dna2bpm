<?php   
   /// Exporta el archivo a Excell - En {filename} va armada la tabla a exportar.
    header("Content-Description: File Transfer");
    header("Content-type: application/x-msexcel" ); 
    header("Content-Type: application/force-download");
    header("Content-Disposition: attachment; filename='data.xls'");
    header("Content-Description: PHP Generated XLS Data" );                
    header("Content-type: text/html; charset=utf-8" ); 
 ?>
 
 {data}