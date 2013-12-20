<div class="row-fluid test" id="barra_user" > 
    <ul class="breadcrumb" style="margin-bottom:0px;padding-bottom:0px" >
        <button type="button" class="btn hide_offline" data-toggle="collapse" data-target="#file_div">
            <i class="icon-plus"></i>  Seleccionar Archivos a Procesar
        </button> 
        {if sgr_period}
        <button type="button" id="no_movement" class="no_movement" value="{sgr_period}">
            <i class="icon-plus"></i>  Asociar el periodo {sgr_period} a "Sin Movimientos"
        </button>
        {/if}
        <li class="pull-right perfil">
            <span id="status"></span>{sgr_nombre}
            <i class="{rol_icono}"></i>  <a   href="{base_url}user/logout"> {username}</a> [{rol}]
        </li>
    </ul>
</div>

{if message}
<div class="alert alert-{success}" id="{success}">   
    {message}
</div>
{/if}


<!-- ==== Contenido ==== -->
<div class="container" > 
    <div class="row-fluid">
        <!-- FILE UPLOAD -->
        <div id="file_div" class="collapse out no-transition">
            <form action="{module_url}" method="POST" enctype="multipart/form-data" class="well" />                   
            <input type="file" name="userfile" multiple="multiple" />
            <input type="hidden" name="sgr" value="{sgr_id_encode}" />
            <input type="hidden" name="anexo" value="{anexo}" />
            <input type="submit" name="submit" value="Upload" class="btn btn-success" />
            </form>

        </div> 
    </div>

    {if sgr_period} 
    <!-- -->
    {else}
    <!-- PERIOD -->
    <div class="row-fluid">
        <div id="meta_div_2">
            <form  method="post" class="well">
                <div  class="row-fluid " >
                    <div class="span6">                        
                        <label>{if rectifica} Rectificar {/if}Anexo</label>
                        <input type="text"  placeholder="{anexo_title}"  class="input-block-level" disabled="true"/>
                        {if rectifica}
                        <div>
                            <label>Rectificacion de {post_period}/ Ingrese el Motivo</label>
                            <select name="rectificar" id="rectificar" class="input-block-level">
                                <option value=1>Errores en el sistema y/o procesamiento del archivo</option>
                                <option value=2>Error en la informacion sumistrada</option>
                                <option value=3>Otros motivos</option>
                            </select>
                        </div>                       
                        {/if}
                    </div>

                    <div class="span6">
                        <div>
                            <label>Seleccione el Período a {if rectifica} Rectificar {else} Informar {/if} </label>
                            <div data-date-viewMode="months" data-date-minViewMode="months" data-date-format="mm-yyyy" data-date="" id="dp3" class="input-append date dp">
                                <input type="text" name="input_period" readonly="" {if post_period} value="{post_period}" {/if} class="input-block-level">
                                       {if rectifica}{else}<span class="add-on"><i class="icon-calendar"></i></span>{/if}
                            </div>
                        </div>
                        {if rectifica}     
                        <input type="hidden" name="rectifica" value="{anexo}" />
                        <div id="others"><label>Otros Motivos</label>
                            <textarea name="observaciones" placeholder="..." class="input-block-level" ></textarea>                        
                        </div>
                        {/if}
                    </div>
                </div>
                <div  class="row-fluid">
                    <div class="span12">
                        <input type="hidden" name="anexo" value="{anexo}" />
                        <button name="submit_period" class="btn btn-block btn-primary hide_offline" type="submit" id="bt_save"><i class="icon-save"></i>{if rectifica} Rectificar {else} Activar{/if} Periodo</button>  
                    </div>
                </div>
            </form>
        </div>
    </div> 
    {/if}


    <!-- TABS -->

    <ul class="nav nav-tabs" id="dashboard_tab1">
        <li class="active"><a href="#tab_pending" data-toggle="tab">Archivos a Procesar</a></li>        
        <li><a href="#tab_processed" data-toggle="tab">Procesados</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="tab_pending">  
            <div class="alert {resumen_class}" id="{_id}">                
                <h3>{anexo_title} </h3>
                <ol>
                    {files_list}
                </ol>
            </div> 
        </div>  

        <div id="tab_processed" class="tab-pane">            
            <div class="alert {resumen_class}" id="{_id}">                
                <h3>{anexo_title} </h3>
                <div  class="row-fluid">  
                    {processed_list}
                </div>

            </div> 
        </div>
    </div>

</div>