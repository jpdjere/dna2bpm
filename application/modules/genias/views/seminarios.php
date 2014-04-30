<!-- Submenu / Breadcrumbs -->
<div class="row-fluid " id="barra_user" >
    <ul class="breadcrumb" style="margin-bottom:0px;padding-bottom:0px" >
         <li ></li> 
          <li class="pull-right perfil">
              <span id="status"></span>
              <a title="{usermail}">{username}</a> <i class="fa fa-angle-right"></i> <i class="{rol_icono}"></i> {rol}
          </li>
    </ul>

</div>
<!-- / Contenido -->
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <!--Sidebar content-->
            <h4>
                Seminarios
            </h4>

<!-- =========== FORM =========== -->
<form class="form-horizontal" action="#">

<!--  CUIT -->
  <div class="control-group">
    <label class="control-label" >CUIT</label>
    <div class="controls">
      <input type="text" name="1695" placeholder="CUIT">
    </div>
  </div>
  
 <!--  Nombre -->
  <div class="control-group">
    <label class="control-label" >Nombre</label>
    <div class="controls">
	<input type="text" name="1693" placeholder="Nombre">
    </div>
  </div>
  
  <!--  Provincia Combo -->
  <div class="control-group">
     <label class="control-label" >Provincia</label>
    <div class="controls">
	  <select name="4651" id="test">

	 </select>
    </div>
   </div>

   <!--  Partido Combo -->
  <div class="control-group">
     <label class="control-label" >Partido</label>
    <div class="controls">
	  <select name="1699">

	 </select>
    </div>
   </div>
  
   <!--  Calle / Ruta -->
  <div class="control-group">
    <label class="control-label" >Calle/Ruta</label>
    <div class="controls">
	<input type="text" name="4653" placeholder="Calle/Ruta">
    </div>
  </div>
 
   <!--  Nro. / Km. -->
  <div class="control-group">
    <label class="control-label" >Nro. / Km.</label>
    <div class="controls">
	<input type="text" name="4654" placeholder="Nro. / Km.">
    </div>
  </div>
 
    <!--  Piso -->
  <div class="control-group">
    <label class="control-label" >Piso</label>
    <div class="controls">
	<input type="text" name="4655" placeholder="Piso">
    </div>
  </div>
    
  <!--  Dto / Oficina -->
  <div class="control-group">
    <label class="control-label" > Dto / Oficina</label>
    <div class="controls">
	<input type="text" name="4656" placeholder=" Dto / Oficina">
    </div>
  </div> 
  
 <!--  Notas -->
  <div class="control-group">
    <label class="control-label" > Notas</label>
    <div class="controls">
	<textarea name="7408"></textarea>
    </div>
  </div> 
   
  <!--  Hidden Fields -->
  <input type="hidden" name="7819" placeholder="Longitud">
  <input type="hidden" name="7820" placeholder="latitud">
    
    

  
   
  
   <!--  Click-->
  <div class="control-group">
    <div class="controls">
      <button type="submit" class="btn">Guardar</button>
    </div>
  </div>
</form>

<!-- ----------- FORM ----------- -->

        </div>

    </div>


</div>
