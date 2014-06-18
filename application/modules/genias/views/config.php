<!-- / Breadcrumbs -->
<ul class="breadcrumb navbar-static-top">
  <li><a href="{module_url}">Dashboard</a> <span class="divider">/</span></li>
  <li><a href="#">Configuración</a> <span class="divider">/</span></li>
</ul>
<!-- / Contenido -->
<div class="container">  
<!-- xxxxxxxxxx Contenido xxxxxxxxxx-->   
<div class="accordion">
<!-- Proyecto -->
<div class="accordion-group">
<div class="accordion-heading">
<a class="accordion-toggle btn" data-toggle="collapse" data-parent="#proyectos" href="#collapse1">
Proyectos
</a>
</div>
<!-- Collaps -->
<div id="collapse1" class="accordion-body collapse in">
<form name="form_projects" method="post">
<div class="accordion-inner">
{projects}
    <div class="form-inline">
    <input type="text" class="input-xlarge" placeholder="Nombre" value="{name}" name="name">
    <input type="text" class="input-small" placeholder="ID" value="{id}" name="id">
    </div>
{/projects}
<div class="form-inline">

<a href="#" class="btn btn-primary" id="save_project"><i class="icon-ok"></i> Guardar</a>
</div>
<div class="alert hide">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  
</div>

</div>
</form>

</div>
</div>

<!-- --------- Detalle ----------->
</div>
<!-- --------- Contenido -----------> 
<!--  Template nuevo elemento -->
<div id="dummy" class="hidden">
<div class="form-inline  " >
<input type="text" class="input-xlarge" placeholder="Nombre"  name="nombre">
<input type="text" class="input-small" placeholder="ID"  name="id">
<button type="submit" class="btn btn-secondary btn-remove"><i class="icon-remove"></i></button>
</div>   
</div>