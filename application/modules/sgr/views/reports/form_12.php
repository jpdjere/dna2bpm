<form id="form_reports" class="form-extra" method="post" novalidate="novalidate">
     <section>
        <div class="row ">
            <!--  ========================== COL IZQ ========================== -->
            <div class="col-md-6" >
                <!--  Desde  -->
                <div class="row ">
                    <div class="form-group col-md-12">                   
                        <div class="input-group date dp" data-date-viewMode="months"
                             data-date-minViewMode="months" data-date-format="mm-yyyy"
                             data-date="">
                             <span class="add-on input-group-addon"><i class="fa fa-calendar"></i> DESDE</span>
                            <input type="text" id="input_period_from" name="input_period_from" readonly=""
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
                            <input type="text" id="input_period_to" name="input_period_to" readonly="" class="form-control">
                        </div>
                    </div>
                </div>

                <!--  Nro de Orden  -->
                <div class="row ">
                    <div class="form-group col-md-12">
                        <input class="form-control" type="text" name="order_number" placeholder="Ingrese el Nro de Orden Ej:XXXXXX/XX" />
                    </div>
                </div>

                {if is_admin}
                <!--  REPORTE COMPLETO  -->
                <div class="row ">
                    <div class="form-group col-md-12">                    
                        <input type="checkbox" name="custom_report" value="1"> Generar Reporte Completo                              
                    </div>
                </div>
                {/if}

            </div><!-- row4-->
            <!--  ========================== COL DER  ========================== -->
            <div class="col-md-6" >                               

                <!--  CUIT  -->
                <div class="row ">
                    <div class="form-group col-md-12">
                        <input type="text" class="form-control" name="cuit_sharer" placeholder="Ingrese el C.U.I.T. Socio Participe Ej:XXXXXXXXXXX" />
                    </div>
                </div>

                <!--  CUIT ACREEDOR -->
                <div class="row ">
                    <div class="form-group col-md-12">
                        <input type="text" class="form-control" name="cuit_creditor" placeholder="Ingrese el C.U.I.T. Socio Acreedor Ej:XXXXXXXXXXX" />
                    </div>
                </div>

                <!--  TIPO  -->
                <div class="row ">
                    <div class="form-group col-md-12">
                        <input class="form-control" type="text" name="warranty_type" placeholder="Ingrese EL Tipo de Garantia Ej: GFEF o GFCPD" />
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-12">                    
                        <div class="input-group ">
                            <select name="sgr" id="sgr_report" class="required form-control"> 
                                {if is_admin}<option value="" disabled selected>Seleccione la SGR</option>{/if}
                                {sgr_options}
                                <div style="clear: both;">
                            </select>
                        </div>  
                    </div>
                </div>

     </section>

     <section>
                {if is_admin}
                <div class="row " id="checks_sgrs" style="display:none">
                    <div class="form-group col-md-12">                    
                        <div class="input-group ">
                            <small>{sgr_options_checklist}</small>
                        </div>
                    </div>
                </div>

                {/if}   
                 

            
            <div id="loading" class="col-md-12" style="display:none;margin-top:20px">
              <div class="box box-gray">
                <div class="box-body">
                  Obteniendo información
                </div>
                <!-- /.box-body -->
                <!-- Loading (remove the following to stop the loading)-->
                <div class="overlay">
                  <i class="fa fa-refresh fa-spin"></i>
                </div>
                <!-- end loading -->
              </div>
              <!-- /.box -->
            </div>



            <div class="row">
                <div class="col-md-12" id='show_link' style="display:none;margin-top:20px">
                    <a class="btn btn-block btn-warning hide_offline" target="_self" href="reports/show_last_report"><i class="fa fa-print"></i> Imprimir el Ultimo Reporte Generado</a>
                </div>

                <div class="col-md-12" id='show_no_record' style="display:none;margin-top:20px">
                    <a class="btn btn-block btn-danger hide_offline"><i class="fa fa-circle-o"></i> No hay registros para mostrar</a>
                </div>
                
                <div class="col-md-12">
                    <input type="hidden" name="anexo" value="{anexo}" />
                    <button name="submit_period"
                            class="btn btn-block btn-info hide_offline" type="submit"
                            id="bt_save_{sgr_period}">
                        <i class="fa fa-search"></i> Generar Reporte
                    </button>
                    <!--{link_report}-->                    
                </div>
            </div>

               
            </div>
        </div>
       
    </section>     

</form>
