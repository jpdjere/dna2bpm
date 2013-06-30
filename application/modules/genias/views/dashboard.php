<!-- / Contenido -->
<div class="container" > 
    
    
<div class="row-fluid">
    
 <!-- xxxxxxxxxxxxxxxx CREAR META  xxxxxxxxxxxxxxxx -->

 

    

<form id="form_goals" method="post">
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
foreach($metas as $meta){
?>
 <div  class="row-fluid " >
       <div class="span12 <?php echo $meta['status_class'];?>">  
        <div  class="row-fluid" >
            <div class="span12 ">  
                <h3><?php echo $meta['proyecto_nombre'];?><span class="pull-right"><?php echo $meta['cumplidas_count'];?>/<?php echo $meta['cantidad'];?></span></h3>
            </div>
        </div>
        <div  class="row-fluid" >
            <div class="span4"> 
                <ul class="unstyled">
                <li><i class="icon-calendar" ></i> Período: <?php echo $meta['desde'];?></li>
                <li><i class="icon-eye-open" ></i> Estado: <span class="label <?php echo $meta['label_class'];?>"><i class="<?php echo $meta['status_icon_class'];?>"></i>&nbsp;<?php echo $meta['status'];?></span></li>
                <li><i class="icon-user" ></i> Autor: <?php echo $meta['owner'];?></li>
                <li><i class="icon-flag" ></i> Genia: <?php echo $meta['genia'];?></li>
                </ul>
            </div>
            <div class="span8"> 
                <div class="observaciones" >
                <?php echo $meta['observaciones'];?>
                </div>
                
               <?php 
               if($rol=='coordinador'&& $meta['status_class']=='well status_open'){
                    echo '<a class="btn btn-primary pull-right" href="{url_case}" targe="_blank" {case_button_state} type="button" style="margin-top:12px;t"><i class="icon-thumbs-up-alt"></i> Aprobar</a>';
               }
               ?>
                
            </div>
        </div> 
     </div>
 </div>
<?php
}//each
?>
<!-- ============= metas  ============= -->

 
</div> 

