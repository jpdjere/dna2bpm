<!-- Submenu / Breadcrumbs -->
<div class="row-fluid " id="barra_user" >
    <ul class="breadcrumb" style="margin-bottom:0px;padding-bottom:0px" >
         <li ></li> 
          <li class="pull-right perfil">
              <a title="{usermail}">{username}</a> <i class="icon-angle-right"></i> <i class="{rol_icono}"></i> {rol}
          </li>
    </ul>

</div>
<!-- / Contenido -->
<div  class="row-fluid container" >
<div id="detalle" class="span4" >
    <form method="post">
        <input name="id" value="{id}" type="hidden"/>
         <!-- Fechas -->
        <div class="controls">
            <select name="proyecto" class="span12">
            {projects}
            <option value="{id}">{name}</option>
            {/projects}
            </select>
        </div>
        <!-- titulo -->
        <div class="controls">
             <input type="text" name="title" placeholder="Title" class="span12" />
        </div>
        

        
        <!-- Fecha -->
        <div class="row-fluid">
            
            <div class="span6">
        <div  data-date-format="dd-mm-yyyy" data-date="" id="dp3" class="input-append date">
            <input type="text" name="dia" readonly="" value=""  class="span10">
            <span class="add-on"><i class="icon-calendar"></i></span>
        </div>
<!--            <div class="input-prepend input-append">
             <span class="add-on "><i class="icon-calendar"></i></span>
                <input  type="text"  name="dia" class="datepicker input-small" placeholder="Fecha">
                <span class="add-on "><i class="icon-time"></i></span>
            </div>-->
            </div>
            <div class="span3">
            <select  name="hora" class="input-block-level">
            <option>01</option>
            <option>02</option>
            <option>03</option>
            <option>04</option>
            <option>05</option>
            <option>06</option>
            <option>07</option>
            <option>08</option>
            <option>09</option>
            <option>10</option>
            <option>11</option>
            <option selected="selected">12</option>
            <option>13</option>
            <option>14</option>
            <option>15</option>
            <option>16</option>
            <option>17</option>
            <option>18</option>
            <option>19</option>
            <option>20</option>
            <option>21</option>
            <option>22</option>
            <option>23</option>
            <option>24</option>
            </select>
            </div>
            <div class="span3">
            <select  name="minutos" class="input-block-level" >
            <option selected="selected" >00</option>
            <option>05</option>
            <option>10</option>
            <option>15</option>
            <option>20</option>
            <option>25</option>
            <option>30</option>
            <option>35</option>
            <option>40</option>
            <option>45</option>
            <option>50</option>
            <option>55</option>
            </select>
            </div>
        </div>

    
    <textarea type="text" name="detail" placeholder="Detail" class="input-block-level" ></textarea>
<label class="checkbox">
<input type="checkbox" name="finalizada"> Finalizada
</label>
    <a class="btn btn-block  btn-primary disabled" id="bt_form" href="#"><i class="icon-tasks"></i> Cargar formulario</a>
    <a class="btn btn-block btn-primary disabled"  id="bt_delete" href="#"><i class="icon-trash"></i> Eliminar tarea</a>
    <button class="btn btn-block btn-primary " type="button" id="bt_clear"><i class="icon-plus-sign"></i>  Nueva tarea</button>
    <button class="btn btn-block btn-primary " type="submit" id="bt_save"><i class="icon-save"></i>  Guardar</button>

    </form>
</div>
<!-- ^CALENDARIO -->
<div id="calendar" class="span8" ></div>
<!-- $calendario -->


</div>
<div id='loading' style='display:none'>loading...</div>

