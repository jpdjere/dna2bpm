<!-- ================= SUB BARRA   ================= -->
<div class="container-fluid" style="padding-bottom:15px" > 
    <div class="row-fluid">
    <div class="col-md-12">
		<ul class="nav nav-pills" style="margin-bottom: 8px">
		 <li > <button type="button" id="bt_clear" class="btn btn-primary btn-sm hide_offline" data-toggle="modal" data-target="#myModal" ><i class="fa fa-plus-circle"></i> Nueva tarea</button></li>	
		</ul>
	</div>			
	 </div> 
 </div>
 

<!-- ================= CONTENIDO   ================= -->
<div  class="container" >
<div id="calendar" class="col-md-12" ></div>
<!-- $calendario -->
</div>
<div id='loading' style='display:none'>loading...</div>


<!--  ==================== CARGA ====================-->

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Mi tarea</h4>
      </div>
      <div class="modal-body">
       <!--  =================  FORM ================ -->
       <form method="post">
        <input name="id" value="{id}" type="hidden"/>
        <!-- proyecto -->
         <div class="form-group">

	            <select name="proyecto" class="form-control">
	            {projects}
	            <option value="{id}">{name}</option>
	            {/projects}
	            </select>

		  </div>
		  
         <!-- titulo -->
         <div class="form-group">
	             <input type="text" name="title" placeholder="Title" class="form-control" >
		  </div>
		  
         <!-- fecha -->
         <div class="row form-group">
             <div class="col-md-6">
	            <div data-date-format="dd-mm-yyyy" data-date=""  class=" input-prepend date pull-right dp" id="dp3" >
					<div class="input-group">
					  <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
					  <input type="text" placeholder="Período" name="dia" readonly="" value=""  class="form-control" >
					</div>
				</div> 
             </div>
             <div class="col-md-3">
              <select  name="hora" class="form-control">
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
             <div class="col-md-3">
	            <select  name="minutos" class="form-control" >
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

         <!-- Detalle -->
         <div class="form-group">
             <textarea type="text" name="detail" placeholder="Detail" class="form-control" ></textarea>
         </div>
         
         <!-- Detalle -->
         <div class="form-group">
             <label>Finalizada
             <input type="checkbox"  name="finalizada"> 
             </label>
         </div>
          



    
      </div>
     	<div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-default" id="bt_form"><i class="fa fa-tasks"></i> Cargar Formulario</button>
        <button type="button" class="btn btn-danger" id="bt_delete"><i class="fa fa-trash-o"></i> Eliminar</button>
        <button type="submit" class="btn btn-primary" id="bt_save"><i class="fa fa-save"></i> Guardar</button> 
      </div>
      </form>
      <!--  -----------------  FORM ---------------- -->
    </div>
  </div>
</div>







