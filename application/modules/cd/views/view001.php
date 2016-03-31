<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Bootstrap 101 Template</title>
<!--    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">-->
<link href="{module_url}assets/css/print001.css" rel="stylesheet" type="text/css" />


  </head>
  <body>
      <div class="mark" id="mark1"></div>
      <div class="mark" id="mark2"></div>
      <div class="mark" id="mark3"></div>
      
      <div class="mark" id="mark4"></div>
      <div class="mark" id="mark5"></div>
      
<div id="wrapper_legal">
  <div id="talon">
<!--     <legend>TALON</legend>-->
     <span class="remitente_nombre" >{remitente_nombre}</span>
     <span class="remitente_domicilio">{remitente_domicilio}</span>
     <span class="remitente_cpa">{remitente_cpa}</span>
     <span class="remitente_localidad">{remitente_localidad}</span>
     <span class="remitente_provincia">{remitente_provincia}</span>
     <!-- -->
    <span class="destinatario_nombre" >{destinatario_nombre}</span>
    <span class="destinatario_domicilio">{destinatario_domicilio}</span>
    <span class="destinatario_cpa">{destinatario_cpa}</span>
    <span class="destinatario_localidad">{destinatario_localidad}</span>
    <span class="destinatario_provincia">{destinatario_provincia}</span>
   
  </div>
  <div id="cabezal">
<!--    <legend>CABEZAL</legend>-->
     <span class="remitente_nombre" >{remitente_nombre}</span>
     <span class="remitente_domicilio">{remitente_domicilio}</span>
     <span class="remitente_cpa">{remitente_cpa}</span>
     <span class="remitente_localidad">{remitente_localidad}</span>
     <span class="remitente_provincia">{remitente_provincia}</span>
     <!-- -->
    <span class="destinatario_nombre" >{destinatario_nombre}</span>
    <span class="destinatario_domicilio">{destinatario_domicilio}</span>
    <span class="destinatario_cpa">{destinatario_cpa}</span>
    <span class="destinatario_localidad">{destinatario_localidad}</span>
    <span class="destinatario_provincia">{destinatario_provincia}</span>
  </div>
  <div id="cuerpo">
<!--    <legend>CUERPO</legend>-->
    {cuerpo}
     </div>
</div>
  </body>
</html>