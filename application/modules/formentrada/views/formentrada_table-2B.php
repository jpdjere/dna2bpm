
      
      
      <div class='row'>
      <div class='col-md-12'>
      <div id='col5' name='col5'>
      <form id="commentForm2" name="commentForm2" method="post" action="">    
            
            <div class="form-group">
            <h3 class='text-info'>INFORMACIÓN DEL PROYECTO</h3>
            </div>   
                
                <!--0-->
                    <div class="form-group">
                        <label>Tipo de Préstamo:</label>
                            <select id="tipo_pres" name="tipo_pres" class="form-control">
                                <option selected value=""> Elige una opción </option>
                                <option value="Bienes de Capital">Bienes de Capital</option> 
                                <option value="Construcciones e instalaciones">Construcciones e instalaciones</option>
                                <option value="Bienes de Capital y Construcciones e instalaciones">Bienes de Capital y Construcciones e instalaciones</option>
                                <option value="Capital de trabajo">Capital de trabajo</option>
                                <option value="Pre financiación de exportaciones">Pre financiación de exportaciones</option>
                                <option value="Post financiación de exportaciones">Post financiación de exportaciones</option>
                                <option value="Compra de unidades productivas (Galpones nuevos o usados)">Compra de unidades productivas (Galpones nuevos o usados)</option>
                                <option value="Gastos de mudanza a Parques Industriales">Gastos de mudanza a Parques Industriales</option>
                            </select>
                    </div>
                    
                    <!--1-->
                    <div class="form-group">
                        <label>Monto Total del Proyecto ($):</label>
                        <input type="text" id="monto_total" name="monto_total" class="form-control" >
                    </div>    
                    
                    <!--2-->
                    <div class="form-group">
                        <label>Financiamiento a solicitar ($):</label>
                        <input type="text" id="financiamiento" name="financiamiento" class="form-control" >
                    </div>   
                    
                    
                    <!--3-->
                    <div class="form-group">
                        <label>Ventas (Último Balance/Año):</label>
                        <input type="text" id="balance_1" name="balance_1" class="form-control">
                    </div>   
                    
                    <!--4-->
                    <div class="form-group">
                        <label>Ventas Exportación (Último Balance/Año):</label>
                        <input type="text" id="balance_1_exp" name="balance_1_exp" class="form-control">
                    </div>
                    
                    <!--5-->
                    <div class="form-group">
                        <label>Ventas (Ante Último Balance/Año):</label>
                        <input type="text" id="balance_2" name="balance_2" class="form-control" >
                    </div>
                    
                    <!--6-->
                    <div class="form-group">
                        <label>Ventas Exportación (Ante Último Balance/Año):</label>
                        <input type="text" id="balance_2_exp" name="balance_2_exp" class="form-control" >
                    </div>    
                        
                    <!--7-->
                    <div class="form-group">
                        <label>Ventas (Ante Penúltimo Balance/Año):</label>
                        <input type="text" id="balance_3" name="balance_3" class="form-control" >
                    </div>    
                        
                    <!--8-->
                    <div class="form-group">
                        <label>Ventas Exportación (Ante Penúltimo Balance/Año):</label>
                        <input type="text" id="balance_3_exp" name="balance_3_exp" class="form-control" >
                     </div> 
                    <!--9-->

                    <div class="form-group">
                        <label>Indique a que sector pertenece su empresa:</label>
                        <select id="sector_emp" name="sector_emp" class="form-control">
                                <option selected value=""> Elige una opción </option>
                                <option value="Automotriz y Autopartista">Automotriz y Autopartista</option> 
                                <option value="Maquinaria Agrícola">Maquinaria Agrícola</option>
                                <option value="Biotecnología">Biotecnología</option>
                                <option value="Industria farmacéutica">Industria farmacéutica</option>
                                <option value="Manufacturas especializadas, orientadas a fortalecer diseño y uso de base a mano de obra calificada">Manufacturas especializadas, orientadas a fortalecer diseño y uso de base a mano de obra calificada</option>
                                <option value="Agroindustria">Agroindustria</option>
                                <option value="Productos médicos (s/ ANMAT)">Productos médicos (s/ ANMAT)</option>
                                <option value="Software, TICS, Servicios Audiovisual, Serv. Profesionales y Serv. de invest. clínica y serv. KIBS">Software, TICS, Servicios Audiovisual, Serv. Profesionales y Serv. de invest. clínica y serv. KIBS</option>
                                <option value="Industrias Creativas">Industrias Creativas</option>
                                <option value="Proveedores (servicios especializados y bienes de capital) para el sector minero, petróleo, gas, e industrias extractivas y energías renovables">Proveedores (servicios especializados y bienes de capital) para el sector minero, petróleo, gas, e industrias extractivas y energías renovables</option>
                                <option value="Proveedores del sector aeronáutico, aerospacial, naval y ferroviario">Proveedores del sector aeronáutico, aerospacial, naval y ferroviario</option>
                                <option value="Foresto-Industrial, incluyendo muebles, biomasa y dendroenergía">Foresto-Industrial, incluyendo muebles, biomasa y dendroenergía</option>
                                <option value="OTRO">OTRO</option>
                            </select>
                     </div> 
                    
                    <!--10-->
                    <div class="form-group">
                        <label>Código de Actividad Principal, según Constancia AFIP (F-883 6 Dígitos Numérico):</label>
                        <input type="text" id="codigo_act_emp" name="codigo_act_emp" class="form-control" ></br> 
                        <div id='col7' name='col7' type='danger'></div>  
                    </div> 
                    
                    
                    <!--11-->
                    <div class="form-group">
                        <label>Código de la actividad a ser financiada, según constancia AFIP (F-883 6 Dígitos Numérico):</label>
                        <input type="text" id="codigo_act" name="codigo_act" class="form-control" ></br> 
                        <div id='col6' name='col6' type='danger'></div>  
                    </div>
                    
                    <!--12-->
                    <div class="form-group">
                        <label>Fecha de Inscripción en AFIP de la actividad financiada:</label>
                        <input type="text" id="afip" name="afip" class="form-control" >
                    </div>
                    
                    <!--13-->
                    <div class="form-group">
                        <label>Sector de Actividad:</label>
                        <select id="sector" name="sector" class="form-control">
                                <option selected value=""> Elige una opción </option>
                                <option value="Industria, Agroindustria y Mineria">Industria, Agroindustria y Minería</option> 
                                <option value="Construccion">Construcción</option>
                                <option value="Servicios Industriales">Servicios Industriales</option>
                                <option value="Comercio">Comercio</option>
                                <option value="Agropecuario">Agropecuario</option>
                            </select>
                     </div>
                    
                    <!--14-->
                    <div class="form-group">
                        <label>Descripción del Proyecto:</label>
                        <textarea id="descrip" name="descrip" class="form-control" rows="3" cols ="50" maxlength ="250">
                        </textarea>
                     </div> 
                    
                    <!--15-->
                    <div class="form-inline">
                        <label>¿Acepta Usted que compartamos esta información con Bancos para generar alternativas de financiamieto?</label>
                        <fieldset>
                            <label for="acepta_si">
                                <input type="radio" name="acepta" id="acepta_si" value="SI" required>SI
                            </label></br>
                            <label for="acepta" class="error"></label>
                        </fieldset>  
                    </div> 
                    
                               
                    <div class="form-group">
                       <button id="enviar" class="btn btn-primary btn-lg btn-block" name="enviar"  type="submit" value="Submit">Enviar</button>
                   </div>
       
         </form>      
         </div>
         </div>
         </div>
        <div class="table table-striped"> </div>       
    

         
