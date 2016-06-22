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

<script src="{base_url}financiamiento/assets/jscript/arrays_de_campos.js"></script>
<script src="{base_url}financiamiento/assets/jscript/oculta_muestra.js"></script>
</head>
<body>

<?php include("listas.php"); ?>
<?php
function lista($nombre, $meses, $nombre2=null){
  if($nombre2==null){
    $nombre2=$nombre;
  }
	$array = $meses;
	$txt= "<select required disabled class='form-control' name='$nombre2' id='".$nombre."2'><option selected disabled>---</option>";
	//disabled
	for ($i=0; $i<sizeof($array); $i++){
	$txt .= "<option value='$i'>". $array[$i] . '</option>';
	}
	$txt .= '</select>';
	return $txt;
}?>

<div class="col-sm-12 contenedor">
 <header style="width:100%; float:left">
      <a class="logo" href="http://www.produccion.gob.ar">
<img width="260" src="{base_url}financiamiento/assets/images/Logo-ministerio.png" scale="0">
</a>


  </header>

<section class="formulario_unico">
<h1 class="entry-title h1-paginas">
<a class="volver" href="javascript:history.back()" title="Volver Atras">
<i class="fa fa-angle-left"></i>
</a>
FORMULARIO ÚNICO - PYME BANCARIO
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
<form class="formulario"  action="{base_url}financiamiento/financiamiento/continuar_flujo"  method="post">
<fieldset>

<!-- Campos ocultos -->
<input type="hidden" name="idwf" value="{idwf}">
<input type="hidden" name="idcase" value="{idcase}">
<input type="hidden" name="token" value="{token}">



<!-- es pyme  bancario rbt-->
<?php  $id = 'banco_rbt';?><div style="display:none"  id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6">
  <label class="control-label" for="banco_rbt">BANCOS con convenio RBT</label>
<?php
		 
$resultado = lista($id, $banco_rbt);
echo $resultado;
		    ?>                 
</div>
<!-- fin pyme  bancario rbt-->

<!-- es pyme  bancario mi galpon-->
<?php $id = 'banco_parques';?><div style="display:none"  id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6">
  <label class="control-label" for="banco_mi_galpon_rbt">BANCOS con convenio PARQUES  (SIN PRIORIZACIÓN)</label>
<?php
		   
$resultado = lista($id, $banco_parques);
echo $resultado;
		    ?>                 
</div>

<!-- fin pyme  bancario mi galpon-->


<!-- es pyme  bancario mi parques-->

<?php  $id = 'banco_parques_rbt';?><div style="display:none"  id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6">
  <label class="control-label" for="banco_parques_rbt">BANCOS con convenio RBT + PARQUES (SIN PRIORIZACIÓN)</label>
<?php
		  
$resultado = lista($id, $banco_parques_rbt);
echo $resultado;
		    ?>                 
</div>

<!-- fin pyme  bancario mi parques-->
<!-- fin pyme  bancario mi todos-->
<?php  $id = 'todos_bancos';?><div style="display:none"  id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6">
  <label class="control-label" for="todos_bancos">Todos los Bancos</label>
<?php
		  
$resultado = lista($id, $todos_bancos);
echo $resultado;
		    ?>                 
</div>
 
<?php $id = 'compartir_efis';?><div style="display:none"  id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6">
  <label class="control-label" for="compartir_efis">Compartir información con EFIS?</label>
<?php
		   
$resultado = lista($id, $si_no);
echo $resultado;
		    ?>                 
</div>
<!-- fin es pyme  bancario rbt-->



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