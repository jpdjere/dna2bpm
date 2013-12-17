<div class="row-fluid test" id="barra_user" > 
    <ul class="breadcrumb" style="margin-bottom:0px;padding-bottom:0px" >
        <button type="button" class="btn hide_offline" data-toggle="collapse" data-target="#meta_div">
            <i class="icon-plus"></i>  Importar Archivo a Procesar
        </button> 
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
        <!-- xxxxxxxxxxxxxxxx CREAR   xxxxxxxxxxxxxxxx -->
        <div id="meta_div" class="collapse out no-transition">

            <!-- FILE UPLOAD -->

            <form action="{module_url}" method="POST" enctype="multipart/form-data" class="well" />                   
            <input type="file" name="userfile" multiple="multiple" />
            <input type="hidden" name="sgr" value="{sgr_id_encode}" />
            <input type="hidden" name="anexo" value="{anexo}" />
            <input type="submit" name="submit" value="Upload" class="btn btn-success" />
            </form>

        </div> 
    </div> 

    <ul class="nav nav-tabs" id="dashboard_tab1">
        <li class="active"><a href="#tab_pending" data-toggle="tab">Archivos a Procesar</a></li>        
        <li><a href="#tab_processed" data-toggle="tab">Procesados</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="tab_pending">            

            {if sgr_period} 
            <!-- -->
            {else}           

            <form class="well" method="post" />
            <div  class="row-fluid">
                <div class="span6">
                    <div class="">
                        <label>Seleccione el Período a informar {sgr_period}</label>

                        <div class="input-append">
                            <input type="hidden" name="desde" placeholder="Período" class="input-block-level "/>
                        </div>

                        <div data-date-viewMode="months" data-date-minViewMode="months" data-date-format="mm-yyyy" data-date="" id="dp3" class="input-append date dp">
                            <input type="text" name="input_period" readonly="" value=""  class="input-block-level">
                            <span class="add-on"><i class="icon-calendar"></i></span>
                        </div>
                    </div>
                    <!--
                    <label>Observaciones</label>
                    <textarea name="observaciones" placeholder="Observaciones"  class="input-block-level" ></textarea>
                    -->

                </div>
            </div>


            <div  class="row-fluid">
                <div class="span12">
                    <button name="submit_period" class="btn btn-block btn-primary hide_offline" type="submit" id="bt_save"><i class="icon-save"></i>  Agregar</button>  
                </div>

            </div>
            </form>
            {/if}


            <div class="alert {resumen_class}" id="{_id}">                
                <h3>{anexo_title} </h3>
                <ol>
                    {files_list}                   
                </ol>
            </div> 
        </div>  

        <div id="tab_processed" class="tab-pane">
            <ol>
                {processed_list}
            </ol>
        </div>
    </div>

</div>