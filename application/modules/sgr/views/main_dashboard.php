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


            <li class="pull-right perfil">
                <i class="{rol_icono}"></i> <strong>{if fre_session} FRE {else}  {sgr_nombre}{/if} </strong> <span class="label label">  {username}</span> |
            </li>

            {if fre_session}
            <li class="pull-right perfil" ><a  href="{base_url}sgr/exit_fre" ><span class="label label-danger">CERRAR<strong> {sgr_nombre}</strong></span></a></li>
            {/if} 
        </ul>

    </div>

   

    <!-- ============= Barra Ministerio  -->
     <div class="header_institucional">
      <img src="{base_url}dashboard/assets/img/logo_presidencia.png" class="presidencia_logo">
      <img src="{base_url}dashboard/assets/img/logo_secretaria.png" class="secretaria_logo">
    </div>



    <!-- header -->
    <section class="content-header">
        <h3>SGR SIPRIN Dashboard.</h3>                
    </section>





    <!-- links -->
    <section>

        


        {if fre_list} 

          <!-- FRE -->
          <div class="col-lg-12">
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h4>FONDOS DE RIESGO ESPEC&Iacute;FICOS 
                        <small>Seleccione</small>
                    </h4>    
                </div><!-- /.box-header -->
                <div class="box-body" class="small-box">
                    <div style="margin-left:20%">
                        <form method="post" class="form-extra" id="select_anexos_fre">
                            <div class="col-lg-9 input-group input-group-sm">          
                                <select class="form-control">
                                <option value="">{sgr_nombre}</option>
                                    {fre_list}
                                </select>
                                <span class="input-group-btn">
                                <button class="btn btn-sm btn-info btn-flat pull-right"  id="anexo_fre" type="submit">seleccionar</button>
                                </span>
                            </div>
                            <div class="icon">
                                <i class="fa fa-user"></i>
                            </div> 
                        </form>
                    </div>   
                </div>        
            </div>
          </div>
        <!-- FRE -->
        {/if}
   
        <!-- ANEXOS -->
        <div class="col-lg-7">
            <div class="small-box bg-teal">
                <div class="inner">
                    <h4>ANEXOS
                        <small>Seleccione</small>
                    </h4>    
                </div><!-- /.box-header -->
                <div class="box-body" class="small-box">
                    <div style="margin-left:20%">
                        <form method="post" class="form-extra" id="select_anexos">
                            <div class="icon">
                              <i class="fa fa-file-excel-o"></i>
                            </div> 
                            <div class="col-lg-9 input-group input-group-sm">          
                                <select class="form-control">
                                    {anexo_list}
                                </select>
                                <span class="input-group-btn">
                                <button class="btn btn-sm btn-info btn-flat pull-right" id="anexo" type="submit">Acceder</button>
                                </span>
                            </div>                            
                        </form>
                    </div>   
                </div>        
            </div>
        </div>
        <!-- END ANEXOS --> 
        

        <!-- REPORTES -->
        <div class="col-lg-5">
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h4>REPORTES
                        <small>Por Anexo</small>
                    </h4>    
                </div><!-- /.box-header -->
                <div class="box-body" class="small-box">
                    <div style="margin-left:20%">
                        <form method="post" class="form-extra" id="select_reports">
                            <div class="icon">
                                <i class="fa fa-file-text-o"></i>
                            </div> 
                            <div class="col-lg-9 input-group input-group-sm">          
                                <select class="form-control">
                                    <option value="{module_url}reports/anexo_code/06">Anexo 06 </option>
                                    <option value="{module_url}reports/anexo_code/061">Anexo 06.1 </option>
                                    <option value="{module_url}reports/anexo_code/062">Anexo 06.2 </option>
                                    <option value="{module_url}reports/anexo_code/12">Anexo 12 </option>
                                    <option value="{module_url}reports/anexo_code/125">Anexo 12.5 </option>
                                    <option value="{module_url}reports/anexo_code/126">Anexo 12.6 </option>
                                    <option value="{module_url}reports/anexo_code/13">Anexo 13 </option>
                                    <option value="{module_url}reports/anexo_code/14">Anexo 14 </option>
                                    <option value="{module_url}reports/anexo_code/141">Anexo 14.1 </option>
                                    <option value="{module_url}reports/anexo_code/15">Anexo 15 </option>
                                    <option value="{module_url}reports/anexo_code/16">Anexo 16 </option>
                                    <option value="{module_url}reports/anexo_code/201">Anexo 20.1 </option>
                                    <option value="{module_url}reports/anexo_code/202">Anexo 20.2 </option>
                                </select>
                                <span class="input-group-btn">
                                <button class="btn btn-sm btn-warning btn-flat pull-right" id="report" type="submit">Consultar</button>
                                </span>
                            </div>
                            
                        </form>
                    </div>   
                </div> 
            </div>       
        </div>
        <!-- END REPORTES -->   

        
    </section>
    <!-- end links -->
   
    <section class="content-header">
        <h3>HERRAMIENTAS</h3>                     
    </section>



    <section id="col1" class="col-lg-5 connectedSortable ui-sortable">

        <div class="box box">   
            <div class="box-header">
                <h3 class="box-title"> DESCARGAS  
                    <small></small>
                </h3>        
            </div><!-- /.box-header -->

            <div class="box-body" class="small-box">
                <div>
                     <ul class="nav nav-stacked">
                        <li>
                            <a href="{module_url}assets/download/modelos.zip" target="_self">Descargar Modelos de Importación</a> 
                        </li>
                        <li>
                         <a href="{module_url}assets/download/documentacion.zip" target="_self">Descargar Documentación</a>
                        </li>
                    </ul>
                </div>   
            </div>        
        </div>
        <div class="box box">   
            <div class="box-header">
                <h3 class="box-title"> CENTRAL DEUDORES 
                    <small></small>
                </h3>        
            </div><!-- /.box-header -->

            <div class="box-body" class="small-box">
                <div>
                     <ul class="nav nav-stacked">
                        <li><a href="{module_url}central" target="_blank">Consultar Central Deudores</a></li>
                    </ul>
                </div>   
            </div>        
        </div>
    </section>





<section id="col2" class="col-lg-7 connectedSortable ui-sortable">
 <div class="box box">   
     <div class="box-header">
      <h3 class="box-title">CONSULTA
        <small>Tipo de Socio por C.U.I.T.</small>
    </h3>    
  </div><!-- /.box-header -->

  <div class="box-body" class="small-box">
   <div style="margin-left:20%">
     <form method="post" class="form-extra" id="consult">
        <div class="col-lg-9 input-group input-group-sm">
          <span class="input-group-addon">Ingrese la C.U.I.T.</span>
          <input type="text" placeholder="ej: XXXXXXXXXXX" name="cuit" id="cuit" class="form-control" maxlength="11">
          <span class="input-group-btn">
            <button class="btn btn-info btn-flat btn-search" type="submit">Buscar </button>
        </span>
    </div>
</form>
</div>   


</div>        
</div>

</section>


<section>

<div id="loading" class="col-md-7" style="display:none;margin-top:20px">
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


  <!-- A -->
  <div id='A' style="display:none;margin-top:20px" class='cuit_all'>
    <div class="col-md-7">
      <!-- VINCULADAS -->
      <div class="box box-success">
        <div class="box-header with-border">
          <h2 class="box-title"><span id="a_cuit"></span></h2><h3>  <span id="a_rs"></span><p><small> Es Socio Partícipe.</small></h3>

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

<!-- B -->
  <div id='B' style="display:none;margin-top:20px" class='cuit_all'>
    <div class="col-md-7">
      <!-- VINCULADAS -->
      <div class="box box-success">
        <div class="box-header with-border">
          <h2 class="box-title"><span id="b_cuit"></span></h2><h3>  <span id="b_rs"></span><p><small> Es Socio Protector.</small></h3>

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
    <div class="col-md-7">
      <!-- VINCULADAS -->
      <div class="box box-danger">
        <div class="box-header with-border">
          <h2 class="box-title"><span id="e_cuit"></span></h2><h3>  SIN DATO<p><small> No es Socio Registrado.</small></h3>

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
        <script src="{base_url}sgr/assets/jscript/dashboard.js"></script>
        <script src="{base_url}sgr/assets/jscript/form_dashboard.js"></script>
        <!--CALENDAR -->
        <script src='{base_url}jscript/jquery/ui/jquery-ui-1.10.2.custom/jquery-ui-1.10.2.custom.min.js'></script>
        <!--<script src='{base_url}dashboard/assets/bootstrap-wysihtml5/js/AdminLTE/app.js'></script>-->
        <script src='{base_url}jscript/jquery/plugins/Form/jquery.form.min.js'></script>


    </body>

    </html>

</div>

