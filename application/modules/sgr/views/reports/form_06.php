
<div class="row-fluid">
    <div id="meta_div_2">
        <form  method="post" class="well" id="period_{sgr_period}" >
            <div  class="row-fluid " >
                <div class="span6">                        
                   <div>
                            <label>Seleccione Desde </label>
                            <div data-date-viewMode="months" data-date-minViewMode="months" data-date-format="mm-yyyy" data-date="" id="dp3" class="input-append date dp">
                                <input type="text" name="input_period_from"  class="input-block-level">                                       
                                       <span class="add-on"><i class="icon-calendar"></i></span>                                
                            </div>
                        </div>

                    <div>
                        <label>Seleccione la SGR</label>
                        <select name="rectify" id="rectify_{sgr_period}" class="input-block-level required">
                            {sgr_options}
                        </select>
                    </div>                       

                </div>

                <div class="span6">
                    <div>
                            <label>Seleccione Hasta </label>
                            <div data-date-viewMode="months" data-date-minViewMode="months" data-date-format="mm-yyyy" data-date="" id="dp3" class="input-append date dp">
                                <input type="text" name="input_period_to" class="input-block-level">                                       
                                       <span class="add-on"><i class="icon-calendar"></i></span>                                
                            </div>
                        </div>

                </div>
            </div>
            <div  class="row-fluid">
                <div class="span12">
                        <input type="hidden" name="anexo" value="{anexo}" />
                        <button name="submit_period" class="btn btn-block btn-primary hide_offline" type="submit" id="bt_save_{sgr_period}"><i class="fa fa-search"></i> Buscar</button>  
                    </div>
            </div>
        </form>
    </div>
</div>

