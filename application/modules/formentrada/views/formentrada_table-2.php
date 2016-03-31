
       <div id='col5' name='col5'>
      <form class="cmxform" id="commentForm2" name="commentForm2" method="post" action="">    
            
            <div class="table table-striped">INFORMACIÓN DEL PROYECTO
            </div>
             <table   class="table table-striped"> 
                
                    <tr>
                        <td>Tipo de Préstamo:
                         </td>
                         <td>
                            <select id="tipo_pres" name="tipo_pres" class="form-control">
                                <option selected value=""> Elige una opción </option>
                                <option value="Bienes de Capital">Bienes de Capital</option> 
                                <option value="Construcciones e instalaciones">Construcciones e instalaciones</option>
                                <option value="Bienes de Capital y Construcciones e instalaciones">Bienes de Capital y Construcciones e instalaciones</option>
                                <option value="Capital de trabajo">Capital de trabajo</option>
                             
                        </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Monto Total del Proyecto ($):
                         </td>
                         <td>
                             <input type="text" id="monto_total" name="monto_total" class="form-control" >
                        </td>
                    </tr>
                    <tr>
                        <td>Financiamiento a solicitar ($):
                         </td>
                         <td>
                             <input type="text" id="financiamiento" name="financiamiento" class="form-control" >
                        </td>
                    </tr>
                    <tr>
                        <td>Ventas (Último Balance/Año):
                         </td>
                         <td>
                             <input type="text" id="balance_1" name="balance_1" class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <td>Ventas (Ante Último Balance/Año) :
                         </td>
                         <td>
                             <input type="text" id="balance_2" name="balance_2" class="form-control" >
                        </td>
                    </tr>
                    <tr>
                        <td>Ventas (Ante Penúltimo Balance/Año):
                         </td>
                         <td>
                             <input type="text" id="balance_3" name="balance_3" class="form-control" >
                        </td>
                    </tr>
                    <tr>
                        <td>Fecha de Inscripción en AFIP de la actividad financiada:
                         </td>
                         <td>
                             <input type="text" id="afip" name="afip" class="form-control" >
                        </td>
                    </tr>
                    <tr>
                        <td>Sector de Actividad:
                         </td>
                         <td>
                             
                            <select id="sector" name="sector" class="form-control">
                                <option selected value=""> Elige una opción </option>
                                <option value="Industria, Agroindustria y Mineria">Industria, Agroindustria y Minería</option> 
                                <option value="Construccion">Construcción</option>
                                <option value="Servicios Industriales">Servicios Industriales</option>
                                <option value="Comercio">Comercio</option>
                                <option value="Agropecuario">Agropecuario</option>
                            </select>
                        
                        </td>
                    </tr>
                    <tr>
                        <td>Código de la actividad a ser financiada, según constancia AFIP (F-883 6 Dígitos Numérico):
                         </td>
                         <td>
                            <input type="text" id="codigo_act" name="codigo_act" class="form-control" ></br> 
                            <div id='col6' name='col6' type='danger'>
                                
                            </div>     
                             
                            </td>
                    </tr>
                    
                               
                    
                
            </table>
            
            
                  <div >
            
        
                   <button id="enviar" class="btn btn-primary btn-xs" name="enviar"  type="submit" value="Submit">Enviar</button>
                   
        </div> 
        
        
       
         </form>      
         </div> 
        <div class="table table-striped"> </div>       
    

         
