<div class="row test" id="barra_user" >
    <ul class="breadcrumb" style="margin-bottom:0px;padding-bottom:0px" >
    <button type="button" class="btn hide_offline" data-toggle="collapse" data-target="#meta_div">
        <i class="icon-plus"></i> Nueva meta
    </button>
          <li class="pull-right perfil">
              <span id="status"></span>
              <a title="{usermail}">{username}</a> <i class="fa fa-angle-right"></i> <i class="{rol_icono}"></i> {rol}
          </li>
    </ul>
</div>
<!-- ==== Contenido ==== -->
<div class="container" > 
    
    <div class="row">
        <!-- xxxxxxxxxxxxxxxx CREAR META  xxxxxxxxxxxxxxxx -->
        <div id="meta_div" class="collapse out no-transition">
            <form id="form_goals" method="post" class="well">
                <div  class="row">
                    <div class="col-md-6">
                        <label>Proyecto</label>
                        <select name="proyecto" class="input-block-level">
                            {projects}
                            <option value="{id}">{name}</option>
                            {/projects}
                        </select>
                        <label>Genia</label>
                        <select name="genia" class="input-block-level">
                            {genias}
                            <option value="{_id}">{nombre}</option>
                            {/genias}
                        </select>
                        <div class="">
                            <label>Cantidad</label>
                            <input type="number" name="cantidad" placeholder="Cantidad"   class="input-block-level"/>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="">
                            <label>Período</label>

                            <!--<div class="input-append">
                            <input type="text" name="desde" placeholder="Período"   class="input-block-level "/>
                            </div>
                            -->
                            <div data-date-viewMode="months" data-date-minViewMode="months" data-date-format="mm-yyyy" data-date="" id="dp3" class="input-append date dp">
                                <input type="text" name="desde" readonly="" value=""  class="input-block-level">
                                <span class="add-on"><i class="icon-calendar"></i></span>
                            </div>
                        </div>

                        <label>Observaciones</label>
                        <textarea name="observaciones" placeholder="Observaciones"  class="input-block-level" ></textarea>

                    </div>
                </div>
                <div  class="row-fluid">
                    <div class="col-md-12">

                        <button class="btn btn-block btn-primary hide_offline" type="submit" id="bt_save"><i class="icon-save"></i>  Agregar</button>  
                    </div> 

                </div> 

            </form>  



        </div> 
    </div> 
 

<!-- ==== RESUMEN COORDINADOR ==== -->


        <ul class="nav nav-tabs" id="dashboard_tab1">
        <li class="active"><a href="#tab_resumen" data-toggle="tab">Resumen</a></li>
        {genias} 
        <li class=""><a href="#tab-{_id}" data-toggle="tab">{nombre}</a></li>
        {/genias}
        </ul>
        
        <div class="tab-content">
            <div class="tab-pane active" id="tab_resumen">
<!-- =================  ESCENARIO PYME ================= -->
                <div class="row">
                     <div class="col-md-12">
                         <h1><i class="fa fa-bookmark"></i> Escenario PYME</h1>
                     </div>
                </div>
                
                <div class="alert {resumen_class}" id="{_id}">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Sus genias tienen {goal_cumplidas_total_2} de {goal_cantidad_total_2} objetivos cumplidos.</strong> 
                </div> 
                
                <!-- ================= VISITAS  -->
<div class='row' id="filtro_visitas">
<div class='col-md-3' >

<div data-date-viewMode="months" data-date-minViewMode="months" data-date-format="mm-yyyy" data-date=""  class=" input-prepend date pull-right " id="dp4">
<!-- === -->
<div class="input-group">
  <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
  <input type="text" name="visitas_desde" readonly="" value=""  class="form-control" >
</div>
<!-- --- -->
</div> 
</div>
<div class='col-md-9' id="wrapper_visitas">
<!-- dummy visitas -->
</div>
</div>
<!-- =================  ESCENARIO POLITICO ================= -->
                        <div class="row">
                     <div class="col-md-12">
                         <h1><i class="fa fa-bookmark"></i> Escenario Político</h1>
                     </div>
                </div>
                
                <div class="alert {resumen_class}" id="{_id}">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Sus genias tienen {goal_cumplidas_total_4} de {goal_cantidad_total_4} objetivos cumplidos.</strong> 
                </div> 

                <!-- ================= VISITAS  -->

<div class='row' id="filtro_visitas_instituciones">
<div class='col-md-3' >


<div data-date-viewMode="months" data-date-minViewMode="months" data-date-format="mm-yyyy" data-date=""  class=" input-prepend date pull-right " id="datepicker_instituciones">
    <!-- === -->
<div class="input-group">
  <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
  <input type="text" name="visitas_desde" readonly="" value=""  class="form-control" >
</div>
<!-- --- -->

</div> 
</div>
<div class='col-md-9' id="wrapper_visitas_instituciones">
<!-- dummy visitas -->
</div>
</div>                        
            </div>
<?php
// ==== TABS DE LAS GENIAS
foreach($genias as $genia){ 
echo "<div class='tab-pane' id='tab-{$genia['_id']}'>";
// Escenario Politico
echo <<<BLOC
        <div class="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Escenario Político: {$goal_cumplidas_2[(string)$genia['_id']]} de {$goal_cantidad_2[(string)$genia['_id']]} objetivos cumplidos.</strong> 
        </div>
BLOC;
// Escenario Institucional
echo <<<BLOC
        <div class="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Escenario Institucional: {$goal_cumplidas_4[(string)$genia['_id']]} de {$goal_cantidad_4[(string)$genia['_id']]} objetivos cumplidos.</strong> 
        </div>
BLOC;
        
echo "</div>";
} 
?>
        

    




    <!-- ==== METAS ==== -->

        <h1><i class="fa fa-bookmark"></i> Metas</h1>

    <div class="row">

        {metas}
        <!-- test  -->
        <!-- <div class="span6 {status_class}">  -->
        <div class="col-md-6 meta" data-genia="{genia}" > 
            <input type="hidden" name="metaid" value="{_id}"/>
            <div class="well">
 <!--  -------------------------------------------  -
                <!-- Nombre Proyecto -->
                <div class="row	"> 
                    {if {rol}=='coordinador'}
	                    <div class="col-md-6">  
	                    <select name="metas_proyecto" class="form-control">  
	                        {select_project}
	                    </select>
	                     </div>
	                    <div class="col-md-6"> 
	                        <span class="pull-right metas_cantidad">{cumplidas_count} / {cantidad}</span>
	                    </div>
                    {else}
	                    <div class="col-md-12"> 
	                    <h1>{proyecto_name}<span class="pull-right">{cumplidas_count}/{cantidad}</span></h1>
	                    </div> 
                    {/if}
                </div>
                <!-- === PERIODO === -->
				<div class="row"> 
					<div class="col-md-12">  		
					{if {rol}=='coordinador'} 			
						<div data-date-viewMode="months" data-date-minViewMode="months" data-date-format="mm-yyyy" data-date=""  class="input-append date dp" >
						 <div class="input-group">
						  <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
						  <input type="text" name="desde" readonly="" value="{desde_raw}"  class="form-control" >
						  </div>
					  </div>
	                 {else}
	                 	{desde}  
	                 {/if}
					</div>
				</div>
				<!-- === TEXTAREA === -->
				<div class="row"> 
				<div class="col-md-12"> 	
					<textarea rows="3" class="form-control" name="observaciones">{observaciones} </textarea>
				 </div>	  
			 	 </div>	
					  
                <div class="row"> 
                <div class="col-md-12"> 
                    <ul class="list-unstyled list-inline">
						<!-- === STATUS === -->
                        <li>
                            <i class="fa fa-eye" title="Estado" ></i> 
                                {if {status} == 'open'}
                                 <span class="label label-important">Pendiente de revisión</span>
                                {/if}
                                {if {status} == 'closed'}
                                 <span class="label label-success">Aprobado</span>
                                {/if}
                                             
                                {if {rol}=='coordinador'}
                                   {if {status} == 'open'}
                                        <button class="aprobar btn btn-xs btn-success hide_offline" url="{url_case}" type="button">
                                               <i class="fa fa-thumbs-o-up"></i> Aprobar
                                       </button>
                                    {/if}
                                {/if}
                        </li>
                        <li>
                        <!-- === AUTHOR === -->
                            <i class="fa fa-user" title="Autor"></i> {owner}
                        </li>
                        <li>
                        <!-- === GENIA === -->
                            <i class="fa fa-flag" title="Genia" ></i> {genia_nombre}
                        </li>
                    </ul>
                </div>
				</div>
                {if {rol}=='coordinador'}
                    <button class="guardar btn btn-xs btn-success hide_offline" url="#" type="button">
                            <i class="fa fa-thumbs-up"></i> Guardar
                    </button>
                   <a class="bt_delete btn btn-xs btn-danger hide_offline"  type="button">
                            <i class="fa fa-trash-o"></i> Eliminar
                    </a>
                {/if}
                
  <!--  -------------------------------------------  -->
            </div>
        </div>
        
        {/metas}
    </div>
    <!-- ============= metas  ============= -->
</div>
<iframe src="{module_url}splash" width="1" height="1">
  <p>Your browser does not support iframes.</p>
</iframe>