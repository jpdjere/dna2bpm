    <!DOCTYPE html>
    <html lang="en">

    <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
      <title>{title}</title>

      <!-- Bootstrap -->
      <link href="{base_url}/dashboard/assets/bootstrap-wysihtml5/css/bootstrap.min.css" rel="stylesheet">
      <link href="{base_url}sgr/assets/css/dashboard.css" rel="stylesheet">    
      <!-- Theme style -->
        <!--<link href="{base_url}/dashboard/assets/bootstrap-wysihtml5/css/AdminLTE.css" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="{base_url}dashboard/assets/bootstrap-wysihtml5/css/font-awesome-4.4.0/css/font-awesome.css" rel="stylesheet" type="text/css" />
    </head>

    <body class="skin-blue sidebar-mini sidebar-collapse">


        <div class='container'>
          <div id="barra_user" class="row test">
            <ul class="breadcrumb" style="margin-bottom:0px;padding-bottom:0px; align=right" > 
               <li class="pull-right perfil"><a  href="{base_url}user/logout"><span class="label label-info">SALIR</span></a>  
        </ul>

    </div>

   

    <!-- ============= Barra Ministerio  -->
     <div class="header_institucional">
      <img src="{base_url}dashboard/assets/img/logo_presidencia.png" class="presidencia_logo">
      <img src="{base_url}dashboard/assets/img/logo_secretaria.png" class="secretaria_logo">
    </div>



    <!-- header -->
    <section class="content-header">
        <h1>{title}</h1>                
    </section>

 <section class="content-header">
        <h1></h1>                
    </section>

   



  



<section id="col2" class="col-lg-12 connectedSortable ui-sortable">
 <div class="box box">   
     <div class="box-header">
      <h3 class="box-title">Consulta 
        <small>Afip por C.U.I.T.</small>
    </h3>    
  </div><!-- /.box-header -->

  <div class="box-body" class="small-box">
   <div style="margin-left:20%">
     <form method="post" class="form-extra" id="consult">
        <div class="col-lg-9 input-group input-group-sm">
          <span class="input-group-addon">Ingrese la C.U.I.T.</span>
          <input type="text" placeholder="ej: XXXXXXXXXXX" name="cuit" id="cuit" class="form-control" maxlength="11">
          
          <span class="input-group-addon">Ingrese N° Transacción (1272)</span>
          <input type="text" placeholder="ej: XXXXXXX" name="transaccion" id="transaccion" class="form-control" maxlength="11">

          <span class="input-group-btn">
            <button class="btn btn-info btn-flat btn-search" type="submit">Consultar</button>
        </span>
    </div>
</form>
</div>   


</div>        
</div>


</section>



</section>


<section>

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


  <!-- success_update -->
  <div id='success_update' style="display:none;margin-top:20px" class='cuit_all'>
    <div class="col-md-12">
      <!-- VINCULADAS -->
      <div class="box box-success">
        <div class="box-header with-border">
          <h2 class="box-title"><span id="a_cuit"></span></h2><h3>  <span id="a_rs"></span><p><small>OK</small></h3>

          <div class="box-tools pull-right">
             <!-- <span class="badge bg-yellow" title="" data-toggle="tooltip" data-original-title="Total">3</span>                -->
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
    <div class="col-md-12">
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
    <div class="col-md-12">
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



</section>



<!-- JS Global -->
<script>
            //-----declare global vars
            var base_url = '{base_url}';
        </script>

        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="{base_url}jscript/jquery/jquery.min.js"></script>

        <script src="{base_url}jscript/jquery/plugins/jquery-validation-1.15.0/jquery.validate.min.js"></script>           
        <script src="{base_url}perfil/assets/jscript/form_expertos.js"></script>
        <!--CALENDAR -->
        <script src='{base_url}jscript/jquery/ui/jquery-ui-1.10.2.custom/jquery-ui-1.10.2.custom.min.js'></script>
        <!--<script src='{base_url}dashboard/assets/bootstrap-wysihtml5/js/AdminLTE/app.js'></script>-->
        <script src='{base_url}jscript/jquery/plugins/Form/jquery.form.min.js'></script>


    </body>

    </html>

</div>

