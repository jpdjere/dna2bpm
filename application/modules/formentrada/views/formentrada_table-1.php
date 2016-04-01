<html>
    <head>
        <meta charset="UTF-8">
        <title>Formulario</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

     <!-- bootstrap 3.0.2 -->
        <link href="{base_url}dashboard/assets/bootstrap-wysihtml5/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="{base_url}dashboard/assets/bootstrap-wysihtml5/css/font-awesome-4.4.0/css/font-awesome.css" rel="stylesheet" type="text/css" />
        <link href='{base_url}formentrada/assets/jscript/jquery-ui.css' rel="stylesheet" type="text/css"/>
    
    </head>
     <body class="skin-blue sidebar-collapse fixed">
    <div class="container-fluid">
        <div class="row">  
        <img src = '{base_url}formentrada/assets/logos2016.jpg' style= 'width:100%'>
        </div>
      <div class="row">  
       <div class="col-md-12"> 
      <div id='col3' name='col3'>
      <form class="cmxform" id="commentForm" method="post" action="">    

            <table   class="table table-striped"> 
                
                    <tr>
                        <th>¿Tiene uno o más préstamos bancarios vigentes, vinculados a la actividad de la empresa?
                         </th>
                         <th>
                        <fieldset>
                            <label for="prestamo_si">
                                <input type="radio" name="prestamo" id="prestamo_si" value="SI" required>SI</br>
                            </label>
                            <label for="prestamo_no">
                                <input type="radio" name="prestamo" id="prestamo_no" value="NO">NO
                            </label></br>
                        <label for="prestamo" class="error"></label>
                        </fieldset>
                        </th>
                    </tr>
                    
                    <tr>
                        <th>¿El monto del préstamo bancario vigente ó la sumatoria de los montos de los prestamos vigentes solicitados al sistema bancario es inferior a $1.000.000?</br>
                        (Para el monto indicado se deben considerar únicamente los créditos otorgados por entidades financieras. NO COMPRENDE acuerdos de descubiertos, acuerdos para descuentos de cheques, prefinanciación de exportaciones y contratos de leasing.)
                         </th>
                         <th>
                             
                            <fieldset>
                                <label for="monto_si">
                                    <input type="radio" name="monto" id="monto_si" value="SI" required>SI</br>
                                </label>
                                <label for="monto_no">
                                    <input type="radio" name="monto" id="monto_no" value="NO">NO
                                </label></br>
                                <label for="monto" class="error"></label>
                            </fieldset>
                        
                        </th>
                    </tr>
                    
                    
                    
                    <tr>
                        <th>Clasificación de deudores según el BCRA: ¿Tiene situación 2 o más?
                        <a href="http://www.bcra.gob.ar/Informacion_usuario/iaucen010001.asp?error=0" target="_blank">Para conocer su Situación ante el BCRA haga click aquí</a>
                         </th>
                         <th>
                            <fieldset>
                                <label for="situacion_si">
                                    <input type="radio" name="situacion" id="situacion_si" value="SI" required>SI</br>
                                </label>
                                <label for="situacion_no">
                                    <input type="radio" name="situacion" id="situacion_no" value="NO">NO
                                </label></br>
                                <label for="situacion" class="error"></label>
                            </fieldset> 
                        </th>
                    </tr>
                    <tr>
                        <th>¿Pertenece a un Grupo Empresarial? o ¿Está vinculada comercialmente a otra/s sociedades?
                         </th>
                         <th>
                             
                            <fieldset>
                                <label for="vincu_si">
                                    <input type="radio" name="vincu" id="vincu_si" value="SI" required>SI
                                </label>
                                <label for="vincu_no">
                                    <input type="radio" name="vincu" id="vincu_no" value="NO">NO
                                </label>
                                <label for="vincu" class="error"></label>
                            </fieldset>  
                        </th>
                    </tr>
                               
                    
                
            </table>    
            
          
            <div class="table table-striped">DATOS DEL SOLICITANTE </div>
            <table   class="table table-striped"> 
                
                    <tr>
                        <td>Nombre o Razón Social de la empresa solicitante del crédito:
                         </td>
                         <td>
                             <input type="text" id="nombre" name="nombre" class="form-control" >
                             
                        </td>
                    </tr>
                    <tr>
                        <td>Forma Jurídica de la Empresa:
                         </td>
                         <td>
                             <input type="text" id="forma" name="forma" class="form-control" >
                        </td>
                    </tr>
                    <tr>
                        <td>CUIT:
                         </td>
                         <td>
                             <input type="text" id="cuit"  name="cuit" class="form-control" >
                        </td>
                    </tr>
                    <tr>
                        <td>Provincia:
                         </td>
                         <td>
                            <select id="provincia" name="provincia" class="form-control">
                                <option selected value=""> Elige una opción </option>
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
                        </td>
                    </tr>
                    <tr>
                        <td>Localidad:
                         </td>
                         <td>
                             <input type="text" id="municipio" name="municipio" class="form-control" >
                        </td>
                    </tr>
                    <tr>
                        <td>Dirección:
                         </td>
                         <td>
                             <input type="text" id="direc" name="direc" class="form-control" >
                        </td>
                    </tr>
                    <tr>
                        <td>Nombre de Contacto:
                         </td>
                         <td>
                             <input type="text" id="contact" name="contact" class="form-control" >
                        </td>
                    </tr>
                    <tr>
                        <td>Cargo:
                         </td>
                         <td>
                             <input type="text" id="cargo" name="cargo" class="form-control" >
                        </td>
                    </tr>
                    <tr>
                        <td>Teléfono:
                         </td>
                         <td>
                             <input type="text" id="telefono" name="telefono" class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <td>E-mail:
                         </td>
                         <td>
                              <input id="email" name="email" type="email" required class="form-control">
                        </td>
                    </tr>
                    <tr>
                    <td>Por favor repita su E-mail:
                         </td>
                         <td>
                              <input id="email1" name="email1" type="email" required class="form-control">
                        </td>
                    </tr>           
                    
                
            </table>
            
            
                <div >
                   <button id="siguiente" class="btn btn-primary btn-xs" name="siguiente" >Siguiente</button>
                </div> 
        
        
       
         </form>    
         
         
        </div> 
         </div>
        </div>
        </div> 
        
         
        <div class="table table-striped"> </div>       
    
        <script>
            //-----declare global vars
            var base_url = "{base_url}";
        </script>
            
        <script  src='{base_url}formentrada/assets/jscript/jquery-1.12.1.js'></script>
        <!--<script  src='http://localhost/dna2bpm/jscript/jquery/plugins/Form/jquery.form.min.js'></script>-->
        <script  src='{base_url}formentrada/assets/jscript/jquery.validate.js'></script>
        <script  src='{base_url}formentrada/assets/jscript/jquery-ui.js'></script>
        <script  src='{base_url}formentrada/assets/jscript/formentrada_script.js'></script>
        <script  src='{base_url}formentrada/assets/jscript/moment.min.js'></script>
        <script  src='{base_url}formentrada/assets/jscript/jquery.price_format.2.0.js'></script>
        <script  src='{base_url}formentrada/assets/jscript/jquery.maskedinput.js'></script> 
 
 
        
        <!-- JS inline -->     
        <script>
        
        
        
        </script>

    </body>
</html>