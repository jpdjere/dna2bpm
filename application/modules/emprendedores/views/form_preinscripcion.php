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
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
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
          <h2>Programa Clubes de Emprendedores</h2>
          <h3>Formulario de Preinscripción</h3>
        <form method='post'>
          
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
          
          <!-- Organismos  -->
          <div class="form-group">
            <h3 class='text-info'> Organizaciones que presentan la propuesta para la puesta en marcha de un Club de Emprendedores.</h3>
            <span>Es requisito para participar del programa que la propuesta sea presentada por 3 organizaciones, una de las cuales debe ser un organismo gubernamental provincial o municipal.</span>
          </div> 
          
          
<!-- Organismos Gubernamentales -->
    
          <h4 class='text-info'><i class="fa fa-chevron-right text-info"></i> Organismo gubernamental</h4>

          <!-- Tipo  -->
          <div class="form-group">
            <label for="og_ambito">Ámbito</label>
            <div>
              <label class="radio-inline">
                <input type="radio" id="og_ambito" name="og_ambito" value="municipal"> Municipal
              </label>
              <label class="radio-inline">
                <input type="radio"  id="og_ambito" name="og_ambito" value="provincial"> Provincial
              </label>
              </div>
          </div>
          
          <!-- Nombre  -->
          <div class="form-group">
            <label for="og_nombre">Nombre del referente</label>
            <input type="text" class="form-control" id="og_nombre" name="og_nombre" placeholder="Nombre del referente" >
          </div>

          <!-- Email  -->
          <div class="form-group">
            <label for="og_email">Email</label>
            <input type="email" class="form-control" id="og_email" name="og_email" placeholder="Email de contacto" >
          </div>
          
          <!-- telefono  -->
          <div class="form-group">
            <label for="og_telefono">Teléfono</label>
            <input type="text" class="form-control" id="og_telefono" name="og_telefono" placeholder="Teléfono" >
          </div>
          
 <!-- Organización 1 -->
 
         <h4 class='text-info'><i class="fa fa-chevron-right text-info"></i> Organización 1</h4>
 
           <!-- Nombre  -->
          <div class="form-group">
            <label for="o1_nombre">Nombre del referente</label>
            <input type="text" class="form-control" id="o1_nombre" name="o1_nombre" placeholder="Nombre del referente" >
          </div>

          <!-- Email  -->
          <div class="form-group">
            <label for="o1_email">Email</label>
            <input type="email" class="form-control" id="o1_email" name="o1_email" placeholder="Email de contacto" >
          </div>
          
          <!-- telefono  -->
          <div class="form-group">
            <label for="o1_telefono">Teléfono</label>
            <input type="text" class="form-control" id="o1_telefono" name="o1_telefono" placeholder="Teléfono" >
          </div>
 
  <!-- Organización 2 -->
 
         <h4 class='text-info'><i class="fa fa-chevron-right text-info"></i> Organización 2</h4>
 
           <!-- Nombre  -->
          <div class="form-group">
            <label for="o2_nombre">Nombre del referente</label>
            <input type="text" class="form-control" id="o2_nombre" name="o2_nombre" placeholder="Nombre del referente" >
          </div>

          <!-- Email  -->
          <div class="form-group">
            <label for="o2_email">Email</label>
            <input type="email" class="form-control" id="o2_email" name="o2_email" placeholder="Email de contacto" >
          </div>
          
          <!-- telefono  -->
          <div class="form-group">
            <label for="o2_telefono">Teléfono</label>
            <input type="text" class="form-control" id="o2_telefono" name="o2_telefono" placeholder="Teléfono" >
          </div>
          
   <!-- Espacio Físico -->
         <h4 class='text-info' ><i class="fa fa-chevron-right text-info"></i> Espacio Físico destinado al Club Emprendedor</h4>         

          <!-- uso actual  -->
          <div class="form-group">
            <label for="espacio_uso">Uso actual</label>
            <textarea class="form-control" id="espacio_uso" name="espacio_uso" placeholder="Uso actual" ></textarea>
          </div>
  
          <!-- telefono  -->
          <div class="row">
              <div class='col-md-8'>
                  <label for="espacio_domicilio">Domicilio</label>
                  <input type="text" class="form-control" id="espacio_domicilio" name="espacio_domicilio" placeholder="Domicilio" >
              </div>
              <div class='col-md-4'>
                  <label for="espacio_m2">M2</label>
                  <input type="text" class="form-control" id="espacio_m2" name="espacio_m2" placeholder="M2" >
              </div>
         
          </div>
          
   <!-- Población objetivo -->
   
        <h4 class='text-info' ><i class="fa fa-chevron-right text-info"></i> Población objetivo</h4>       
     
          <div class="form-group">
            <label for="po_impacto">Impacto</label>
            <p>Indique en cuantas personas impactaría la creación del nuevo "Club emprendedor"</p>
            <input type="text" class="form-control" id="po_impacto" name="po_impacto" placeholder="Cnantidad de personas.." >
          </div>
          <!-- uso esperado del espacio  -->
          <div class="form-group">
            <label for="po_uso_esperado">Uso esperado del espacio</label>
            <p>Indique que actividades se llevaran acabo en el "Club emprendedor"</p>
            <textarea class="form-control" id="po_uso_esperado" name="po_uso_esperado" placeholder="Uso esperado del espacio" ></textarea>
          </div>                          
          
          
        </form>
<!-- MSGs -->

    <div id='msg_error'style="display:none;margin-top:20px">
        <div class="alert alert-danger" role="alert" >
        Ha habido un error al enviar el formulario. Contactarse a clubemprendedor@produccion.gob.ar.
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
    <script src="{base_url}/emprendedores/assets/jscript/form_preinscripcion.js"></script>
    
    
  </body>
</html>