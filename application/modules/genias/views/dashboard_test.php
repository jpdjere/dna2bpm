<?php

echo "INICIO DASH";


?>

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
        <li class="pull-right "><span class="divider">/</span</li>
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
      
TESTEO DE SISTEMAS SOLO MATILDE
    <!-- xxxxxxxxxxxxxxxx METAS  xxxxxxxxxxxxxxxx -->
    <div class="row">

<?php 
$i=0;
foreach($metas as $meta){
$i++;
if($i==30)break;
?>
        <!-- test  -->
        <!-- <div class="span6 {status_class}">  -->
        <div class="span6 meta" data-genia="<?php echo $meta['genia'];?>"> 
            <input type="hidden" name="metaid" value="<?php echo $meta['_id'];?>"/>
            <div class="well">
                <!-- Nombre Proyecto -->
                <div class="row-fluid"> 
                    <?php if($rol=='coordinador'){?> 
                    <div class="span6">  
                    <select name="metas_proyecto">  
                       <?php echo $meta['select_project'];?>
                    </select>
                     </div>
                    <div class="span6"> 
                        <h3><span class="pull-right"><?php echo $meta['cumplidas_count'];?> / <?php echo $meta['cantidad'];?> </span></h3>
                    </div>
                   <?php }else{?>
                    <div class="span12"> 
                    <h3><?php echo $meta['proyecto_name'];?><span class="pull-right"><?php echo $meta['cumplidas_count'];?> / <?php echo $meta['cantidad'];?></span></h3>
                    </div> 
                    <?php }?>
                </div>

                <div> 
                    <ul class="unstyled">
                        <li>
                            <i class="icon-calendar" ></i> Período:
                            <?php if($rol=='coordinador'){?>                                   
                            <div data-date-viewMode="months" data-date-minViewMode="months" data-date-format="mm-yyyy" data-date=""  class="input-append date dp">
                                <input type="text" name="desde" readonly="" value="<?php echo $meta['desde_raw'];?>"  class="span1">
                                <span class="add-on"><i class="icon-calendar"></i></span>
                            </div>
                            <?php }else{?>
                                <?php echo $meta['desde'];?> 
                           <?php }?>
                        </li>
                        <li>
                            <i class="icon-eye-open" ></i> Estado: 
                            <button class="btn btn-mini <?php echo $meta['label_class'];?>">
                                <i class="<?php echo $meta['status_icon_class'];?>"></i>&nbsp;<?php echo $meta['status'];?>   
                                <!-- Btn Aprobar -->
                            </button>
                               <?php if($rol=='coordinador'){?>
                                <?php if($meta['status_class']=='well status_open'){?>

<!--                                        <button class="aprobar btn btn-mini btn-success" url="{url_case}" type="button">
                                               <i class="icon-thumbs-up-alt"></i> Aprobar
                                       </button>-->
                                        <button class="aprobar btn btn-mini btn-success" url="<?php echo $meta['url_case'];?>" type="button">
                                               <i class="icon-thumbs-up-alt"></i> Aprobar
                                       </button>
                                    <?php }?>
                                <?php }?>
                        </li>
                        <li>
                            <i class="icon-user" ></i> Autor: <?php echo $meta['owner'];?>
                        </li>
                        <li>
                            <i class="icon-flag" ></i> Genia: <?php echo $meta['genia_nombre'];?>
                        </li>
                    </ul>
                </div>

                <div>
                    <textarea rows="3" class="input-block-level" name="observaciones"><?php echo $meta['observaciones'];?> </textarea>
                </div>

                <?php if($rol=='coordinador'){?>
                    <button class="guardar btn btn-mini btn-success" url="#" type="button">
                            <i class="icon-thumbs-up-alt"></i> Guardar
                    </button>
                <?php }?>
                
  
            </div>
        </div>
        
<?php 
}//foreach
?>
    </div>
    <!-- ============= metas  ============= -->
</div>


