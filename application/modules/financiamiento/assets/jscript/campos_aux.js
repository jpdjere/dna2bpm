
var sectores=[
	"cat_agropecuario",
	"cat_comercio",
	"cat_industria_mineria",
	"cat_servicios",
	"cat_construccion"
];

/*
var sectores=[
	"cat_agropecuario",
	"cat_industria",
	"cat_minería",
	"cat_servicios",
	"cat_construccion"
	"cat_comercio",
];
*/

var campos_pyme_general=[
	"tiene_prestamos",
	"clasificacion_deudores",
	"tiene_tramite"
];

var campos_pyme_bancario=[
	"destino_prestamo",
	"sectores_proyecto",
	"parque_industria",
	"monto_prestamo",
	"destino_prestamo-0",
	"destino_prestamo-1",
	"destino_prestamo-2",
	"destino_prestamo-3",
	"destino_prestamo-4",
	"destino_prestamo-5",
];

var campos_pyme_no_bancario=[
	"destino_prestamo_nobanc",
	"sectores_proyecto_nobanc",
	"monto_solicitado_bienes_cap",
	"monto_solicitado_inversion_prod",
	"monto_solicitado_otros",
	"concurso_homologado"
];

var campos_gran_empresa=[
	"destino_prestamo_gran",
	"sectores_proyecto_gran",
	"monto_prestamo_gran",
	"situacion_2_gran",
	"deuda_afip_gran",
	"signo_negativo_gran",
	"endeudamiento_gran",
	"liquidez_gran",
	"capital_trabajo"
];

var todos_los_campos=[
	campos_pyme_general,
	campos_pyme_bancario,
	campos_pyme_no_bancario,
	campos_gran_empresa,
];


/*

<!-- PYME RBT (1.1.RBT) -->
<?php  $id = 'banco_rbt';?><div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6 oculto">
  <label class="control-label" for="banco_rbt">BANCOS con convenio RBT</label>
  <?php $resultado = lista($id, $banco_rbt); echo $resultado;?>
</div>

<!-- PYME MI GALPON (1.1.MI GALPON) -->
<?php  $id = 'banco_migalpon';?><div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6 oculto">
  <label class="control-label" for="banco_migalpon">BANCOS con convenio MI GALPÓN</label>
  <?php $resultado = lista($id, $banco_mi_galpon); echo $resultado;?>
</div>

<!-- PYME PARQUES (1.1.PARQUES) -->
<?php $id = 'banco_parques';?><div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6 oculto">
  <label class="control-label" for="banco_mi_galpon_rbt">BANCOS con convenio PARQUES  (SIN PRIORIZACIÓN)</label>
  <?php $resultado = lista($id, $banco_parques); echo $resultado;?>
</div>

<!-- fin pyme  bancario mi galpon-->


<!-- es pyme  bancario mi parques-->

<?php  $id = 'banco_parques_rbt';?><div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6 oculto">
  <label class="control-label" for="banco_parques_rbt">BANCOS con convenio RBT + PARQUES (SIN PRIORIZACIÓN)</label>
<?php
		  
$resultado = lista($id, $banco_parques_rbt);
echo $resultado;
		    ?>                 
</div>

<!-- fin pyme  bancario mi parques-->
<!-- fin pyme  bancario mi todos-->
<?php  $id = 'todos_bancos';?><div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6 oculto">
  <label class="control-label" for="todos_bancos">Todos los Bancos</label>
<?php
		  
$resultado = lista($id, $todos_bancos);
echo $resultado;
		    ?>                 
</div>
 
<?php $id = 'compartir_efis';?><div id="<?php echo $id; ?>" class="form-group col-xs-12 col-sm-6 col-lg-6 oculto">
  <label class="control-label" for="compartir_efis">Compartir información con EFIS?</label>
<?php
		   
$resultado = lista($id, $si_no);
echo $resultado;
		    ?>                 
</div>
<!-- fin es pyme  bancario rbt-->


*/