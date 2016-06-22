<!DOCTYPE html>
<html lang="es">
<head>
  <title>Ministerio de Produccón de la Nación</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="{base_url}dashboard/assets/bootstrap-wysihtml5/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="{base_url}financiamiento/assets/css/estilo.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
  <link rel="shortcut icon" type="image/png" href="{base_url}financiamiento/assets/images/cropped-Escudo-01.png"/>
  <script src="{base_url}financiamiento/assets/css/bootstrap.min.js"></script>
  <link href="{base_url}dashboard/assets/bootstrap-wysihtml5/css/font-awesome-4.4.0/css/font-awesome.css" rel="stylesheet" type="text/css" />
  <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
 </head>
<body>
<?php include("listas.php"); ?>
<?php 

$tipo_sociedad[$_REQUEST['tipo_sociedad']];
$provincia[$_REQUEST['provincia']];
$sector_actividad[$_REQUEST['sector_actividad']];
$cat_agropecuario[$_REQUEST['cat_agropecuario']];;
$cat_industria_mineria[$_REQUEST['cat_industria_mineria']];
$cat_comercio[$_REQUEST['cat_comercio']];
$cat_servicios[$_REQUEST['cat_servicios']];
$cat_construccion[$_REQUEST['cat_construccion']];
$destino_prestamo[$_REQUEST['destino_prestamo']];
$sectores_proyecto[$_REQUEST['sectores_proyecto']];
$monto_prestamo_gran[$_REQUEST['monto_prestamo_gran']];
$banco_rbt[$_REQUEST['banco_rbt']];
$banco_mi_galpon[$_REQUEST['banco_mi_galpon']];
$banco_mi_galpon_rbt[$_REQUEST['banco_mi_galpon_rbt']];
$banco_parques[$_REQUEST['banco_parques']];
$banco_parques_rbt[$_REQUEST['banco_parques_rbt']];
$destino_prestamo_gran[$_REQUEST['destino_prestamo_gran']];
$todos_bancos[$_REQUEST['todos_bancos']];




?>
<div class="col-sm-12 contenedor">
 <header style="width:100%; float:left">
      <a class="logo" href="http://www.produccion.gob.ar">
<img width="260" src="../assets/images/Logo-ministerio.png" scale="0">
</a>


    </header>

    <section class="formulario_unico">
  <h1 class="entry-title h1-paginas">
<a class="volver" href="javascript:history.back()" title="Volver Atras">
<i class="fa fa-angle-left"></i>
</a>
FORMULARIO ÚNICO
</h1>
<div class="share-post">
<a class="facebook" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=http://www.produccion.gob.ar/pac-conglomerados-productivos/">
<i class="fa fa-facebook fa-1x"></i>
</a>
<a class="twitter" target="_blank" href="https://twitter.com/intent/tweet?url=http://www.produccion.gob.ar/pac-conglomerados-productivos/&text=PAC – Conglomerados Productivos">
<i class="fa fa-twitter fa-1x"></i>
</a>
<a target="_blank" href="mailto:complete-mail@complete.com?subject=Formulario Único&body=http://www.produccion.gob.ar/formulario-unico/">
<i class="fa fa-envelope fa-1x"></i>
</a>
</div>
           <div class="col-sm-12">
<div id="resspuesta" class="form-group col-xs-12 col-sm-12 col-lg-12">
<div class="alert alert-success">
  <strong>Formulario enviado!</strong> Recibirá por mail información sobre los pasos a seguir.
</div>
<div class="alert alert-info">
 figura respuesta segui seleccion.</div>
</div>     
                
       
    </section>
  
    <footer style=" margin-bottom: 20px; width: 100%; float:left; margin-top:20px;">
    <div class="col-xs-12 col-sm-12 col-md-8 footer-texto"> 2016 | Ministerio de Producción | Hipólito Yrigoyen 250 | (C1086AAB) CABA | Tel. 0800.333.7963</div>
<div class="col-xs-12 col-sm-12 col-md-4 logo-footer">
<a href="http://www.produccion.gob.ar">
<img width="230" src="{base_url}financiamiento/assets/images/Logo-ministerio.png" scale="0">
</a>
</div>   
    </footer>
</div>


</body>
</html>