<!-- / Breadcrumbs -->
<div class="row-fluid " style="background-color:#f5f5f5;">  
<div class="span12" style="padding: 3px">
<ul class="breadcrumb navbar-static-top pull-left"  >
  <li><a href="{module_url}">Dashboard</a> <span class="divider">/</span></li>
  <li><a href="#">Tareas</a> <span class="divider">/</span></li>
</ul>
<a class="btn pull-right"  type="button" href="{base_url}/user/logout"><i class="icon-off"></i> Salir</a>
</div>
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


