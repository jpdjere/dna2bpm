
           
                    
                    
            <form action="{base_url}estado_inversiones_report/estadodeinversiones_list" method="POST" enctype="rr/form-data">
            <div class="form-group">
                <b>Fecha Inicio</b>
                <div class='input-group date' id='currentDateTime' href="javascript:;">
                    <input id="fechainicio" name="fechainicio" class="form-control datepicker" type="text" href="javascript:;">
                    
                    
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
                <b>Fecha Fin</b>
                 <div class='input-group date' id='currentDateTime' href="javascript:;">
                    <input id="fechafin" name="fechafin" class="form-control datepicker" type="text" href="javascript:;">
                    
                    
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
       
                    
                    
                    
                
                        <input type="hidden" name="anexo" value="{anexo}" />
                        <button name="submit_period" class="btn btn-block btn-primary hide_offline" type="submit" ><i class="icon-save"></i> Buscar Periodo </button>  
                    
            </form>
        


    