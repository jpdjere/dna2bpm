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
<form class="formulario"  action="{base_url}financiamiento/financiamiento/continuar_flujo"  method="post">
<fieldset>

<!-- Campos ocultos -->
<input type="hidden" name="idwf" value="{idwf}">
<input type="hidden" name="idcase" value="{idcase}">
<input type="hidden" name="token" value="{token}">

<!-- Datos Generales -->
<div  class="form-group col-xs-12 col-sm-6 col-lg-6">
  <label for="razon_social" class="control-label">Razón Social</label>
    <input class="form-control" id="razon_social" placeholder="" required type="text">
</div>

<div class="form-group col-xs-12 col-sm-6 col-lg-6">
  <label class="control-label" for="tipo_sociedadInput">Tipo de Sociedad</label>
  <?php
    $id = 'tipo_sociedad';
    $resultado = lista($id, $tipo_sociedad);
    echo $resultado;
  ?>
</div>

<div class="form-group col-xs-12 col-sm-6 col-lg-6">
  <label for="cuit" class="control-label">Cuit:</label>
    <input class="form-control" id="cuit" placeholder="" required type="number">
    <p class="help-block">Sin guiones ni espacios</p>
</div>

<?php $id = 'provincia';?>
<div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6">
  <label class="control-label" for="provincia_implementacion">Provincia de Implementación del proyecto</label>
  <?php $resultado = lista($id, $provincia); echo $resultado;?>
</div>

<div  class="form-group col-xs-12 col-sm-6 col-lg-6">
  <label for="nombre_contacto" class="control-label">Nombre de Contacto</label>
    <input class="form-control" id="nombre_contacto" placeholder="" required type="text">
</div>

<div class="form-group col-xs-12 col-sm-6 col-lg-6">
  <label for="cargo" class="control-label">Cargo</label>
    <input class="form-control" id="cargo" name="cargo" placeholder="" required type="text">
</div>

<?php $id = '';?>
<div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6">
  <label for="telefono" class="control-label">Teléfono</label>
    <input class="form-control" id="telefono" name="telefono" placeholder="" required type="tel">
</div>

<?php $id = 'mail';?>
<div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6">
  <label for="mail" class="control-label">E-mail</label>
    <input class="form-control" id="mail" id="mail" placeholder="" required type="email">
 </div>


<!-- Sector y Actividad -->
<?php $id = 'sector_actividad';?>
<div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6">
  <label class="control-label" for="sector_actividad">Sector de actividad Principal</label>
  <?php $resultado = lista($id, $sector_actividad); echo $resultado;?>
</div>

<?php $id = 'cat_agropecuario';?><div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6 oculto">
  <label class="control-label" for="cat_agropecuario">Categorías Pyme Agropecuario</label>
  <?php $resultado = lista($id, $cat_agropecuario, "cat_pyme"); echo $resultado;?>
</div>

<?php $id = 'cat_industria_mineria';?><div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6 oculto">
  <label class="control-label" for="cat_industria_mineria">Categorías Pyme Industria y Minería</label>
  <?php $resultado = lista($id, $cat_industria_mineria, "cat_pyme"); echo $resultado;?>
</div>

<?php  $id = 'cat_comercio';?><div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6 oculto">
  <label class="control-label" for="cat_comercio">Categorías Pyme Comercio</label>
  <?php $resultado = lista($id, $cat_comercio, "cat_pyme"); echo $resultado;?>
</div>

<?php $id = 'cat_servicios';?><div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6 oculto">
  <label class="control-label" for="cat_servicios">Categorías Pyme Servicios</label>
  <?php $resultado = lista($id, $cat_servicios, "cat_pyme"); echo $resultado;?>
</div>

<?php $id = 'cat_construccion';?><div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6 oculto">
  <label class="control-label" for="cat_construccion">Categorías Pyme Construcción</label>
  <?php $resultado = lista($id, $cat_construccion, "cat_pyme"); echo $resultado;?>
</div>


<!-- PYME (1) -->
<?php $id = 'tiene_prestamos';?><div  id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6 oculto">
  <label class="control-label" for="tiene_prestamos">¿Tiene uno o más préstamos bancarios vigentes, vinculados a la actividad de la empresa, que en su totalidad sumen un monto SUPERIOR a $1.000.000?</label>
  <?php $resultado = lista($id, $si_no); echo $resultado;?>
</div>

<?php $id = 'clasificacion_deudores';?><div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6 oculto">
  <label class="control-label" for="clasificacion_deudores">Clasificación de Deudores según BCRA ¿Tiene situación 2 o más?</label>
<?php $resultado = lista($id, $si_no); echo $resultado;?>
</div>

<?php  $id = 'tiene_tramite';?><div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6 oculto">
  <label class="control-label" for="tiene_tramite">¿Tiene en tramite un concurso de acreedores?</label>
  <?php $resultado = lista($id, $si_no); echo $resultado;?>
</div>


<!-- PYME BANCARIO (1.1) -->
<?php  $id = 'destino_prestamo';?><div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6 oculto">
  <label class="control-label" for="destino_prestamo">Destino del Préstamo</label>
  <?php $resultado = lista($id, $destino_prestamo); echo $resultado;?>
</div>

<?php  $id = 'sectores_proyecto';?><div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6 oculto">
  <label class="control-label" for="sectores_proyecto">Sector al que pertenece la actividad a ser financiada </label>
  <?php $resultado = lista($id, $sectores_proyecto); echo $resultado;?>
</div>

<?php  $id = 'parque_industria';?><div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6 oculto">
  <label class="control-label" for="parque_industria">Está situado o por situarse en un parque industrial?</label>
  <?php $resultado = lista($id, $si_no); echo $resultado;?>
</div>

<?php $id = 'monto_prestamo';?><div  id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6 oculto">
  <label for="monto_prestamo" class="control-label">Indique Monto del préstamo solicitado MM$</label>
  <input class="form-control"  disabled="true" id="monto_prestamo2" name="monto_prestamo" placeholder="$" required type="number">
</div>


<!-- PYME NO BANCARIO -->
<?php $id = 'destino_prestamo_nobanc';?><div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6 oculto">
  <label class="control-label" for="destino_prestamo_nobanc">Destino del Préstamo</label>
  <?php $resultado = lista($id, $destino_prestamo); echo $resultado;?>
</div>

<?php $id = 'sectores_proyecto_nobanc';?><div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6 oculto">
  <label class="control-label" for="sectores_proyecto">Sector al que pertenece la actividad a ser financiada </label>
  <?php $resultado = lista($id, $sectores_proyecto); echo $resultado;?>
</div>

<?php $id = 'monto_solicitado';?><div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6 oculto">
  <label class="control-label" for="monto_solicitado">Monto del préstamo solicitado MM$</label>
  <?php $resultado = lista($id, $monto_prestamo_gran); echo $resultado;?>
  </div>

<?php $id = 'concurso_homologado';?><div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6 oculto">
  <label class="control-label" for="concurso_homologado">El concurso se encuentra homologado?</label>
  <?php $resultado = lista($id, $si_no); echo $resultado;?>
</div>


<!-- GRAN EMPRESA -->
<?php $id = 'destino_prestamo_gran';?><div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6 oculto">
  <label class="control-label" for="destino_prestamo_gran">Destino del Préstamo</label>
  <?php $resultado = lista($id, $destino_prestamo_gran); echo $resultado;?>
</div>

<?php $id = 'sectores_proyecto_gran';?><div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6 oculto">
  <label class="control-label" for="situacion_2_gran">Sector al que pertenece la actividad a ser financiada</label>
  <?php $resultado = lista($id, $sectores_proyecto); echo $resultado;?>
</div>

<?php $id = 'monto_prestamo_gran';?><div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6 oculto">
  <label for="monto_prestamo_gran" class="control-label">Indique Monto del préstamo solicitado MM$</label>
  <?php $resultado = lista($id, $monto_prestamo_gran); echo $resultado;?>
</div>

<?php $id = 'situacion_2_gran';?><div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6 oculto">
  <label class="control-label" for="situacion_2_gran">Clasificación de Deudores según BCRA ¿Tiene situación 2 o más?</label>
  <?php $resultado = lista($id, $si_no); echo $resultado;?>
</div>

<?php $id = 'deuda_afip_gran';?><div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6 oculto">
  <label class="control-label" for="deuda_afip_gran">Deuda con Afip de más de 2 periodos</label>
  <?php $resultado = lista($id, $si_no); echo $resultado;?>
</div>

<?php $id = 'signo_negativo_gran';?><div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6 oculto">
  <label class="control-label" for="signo_negativo_gran">Más de dos Resultado Neto últimos 3 ejercicios con signo negativo</label>
  <?php $resultado = lista($id, $si_no); echo $resultado;?>
</div>

<?php $id = 'endeudamiento_gran';?><div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6 oculto">
  <label class="control-label" for="Endeudamiento_gran">Endeudamiento: (Pasivo/Patrimonio Neto) > 1,5</label>
  <?php $resultado = lista($id, $si_no); echo $resultado;?>
</div>

<?php  $id = 'liquidez_gran';?><div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6 oculto">
  <label class="control-label" for="Liquidez_gran">Liquidez (Activo Cte. / Pasivo Cte.) < 1</label>
  <?php $resultado = lista($id, $si_no); echo $resultado;?>
</div>

<?php $id = 'capital_trabajo';?><div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6 oculto">
  <label class="control-label" for="capital_trabajo">Capital de Trabajo: (Activo Cte. - Pasivo Cte.) < 0</label>
  <?php $resultado = lista($id, $si_no); echo $resultado;?>
</div>


<!-- FIN FORMULARIO -->
<?php $id = 'enviar';?><div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-12 col-lg-12">
  <label class="control-label" for="enviar"></label>
  <div class="text-right">
    <input type="submit" class="btn btn-primary" value="Siguiente">
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