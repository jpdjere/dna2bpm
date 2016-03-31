      <div class="container">
          <form class="form-horizontal" method="post" action="{module_url}create">
            
<!-- ============================= REMITENTE  ============================= -->   

        <h1>Remitente</h1>
        <!-- remitente_nombre -->
        <div class="form-group">
          <label for="remitente_nombre" class="col-sm-2 control-label">Nombre</label>
          <div class="col-sm-10">
            <input type="text" class="form-control"  placeholder="Nombre" name="remitente_nombre">
          </div>
        </div>
        <!-- remitente_domicilio -->
        <div class="form-group">
          <label for="remitente_domicilio" class="col-sm-2 control-label">Domicilio</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" i placeholder="Domicilio" name="remitente_domicilio">
          </div>
        </div>
         <!-- remitente_domicilio -->
        <div class="form-group">
          <label for="remitente_cpa" class="col-sm-2 control-label">CPA</label>
          <div class="col-sm-10">
            <input type="text" class="form-control"  placeholder="CPA" name="remitente_cpa">
          </div>
        </div>      
        <!-- remitente_localidad -->
        <div class="form-group">
          <label for="remitente_localidad" class="col-sm-2 control-label">Localidad</label>
          <div class="col-sm-10">
            <input type="text" class="form-control"  placeholder="Localidad" name="remitente_localidad">
          </div>
        </div>        
         <!-- remitente_provincia -->
        <div class="form-group">
          <label for="remitente_provincia" class="col-sm-2 control-label">Provincia</label>
          <div class="col-sm-10">
            <select class="form-control" name="remitente_provincia">
                <option>C.A.B.A.</option>
                <option>Buenos Aires</option>
                <option>Catamarca</option>
                <option>Chaco</option>
                <option>Chubut</option>
                <option>Córdoba</option>
                <option>Corrientes</option>
                <option>Entre Rios</option>
                <option>Formosa</option>
                <option>Jujuy</option>
                <option>La Rioja</option>
                <option>La Pampa</option>              
                <option>Mendoza</option>
                <option>Misiones</option>
                <option>Neuquen</option>
                <option>Rio Negro</option>
                <option>San Juan</option>
                <option>San Luis</option>
                <option>Santa Fé</option>
                <option>Salta</option>
                <option>Santa Cruz</option>
                <option>Santiago Del Estero</option>
                <option>Tierra Del Fuego</option>
                <option>Tucumán</option>
              </select>
           
          </div>
        </div>           
         
 <!-- ============================= DESTINATARIO  ============================= -->   
 
        <h1>Destinatario</h1>
        <!-- remitente_nombre -->
        <div class="form-group">
          <label for="destinatario_nombre" class="col-sm-2 control-label">Nombre</label>
          <div class="col-sm-10">
            <input type="text" class="form-control"  placeholder="Nombre" name="destinatario_nombre">
          </div>
        </div>
        <!-- destinatario_domicilio -->
        <div class="form-group">
          <label for="destinatario_domicilio" class="col-sm-2 control-label">Domicilio</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" i placeholder="Domicilio" name="destinatario_domicilio">
          </div>
        </div>
         <!-- destinatario_cpa -->
        <div class="form-group">
          <label for="destinatario_cpa" class="col-sm-2 control-label">CPA</label>
          <div class="col-sm-10">
            <input type="text" class="form-control"  placeholder="CPA" name="destinatario_cpa">
          </div>
        </div>      
        <!-- destinatario_localidad -->
        <div class="form-group">
          <label for="destinatario_localidad" class="col-sm-2 control-label">Localidad</label>
          <div class="col-sm-10">
            <input type="text" class="form-control"  placeholder="Localidad" name="destinatario_localidad">
          </div>
        </div>        
         <!-- destinatario_provincia -->
        <div class="form-group">
          <label for="destinatario_provincia" class="col-sm-2 control-label">Provincia</label>
          <div class="col-sm-10">
            <select class="form-control" name="destinatario_provincia">
                <option>C.A.B.A.</option>
                <option>Buenos Aires</option>
                <option>Catamarca</option>
                <option>Chaco</option>
                <option>Chubut</option>
                <option>Córdoba</option>
                <option>Corrientes</option>
                <option>Entre Rios</option>
                <option>Formosa</option>
                <option>Jujuy</option>
                <option>La Rioja</option>
                <option>La Pampa</option>              
                <option>Mendoza</option>
                <option>Misiones</option>
                <option>Neuquen</option>
                <option>Rio Negro</option>
                <option>San Juan</option>
                <option>San Luis</option>
                <option>Santa Fé</option>
                <option>Salta</option>
                <option>Santa Cruz</option>
                <option>Santiago Del Estero</option>
                <option>Tierra Del Fuego</option>
                <option>Tucumán</option>
              </select>
          </div>
        </div>       
 
<!-- ============================= CUERPO  ============================= --> 
<h1>Cuerpo</h1>
    
        <div class="form-group">
          <label for="cuerpo" class="col-sm-2 control-label"></label>
          <div class="col-sm-10">
           <textarea class="form-control" name="cuerpo" rows="3"></textarea>
          </div>
        </div>       
 



        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn  bg-info"><i class="fa fa-file-pdf-o "></i> GENERAR CD</button>
          </div>
        </div>
        </form>
          
</div>

