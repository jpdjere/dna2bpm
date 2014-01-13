
<div class="row-fluid">
        <div id="meta_div_2">
            <form  method="post" class="well" id="period">
                <div  class="row-fluid " >
                    <div class="span6">                        
                        <label>{if rectifica}Rectificar {/if}Anexo</label>
                        <input type="text"  placeholder="{anexo_title}"  class="input-block-level" disabled="true"/>
                        {if rectifica}
                        <div>
                            <label>Rectificación de {post_period}/ Ingrese el Motivo</label>
                            <select name="rectify" id="rectify" class="input-block-level">
                                <option value="">Seleccione el motivo</option>
                                <option value=1>Errores en el sistema y/o procesamiento del archivo</option>
                                <option value=2>Error en la informacion sumistrada</option>
                                <option value=3>Otros motivos</option>
                            </select>
                        </div>                       
                        {/if}
                    </div>

                    <div class="span6">
                        <div>
                            <label>Seleccione el Período a {if rectifica} Rectificar {else} Informar {/if} </label>
                            <div data-date-viewMode="months" data-date-minViewMode="months" data-date-format="mm-yyyy" data-date="" id="dp3" class="input-append date dp">
                                <input type="text" name="input_period" readonly="" {if post_period} value="{post_period}" {/if} class="input-block-level">
                                       {if rectifica}
                                       <!-- //  -->
                                       {else}
                                       <span class="add-on"><i class="icon-calendar"></i></span>
                                {/if}
                            </div>
                        </div>
                        {if rectifica}     
                        <input type="hidden" name="anexo" value="{anexo}" />
                        <div id="others"><label>Otros Motivos</label>
                            <textarea name="others" placeholder="..." class="input-block-level" ></textarea>                        
                        </div>
                        {/if}
                    </div>
                </div>
                <div  class="row-fluid">
                    <div class="span12">
                        <input type="hidden" name="anexo" value="{anexo}" />
                        <button name="submit_period" class="btn btn-block btn-primary hide_offline" type="submit" id="bt_save"><i class="icon-save"></i>{if rectifica} Rectificar {else} Activar{/if} Periodo</button>  
                    </div>
                </div>
            </form>
        </div>
    </div>