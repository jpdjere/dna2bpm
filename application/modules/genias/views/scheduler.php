<!-- / Breadcrumbs -->
<ul class="breadcrumb navbar-static-top">
  <li><a href="{module_url}">Dashboard</a> <span class="divider">/</span></li>
  <li><a href="#">Agenda</a> <span class="divider">/</span></li>
</ul>
<!-- / Contenido -->
<div  class="container" >
<div  class="row" >
<div id="detalle" class="span4" >
    <form method="post">
    <input name="id" value="{id}" type="hidden"/>
    <select name="proyecto" class="input-block-level">
    <option selected="selected" value="">---- Seleccione un proyecto ----</option>
    {projects}
    <option value="{id}">{name}</option>
    {/projects}
    </select>
    <input type="text" name="title" placeholder="Title" class="input-block-level" />
    <!-- Fechas -->
    <div class="input-prepend">
    <span class="add-on"><i class="icon-calendar"></i></span>
    <input class="span2 datepicker"  type="text" placeholder="Fecha" name="dia">
    </div>

    <select class="span1" name="hora">
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
:    
    <select class="span1" name="minutos">
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

    <textarea type="text" name="detail" placeholder="Detail" class="input-block-level" ></textarea>
    <label class="checkbox">
    <input type="checkbox" value="1" name="finalizada" >
    Tarea finalizada
    </label>
    <a class="btn btn-block  btn-primary disabled" disabled="disabled" id="bt_form" href="#"><i class="icon-tasks"></i> Cargar formulario</a>
    <a class="btn btn-block btn-primary disabled" disabled="disabled" id="bt_delete" href="#"><i class="icon-trash"></i> Eliminar tarea</a>
    <button class="btn btn-block btn-primary " type="button" id="bt_clear"><i class="icon-plus-sign"></i>  Nueva tarea</button>
    <button class="btn btn-block btn-primary " type="submit" id="bt_save"><i class="icon-save"></i>  Guardar</button>

    </form>
</div>
<div id="calendar" class="span8" ></div>
</div>

</div>
<div id='loading' style='display:none'>loading...</div>

