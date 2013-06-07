<!DOCTYPE html>
<html>
    <head>
        <title>{title}</title>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <meta name="description" content="">
        <meta name="author" content="">
        <link href="{base_url}jscript/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="{base_url}jscript/fontawesome/css/font-awesome.min.css" />
        <!-- CSS -->
        {css}

        <link href="{base_url}jscript/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
    </head>
    <body>
        {content}
        <!-- Boot -->
        <script type="text/javascript">
            //-----declare global vars
            var globals={inline_js};
        </script>
        <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
        <script type="text/javascript" src="{base_url}jscript/jquery/jquery.min.js"></script>
        <!-- <script type="text/javascript" src="{base_url}jscript/bootstrap/js/bootstrap.min.js"></script> -->
        <script type="text/javascript" src="{module_url}assets/jscript/jquery.ui.map.full.min.js"></script>
        
        {js}
    </body>
</html>