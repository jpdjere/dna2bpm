<div class="nav-tabs-custom">
    <div id="expid" class="pull-left">
        <div class="alert alert-warning alert-dismissible hidden" role="alert" style="margin-top:10px">
            <strong>PDE</strong> 9011/2014
         </div>
    </div>  

	<!-- Tabs within a box -->
	<ul class="nav nav-tabs pull-right">
                <li class=""><a href="#panel_expedientes" data-toggle="tab"><i class="fa fa-camera"></i> Mostrar expedientes</a></li>
		<li><a href="#panel_manual" data-toggle="tab"><i class="fa fa-keyboard-o"></i> Manual</a></li>
		<li class="active"><a href="#panel_camara" data-toggle="tab"><i	class="fa fa-camera"></i> Con cámara</a></li>


	</ul>
	<div class="tab-content no-padding">
		<!-- ======== CAMARA ======== -->
		<div class="chart tab-pane active" id="panel_camara" style="position: relative; height: auto; padding: 10px;">
			<a  class="btn btn-default" href="#" id="bt_cam_info" role="button"><i class="fa fa-info-circle"></i> Información Expediente</a> 
			<a  class="btn btn-default showifcode disabled" href="#" id="bt_cam_checkin" role="button"><i	class="fa fa-check-circle"></i> Check-in</a>
			<a  class="btn btn-default showifcode disabled" href="#" id="bt_cam_assign" role="button"><i class="fa fa-user"></i> Asignar Usuario</a>
		</div>
		<!-- ======== MANUAL ======== -->
		<div class="chart tab-pane" id="panel_manual"
			style="position: relative; height: auto; padding: 10px">
			<form role="form">
				<!--  Tipo -->
				<div class="form-group">
					<div class="row">
						<div class="col-lg-12">
							<div class="input-group">
								<span class="input-group-addon">TIPO</span> <select
									class="form-control" name="type" id="type">
									<option val="PDE">PDE</option>
									<option val="PP">PP</option>
									<option val="PFI">PFI</option>
								</select>
							</div>
						</div>
					</div>
				</div>
				<!--  Tipo -->
				<div class="form-group">
					<div class="row">
						<div class="col-lg-12">
							<div class="input-group">
								<input id="code" type="text" name="code" placeholder="2234/2012"
									class="form-control"> <span class="input-group-btn">
									<button class="btn btn-default" id="btn_seach" type="button">
										<i class="fa fa-search"></i>
									</button>
								</span>
							</div>
						</div>
					</div>
				</div>
				<!-- Buttons -->
				<div class="form-group">
					<div class="row">
						<div class="col-lg-12">
							<button type="button" class="btn btn-default showifcode disabled" id="btn_claim">
								<i class="fa fa-check-circle"></i> Check-In
							</button>
							<button type="button" class="btn btn-default showifcode disabled" id="btn_assign_to">
								<i class="fa fa-user"></i> Assignar Usuario
							</button>
							<button type="button" class="btn btn-default  " id="btn_gencode">
								<i class="fa fa-qrcode"></i> Generar Código
							</button>

						</div>
					</div>
				</div>
				<!-- ./form  -->
			</form>
		</div>
                <!-- ======== Expedientes ======== -->
		<div class="chart tab-pane" id="panel_expedientes"   style="position: relative; height: auto; padding: 10px">
                       <form action="{module_url}show_objects" class="form">
	 		<!--  Grupo -->
				<div class="form-group">
					<div class="row">
						<div class="col-lg-12">
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-users"></i> GRUPO</span>
								<select class="form-control" name="group" id="group_select">
			                        {groups}
			                        <option value="{idgroup}">
			                            {name}
			                        </option>
			                        {/groups}
								</select>
							</div>
						</div>
					</div>
				</div>
				<!--  usuarios -->
				<div class="form-group">
					<div class="row">
						<div class="col-lg-12">
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-user"></i> USUARIO</span>
								<select class="form-control" name="user" id="user_select">
			                        {users}
			                        <option value="idu">
			                            {name} {lastname}
			                        </option>
			                        {/users}
								</select>
							</div>
						</div>
					</div>
				</div>
				<input id="data" type="hidden" value="{data}"/>
				<a id="btn_showobjects" class="btn btn-primary">Mostrar</a>
	 </form>  
                </div>
		<!-- ========  -->
	</div>
</div>
<!-- /.nav-tabs-custom -->

<!-- ======== EXPEDIENTES ======== -->
<!--<div class="box box-info">
	<div class="box-header">
		<i class="fa fa-folder-open"></i>
		<div class="box-title">Mostrar Expedientes</div>
	</div>
	 /.box-header 
	<div class="box-body ">
	 

	</div>
	 /.box-body 
</div>
 /.box -->

<!-- ======== DUMMY ======== -->
<div id="result" >{result}</div>

<!-- ======== Mycam ======== -->
<div class="modal fade" id="mycam" tabindex="-1" role="dialog"  aria-hidden="true" >
  <div class="modal-dialog modal-sm" style="width:400px;margin-left:-200px¨">
    <div class="modal-content" >
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="exampleModalLabel">{title}</h4>
      </div>
      <div class="modal-body">
            <div   id="reader" style="width:350px;height:262px;background-color: #ccc"></div>          
      </div>
      <div class="modal-footer"  style="padding-bottom:0px">
                    <div class="alert alert-warning" role="alert"  >
                    <i class="fa fa-spinner fa-spin"></i><span id="reader_status"></span>
                    </div>
      </div>
      <div class="modal-footer" style="margin-top:0px">          
                 <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- ======== MycQR ======== -->
<div class="modal fade" id="myQR" tabindex="-1" role="dialog"  aria-hidden="true" >
  <div class="modal-dialog modal-sm" style="width:400px;margin-left:-200px¨">
    <div class="modal-content" >
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <button type="button" class="pull-right btn btn-default btn-sm btn-flat bt-print" style="margin-right:10px"><i class="fa fa-print"></i><span class="sr-only">Print</button></a>
      
        <h4 class="modal-title" id="exampleModalLabel">{title}</h4>
      </div>
      <div class="modal-body ">
                      
      </div>
    </div>
  </div>
</div>






