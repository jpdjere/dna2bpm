<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>CONSTANCIA CATEGORIZACIÓN MYPYME</title>

    <!-- Bootstrap Core CSS -->
    <link href="{base_url}afip/assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{base_url}afip/assets/css/style2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'>


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div class="container">
        <div class="row rt50">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-lg-6 col-sm-8 col-xs-8">
                                <a href="/"><img alt="img" src="{base_url}afip/assets/images/pyme.jpg" class="img-responsive pull-left no-displayblock pyme"></a>
                            </div>
                            <div class="col-lg-6 col-sm-4 col-xs-4">
                                 <a href="/"><img alt="img" src="{base_url}afip/assets/images/ministerio_logo.jpg" class="img-responsive pull-right no-displayblock logo"></a>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <h2 class="text-center titulo">CONSTANCIA CATEGORIZACIÓN MYPYME</h2>
                        <hr style="border-color: #a8dff7">
                        <div class="row">
                            <div class="col-md-7 col-sm-7">
                                <p class="info">Fecha de emisión: {fecha_emision}</p>
                            </div>
                            <div class="col-md-5 col-sm-5">
                                <p class="info">Vigencia hasta: {fecha_validez}</p>
                            </div>
                        </div>    
                        <div class="row rt20">
                            <div class="col-md-12">
                                <p class="info">CUIT: {cuit}</p>
                            </div>
                        </div>
                        <div class="row rt20">
                            <div class="col-md-12">
                                <p class="info">Razón Social: {razon_social}</p>
                            </div>
                        </div>
                        <hr style="border-color: #a8dff7">
                        <div class="row">
                            <div class="col-md-12 pad-5">
                                <div class="panel panel-info">
                                    <div class="panel-heading titulo-tabla-pad">
                                        <h3 class="panel-title text-center info titulo-tabla">Categorización</h3>
                                    </div>
                                    <div class="col-md-4 col-sm-4 pad-left-0">
                                        <div class="panel panel-default no-border">
                                            <div class="panel-heading titulo-tabla-pad no-border-bottom">
                                                <h3 class="panel-title text-center info">Sector</h3>
                                            </div>
                                            <div class="panel-body panel-border">Lorem ipsum dolor  ametelit</div>
                                        </div>
                                    </div>
                                     <div class="col-md-4 col-sm-4 pad-left-0">
                                        <div class="panel panel-default no-border">
                                            <div class="panel-heading titulo-tabla-pad no-border-bottom">
                                                <h3 class="panel-title text-center info">Tramo</h3>
                                            </div>
                                            <div class="panel-body panel-border">Lorem ipsum dolor  ametelit</div>
                                        </div>
                                    </div>
                                     <div class="col-md-4 col-sm-4 pad-left-0 pad-right-0">
                                        <div class="panel panel-default no-border">
                                            <div class="panel-heading titulo-tabla-pad no-border-bottom">
                                                <h3 class="panel-title text-center info">Actividad principal</h3>
                                            </div>
                                            <div class="panel-body panel-border">{descripcionActividadPrincipal}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="well info-well rt20 border-well">La presente categorización fue realizada en el marco de la Resolución 24/2001 de la ex Secretería de la Pequeña y Mediana Empresa y sus modificaciones, de conformidad a la información aportada por Ud. en carácter de declaración jurada. En caso de detectarse alguna falsedad en dicha declaración, esta categorización quedará sin efecto automáticamente con la consecuente exclusión de todo beneficio que la misma pudiera haber generado y sin perjuicio de las demás sanciones y consecuencias que pudieran corresponder en virtud de la normativa aplicable.
                        </div>
                        <div class="row rt50 r10">
                            <div class="col-md-3 col-md-offset-6 col-sm-4 col-sm-offset-5">
                                <p  class="info r0 p-qr">Código QR</p>
                            </div>
                            <div class="col-md-3 col-sm-3">
                                 <img src="{base_url}afip/consultas/gen_url/{qr_url}" class="img-thumbnail">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.container -->
        
</body>

</html>