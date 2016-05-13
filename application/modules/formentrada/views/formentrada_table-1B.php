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
        <div class='row'>
        <div class='col-md-12'> 
        <div class="container-fluid">
        <div class="row">  
            <img src = '{base_url}formentrada/assets/logos2016.jpg' style= 'width:100%'>
        </div>
        <div class="row">  
        <div class="col-md-12"> 
        <div id='col3' name='col3'>
        <form class="cmxform" id="commentForm" method="post" action="">    

            <!--1-->
            <div class="form-group">
                <label for="prestamo">¿Tiene uno o más préstamos bancarios vigentes, vinculados a la actividad de la empresa?</label>
                <div>
                <label class="radio-inline">
                <input type="radio" name="prestamo" id="prestamo" value="SI"  required>SI
                </label>
                <label class="radio-inline">
                <input type="radio" name="prestamo" id="prestamo" value="NO" >NO
                </label>
                </div>
            </div>    
            
            <!--2-->
            <div class="form-group">
                <label class="error">El monto del préstamo bancario vigente ó la sumatoria de los montos de los prestamos vigentes solicitados al sistema bancario es SUPERIOR a $1.000.000?</br>
                        (Para el monto indicado se deben considerar únicamente los créditos otorgados por entidades financieras. NO COMPRENDE acuerdos de descubiertos, acuerdos para descuentos de cheques, prefinanciación de exportaciones y contratos de leasing).?</label>
                <input type="radio" name="monto" id="monto_si" value="SI" class="form-control" required>SI</br>
                <input type="radio" name="monto" id="monto_no" value="NO" class="form-control">NO
            </div>    
    
            <!--3-->
            <div class="form-group">
                <label class="error">Clasificación de deudores según el BCRA: ¿Tiene situación 2 o más?</label>
                <a href="http://www.bcra.gob.ar/Informacion_usuario/iaucen010001.asp?error=0" target="_blank" class="form-control">Para conocer su Situación ante el BCRA haga click aquí</a>
                <input type="radio" name="situacion" id="situacion_si" value="SI"  class="form-control" required>SI</br>
            </div>    

            <!--4-->
            <div class="form-group">
                <label class="error">¿Pertenece a un Grupo Empresarial?</label>
                <input type="radio" name="vincu" id="vincu_si" value="SI" class="form-control" required>SI
                <input type="radio" name="vincu" id="vincu_no" value="NO" class="form-control">NO
            </div>
                    
            <!--5-->
            <div class="form-group">
                <label class="error">¿Está vinculada comercialmente a otra/s sociedades?</label>
                <input type="radio" name="vincu1" id="vincu1_si" value="SI" class="form-control" required>SI
                <input type="radio" name="vincu1" id="vincu1_no" value="NO" class="form-control">NO
            </div>
            
            <!--6-->
            <div class="form-group">
                <label class="error">¿Está vinculada comercialmente a otra/s sociedades?</label>
                <input type="radio" name="vincu1" id="vincu1_si" value="SI" class="form-control" required>SI
                <input type="radio" name="vincu1" id="vincu1_no" value="NO" class="form-control">NO
            </div>
            

            <div class="form-group">
                <h3 class='text-info'>DATOS DEL SOLICITANTE</h3>
            </div>   

            <!--7-->
            <div class="form-group">
                <label class="error">Nombre o Razón Social de la empresa solicitante del crédito:</label>
                <input type="text" id="nombre" name="nombre" class="form-control" >
            </div>
                   
            <!--8-->
            <div class="form-group">
                <label class="error">Forma Jurídica de la Empresa:</label>
                <input type="text" id="forma" name="forma" class="form-control" >
            </div>
                    
            <!--9-->
            <div class="form-group">
                <label class="error">CUIT:</label>
                <input type="text" id="cuit"  name="cuit" class="form-control" >
            </div>
                
            <!--10-->
            <div class="form-group">
                <label class="error">Provincia de Implementación del Proyecto:</label>
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
            </div>

            <!--11-->
            <div class="form-group">
                <label class="error">Localidad de Implementación del Proyecto:</label>
                <input type="text" id="municipio" name="municipio" class="form-control" >
            </div>

            <!--12-->
            <div class="form-group">
                <label class="error">Dirección de Implementación del Proyecto:</label>
                <input type="text" id="direc" name="direc" class="form-control" >
            </div>

            <!--13-->
            <div class="form-group">
                <label class="error">Domicilio Legal - Provincia:</label>
                <select id="provincia_leg" name="provincia_leg" class="form-control">
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
            </div>

            <!--14-->
            <div class="form-group">
                <label class="error">Domicilio Legal - Localidad:</label>
                <input type="text" id="municipio_leg" name="municipio_leg" class="form-control" >
            </div>
            
            <!--15-->
            <div class="form-group">
                <label class="error">Domicilio Legal - Dirección:</label>
                <input type="text" id="direc_leg" name="direc_leg" class="form-control" >
            </div>
                    
            <!--16-->
            <div class="form-group">
                <label class="error">Nombre de Contacto:</label>
                <input type="text" id="contact" name="contact" class="form-control" >
            </div>
                    
            <!--17-->
            <div class="form-group">
                <label class="error">Cargo:</label>
                <input type="text" id="cargo" name="cargo" class="form-control" >
            </div>

            <!--18-->
            <div class="form-group">
                <label class="error">Teléfono:</label>
                <input type="text" id="telefono" name="telefono" class="form-control">
            </div>

            <!--19-->
            <div class="form-group">
                <label class="error">E-mail:</label>
                <input id="email" name="email" type="email" required class="form-control">
            </div>

            <!--20-->
            <div class="form-group">
                <label class="error">Por favor repita su E-mail:</label>
                <input id="email1" name="email1" type="email" required class="form-control">
            </div>

            <!--21-->
            <div class="form-group">
                <label class="error">¿Su sede productiva se encuentra radicada en un parque industrial?</label>
                <select id="parque_ind" name="parque_ind" class="form-control">
                    <option selected value=""> Elige una opción </option>
                    <option value="SI">SI</option>
                    <option value="NO">NO</option>
                </selected>      
            </div>

            <!--22-->
            <div class="form-group">
                <label class="error">antidad actual de empleados:</label>
                <input id="cant_emp" name="cant_emp"  required class="form-control">
            </div>

            <div class="form-group">
                <button id="siguiente" class="btn btn-primary btn-xs" name="siguiente" >Siguiente</button>
            </div>

        </form>
        </div>
        </div>
        </div>
        </div>
        </div>
        </div>
         
        <div class="table table-striped"></div>
    
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