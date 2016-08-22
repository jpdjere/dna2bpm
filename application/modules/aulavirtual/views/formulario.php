<form id="form_profile" class="form-horizontal" action="{base_url}aulavirtual/process" enctype="multipart/form-data" method="POST">
 <input type="hidden" name="idu" value="{idu}">

 <div class="col-md-12 rt20">
      <h4><b>Datos del Representante de la Empresa</b></h4>
      <hr style="margin-top: 5px; margin-bottom: 30px">
    </div>
 
   <!--  ==== NAME==== -->
  <div class="form-group">
    <label class="col-sm-2 control-label">Nombre</label>
    <div class="col-sm-10">
      <input type="text" name="name" id="name" value="{name}" class="form-control" required>
    </div>
  </div>

  <!--  ==== LASTNAME ==== -->
  <div class="form-group rt20">
    <label class="col-sm-2 control-label">Apellido</label>
    <div class="col-sm-10">
      <input type="text" name="lastname" id="lastname" value="{lastname}" class="form-control" required>
    </div>
  </div>
  
     <!--  ==== EMAIL ==== -->
  <div class="form-group rt20">
    <label class="col-sm-2 control-label">Mail del dueño y/o empleado que se inscribirá al/los cursos</label>
    <div class="col-sm-10">
      <input type="text" id="email" name="email" value="{email}" class="form-control">
    </div>
  </div>
  
  <br />
  
   <div class="col-md-12 rt20">
      <h4><b>Datos Generales de la Empresa</b></h4>
      <hr style="margin-top: 5px; margin-bottom: 30px">
    </div>
  
   <!--  ==== CUIT de la EMPRESA ==== -->
  <div class="form-group rt20">
    <label class="col-sm-2 control-label">CUIT de la Empresa</label>
    <div class="col-sm-10">
      <input type="text" name="cuit" id="cuit" value="{cuit}" class="form-control" required>
    </div>
  </div>  
 
   <!--  ==== DOMICILIO ==== -->
  <div class="form-group rt20">
    <label class="col-sm-2 control-label">Domicilio Real</label>
    <div class="col-sm-10">
      <input type="text" name="address" id="address" value="{address}" class="form-control" required>
    </div>
  </div>
  
  <!--  ==== RAZON SOCIAL ==== -->
  <div class="form-group rt20">
    <label class="col-sm-2 control-label">Razón Social</label>
    <div class="col-sm-10">
      <input type="text" name="razon_social" id="razon_social" value="{razon_social}" class="form-control" required>
    </div>
  </div>
 
     <!--  ==== CANTIDAD DE EMPLEADOS ==== -->
  <div class="append">   
  <div class="form-group rt20">
    <label class="col-sm-2 control-label">Cantidad de Empleados que se inscribirán</label>
       <div class="col-sm-10">
      <input type="number" class="form-control" name="cantidad_empleados" id="cantidad_empleados" max="30">
    </div>
  </div>
  </div>
  
 	<!--<div class="form-group rt20">-->

	 <!-- <label class="col-md-2 control-label">PDF Adjunto &nbsp<a  class="glyphicon glyphicon-question-sign"  data-toggle="tooltip" title data-original-title="TEXTO DEL TIPO DE CERTIFICADO QUE SE SOLICITA"></a><br /><small style="color: #999">Máximo tamaño permitido 300kb.</small>  -->
  
  <!--  </label>	-->

		<!--<div class="col-md-10 ">  -->
            <!-- image-preview-filename2 input [CUT FROM HERE]-->
  <!--          <div class="input-group image-preview">-->
  <!--              <input type="text" class="form-control image-preview-filename" disabled="disabled"> <!-- don't give a name === doesn't send on POST/GET -->
  <!--              <span class="input-group-btn">-->
                    <!-- image-preview-clear button -->
  <!--                  <button type="button" class="btn btn-default image-preview-clear" style="display:none;">-->
  <!--                      <span class="glyphicon glyphicon-remove"></span> Borrar-->
  <!--                  </button>-->
                    <!-- image-preview-input -->
  <!--                  <div class="btn btn-default image-preview-input">-->
  <!--                      <span class="glyphicon glyphicon-folder-open"></span>-->
  <!--                      <span class="image-preview-input-title"> Examinar...</span>-->
  <!--                      <input type="file" accept="application/pdf" name="userfile"/> <!-- rename it -->
  <!--                  </div>-->
  <!--              </span>-->
  <!--          </div><!-- /input-group image-preview [TO HERE]-->
  <!--      </div>-->
		<!--</div>-->
         <!--  ==== ACEPTA DECLARACION JURADA==== -->
  <div class="form-group">
      <div class="checkbox">
        <label for="check">
        <input type="checkbox" name="check" id="check">
            Manifiesto en carácter de declaración jurada, que los datos consignados en este formulario son correctos y completos, siendo fiel expresión de la verdad.            
        </label>
        <label id="check-error" class="error" for="check"></label>
    </div>
  </div>


       <!--  ==== finalizar ==== -->
  <div class="form-group">
    <div class="col-sm-4">
					<button type="submit" class="btn btn-primary info form-control" data-style="expand-left"><span class="ladda-label">Inscribirse</span></button>
    </div>
  </div>
  
</form>