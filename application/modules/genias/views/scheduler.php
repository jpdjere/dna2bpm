<!-- / Breadcrumbs -->
<ul class="breadcrumb navbar-static-top">
  <li><a href="{module_url}">Dashboard</a> <span class="divider">/</span></li>
  <li><a href="#">Agenda</a> <span class="divider">/</span></li>
</ul>
<!-- / Contenido -->
<div  class="container" >
<div  class="row" >
<div id="detalle" class="span4" >
    <form >
    <select name="proyecto" class="input-block-level">
    <option selected="selected" value="">---- Seleccione un proyecto ----</option>
    {projects}
    <option value="{id}">{name}</option>
    {/projects}
    </select>
    <label>Title</label>
    <input type="text" name="title" placeholder="Title" class="input-block-level"/>
    <label>Start Date</label>
    <input type="text" name="start" placeholder="Start Date" class="input-block-level datepicker" />
    <label>End Date</label>
    <input type="text" name="end" placeholder="End Date" class="input-block-level datepicker" />
    <label>Detail</label>
    <textarea type="text" name="detail" placeholder="Detail" class="input-block-level" ></textarea>
    <a  href="#" class="btn btn-primary input-block-level" id="bt_submit">Agregar</a>
    </form>
</div>
<div id="calendar" class="span8" ></div>
</div>

</div>
<div id='loading' style='display:none'>loading...</div>

