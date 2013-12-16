<!-- Submenu / Breadcrumbs -->
<div class="row-fluid " id="barra_user">
    <ul class="breadcrumb" style="margin-bottom:0px;padding-bottom:0px" >
         <li ></li> 
          <li class="pull-right perfil">
              <span id="status"></span>
              <a title="{usermail}">{username}</a> <i class="fa fa-angle-right"></i> <i class="{rol_icono}"></i> {rol}
          </li>
    </ul>

</div>

<div class="container">  
<div class="row">
    
{projects}
<div class="row-fluid">
     <div class="span9">
         <h3><i class="fa fa-bookmark"></i> {name}</h3>
         
     </div>
    <div class="span3">
        <div data-date-viewMode="months" data-date-minViewMode="months" data-date-format="mm-yyyy" data-date=""  class=" input-prepend date pull-right dp" id="dp{id}">
            <span class="add-on"><i class="fa fa-calendar"></i></span>
            <input type="text" name="visitas_desde" readonly="" value=""  class="" >

        </div> 
     </div>
</div>
<div id="collapse{id}">
<!-- --------- DUMMY ----------->
</div>
{/projects}



</div></div>
<!-- --------- Contenido ----------->






