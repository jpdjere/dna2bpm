<?php
if (isset($fileName))
    $xlsFileName = str_replace(" ", "_", $fileName);

header("Content-Description: File Transfer");
header("Content-type: application/x-msexcel");
header("Content-Type: application/force-download");
header("Content-Disposition: attachment; filename=resumen_empresas.xls" );
header("Content-Description: PHP Generated XLS Data");
header('Content-type: text/html; charset=UTF-8');
?>
{show_table}