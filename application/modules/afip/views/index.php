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
      <link href="{base_url}afip/assets/css/dashboard.css" rel="stylesheet">    
      <!-- Theme style -->
        <!--<link href="{base_url}/dashboard/assets/bootstrap-wysihtml5/css/AdminLTE.css" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="{base_url}dashboard/assets/bootstrap-wysihtml5/css/font-awesome-4.4.0/css/font-awesome.css" rel="stylesheet" type="text/css" />
      </head>

      <body>


        <div class='container'>
          <div id="barra_user" class="row test">
            <ul class="breadcrumb" style="margin-bottom:0px;padding-bottom:0px; align=right" > 
              <li class="pull-right perfil">
                <a href="{base_url}user/logout"><strong>SALIR</strong></a>
              </li>
            </ul>
          </div>
          <!-- ============= Barra Ministerio  -->
          <div class='row'>
            {logobar}
          </div>


          <!-- header -->
          <section class="content-header">
            <h1>AFIP DashBoard</h1>                
          </section>




          <!-- links -->



          <div class="col-lg-3 ">
           <div class="small-box bg-aqua">
            <div class="inner">
              <h3><span id='status_F1272'></span></h3>

              <p>F.1272</p>
            </div>
            <div class="icon">
              <i class="fa fa-bookmark-o"></i>
            </div>            
          </div>
        </div>
        <section class="">
          <div class=" ull-height">
            <div class="col-lg-3 ">
              <div class="small-box bg-green">
                <div class="inner">
                  <h3>100<sup style="font-size: 20px">%</sup></h3>

                  <p>PROCESS</p>
                </div>
                <div class="icon">
                  <i class="fa fa-terminal"></i>
                </div>

              </div>

            </div>
            <div class="col-lg-3 ">
              <div class="small-box bg-yellow">
                <div class="inner">
                  <h3><span id='status_F1273'></span></h3>

                  <p>F.1273</p>
                </div>
                <div class="icon">
                  <i class="fa fa-th-large"></i>
                </div>              
              </div>
            </div>

            <div class="col-lg-3 ">
              <div class="small-box bg-red">
                <div class="inner">
                 <h3>100<sup style="font-size: 20px">%</sup></h3>

                 <p>SETI</p>
               </div>
               <div class="icon">
                <i class="fa fa-signal"></i>
              </div>

            </div>
          </div>
        </div>

        <section id="col1" class="col-lg-6 connectedSortable ui-sortable">
         <div class="box box-widget">
          <!-- Add the bg color to the header using any of the bg-* classes -->
          <div class="bg-aqua" style="padding:10px">
            <div>

            </div>
            <!-- /.widget-status -->
            <i class="fa fa-th"></i>    STATUS
          </div>
          <div class="box-footer no-padding">
            <ul class="nav nav-stacked">
              <li><a href="{base_url}afip/consultas/queue/ready" target="_blank">Ready <span class="pull-right badge bg-blue" id='status_ready'></span></a></li>
              <li><a href="{base_url}afip/consultas/queue/waiting" target="_blank">Waiting <span class="pull-right badge bg-aqua" id='status_waiting'></span></a></li>
              <li><a href="{base_url}afip/consultas/queue/revision" target="_blank">Revision <span class="pull-right badge bg-yellow"  id='status_revision'></span></a></li>
              <li><a href="{base_url}afip/consultas/queue/" target="_blank">Queue <span class="pull-right badge bg-red" id='status_queue'></span></a></li>
              <!-- VINCULADAS -->
              <!--<li><a href="{base_url}afip/consultas/vinculadas" alt="Vinculadas sin Detalles">Vinculadas sin Detalles <span class="pull-right badge bg-red" id='count_vinculadas'></span></a></li>-->

            </ul>
          </div>


        </div>



      </section>
      <section id="col2" class="col-lg-6 connectedSortable ui-sortable">
       <div class="box box-info">   
        <div style="cursor: move;" class="box-header">
          <i class="ion ion-clipboard"></i>
          <h3 class="box-title">Consulta de Estado por Nro de C.U.I.T.</h3>
          <div class="box-tools pull-right">          
          </div>
        </div><!-- /.box-header -->

        <div class="box-body" class="small-box">
         <div style="margin-left:20%">
           <form method="post" class="form-extra" >
            <div class="col-lg-9 input-group input-group-sm">
              <span class="input-group-addon">Ingrese la C.U.I.T.</span>
              <input type="text" placeholder="ej: XXXXXXXXXXX" name="cuit" id="cuit" class="form-control">
              <span class="input-group-btn">
                <button class="btn btn-info btn-flat btn-search" type="submit">Buscar</button>
              </span>
            </div>
          </form>
        </div>   

       
     </div>        
   </div>


 </section>
</section>


<section>
  <!-- READY -->
  <div id='ready' style="display:none;margin-top:20px" class='cuit_all'>
    <div class="col-md-6">
      <!-- VINCULADAS -->
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"> La C.U.I.T. <span id="s_cuit"></span> ya fue informada con exito F.1273.</h3>

          <div class="box-tools pull-right">
           <!-- <span class="badge bg-yellow" title="" data-toggle="tooltip" data-original-title="Total">3</span>                -->
         </div>
       </div>
       <div class="box-body">
         <div class="right">

          <ul class="nav nav-stacked">
            <li>
              <a href="#" class="source" target="_blank">Ver RAW de la CUIT
                <span class="pull-right badge bg-green"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i></span>
              </a>
            </li>

            <li>
             <a href="#" class="certificado">Generar certificado del beneficio entregado de PyME
              <span class="pull-right badge bg-green"><i class="fa fa-print" aria-hidden="true"></i></span>
            </a>
          </li>

          <li>
              <a>Fecha de Entrada (F1272)
                <span class="pull-right badge bg-green"><i id="fecha_entrada" class="fa fa-calendar" aria-hidden="true"></i></span>
              </a>
            </li>

            <li>
             <a >Procesado Fecha
              <span class="pull-right badge bg-green"><i id="fecha_proceso" class="fa fa-calendar" aria-hidden="true"></i></span>
            </a>
          </li>

            <li>
             <a >Fecha de Salida (F1273)
              <span class="pull-right badge bg-green"><i id="fecha_salida" class="fa fa-calendar" aria-hidden="true"></i></span>
            </a>
          </li>                    
          
        </ul>       
      </div>
    </div>
  </div>          
</div>
</div>


 <!-- WAITING -->
  <div id='waiting' style="display:none;margin-top:20px" class='cuit_all'>
    <div class="col-md-6">
     
      <div class="box box-aqua">
        <div class="box-header with-border">
          <h3 class="box-title"> La C.U.I.T. <span id="w_cuit"></span> se encuentra en espera. </h3>

          <div class="box-tools pull-right">
           <!-- <span class="badge bg-yellow" title="" data-toggle="tooltip" data-original-title="Total">3</span>                -->
         </div>
       </div>
       <div class="box-body">
         <div class="right">
          
          <ul class="nav nav-stacked">
            <li>
              <a href="#" class="source" target="_blank">Ver RAW de la CUIT en Espera
                <span class="pull-right badge bg-aqua"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i></span>
              </a>
            </li>
        </ul>       
      </div>
    </div>
  </div>          
</div>
</div>

 <!-- REVISION --> 
  <div id='revision' style="display:none;margin-top:20px" class='cuit_all'>
    <div class="col-md-6">
     
      <div class="box box-warning">
        <div class="box-header with-border">
          <h3 class="box-title">  La C.U.I.T. <span id="r_cuit"></span> se encuentra en Revisión.  </h3>

          <div class="box-tools pull-right">
           <!-- <span class="badge bg-yellow" title="" data-toggle="tooltip" data-original-title="Total">3</span>                -->
         </div>
       </div>      
       <div class="box-body">
         <div class="right">
          
          <ul class="nav nav-stacked">
            <li>
              <a href="#" class="source" target="_blank">Ver RAW de la CUIT en Revisión
                <span class="pull-right badge bg-yellow"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i></span>
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
          <h3 class="box-title">La C.U.I.T. <span id="e_cuit"></span> no está pendiente de Revisión ni en Espera</h3>

          <div class="box-tools pull-right">
           <!-- <span class="badge bg-yellow" title="" data-toggle="tooltip" data-original-title="Total">3</span>                -->
         </div>
       </div>      
       
  </div>          
</div>
</div>

</section>

<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Modal header</h3>
    </div>
    <div class="modal-body">
        <p>One fine body…</p>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
        <button class="btn btn-primary">Save changes</button>
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
          <script src="{base_url}afip/assets/jscript/dashboard.js"></script>
          <script src="{base_url}afip/assets/jscript/form_consultas_cuit.js"></script>
          <!--CALENDAR -->
          <script src='{base_url}jscript/jquery/ui/jquery-ui-1.10.2.custom/jquery-ui-1.10.2.custom.min.js'></script>
          <script src='{base_url}dashboard/assets/bootstrap-wysihtml5/js/AdminLTE/app.js'></script>
          <script src='{base_url}jscript/jquery/plugins/Form/jquery.form.min.js'></script>

          <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

        </body>

        </html>