 <div class="box box"  class="small-box">
     <form method="post" class="form-extra" id="consult">
        <div class="input-group input-group-sm" style="padding:10px">
          <span class="input-group-addon">Ingrese la C.U.I.T.</span>
          <input type="text" placeholder="ej: XXXXXXXXXXX" name="cuit" id="cuit" class="form-control" maxlength="11">
        </div>
        <div class="input-group input-group-sm" style="padding:10px">             
            <span class="input-group-addon">Ingrese N° Transacción (1272)</span>
            <input type="text" placeholder="ej: XXXXXXX" name="transaccion" id="transaccion" class="form-control" maxlength="9">         
        </div>
        <div class="input-group input-group-sm" style="padding:10px">
            <span class="input-group-btn">
              <button class="btn btn-info btn-flat btn-search" type="submit">Consultar</button>
            </span>
        </div>
      </form>
  </div>


  <div id="loading" class="col-md-" style="display:none;margin-top:20px">
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


  <!-- success_update -->
  <div id='success_update' style="display:none;margin-top:20px" class='cuit_all'>
      <div class="col-md-">
        <!-- VINCULADAS -->
        <div class="box box-success">
            <div class="box-header with-border">
              <h2 class="box-title"><span id="a_cuit"></span></h2><h3>  <span id="a_rs"></span><p><small>OK</small></h3>

                <div class="box-tools pull-right">
                 <!-- <span class="badge bg-yellow" title="" data-toggle="tooltip" data-original-title="Total">3</span>-->
                </div>
            </div>
            <div class="box-body">
              <div id='a_vinculado'></div>
            </div>
          </div>          
      </div>
  </div>

<!-- error_transaccion -->
  <div id='error_transaccion' style="display:none;margin-top:20px" class='cuit_all'>
    <div class="col-md-">
      <!-- VINCULADAS -->
      <div class="box box-alert">
        <div class="box-header with-border">
          <h2 class="box-title"><span id="b_cuit"></span></h2><h3>  <span id="b_rs"></span><p><small> El numero de transaccion es invalido</small></h3>

          <div class="box-tools pull-right">
             <!-- <span class="badge bg-yellow" title="" data-toggle="tooltip" data-original-title="Total">3</span>                -->
         </div>
      </div>
      <div class="box-body">
      <div id='b_vinculado'></div> 
      </div>          
      </div>
      </div>
      </div>

<div id='msg_error' style="display:none;margin-top:20px" class='cuit_all'>
    <div>
      <!-- VINCULADAS -->
      <div class="box box-danger">
        <div class="box-header with-border">
          <h2 class="box-title"><span id="e_cuit"></span></h2><h3>  ERROR<p><small> .....</small></h3>

          <div class="box-tools pull-right">
             <!-- <span class="badge bg-yellow" title="" data-toggle="tooltip" data-original-title="Total">3</span>                -->
         </div>
      </div>
      <div class="box-body">
      <div id='b_vinculado'></div> 
      </div>          
      </div>
      </div>
</div>






<!-- JS Global -->
<script>
            //-----declare global vars
            var base_url = '{base_url}';
        </script>

        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="{base_url}jscript/jquery/jquery.min.js"></script>

        <script src="{base_url}jscript/jquery/plugins/jquery-validation-1.15.0/jquery.validate.min.js"></script>           
        <script src="{base_url}perfil/assets/jscript/form_consulta_cuit.js"></script>
        <!--CALENDAR -->
        <script src='{base_url}jscript/jquery/ui/jquery-ui-1.10.2.custom/jquery-ui-1.10.2.custom.min.js'></script>
        <!--<script src='{base_url}dashboard/assets/bootstrap-wysihtml5/js/AdminLTE/app.js'></script>-->
        <script src='{base_url}jscript/jquery/plugins/Form/jquery.form.min.js'></script>


   
