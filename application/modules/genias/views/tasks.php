<!-- Submenu / Breadcrumbs -->
<div class="row-fluid " >
    <ul class="breadcrumb" style="margin-bottom:0px;padding-bottom:0px" >
         <li ></li> 
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

<div class="container">  
<div class="row">
<!-- xxxxxxxxxx Contenido xxxxxxxxxx-->   
<div class="accordion" id="proyectos">
<!-- Item1 -->

<?php
//var_dump($tasks);
foreach($tasks as $project){
?>
<div class="accordion-group">
<div class="accordion-heading">
<a class="accordion-toggle btn" data-toggle="collapse" data-parent="#proyectos" href="#collapse<?php echo $project['id']?>">
<?php echo $project['name']?>
</a>
</div>

<div id="collapse<?php echo $project['id']?>" class="accordion-body collapse in">
    <ul class="accordion-inner unstyled task_list ">
        <?php foreach($project['items'] as $task){?>
            <?php if($task['finalizada']==0){?>
            <li ><i class="icon-calendar" style="color:#0088CC"></i> <?php echo $task['dia']?> <i class="icon-time" style="color:#0088CC"></i> <?php echo $task['hora']?>:<?php echo $task['minutos']?> <i class="icon-user" style="color:#0088CC"></i> <?php echo $task['autor']?> <a href="{module_url}form/<?php echo $task['id']?>"><?php echo $task['title']?></a> <?php echo $task['detail']?></li>
            <?php }?>
        <?php }?>
    </ul>
</div>
</div>
<?php
}
?>
<di
<!-- --------- Detalle ----------->
</div></div>
<!-- --------- Contenido ----------->



</div>


