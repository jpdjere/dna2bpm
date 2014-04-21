<!--{if sgr_period} 
 
{else}
    {if rectifica} 
         
    {else}
        {if select_period}-->
<div id="no_session">
        <div id="meta_div_2">
            <form  method="post" class="well" id="period_{sgr_period}">
                <div  class="row" >
                    <div class="col-md-6"> 
                        <div class="form-group">
                            <label>Anexo</label>
                            <input type="text"  placeholder="{anexo_title}"  disabled="true" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Seleccione el Período a  Informar </label>
                            <div class="input-group date dp"  data-date-viewMode="months" data-date-minViewMode="months" data-date-format="mm-yyyy" data-date="">                  
                                <span class="add-on input-group-addon"><i class="fa fa-calendar"></i></span>       
                                <input type="text" name="input_period" readonly="" {if post_period} value="{post_period}" {/if} class="form-control">                                       
                                                               
                             </div>
                        </div>
                        
                    </div>
                </div>
                <div  class="row">
                    <div class="col-md-12">
                        <input type="hidden" name="anexo" value="{anexo}" />
                        <button name="submit_period" class="btn btn-block btn-primary hide_offline" type="submit" id="bt_save_{sgr_period}"><i class="icon-save"></i> Activar Periodo</button>  
                    </div>
                </div>
            </form>
        </div>

</div>
<!--        {/if}
    {/if}
{/if}-->

<!-- RECTIFICAR -->
<div id="is_session" style="display:none">
    <div class="row-fluid">
        <div id="meta_div_2">
            <form  method="post" class="well" id="period_{sgr_period}">
                
                <div  class="row" >
                    <!-- Rectificar anexo -->
                    <div  class="col-md-4" >
                        <div class="form-group">
                            <label>Rectificar Anexo </label>
                            <input type="text"  class="form-control" placeholder="{anexo_title}"   disabled="true"/>
                        </div>
                    </div>
                    <!-- Periodo -->
                    <div  class="col-md-4" >
                        <div class="form-group">
                            <label>Ingrese el Motivo</label>
                            <select name="rectify" id="rectify_{sgr_period}" class="form-control required">
                                <option value="">Seleccione el motivo</option>
                                <option value=1>Errores en el sistema y/o procesamiento del archivo</option>
                                <option value=2>Error en la informacion sumistrada</option>
                                <option value=3>Otros motivos</option>
                            </select>
                        </div>
                    </div>
                    <!-- Motivo -->
                    <div  class="col-md-4" >
                        <div class="form-group">
                            <label>Seleccione el Período a  Rectificar</label>
                            <div data-date-viewMode="months" data-date-minViewMode="months" data-date-format="mm-yyyy" data-date="" id="dp3" class="input-append date dp">
                                <input type="text" name="input_period" readonly="" {if post_period} value="{post_period}" {/if} class="form-control">
                            </div>
                        </div>                        
                    </div>
                </div>
                <!-- Otros Motivos -->
                 <div  class="row" >
                     <div class="form-group col-md-12">
                        <input type="hidden" name="recitifica_value" id="check_rectify_{sgr_period}" value="{anexo}" />
                        <div id="others_{sgr_period}">
                            <label>Otros Motivos</label>
                            <textarea name="others" placeholder="..." class="form-control" ></textarea>                        
                        </div>  
                     </div>
                 </div>
                
                <!-- Otros Motivos -->
                 <div  class="row" >
                     <div class="form-group col-md-12">
                         <input type="hidden" name="anexo" value="{anexo}" />
                        <div class="alert alert-info ">
                            <div class="row text-center">  
                            <p>{rectified_legend}</p>
                            </div>
                            <div class="row">                                        
                                <div class="col-md-6"><button name="submit_period" class="btn btn-block btn-success hide_offline" id="bt_save_{sgr_period}"><i class="icon-save"></i>Confirma</button></div>
                                <div class="col-md-6"><a class="btn btn-block btn-danger hide_offline" href="">Cancelar</a></div>  
                            </div>
                            <div class="row text-center">  
                            <h5> NOTA: Para terminar la rectificación deberá asociar el perido a un Archivo o a "SIN MOVIMIENTO"</h5>
                            </div>
                            
                        </div>  
                     </div>
                 </div>
                                
                                



            </form>
        </div>
    </div>
</div>


