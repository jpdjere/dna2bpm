<!-- / Breadcrumbs -->
<div class="row-fluid container" >
    <ul class="breadcrumb"  >
          
          <li><a href="{module_url}">Dashboard</a> <span class="divider">/</span></li>
          <li><span class="divider">/</span></li>
          <li><a href="#">Tareas</a> <span class="divider">/</span></li>
          <!-- / Perfil -->
          <li class="pull-right perfil"><a title="{usermail}">{username}</a> <i class="icon-angle-right"></i> <i class="{rol_icono}"></i> {rol}</li>
    </ul>

</div>



<!-- -->
<div class="container">  
<div class="row">
<!-- xxxxxxxxxx Contenido xxxxxxxxxx-->   
<div class="accordion" id="proyectos">
<!-- Item1 -->
{tasks}
<div class="accordion-group">
<div class="accordion-heading">
<a class="accordion-toggle btn" data-toggle="collapse" data-parent="#proyectos" href="#collapse{id}">
{name}
</a>
</div>

<div id="collapse{id}" class="accordion-body collapse">
    <ul class="accordion-inner unstyled task_list ">
        {items}
        <li><a href="#">{title}</a><i class="icon-calendar"></i>{dia}<i class="icon-time"></i>{hora}:{minutos}</li>
        {/items}
    </ul>
</div>
</div>
{/tasks}
<!-- --------- Detalle ----------->
</div></div>
<!-- --------- Contenido ----------->


</div>


