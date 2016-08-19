<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head</p> any other head content must come *after* these tags -->
    <title>PDF</title>   

    <link href="{base_url}/afip/assets/css/style.css" rel="stylesheet">       
    <style type="text/css">
       
        /*logo*/
.secretaria_logo {
    float: right;
    margin: 30px 0 0;
    width: 240px;
}

.presidencia_logo {
    float: left;
    margin: 0;
    width: 370px;
}

.header_institucional {
    border-bottom: 1px solid #ccc;
    padding: 15px;
}
strong{
    text-transform: uppercase;  
}
    </style>
</head>

<body style=" color: #333;
    font-family: Helvetica Neue,Helvetica,Arial,sans-serif;
    font-size: 14px;
    line-height: 1.42857">


     <div class='container'  class="well" align="center">
        <!-- ============= Barra Ministerio  -->
        <div class='row'>
              <div class="header_institucional">                
                <img src="{base_url}dashboard/assets/img/logo_secretaria.png" class="secretaria_logo img-thumbnail"> <span style="width:50px;">               
              </div>
        </div>
        
        <div class='row'>
              <div class="header_institucional">                
                <img src="{base_url}dashboard/assets/img/logo_presidencia.png" class="presidencia_logo img-thumbnail">
              </div>
        </div>

        
        <br />
        <br />
        <br />
        <br/>
        <!-- ============= Formulario  -->
        <div class='row' style="padding:30px" >
            <h3></h3>
                <h1>Certificado PyME</h1>
                <br />
                
            <div class='col-md-12' style="background-color: #f5f5f5;
    border: 1px solid #e3e3e3;
    border-radius: 4px;
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05) inset;
    margin-bottom: 20px;
    min-height: 20px;
    padding: 19px;">
                
                  
                <ul>
                    <li>CUIT: <strong>{cuit}</strong></li>
                    <li>Razón Social: <strong>{razon_social}</strong></li>
                    <li>Actividades: <strong>{descripcionActividadPrincipal}</strong></li>
                    <li>Sector: <strong>{sector}</strong></li>
                    <li>Clasificación Pyme: <strong>{categoria}</strong></li>
                    <li>Fecha de Emisión: <strong>{fecha_emision}</strong></li>
                    <li>Fecha de validez: <strong>{fecha_validez}</strong></li>
                </ul>
                    <img src="{base_url}afip/consultas/gen_url/{qr_url}">


        </div>
       </div>
    </div>
  
</body>

</html>