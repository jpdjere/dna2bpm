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
              <li class="pull-right perfil">
                <a href="{base_url}user/logout"><strong>SALIR</strong></a>
            </li>
        </ul>
    </div>
    <!-- ============= Barra Ministerio  -->
     <div class="header_institucional">
      <img src="{base_url}dashboard/assets/img/logo_presidencia.png" class="presidencia_logo">
      <img src="{base_url}dashboard/assets/img/logo_secretaria.png" class="secretaria_logo">
    </div>



    <!-- header -->
    <section class="content-header">
        <h1>SGR DashBoard</h1>                
    </section>




    <!-- links -->





    <section id="col1" class="col-lg-6 connectedSortable ui-sortable">
        <div class="box box-info">   
         <div class="box-header">
          <h3 class="box-title">REPORTES
            <small>Por Anexo</small>
        </h3>        
      </div><!-- /.box-header -->

      <div class="box-body" class="small-box">
       <div>
         <ul class="nav nav-stacked">
            <li><a href="{module_url}reports/anexo_code/06" target="_blank">Reporte Anexo 06</a></li>
            <li><a href="{module_url}reports/anexo_code/061" target="_blank">Reporte Anexo 06.1</a></li>
            <li><a href="{module_url}reports/anexo_code/062" target="_blank">Reporte Anexo 06.2</a></li>
            <li><a href="{module_url}reports/anexo_code/12" target="_blank">Reporte Anexo 12</a></li>
            <li><a href="{module_url}reports/anexo_code/125" target="_blank">Reporte Anexo 12.5</a></li>
            <li><a href="{module_url}reports/anexo_code/126" target="_blank">Reporte Anexo 12.6</a></li>
            <li><a href="{module_url}reports/anexo_code/13" target="_blank">Reporte Anexo 13</a></li>
            <li><a href="{module_url}reports/anexo_code/14" target="_blank">Reporte Anexo 14</a></li>
            <li><a href="{module_url}reports/anexo_code/141" target="_blank">Reporte Anexo 14.1</a></li>
            <li><a href="{module_url}reports/anexo_code/15" target="_blank">Reporte Anexo 15</a></li>
            <li><a href="{module_url}reports/anexo_code/16" target="_blank">Reporte Anexo 16</a></li>
            <li><a href="{module_url}reports/anexo_code/201" target="_blank">Reporte Anexo 20.1</a></li>
            <li><a href="{module_url}reports/anexo_code/202" target="_blank">Reporte Anexo 20.2</a></li>
        </ul>
    </div>   


</div>        
</div>

</section>
<section id="col2" class="col-lg-6 connectedSortable ui-sortable">
 <div class="box box-info">   
     <div class="box-header">
      <h3 class="box-title">CONSULTA
        <small>Tipo de Socio por C.U.I.T.</small>
    </h3>    
  </div><!-- /.box-header -->

  <div class="box-body" class="small-box">
   <div style="margin-left:20%">
     <form method="post" class="form-extra" >
        <div class="col-lg-9 input-group input-group-sm">
          <span class="input-group-addon">Ingrese la C.U.I.T.</span>
          <input type="text" placeholder="ej: XXXXXXXXXXX" name="cuit" id="cuit" class="form-control">
          <span class="input-group-btn">
            <button class="btn btn-info btn-flat btn-search" type="submit">Buscar </button>
        </span>
    </div>
</form>
</div>   


</div>        
</div>


</section>



</section>


<section>

<div id="loading" class="col-md-6" style="display:none;margin-top:20px">
          <div class="box box-danger">
            <div class="box-header">
              <h3 class="box-title">Loading ...</h3>
            </div>
            <div class="box-body">
              Actualizando información
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


  <!-- A -->
  <div id='A' style="display:none;margin-top:20px" class='cuit_all'>
    <div class="col-md-6">
      <!-- VINCULADAS -->
      <div class="box box-success">
        <div class="box-header with-border">
          <h2 class="box-title"><span id="a_cuit"></span> </h2>

          <div class="box-tools pull-right">
             <!-- <span class="badge bg-yellow" title="" data-toggle="tooltip" data-original-title="Total">3</span>                -->
         </div>
     </div>
     <div class="box-body">
       <div class="right">

          <ul class="nav nav-stacked">         

        <li>
           <a href="#" class="certificado"><span id="a_rs"></span><p><small> Es Socio Partícipe.</small></p>
              <span class="pull-right badge bg-green"><i class="fa fa-arrow-circle-right"></i> Mas info</span>
          </a>
      </li>
  </ul>       
</div>
</div>
</div>          
</div>
</div>

<!-- B -->
  <div id='B' style="display:none;margin-top:20px" class='cuit_all'>
    <div class="col-md-6">
      <!-- VINCULADAS -->
      <div class="box box-success">
        <div class="box-header with-border">
          <h2 class="box-title"><span id="b_cuit"></span></h2>

          <div class="box-tools pull-right">
             <!-- <span class="badge bg-yellow" title="" data-toggle="tooltip" data-original-title="Total">3</span>                -->
         </div>
     </div>
     <div class="box-body">
       <div class="right">

          <ul class="nav nav-stacked">
        <li>
           <a href="#" class="certificado"><span id="b_rs"></span><p><small> Es Socio Protector.</small></p>
              <span class="pull-right badge bg-green"><i class="fa fa-arrow-circle-right"></i> Mas Info</span>
          </a>
      </li>
  </ul>       
</div>
</div>
</div>          
</div>
</div>



<!-- ERROR --> 
 <!-- MSGs 
        <div id='msg_error' style="display:none;margin-top:20px" class='cuit_all'>
          <div class="alert alert-danger" role="alert">
           La C.U.I.T. <span id="e_cuit"></span> no está pendiente de Revisión ni en Espera
         </div>
       </div>
   -->
   <div id='msg_error' style="display:none;margin-top:20px" class='cuit_all'>
    <div class="col-md-6">

      <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title">La C.U.I.T. <span id="e_cuit"></span> No es Socio registrado.</h3>

          <div class="box-tools pull-right">
             <!-- <span class="badge bg-yellow" title="" data-toggle="tooltip" data-original-title="Total">3</span>                -->
         </div>
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
        <script src="{base_url}sgr/assets/jscript/dashboard.js"></script>
        <script src="{base_url}sgr/assets/jscript/form_dashboard.js"></script>
        <!--CALENDAR -->
        <script src='{base_url}jscript/jquery/ui/jquery-ui-1.10.2.custom/jquery-ui-1.10.2.custom.min.js'></script>
        <!--<script src='{base_url}dashboard/assets/bootstrap-wysihtml5/js/AdminLTE/app.js'></script>-->
        <script src='{base_url}jscript/jquery/plugins/Form/jquery.form.min.js'></script>


    </body>

    </html>

</div>

