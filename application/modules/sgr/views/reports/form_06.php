
<div class="row-fluid">
    <div id="meta_div_2">
        <form  method="post" class="well" id="form">
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
                        <select name="sgr" id="sgr" class="input-block-level required">
                            <!--{sgr_options}-->
                            <option value="666">Todos</option>

                            <option value="2478671474">ACINDAR PYMES S.G.R.</option>


                            <option value="2175135318">AFFIDAVIT S.G.R.</option>


                            <option value="1106520165">AFIANZAR S.G.R.</option>


                            <option value="1476431157">AGROAVAL S.G.R.</option>


                            <option value="1607152997">AMERICANA DE AVALES S.G.R.</option>


                            <option value="3121601518">AVAL RURAL S.G.R.</option>


                            <option value="3653247007">AVALUAR S.G.R.</option>


                            <option value="3703508095">AZUL PYME S.G.R.</option>


                            <option value="2010246721">C.A.E.S. S.G.R.</option>


                            <option value="2257679366">CAMPO AVAL S.G.R.</option>


                            <option value="3790377123">CARDINAL S.G.R.</option>


                            <option value="4284790099">CONFIABLES S.G.R.</option>


                            <option value="2129915769">CUYO AVAL S.G.R.</option>


                            <option value="3303455306">DON MARIO S.G.R.</option>


                            <option value="2840662334">FIDUS S.G.R.</option>


                            <option value="1462524917">GARANTIA DE VALORES S.G.R.</option>


                            <option value="3826154295">GARANTIZAR S.G.R.</option>


                            <option value="3528267758">INTERGARANTIAS S.G.R.</option>


                            <option value="1285076677">LOS GROBO S.G.R.</option>


                            <option value="2519972722"> COMPANIA GENERAL DE AVALES S.G.R.</option>


                            <option value="3885670783">NORTE AVAL S.G.R.</option>


                            <option value="687239304">PROPyME S.G.R.</option>


                            <option value="2267515782">AVAL FEDERAL S.G.R.</option>


                            <option value="1270405713">SOL GARANTIAS S.G.R.</option>


                            <option value="702780368">SOLIDUM S.G.R.</option>


                            <option value="2207746538">VINCULOS S.G.R.</option>


                            <option value="1676213769">LA SOCIEDAD SGR</option>


                            <option value="3768366151">FONDO ESPECIFICO DE RIESGO FIDUCIARIO GARANTAXI I </option>


                            <option value="1045524969">FONDO ESPECIFICO DE RIESGO FIDUCIARIO CORPORACION BUENOS AIRES SUR </option>


                            <option value="1383403561">FRE FIDUCIARIO PARA GARANTIZAR PYMES NO SUJETAS DE CREDITO</option>


                            <option value="1186345001">FONDO ESPECIFICO DE RIESGO FIDUCIARIO PMSA I</option>


                            <option value="128688736">FONDO ESPECIFICO DE RIESGO FIDUCIARIO PROVINCIA DE CATAMARCA</option>


                            <option value="462574988">FONDO ESPECIFICO DE RIESGO FIDUCIARIO PROVINCIA DE SANTA CRUZ</option>


                            <option value="4061642435">FONDO ESPECIFICO DE RIESGO FIDUCIARIO PROVINCIA DE SANTA FE</option>


                            <option value="3755096283">FONDO ESPECIFICO DE RIESGO FIDUCIARIO SOCO RIL</option>


                            <option value="3945918291">FONDO ESPECIFICO DE RIESGO FIDUCIARIO YAGUAR</option>


                            <option value="2957316498">FOGABA - FONDO DE GARANTIAS DE BUENOS AIRES S.G.R.</option>


                            <option value="3624559275">PRODUCTOS HARMONY S.G.R. </option>


                            <option value="627335384">CONFEDERAR NEA S.G.R.</option>


                            <option value="2111570369">ALIANZA S.G.R. En Formacion</option>

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

