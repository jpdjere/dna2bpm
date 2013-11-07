


<div class="row-fluid test" id="barra_user" >
    <ul class="breadcrumb" style="margin-bottom:0px;padding-bottom:0px" >
        <button type="button" class="btn hide_offline" data-toggle="collapse" data-target="#meta_div">
            <i class="icon-plus"></i> Importar Anexo
        </button>
        <li class="pull-right perfil">
            <span id="status"></span>
            <a title="{usermail}">{username}</a> <i class="icon-angle-right"></i> <i class="{rol_icono}"></i> {rol}
        </li>
    </ul>

</div>
<!-- ==== Contenido ==== -->
<div class="container" > 



    

<div class="row-fluid">
    <!-- xxxxxxxxxxxxxxxx CREAR   xxxxxxxxxxxxxxxx -->
    <div id="meta_div" class="collapse out no-transition">
        
        <!-- FILE UPLOAD -->
    <form action="{module_url}" method="POST" enctype="multipart/form-data" class="well" />
    Subir Anexo:<br />       
    <input type="file" name="userfile" multiple="multiple" />
    <input type="submit" name="submit" value="Upload" class="btn btn-success" />
</form>

{if isset($uploaded_file)}
{foreach from=$uploaded_file key=name item=value}
{$name} : {$value}
<br />
{/foreach}
{/if}
        
        <form id="form_goals" method="post" class="well">
            <div  class="row-fluid">
                <div class="span6">
                    <div class="">
                        <label>Seleccione el Período</label>

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
                <div class="span12">

                    <button class="btn btn-block btn-primary hide_offline" type="submit" id="bt_save"><i class="icon-save"></i>  Agregar</button>  
                </div> 

            </div> 

        </form>  



    </div> 
</div> 


<!-- ==== RESUMEN ==== -->


<ul class="nav nav-tabs" id="dashboard_tab1">
    <li class="active"><a href="#tab_anexos" data-toggle="tab">Anexos</a></li>        
    <li class=""><a href="#tab-{_id}" data-toggle="tab">{nombre}</a></li>

</ul>

<div class="tab-content">
    <div class="tab-pane active" id="tab_resumen">                     
        <pre>{virtual_root} / {path_in_url}</pre>
        <ul>
            <?php
            $prefix = $controller . '/' . $method . '/' . $path_in_url;
            if (!empty($dirs))
                foreach ($dirs as $dir)
                    echo anchor($prefix . $dir['name'], $dir['name']) . '<br>';

            if (!empty($files))
                foreach ($files as $file)
                //echo anchor($prefix.$file['name'], $file['name']).'<br>';
                    echo '<li><a href="../anexos_sgr/' . $file['name'] . '">' . $file['name'] . '</a></li>';
            ?>
        </ul>
    </div>       
</div>


</div>