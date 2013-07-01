<!-- / Contenido -->
<div class="container" > 


    <div class="row-fluid">

        <!-- xxxxxxxxxxxxxxxx CREAR META  xxxxxxxxxxxxxxxx -->


        <button type="button" class="btn " data-toggle="collapse" data-target="#meta_div">
            <i class="icon-plus"></i> Nueva meta
        </button>
        <br/>
        <br/>
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
                            <option value="{nombre}">{nombre}</option>
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
<!-- xxxxxxxxxxxxxxxx METAS  xxxxxxxxxxxxxxxx -->
  
<?php
$i=0;
foreach($metas as $meta){
$i++;
if(!($i%2==0))
    echo '<div class="row-fluid " >';
?>

       <div class="span6 <?php echo $meta['status_class'];?>">  
        <div  class="row-fluid" >
            <div class="span12 ">  
                <h3><?php echo $meta['proyecto_nombre'];?><span class="pull-right"><?php echo $meta['cumplidas_count'];?>/<?php echo $meta['cantidad'];?></span></h3>
            </div>
        </div>
        <div  class="row-fluid" >
            <div class="span8"> 
                <ul class="unstyled">
                <li><i class="icon-calendar" ></i> Período: <?php echo $meta['desde'];?></li>
                <li><i class="icon-eye-open" ></i> Estado: <span class="label <?php echo $meta['label_class'];?>"><i class="<?php echo $meta['status_icon_class'];?>"></i>&nbsp;<?php echo $meta['status'];?></span></li>
                <li><i class="icon-user" ></i> Autor: <?php echo $meta['owner'];?></li>
                <li><i class="icon-flag" ></i> Genia: <?php echo $meta['genia'];?></li>
                </ul>

            </div>
            <div class="span4">         
               <?php 
               if($rol=='coordinador'&& $meta['status_class']=='well status_open'){
                    echo '<a class="btn btn-primary pull-right" href="'.$meta['url_case'].'" targe="_blank"  type="button" style="margin-top:12px;t"><i class="icon-thumbs-up-alt"></i> Aprobar</a>';
               }
               ?>             
            </div>
        </div> 
        <div  class="row-fluid" >
            <textarea rows="3" class="span12 "><?php echo $meta['observaciones'];?></textarea>
        </div>
     </div>
 
<?php
if(($i%2==0))
    echo '</div>';
}//each
?>
<!-- ============= metas  ============= -->

 
</div> 

