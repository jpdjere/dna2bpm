
<form method="post" class="well" id="form" action="reports/action_form_cnv/" target="_blank">

    <div class="row ">
        <!--  ========================== row 4 . ========================== -->
        <div class="col-md-4" >
            <!--  Desde  -->
            <div class="row ">
                <div class="form-group col-md-12">
                    <label>Periodo</label>
                    <div class="input-group date dp" data-date-viewMode="months"
                         data-date-minViewMode="months" data-date-format="mm-yyyy"
                         data-date="">
                        <span class="add-on input-group-addon"><i class="fa fa-calendar"></i></span>
                        <input type="text" name="input_period_from" readonly="" class="form-control">
                    </div>
                </div>
            </div>

        </div><!-- row4-->
        <!--  ========================== row 8  ========================== -->
        <div class="col-md-8" >
            <!--  SGR -->
            <div class="row">
                <div class="form-group col-md-12">
                    <label>Seleccione la SGR</label> 
                    <div class="input-group ">
                        <select name="sgr" id="sgr" class="required form-control" > {sgr_options}</select>
                    </div>	
                </div>
            </div>




        </div><!-- row8 -->


        <div class="col-md-8" >
            <div class="row ">
                <div class="form-group col-md-12">                    
                    <input type="checkbox" name="pdf" value="1"> <i class="fa fa-file-pdf">Generar reporte en PDF  </i>
                            
                </div>
            </div>
        </div>

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
