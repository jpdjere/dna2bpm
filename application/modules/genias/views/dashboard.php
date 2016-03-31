<!-- ================= SUB BARRA   ================= -->
<div class="container-fluid" style="padding-bottom:15px" > 
    <div class="row-fluid">
    <div class="col-md-9">
		<ul class="nav nav-pills" style="margin-bottom: 8px">
		  <li class="active"> <button type="button" class="btn btn-primary btn-sm hide_offline" data-toggle="collapse" data-target="#meta_div"><i class="fa fa-plus"></i> Nueva meta</button></li>
		</ul>
	</div>
		<div class="col-md-3">
			<div data-date-viewMode="months" data-date-minViewMode="months" data-date-format="mm-yyyy" data-date=""  class=" input-prepend date pull-right dp" id="dp_metas" >
				<div class="input-group">
				  <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
				  <input type="text" placeholder="Período" name="desde" readonly="" value=""  class="form-control" >
				</div>
			</div> 
		</div>				
	 </div> 
 </div> 

<!-- ================= CREAR META   ================= -->
 
<div class="container" > 
    <div class="row">    
        <div id="meta_div" class="collapse out no-transition">
            <form id="form_goals" method="post" class="well form-horizontal">
                <div  class="row">
                 <!--  ========== LEFT -->
                    <div class="col-md-6" >
                    <!--  Proyecto -->
                      <div class="form-group">
					    <label class="col-md-3">Proyecto</label>
					    <div class="col-md-9">
                        <select name="proyecto" class="form-control " >
                            {projects}
                            <option value="{id}">{name}</option>
                            {/projects}
                        </select>
                        </div>
					  </div>
                      <!--  Proyecto -->
                      <div class="form-group">
					    <label class="col-md-3">Genia</label>
					    <div class="col-md-9">
                        <select name="genia" class="form-control">
                            {genias}
                            <option value="{_id}">{nombre}</option>
                            {/genias}
                        </select>
                         </div>
					  </div>
  
                      <!--  Cantidad -->
                      <div class="form-group">
					    <label class="col-md-3">Cantidad</label>
					    <div class="col-md-9">
 						<input type="number" name="cantidad" placeholder="Cantidad"   class="form-control">	
 						</div>
					  </div>

                    </div>
                    <!--  ========== RIGHT -->
                    <div class="col-md-6" >
                   		 <!--  Periodo -->
                   		 <div class="form-group">
							<div data-date-viewMode="months" data-date-minViewMode="months" data-date-format="mm-yyyy" data-date=""  class=" input-prepend date pull-right dp" >
							<div class="input-group">
							  <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
							  <input type="text" placeholder="Período" name="desde" readonly="" value=""  class="form-control" >
							</div>
							</div> 
						</div> 
							
                      <!--  Observaciones -->
                      <div class="form-group">
 						 <textarea name="observaciones" placeholder="Observaciones"  class="form-control" ></textarea>
					  </div>
					  
                      <!--  ButON -->
                      <div class="form-group">
 						 <button class="btn btn-block btn-primary hide_offline" type="submit" id="bt_save"><i class="icon-save"></i>  Agregar</button>  
      
					  </div>

                    </div>
                </div>


            </form>  



        </div> 
    </div> 
 

<!-- =================  TABS ================= -->

        <ul class="nav nav-tabs" id="dashboard_tab1">
        <li class="active"><a href="#tab_resumen" data-toggle="tab">Resumen</a></li>
        {genias} 
        <li class=""><a href="#tab-{_id}" data-toggle="tab">{nombre}</a></li>
        {/genias}
        </ul>
        
        <div class="tab-content">
        	<!-- ========  RESUMEN ESCENARIO PYME ======== -->
            <div class="tab-pane active" id="tab_resumen">
                <div class="row">
                     <div class="col-md-12">
                         <h1><i class="fa fa-bookmark"></i> Escenario PYME :{periodo} <small>(desde {resumen_periodo})</small></h1>
                     </div>
                </div>
                <!--  Alerts Escenario Pyme -->
                <div class="alert {goal_cantidad_total_2_alert}" id="{_id}">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Sus genias tienen {goal_cumplidas_total_2} de {goal_cantidad_total_2} objetivos cumplidos.</strong>
                <span class="pull-right>">
                        (<a href="{base_url}genias/resumen_empresas/2/{periodo_raw}"><icon class="fa fa-download"></icon> xls</a>)
                </span>
                </div> 
                
                <!--  Visitas Escenario Pyme -->

				<div class='row' >
				<div class='col-md-12' >
				<!-- dummy visitas -->

				{resumen_2}
				</div>
				</div>
				
<!-- ========  RESUMEN ESCENARIO POLITICO ======== -->
                <div class="row">
                     <div class="col-md-12">
                         <h1><i class="fa fa-bookmark"></i> Escenario Político : {periodo} <small>(desde {resumen_periodo})</small></h1>
                     </div>
                </div>
                <!--  Alerts Escenario pOLITICO -->
                <div class="alert {goal_cantidad_total_4_alert}" id="{_id}">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Sus genias tienen {goal_cumplidas_total_4} de {goal_cantidad_total_4} objetivos cumplidos.</strong> 
                <span class="pull-right>">
                        (<a href="{base_url}genias/resumen_empresas/4/{periodo_raw}"><icon class="fa fa-download"></icon> xls</a>)
                    </span>
                </div> 

 <!-- ================= VISITAS  -->

<div class='row' >
<div class='col-md-12' >
<!-- dummy visitas -->

		{resumen_4}
</div>
</div>                        
</div>
<?php
// ==== TABS DE LAS GENIAS
foreach($genias as $genia){ 
echo "<div class='tab-pane' id='tab-{$genia['_id']}'>";
// Escenario Politico

echo <<<BLOC
        <div class="alert {$goal_cantidad_2_alert[(string)$genia['_id']]}">
        <button type="button" class="close" data-dismiss="alert ">&times;</button>
        <strong>Escenario Político: {$goal_cumplidas_2[(string)$genia['_id']]} de {$goal_cantidad_2[(string)$genia['_id']]} objetivos cumplidos.</strong> 
        </div>
BLOC;
// Escenario Institucional
echo <<<BLOC
        <div class="alert {$goal_cantidad_4_alert[(string)$genia['_id']]}">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Escenario Institucional: {$goal_cumplidas_4[(string)$genia['_id']]} de {$goal_cantidad_4[(string)$genia['_id']]} objetivos cumplidos.</strong> 
        </div>
BLOC;
        
echo "</div>";
} 
?>
        

    
<!-- ======== METAS ================ -->

   <h1><i class="fa fa-bookmark"></i> Metas</h1>

    <div class="container-fluid">
    <div class="row">

        {metas}        

        <div class="col-md-6 meta" data-genia="{genia}" > 
            <input type="hidden" name="metaid" value="{_id}"/>
            <div class="well">

                 <!-- === COPETE === -->
                <div class="form-inline meta_copete"> 
               		 <span class="label label-info"><i class="fa fa-calendar"></i> {desde_raw}</span>		     
				      {if {status} == 'open'}<span class="label label-warning"><i class="fa fa-eye" title="Estado" ></i>  Pendiente</span>{/if}
				      {if {status} == 'closed'}<span class="label label-success"> <i class="fa fa-eye" title="Estado" ></i> Aprobado</span>{/if}
					 <span class="text-warning"><i class="fa fa-bookmark"></i> {proyecto_name}</span>		 
	                 <a class='pull-right ul_collapse meta_open' href="#"><i class='fa fa-chevron-circle-down'></i></a>
	                 <span class="metas_cantidad pull-right" style="margin-right:6px">{cumplidas_count} / {cantidad}</span>
                </div>                                  
                                
            
                 <!-- === PROYECTO === --> 
                 <div class="meta_body" style="display:none">
                 <div class="form-group" >
                 {if {rol}=='coordinador'}
					<select name="metas_proyecto" class="form-control">  
					{select_project}
					</select>
		         {/if}
                 </div>
                 
                <!-- === PERIODO === -->
				<div class="row "> 
					<div class="col-md-12 form-group" >  		
					{if {rol}=='coordinador'} 			
						<div data-date-viewMode="months" data-date-minViewMode="months" data-date-format="mm-yyyy" data-date=""  class=" input-append date dp" >
						 <div class="input-group">
						  <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
						  <input type="text" name="desde" readonly="" value="{desde_raw}"  class="form-control" >
						  </div>
					  </div>  
	                 {/if}
					</div>
				</div>
				<!-- === TEXTAREA === -->
				<div class="row"> 
				<div class="col-md-12 form-group"> 	
					<textarea rows="3" class="form-control" name="observaciones">{observaciones} </textarea>
				 </div>	  
			 	 </div>	
					  
                <div class="row"> 
                <div class="col-md-12"> 
                    <ul class="list-unstyled list-inline text-warning">
						<!-- === STATUS === -->
                        <li>
                                             
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
                        <li >
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
             </div>
            <!-- meta_body  -->
            </div>
        </div>
        
        {/metas}
    </div>
    </div>
    <!-- ============= metas  ============= -->
</div>
<iframe src="{module_url}splash" width="1" height="1">
  <p>Your browser does not support iframes.</p>
</iframe>
<!-- ============= modal  ============= -->

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Detalle de visita	</h4>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


