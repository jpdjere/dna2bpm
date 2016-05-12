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
    <link href="{base_url}/dashboard/assets/css/style.css" rel="stylesheet">
    <link href="{base_url}gestion/assets/css/style.css" rel="stylesheet">
    <!-- font Awesome -->
    <link href="{base_url}dashboard/assets/bootstrap-wysihtml5/css/font-awesome-4.4.0/css/font-awesome.css" rel="stylesheet" type="text/css" />

    <!-- Daterange picker -->
    <link href="{base_url}/dashboard/assets/bootstrap-wysihtml5/css/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />

    <!-- overload css skins -->
    <link href="{base_url}/dashboard/assets/css/style.css" rel="stylesheet" type="text/css" />
    <!-- CSS:fullcalendar -->
    <link rel='stylesheet' type='text/css' href='{base_url}/dashboard/assets/bootstrap-wysihtml5/js/plugins/fullcalendar-2.3.1/fullcalendar.css' />
    <!-- CSS:daterangerpicker -->
    <link rel='stylesheet' type='text/css' href='{base_url}/dashboard/assets/bootstrap-wysihtml5/css/daterangepicker/daterangepicker.css' />


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script> --
          <![endif]-->
</head>

<body>


    <div class='container'>
        <!-- ============= Barra Ministerio  -->

        <div class='row'>
            {logobar}
        </div>
        <!-- ============= Formulario  -->

        <div class='row'>
            <div class='col-md-12'>
                <h2>ADMINISTRACION DE</h2>
                <h3>Solicitud de anticipo de viaticos y ordenes de pasaje</h3>


                <div class="box box-solid box-info">


                    <!-- /.box-header -->
                    <div style="display: block;" class="box-body">
                        <div style="position: relative; overflow: hidden; width: auto; height: 590px;" class="slimScrollDiv">
                            <section style="overflow: hidden; width: auto; height: 590px;" class="sidebar">
                                <ul class="sidebar-menu">

                                    <li class="treeview">
                                        <a href="#">
                                            <i class="fa fa-laptop"></i>
                                            <span>INTERIOR</span>
                                            <i class="fa pull-right fa-angle-left"></i>
                                        </a>
                                        <ul style="display: none;" class="treeview-menu">

                                            {table_interior}
                                        </ul>
                                    </li>
                                    
                                    <li class="treeview">
                                        <a href="#">
                                            <i class="fa fa-laptop"></i>
                                            <span>EXTERIOR</span>
                                            <i class="fa pull-right fa-angle-left"></i>
                                        </a>
                                        <ul style="display: none;" class="treeview-menu">

                                            {table_exterior}
                                        </ul>
                                    </li>


                                </ul>
                            </section>
                            <div style="background: rgba(0, 0, 0, 0.2) none repeat scroll 0% 0%; width: 7px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 0px; z-index: 99; right: 1px; height: 419.904px;" class="slimScrollBar"></div>
                            <div style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 0px; background: rgb(51, 51, 51) none repeat scroll 0% 0%; opacity: 0.2; z-index: 90; right: 1px;" class="slimScrollRail"></div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div style="display: block;" class="box-footer clearfix no-border">
                        <!--        <button class="btn btn-default pull-right"><i class="fa fa-plus"></i> Add item</button>-->
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
        <script src="{base_url}/jscript/jquery/jquery.min.js"></script>
        <script src="{base_url}/dashboard/assets/bootstrap-wysihtml5/js/bootstrap.min.js"></script>
        <script src="{base_url}/jscript/jquery/plugins/jquery-validation-1.15.0/jquery.validate.min.js"></script>
        <script src="{base_url}/jscript/jquery/plugins/jquery-validation-1.15.0/localization/messages_es_AR.js"></script>
        <script src="{base_url}/gestion/assets/jscript/form_viaticos.js"></script>

        <!--CALENDAR -->
        <script src='{base_url}/jscript/jquery/ui/jquery-ui-1.10.2.custom/jquery-ui-1.10.2.custom.min.js'></script>
        <script src='{base_url}/dashboard/assets/bootstrap-wysihtml5/js/AdminLTE/app.js'></script>
        <script src='{base_url}/jscript/jquery/plugins/Form/jquery.form.min.js'></script>
       
        <!-- JS custom -->
        <!-- JS:calendar-JS -->
        <script src='{base_url}/calendar/assets/jscript/app.js'></script>


</body>

</html>