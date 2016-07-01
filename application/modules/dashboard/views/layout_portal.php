<?php 
/* 
 *  Header : CSS Load & some body
 * 
 */


?>
 <!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>{title}</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!--====== CSS BASE ===== -->
        <!-- bootstrap 3.0.2 -->
        <link href="{base_url}dashboard/assets/bootstrap-wysihtml5/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="{base_url}dashboard/assets/bootstrap-wysihtml5/css/font-awesome-4.4.0/css/font-awesome.css" rel="stylesheet" type="text/css" />
        <!-- Ionicons -->
        <link href="{base_url}dashboard/assets/bootstrap-wysihtml5/css/ionicons.min.css" rel="stylesheet" type="text/css" />
        <!-- Daterange picker -->
        <link href="{base_url}dashboard/assets/bootstrap-wysihtml5/css/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
        <!-- bootstrap wysihtml5 - text editor -->
        <link href="{base_url}dashboard/assets/bootstrap-wysihtml5/css/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="{base_url}dashboard/assets/bootstrap-wysihtml5/css/AdminLTE.css" rel="stylesheet" type="text/css" />
        <!--  Juery UI css -->
        <link href="{base_url}jscript/jquery/ui/jquery-ui-1.10.2.custom/css/smoothness/jquery-ui-1.10.2.custom.min.css" rel="stylesheet" type="text/css" />
        <!--  iCheck -->
        <link href="{base_url}dashboard/assets/bootstrap-wysihtml5/css/iCheck/minimal/blue.css" rel="stylesheet" type="text/css" />

        <!--====== Font Kits ===== -->
        <link href="{base_url}dashboard/assets/fonts/webfontkit-20140806-113318/stylesheet.css" rel="stylesheet" type="text/css" />
        <link href="{base_url}dashboard/assets/fonts/Droid-Sans-fontfacekit/web_fonts/droidsans_regular_macroman/stylesheet.css" rel="stylesheet" type="text/css" />   

        <!--====== CSS for widgets ===== -->
        {widgets_css}

        <!-- overload css skins -->
        <link href="{base_url}dashboard/assets/css/style.css" rel="stylesheet" type="text/css" />



        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
        {custom_css}
    </head>
     <body class="skin-blue sidebar-collapse fixed">

        <!-- ======== HEADER ======== -->   

        <header class="header">
            <div href="{base_url}" class="logo" style="background-color:#3C8DBC">
                <!-- Add the class icon to your logo image or logo icon to add the margining -->
               <form class="form-inline">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon"><i class="fa fa-search" aria-hidden="true"></i></div>
                      <input type="text" class="form-control" id="exampleInputAmount" placeholder="Buscar">
                    </div>
                  </div>
                </form>
            </div>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation" style="border-bottom:0px">

                <div class="navbar-right">
                    <ul class="nav navbar-nav">

                    <li class="messages-menu" >
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"> 
                            <i class="fa fa-flag-o" aria-hidden="true"></i>
                        </a>
                    </li>


                    <li class="messages-menu" >
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"> 
                            <i class="fa fa-comments-o" aria-hidden="true"></i>
                        </a>
                    </li>

                    <li class="messages-menu" >
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"> 
                           <i class="fa fa-user-plus" aria-hidden="true"></i>
                        </a>
                    </li>
                        <!-- Messages: style can be found in dropdown.less-->
                     
                        {toolbar_inbox}
                    
                        

                        <!-- ========== USER PROFILE  ==========-->
                        <li class="dropdown user user-menu">
                            
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                
              
                                <span>{name} <i class="caret"></i></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header bg-light-blue">
                                    <img src="{avatar}" class="avatar" alt="User Image" />
                                    <p>
                                        {name}
                                    </p>
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="{base_url}dashboard/profile" class="btn btn-default btn-flat">{lang user_profile}</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="{base_url}user/logout" class="btn btn-default btn-flat">{lang user_logout}</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <li><img src="{avatar}" class="avatar" alt="User Image" style="float:right;height:50px;width:50px;margin-left:8px;"/></li>
                        <!-- ++++ USER PROFILE -->
                    </ul>
                </div>
            </nav>
        </header>

        <!-- ++++++++ HEADER  -->        
        <div class="wrapper row-offcanvas row-offcanvas-left hidden-print"><!-- Wrapper -->



            <!-- ======== CONTENT AREA ======== --> 
            <aside class="right-side strech" >
                <!-- Content Header (Page header) -->
                <section class="content-header" style="padding-top:0px;margin-bottom:50px;">

                    <nav class="navbar navbar-default navbar-static-top" style="background-color:#B9B9B9">
                      <div class="container-fluid">
                        <!-- Brand and toggle get grouped for better mobile display -->
                        <div class="navbar-header">
                          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                          </button>
                         <!--  <a class="navbar-brand" href="#"></a> -->
                        </div>

                        <!-- Collect the nav links, forms, and other content for toggling -->
                        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                          <ul class="nav navbar-nav">
                            <li><a href="#">Inicio</a></li>
                            <li><a href="#">Perfil</a></li>
                            <li><a href="#">Mi red</a></li>
                            <li><a href="#">Mis programas</a></li>
                            <li><a href="#">Mis Intereses</a></li>
                          </ul>
                        </div><!-- /.navbar-collapse -->
                      </div><!-- /.container-fluid -->
                    </nav>
                    <!-- menu -->
                </section>

                <section class="content">
 
                    {alerts}

                    <div id="tiles_after">
                        <section class="col-lg-12 connectedSortable ui-sortable">
                            {tiles_after}
                        </section>
                    </div>


                    <section class="col-lg-6 connectedSortable ui-sortable" id="col1">
                        {col1}
                    </section>
                    <section class="col-lg-6 connectedSortable ui-sortable" id="col2">
                        {col2}
                    </section>
                </section>
        
             </div>        


            </aside>
            <!-- ++++++++ CENTRO  -->
        </div><!-- /Wrapper -->

        
<?php 
/* 
 *  FOOTER 
 * 
 */
include('_footer.php')

?>
    