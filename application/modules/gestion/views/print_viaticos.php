<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>{title}</title>

    <!-- Bootstrap -->
    <link href="{base_url}/dashboard/assets/bootstrap-wysihtml5/css/bootstrap.min.css" rel="stylesheet">
    <link href="{base_url}/dashboard/assets/css/style.css" rel="stylesheet">
    <link href="{base_url}gestion/assets/css/style.css" rel="stylesheet">
     <!-- font Awesome -->
    <link href="{base_url}dashboard/assets/bootstrap-wysihtml5/css/font-awesome-4.4.0/css/font-awesome.css" rel="stylesheet" type="text/css" />
        
    
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script> --
    <![endif]-->
  </head>
  <body>
    
    
    <div class='container'>
      <!-- ============= Barra Ministerio  -->
      
      <div class='row'>
        {logobar}
        </div>
      <!-- ============= Formulario  -->
     <div class='row'>
        <div class='col-md-12'>
          <h2>EXPTE S01</h2>
          <h3>Solicitud de anticipo de viaticos y ordenes de pasaje</h3>
          <h4>Normativa: Decreto Nº 1343/74 compl.y modif. y/o Decreto Nº 2345/08</h4>
          
          <!-- MSGs -->

    <div id='msg_error'style="display:none;margin-top:20px">
        <div class="alert alert-danger" role="alert" >
        Error
        </div>  
    </div>
      
    <div id='msg_ok'style="display:none;margin-top:20px">
        <div class="alert alert-success" role="alert" >
        El formulario se ha enviado correctamente. Gracias!
        <div id="MSG"></div>
        </div>  
    </div>
          
        
        <h4 class='text-info'><i class="fa fa-chevron-right text-info"></i>INTERIOR</h4>
        
        <!-- Destino Interior/Internacional--> 
         <div class="form-group">
            <label for="nombre">Destino Viaje:</label>
            {provincia}
         </div>  
         
          
          <!-- Ciudad  -->
          <div class="form-group">
            <label for="ciudad">Ciudad: </label>
            {ciudad}
          </div>
          
           
          <!-- Fecha/Hora  -->
          <div class="form-group">
              <label>Fecha Desde/Hasta:</label>
              {event-interval}
          </div>
          
          
          <div class="row">
              <div class='col-md-12'>
                  <label for="motivo">Motivo de la comisión de servicio:</label>
                  {motivo}
              </div>
          </div>
          
          <div class="row">
              <div class='col-md-6'>
                  <label for="dependencia">Dependencia que requiere el servicio: </label>
                  {dependencia}
              </div>
              
             <div class='col-md-6'>
             <label for="nombre">Medio de Trasporte:</label>
             {transporte}
             </div>
          </div>
          
         <!-- ADD -->
            <div class="table-responsive dummy_msgs">
              <hr>
              <table class="table table-mailbox">
                <thead>
                  <th>APELLIDO Y NOMBRE</th>
                  <th>C.U.I.T o C.U.I.L.</th>
                  <th>PERMAN. O CONTRATADO</th>
                  <th>NIVEL/GRA CATEG/RAN</th>
                  <th>E-MAIL</th>
                  <th>TELEFONO</th>	
                  <th>VENCIMIENTO CONTRATO</th>
                  <th>VIATICO DIARIO</th>
                  <th>VIATICO TOTAL</th>
              </thead>
              <tbody>{agentes}</tbody>
              </table>
              <hr>
            </div>
            


          <!-- pasaje -->
            <div class="row">
              <div class='col-md-6'>
                  <label for="pasaje">Pasaje:</label>
                  {pasaje}<br/>
              </div>
              
             <div class='col-md-3'>
                  <label for="importe_pasaje">Importe Pasaje:</label>
                  ${importe_pasaje}
              </div>
              
               <div class='col-md-3'>
                  <label for="gastos_eventuales">Gastos Eventuales:</label>
                  ${gastos_eventuales}
              </div>
             </div>
             
             
             <div class="row">
              <div class='col-md-12'>
                  <label for="observaciones">Observaciones:</label>
                  {observaciones}
                  <br />
              </div>
          </div>
          
          <div class="row"  align="right">
              <div class='col-md-9'></div>
              <div class='col-md-3' align="center"><hr>FIRMA Y SELLO DEL RESPONSABLE</div>
          </div>
          
          <div class="row">
            
              <div class='col-md-12' align="center">
                <hr>
                Autorízase el anticipo solicitado y de corresponder la emisión de la/s orden/es de pasaje.
Regístrese en la DIRECCION GENERAL DE ADMINISTRACION, procedase por el Departamento Tesoreria al pago resultante.
</div>
          </div>
          
          
          


        

         <!-- end ADD -->
           <!-- INTERIOR -->
        <div id="interior">
          </div>
          
          <!-- EXTERIOR -->
          <div id="exterior">
          <h4 class='text-info'><i class="fa fa-chevron-right text-info"></i> EXTERIOR</h4>
          
          <!-- Apellido y Nombre /  CUIL  -->
          <div class="row">
              <div class='col-md-6'>
                  <label for="nombre">Apellido y Nombre</label>
                  <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Apellido y Nombre" >
              </div>
              
              <div class='col-md-6'>
                  <label for="cuil">CUIL</label>
                  <input type="text" class="form-control" id="cuil" name="cuil" placeholder="Ingrese un CUIL válido" >
              </div>
          </div>
          
          
          <h5><strong>Organismo y Dependencia:</strong>SECRETARIA DE EMPRENDEDORES Y DE LA PEQUEÑA Y MEDIANA EMPRESA</h5>
          
          <!-- Categoria -->
          <div class="row">
              <div class='col-md-6'>
                  <label for="categoria">Categoría o Cargo</label>
                  <input type="text" class="form-control" id="categoria" name="categoria" placeholder="Categoría o Cargo" >
              </div>
              
              <div class='col-md-6'>
              <label for="nombre">Tipo de Contratación</label>
              <select name="contratacion" name="contratacion" class="form-control">
                <option value="">Seleccione</option>
                <option value="permanente">Permanente</option>
                <option value="contratado">Contratado</option>
             </select>
              </div>
          </div>
          
          
          <!-- Mision -->
          <div class="row">
              <div class='col-md-6'>
                  <label for="motivo_mision">Motivo de la misión</label>
                  <type="text" class="form-control" id="motivo_mision" name="motivo_mision" placeholder="Motivo de la misión" >
              </div>
              
              <div class='col-md-6'>
                  <label for="duracion_mision">Duración</label>
                  <input type="text" class="form-control" id="duracion_mision" name="duracion_mision" placeholder="Tiempo de la misión" >
              </div>
          </div>
          
          
          <!-- Fecha/Hora  -->
          <div class="row">
              <div class='col-md-6'>
                  <label for="partida">Partida</label>
                  <input type="text" class="form-control" id="partida" name="partida" placeholder="partida" >
              </div>
              
              <div class='col-md-6'>
                  <label for="llegada">Llegada</label>
                  <input type="text" class="form-control" id="llegada" name="llegada" placeholder="llegada" >
              </div>
          </div>
          
          
          <h5><strong>COSTO DE DESPLAZAMIENTO:</strong></h5>
          
          
          <!-- Lugar/Viaticos -->
          <div class="row">
              <div class='col-md-6'>
                  <label for="lugar_destino">Lugar de destino</label>
                  <type="text" class="form-control" id="lugar_destino" name="lugar_destino" placeholder="Lugar de destino" >
              </div>
              
              <div class='col-md-6'>
                  <label for="viaticos_diarios">Viáticos diarios</label>
                  <input type="text" class="form-control" id="viaticos_diarios" name="viaticos_diarios" placeholder="Viáticos diarios" >
              </div>
          </div>
          
          <!-- Transporte/Seguro -->
          <div class="row">
              <div class='col-md-6'>
                  <label for="transporte">Transporte y pasajes:</label>
                  <type="text" class="form-control" id="transporte" name="transporte" placeholder="Transporte y pasajes" >
              </div>
              
              <div class='col-md-6'>
                  <label for="seguro">Seguro Medico</label>
                  <input type="text" class="form-control" id="seguro" name="seguro" placeholder="Seguro Medico" >
              </div>
          </div>
          
          <!-- Tramos/Tipo de Cambio -->
          <div class="row">
              <div class='col-md-6'>
                  <label for="transporte">Transporte y pasajes</label>
                  <type="text" class="form-control" id="transporte" name="transporte" placeholder="Transporte y pasajes" >
              </div>
              
              <div class='col-md-6'>
                  <label for="seguro">Seguro Medico</label>
                  <input type="text" class="form-control" id="seguro" name="seguro" placeholder="Seguro Medico" >
              </div>
          </div>
          
          <!-- Tramos/Tipo de Cambio -->
          <div class="row">
              <div class='col-md-6'>
                  <label for="tramo">Tramo del Pasajes</label>
                  <type="text" class="form-control" id="tramo" name="tramo" placeholder="Tramo del Pasajes" >
              </div>
              
              <div class='col-md-6'>
                  <label for="tipo_cambio">Tipo de Cambio</label>
                  <input type="text" class="form-control" id="tipo_cambio" name="tipo_cambio" placeholder="Tipo de Cambio">
              </div>
          </div>
          
          <!-- Costos -->
          <div class="row">
              <div class='col-md-6'>
                  <label for="costo_moneda_extrangera">Costo en moneda Extrangera</label>
                  <type="text" class="form-control" id="costo_moneda_extrangera" name="costo_moneda_extrangera" placeholder="Costo en moneda Extrangera" >
              </div>
              
              <div class='col-md-6'>
                  <label for="costo_pesos">Costo en Pesos</label>
                  <input type="text" class="form-control" id="costo_pesos" name="costo_pesos" placeholder="Costo en Pesos">
              </div>
          </div>
          
          
          </div>
        


    
    
    
    
    </div>
</div>
    <!-- JS Global -->
    <script>
        //-----declare global vars
        var base_url = '{base_url}';
    

    </script>
        
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="{base_url}/jscript/jquery/jquery.min.js"></script>
    <script src="{base_url}/dashboard/assets/bootstrap-wysihtml5/js/bootstrap.min.js"></script>
    <script src="{base_url}/jscript/jquery/plugins/jquery-validation-1.15.0/jquery.validate.min.js"></script>
    <script src="{base_url}/jscript/jquery/plugins/jquery-validation-1.15.0/localization/messages_es_AR.js"></script>
    <script src="{base_url}/gestion/assets/jscript/form_viaticos.js"></script>
   
   
        <!-- JS custom -->     
        <!-- JS:calendar-JS -->
        <script  src='{base_url}/calendar/assets/jscript/app.js'></script>

 
  </body>
</html>