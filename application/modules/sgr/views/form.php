{if sgr_period} 
    
{else}
    

{if rectifica}  {else} 

{if select_period}

<div id="no_session">
    <div class="row-fluid">
        <div id="meta_div_2">
            <form  method="post" class="well" id="period_{sgr_period}">
                <div  class="row-fluid " >
                    <div class="span6">                        
                        <label>Anexo</label>
                        <input type="text"  placeholder="{anexo_title}"  class="input-block-level" disabled="true"/>
                    </div>

                    <div class="span6">
                        <div>
                            <label>Seleccione el Período a  Informar </label>
                            <div data-date-viewMode="months" data-date-minViewMode="months" data-date-format="mm-yyyy" data-date="" id="dp3" class="input-append date dp">
                                <input type="text" name="input_period" readonly="" {if post_period} value="{post_period}" {/if} class="input-block-level">                                       
                                       <span class="add-on"><i class="icon-calendar"></i></span>                                
                            </div>
                        </div>
                    </div>
                </div>
                <div  class="row-fluid">
                    <div class="span12">
                        <input type="hidden" name="anexo" value="{anexo}" />
                        <button name="submit_period" class="btn btn-block btn-primary hide_offline" type="submit" id="bt_save_{sgr_period}"><i class="icon-save"></i> Activar Periodo</button>  
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
{/if}
{/if}
{/if}

<!-- RECTIFICAR -->
<div id="is_session">
    <div class="row-fluid">
        <div id="meta_div_2">
            <form  method="post" class="well" id="period_{sgr_period}">
                <div  class="row-fluid " >
                    <div class="span6">                        
                        <label>Rectificar Anexo</label>
                        <input type="text"  placeholder="{anexo_title}"  class="input-block-level" disabled="true"/>

                        <div>
                            <label>Rectificación de {if post_period}{post_period}{/if} Ingrese el Motivo</label>
                            <select name="rectify" id="rectify_{sgr_period}" class="input-block-level required">
                                <option value="">Seleccione el motivo</option>
                                <option value=1>Errores en el sistema y/o procesamiento del archivo</option>
                                <option value=2>Error en la informacion sumistrada</option>
                                <option value=3>Otros motivos</option>
                            </select>
                        </div>                       

                    </div>

                    <div class="span6">
                        <div>
                            <label>Seleccione el Período a  Rectificar</label>
                            <div data-date-viewMode="months" data-date-minViewMode="months" data-date-format="mm-yyyy" data-date="" id="dp3" class="input-append date dp">
                                <input type="text" name="input_period" readonly="" {if post_period} value="{post_period}" {/if} class="input-block-level">

                            </div>
                        </div>

                        <input type="hidden" name="recitifica_value" id="check_rectify_{sgr_period}" value="{anexo}" />
                        <div id="others_{sgr_period}"><label>Otros Motivos</label>
                            <textarea name="others" placeholder="..." class="input-block-level" ></textarea>                        
                        </div>

                    </div>
                </div>
                <div  class="row-fluid">
                    <div class="span12">
                        <input type="hidden" name="anexo" value="{anexo}" />
                        <button name="submit_period" class="btn btn-block btn-primary hide_offline" type="submit" id="bt_save_{sgr_period}"><i class="icon-save"></i>Rectificar  Periodo</button>  
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
