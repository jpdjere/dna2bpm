<!DOCTYPE html>
<html lang="es">
<head>
  <title>Ministerio de Produccón de la Nación</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <link href="{base_url}dashboard/assets/bootstrap-wysihtml5/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="{base_url}financiamiento/assets/css/estilo.css">
  <link href="{base_url}dashboard/assets/bootstrap-wysihtml5/css/font-awesome-4.4.0/css/font-awesome.css" rel="stylesheet" type="text/css" />
  <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
  <link rel="shortcut icon" type="image/png" href="{base_url}financiamiento/assets/images/cropped-Escudo-01.png"/>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
  <script src="{base_url}financiamiento/assets/css/bootstrap.min.js"></script>
  <script src="{base_url}financiamiento/assets/jscript/jquery.maskedinput.js"></script>
  <script type="text/javascript"> var base_url = {base_url}; </script>
  
<script src="{base_url}financiamiento/assets/jscript/campos_aux.js"></script>
<script src="{base_url}financiamiento/assets/jscript/formulario_principal2.js"></script>
</head>
<body>
<?php include("listas.php"); ?>
<?php
function lista($nombre, $meses, $nombre2=null){
  if($nombre2==null){
    $nombre2=$nombre;
  }
	$array = $meses;
	$txt= "<select disabled class='form-control' name='$nombre2' id='".$nombre."2' required><option disabled selected value=''>---</option>";
	for ($i=0; $i<sizeof($array); $i++){
	$txt .= "<option value='$i'>". $array[$i] . '</option>';
	}
	$txt .= '</select>';
	return $txt;
}

function lista_multiple($nombre, $meses, $nombre2=null){
  if($nombre2==null){
    $nombre2=$nombre;
  }
	$array = $meses;
	$txt= "<div class='$nombre input-checkbox'>
	          <input type='text' id='$nombre-checkbox-text' class='form-control' placeholder='Seleccione una opción'>
	            <div class='contenedor-checkboxes'>";
	for ($i=0; $i<sizeof($array); $i++){
	$txt .= "
    <div class='checkbox'>
      <label class='checkbox-custom' data-initialize='checkbox'>
        <input id='$nombre-$i' name='".$nombre."[]' type='checkbox' value='$i' disabled>
        <span class='checkbox-label'>$array[$i]</span>
      </label>
    </div>";
    }
	$txt .= '</div></div>';
	return $txt;
}

function lista_multiple_no_desplegable($nombre, $meses, $nombre2=null){
  if($nombre2==null){
    $nombre2=$nombre;
  }
	$array = $meses;
	$txt= "<div class='$nombre input-checkbox'>";
	for ($i=0; $i<sizeof($array); $i++){
	$txt .= "
    <div class='checkbox'>
      <label class='checkbox-custom' data-initialize='checkbox'>
        <input id='$nombre-$i' name='".$nombre."[]' type='checkbox' value='$i' disabled>
        <span class='checkbox-label'>$array[$i]</span>
      </label>
    </div>";
    }
	$txt .= '</div>';
	return $txt;
}

?>
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
  
  
<h2 class="subtitulo">Te ayudamos a encontrar la mejor opción de financiamiento para tu empresa</h2>
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
Formulario de Financiamiento
</h1>


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
    <input class="form-control" id="razon_social" name="razon_social" placeholder="" required type="text">
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
    <input class="form-control" id="cuit" name="cuit" required>
    <p class="help-block">Sin guiones ni espacios</p>
</div>

<?php $id = 'provincia';?>
<div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6">
  <label class="control-label" for="provincia_implementacion">Provincia de Implementación del proyecto</label>
  <?php $resultado = lista($id, $provincia); echo $resultado;?>
</div>

<div  class="form-group col-xs-12 col-sm-6 col-lg-6">
  <label for="nombre_contacto" class="control-label">Nombre de Contacto</label>
    <input class="form-control" id="nombre_contacto" name="nombre_contacto" placeholder="" required type="text">
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
    <input class="form-control" id="mail" name="mail" placeholder="" required type="email">
</div>

<!-- EFIS -->
<?php $id = 'compartir_efis';?><div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6">
  <label class="control-label" for="compartir_efis">¿Acepta usted que compartamos esta información con Bancos para generar alternativas de financiamiento?</label>
  <?php $resultado = lista($id, $si_no); echo $resultado;?>
</div>

<?php  $id = 'bancos_otros';?>
<div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6">
<div class="form-group">
  <label for="<?php echo $id; ?>" class="control-label">Seleccione el/los bancos con los cuales trabaja para futuras lineas disponibles</label>
  <?php echo lista_multiple($id, $todos_bancos, $id.'[]');?>
</div>
</div>


<!-- Sector y Actividad -->
<?php $id = 'sector_actividad';?>
<div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6">
  <label class="control-label" for="sector_actividad">Sector de actividad Principal</label>
  <?php $resultado = lista($id, $sector_actividad); echo $resultado;?>
</div>

<?php $id = 'cat_agropecuario';?><div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6 oculto">
  <label class="control-label" for="cat_agropecuario">Categorías Agropecuario</label>
  <?php $resultado = lista($id, $cat_agropecuario, "cat_pyme"); echo $resultado;?>
</div>

<!--
<?php $id = 'cat_industria';?><div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6 oculto">
  <label class="control-label" for="cat_agropecuario">Categorías Agropecuario</label>
  <?php $resultado = lista($id, $cat_industria_mineria, "cat_pyme"); echo $resultado;?>
</div>

<?php $id = 'cat_mineria';?><div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6 oculto">
  <label class="control-label" for="cat_agropecuario">Categorías Agropecuario</label>
  <?php $resultado = lista($id, $cat_industria_mineria, "cat_pyme"); echo $resultado;?>
</div>
-->

<?php $id = 'cat_industria_mineria';?><div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6 oculto">
  <label class="control-label" for="cat_industria_mineria">Categorías Industria y Minería</label>
  <?php $resultado = lista($id, $cat_industria_mineria, "cat_pyme"); echo $resultado;?>
</div>

<?php  $id = 'cat_comercio';?><div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6 oculto">
  <label class="control-label" for="cat_comercio">Categorías Comercio</label>
  <?php $resultado = lista($id, $cat_comercio, "cat_pyme"); echo $resultado;?>
</div>

<?php $id = 'cat_servicios';?><div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6 oculto">
  <label class="control-label" for="cat_servicios">Categorías Servicios</label>
  <?php $resultado = lista($id, $cat_servicios, "cat_pyme"); echo $resultado;?>
</div>

<?php $id = 'cat_construccion';?><div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6 oculto">
  <label class="control-label" for="cat_construccion">Categorías Construcción</label>
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
  <label class="control-label" for="tiene_tramite">¿Tiene en trámite un concurso de acreedores?</label>
  <?php $resultado = lista($id, $si_no); echo $resultado;?>
</div>


<!-- PYME BANCARIO (1.1) -->
<?php  $id = 'destino_prestamo';?>
<div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6 oculto">
<div class="form-group">
  <label for="<?php echo $id; ?>" class="control-label">Destino del Préstamo</label>
  <?php echo lista_multiple_no_desplegable($id, $destino_prestamo, $id.'[]');?>
</div>
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
  <input class="form-control"  disabled="true" id="monto_prestamo2" name="monto_prestamo" placeholder="$" required type="number" min="0">
</div>


<!-- PYME NO BANCARIO -->
<?php $id = 'concurso_homologado';?><div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6 oculto">
  <label class="control-label" for="concurso_homologado">El concurso se encuentra homologado?</label>
  <?php $resultado = lista($id, $si_no); echo $resultado;?>
</div>

<?php $id = 'sectores_proyecto_nobanc';?><div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6 oculto">
  <label class="control-label" for="sectores_proyecto">Sector al que pertenece la actividad a ser financiada </label>
  <?php $resultado = lista($id, $sectores_proyecto); echo $resultado;?>
</div>

<?php $id = 'destino_prestamo_nobanc';?><div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6 oculto">
  <label class="control-label" for="destino_prestamo_nobanc">Destino del Préstamo</label>
  <?php $resultado = lista($id, $destino_prestamo_fona); echo $resultado;?>
</div>

  <!-- MONTOS -->
<?php $id = 'monto_solicitado_bienes_cap';?><div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6 oculto">
  <label class="control-label" for="monto_solicitado_bienes_cap">Monto del préstamo solicitado MM$</label>
  <?php $resultado = lista($id, $monto_prestamo_bienes_cap, 'monto_solicitado'); echo $resultado;?>
</div>

<?php $id = 'monto_solicitado_inversion_prod';?><div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6 oculto">
  <label class="control-label" for="monto_solicitado_inversion_prod">Monto del préstamo solicitado MM$</label>
  <?php $resultado = lista($id, $monto_prestamo_inversion_prod, 'monto_solicitado'); echo $resultado;?>
</div>

<?php $id = 'monto_solicitado_otros';?><div  id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6 oculto">
  <label for="monto_solicitado_otros" class="control-label">Indique Monto del préstamo solicitado MM$</label>
  <input class="form-control"  disabled="true" id="monto_solicitado_otros2" name="monto_solicitado" placeholder="$" required type="number" min="0">
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

<?php $id = 'monto_prestamo_gran';?><div  id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6 oculto">
  <label for="monto_prestamo_gran" class="control-label">Indique Monto del préstamo solicitado MM$</label>
  <input class="form-control"  disabled="true" id="monto_prestamo_gran2" name="monto_prestamo_gran" placeholder="$" required type="number" min="0">
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

<footer style="width: 100%; float:left; margin-top:20px; background:#F0F1F1;">
<div class="col-xs-12 col-sm-12 col-md-12 footer-texto">
<a class="logofoter" href="http://www.produccion.gob.ar">
<img width="230" src="{base_url}financiamiento/assets/images/Logo-ministerio.png" scale="0">
</a></div>
</div>   
</footer>

</div>
</body>
</html>