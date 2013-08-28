<div class="row-fluid test" >
    <ul class="breadcrumb" style="margin-bottom:0px;padding-bottom:0px" >
    <button type="button" class="btn " data-toggle="collapse" data-target="#meta_div">
        <i class="icon-plus"></i> Nueva meta
    </button>
          <li class="pull-right perfil">
              <a title="{usermail}">{username}</a> <i class="icon-angle-right"></i> <i class="{rol_icono}"></i> {rol}
          </li>
    </ul>
    <ul class="breadcrumb breadcrumb-genias" style="padding-top:0px">
        <li ></li>
        
        {genias}  
        <li class="pull-right "><span class="divider">/</span></li>
        <li class="pull-right">{nombre}</li>
        {/genias}

    </ul> 
</div>
<!-- ==== Contenido ==== -->
<div class="container" > 
    
    <div class="row-fluid">
        <!-- xxxxxxxxxxxxxxxx CREAR META  xxxxxxxxxxxxxxxx -->
        <div id="meta_div" class="collapse out no-transition">
            <form id="form_goals" method="post" class="well">
                <div  class="row-fluid">
                    <div class="span6">
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
                    <div class="span6">
                        <div class="">
                            <label>Período</label>

                            <!--<div class="input-append">
                            <input type="text" name="desde" placeholder="Período"   class="input-block-level "/>
                            </div>
                            -->
                            <div data-date-viewMode="months" data-date-minViewMode="months" data-date-format="mm-yyyy" data-date="" id="dp3" class="input-append date">
                                <input type="text" name="desde" readonly="" value=""  class="input-block-level">
                                <span class="add-on"><i class="icon-calendar"></i></span>
                            </div>
                        </div>

                        <label>Observaciones</label>
                        <textarea name="observaciones" placeholder="Observaciones"  class="input-block-level" ></textarea>

                    </div>
                </div>
                <div  class="row-fluid">
                    <div class="span12">

                        <button class="btn btn-block btn-primary " type="submit" id="bt_save"><i class="icon-save"></i>  Agregar</button>  
                    </div> 

                </div> 

            </form>  



        </div> 
    </div> 
    
    <!-- ==== RESUMEN COORDINADOR==== -->

    {if {rol}=='coordinador'}
        <ul class="nav nav-tabs" id="dashboard_tab1">
        <li class="active"><a href="#tab_resumen" data-toggle="tab">Resumen</a></li>
        {genias} 
        <li class=""><a href="#tab-{_id}" data-toggle="tab">{nombre}</a></li>
        {/genias}
        </ul>

        <div class="tab-content">
            <div class="tab-pane active" id="tab_resumen">
                <div class="alert {resumen_class}" id="{_id}">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Sus genias tienen {goal_cumplidas_total} de {goal_cantidad_total} objetivos cumplidos.</strong> 
                </div> 
            </div>
<!--        {genias} 
            <div class="tab-pane" id="tab-{_id}">
                <div class="alert" id="{_id}">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Sus genias tienen {goal_cumplidas} {/goal_cumplidas}  de {goal_cantidad} objetivos cumplidos. {_id}</strong> 
                
                </div>
            </div> 
        {/genias} -->
        <?php
        foreach($genias as $genia){     
echo <<<BLOC
            <div class="tab-pane" id="tab-{$genia['_id']}">
                <div class="alert" id="{$genia['_id']}">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Sus genias tienen {$goal_cumplidas[(string)$genia['_id']]} de {$goal_cantidad[(string)$genia['_id']]} objetivos cumplidos.</strong> 
                
                </div>
            </div> 
BLOC;
}
        
        ?>
        </div>
    {/if}
       
    




    <!-- xxxxxxxxxxxxxxxx METAS  xxxxxxxxxxxxxxxx -->
    <div class="row">

        {metas}
        <!-- test  -->
        <!-- <div class="span6 {status_class}">  -->
        <div class="span6 meta" data-genia="{genia}"> 
            <input type="hidden" name="metaid" value="{_id}"/>
            <div class="well">
                <!-- Nombre Proyecto -->
                <div class="row-fluid"> 
                    {if {rol}=='coordinador'}
                    <div class="span6">  
                    <select name="metas_proyecto">  
                        {select_project}
                    </select>
                     </div>
                    <div class="span6"> 
                        <h3><span class="pull-right">{cumplidas_count}/ {cantidad} </span></h3>
                    </div>
                    {else}
                    <div class="span12"> 
                    <h3>{proyecto_name}<span class="pull-right">{cumplidas_count}/{cantidad}</span></h3>
                    </div> 
                    {/if}
                </div>

                <div> 
                    <ul class="unstyled">
                        <li>
                            <i class="icon-calendar" ></i> Período:
                            {if {rol}=='coordinador'}                                     
                            <div data-date-viewMode="months" data-date-minViewMode="months" data-date-format="mm-yyyy" data-date=""  class="input-append date dp">
                                <input type="text" name="desde" readonly="" value="{desde_raw}"  class="span1">
                                <span class="add-on"><i class="icon-calendar"></i></span>
                            </div>
                            {else}
                                {desde}  
                            {/if}
                        </li>
                        <li>
                            <i class="icon-eye-open" ></i> Estado: 
                                {if {status} == 'closed'}
                                 <span class="label label-important">Pendiente de revisión</span>
                                {/if}
                                {if {status} == 'open'}
                                 <span class="label label-success">Aprobado</span>
                                {/if}
                                
                         
                                {if {rol}=='coordinador'}
                                   {if {status} == 'closed'}
                                        <button class="aprobar btn btn-mini btn-success" url="{url_case}" type="button">
                                               <i class="icon-thumbs-up-alt"></i> Aprobar
                                       </button>
                                    {/if}
                                {/if}
                        </li>
                        <li>
                            <i class="icon-user" ></i> Autor: {owner}
                        </li>
                        <li>
                            <i class="icon-flag" ></i> Genia: {genia_nombre}
                        </li>
                    </ul>
                </div>

                <div>
                    <textarea rows="3" class="input-block-level" name="observaciones">{observaciones} </textarea>
                </div>
                {if {rol}=='coordinador'}
                    <button class="guardar btn btn-mini btn-success" url="#" type="button">
                            <i class="icon-thumbs-up-alt"></i> Guardar
                    </button>
                    <a class="bt_delete btn btn-mini btn-danger"  type="button">
                            <i class="icon-trash"></i> Eliminar
                    </a>
                {/if}
                
  
            </div>
        </div>
        
        {/metas}
    </div>
    <!-- ============= metas  ============= -->
</div>
