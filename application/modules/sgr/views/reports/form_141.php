
<form method="post" class="well" id="form" action="reports/new_report/" target="_blank">
 <section>
    <div class="row ">
        <!--  ========================== row 6 . ========================== -->
        <div class="col-md-6" >
            <!--  Desde  -->
            <div class="row ">
                <div class="form-group col-md-12">                   
                    <div class="input-group date dp" data-date-viewMode="months"
                         data-date-minViewMode="months" data-date-format="mm-yyyy"
                         data-date="">
                         <span class="add-on input-group-addon"><i class="fa fa-calendar"></i> DESDE</span>
                        <input type="text" name="input_period_from" readonly=""
                               class="form-control">
                    </div>
                </div>
            </div>
            <!--  Hasta  -->
            <div class="row ">
                <div class="form-group col-md-12">
                    <div class="input-group date dp" data-date-viewMode="months"
                         data-date-minViewMode="months" data-date-format="mm-yyyy"
                         data-date="">
                        <span class="add-on input-group-addon"><i class="fa fa-calendar"></i> HASTA</span>
                        <input type="text" name="input_period_to" readonly="" class="form-control">
                    </div>
                </div>
            </div>
        </div><!-- row4-->
        <!--  ========================== row 6  ========================== -->
        <div class="col-md-6" >
            <!--  SGR -->

            <div class="row ">
                <div class="form-group col-md-10">                                         
                    <input type="text" class="form-control" name="cuit_socio" placeholder="Ingrese el C.U.I.T. Socio Participe. Ej:XXXXXXXXXXX" />
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-10">                    
                    <div class="input-group ">
                        <select name="sgr" id="sgr_report" class="required form-control"> 
                            <option value="" disabled selected>Seleccione la SGR</option>
                            {sgr_options}
                            <div style="clear: both;">
                        </select>
                    </div>	
                </div>
            </div>

 </section>
 <section>
             {if is_admin}
            <div class="row ">
                <div class="form-group col-md-12">                    
                    <div class="input-group ">
                        <small>{sgr_options_checklist}</small>
                    </div>
                </div>
            </div>

            {/if}
            
            <!--  ROW 3  -->
    <div class="row">
        <div class="col-md-12">
            <input type="hidden" name="anexo" value="{anexo}" />
            <button name="submit_period"
                    class="btn btn-block btn-primary hide_offline" type="submit"
                    id="bt_save_{sgr_period}">
                <i class="fa fa-search"></i> Generar Reporte
            </button>
        </div>
    </div>
       
        </div>

        
    </div>



    
    </section>     

</form>
