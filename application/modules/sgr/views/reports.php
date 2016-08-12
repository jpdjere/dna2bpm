       <!DOCTYPE html>
      <html lang="en">

      <head>
         <meta charset="UTF-8" />

        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="{module_url}assets/jscript/bootstrap/css/bootstrap.min.css" />
 
 
        <link rel="stylesheet" href="{module_url}assets/css/font-awesome-4.0.3/css/font-awesome.min.css" />
        <link rel="stylesheet" href="{module_url}assets/jscript/jquery-ui-1.10.2.custom/css/smoothness/jquery-ui-1.10.2.custom.min.css" />  
        <link rel="stylesheet" href="{module_url}assets/css/extra-icons.css" /> 
        <link rel="stylesheet" href="{module_url}assets/jscript/fullcalendar/fullcalendar.css" />
        <link rel="stylesheet" href="{module_url}assets/jscript/datepicker/css/datepicker.css" />
        <link rel="stylesheet" href="{module_url}assets/css/dashboard.css" />
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
          <h3>SGR SIPRIN Reportes.</h3>                
      </section>

      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <!-- Derecha -->
      <ul class="nav navbar-nav navbar-right">

        <li class="dropdown">
             <a href="#" data-toggle="dropdown" data-target="#menu-messages" class="dropdown-toggle">
                <i class="fa fa-file-text"> </i> <span class="text"> Anexos:</span> <span class=""> {anexo_title_cap} </span> <b class="caret"></b>
            </a> 
            <ul class="dropdown-menu">
                {anexo_list}
            </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->



      <!-- Form -->
      <div>
        {form_template}
    </div>
      
  </div>
    <script src="{module_url}assets/jscript/jquery.min.js"></script>
        <script src="{module_url}assets/jscript/bootstrap/js/bootstrap.min.js"></script>
        <script src="{module_url}assets/jscript/bootbox.min.js"></script>
        <script src="{module_url}assets/jscript/datepicker/js/bootstrap-datepicker.js"></script>
        <script src="{module_url}assets/jscript/modernizr.custom.22198.js"></script>
        <!-- Custom JS -->
        <script type="text/javascript">
            //-----declare global vars
            var globals = {inline_js};

        </script>
        {js}
  </body>
</html>
