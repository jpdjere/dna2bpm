<div class="box">
    <div class="box-header">
        <h3 class="box-title">Formulario Reportes POA</h3>
    </div><!-- /.box-header -->
    <div class="box-body">
       
<div class="row">

          <div class="col-md-12 form-group">
          
          <form id="form" method="POST" action="{module_url}poa/save/" >
              
            <div class="form-row">
              <div class="col-xs-4 form-group required">
                <label class="control-label">Seleccione Área</label>
                {areas}
              </div>
            </div>  
            <div class="form-row">
              <div class="col-xs-4 form-group required">
                <label class="control-label">Componente</label>
                {componentes}
              </div>
            </div>  
            <div class="form-row">
              <div class="col-xs-4 form-group required">
                <label class="control-label">Subcomponente</label>
                {subcomponentes}
              </div>
            </div>  
            <div class="col-md-12">
            <h4 style="margin-top: 20px">Indique si es contratado o no</h4>
            <hr class="featurette-divider">
          </div>
            <div class="form-row">
                <div class="col-xs-12 form-group card required">
                  <label class="checkbox-inline">
                    <input type="radio" name="CONTRATADO" id="inlineCheckbox1" value="SI"> Si
                  </label>
                  <label class="checkbox-inline">
                    <input type="radio" name="CONTRATADO" id="inlineCheckbox2" value="NO"> No
                  </label>
              </div>
            </div>
            
          
        </div>
  </div>
<div class="row">
  <div style="margin-top: 60px" class="col-md-12">
<div class="stepwizard">
    <div class="stepwizard-row setup-panel">
        <div class="stepwizard-step">
            <a href="#step-1" type="button" class="btn btn-primary btn-circle">1</a>
            <p>Paso 1</p>
        </div>
        <div class="stepwizard-step">
            <a href="#step-2" type="button" class="btn btn-default btn-circle" disabled="disabled">2</a>
            <p>Paso 2</p>
        </div>
        <div class="stepwizard-step">
            <a href="#step-3" type="button" class="btn btn-default btn-circle" disabled="disabled">3</a>
            <p>Paso 3</p>
        </div>
    </div>
</div>

    <div class="row setup-content" id="step-1">
        <div class="col-xs-12">
                <h3>Indicador Producto</h3>
                 <hr class="featurette-divider">
                <div class="spacer20"></div>
                <div class="col-xs-12 form-group">
                    <label class="control-label">Unidad Medida</label>
                    <input type="text" class="form-control"/>
                </div>
              <div class="col-xs-2 form-group cvc required">
                <label class="control-label">TI</label>
                <input id="IP_TI" name="IP_TI" type="text" size="4" value="0" placeholder="0" class="form-control">
              </div>
              <div class="col-xs-2 form-group expiration required">
                <label class="control-label">TII</label>
                <input id="IP_TII" name="IP_TII" type="text" size="2" value="0" class="form-control" placeholder="0">
              </div>
              <div class="col-xs-2 form-group expiration required">
                <label class="control-label">TIII</label>
                <input id="IP_TIII" name="IP_TIII" type="text" size="4" value="0" class="form-control" placeholder="0">
              </div>
              <div class="col-xs-2 form-group expiration required">
                <label class="control-label">TIV</label>
                <input id="IP_TIV" name="IP_TIV" type="text" size="4" value="0" class="form-control" placeholder="0">
              </div>
                <div class="col-xs-4 form-group">
                    <label class="control-label">Total</label>
                    <input id="IP_TOTAL" name="IP_TOTAL" type="text" readonly="readonly" class="form-control"/>
                </div>
                <div class="col-lg-12">
                 <hr class="featurette-divider">
                 </div>
              <div class="col-xs-6 form-group card required">
                <label class="control-label">Costo Unitario</label>
                <input type="text" name="COSTO_UNI" size="20" class="form-control">
              </div>
            
              <div class="col-xs-6 form-group card required">
                <label class="control-label">Inciso ONP</label>
                <input type="text" name="Inciso_ONP" size="20" class="form-control">
                <div class="spacer20"></div>
              </div>
              <h3>Fuente</h3>
               <hr class="featurette-divider">
              <div class="spacer20"></div>
              <div class="col-xs-4 form-group cvc required">
                <label class="control-label">22</label>
                <input type="text" name="FUENTE_22" size="4" class="form-control">
              </div>
              <div class="col-xs-4 form-group expiration required">
                <label class="control-label">11</label>
                <input type="text" name="FUENTE_11" size="2" class="form-control">
              </div>
              <div class="col-xs-4 form-group expiration required">
                <label class="control-label">PYME</label>
                <input type="text" name="FUENTE_PYME" size="4" class="form-control">
              </div>
               <div class="col-lg-12">
                 <hr class="featurette-divider">
                 </div>
                <button class="btn btn-primary nextBtn pull-right" type="button" >Siguiente</button>
        </div>
    </div>
    <div class="row setup-content" id="step-2">
         <div class="spacer30"></div>
        <div class="col-xs-12">
                <div class="col-md-8 col-sm-6 col-xs-12">
                    <h3>T I ($) En Pesos (Estimado)</h3>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12 form-group">
                    <label class="control-label">Cotización en Dolares</label>
                    <input type="text" name="COTIZACION" id="..." value="0" class="form-control">
                </div>
                <div class="col-xs-12">
                 <hr class="featurette-divider">
                 </div>
                <div class="spacer20"></div>
                <div class="col-xs-4 form-group">
                    <label class="control-label">BID</label>
                    <input id="PESO_TI_BID" name="PESO_TI_BID" type="text" value="0" class="form-control"/>
                </div>
                <div class="col-xs-4 form-group cvc required">
                <label class="control-label">Nación</label>
                <input id="PESO_TI_BNA" name="PESO_TI_BNA" type="text" value="0" size="4" class="form-control">
              </div>
              <div class="col-xs-4 form-group expiration required">
                <label class="control-label">Aporte PYME</label>
                <input id="PESO_TI_PYME" name="PESO_TI_PYME" type="text" value="0"size="2"  class="form-control">
                <div class="spacer20"></div>
              </div>
               
             <h3>T II ($)</h3>
                 <hr class="featurette-divider">
                <div class="spacer20"></div>
                <div class="col-xs-4 form-group">
                    <label class="control-label">BID</label>
                    <input id="PESO_TII_BID" name="PESO_TII_BID" type="text" value="0" class="form-control"/>
                </div>
                <div class="col-xs-4 form-group cvc required">
                <label class="control-label">Nación</label>
                <input id="PESO_TII_BNA" name="PESO_TII_BNA" type="text" size="4" value="0" class="form-control">
              </div>
              <div class="col-xs-4 form-group expiration required">
                <label class="control-label">Aporte PYME</label>
                <input id="PESO_TII_PYME" name="PESO_TII_PYME" type="text" size="2" value="0"  class="form-control">
                <div class="spacer20"></div>
              </div>
               <h3>T III ($)</h3>
                 <hr class="featurette-divider">
                <div class="spacer20"></div>
                <div class="col-xs-4 form-group">
                    <label class="control-label">BID</label>
                    <input id="PESO_TIII_BID" name="PESO_TIII_BID" type="text" value="0"  class="form-control"/>
                </div>
                <div class="col-xs-4 form-group cvc required">
                <label class="control-label">Nación</label>
                <input id="PESO_TIII_BNA" name="PESO_TIII_BNA" type="text" size="4" value="0"  class="form-control">
              </div>
              <div class="col-xs-4 form-group expiration required">
                <label class="control-label">Aporte PYME</label>
                <input id="PESO_TIII_PYME" name="PESO_TIII_PYME" type="text" size="2" value="0" class="form-control">
                <div class="spacer20"></div>
              </div>
              <h3>T IV ($)</h3>
                 <hr class="featurette-divider">
                <div class="spacer20"></div>
                <div class="col-xs-4 form-group">
                    <label class="control-label">BID</label>
                    <input id="PESO_TIV_BID" name="PESO_TIV_BID" type="text"  value="0" class="form-control"/>
                </div>
                <div class="col-xs-4 form-group cvc required">
                <label class="control-label">Nación</label>
                <input  id="PESO_TIV_BNA" name="PESO_TIV_BNA" type="text" size="4"  value="0" class="form-control">
              </div>
              <div class="col-xs-4 form-group expiration required">
                <label class="control-label">Aporte PYME</label>
                <input id="PESO_TIV_PYME" name="PESO_TIV_PYME" type="text" size="2"  value="0"   class="form-control">
                <div class="spacer20"></div>
              </div>
              <h3>Total por Fuente</h3>
                 <hr class="featurette-divider">
                <div class="spacer20"></div>
                <div class="col-xs-4 form-group">
                    <label class="control-label">BID</label>
                    <input id="PESO_TOTFUE_BID" name="PESO_TOTFUE_BID" type="text" readonly="readonly" value="0"  class="form-control"/>
                </div>
                <div class="col-xs-4 form-group cvc required">
                <label class="control-label">Nación</label>
                <input id="PESO_TOTFUE_BNA" name="PESO_TOTFUE_BNA" type="text" readonly="readonly" size="4" value="0"  class="form-control">
              </div>
              <div class="col-xs-4 form-group expiration required">
                <label class="control-label">Aporte PYME</label>
                <input id="PESO_TOTFUE_PYME" name="PESO_TOTFUE_PYME" type="text" readonly="readonly" size="2" value="0"   class="form-control">
              </div>
              <div class="col-lg-12">
                 <hr class="featurette-divider">
                 </div>
                 <div class="col-xs-12 form-group expiration required">
                <label class="control-label">Total</label>
                <input id="PESO_TOTAL" name="PESO_TOTAL" value="0" type="text" size="2" readonly="readonly"  class="form-control">
              </div>
               <div class="col-lg-12">
                 <hr class="featurette-divider">
                 </div>
                <button class="btn btn-primary nextBtn pull-right" type="button" >Siguiente</button>
        </div>
    </div>


    <div class="row setup-content" id="step-3">
         <div class="spacer30"></div>
        <div class="col-xs-12">
                <div class="col-md-8 col-sm-6 col-xs-12">
                    <h3>Indicador Producto</h3>
                </div>
         
                <div class="col-xs-12">
                 <hr class="featurette-divider">
                 </div>
                <div class="spacer20"></div>
                <div class="col-xs-2 form-group cvc required">
                <label class="control-label">TI</label>
                <input id="IP_TI" name="IP_TI_REAL" type="text" size="4" value="0" placeholder="0" class="form-control">
              </div>
              <div class="col-xs-2 form-group expiration required">
                <label class="control-label">TII</label>
                <input id="IP_TII" name="IP_TII_REAL" type="text" size="2" value="0" class="form-control" placeholder="0">
              </div>
              <div class="col-xs-2 form-group expiration required">
                <label class="control-label">TIII</label>
                <input id="IP_TIII" name="IP_TIII_REAL" type="text" size="4" value="0" class="form-control" placeholder="0">
              </div>
              <div class="col-xs-2 form-group expiration required">
                <label class="control-label">TIV</label>
                <input id="IP_TIV" name="IP_TIV_REAL" type="text" size="4" value="0" class="form-control" placeholder="0">
              </div>
                <div class="col-xs-4 form-group">
                    <label class="control-label">Total</label>
                    <input id="IP_TOTAL" name="IP_TOTAL_REAL" type="text" readonly="readonly" class="form-control"/>
                </div>
            <div class="col-xs-12">
                 <hr class="featurette-divider">
                 </div>

                <div class="col-md-12 col-sm-6 col-xs-12">
                    <h3>T I ($) En Pesos (Real) </h3>
                </div>
                <div class="col-xs-12">
                 <hr class="featurette-divider">
                 </div>
                <div class="spacer20"></div>
                <div class="col-xs-4 form-group">
                    <label class="control-label">BID</label>
                    <input id="PESO_TI_BID" name="PESO_TI_BID_REAL" type="text" value="0" class="form-control"/>
                </div>
                <div class="col-xs-4 form-group cvc required">
                <label class="control-label">Nación</label>
                <input id="PESO_TI_BNA" name="PESO_TI_BNA_REAL" type="text" value="0" size="4" class="form-control">
              </div>
              <div class="col-xs-4 form-group expiration required">
                <label class="control-label">Aporte PYME</label>
                <input id="PESO_TI_PYME" name="PESO_TI_PYME_REAL" type="text" value="0"size="2"  class="form-control">
                <div class="spacer20"></div>
              </div>
               
             <h3>T II ($)</h3>
                 <hr class="featurette-divider">
                <div class="spacer20"></div>
                <div class="col-xs-4 form-group">
                    <label class="control-label">BID</label>
                    <input id="PESO_TII_BID" name="PESO_TII_BID_REAL" type="text" value="0" class="form-control"/>
                </div>
                <div class="col-xs-4 form-group cvc required">
                <label class="control-label">Nación</label>
                <input id="PESO_TII_BNA" name="PESO_TII_BNA_REAL" type="text" size="4" value="0" class="form-control">
              </div>
              <div class="col-xs-4 form-group expiration required">
                <label class="control-label">Aporte PYME</label>
                <input id="PESO_TII_PYME" name="PESO_TII_PYME_REAL" type="text" size="2" value="0"  class="form-control">
                <div class="spacer20"></div>
              </div>
               <h3>T III ($)</h3>
                 <hr class="featurette-divider">
                <div class="spacer20"></div>
                <div class="col-xs-4 form-group">
                    <label class="control-label">BID</label>
                    <input id="PESO_TIII_BID" name="PESO_TIII_BID_REAL" type="text" value="0"  class="form-control"/>
                </div>
                <div class="col-xs-4 form-group cvc required">
                <label class="control-label">Nación</label>
                <input id="PESO_TIII_BNA" name="PESO_TIII_BNA_REAL" type="text" size="4" value="0"  class="form-control">
              </div>
              <div class="col-xs-4 form-group expiration required">
                <label class="control-label">Aporte PYME</label>
                <input id="PESO_TIII_PYME" name="PESO_TIII_PYME_REAL" type="text" size="2" value="0" class="form-control">
                <div class="spacer20"></div>
              </div>
              <h3>T IV ($)</h3>
                 <hr class="featurette-divider">
                <div class="spacer20"></div>
                <div class="col-xs-4 form-group">
                    <label class="control-label">BID</label>
                    <input id="PESO_TIV_BID" name="PESO_TIV_BID_REAL" type="text"  value="0" class="form-control"/>
                </div>
                <div class="col-xs-4 form-group cvc required">
                <label class="control-label">Nación</label>
                <input  id="PESO_TIV_BNA" name="PESO_TIV_BNA_REAL" type="text" size="4"  value="0" class="form-control">
              </div>
              <div class="col-xs-4 form-group expiration required">
                <label class="control-label">Aporte PYME</label>
                <input id="PESO_TIV_PYME" name="PESO_TIV_PYME_REAL" type="text" size="2"  value="0"   class="form-control">
                <div class="spacer20"></div>
              </div>
              <h3>Total por Fuente</h3>
                 <hr class="featurette-divider">
                <div class="spacer20"></div>
                <div class="col-xs-4 form-group">
                    <label class="control-label">BID</label>
                    <input id="PESO_TOTFUE_BID" name="PESO_TOTFUE_BID_REAL" type="text" readonly="readonly" value="0"  class="form-control"/>
                </div>
                <div class="col-xs-4 form-group cvc required">
                <label class="control-label">Nación</label>
                <input id="PESO_TOTFUE_BNA" name="PESO_TOTFUE_BNA_REAL" type="text" readonly="readonly" size="4" value="0"  class="form-control">
              </div>
              <div class="col-xs-4 form-group expiration required">
                <label class="control-label">Aporte PYME</label>
                <input id="PESO_TOTFUE_PYME" name="PESO_TOTFUE_PYME_REAL" type="text" readonly="readonly" size="2" value="0"   class="form-control">
              </div>
              <div class="col-lg-12">
                 <hr class="featurette-divider">
                 </div>
                 <div class="col-xs-12 form-group expiration required">
                <label class="control-label">Total</label>
                <input id="PESO_TOTAL" name="PESO_TOTAL_REAL" value="0" type="text" size="2" readonly="readonly"  class="form-control">
              </div>


              <div class="col-lg-12">
                 <hr class="featurette-divider">
                  <div class="pull-right">
                  <button type="submit" class="btn btn-primary nextBtn" name="sigue"> Guardar y seguir</button>
                <button name="finish" class="btn btn-primary nextBtn" id="finish" type="submit"> Guardar y terminar</button>
                 </div>
              </div>
        </div>
    </div>

</form>

</div>
</div>

        
    </div><!-- /.box-body -->
</div>