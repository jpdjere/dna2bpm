<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Portal de Servicios</title>

    <!-- Base -->
<!--     <base href="http://localhost/portal/index.php"> -->


    <!-- Bootstrap -->
    <link href="/sepyme/css/bootstrap.css" rel="stylesheet">

    <!-- Fonts -->
    <link href="/sepyme/fonts/roboto-fontfacekit/stylesheet.css" rel="stylesheet">

    <link href="/sepyme/fonts/font-awesome-4.6.3/css/font-awesome.min.css" rel="stylesheet">

    <!-- Yamm styles-->
    <link href="/sepyme/yamm3-master/yamm/yamm.css" rel="stylesheet">

    <link href="/sepyme/css/app.css" rel="stylesheet">


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>

 <div class="barra_top">
  <div class="container">
  <div class="row">
    <div class="col-md-3 ">
     <a href="/portal.php"><img src="/sepyme/img/secretaria.png" class="hidden-xs hidden-sm" ></a>
     <a href="http://www.produccion.gob.ar/"><img src="/sepyme/img/presidencia.png"  class="visible-xs visible-sm"></a>
    </div>
    <div class="col-md-6">
    <!-- Nav -->
      <div class="navbar navbar-default yamm">
            <div class="navbar-header">
              <button type="button" data-toggle="collapse" data-target="#navbar-collapse-grid" class="navbar-toggle">
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              </button>
              <a href="/index.php" class="navbar-brand visible-xs visible-sm">
               <img src="/sepyme/img/secretaria.png" style="position:relative;top:-10px"  > 
             </a> 
            </div>
            <div id="navbar-collapse-grid" class="navbar-collapse collapse">
              <ul class="nav navbar-nav navbar-right">
                <!-- Programas y beneficios -->
                <li class="dropdown ">
                 <a href="#" data-toggle="dropdown" class="dropdown-toggle">Programas y beneficios</a>
                  <ul class="dropdown-menu">
                  <div class="yamm-content">
                  {menu_programas}
                    </div>  
                  }
                  </ul>
                </li>

                <!-- Agenda -->
                <li class="dropdown "><a href="#" data-toggle="_dropdown" class="dropdown-toggle">Agenda</a>
                  <ul class="dropdown-menu">
                  <div class="yamm-content">
                    <?php
                    # ------- Menu  
                    //include('menu_agenda.php'); 
                    ?> 
                    </div>  
                  </ul>
                </li>

                <!-- SEPyME -->
                <li class="dropdown "><a href="#" data-toggle="_dropdown" class="dropdown-toggle">SEPyME</a>
                  <ul class="dropdown-menu">
                  <div class="yamm-content">
                    <?php
                    # ------- Menu  
                    //include('menu_sepyme.php'); 
                    ?> 
                    </div>  
                  </ul>
                </li>


              </ul>
            </div>
      </div>
      <!-- ./nav -->
     </div>

    <div class="col-md-3  hidden-xs hidden-sm" >
      <img src="/sepyme/img/presidencia.png"  >
    </div>

</div>
</div>
</div>

<!--  ==========   Block 2  ======== -->


<div class="block_registro ">
<div class="container ">
<div class="row" style="padding:80px 0px">
      <!-- === -->
      <div class="col-md-4 col-md-offset-2 ">
        <div class="shadow-box">
          <div class="bg-sepyme-3 text-center text-white shadow-box-head" style="min-height:60px;padding-top:10px;">
              <h4 class=" ">Ingresá a la Plataforma SEPyME</h4>
          </div>
          <div class="shadow-box-body bg-white" style="min-height:290px">
				<form id="formAuth" action="{authUrl}" method="post">
				  <div class="form-group">
				    <input type="text" name="username" class="form-control"  placeholder="CUIT/CUIL">
				  </div>
				  <div class="form-group">
				    <input type="password" name="password" class="form-control"  placeholder="Contraseña">
				  </div>
				  <div class="checkbox">
				  <label>
				    <input type="checkbox" value="1">
				    Recordarme
				  </label>
				</div>
			  	<div class="form-group">
					<button type="submit" class="btn btn-success center-block " style="width:50%"  href="#"><strong>Ingresar </strong></button>
				  </div>

				  <p class="text-center"><a href="http://dna2.produccion.gob.ar/dna2/login.php?reset=1">&iquest;Olvidaste tu CUIT/CUIL o contraseña?</a></p>
				</form>
            
         </div>
        </div>
      </div>

      <!-- === -->
      <div class="col-md-4 ">
        <div class="shadow-box" >
          <div class="bg-sepyme-3 text-center text-white shadow-box-head " style="min-height:60px;padding-top:10px;background-color:transparent">
          </div>
          <div class="shadow-box-body bg-white text-centers radius-top" style="min-height:290px">
           <h3 class="text-center">&iquest;Sos usuario nuevo?</h3>
           <p>Para acceder a los programas y beneficios de la SEPyME registrate acá</p>
           <br>
            <a type="submit" class="btn btn-success center-block " style="width:80%;background-color:#999;border:none"  href="/sepyme/registro.php"><strong>Registrarme </strong></a>
         </div>
        </div>
      </div>


</div>
</div>
</div>


<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="/sepyme/js/bootstrap.min.js"></script>

<script>

$(document).ready(function() {

$(document).on('click', '.yamm .dropdown-menu', function(e) {
  e.stopPropagation()
})


});
</script>

<!--  ==========   Footer ======== -->
<div id="wrapper_footer">
<div class="container" >
<footer id="site-footer" role="contentinfo" >
      
<div class="row" style="margin-top:10px">
 <div class="col-xs-12 col-sm-12 col-md-12 logo-footer text-center"><a href="http://www.produccion.gob.ar"><img src="/sepyme/img/logo_ministerio_BW.png" width="230"></a></div>
</div>
<div class="row" style="margin-top:10px">
  <div class="col-xs-12 col-sm-12 col-md-12 text-gray text-center">2016 | Ministerio de Producción | Hipólito Yrigoyen 250 | (C1086AAB) CABA | Tel. 0800.333.7963</div>

  <!-- Código opcional para limpiar las columnas XS en caso de que el
       contenido de todas las columnas no coincida en altura -->
  <div class="clearfix visible-xs"></div>
</div>
</footer>
</div>
</div>
