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
    <link href="{base_url}emprendedores/assets/css/style.css" rel="stylesheet">
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
        <form method='post'>
            
            
        <!-- Destino Interior/Internacional--> 
         <div class="form-group">
            <label for="nombre">Destino Viaje</label>
            <select name="provincia" name="provincia" class="form-control"  >
              <option value="">Seleccione</option>
              <option value="interior">Interior</option>
              <option value="exterior">Exterior</option>
            </select>
         </div>  
          
        <!-- INTERIOR -->
        <h4 class='text-info'><i class="fa fa-chevron-right text-info"></i> INTERIOR</h4>
        <!-- Provincia  -->
          <div class="form-group">
            <label for="nombre">Provincia</label>
            <select name="provincia" name="provincia" class="form-control"  >
              <option value="">Elija provincia</option>
              <option value="Buenos Aires">Buenos Aires</option>
              <option value="CABA">CABA</option> 
              <option value="Catamarca">Catamarca</option>
              <option value="Chaco">Chaco</option> 
              <option value="Chubut">Chubut</option>
              <option value="Cordoba">Córdoba</option>
              <option value="Corrientes">Corrientes</option> 
              <option value="Entre Rios">Entre Rios</option>
              <option value="Formosa">Formosa</option>
              <option value="Jujuy">Jujuy</option>
              <option value="La Pampa">La Pampa</option> 
              <option value="La Rioja">La Rioja</option>
              <option value="Mendoza">Mendoza</option> 
              <option value="Misiones">Misiones</option>
              <option value="Neuquen">Neuquén</option> 
              <option value="Rio Negro">Rio Negro</option>
              <option value="Salta">Salta</option> 
              <option value="San Juan">San Juan</option>
              <option value="San Luis">San Luis</option> 
              <option value="Santa Cruz">Santa Cruz</option> 
              <option value="Santa Fe">Santa Fe</option>
              <option value="Santiago del Estero">Santiago del Estero</option> 
              <option value="Tierra del Fuego">Tierra del Fuego</option>
              <option value="Tucuman">Tucumán</option> 
          </select>
          </div>
          
          <!-- Ciudad  -->
          <div class="form-group">
            <label for="ciudad">Ciudad</label>
            <input type="text" class="form-control" id="ciudad" name="ciudad" placeholder="Ciudad" >
          </div>
          
           
          <!-- Fecha/Hora  -->
          <div class="row">
              <div class='col-md-6'>
                  <label for="partida">Partida</label>
                  <input type="text" name="daterange" value="01/01/2015 1:30 PM - 01/01/2015 2:00 PM" />
                  <!--<input type="text" class="form-control" id="partida" name="partida" placeholder="partida" >-->
              </div>
              
              <div class='col-md-6'>
                  <label for="llegada">Llegada</label>
                  <input type="text" class="form-control" id="llegada" name="llegada" placeholder="llegada" >
              </div>
              
         
          </div>

   <!-- Send -->
          <div class="form-group" style="margin-top:15px">
                  <input type="submit" class="form-control btn btn-primary" value="Enviar" >
          </div>
   
          
        </form>
<!-- MSGs -->

    <div id='msg_error'style="display:none;margin-top:20px">
        <div class="alert alert-danger" role="alert" >
        Error
        </div>  
    </div>
      
    <div id='msg_ok'style="display:none;margin-top:20px">
        <div class="alert alert-success" role="alert" >
        El formulario se ha enviado correctamente. Gracias!
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
    <script src="{base_url}/emprendedores/assets/jscript/form_viaticos.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
    
    <script type="text/javascript">
$(function() {
    $('input[name="daterange"]').daterangepicker({
        timePicker: true,
        timePickerIncrement: 30,
        locale: {
            format: 'MM/DD/YYYY h:mm A'
        }
    });
});
</script>
  </body>
</html>