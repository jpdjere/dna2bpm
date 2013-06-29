<!-- / Breadcrumbs -->
<div class="row-fluid " >
    <ul class="breadcrumb"  >
          <li><span class="divider">/</span></li>
          <li><a href="{module_url}">Dashboard</a> <span class="divider">/</span></li>
          <li class="pull-right perfil"><a title="{usermail}">{username}</a> <i class="icon-angle-right"></i> <i class="{rol_icono}"></i> {rol}</li>
    </ul>

</div>

<!-- / Contenido -->
<div class="container" > 
    
    
<div class="row-fluid">
    
 <!-- xxxxxxxxxxxxxxxx CREAR META  xxxxxxxxxxxxxxxx -->

 

    

<form id="form_goals">
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
        <a  href="#" class="btn btn-primary input-block-level" >Agregar</a>
            
    </div> 
            
</div> 

</form>  



    

 
 
</div> 
<!-- xxxxxxxxxxxxxxxx METAS  xxxxxxxxxxxxxxxx -->
{metas}
 <div  class="row-fluid " >
       <div class="span12 {status_class}">  
        <div  class="row-fluid" >
            <div class="span12 ">  
                <h3>{proyecto_nombre}<span class="pull-right">{cumplidas}/{cantidad}</span></h3>
            </div>
        </div>
        <div  class="row-fluid" >
            <div class="span4"> 
                <ul class="unstyled">
                <li><i class="icon-calendar" ></i> Período: {desde}</li>
                <li><i class="icon-eye-open" ></i> Estado: <span class="label {label_class}"><i class="{status_icon_class}"></i>&nbsp;{status}</span></li>
                <li><i class="icon-user" ></i> Autor: {owner}</li>
                <li><i class="icon-flag" ></i> Genia: {genia}</li>
                </ul>
            </div>
            <div class="span8"> 
                <div class="observaciones" >

                {observaciones}
                </div>
            </div>
        </div> 
     </div>
 </div>
{/metas}
<!-- ============= metas  ============= -->

 
</div> 

