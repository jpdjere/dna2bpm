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
  <link href="{base_url}dashboard/assets/bootstrap-wysihtml5/css/font-awesome-4.4.0/css/font-awesome.css" rel="stylesheet" type="text/css" />
  <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
  <script src="{base_url}financiamiento/assets/css/bootstrap.min.js"></script>
  <script type="text/javascript">var base_url="{base_url}";</script>
  <script src="{base_url}financiamiento/assets/jscript/respuesta_pyme_bancario.js"></script>
</head>
<body>

<?php include("listas.php"); ?>
<?php
function lista($nombre, $meses, $nombre2=null){
  if($nombre2==null){
    $nombre2=$nombre;
  }
	$array = $meses;
	$txt= "<select required disabled class='form-control' name='$nombre2' id='".$nombre."2'><option selected disabled value=''>---</option>";
	//disabled
	for ($i=0; $i<sizeof($array); $i++){
	$txt .= "<option value='$i'>". $array[$i] . '</option>';
	}
	$txt .= '</select>';
	return $txt;
}?>

<div class="col-sm-12 contenedor">
  
<header>
<div class="cabezal">
<a class="logo" href="http://www.produccion.gob.ar">
  <img width="260" src="{base_url}financiamiento/assets/images/Logo-ministerio.png" scale="0">
</a>
</div>
<div class="slide">
<div class="imagen"><h2 class="titulo-slide">
FINANCIAMIENTO
</h2></div>
</div>
</header>

<section class="formulario_unico">


<h2 class="subtitulo"> A través del Formulario Único, las empresas podrán acceder al ﬁnanciamiento que necesitan.</h2>
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
<h1 class="entry-title h1-paginas">
<a class="volver" href="javascript:history.back()" title="Volver Atras">
<i class="fa fa-angle-left"></i>
</a>
FORMULARIO ÚNICO
</h1>


<div class="col-sm-12">
<form class="formulario"  action="{base_url}financiamiento/financiamiento/guardar_bancos"  method="post">
<fieldset>

<!-- Campos ocultos -->
<input type="hidden" id="idwf" name="idwf" value="{idwf}">
<input type="hidden" id="idcase" name="idcase" value="{idcase}">
<input type="hidden" id="token" name="token" value="{token}">

<div class="alert alert-success">
  Según los datos enviados su empresa podría calificar a los siguientes programas: {programas}
</div>

<!-- RBT -->
<?php  $id = 'rbt';?><div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6 oculto">
  <label class="control-label" for="rbt">BANCOS con convenio RBT</label>
  <?php $resultado = lista($id, $banco_rbt); echo $resultado;?>
</div>

<!-- PARQUES -->
<?php $id = 'parques';?><div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6 oculto">
  <label class="control-label" for="parques">BANCOS con convenio PARQUES</label>
  <?php $resultado = lista($id, $banco_parques); echo $resultado;?>
</div>

<!-- MI GALPON -->
<?php  $id = 'mi_galpon';?><div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6 oculto">
  <label class="control-label" for="mi_galpon">BANCOS con convenio MI GALPÓN</label>
  <?php $resultado = lista($id, $banco_mi_galpon); echo $resultado;?>
</div>

<!-- OTROS -->
<?php  $id = 'otros';?><div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6 oculto">
  <label class="control-label" for="otros">Todos los Bancos</label>
  <?php $resultado = lista($id, $todos_bancos); echo $resultado;?>
</div>

<!-- EFIS -->
<?php $id = 'compartir_efis';?><div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6 oculto">
  <label class="control-label" for="compartir_efis">¿Acepta usted que compartamos esta información con Bancos para generar alternativas de financiamiento?</label>
  <?php $resultado = lista($id, $si_no); echo $resultado;?>
</div>

<!-- FIN FORMULARIO -->
<?php $id = 'enviar';?><div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-12 col-lg-12">
  <label class="control-label" for="enviar"></label>
  <div class="text-right">
    <input type="submit" class="btn btn-primary" value="Enviar">
  </div>
</div>


</fieldset>
</form>
</section>


<footer style="width: 100%; float:left; margin-top:20px; background:#F0F1F1;">
<div class="col-xs-12 col-sm-12 col-md-12 footer-texto">
<img width="230" src="{base_url}financiamiento/assets/images/secretaria.jpg" scale="0"><a class="logofoter" href="http://www.produccion.gob.ar">
<img width="230" src="{base_url}financiamiento/assets/images/Logo-ministerio.png" scale="0">
</a></div>
</div>   
</footer>


</div>
</body>
</html>