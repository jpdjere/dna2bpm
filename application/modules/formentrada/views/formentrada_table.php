<html>
    <head>
        <meta charset="UTF-8">
        <title>Formulario</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

     <!-- bootstrap 3.0.2 -->
        <link href="http://localhost/dna2bpm/dashboard/assets/bootstrap-wysihtml5/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="http://localhost/dna2bpm/dashboard/assets/bootstrap-wysihtml5/css/font-awesome-4.4.0/css/font-awesome.css" rel="stylesheet" type="text/css" />
    
    
    </head>
     <body class="skin-blue sidebar-collapse fixed">
      
      <form class="cmxform" id="commentForm" method="get" action="">    
            <table   class="table table-striped"> 
                
                    <tr>
                        <th>¿Tiene préstamos bancarios vigentes, vinculados con la actividad de la empresa?
                         </th>
                         <th>
                        <select name="prestamo" id="prestamo" >
                            <option selected value=""> Elige una opción </option>
                           <option value="SI">SI</option> 
                           <option value="NO">NO</option>
                        </select>
                        </th>
                    </tr>
                    
                    <tr>
                        <th>¿El monto solicitado del préstamo es inferior a $1.000.000?
                         </th>
                         <th>
                        <select name="monto" id="monto" href="javascript:;">
                            <option selected value=""> Elige una opción </option>
                           <option value="SI">SI</option> 
                           <option value="NO">NO</option>
                        </select>
                        </th>
                    </tr>
                    
                    
                    
                    <tr>
                        <th>Clasificación de deudores según el BCRA: ¿Tiene situación 2 o más?
                         </th>
                         <th>
                        <select name="situacion" id="situacion" href="javascript:;">
                            <option selected value=""> Elige una opción </option>
                           <option value="SI">SI</option> 
                           <option value="NO">NO</option>
                        </select>
                        </th>
                    </tr>
                    <tr>
                        <th>¿Pertenece a un Grupo Empresarial? o ¿Está vinculada comercialmente a otra/s sociedades?
                         </th>
                         <th>
                        <select name="vincu" id="vincu" href="javascript:;">
                            <option selected value=""> Elige una opción </option>
                           <option value="SI">SI</option> 
                           <option value="NO">NO</option>
                        </select>
                        </th>
                    </tr>
                               
                    
                
            </table>    
            
          
            <div class="table table-striped">DATOS DEL SOLICITANTE
            </div>
            <table   class="table table-striped"> 
                
                    <tr>
                        <td>Nombre o Razón Social de la empresa solicitante del crédito:
                         </td>
                         <td>
                             <input type="text" id="nombre" name="nombre" href="javascript:;" >
                             
                        </td>
                    </tr>
                    <tr>
                        <td>Forma Jurídica de la Empresa:
                         </td>
                         <td>
                             <input type="text" id="forma" name="forma" href="javascript:;" >
                        </td>
                    </tr>
                    <tr>
                        <td>CUIT:
                         </td>v
                         <td>
                             <input type="text" id="cuit"  name="cuit" href="javascript:;" >
                        </td>
                    </tr>
                    <tr>
                        <td>Provincia:
                         </td>
                         <td>
                            <select name="provincia" id="vincu" name="provincia" href="javascript:;">
                                
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
                        </td>
                    </tr>
                    <tr>
                        <td>Municipio:
                         </td>
                         <td>
                             <input type="text" id="municipio" name="municipio" href="javascript:;" >
                        </td>
                    </tr>
                    <tr>
                        <td>Dirección:
                         </td>
                         <td>
                             <input type="text" id="direc" name="direc" href="javascript:;" >
                        </td>
                    </tr>
                    <tr>
                        <td>Nombre de Contacto:
                         </td>
                         <td>
                             <input type="text" id="contact" name="contact" href="javascript:;" >
                        </td>
                    </tr>
                    <tr>
                        <td>Cargo:
                         </td>
                         <td>
                             <input type="text" id="cargo" name="cargo" href="javascript:;" >
                        </td>
                    </tr>
                    <tr>
                        <td>Teléfono:
                         </td>
                         <td>
                             <input type="text" id="telefono" name="telefono" href="javascript:;">
                        </td>
                    </tr>
                    <tr>
                        <td>E-mail:
                         </td>
                         <td>
                              <input id="email" name="email" type="email" required>
                        </td>
                    </tr>
                               
                    
                
            </table>
            <div class="table table-striped">INFORMACIÓN DEL PROYECTO
            </div>
             <table   class="table table-striped"> 
                
                    <tr>
                        <td>Tipo de Préstamo:
                         </td>
                         <td>
                            <select name="tipo_pres" id="tipo_pres" href="javascript:;">
                            <option value="Inversiones">Inversiones</option> 
                            <option value="Capital de trabajo">Capital de trabajo</option>
                        </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Monto Total del Proyecto ($):
                         </td>
                         <td>
                             <input type="text" id="monto_total" href="javascript:;" >
                        </td>
                    </tr>
                    <tr>
                        <td>Financiamiento a solicitar ($):
                         </td>
                         <td>
                             <input type="text" id="financiamiento" href="javascript:;" >
                        </td>
                    </tr>
                    <tr>
                        <td>Ventas (Último Balance/Año):
                         </td>
                         <td>
                             <input type="text" id="balance_1" href="javascript:;">
                        </td>
                    </tr>
                    <tr>
                        <td>Ventas (Ante Último Balance/Año) :
                         </td>
                         <td>
                             <input type="text" id="balance_2" href="javascript:;" >
                        </td>
                    </tr>
                    <tr>
                        <td>Vantas (Ante Penúltimo Balance/Año):
                         </td>
                         <td>
                             <input type="text" id="balance_3" href="javascript:;" >
                        </td>
                    </tr>
                    <tr>
                        <td>Fecha de Inscripción en AFIP de la actividad financiada:
                         </td>
                         <td>
                             <input type="text" id="afip" href="javascript:;" >
                        </td>
                    </tr>
                    <tr>
                        <td>Sector de Actividad:
                         </td>
                         <td>
                             <input type="text" id="sector" href="javascript:;" >
                        </td>
                    </tr>
                    <tr>
                        <td>Código de la actividad a ser financiada, según constancia AFIP (F-883):
                         </td>
                         <td>
                             <input type="text" id="codigo_act" href="javascript:;">
                        </td>
                    </tr>
                    <tr>
                        <td>Tipo de Actividad a ser financiada, según Constancia AFIP (F-883):
                         </td>
                         <td>
                             <input type="text" id="tipo_act" href="javascript:;">
                        </td>
                    </tr>
                               
                    
                
            </table>
            
            
                  <div >
            
        
                   <button id="enviar" class="btn btn-primary btn-xs" name="enviar"  >Enviar</button>
                   
        </div> 
        
        
       
         </form>           
        <div class="table table-striped"> </div>       
    

            
        <script  src='http://localhost/dna2bpm/formentrada/assets/jscript/jquery-1.12.1.js'></script>
        <!--<script  src='http://localhost/dna2bpm/jscript/jquery/plugins/Form/jquery.form.min.js'></script>-->
        <script  src='http://localhost/dna2bpm/formentrada/assets/jscript/jquery.validate.js'></script>
        <script  src='http://localhost/dna2bpm/formentrada/assets/jscript/formentrada_script.js'></script>
        
         
 
 
        
        <!-- JS inline -->     
        <script>
        
        
        
        </script>

    </body>
</html>