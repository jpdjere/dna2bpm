<form id="form_reports" class="form-extra" method="post" novalidate="novalidate">

    <div class="row ">
        <!--  ========================== row 4 . ========================== -->
        <div class="col-md-4" >
            <!--  Desde  -->
            <div class="row ">
                <div class="form-group col-md-12">
                    <label>Desde</label>
                    <div class="input-group date dp" data-date-viewMode="months"
                         data-date-minViewMode="months" data-date-format="mm-yyyy"
                         data-date="">
                        <span class="add-on input-group-addon"><i class="fa fa-calendar"></i></span>
                        <input type="text" name="input_period_from" readonly=""
                               class="form-control">
                    </div>
                </div>
            </div>
            <!--  Hasta  -->
            <div class="row ">
                <div class="form-group col-md-12">
                    <label>Hasta</label>
                    <div class="input-group date dp" data-date-viewMode="months"
                         data-date-minViewMode="months" data-date-format="mm-yyyy"
                         data-date="">
                        <span class="add-on input-group-addon"><i class="fa fa-calendar"></i></span>
                        <input type="text" name="input_period_to" readonly="" class="form-control">
                    </div>
                </div>
            </div>
        </div><!-- row4-->
        <!--  ========================== row 8  ========================== -->
        <div class="col-md-8" >
            <!--  SGR -->

            <div class="row ">
                <div class="form-group col-md-10">
                    <label>C.U.I.T. Socio</label> 
                    <input type="text" class="form-control" name="cuit_socio" placeholder="XXXXXXXXXXX" />
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-10">
                    <label>Seleccione la SGR</label> 
                    <div class="input-group ">
                        <select name="sgr" id="sgr" class="required form-control" > {sgr_options}</select>
                    </div>	
                </div>
            </div>
            
            <div class="row ">
                <div class="form-group col-md-12">                    
                    <div class="input-group ">
                        <small>{sgr_options_checklist}</small>
                    </div>
                </div>
            </div>
            <!--  Reporte  
            <div class="row ">
                <div class="form-group col-md-6">
                    <label>Reporte</label> 
                    <div class="input-group ">
                        <select name="report_name" id="sgr" class="form-control required">
                            <option value="A">A.- MOVIMIENTOS CAPITAL SOCIAL</option>
                            <option value="B" disabled="disabled">B.- CUENTA CORRIENTE
                                CAPITAL SOCIAL (No Disponible)</option>
                            <option value="C" disabled="disabled">C.- SALDOS CAPITAL SOCIAL
                                (No Disponible)</option>
                        </select>
                    </div>
                </div>
            </div>-->

            
            

        </div><!-- row8 -->
    </div>



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
</form>
